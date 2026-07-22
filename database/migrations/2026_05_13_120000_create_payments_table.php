<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewaan_id')->constrained('penyewaan')->cascadeOnDelete();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->string('order_id')->unique();
            $table->string('transaction_id')->nullable();
            $table->string('payment_type')->nullable();
            $table->unsignedBigInteger('gross_amount')->nullable();
            $table->string('transaction_status')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
