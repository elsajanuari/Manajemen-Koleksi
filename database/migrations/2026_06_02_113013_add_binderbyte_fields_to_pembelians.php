<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom city_name dan province_id untuk integrasi Binderbyte.
     * destination_city_id (RajaOngkir) tidak dihapus untuk backward compatibility.
     */
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            // Nama kota teks untuk Binderbyte cost API (contoh: "bogor", "jakarta pusat")
            $table->string('city_name', 100)->nullable()->after('kode_pos');

            // ID provinsi dari Binderbyte (contoh: "32" untuk Jawa Barat)
            $table->string('province_id', 10)->nullable()->after('city_name');
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn(['city_name', 'province_id']);
        });
    }
};