<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            // City ID tujuan dari RajaOngkir — untuk kalkulasi ongkir kurir di form approve
            $table->unsignedInteger('destination_city_id')->nullable()->after('shipping_zone_id');
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn('destination_city_id');
        });
    }
};