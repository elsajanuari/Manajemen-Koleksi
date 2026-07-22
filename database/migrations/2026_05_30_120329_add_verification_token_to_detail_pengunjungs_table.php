<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_pengunjungs', function (Blueprint $table) {
            $table->string('tiket_verifikasi_token', 100)->nullable()->unique()->after('tipe_pengunjung');
            $table->timestamp('tiket_terpakai_at')->nullable()->after('tiket_verifikasi_token');
        });
    }

    public function down(): void
    {
        Schema::table('detail_pengunjungs', function (Blueprint $table) {
            $table->dropColumn(['tiket_verifikasi_token', 'tiket_terpakai_at']);
        });
    }
};