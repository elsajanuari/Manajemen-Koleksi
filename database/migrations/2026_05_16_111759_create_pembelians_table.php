<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('painting_id')->constrained()->cascadeOnDelete();

            // Status
            $table->enum('status', [
                'menunggu_verifikasi',
                'disetujui',
                'ditolak',
                'menunggu_pembayaran',
                'pembayaran_berhasil',
                'dibatalkan',
            ])->default('menunggu_verifikasi');

            // Data Pribadi
            $table->string('nama_lengkap');
            $table->string('nik', 16);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('pekerjaan');
            $table->string('npwp')->nullable();

            // Kontak
            $table->string('nomor_hp', 25);
            $table->string('email');

            // Alamat Pengiriman
            $table->text('alamat_pengiriman');
            $table->string('rt', 10);
            $table->string('rw', 10);
            $table->string('kelurahan_desa');
            $table->string('kota_kabupaten');
            $table->string('provinsi');
            $table->string('kode_pos', 10);

            // Upload Dokumen
            $table->string('upload_ktp')->nullable();
            $table->string('upload_npwp')->nullable();

            // Harga (snapshot saat pengajuan)
            $table->unsignedInteger('harga_beli');   // sale_price saat itu
            $table->unsignedInteger('ppn');           // 11% dari harga_beli
            $table->unsignedInteger('total_bayar');   // harga_beli + ppn

            // Catatan pengelola saat approve/reject
            $table->text('catatan_pengelola')->nullable();

            // Midtrans
            $table->string('payment_reference')->nullable();
            $table->string('payment_status')->nullable(); // pending, paid, failed, expired
            $table->timestamp('paid_at')->nullable();

            // Timestamps
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelians');
    }
};