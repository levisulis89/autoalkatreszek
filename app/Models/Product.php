<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;

    protected $guarded = [];

    protected $casts = [
        'attributes' => 'array',
        'is_active' => 'boolean',
    ];

    // Kapcsolatok
    public function brand() { return $this->belongsTo(Brand::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function images() { return $this->hasMany(ProductImage::class)->orderBy('sort'); }
    public function vehicles() { return $this->belongsToMany(Vehicle::class)->withTimestamps(); }
    public function stocks() { return $this->hasMany(Stock::class); }
    public function prices() { return $this->hasMany(Price::class); }
    public function references() { return $this->hasMany(ProductReference::class); }
    public function cartItems()
{
    return $this->hasMany(\App\Models\CartItem::class);
}


    // Scout index
    public function searchableAs(): string
    {
        return 'products';
    }

    public function toSearchableArray(): array
    {
        $refs = $this->references()->pluck('value')->take(50)->all();

        $stockQty = (int) $this->stocks()->sum('qty');
        $inStock  = $stockQty > 0;

        $price = $this->prices()->orderByDesc('valid_from')->value('gross');
        $vehicleIds = $this->vehicles()->pluck('vehicles.id')->all();

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'oem_number' => $this->oem_number,
            'references' => $refs,

            // filter/sort mezők (Meilisearch Enterprise)
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'vehicle_ids' => $vehicleIds,
            'in_stock' => $inStock,
            'stock_qty' => $stockQty,
            'price_gross' => (int) ($price ?? 0),

            // UI/keresés
            'brand_name' => $this->brand?->name,
            'category_name' => $this->category?->name,
        ];
    }

    // UI helper (a Blade-hez)
    public function availability(): array
    {
        $inStock = $this->stocks()->where('qty', '>', 0);

        return [
            'qty' => (int) $inStock->sum('qty'),
            'lead_days' => (int) ($inStock->min('lead_days') ?? 7),
        ];
    }
}
