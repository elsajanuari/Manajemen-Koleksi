<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('koleksis', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('nama');
            $table->decimal('daily_rate', 15, 2)->default(0)->after('price');
            $table->decimal('sale_price', 15, 2)->nullable()->after('daily_rate');
            $table->boolean('for_sale')->default(false)->after('sale_price');
            $table->boolean('for_rent')->default(false)->after('for_sale');
            $table->integer('weight_gram')->nullable()->after('for_rent');
            $table->boolean('available')->default(true)->after('weight_gram');
            $table->json('gallery_paths')->nullable()->after('foto');
            $table->json('extra_info')->nullable()->after('gallery_paths');
        });
    }

    public function down(): void
    {
        Schema::table('koleksis', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'daily_rate', 'sale_price', 'for_sale', 'for_rent',
                'weight_gram', 'available', 'gallery_paths', 'extra_info',
            ]);
        });
    }
};
