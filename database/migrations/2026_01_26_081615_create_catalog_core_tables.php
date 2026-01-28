<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | BRANDS
        |--------------------------------------------------------------------------
        */
        Schema::create('brands', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->string('slug')->unique();
            $t->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | CATEGORIES (unix-fa)
        | Audi -> A3 -> 8P
        |--------------------------------------------------------------------------
        */
        Schema::create('categories', function (Blueprint $t) {
            $t->id();

            $t->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $t->string('name');
            $t->string('slug');
            $t->unsignedInteger('sort')->default(0);

            $t->timestamps();

            // ugyanazon a szinten legyen egyedi
            $t->unique(['parent_id', 'slug']);
        });

        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */
        Schema::create('products', function (Blueprint $t) {
            $t->id();

            // opcionális, ha akarod külön a brandet
            $t->foreignId('brand_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // EZ A FONTOS: a legalsó category (pl. 8P)
            $t->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $t->string('sku')->unique();
            $t->string('name');
            $t->string('slug')->unique();
            $t->text('description')->nullable();

            $t->string('oem_number')->nullable();
            $t->json('attributes')->nullable();
            $t->boolean('is_active')->default(true);

            $t->timestamps();

            $t->index(['name']);
        });

        /*
        |--------------------------------------------------------------------------
        | PRODUCT IMAGES
        |--------------------------------------------------------------------------
        */
        Schema::create('product_images', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('path');
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | VEHICLES
        |--------------------------------------------------------------------------
        */
        Schema::create('vehicles', function (Blueprint $t) {
            $t->id();
            $t->string('make');
            $t->string('model');
            $t->string('engine')->nullable();
            $t->unsignedSmallInteger('year_from')->nullable();
            $t->unsignedSmallInteger('year_to')->nullable();
            $t->timestamps();

            $t->index(['make', 'model', 'engine']);
        });

        /*
        |--------------------------------------------------------------------------
        | PRODUCT <-> VEHICLE (pivot)
        |--------------------------------------------------------------------------
        */
        Schema::create('product_vehicle', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $t->timestamps();

            $t->unique(['product_id', 'vehicle_id']);
        });

        /*
        |--------------------------------------------------------------------------
        | WAREHOUSES
        |--------------------------------------------------------------------------
        */
        Schema::create('warehouses', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('code')->unique();
            $t->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | STOCKS
        |--------------------------------------------------------------------------
        */
        Schema::create('stocks', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $t->integer('qty')->default(0);
            $t->unsignedSmallInteger('lead_days')->default(1);
            $t->timestamps();

            $t->unique(['product_id', 'warehouse_id']);
        });

        /*
        |--------------------------------------------------------------------------
        | PRICES
        |--------------------------------------------------------------------------
        */
        Schema::create('prices', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('currency', 3)->default('HUF');
            $t->unsignedInteger('gross');
            $t->unsignedInteger('net')->nullable();
            $t->timestamp('valid_from')->nullable();
            $t->timestamp('valid_to')->nullable();
            $t->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | PRODUCT REFERENCES
        |--------------------------------------------------------------------------
        */
        Schema::create('product_references', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->string('type'); // OEM / EAN / ALT / SUPPLIER
            $t->string('value');
            $t->timestamps();

            $t->index(['type', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_references');
        Schema::dropIfExists('prices');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('product_vehicle');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
    }
};
