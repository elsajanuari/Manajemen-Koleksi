<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConservationAction;
use App\Models\Koleksi;
use App\Models\KondisiKoleksi;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PerawatanKoleksi extends Model
{
    use HasFactory;

    public const STATUS_TERJADWAL   = 'terjadwal';
    public const STATUS_SELESAI     = 'selesai';
    public const STATUS_DIBATALKAN  = 'dibatalkan';

    public const JENIS_PEMELIHARAAN         = 'pemeliharaan';
    public const JENIS_PENANGANAN_KERUSAKAN = 'penanganan_kerusakan';
    public const JENIS_PEMERIKSAAN_ULANG    = 'pemeriksaan_ulang';

    public const JENIS_OPTIONS = [
        self::JENIS_PEMELIHARAAN         => 'Pemeliharaan',
        self::JENIS_PENANGANAN_KERUSAKAN => 'Penanganan Kerusakan',
        self::JENIS_PEMERIKSAAN_ULANG    => 'Pemeriksaan Ulang',
    ];

    public const JENIS_HELPER_TEXT = [
        self::JENIS_PEMELIHARAAN         => 'Konservasi preventif untuk menjaga kondisi koleksi tetap stabil.',
        self::JENIS_PENANGANAN_KERUSAKAN => 'Konservasi kuratif untuk menangani kerusakan yang sudah terjadi pada koleksi.',
        self::JENIS_PEMERIKSAAN_ULANG    => 'Kegiatan pengecekan kondisi fisik koleksi untuk menilai perkembangan kondisi sejak pemeriksaan terakhir.',
    ];

    public const JENIS_HELPER_TEXT_DEFAULT = 'Pilih jenis konservasi untuk melihat penjelasan singkat.';

    public const FREKUENSI_OPTIONS = [
        'sekali'   => 'Sekali',
        'bulanan'  => 'Bulanan',
        'triwulan' => 'Per Triwulan',
        'tahunan'  => 'Tahunan',
    ];

    public const KEGIATAN_PEMELIHARAAN = [
        'pembersihan'            => 'Pembersihan rutin',
        'pengendalian_hama'      => 'Pengendalian hama (serangga/jamur)',
        'kontrol_iklim'          => 'Kontrol suhu & kelembapan',
        'kontrol_cahaya'         => 'Pengaturan pencahayaan / UV',
        'penataan_penyimpanan'   => 'Penataan & penyimpanan',
        'pemeriksaan_lingkungan' => 'Pemeriksaan lingkungan',
    ];

    public const STATUS_OPTIONS = [
        self::STATUS_TERJADWAL  => 'Terjadwal',
        self::STATUS_SELESAI    => 'Selesai',
        self::STATUS_DIBATALKAN => 'Dibatalkan',
    ];

    public const PRIORITAS_TINGGI = 'tinggi';
    public const PRIORITAS_SEDANG = 'sedang';
    public const PRIORITAS_RENDAH = 'rendah';

    public const PRIORITAS_OPTIONS = [
        self::PRIORITAS_TINGGI => 'Tinggi',
        self::PRIORITAS_SEDANG => 'Sedang',
        self::PRIORITAS_RENDAH => 'Rendah',
    ];

    protected $fillable = [
        'koleksi_id',
        'kondisi_koleksi_id',
        'jenis_perawatan',
        'jadwal_tanggal',
        'frekuensi',
        'estimasi_durasi_menit',
        'penanggung_jawab',
        'penanggung_jawab_user_id',
        'created_by',
        'catatan',
        'status',
        'tanggal_selesai',
        'catatan_penyelesaian',
        'alasan_pembatalan',
    ];

    protected $casts = [
        'jadwal_tanggal'  => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function koleksi(): BelongsTo
    {
        return $this->belongsTo(Koleksi::class);
    }

    public function kondisiHasil(): HasOne
    {
        return $this->hasOne(KondisiKoleksi::class, 'perawatan_id');
    }

    public function kondisiSumber(): BelongsTo
    {
        return $this->belongsTo(KondisiKoleksi::class, 'kondisi_koleksi_id');
    }

    public function conservationAction(): HasOne
    {
        return $this->hasOne(ConservationAction::class, 'perawatan_koleksi_id');
    }

    public function penanggungJawabUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Label helpers ────────────────────────────────────────────

    public function getLabelJenisAttribute(): string
    {
        return self::JENIS_OPTIONS[$this->jenis_perawatan] ?? ucfirst(str_replace('_', ' ', $this->jenis_perawatan));
    }

    public function getLabelFrekuensiAttribute(): string
    {
        return self::FREKUENSI_OPTIONS[$this->frekuensi] ?? ucfirst($this->frekuensi);
    }

    public function isPemeriksaan(): bool
    {
        return $this->jenis_perawatan === self::JENIS_PEMERIKSAAN_ULANG;
    }

    public function completeFromPemeriksaan(KondisiKoleksi $kondisi): void
    {
        $this->update([
            'status'               => self::STATUS_SELESAI,
            'tanggal_selesai'      => $kondisi->tanggal_periksa,
            'catatan_penyelesaian' => $kondisi->catatan,
        ]);
    }

    public function requiresConservation(): bool
    {
        return $this->jenis_perawatan === self::JENIS_PENANGANAN_KERUSAKAN;
    }

    public function isPemeliharaan(): bool
    {
        return $this->jenis_perawatan === self::JENIS_PEMELIHARAAN;
    }

    public function canStartConservation(): bool
    {
        return $this->requiresConservation()
            && $this->isScheduled()
            && $this->kondisi_koleksi_id !== null;
    }

    /**
     * Jadwal penanganan kerusakan yang masih terjadwal namun belum memiliki
     * tindakan konservasi (belum ada dokumentasi yang dibuat).
     */
    public function isAwaitingConservation(): bool
    {
        return $this->requiresConservation()
            && $this->isScheduled()
            && $this->conservationAction === null;
    }

    /** Langkah dokumentasi konservasi berikutnya. */
    public function getConservationWorkflowStep(): ?string
    {
        if (! $this->requiresConservation()) {
            return null;
        }

        $action = $this->conservationAction;

        if (! $action) {
            return 'mulai';
        }

        if ($action->status === ConservationAction::STATUS_SELESAI) {
            return 'selesai';
        }

        if (! $action->plan) {
            return 'rencana';
        }

        if ($action->implementations->isEmpty()) {
            return 'pelaksanaan';
        }

        if (! $action->result) {
            return 'hasil';
        }

        return 'selesai';
    }

    public function getConservationWorkflowLabelAttribute(): ?string
    {
        return match ($this->getConservationWorkflowStep()) {
            'mulai'       => 'Belum Ada Catatan',
            'rencana'     => 'Isi Rencana',
            'pelaksanaan' => 'Catat Pelaksanaan',
            'hasil'       => 'Catat Hasil',
            'selesai'     => 'Dokumentasi Lengkap',
            default       => null,
        };
    }

    public function getConservationActionUrlAttribute(): ?string
    {
        $step = $this->getConservationWorkflowStep();
        $action = $this->conservationAction;

        if (! $step || $step === 'mulai' || ! $action) {
            return $action ? route('konservasi.tindakan.show', $action) : null;
        }

        return match ($step) {
            'rencana'     => route('konservasi.tindakan.plan', $action),
            'pelaksanaan' => route('konservasi.tindakan.pelaksanaan', $action),
            'hasil'       => route('konservasi.tindakan.hasil', $action),
            'selesai'     => route('konservasi.tindakan.show', $action),
            default       => route('konservasi.tindakan.show', $action),
        };
    }

    public function syncStatusFromImplementation(
        \Carbon\CarbonInterface|string|null $tanggalPelaksanaan,
    ): void {
        if (! $this->requiresConservation()) {
            return;
        }

        if ($this->status === self::STATUS_DIBATALKAN) {
            return;
        }

        if ($tanggalPelaksanaan === null) {
            return;
        }

        $pelaksanaan = \Carbon\Carbon::parse($tanggalPelaksanaan)->startOfDay();
        $jadwal = $this->jadwal_tanggal?->copy()?->startOfDay();

        if (! $jadwal) {
            return;
        }

        if ($pelaksanaan->greaterThan($jadwal)) {
            $this->update([
                'status' => self::STATUS_TERJADWAL,
                'tanggal_selesai' => null,
                'catatan_penyelesaian' => 'Pelaksanaan konservasi terlambat dari jadwal yang telah ditetapkan.',
            ]);

            return;
        }

        $this->update([
            'status' => self::STATUS_SELESAI,
            'tanggal_selesai' => $pelaksanaan->toDateString(),
            'catatan_penyelesaian' => 'Pelaksanaan konservasi ditandai selesai karena tanggal pelaksanaan sesuai atau sebelum jadwal.',
        ]);
    }

    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_TERJADWAL;
    }

    public function isOverdue(): bool
    {
        return $this->isScheduled()
            && $this->jadwal_tanggal->isPast()
            && ! $this->jadwal_tanggal->isToday();
    }

    public function getJadwalIndikatorWaktuAttribute(): ?string
    {
        if ($this->status === self::STATUS_SELESAI) {
            $selesai = ($this->tanggal_selesai ?? $this->jadwal_tanggal)->startOfDay();
            $today = today();

            if ($selesai->equalTo($today)) {
                return 'Selesai hari ini';
            }

            if ($selesai->greaterThan($today)) {
                $hari = (int) $today->diffInDays($selesai);

                return $hari === 1 ? 'Selesai 1 hari lagi' : "Selesai {$hari} hari lagi";
            }

            $hari = (int) $selesai->diffInDays($today);

            return $hari === 1 ? 'Selesai 1 hari lalu' : "Selesai {$hari} hari lalu";
        }

        if ($this->status === self::STATUS_DIBATALKAN) {
            return 'Dibatalkan';
        }

        $jadwal = $this->jadwal_tanggal->startOfDay();
        $today = today();

        if ($jadwal->equalTo($today)) {
            return 'Hari ini';
        }

        if ($jadwal->lessThan($today)) {
            $hari = (int) $jadwal->diffInDays($today);

            return $hari === 1 ? 'Terlambat 1 hari' : "Terlambat {$hari} hari";
        }

        $hari = (int) $today->diffInDays($jadwal);

        return $hari === 1 ? '1 hari lagi' : "{$hari} hari lagi";
    }

    public function getJadwalIndikatorBadgeClassAttribute(): string
    {
        if ($this->status === self::STATUS_SELESAI) {
            return 'bg-green-50 text-green-700 ring-green-200';
        }

        if ($this->status === self::STATUS_DIBATALKAN) {
            return 'bg-gray-50 text-gray-600 ring-gray-200';
        }

        $jadwal = $this->jadwal_tanggal->startOfDay();
        $today = today();

        if ($jadwal->lessThan($today)) {
            return 'bg-red-50 text-red-700 ring-red-200';
        }

        if ($jadwal->equalTo($today)) {
            return 'bg-indigo-50 text-indigo-700 ring-indigo-200';
        }

        $hari = (int) $today->diffInDays($jadwal);

        if ($hari <= 3) {
            return 'bg-amber-50 text-amber-700 ring-amber-200';
        }

        if ($hari <= 7) {
            return 'bg-yellow-50 text-yellow-700 ring-yellow-200';
        }

        return 'bg-slate-50 text-slate-600 ring-slate-200';
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_TERJADWAL);
    }

    public function scopeDueToday(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_TERJADWAL)
            ->whereDate('jadwal_tanggal', today());
    }

    public function scopeDueTomorrow(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_TERJADWAL)
            ->whereDate('jadwal_tanggal', today()->addDay());
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_TERJADWAL)
            ->whereDate('jadwal_tanggal', '<', today());
    }

    /**
     * Jadwal penanganan kerusakan yang masih terjadwal namun belum memiliki
     * tindakan konservasi (belum ada dokumentasi yang dibuat).
     */
    public function scopeAwaitingConservation(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_TERJADWAL)
            ->where('jenis_perawatan', self::JENIS_PENANGANAN_KERUSAKAN)
            ->whereDoesntHave('conservationAction');
    }

    public static function resolvePrioritas(?string $jenisPerawatan, ?string $kondisiSumber = null): string
    {
        if ($jenisPerawatan === self::JENIS_PENANGANAN_KERUSAKAN || $kondisiSumber === 'rusak_berat') {
            return self::PRIORITAS_TINGGI;
        }

        if ($jenisPerawatan === self::JENIS_PEMERIKSAAN_ULANG || $kondisiSumber === 'rusak_ringan') {
            return self::PRIORITAS_SEDANG;
        }

        return self::PRIORITAS_RENDAH;
    }

    public static function prioritasRankExpression(): string
    {
        return "CASE
            WHEN jenis_perawatan = 'penanganan_kerusakan' THEN 'tinggi'
            WHEN EXISTS (
                SELECT 1 FROM kondisi_koleksis kk
                WHERE kk.id = perawatan_koleksis.kondisi_koleksi_id
                AND kk.kondisi = 'rusak_berat'
            ) THEN 'tinggi'
            WHEN jenis_perawatan = 'pemeriksaan_ulang' THEN 'sedang'
            WHEN EXISTS (
                SELECT 1 FROM kondisi_koleksis kk
                WHERE kk.id = perawatan_koleksis.kondisi_koleksi_id
                AND kk.kondisi = 'rusak_ringan'
            ) THEN 'sedang'
            ELSE 'rendah'
        END";
    }

    public static function prioritasRankScoreExpression(): string
    {
        return "CASE
            WHEN jenis_perawatan = 'penanganan_kerusakan' THEN 3
            WHEN EXISTS (
                SELECT 1 FROM kondisi_koleksis kk
                WHERE kk.id = perawatan_koleksis.kondisi_koleksi_id
                AND kk.kondisi = 'rusak_berat'
            ) THEN 3
            WHEN jenis_perawatan = 'pemeriksaan_ulang' THEN 2
            WHEN EXISTS (
                SELECT 1 FROM kondisi_koleksis kk
                WHERE kk.id = perawatan_koleksis.kondisi_koleksi_id
                AND kk.kondisi = 'rusak_ringan'
            ) THEN 2
            ELSE 1
        END";
    }

    public static function statusRankExpression(): string
    {
        return "CASE status
            WHEN 'terjadwal' THEN 0
            WHEN 'dibatalkan' THEN 1
            WHEN 'selesai' THEN 2
            ELSE 3
        END";
    }

    public function scopeWherePrioritas(Builder $query, string $prioritas): Builder
    {
        if (! isset(self::PRIORITAS_OPTIONS[$prioritas])) {
            return $query;
        }

        return $query->whereRaw('(' . self::prioritasRankExpression() . ') = ?', [$prioritas]);
    }

    public function getLabelStatusAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? ucfirst($this->status);
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_TERJADWAL  => 'bg-blue-100 text-blue-800',
            self::STATUS_SELESAI    => 'bg-green-100 text-green-800',
            self::STATUS_DIBATALKAN => 'bg-red-100 text-red-800',
            default      => 'bg-gray-100 text-gray-700',
        };
    }

    // Jadwal yang melewati hari ini dan masih terjadwal = terlambat
    public function getIsTerlambatAttribute(): bool
    {
        return $this->isOverdue();
    }

    public function getPrioritasAttribute(): string
    {
        $kondisi = $this->relationLoaded('kondisiSumber')
            ? $this->kondisiSumber?->kondisi
            : $this->kondisiSumber()->value('kondisi');

        return self::resolvePrioritas($this->jenis_perawatan, $kondisi);
    }

    public function getLabelPrioritasAttribute(): string
    {
        return self::PRIORITAS_OPTIONS[$this->prioritas] ?? ucfirst($this->prioritas);
    }

    public function getPrioritasBadgeClassAttribute(): string
    {
        return match ($this->prioritas) {
            self::PRIORITAS_TINGGI => 'bg-red-100 text-red-800',
            self::PRIORITAS_SEDANG => 'bg-amber-100 text-amber-800',
            default                => 'bg-gray-100 text-gray-700',
        };
    }

    public function getEstimasiDurasiLabelAttribute(): ?string
    {
        if ($this->estimasi_durasi_menit === null) {
            return null;
        }

        $hours = intdiv($this->estimasi_durasi_menit, 60);
        $minutes = $this->estimasi_durasi_menit % 60;

        if ($hours > 0 && $minutes > 0) {
            return sprintf('%d jam %d menit', $hours, $minutes);
        }

        if ($hours > 0) {
            return sprintf('%d jam', $hours);
        }

        return sprintf('%d menit', $minutes);
    }

    public function calculateNextScheduleDate(): ?\Illuminate\Support\Carbon
    {
        if ($this->frekuensi === 'sekali') {
            return null;
        }

        $base = $this->tanggal_selesai ?? $this->jadwal_tanggal;

        return match ($this->frekuensi) {
            'bulanan'  => $base->copy()->addMonth(),
            'triwulan' => $base->copy()->addMonths(3),
            'tahunan'  => $base->copy()->addYear(),
            default    => null,
        };
    }

    /** @return array<string, mixed>|null */
    public static function buildConservationFollowUpSuggestion(ConservationAction $action, array $resultData): ?array
    {
        $action->loadMissing(['koleksi', 'perawatanKoleksi']);

        if (! $action->koleksi || ! $action->kondisi_koleksi_id) {
            return null;
        }

        $catatanParts = ['Tindak lanjut konservasi setelah evaluasi "Perlu Tindak Lanjut".'];

        if (! empty($resultData['catatan_akhir'])) {
            $catatanParts[] = $resultData['catatan_akhir'];
        }

        $perawatan = $action->perawatanKoleksi;

        return [
            'koleksi_id'            => $action->koleksi_id,
            'koleksi_nama'          => $action->koleksi->nama,
            'kondisi_koleksi_id'    => $action->kondisi_koleksi_id,
            'jenis_perawatan'       => self::JENIS_PENANGANAN_KERUSAKAN,
            'frekuensi'             => 'sekali',
            'jadwal_tanggal'        => today()->addWeeks(2)->toDateString(),
            'penanggung_jawab'      => $perawatan?->penanggung_jawab,
            'estimasi_durasi_menit' => $perawatan?->estimasi_durasi_menit,
            'catatan'               => implode(' ', $catatanParts),
        ];
    }

    /** @return array<string, mixed>|null */
    public function buildNextScheduleSuggestion(): ?array
    {
        $nextDate = $this->calculateNextScheduleDate();

        if (! $nextDate) {
            return null;
        }

        $this->loadMissing('koleksi');

        $suggestedDate = max(today(), $nextDate);

        return [
            'koleksi_id'               => $this->koleksi_id,
            'koleksi_nama'             => $this->koleksi->nama,
            'jenis_perawatan'          => $this->jenis_perawatan,
            'frekuensi'                => $this->frekuensi,
            'label_frekuensi'          => $this->label_frekuensi,
            'jadwal_tanggal'           => $suggestedDate->toDateString(),
            'penanggung_jawab'      => $this->penanggung_jawab,
            'estimasi_durasi_menit' => $this->estimasi_durasi_menit,
        ];
    }

    /** @return \Illuminate\Support\Collection<int, User> */
    public function resolveReminderRecipients(): \Illuminate\Support\Collection
    {
        if (! $this->created_by) {
            return collect();
        }

        $user = User::find($this->created_by);

        return $user ? collect([$user]) : collect();
    }
}