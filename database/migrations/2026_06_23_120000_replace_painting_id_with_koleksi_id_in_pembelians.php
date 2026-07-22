<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->unsignedBigInteger('koleksi_id')->nullable()->after('user_id');
        });

        DB::statement('
            UPDATE pembelians p
            INNER JOIN paintings pt ON pt.id = p.painting_id
            SET p.koleksi_id = pt.koleksi_id
            WHERE pt.koleksi_id IS NOT NULL
        ');

        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropForeign(['painting_id']);
            $table->dropColumn('painting_id');
        });

        Schema::table('pembelians', function (Blueprint $table) {
            $table->foreign('koleksi_id')->references('id')->on('koleksis')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropForeign(['koleksi_id']);
            $table->dropColumn('koleksi_id');
            $table->unsignedBigInteger('painting_id')->nullable()->after('user_id');
            $table->foreign('painting_id')->references('id')->on('paintings')->nullOnDelete();
        });
    }
};
