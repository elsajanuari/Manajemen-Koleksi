<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposit_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaan')->cascadeOnDelete();

            // Nominal
            $table->unsignedBigInteger('deposit_amount');       // total deposit
            $table->unsignedBigInteger('damage_deduction')->default(0); // potongan kerusakan
            $table->unsignedBigInteger('refund_amount');        // nominal yang dikembalikan

            // Rekening tujuan (snapshot dari penyewaan saat refund)
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder');

            // Data transfer
            $table->date('refund_date');
            $table->string('transfer_proof_path')->nullable(); // bukti transfer
            $table->text('notes')->nullable();

            // Status
            $table->enum('status', [
                'pending',
                'processed',
            ])->default('pending');

            $table->string('processed_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposit_refunds');
    }
};