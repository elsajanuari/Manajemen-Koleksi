<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('perawatan_koleksis', function (Blueprint $table) {
            $table->foreignId('created_by')
                ->nullable()
                ->after('penanggung_jawab_user_id')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('perawatan_koleksis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
        });
    }
};
