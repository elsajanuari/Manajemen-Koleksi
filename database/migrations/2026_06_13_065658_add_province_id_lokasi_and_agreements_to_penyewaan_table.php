<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            if (!Schema::hasColumn('penyewaan', 'province_id_lokasi')) {
                $table->string('province_id_lokasi', 10)->nullable()->after('destination_city_id');
            }
            if (!Schema::hasColumn('penyewaan', 'agree_terms')) {
                $table->boolean('agree_terms')->nullable()->after('account_number');
            }
            if (!Schema::hasColumn('penyewaan', 'agree_responsibility')) {
                $table->boolean('agree_responsibility')->nullable()->after('agree_terms');
            }
            if (!Schema::hasColumn('penyewaan', 'agree_privacy')) {
                $table->boolean('agree_privacy')->nullable()->after('agree_responsibility');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumn(['province_id_lokasi', 'agree_terms', 'agree_responsibility', 'agree_privacy']);
        });
    }
};