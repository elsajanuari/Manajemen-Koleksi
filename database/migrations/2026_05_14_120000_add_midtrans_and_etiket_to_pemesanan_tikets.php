<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan_tikets', function (Blueprint $table) {
            $table->string('midtrans_order_id')->nullable()->unique()->after('tanggal_bayar');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->string('midtrans_payment_type', 64)->nullable()->after('midtrans_transaction_id');
            $table->string('tiket_verifikasi_token', 64)->nullable()->unique()->after('midtrans_payment_type');
            $table->timestamp('tiket_terpakai_at')->nullable()->after('tiket_verifikasi_token');
            $table->foreignId('tiket_diverifikasi_oleh')->nullable()->after('tiket_terpakai_at')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan_tikets', function (Blueprint $table) {
            $table->dropForeign(['tiket_diverifikasi_oleh']);
            $table->dropColumn([
                'midtrans_order_id',
                'midtrans_transaction_id',
                'midtrans_payment_type',
                'tiket_verifikasi_token',
                'tiket_terpakai_at',
                'tiket_diverifikasi_oleh',
            ]);
        });
    }
};
