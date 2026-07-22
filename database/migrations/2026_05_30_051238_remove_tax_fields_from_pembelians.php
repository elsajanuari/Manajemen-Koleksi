<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Hapus kolom PPh & NPWP-status dari tabel pembelians
 *
 * ALASAN:
 * Museum MK Lesmana adalah museum swasta yang belum menjadi PKP
 * (Pengusaha Kena Pajak), sehingga:
 *   1. PPN 11% → tidak dipungut (bukan PKP)
 *   2. PPh Pasal 22 → tidak berlaku karena koleksi seni bukan
 *      termasuk "barang sangat mewah" sesuai PMK 92/PMK.03/2019
 *      (yang mencakup: rumah mewah, apartemen mewah, pesawat
 *       pribadi, kapal pesiar, kendaraan mewah).
 *
 * Kolom NPWP (npwp, company_npwp) TETAP dipertahankan untuk
 * keperluan administratif, namun tidak mempengaruhi perhitungan harga.
 *
 * Kolom yang dihapus:
 *   - ppn         (PPN 11% dalam Rupiah)
 *   - pph_rate    (tarif PPh dalam %)
 *   - pph         (PPh Pasal 22 dalam Rupiah)
 *   - pph_status  (not_final / npwp / no_npwp)
 *   - npwp_status (pending / valid / invalid / not_applicable)
 *   - npwp_verified_at (timestamp verifikasi NPWP)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelians', function (Blueprint $table) {
            // Hapus kolom pajak transaksi yang tidak berlaku
            $table->dropColumn([
                'ppn',
                'pph_rate',
                'pph',
                'pph_status',
                'npwp_status',
                'npwp_verified_at',
            ]);

            // total_bayar sekarang = harga_beli saja
            // (tidak perlu ubah kolom, nilainya akan diupdate via seeder/tinker jika perlu)
        });
    }

    public function down(): void
    {
        // Kembalikan kolom jika rollback diperlukan
        Schema::table('pembelians', function (Blueprint $table) {
            $table->bigInteger('ppn')->default(0)->after('harga_beli');
            $table->decimal('pph_rate', 4, 2)->default(0)->after('ppn');
            $table->bigInteger('pph')->default(0)->after('pph_rate');
            $table->enum('pph_status', ['not_final', 'npwp', 'no_npwp'])->default('not_final')->after('pph');
            $table->enum('npwp_status', ['pending', 'valid', 'invalid', 'not_applicable'])->default('pending');
            $table->timestamp('npwp_verified_at')->nullable();
        });
    }
};