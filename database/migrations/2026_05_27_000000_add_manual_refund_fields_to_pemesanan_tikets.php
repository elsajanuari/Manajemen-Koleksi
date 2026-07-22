<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pemesanan_tikets', function (Blueprint $table) {
            $table->string('no_rekening_refund')->nullable()->after('midtrans_refund_key');
            $table->string('bukti_pengembalian')->nullable()->after('no_rekening_refund');
            $table->timestamp('refund_requested_at')->nullable()->after('bukti_pengembalian');
            $table->timestamp('refund_completed_at')->nullable()->after('refund_requested_at');
        });

        DB::statement("ALTER TABLE `pemesanan_tikets` MODIFY `status` ENUM('pending','menunggu_pembayaran','lunas','proses_pembatalan','pengembalian_berhasil','dibatalkan') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemesanan_tikets', function (Blueprint $table) {
            $table->dropColumn([
                'no_rekening_refund',
                'bukti_pengembalian',
                'refund_requested_at',
                'refund_completed_at',
            ]);
        });

        DB::statement("ALTER TABLE `pemesanan_tikets` MODIFY `status` ENUM('pending','menunggu_pembayaran','lunas','dibatalkan') NOT NULL DEFAULT 'pending'");
    }
};
