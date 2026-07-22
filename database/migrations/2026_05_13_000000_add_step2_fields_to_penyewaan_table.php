<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            // ========== STEP 2 PERSEORANGAN ==========
            $table->string('nik')->nullable()->after('contact_name');
            $table->string('tempat_lahir')->nullable()->after('nik');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('tanggal_lahir');
            $table->enum('kewarganegaraan', ['WNI', 'WNA'])->default('WNI')->after('jenis_kelamin');
            $table->string('negara_asal')->nullable()->after('kewarganegaraan');
            $table->string('pekerjaan')->nullable()->after('negara_asal');
            $table->string('npwp')->nullable()->after('pekerjaan');

            // ========== STEP 2 INSTANSI ==========
            $table->string('nama_instansi')->nullable()->after('institution_name');
            $table->enum('jenis_instansi', [
                'Perusahaan Swasta',
                'BUMN / BUMD',
                'Instansi Pemerintah',
                'Yayasan / NGO',
                'Lembaga Pendidikan',
                'Komunitas / Organisasi',
                'Lainnya'
            ])->nullable()->after('nama_instansi');
            $table->string('bidang_usaha')->nullable()->after('jenis_instansi');
            $table->string('email_instansi')->nullable()->after('bidang_usaha');
            $table->string('telepon_kantor')->nullable()->after('email_instansi');
            $table->string('website_instansi')->nullable()->after('telepon_kantor');
            
            // Instansi Address
            $table->string('alamat_instansi')->nullable()->after('website_instansi');
            $table->string('provinsi_instansi')->nullable()->after('alamat_instansi');
            $table->string('kota_instansi')->nullable()->after('provinsi_instansi');
            $table->string('kode_pos_instansi')->nullable()->after('kota_instansi');
            
            // Instansi Legality
            $table->string('npwp_instansi')->nullable()->after('kode_pos_instansi');
            $table->string('nomor_nib')->nullable()->after('npwp_instansi');
            $table->string('nomor_siup')->nullable()->after('nomor_nib');
            
            // Instansi PIC (Person In Charge)
            $table->string('nama_pic')->nullable()->after('nomor_siup');
            $table->string('jabatan_pic')->nullable()->after('nama_pic');
            $table->string('nik_pic')->nullable()->after('jabatan_pic');
            $table->string('hp_pic')->nullable()->after('nik_pic');
            $table->string('email_pic')->nullable()->after('hp_pic');

            // ========== TRACKING ==========
            $table->enum('submission_status', ['draft', 'submitted'])->default('draft')->after('status');
            $table->integer('current_step')->default(1)->after('submission_status');
            $table->timestamp('submitted_at')->nullable()->after('current_step');
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumn([
                // Step 2 Perseorangan
                'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 
                'kewarganegaraan', 'negara_asal', 'pekerjaan', 'npwp',
                
                // Step 2 Instansi
                'nama_instansi', 'jenis_instansi', 'bidang_usaha', 
                'email_instansi', 'telepon_kantor', 'website_instansi',
                'alamat_instansi', 'provinsi_instansi', 'kota_instansi', 'kode_pos_instansi',
                'npwp_instansi', 'nomor_nib', 'nomor_siup',
                'nama_pic', 'jabatan_pic', 'nik_pic', 'hp_pic', 'email_pic',
                
                // Tracking
                'submission_status', 'current_step', 'submitted_at'
            ]);
        });
    }
};
