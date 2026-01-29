<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load([
            'brand',
            'category',
            'images' => fn ($q) => $q->orderBy('sort'),
            'references',
            'vehicles',
            'prices' => fn ($q) => $q->orderByDesc('valid_from'),
            'stocks.warehouse',
        ]);

        return view('products.show', compact('product'));
    }
}
