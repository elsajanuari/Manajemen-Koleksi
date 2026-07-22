<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perawatan_koleksis', function (Blueprint $table) {
            $table->foreignId('kondisi_koleksi_id')
                ->nullable()
                ->after('koleksi_id')
                ->constrained('kondisi_koleksis')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('perawatan_koleksis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kondisi_koleksi_id');
        });
    }
};
