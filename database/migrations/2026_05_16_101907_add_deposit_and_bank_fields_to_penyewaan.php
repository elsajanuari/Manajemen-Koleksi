<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            // Bank fields (account_holder sudah ada, tambah yang belum)
            if (! Schema::hasColumn('penyewaan', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('payment_reference');
            }
            if (! Schema::hasColumn('penyewaan', 'account_number')) {
                $table->string('account_number')->nullable()->after('bank_name');
            }
            // account_holder sudah ada dari migration sebelumnya

            // Deposit
            if (! Schema::hasColumn('penyewaan', 'deposit_amount')) {
                $table->unsignedBigInteger('deposit_amount')->default(0)->after('account_number');
            }
            if (! Schema::hasColumn('penyewaan', 'deposit_status')) {
                $table->enum('deposit_status', [
                    'unpaid',
                    'paid',
                    'returned',
                    'partially_returned',
                    'deducted',
                    'additional_payment_required',
                ])->default('unpaid')->after('deposit_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penyewaan', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('penyewaan', 'bank_name'))        $columns[] = 'bank_name';
            if (Schema::hasColumn('penyewaan', 'account_number'))   $columns[] = 'account_number';
            if (Schema::hasColumn('penyewaan', 'deposit_amount'))   $columns[] = 'deposit_amount';
            if (Schema::hasColumn('penyewaan', 'deposit_status'))   $columns[] = 'deposit_status';
            if ($columns) $table->dropColumn($columns);
        });
    }
};