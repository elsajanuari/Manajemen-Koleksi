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
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->json('damage_items_detail')->nullable()->after('damage_notes');
            // Jika belum ada, tambahkan juga:
            $table->timestamp('refund_confirmed_at')->nullable()->after('damage_items_detail');
            $table->unsignedBigInteger('refund_confirmed_by')->nullable()->after('refund_confirmed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            //
        });
    }
};
