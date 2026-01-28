<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Vehicle;

class CatalogController extends Controller
{
    // Főoldal: felső szint (pl. Audi, BMW)
    public function index()
    {
        $categories = Category::whereNull('parent_id')
            ->with('children.children') // 3 szint
            ->orderBy('name')
            ->get();

        return view('catalog.index', compact('categories'));
    }

    // Egy konkrét kategória (pl. 8P)
    public function category(Category $category)
    {
        $vehicles = Vehicle::orderBy('make')->orderBy('model')->get();

        return view('catalog.category', compact('category', 'vehicles'));
    }
}
