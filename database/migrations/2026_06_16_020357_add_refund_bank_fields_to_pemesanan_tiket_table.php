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
        Schema::table('pemesanan_tikets', function (Blueprint $table) {
            $table->string('nama_bank_refund')->nullable()->before('no_rekening_refund');
            $table->string('atas_nama_refund')->nullable()->after('nama_bank_refund');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan_tikets', function (Blueprint $table) {
            $table->dropColumn(['nama_bank_refund', 'atas_nama_refund']);
        });
    }
};
