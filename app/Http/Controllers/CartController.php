<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request, CartService $cart)
    {
        $c = $cart->getOrCreateCart($request)->load('items.product');
        return view('store.cart', [
            'cart' => $c,
            'totals' => $cart->totals($c),
        ]);
        
    }

    public function add(Request $request, CartService $cart)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'qty' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        $cart->add($request, $product, (int) ($data['qty'] ?? 1));

        return redirect('/kosar')->with('ok', 'Hozzáadva a kosárhoz.');
    }

    public function updateQty(Request $request, CartService $cart)
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer'],
            'qty' => ['required', 'integer', 'min:0'],
        ]);

        $cart->updateQty($request, (int) $data['item_id'], (int) $data['qty']);

        return redirect('/kosar');
    }

    public function remove(Request $request, CartService $cart)
    {
        $data = $request->validate([
            'item_id' => ['required', 'integer'],
        ]);

        $cart->remove($request, (int) $data['item_id']);

        return redirect('/kosar');
    }
}
