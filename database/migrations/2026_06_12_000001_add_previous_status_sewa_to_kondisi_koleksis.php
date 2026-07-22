<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kondisi_koleksis', function (Blueprint $table) {
            $table->string('previous_status_sewa')->nullable()->after('kondisi');
        });
    }

    public function down(): void
    {
        Schema::table('kondisi_koleksis', function (Blueprint $table) {
            $table->dropColumn('previous_status_sewa');
        });
    }
};
