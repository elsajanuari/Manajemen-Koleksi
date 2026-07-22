<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu_verifikasi',
                'disetujui',
                'ditolak',
                'menunggu_pembayaran',
                'pembayaran_berhasil',
                'siap_diserahkan',
                'dalam_pengiriman',
                'menunggu_dokumen_serah_terima',
                'menunggu_validasi_serah_terima',
                'diterima_pembeli',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_verifikasi')->change();

            $table->string('handover_document_path')->nullable()->after('completed_at');
            $table->string('handover_signed_document_path')->nullable()->after('handover_document_path');
            $table->timestamp('handover_signed_at')->nullable()->after('handover_signed_document_path');
            $table->timestamp('handover_document_uploaded_at')->nullable()->after('handover_signed_at');
            $table->text('handover_condition_notes')->nullable()->after('handover_document_uploaded_at');
            $table->string('handover_received_condition_photo_path')->nullable()->after('handover_condition_notes');
            $table->boolean('handover_checklist_frame_safe')->default(false)->after('handover_received_condition_photo_path');
            $table->boolean('handover_checklist_no_tears')->default(false)->after('handover_checklist_frame_safe');
            $table->boolean('handover_checklist_color_normal')->default(false)->after('handover_checklist_no_tears');
            $table->boolean('handover_checklist_glass_safe')->default(false)->after('handover_checklist_color_normal');
            $table->boolean('handover_checklist_no_mold')->default(false)->after('handover_checklist_glass_safe');
            $table->boolean('handover_checklist_matches_documentation')->default(false)->after('handover_checklist_no_mold');
            $table->text('handover_validation_notes')->nullable()->after('handover_checklist_matches_documentation');
            $table->timestamp('handover_validated_at')->nullable()->after('handover_validation_notes');
            $table->string('handover_validated_by')->nullable()->after('handover_validated_at');
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn([
                'handover_document_path',
                'handover_signed_document_path',
                'handover_signed_at',
                'handover_document_uploaded_at',
                'handover_condition_notes',
                'handover_received_condition_photo_path',
                'handover_checklist_frame_safe',
                'handover_checklist_no_tears',
                'handover_checklist_color_normal',
                'handover_checklist_glass_safe',
                'handover_checklist_no_mold',
                'handover_checklist_matches_documentation',
                'handover_validation_notes',
                'handover_validated_at',
                'handover_validated_by',
            ]);

            $table->enum('status', [
                'menunggu_verifikasi',
                'disetujui',
                'ditolak',
                'menunggu_pembayaran',
                'pembayaran_berhasil',
                'siap_diserahkan',
                'dalam_pengiriman',
                'diterima_pembeli',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_verifikasi')->change();
        });
    }
};
