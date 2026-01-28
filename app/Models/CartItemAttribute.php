<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItemAttribute extends Model
{
    protected $guarded = [];

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class);
    }
}
