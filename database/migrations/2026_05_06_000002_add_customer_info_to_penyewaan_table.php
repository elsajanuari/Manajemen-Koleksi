<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->string('contact_name')->after('painting_id');
            $table->string('contact_email')->after('contact_name');
            $table->text('full_address')->after('contact_email');
            $table->enum('rental_type', ['perseorangan', 'instansi'])->default('perseorangan')->after('full_address');
            $table->string('institution_name')->nullable()->after('rental_type');
            $table->text('purpose')->nullable()->after('institution_name');
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $table->dropColumn(['contact_name', 'contact_email', 'full_address', 'rental_type', 'institution_name', 'purpose']);
        });
    }
};
