<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conservation_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('koleksi_id')->constrained('koleksis')->cascadeOnDelete();
            $table->foreignId('kondisi_koleksi_id')->constrained('kondisi_koleksis')->cascadeOnDelete();
            $table->foreignId('perawatan_koleksi_id')->constrained('perawatan_koleksis')->cascadeOnDelete();
            $table->string('jenis_konservasi'); // preventif | kuratif
            $table->string('status')->default('direncanakan'); // direncanakan | sedang_berjalan | selesai
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('perawatan_koleksi_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conservation_actions');
    }
};