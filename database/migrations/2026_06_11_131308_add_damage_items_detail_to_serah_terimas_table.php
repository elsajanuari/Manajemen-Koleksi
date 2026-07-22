<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            // Sudah ada kolom? Skip (idempotent)
            if (! Schema::hasColumn('serah_terimas', 'damage_items_detail')) {
                $table->json('damage_items_detail')->nullable()->after('damage_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->dropColumn('damage_items_detail');
        });
    }
};