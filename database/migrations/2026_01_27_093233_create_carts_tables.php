<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $t) {
            $t->id();

            $t->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $t->string('token')->nullable()->unique();

            $t->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $t) {
            $t->id();

            $t->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();

            $t->unsignedInteger('price_gross');
            $t->string('currency', 3)->default('HUF');

            $t->unsignedInteger('qty')->default(1);

            // snapshot (hogy később is stimmeljen)
            $t->string('product_name');
            $t->string('product_sku');

            $t->timestamps();

            $t->unique(['cart_id', 'product_id']);
        });

        Schema::create('cart_item_attributes', function (Blueprint $t) {
            $t->id();

            $t->foreignId('cart_item_id')->constrained()->cascadeOnDelete();

            $t->string('key');
            $t->string('value');

            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_item_attributes');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
