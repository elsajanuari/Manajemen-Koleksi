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
        Schema::table('paintings', function (Blueprint $table) {
            $table->boolean('for_sale')->default(false)->after('sale_price');
        });
    }

    public function down(): void
    {
        Schema::table('paintings', function (Blueprint $table) {
            $table->dropColumn('for_sale');
        });
    }
};
