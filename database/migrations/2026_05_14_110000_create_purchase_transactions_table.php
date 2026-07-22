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
        Schema::create('purchase_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('koleksi_id')->constrained('koleksis')->onDelete('restrict');
            $table->string('buyer_name');
            $table->string('buyer_email');
            $table->string('buyer_phone');
            $table->text('buyer_address');
            $table->decimal('price', 12, 2);
            $table->text('notes')->nullable();
            $table->enum('status', ['menunggu_verifikasi', 'diproses', 'dibatalkan', 'selesai'])->default('menunggu_verifikasi');
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index('transaction_code');
            $table->index('user_id');
            $table->index('koleksi_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_transactions');
    }
};
