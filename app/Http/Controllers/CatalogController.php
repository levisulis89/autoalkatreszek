<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // Lépcső paraméterek
        $make   = trim((string) $request->get('make', ''));
        $model  = trim((string) $request->get('model', ''));
        $engine = trim((string) $request->get('engine', ''));
        $year   = $request->filled('year') ? (int) $request->get('year') : null;
        $body   = trim((string) $request->get('body', ''));

        // 1) Gyártók
        $makes = Vehicle::query()
            ->select('make')
            ->whereNotNull('make')->where('make', '!=', '')
            ->distinct()->orderBy('make')
            ->pluck('make');

        // 2) Modellek
        $models = collect();
        if ($make !== '') {
            $models = Vehicle::query()
                ->where('make', $make)
                ->select('model')
                ->whereNotNull('model')->where('model', '!=', '')
                ->distinct()->orderBy('model')
                ->pluck('model');
        }

        // 3) Motorok
        $engines = collect();
        if ($make !== '' && $model !== '') {
            $engines = Vehicle::query()
                ->where('make', $make)
                ->where('model', $model)
                ->select('engine')
                ->whereNotNull('engine')->where('engine', '!=', '')
                ->distinct()->orderBy('engine')
                ->pluck('engine');
        }

        // 4) Évjáratok (year_from..year_to-ból diszkrét lista)
        $years = collect();
        if ($make !== '' && $model !== '' && $engine !== '') {
            $ranges = Vehicle::query()
                ->where('make', $make)
                ->where('model', $model)
                ->where('engine', $engine)
                ->get(['year_from', 'year_to']);

            $set = [];
            foreach ($ranges as $vr) {
                $from = (int) ($vr->year_from ?? 0);
                $to   = (int) ($vr->year_to ?? $vr->year_from ?? 0);

                if ($from <= 0) continue;
                if ($to <= 0) $to = $from;
                if ($to < $from) $to = $from;

                // védelem
                $to = min($to, $from + 40);

                for ($y = $from; $y <= $to; $y++) $set[$y] = true;
            }
            ksort($set);
            $years = collect(array_keys($set));
        }

        // 5) Kivitel (body_style) – nálad most NULL, attól még mehet a “Mindegy”
        $bodies = collect();
        if ($make !== '' && $model !== '' && $engine !== '' && $year) {
            $bodies = Vehicle::query()
                ->where('make', $make)
                ->where('model', $model)
                ->where('engine', $engine)
                ->where(function ($q) use ($year) {
                    $q->whereNull('year_from')->orWhere('year_from', '<=', $year);
                })
                ->where(function ($q) use ($year) {
                    $q->whereNull('year_to')->orWhere('year_to', '>=', $year);
                })
                ->select('body_style')
                ->whereNotNull('body_style')->where('body_style', '!=', '')
                ->distinct()->orderBy('body_style')
                ->pluck('body_style');

            // ha minden NULL, akkor üres marad, ez oké
        }

        // kész-e a lépcső? (kivitel opcionális)
        $ready = ($make !== '' && $model !== '' && $engine !== '' && (int)$year > 0);

        return view('catalog.index', compact(
            'makes', 'models', 'engines', 'years', 'bodies',
            'make', 'model', 'engine', 'year', 'body',
            'ready'
        ));
    }

    // 2. oldal: jármű variáns lista
    public function vehicleIndex(Request $request)
    {
        $make   = trim((string) $request->get('make', ''));
        $model  = trim((string) $request->get('model', ''));
        $engine = trim((string) $request->get('engine', ''));
        $year   = $request->filled('year') ? (int) $request->get('year') : null;
        $body   = trim((string) $request->get('body', ''));

        // ha nincs kész, vissza a kiválasztóba
        if ($make === '' || $model === '' || $engine === '' || !$year) {
            return redirect()->route('catalog.index', $request->query());
        }

        $vehicles = Vehicle::query()
            ->where('make', $make)
            ->where('model', $model)
            ->where('engine', $engine)
            ->where(function ($q) use ($year) {
                $q->whereNull('year_from')->orWhere('year_from', '<=', $year);
            })
            ->where(function ($q) use ($year) {
                $q->whereNull('year_to')->orWhere('year_to', '>=', $year);
            })
            ->when($body !== '', fn($q) => $q->where('body_style', $body))
            ->orderBy('year_from')
            ->orderBy('year_to')
            ->orderBy('id')
            ->get();

        return view('catalog.vehicle', compact(
            'vehicles',
            'make','model','engine','year','body'
        ));
    }

    // 3. oldal: alkatrészek a kiválasztott vehicle-hoz
    public function parts(Request $request, Vehicle $vehicle)
    {
        $inStock = $request->boolean('in_stock');
        $q = trim((string)$request->get('q',''));

        $query = Product::query()
            ->with([
                'brand:id,name',
                'category:id,name,parent_id',
                'images:id,product_id,path,sort',
            ])
            ->where('is_active', true)
            ->whereHas('vehicles', fn($v) => $v->where('vehicles.id', $vehicle->id));

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('oem_number', 'like', "%{$q}%");
            });
        }

        if ($inStock) {
            $query->whereHas('stocks', fn ($s) => $s->where('qty', '>', 0));
        }

        $products = $query->latest('id')->paginate(24)->withQueryString();

        return view('catalog.parts', compact('vehicle', 'products', 'inStock', 'q'));
    }
}