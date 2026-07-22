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
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dateTime('delivery_at')->nullable()->after('payment_reference');
            $table->dateTime('received_at')->nullable()->after('delivery_at');
            $table->dateTime('rental_started_at')->nullable()->after('received_at');
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumn(['delivery_at', 'received_at', 'rental_started_at']);
        });
    }
};
