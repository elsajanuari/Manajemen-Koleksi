<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->string('manager_delivery_status')->nullable()->after('delivery_notes');
            $table->json('manager_delivery_timeline')->nullable()->after('manager_delivery_status');
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn(['manager_delivery_status', 'manager_delivery_timeline']);
        });
    }
};