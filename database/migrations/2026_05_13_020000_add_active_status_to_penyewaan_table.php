<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->string('status')->change();
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->enum('status', ['menunggu verifikasi', 'disetujui', 'dibatalkan', 'ditolak'])
                  ->default('menunggu verifikasi')
                  ->change();
        });
    }
};
