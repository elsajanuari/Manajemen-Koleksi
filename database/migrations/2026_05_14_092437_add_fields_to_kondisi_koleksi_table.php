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
        Schema::table('kondisi_koleksis', function (Blueprint $table) {
            $table->foreignId('perawatan_id')
                  ->nullable()
                  ->constrained('perawatan_koleksis')
                  ->onDelete('set null');
            
            $table->boolean('is_manual')->default(false)->comment('true = pemeriksaan ad-hoc, false = dari jadwal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kondisi_koleksis', function (Blueprint $table) {
            $table->dropForeignIdFor('perawatan_id', 'perawatan_id');
            $table->dropColumn('perawatan_id');
            $table->dropColumn('is_manual');
        });
    }
};
