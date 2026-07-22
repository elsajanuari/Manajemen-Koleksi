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
            // Perseorangan fields - make nullable
            $table->string('contact_name')->nullable()->change();
            $table->char('nik', 16)->nullable()->change();
            $table->string('tempat_lahir')->nullable()->change();
            $table->date('tanggal_lahir')->nullable()->change();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->change();
            $table->enum('kewarganegaraan', ['WNI', 'WNA'])->nullable()->change();
            $table->string('negara_asal')->nullable()->change();
            $table->string('pekerjaan')->nullable()->change();
            $table->string('npwp')->nullable()->change();

            // Instansi fields - make nullable
            $table->string('nama_instansi')->nullable()->change();
            $table->enum('jenis_instansi', [
                'Perusahaan Swasta',
                'BUMN/BUMD',
                'Instansi Pemerintah',
                'Yayasan/NGO',
                'Lembaga Pendidikan',
                'Komunitas/Organisasi',
                'Lainnya'
            ])->nullable()->change();
            $table->string('bidang_usaha')->nullable()->change();
            $table->string('email_instansi')->nullable()->change();
            $table->string('telepon_kantor')->nullable()->change();
            $table->string('website_instansi')->nullable()->change();
            $table->text('alamat_instansi')->nullable()->change();
            $table->string('provinsi_instansi')->nullable()->change();
            $table->string('kota_instansi')->nullable()->change();
            $table->string('kode_pos_instansi')->nullable()->change();
            $table->string('npwp_instansi')->nullable()->change();
            $table->string('nomor_nib')->nullable()->change();
            $table->string('nomor_siup')->nullable()->change();

            // PIC fields - make nullable
            $table->string('nama_pic')->nullable()->change();
            $table->string('jabatan_pic')->nullable()->change();
            $table->char('nik_pic', 16)->nullable()->change();
            $table->string('hp_pic')->nullable()->change();
            $table->string('email_pic')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            // Perseorangan fields - revert to not nullable
            $table->string('contact_name')->nullable(false)->change();
            $table->char('nik', 16)->nullable(false)->change();
            $table->string('tempat_lahir')->nullable(false)->change();
            $table->date('tanggal_lahir')->nullable(false)->change();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable(false)->change();
            $table->enum('kewarganegaraan', ['WNI', 'WNA'])->nullable(false)->change();
            $table->string('negara_asal')->nullable(false)->change();
            $table->string('pekerjaan')->nullable(false)->change();
            $table->string('npwp')->nullable(false)->change();

            // Instansi fields - revert to not nullable
            $table->string('nama_instansi')->nullable(false)->change();
            $table->enum('jenis_instansi', [
                'Perusahaan Swasta',
                'BUMN/BUMD',
                'Instansi Pemerintah',
                'Yayasan/NGO',
                'Lembaga Pendidikan',
                'Komunitas/Organisasi',
                'Lainnya'
            ])->nullable(false)->change();
            $table->string('bidang_usaha')->nullable(false)->change();
            $table->string('email_instansi')->nullable(false)->change();
            $table->string('telepon_kantor')->nullable(false)->change();
            $table->string('website_instansi')->nullable(false)->change();
            $table->text('alamat_instansi')->nullable(false)->change();
            $table->string('provinsi_instansi')->nullable(false)->change();
            $table->string('kota_instansi')->nullable(false)->change();
            $table->string('kode_pos_instansi')->nullable(false)->change();
            $table->string('npwp_instansi')->nullable(false)->change();
            $table->string('nomor_nib')->nullable(false)->change();
            $table->string('nomor_siup')->nullable(false)->change();

            // PIC fields - revert to not nullable
            $table->string('nama_pic')->nullable(false)->change();
            $table->string('jabatan_pic')->nullable(false)->change();
            $table->char('nik_pic', 16)->nullable(false)->change();
            $table->string('hp_pic')->nullable(false)->change();
            $table->string('email_pic')->nullable(false)->change();
        });
    }
};
