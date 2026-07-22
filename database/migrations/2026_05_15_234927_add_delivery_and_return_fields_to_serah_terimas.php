<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            // Delivery info (tahap 13-14)
            $table->string('delivery_officer')->nullable()->after('delivery_location');   // petugas pengirim
            $table->string('delivery_tracking_number')->nullable()->after('delivery_officer'); // nomor resi
            $table->text('delivery_notes')->nullable()->after('delivery_tracking_number'); // catatan pengiriman
            $table->dateTime('delivery_scheduled_at')->nullable()->after('delivery_notes'); // rencana tanggal kirim

            // Penerimaan oleh penyewa (tahap 15)
            $table->dateTime('confirmed_received_at')->nullable()->after('delivered_at'); // waktu konfirmasi terima

            // Upload dokumen serah terima oleh penyewa (tahap 17)
            $table->string('tenant_signed_document_path')->nullable()->after('received_condition_photo_path');
            $table->dateTime('tenant_uploaded_at')->nullable()->after('tenant_signed_document_path');

            // Validasi serah terima oleh pengelola (tahap 18)
            $table->enum('serah_terima_status', [
                'pending',
                'waiting_confirmation',   // menunggu konfirmasi terima dari penyewa
                'waiting_document',       // menunggu upload dokumen dari penyewa
                'document_uploaded',      // dokumen sudah diupload, menunggu validasi
                'validated',              // pengelola sudah validasi, penyewaan aktif
            ])->default('pending')->after('status');
            $table->text('validation_notes')->nullable()->after('serah_terima_status');
            $table->dateTime('validated_at')->nullable()->after('validation_notes');
            $table->string('validated_by')->nullable()->after('validated_at');

            // Pengembalian koleksi (tahap 21-23)
            $table->dateTime('return_date')->nullable()->after('validated_at');
            $table->text('return_condition_notes')->nullable()->after('return_date');
            $table->string('return_condition_photo_path')->nullable()->after('return_condition_notes');
            $table->boolean('return_checklist_frame_safe')->default(false)->after('return_condition_photo_path');
            $table->boolean('return_checklist_no_tears')->default(false)->after('return_checklist_frame_safe');
            $table->boolean('return_checklist_color_normal')->default(false)->after('return_checklist_no_tears');
            $table->boolean('return_checklist_glass_safe')->default(false)->after('return_checklist_color_normal');
            $table->boolean('return_checklist_no_mold')->default(false)->after('return_checklist_glass_safe');
            $table->boolean('return_checklist_matches_documentation')->default(false)->after('return_checklist_no_mold');
            $table->text('damage_notes')->nullable()->after('return_checklist_matches_documentation');
            $table->unsignedBigInteger('damage_cost')->default(0)->after('damage_notes');
            $table->string('return_document_path')->nullable()->after('damage_cost'); // dokumen serah terima pengembalian
        });

        // Update enum handover_status untuk tambah status baru
        Schema::table('serah_terimas', function (Blueprint $table) {
            \DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN handover_status ENUM(
                'waiting_handover',
                'preparing_delivery',
                'in_delivery',
                'delivered',
                'handover_completed',
                'returned'
            ) DEFAULT 'waiting_handover'");
        });

        // Update enum status untuk tambah status baru
        \DB::statement("ALTER TABLE serah_terimas MODIFY COLUMN status ENUM(
            'generated',
            'uploaded',
            'validated',
            'returning',
            'completed'
        ) DEFAULT 'generated'");
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_officer', 'delivery_tracking_number', 'delivery_notes',
                'delivery_scheduled_at', 'confirmed_received_at',
                'tenant_signed_document_path', 'tenant_uploaded_at',
                'serah_terima_status', 'validation_notes', 'validated_at', 'validated_by',
                'return_date', 'return_condition_notes', 'return_condition_photo_path',
                'return_checklist_frame_safe', 'return_checklist_no_tears',
                'return_checklist_color_normal', 'return_checklist_glass_safe',
                'return_checklist_no_mold', 'return_checklist_matches_documentation',
                'damage_notes', 'damage_cost', 'return_document_path',
            ]);
        });
    }
};