<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            // Zona & ongkir — snapshot saat invoice dibuat
            $table->foreignId('shipping_zone_id')->nullable()->constrained('shipping_zones')->nullOnDelete()->after('total_bayar');
            $table->decimal('shipping_cost', 15, 0)->default(0)->after('shipping_zone_id');
            // shipping_method sudah ada di model (delivery_method), tapi kita tambah
            // kolom khusus untuk method saat pengajuan (courier / manager)
            $table->enum('shipping_method_type', ['courier', 'manager'])->nullable()->after('shipping_cost');
            $table->string('courier_name')->nullable()->after('shipping_method_type'); // "JNE", "JNT", null jika pengelola

            // Ubah kota_kabupaten menjadi lebih spesifik (sudah ada, tidak perlu tambah)
            // Tambah kolom untuk kabupaten/kota yang dipilih via dropdown khusus Purwakarta
            $table->string('kecamatan')->nullable()->after('kode_pos'); // opsional untuk detail
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropForeign(['shipping_zone_id']);
            $table->dropColumn([
                'shipping_zone_id',
                'shipping_cost',
                'shipping_method_type',
                'courier_name',
                'kecamatan',
            ]);
        });
    }
};