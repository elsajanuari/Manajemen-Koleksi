<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->boolean('boleh_reschedule')->default(false)->after('status');
            $table->boolean('boleh_cancel')->default(false)->after('boleh_reschedule');
        });

        Schema::table('pemesanan_tikets', function (Blueprint $table) {
            $table->timestamp('dibatalkan_pada')->nullable()->after('tiket_diverifikasi_oleh');
            $table->string('midtrans_refund_key', 64)->nullable()->after('dibatalkan_pada');
            $table->timestamp('reschedule_pada')->nullable()->after('midtrans_refund_key');
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan_tikets', function (Blueprint $table) {
            $table->dropColumn(['dibatalkan_pada', 'midtrans_refund_key', 'reschedule_pada']);
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['boleh_reschedule', 'boleh_cancel']);
        });
    }
};
