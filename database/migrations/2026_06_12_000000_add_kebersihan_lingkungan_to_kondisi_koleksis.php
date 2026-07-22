<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kondisi_koleksis', function (Blueprint $table) {
            $table->string('kebersihan_lingkungan')->nullable()->after('jenis_kerusakan')->comment('Penilaian kebersihan lingkungan: baik/cukup/buruk');
        });

        try {
            DB::statement("UPDATE kondisi_koleksis SET kebersihan_lingkungan = kondisi_lingkungan WHERE kondisi_lingkungan IN ('baik','cukup','buruk')");
        } catch (\Throwable $e) {
        }
    }

    public function down(): void
    {
        Schema::table('kondisi_koleksis', function (Blueprint $table) {
            $table->dropColumn('kebersihan_lingkungan');
        });
    }
};
