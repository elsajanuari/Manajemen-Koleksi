<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->after('status');
            $table->string('invoice_path')->nullable()->after('invoice_number');

            // Kolom B2B yang belum ada
            if (!Schema::hasColumn('pembelians', 'company_rt')) {
                $table->string('company_rt', 10)->nullable()->after('company_address');
            }
            if (!Schema::hasColumn('pembelians', 'company_rw')) {
                $table->string('company_rw', 10)->nullable()->after('company_rt');
            }
            if (!Schema::hasColumn('pembelians', 'company_kelurahan_desa')) {
                $table->string('company_kelurahan_desa')->nullable()->after('company_rw');
            }
            if (!Schema::hasColumn('pembelians', 'upload_pic_ktp')) {
                $table->string('upload_pic_ktp')->nullable()->after('upload_ktp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'invoice_path']);
            if (Schema::hasColumn('pembelians', 'company_rt')) $table->dropColumn('company_rt');
            if (Schema::hasColumn('pembelians', 'company_rw')) $table->dropColumn('company_rw');
            if (Schema::hasColumn('pembelians', 'company_kelurahan_desa')) $table->dropColumn('company_kelurahan_desa');
            if (Schema::hasColumn('pembelians', 'upload_pic_ktp')) $table->dropColumn('upload_pic_ktp');
        });
    }
};