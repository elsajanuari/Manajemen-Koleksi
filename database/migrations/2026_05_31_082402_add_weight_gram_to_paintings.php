<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paintings', function (Blueprint $table) {
            $table->unsignedInteger('weight_gram')->default(1000)->after('for_sale');
            // default 1kg — pengelola bisa update per koleksi
        });
    }

    public function down(): void
    {
        Schema::table('paintings', function (Blueprint $table) {
            $table->dropColumn('weight_gram');
        });
    }
};