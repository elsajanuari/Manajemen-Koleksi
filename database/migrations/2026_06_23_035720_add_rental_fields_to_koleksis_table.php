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
            if (!Schema::hasColumn('koleksis', 'slug'))
                $table->string('slug')->unique()->nullable()->after('nama');
            if (!Schema::hasColumn('koleksis', 'daily_rate'))
                $table->decimal('daily_rate', 15, 2)->default(0)->after('price');
            if (!Schema::hasColumn('koleksis', 'sale_price'))
                $table->decimal('sale_price', 15, 2)->nullable()->after('daily_rate');
            if (!Schema::hasColumn('koleksis', 'for_sale'))
                $table->boolean('for_sale')->default(false)->after('sale_price');
            if (!Schema::hasColumn('koleksis', 'for_rent'))
                $table->boolean('for_rent')->default(false)->after('for_sale');
            if (!Schema::hasColumn('koleksis', 'weight_gram'))
                $table->integer('weight_gram')->nullable()->after('for_rent');
            if (!Schema::hasColumn('koleksis', 'available'))
                $table->boolean('available')->default(true)->after('weight_gram');
            if (!Schema::hasColumn('koleksis', 'gallery_paths'))
                $table->json('gallery_paths')->nullable()->after('foto');
            if (!Schema::hasColumn('koleksis', 'extra_info'))
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
