<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with([
                'brand:id,name',
                'category:id,name,parent_id',
                'images:id,product_id,path,sort',
            ])
            ->where('is_active', true);

        // Keresés
        if ($search = trim((string) $request->get('q', ''))) {
            $query->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('oem_number', 'like', "%{$search}%");
            });
        }

        // Szűrők
        if ($categoryId = $request->integer('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($brandId = $request->integer('brand_id')) {
            $query->where('brand_id', $brandId);
        }

        if ($vehicleId = $request->integer('vehicle_id')) {
            $query->whereHas('vehicles', fn ($v) => $v->where('vehicles.id', $vehicleId));
        }

        if ($request->boolean('in_stock')) {
            $query->whereHas('stocks', fn ($s) => $s->where('qty', '>', 0));
        }

        $products = $query->latest('id')->paginate(24)->withQueryString();

        // Option listák
        $categories = Category::query()
            ->orderBy('parent_id')
            ->orderBy('sort')
            ->get(['id', 'parent_id', 'name', 'sort']);

        $brands = Brand::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $vehicles = Vehicle::query()
            ->orderBy('make')
            ->orderBy('model')
            ->orderBy('engine')
            ->limit(300)
            ->get(['id', 'make', 'model', 'engine', 'year_from', 'year_to']);

        return view('catalog.index', compact('products', 'categories', 'brands', 'vehicles'));
    }
}
