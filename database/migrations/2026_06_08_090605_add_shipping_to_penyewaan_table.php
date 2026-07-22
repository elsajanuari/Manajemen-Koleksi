<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            // ── Zona & Ongkir ─────────────────────────────────────────
            // FK ke shipping_zones (tabel yang sudah ada dari pembelian)
            $table->foreignId('shipping_zone_id')
                  ->nullable()
                  ->constrained('shipping_zones')
                  ->nullOnDelete()
                  ->after('payment_reference');

            // Ongkir snapshot saat diverifikasi (0 jika pengelola/gratis)
            $table->decimal('shipping_cost', 15, 0)->default(0)->after('shipping_zone_id');

            // Metode pengiriman: 'courier' atau 'manager' — ditentukan pengelola saat verifikasi
            $table->enum('shipping_method_type', ['courier', 'manager'])
                  ->nullable()
                  ->after('shipping_cost');

            // Nama kurir jika metode = courier (mis: "JNE", "JNT")
            $table->string('courier_name', 100)->nullable()->after('shipping_method_type');

            // Layanan kurir (mis: "REG", "OKE")
            $table->string('courier_service', 100)->nullable()->after('courier_name');

            // Estimasi tiba dari kurir
            $table->string('courier_etd', 50)->nullable()->after('courier_service');

            // City ID tujuan dari RajaOngkir — untuk fetch tarif kurir saat approve
            $table->unsignedInteger('destination_city_id')->nullable()->after('courier_etd');

            // Nama kota teks untuk Binderbyte (mis: "bogor", "jakarta pusat")
            $table->string('city_name', 100)->nullable()->after('destination_city_id');

            // Total yang harus dibayar = subtotal sewa + deposit + ongkir
            // Kolom ini menggantikan kalkulasi manual di blade
            $table->decimal('total_bayar', 15, 0)->default(0)->after('city_name');

            // Catatan pengelola untuk penyewa (opsional, ditampilkan di notifikasi/invoice)
            $table->text('catatan_pengelola')->nullable()->after('total_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropForeign(['shipping_zone_id']);
            $table->dropColumn([
                'shipping_zone_id',
                'shipping_cost',
                'shipping_method_type',
                'courier_name',
                'courier_service',
                'courier_etd',
                'destination_city_id',
                'city_name',
                'total_bayar',
                'catatan_pengelola',
            ]);
        });
    }
};