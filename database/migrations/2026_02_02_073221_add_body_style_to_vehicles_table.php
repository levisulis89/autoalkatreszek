<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('vehicles', function (Blueprint $table) {
        $table->string('body_style')->nullable()->after('engine'); // pl. Ferdehátú, Kombi, Sedan
    });
}

public function down(): void
{
    Schema::table('vehicles', function (Blueprint $table) {
        $table->dropColumn('body_style');
    });
}
};
