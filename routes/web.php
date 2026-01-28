<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Vehicle;

Route::view('/', 'welcome');

Route::get('/kereses', function (Request $r) {
    $q = trim((string) $r->get('q', ''));

    // Filter optionok
    $brands = Brand::query()->orderBy('name')->get(['id', 'name']);
    $categories = Category::query()->orderBy('name')->get(['id', 'name']);

    $brandId    = $r->filled('brand_id') ? (int) $r->input('brand_id') : null;
    $categoryId = $r->filled('category_id') ? (int) $r->input('category_id') : null;
    $vehicleId  = $r->filled('vehicle_id') ? (int) $r->input('vehicle_id') : null;
    $inStock    = (bool) $r->boolean('in_stock');

    // Jármű lista (MVP)
    $vehicles = Vehicle::query()
        ->orderBy('make')->orderBy('model')->orderBy('engine')
        ->limit(300)
        ->get(['id','make','model','engine','year_from','year_to']);

    // Pagináció
    $page = max(1, (int) $r->get('page', 1));
    $perPage = 24;

    // Enterprise filter string Meilihez
    $filters = [];
    if ($brandId)    $filters[] = "brand_id = {$brandId}";
    if ($categoryId) $filters[] = "category_id = {$categoryId}";
    if ($vehicleId)  $filters[] = "vehicle_ids = {$vehicleId}";
    if ($inStock)    $filters[] = "in_stock = true";
    $filterStr = !empty($filters) ? implode(' AND ', $filters) : null;

    // Meili -> IDs + total
    $ids = [];
    $total = 0;

    try {
        $builder = Product::search($q)->options([
            'filter' => $filterStr,
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage,
        ]);

        $ids = $builder->keys()->toArray();

        $raw = $builder->raw();
        $total = (int)($raw['estimatedTotalHits'] ?? $raw['nbHits'] ?? 0);

        $products = Product::query()
            ->with(['brand', 'category', 'images', 'stocks', 'prices', 'vehicles'])
            ->where('is_active', true)
            ->when(!empty($ids), fn($qq) => $qq->whereIn('id', $ids))
            ->get()
            ->sortBy(fn($p) => array_search($p->id, $ids, true))
            ->values();

    } catch (\Throwable $e) {
        $query = Product::query()
            ->with(['brand', 'category', 'images', 'stocks', 'prices', 'vehicles'])
            ->where('is_active', true);

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('oem_number', 'like', "%{$q}%");
            });
        }

        if ($brandId)    $query->where('brand_id', $brandId);
        if ($categoryId) $query->where('category_id', $categoryId);
        if ($vehicleId)  $query->whereHas('vehicles', fn($qq) => $qq->where('vehicles.id', $vehicleId));
        if ($inStock)    $query->whereHas('stocks', fn($qq) => $qq->where('qty', '>', 0));

        $paginator = $query->paginate($perPage);

        $products = collect($paginator->items());
        $total = $paginator->total();
        $page = $paginator->currentPage();
        $perPage = $paginator->perPage();
    }

    return view('store.search', [
        'q' => $q,
        'products' => $products,
        'total' => $total,
        'page' => $page,
        'perPage' => $perPage,
        'brands' => $brands,
        'categories' => $categories,
        'vehicles' => $vehicles,
        'filters' => [
            'brand_id' => $brandId,
            'category_id' => $categoryId,
            'vehicle_id' => $vehicleId,
            'in_stock' => $inStock,
        ],
    ]);
});

Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/category/{category}', [CatalogController::class, 'category'])->name('catalog.category');


// ✅ KOSÁR ROUTE-OK KÍVÜL (EZ A LÉNYEG!)
Route::get('/kosar', [CartController::class, 'show'])->name('cart.show');
Route::post('/kosar/hozzaad', [CartController::class, 'add'])->name('cart.add');
Route::post('/kosar/mennyiseg', [CartController::class, 'updateQty'])->name('cart.qty');
Route::post('/kosar/torol', [CartController::class, 'remove'])->name('cart.remove');
