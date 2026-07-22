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
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->string('initial_return_document_path')->nullable()->after('return_document_path');
            $table->string('tenant_signed_initial_return_path')->nullable()->after('initial_return_document_path');
            $table->dateTime('tenant_signed_initial_return_at')->nullable()->after('tenant_signed_initial_return_path');
        });
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->dropColumn([
                'initial_return_document_path',
                'tenant_signed_initial_return_path',
                'tenant_signed_initial_return_at',
            ]);
        });
    }
};
