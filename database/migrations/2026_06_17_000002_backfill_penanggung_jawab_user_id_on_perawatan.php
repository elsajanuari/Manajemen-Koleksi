<?php

use App\Models\PerawatanKoleksi;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $usersByName = User::where('role', 'pengelola')->pluck('id', 'name');

        PerawatanKoleksi::whereNull('penanggung_jawab_user_id')
            ->whereNotNull('penanggung_jawab')
            ->each(function (PerawatanKoleksi $perawatan) use ($usersByName) {
                $userId = $usersByName[$perawatan->penanggung_jawab] ?? null;

                if ($userId) {
                    $perawatan->update(['penanggung_jawab_user_id' => $userId]);
                }
            });
    }

    public function down(): void
    {
        // Data backfill — tidak di-rollback.
    }
};
