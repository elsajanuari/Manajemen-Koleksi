<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('damage_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaan')->cascadeOnDelete();
            $table->string('invoice_number')->unique();

            // Detail kerusakan
            $table->string('damage_type');                          // jenis kerusakan
            $table->enum('damage_level', ['ringan', 'sedang', 'berat']); // tingkat kerusakan
            $table->unsignedBigInteger('restoration_cost');         // estimasi biaya restorasi
            $table->unsignedBigInteger('deposit_amount');           // deposit yg dimiliki penyewa
            $table->unsignedBigInteger('deposit_used')->default(0); // deposit yg dipakai menutup
            $table->unsignedBigInteger('additional_charge');        // selisih yang harus dibayar (0 jika deposit cukup)
            $table->text('damage_notes')->nullable();

            // Midtrans
            $table->string('order_id')->unique()->nullable();
            $table->string('snap_token')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('transaction_status')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('payload')->nullable();

            // Status invoice
            $table->enum('status', [
                'unpaid',
                'pending',
                'paid',
                'failed',
                'expired',
                'not_required',     // jika deposit cukup menutup kerusakan
            ])->default('unpaid');

            $table->string('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('damage_invoices');
    }
};