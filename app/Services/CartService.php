<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartService
{
    public const SESSION_KEY = 'cart_token';

    public function getOrCreateCart(Request $request): Cart
    {
        $user = $request->user();

        // 1) Ha be van jelentkezve: user cart
        if ($user) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id], [
                'token' => null,
            ]);

            // 2) Ha volt vendég kosár token: merge
            $token = $request->session()->get(self::SESSION_KEY);
            if ($token) {
                $guestCart = Cart::whereNull('user_id')->where('token', $token)->first();
                if ($guestCart && $guestCart->id !== $cart->id) {
                    $this->mergeCarts($guestCart, $cart);
                    $guestCart->delete();
                }
                $request->session()->forget(self::SESSION_KEY);
            }

            return $cart;
        }

        // Vendég: tokenes cart
        $token = $request->session()->get(self::SESSION_KEY);
        if (!$token) {
            $token = Str::uuid()->toString();
            $request->session()->put(self::SESSION_KEY, $token);
        }

        return Cart::firstOrCreate(['token' => $token, 'user_id' => null]);
    }

    public function add(Request $request, Product $product, int $qty = 1): Cart
    {
        $qty = max(1, $qty);

        $cart = $this->getOrCreateCart($request);

        // Legutolsó bruttó ár (ha nincs, akkor 0)
        $gross = (int) ($product->prices()->orderByDesc('valid_from')->value('gross') ?? 0);
        $currency = (string) ($product->prices()->orderByDesc('valid_from')->value('currency') ?? 'HUF');

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->qty += $qty;

            // ha közben változott az ár, itt dönthetsz: frissítsük-e? (most: nem)
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'qty' => $qty,

                // snapshot
                'price_gross' => $gross,
                'currency' => $currency,
                'product_name' => (string) $product->name,
                'product_sku' => (string) $product->sku,
            ]);
        }

        return $cart->load('items.product');
    }

    public function updateQty(Request $request, int $itemId, int $qty): Cart
    {
        $cart = $this->getOrCreateCart($request);
        $qty = max(0, $qty);

        $item = CartItem::where('cart_id', $cart->id)->where('id', $itemId)->firstOrFail();

        if ($qty === 0) {
            $item->delete();
        } else {
            $item->qty = $qty;
            $item->save();
        }

        return $cart->load('items.product');
    }

    public function remove(Request $request, int $itemId): Cart
    {
        $cart = $this->getOrCreateCart($request);
        CartItem::where('cart_id', $cart->id)->where('id', $itemId)->delete();
        return $cart->load('items.product');
    }

    public function totals(Cart $cart): array
    {
        $items = $cart->items;
        $subtotal = 0;
        $count = 0;

        foreach ($items as $it) {
            $subtotal += ((int) $it->price_gross) * ((int) $it->qty);
            $count += (int) $it->qty;
        }

        return [
            'items_count' => $count,
            'subtotal_gross' => $subtotal,
            'currency' => $items->first()->currency ?? 'HUF',
        ];
    }

    protected function mergeCarts(Cart $from, Cart $to): void
    {
        $from->load('items');
        foreach ($from->items as $it) {
            $existing = CartItem::where('cart_id', $to->id)
                ->where('product_id', $it->product_id)
                ->first();

            if ($existing) {
                $existing->qty += $it->qty;
                $existing->save();
                $it->delete();
            } else {
                $it->cart_id = $to->id;
                $it->save();
            }
        }
    }
}
