<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            // ========== STEP 3 — Kontak & Alamat ==========
            // contact_phone & contact_email sudah ada, tinggal alamat
            $table->text('alamat_ktp')->nullable()->after('contact_email');
            $table->text('alamat_domisili')->nullable()->after('alamat_ktp');
            $table->string('rt', 10)->nullable()->after('alamat_domisili');
            $table->string('rw', 10)->nullable()->after('rt');
            $table->string('kelurahan_desa')->nullable()->after('rw');
            $table->string('provinsi')->nullable()->after('kelurahan_desa');
            $table->string('kota_kabupaten')->nullable()->after('provinsi');
            $table->string('kode_pos', 10)->nullable()->after('kota_kabupaten');

            // ========== STEP 4 — Data Penyewaan ==========
            $table->string('nama_tempat')->nullable()->after('kode_pos');
            $table->string('jenis_tempat', 100)->nullable()->after('nama_tempat');
            $table->enum('indoor_outdoor', ['Indoor', 'Outdoor'])->nullable()->after('jenis_tempat');
            $table->text('lokasi_lengkap')->nullable()->after('indoor_outdoor');
            $table->string('kota_lokasi')->nullable()->after('lokasi_lengkap');
            $table->string('tujuan_penyewaan', 100)->nullable()->after('kota_lokasi');
            $table->integer('jumlah_pengunjung')->nullable()->after('tujuan_penyewaan');
            $table->text('deskripsi_kegiatan')->nullable()->after('jumlah_pengunjung');
            $table->enum('cctv', ['ya', 'tidak'])->nullable()->after('deskripsi_kegiatan');
            $table->enum('keamanan', ['ya', 'tidak'])->nullable()->after('cctv');
            $table->enum('ber_ac', ['ya', 'tidak'])->nullable()->after('keamanan');
            $table->enum('risiko_cuaca', ['ya', 'tidak'])->nullable()->after('ber_ac');

            // ========== STEP 5 — Penagihan ==========
            $table->string('invoice_name')->nullable()->after('risiko_cuaca');
            $table->string('invoice_email')->nullable()->after('invoice_name');
            $table->text('invoice_address')->nullable()->after('invoice_email');
            $table->string('payment_method', 50)->nullable()->after('invoice_address');
            $table->string('bank_name')->nullable()->after('payment_method');
            $table->string('account_number', 50)->nullable()->after('bank_name');

            // ========== STEP 6 — Upload Dokumen ==========
            $table->string('upload_ktp')->nullable()->after('account_number');
            $table->string('upload_selfie_ktp')->nullable()->after('upload_ktp');
            $table->string('upload_npwp')->nullable()->after('upload_selfie_ktp');
            $table->string('upload_foto_lokasi')->nullable()->after('upload_npwp');
            $table->string('upload_denah')->nullable()->after('upload_foto_lokasi');
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumn([
                // Step 3
                'alamat_ktp', 'alamat_domisili', 'rt', 'rw',
                'kelurahan_desa', 'provinsi', 'kota_kabupaten', 'kode_pos',
                // Step 4
                'nama_tempat', 'jenis_tempat', 'indoor_outdoor', 'lokasi_lengkap',
                'kota_lokasi', 'tujuan_penyewaan', 'jumlah_pengunjung',
                'deskripsi_kegiatan', 'cctv', 'keamanan', 'ber_ac', 'risiko_cuaca',
                // Step 5
                'invoice_name', 'invoice_email', 'invoice_address',
                'payment_method', 'bank_name', 'account_number',
                // Step 6
                'upload_ktp', 'upload_selfie_ktp', 'upload_npwp',
                'upload_foto_lokasi', 'upload_denah',
            ]);
        });
    }
};