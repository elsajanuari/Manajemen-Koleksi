<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->string('account_holder')->nullable()->after('bank_name');
        });
    }

    public function down()
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumn('account_holder');
        });
    }
};
