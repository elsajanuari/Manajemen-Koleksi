<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Tabel zona pengiriman ────────────────────────────────
        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('zone_name');           // "Zona 1", "Zona 2", dst
            $table->string('description');         // "Purwakarta (Kab + Kota)"
            $table->decimal('manager_rate', 15, 0)->default(0); // ongkir pengelola (bisa diubah admin)
            $table->boolean('is_free')->default(false);         // TRUE hanya Zona 1
            $table->timestamps();
        });

        // ── Mapping provinsi ke zona ────────────────────────────
        Schema::create('shipping_zone_provinces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('shipping_zones')->cascadeOnDelete();
            $table->string('province_name');       // nama provinsi
            $table->timestamps();
        });

        // ── Seed data zona default ───────────────────────────────
        $zones = [
            ['zone_name' => 'Zona 1', 'description' => 'Purwakarta (Kab + Kota)',                       'manager_rate' => 0,       'is_free' => true],
            ['zone_name' => 'Zona 2', 'description' => 'Jawa Barat (luar Purwakarta)',                  'manager_rate' => 500000,  'is_free' => false],
            ['zone_name' => 'Zona 3', 'description' => 'Jawa (luar Jabar) + Bali',                     'manager_rate' => 2000000, 'is_free' => false],
            ['zone_name' => 'Zona 4', 'description' => 'Luar Jawa (Sumatera, Kalimantan, dll)',         'manager_rate' => 5000000, 'is_free' => false],
            ['zone_name' => 'Zona 5', 'description' => 'Indonesia Timur (Maluku, Papua, dll)',          'manager_rate' => 5000000, 'is_free' => false],
        ];

        foreach ($zones as $zone) {
            DB::table('shipping_zones')->insert(array_merge($zone, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Zona 1 tidak perlu mapping provinsi (dideteksi dari kabupaten = Purwakarta)
        // Zona 2 = Jawa Barat
        $zone2Id = DB::table('shipping_zones')->where('zone_name', 'Zona 2')->value('id');
        DB::table('shipping_zone_provinces')->insert([
            ['zone_id' => $zone2Id, 'province_name' => 'Jawa Barat', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Zona 3 = Jawa (luar Jabar) + Bali
        $zone3Id = DB::table('shipping_zones')->where('zone_name', 'Zona 3')->value('id');
        $zone3Provinces = ['DKI Jakarta', 'Banten', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Bali'];
        foreach ($zone3Provinces as $prov) {
            DB::table('shipping_zone_provinces')->insert([
                'zone_id'       => $zone3Id,
                'province_name' => $prov,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // Zona 4 = Luar Jawa
        $zone4Id = DB::table('shipping_zones')->where('zone_name', 'Zona 4')->value('id');
        $zone4Provinces = [
            'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Kepulauan Riau',
            'Jambi', 'Sumatera Selatan', 'Kepulauan Bangka Belitung', 'Bengkulu', 'Lampung',
            'Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
            'Sulawesi Utara', 'Gorontalo', 'Sulawesi Tengah', 'Sulawesi Barat', 'Sulawesi Selatan', 'Sulawesi Tenggara',
            'Nusa Tenggara Barat', 'Nusa Tenggara Timur',
        ];
        foreach ($zone4Provinces as $prov) {
            DB::table('shipping_zone_provinces')->insert([
                'zone_id'       => $zone4Id,
                'province_name' => $prov,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // Zona 5 = Indonesia Timur
        $zone5Id = DB::table('shipping_zones')->where('zone_name', 'Zona 5')->value('id');
        $zone5Provinces = [
            'Maluku', 'Maluku Utara',
            'Papua', 'Papua Barat', 'Papua Selatan', 'Papua Tengah', 'Papua Pegunungan', 'Papua Barat Daya',
        ];
        foreach ($zone5Provinces as $prov) {
            DB::table('shipping_zone_provinces')->insert([
                'zone_id'       => $zone5Id,
                'province_name' => $prov,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_zone_provinces');
        Schema::dropIfExists('shipping_zones');
    }
};