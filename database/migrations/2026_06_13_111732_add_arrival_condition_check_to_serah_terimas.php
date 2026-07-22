<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Tambah nilai baru ke enum handover_status ──────────────────
        // MySQL tidak bisa ALTER ENUM langsung dengan aman jika ada data,
        // jadi kita ubah ke string dulu lalu tambahkan constraint via app layer.
        // Atau: modifikasi kolom enum dengan menambah nilai baru.
        Schema::table('serah_terimas', function (Blueprint $table) {

            // ── Ubah enum handover_status untuk menampung nilai baru ──────
            // Nilai lama: waiting_handover, preparing_delivery, in_delivery,
            //             delivered, handover_completed
            // Nilai baru tambahan: condition_checking, damage_reported, damage_reviewed
            $table->string('handover_status', 50)->default('waiting_handover')->change();

            // ── Kolom pengecekan kondisi saat terima ──────────────────────
            $table->enum('condition_check_status', ['good', 'damaged'])
                ->nullable()
                ->after('confirmed_received_at')
                ->comment('Hasil pengecekan kondisi saat penyewa terima koleksi');

            $table->timestamp('condition_checked_at')
                ->nullable()
                ->after('condition_check_status');

            // Foto kondisi saat terima (depan & belakang) — wajib kalau kondisi baik
            $table->string('condition_front_photo')->nullable()
                ->after('condition_checked_at')
                ->comment('Foto depan koleksi saat diterima penyewa');

            $table->string('condition_back_photo')->nullable()
                ->after('condition_front_photo')
                ->comment('Foto belakang koleksi saat diterima penyewa');

            // ── Kolom kerusakan saat penerimaan ───────────────────────────
            $table->json('arrival_damage_checklist')
                ->nullable()
                ->after('condition_back_photo')
                ->comment('Array item kerusakan yang dicentang penyewa: [{key, label, checked}]');

            $table->json('arrival_damage_photos')
                ->nullable()
                ->after('arrival_damage_checklist')
                ->comment('Array path foto/video kerusakan yang diupload penyewa');

            $table->string('arrival_condition_front_photo')->nullable()
                ->after('arrival_damage_photos')
                ->comment('Foto depan koleksi saat diterima (ada kerusakan)');

            $table->string('arrival_condition_back_photo')->nullable()
                ->after('arrival_condition_front_photo')
                ->comment('Foto belakang koleksi saat diterima (ada kerusakan)');

            $table->enum('arrival_damage_severity', ['ringan', 'parah'])
                ->nullable()
                ->after('arrival_condition_back_photo')
                ->comment('Tingkat keparahan kerusakan menurut penyewa');

            $table->text('arrival_damage_description')
                ->nullable()
                ->after('arrival_damage_severity')
                ->comment('Deskripsi bebas kerusakan dari penyewa');

            $table->enum('arrival_damage_tenant_decision', ['lanjutkan', 'batalkan'])
                ->nullable()
                ->after('arrival_damage_description')
                ->comment('Keputusan penyewa: lanjutkan sewa atau batalkan');

            $table->timestamp('arrival_damage_reported_at')
                ->nullable()
                ->after('arrival_damage_tenant_decision');

            // ── Kolom keputusan pengelola ─────────────────────────────────
            $table->enum('arrival_damage_manager_decision', [
                'setuju_lanjut',   // kerusakan diakui, sewa lanjut, catat sebagai kondisi awal
                'tolak_lanjut',    // kerusakan tidak diakui penyewa, tapi sewa tetap lanjut
                'setuju_batal',    // kerusakan diakui, sewa dibatalkan + refund penuh
            ])->nullable()
              ->after('arrival_damage_reported_at')
              ->comment('Keputusan pengelola atas laporan kerusakan saat penerimaan');

            $table->text('arrival_damage_manager_notes')
                ->nullable()
                ->after('arrival_damage_manager_decision')
                ->comment('Catatan pengelola terkait keputusan');

            $table->timestamp('arrival_damage_decided_at')
                ->nullable()
                ->after('arrival_damage_manager_notes');
        });
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->dropColumn([
                'condition_check_status',
                'condition_checked_at',
                'condition_front_photo',
                'condition_back_photo',
                'arrival_damage_checklist',
                'arrival_damage_photos',
                'arrival_condition_front_photo',
                'arrival_condition_back_photo',
                'arrival_damage_severity',
                'arrival_damage_description',
                'arrival_damage_tenant_decision',
                'arrival_damage_reported_at',
                'arrival_damage_manager_decision',
                'arrival_damage_manager_notes',
                'arrival_damage_decided_at',
            ]);

            // Kembalikan handover_status ke enum asli
            $table->enum('handover_status', [
                'waiting_handover',
                'preparing_delivery',
                'in_delivery',
                'delivered',
                'handover_completed',
            ])->default('waiting_handover')->change();
        });
    }
};