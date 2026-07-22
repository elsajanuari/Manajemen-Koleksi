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
            $table->string('generated_file')->nullable()->after('handover_document_path');
            $table->string('uploaded_file')->nullable()->after('generated_file');
            $table->dateTime('uploaded_at')->nullable()->after('uploaded_file');
            $table->enum('status', ['generated', 'uploaded'])->default('generated')->after('uploaded_at');
        });
    }

    public function down(): void
    {
        Schema::table('serah_terimas', function (Blueprint $table) {
            $table->dropColumn(['generated_file', 'uploaded_file', 'uploaded_at', 'status']);
        });
    }
};
