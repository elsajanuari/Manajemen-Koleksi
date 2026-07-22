<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            // Info pengiriman balik dari penyewa
            $table->string('return_shipment_method')->nullable()->after('return_document_path');
            $table->string('return_shipment_officer')->nullable()->after('return_shipment_method');
            $table->string('return_shipment_tracking')->nullable()->after('return_shipment_officer');
            $table->dateTime('return_shipment_scheduled_at')->nullable()->after('return_shipment_tracking');
            $table->text('return_shipment_notes')->nullable()->after('return_shipment_scheduled_at');
            $table->dateTime('return_shipment_submitted_at')->nullable()->after('return_shipment_notes');

            // Dokumen pengembalian ditandatangani penyewa
            $table->string('tenant_signed_return_document_path')->nullable()->after('return_shipment_submitted_at');
            $table->dateTime('tenant_signed_return_at')->nullable()->after('tenant_signed_return_document_path');

            // Konfirmasi pengelola terima koleksi kembali
            $table->dateTime('collection_returned_at')->nullable()->after('tenant_signed_return_at');
        });
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->dropColumn([
                'return_shipment_method',
                'return_shipment_officer',
                'return_shipment_tracking',
                'return_shipment_scheduled_at',
                'return_shipment_notes',
                'return_shipment_submitted_at',
                'tenant_signed_return_document_path',
                'tenant_signed_return_at',
                'collection_returned_at',
            ]);
        });
    }
};