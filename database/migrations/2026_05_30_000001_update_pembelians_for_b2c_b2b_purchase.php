<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->enum('buyer_type', ['b2c', 'b2b'])->default('b2c')->after('painting_id');
            $table->string('company_name')->nullable()->after('buyer_type');
            $table->string('company_type')->nullable()->after('company_name');
            $table->string('business_field')->nullable()->after('company_type');
            $table->string('company_npwp')->nullable()->after('business_field');
            $table->text('company_address')->nullable()->after('company_npwp');
            $table->string('company_rt', 10)->nullable()->after('company_address');
            $table->string('company_rw', 10)->nullable()->after('company_rt');
            $table->string('company_kelurahan_desa')->nullable()->after('company_rw');
            $table->string('company_city')->nullable()->after('company_kelurahan_desa');
            $table->string('company_province')->nullable()->after('company_city');
            $table->string('company_postal_code', 10)->nullable()->after('company_province');
            $table->string('company_email')->nullable()->after('company_postal_code');
            $table->string('company_phone', 25)->nullable()->after('company_email');
            $table->string('company_website')->nullable()->after('company_phone');
            $table->string('pic_name')->nullable()->after('company_website');
            $table->string('pic_position')->nullable()->after('pic_name');
            $table->string('pic_nik', 16)->nullable()->after('pic_position');
            $table->string('pic_phone', 25)->nullable()->after('pic_nik');
            $table->string('pic_email')->nullable()->after('pic_phone');
            $table->string('upload_npwp_company')->nullable()->after('upload_npwp');
            $table->string('upload_purchase_request_letter')->nullable()->after('upload_npwp_company');
            $table->string('upload_pic_ktp')->nullable()->after('upload_purchase_request_letter');
            $table->string('upload_legal_document')->nullable()->after('upload_pic_ktp');
            $table->enum('npwp_status', ['pending', 'valid', 'invalid', 'not_applicable'])->default('pending')->after('upload_legal_document');
            $table->timestamp('npwp_verified_at')->nullable()->after('npwp_status');
            $table->timestamp('invoice_generated_at')->nullable()->after('npwp_verified_at');
        });

        // Make personal identity fields nullable for B2B submissions
        $databaseDriver = DB::getDriverName();

        if ($databaseDriver === 'mysql') {
            DB::statement('ALTER TABLE pembelians MODIFY nama_lengkap VARCHAR(255) NULL');
            DB::statement('ALTER TABLE pembelians MODIFY nik VARCHAR(16) NULL');
            DB::statement('ALTER TABLE pembelians MODIFY tempat_lahir VARCHAR(255) NULL');
            DB::statement('ALTER TABLE pembelians MODIFY tanggal_lahir DATE NULL');
            DB::statement('ALTER TABLE pembelians MODIFY jenis_kelamin ENUM("Laki-laki","Perempuan") NULL');
            DB::statement('ALTER TABLE pembelians MODIFY pekerjaan VARCHAR(255) NULL');
            DB::statement('ALTER TABLE pembelians MODIFY upload_ktp VARCHAR(255) NULL');
            DB::statement('ALTER TABLE pembelians MODIFY upload_npwp VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn([
                'buyer_type',
                'company_name',
                'company_type',
                'business_field',
                'company_npwp',
                'company_address',
                'company_rt',
                'company_rw',
                'company_kelurahan_desa',
                'company_city',
                'company_province',
                'company_postal_code',
                'company_email',
                'company_phone',
                'company_website',
                'pic_name',
                'pic_position',
                'pic_nik',
                'pic_phone',
                'pic_email',
                'upload_npwp_company',
                'upload_purchase_request_letter',
                'upload_pic_ktp',
                'upload_legal_document',
                'npwp_status',
                'npwp_verified_at',
                'invoice_generated_at',
            ]);
        });

        $databaseDriver = DB::getDriverName();
        if ($databaseDriver === 'mysql') {
            DB::statement('ALTER TABLE pembelians MODIFY nama_lengkap VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE pembelians MODIFY nik VARCHAR(16) NOT NULL');
            DB::statement('ALTER TABLE pembelians MODIFY tempat_lahir VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE pembelians MODIFY tanggal_lahir DATE NOT NULL');
            DB::statement('ALTER TABLE pembelians MODIFY jenis_kelamin ENUM("Laki-laki","Perempuan") NOT NULL');
            DB::statement('ALTER TABLE pembelians MODIFY pekerjaan VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE pembelians MODIFY upload_ktp VARCHAR(255) NULL');
            DB::statement('ALTER TABLE pembelians MODIFY upload_npwp VARCHAR(255) NULL');
        }
    }
};
