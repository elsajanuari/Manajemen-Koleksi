<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('serah_terima_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serah_terima_id')->constrained('serah_terimas')->cascadeOnDelete();
            $table->string('status');
            $table->string('performed_by')->nullable();
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('serah_terima_logs');
    }
};
