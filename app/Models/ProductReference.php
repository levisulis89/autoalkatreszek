<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReference extends Model
{
    protected $guarded = [];
    public function product() { return $this->belongsTo(Product::class); }
}

