<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class KondisiKoleksi extends Model
{
    use HasFactory;

    public const REKOMENDASI_OPTIONS = [
        'tidak_perlu_tindakan'  => 'Tidak Perlu Tindakan',
        'pemeliharaan'          => 'Pemeliharaan',
        'penanganan_kerusakan'  => 'Penanganan Kerusakan',
        'pemeriksaan_ulang'     => 'Pemeriksaan Ulang',
    ];

    public const KONDISI_OPTIONS = [
        'baik'         => 'Baik',
        'rusak_ringan' => 'Rusak Ringan',
        'rusak_berat'  => 'Rusak Berat',
    ];

    public const REKOMENDASI_TO_JENIS_PERAWATAN = [
        'pemeliharaan'         => 'pemeliharaan',
        'penanganan_kerusakan' => 'penanganan_kerusakan',
        'pemeriksaan_ulang'    => 'pemeriksaan_ulang',
    ];

    public const STATUS_REKOMENDASI_TIDAK_BERLAKU     = 'tidak_berlaku';
    public const STATUS_REKOMENDASI_MENUNGGU_JADWAL     = 'menunggu_jadwal';
    public const STATUS_REKOMENDASI_SUDAH_DIJADWALKAN   = 'sudah_dijadwalkan';
    public const STATUS_REKOMENDASI_SELESAI             = 'selesai';

    public const STATUS_REKOMENDASI_OPTIONS = [
        self::STATUS_REKOMENDASI_TIDAK_BERLAKU   => 'Tidak Berlaku',
        self::STATUS_REKOMENDASI_MENUNGGU_JADWAL => 'Menunggu Jadwal',
        self::STATUS_REKOMENDASI_SUDAH_DIJADWALKAN => 'Sudah Dijadwalkan',
        self::STATUS_REKOMENDASI_SELESAI         => 'Selesai',
    ];

    private const KONDISI_LEVEL = [
        'baik'        => 3,
        'rusak_ringan' => 2,
        'rusak_berat'  => 1,
    ];

    protected $fillable = [
        'koleksi_id',
        'perawatan_id',
        'tanggal_periksa',
        'kondisi',
        'pemeriksa',
        'catatan',
        'suhu',
        'kelembapan',
        'pencahayaan',
        'jenis_kerusakan',
        'kebersihan_lingkungan',
        'previous_status_sewa',
        'foto',
        'foto_sebelum',
        'foto_kondisi_saat_ini',
        'foto_kerusakan',
        'rekomendasi_tindak_lanjut',
        'is_manual',
    ];

    protected $casts = [
        'tanggal_periksa' => 'date',
        'suhu' => 'float',
        'kelembapan' => 'integer',
        'kebersihan_lingkungan' => 'string',
        'previous_status_sewa' => 'string',
        'is_manual' => 'boolean',
    ];

    public function koleksi(): BelongsTo
    {
        return $this->belongsTo(Koleksi::class);
    }

    public function perawatan(): BelongsTo
    {
        return $this->belongsTo(PerawatanKoleksi::class);
    }

    public function jadwalRekomendasi(): HasMany
    {
        return $this->hasMany(PerawatanKoleksi::class, 'kondisi_koleksi_id')->latest();
    }

    public function jadwalTerjadwal(): HasMany
    {
        return $this->hasMany(PerawatanKoleksi::class, 'kondisi_koleksi_id')
            ->where('status', PerawatanKoleksi::STATUS_TERJADWAL);
    }

    public function hasJadwalTerjadwal(): bool
    {
        if ($this->relationLoaded('jadwalRekomendasi')) {
            return $this->jadwalRekomendasi
                ->contains(fn (PerawatanKoleksi $jadwal) => $jadwal->isScheduled());
        }

        return $this->jadwalTerjadwal()->exists();
    }

    public function canBuatJadwal(): bool
    {
        return $this->hasRekomendasiUntukJadwal() && ! $this->hasJadwalTerjadwal();
    }

    public function getStatusRekomendasiAttribute(): string
    {
        if (! $this->hasRekomendasiUntukJadwal()) {
            return self::STATUS_REKOMENDASI_TIDAK_BERLAKU;
        }

        $jadwals = $this->relationLoaded('jadwalRekomendasi')
            ? $this->jadwalRekomendasi
            : $this->jadwalRekomendasi()->get();

        if ($jadwals->isEmpty()) {
            return self::STATUS_REKOMENDASI_MENUNGGU_JADWAL;
        }

        if ($jadwals->contains(fn (PerawatanKoleksi $jadwal) => $jadwal->isScheduled())) {
            return self::STATUS_REKOMENDASI_SUDAH_DIJADWALKAN;
        }

        if ($jadwals->contains(fn (PerawatanKoleksi $jadwal) => $jadwal->status === PerawatanKoleksi::STATUS_SELESAI)) {
            return self::STATUS_REKOMENDASI_SELESAI;
        }

        return self::STATUS_REKOMENDASI_MENUNGGU_JADWAL;
    }

    public function getLabelStatusRekomendasiAttribute(): string
    {
        return self::STATUS_REKOMENDASI_OPTIONS[$this->status_rekomendasi] ?? '-';
    }

    public function getStatusRekomendasiBadgeClassAttribute(): string
    {
        return match ($this->status_rekomendasi) {
            self::STATUS_REKOMENDASI_MENUNGGU_JADWAL   => 'bg-amber-100 text-amber-800',
            self::STATUS_REKOMENDASI_SUDAH_DIJADWALKAN => 'bg-blue-100 text-blue-800',
            self::STATUS_REKOMENDASI_SELESAI           => 'bg-green-100 text-green-800',
            default                                    => 'bg-gray-100 text-gray-700',
        };
    }

    public function hasRekomendasiUntukJadwal(): bool
    {
        return $this->rekomendasi_tindak_lanjut
            && $this->rekomendasi_tindak_lanjut !== 'tidak_perlu_tindakan'
            && isset(self::REKOMENDASI_TO_JENIS_PERAWATAN[$this->rekomendasi_tindak_lanjut]);
    }

    public function getJenisPerawatanDariRekomendasi(): ?string
    {
        return self::REKOMENDASI_TO_JENIS_PERAWATAN[$this->rekomendasi_tindak_lanjut] ?? null;
    }

    public function getLabelKondisiAttribute(): string
    {
        return self::KONDISI_OPTIONS[$this->kondisi] ?? ucfirst($this->kondisi);
    }

    public function getPencahayaanLabelAttribute(): string
    {
        return match ($this->pencahayaan) {
            'rendah' => 'Rendah',
            'sedang' => 'Sedang',
            'tinggi' => 'Tinggi',
            default  => $this->pencahayaan ? ucfirst($this->pencahayaan) : '-',
        };
    }

    public function getLingkunganSummaryAttribute(): string
    {
        $parts = [];

        if ($this->suhu !== null) {
            $parts[] = sprintf('Suhu %s°C', rtrim(rtrim(number_format($this->suhu, 2, '.', ''), '0'), '.'));
        }

        if ($this->kelembapan !== null) {
            $parts[] = sprintf('RH %s%%', $this->kelembapan);
        }

        if ($this->pencahayaan) {
            $parts[] = $this->pencahayaan_label;
        }

        return $parts ? implode(' · ', $parts) : '-';
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    public function getFotoSebelumUrlAttribute(): ?string
    {
        return $this->foto_sebelum ? asset('storage/' . $this->foto_sebelum) : null;
    }

    public function getFotoKondisiSaatIniUrlAttribute(): ?string
    {
        return $this->foto_kondisi_saat_ini ? asset('storage/' . $this->foto_kondisi_saat_ini) : null;
    }

    public function getFotoKerusakanUrlAttribute(): ?string
    {
        return $this->foto_kerusakan ? asset('storage/' . $this->foto_kerusakan) : null;
    }

    public function getLabelRekomendasiAttribute(): string
    {
        return self::REKOMENDASI_OPTIONS[$this->rekomendasi_tindak_lanjut] ?? '-';
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->kondisi) {
            'baik'        => 'bg-green-100 text-green-800',
            'rusak_ringan' => 'bg-amber-100 text-amber-800',
            'rusak_berat'  => 'bg-red-100 text-red-800',
            default        => 'bg-gray-100 text-gray-700',
        };
    }

    public function getPreviousInspection(): ?self
    {
        return $this->koleksi
            ->kondisis()
            ->where(function ($query) {
                $query->where('tanggal_periksa', '<', $this->tanggal_periksa)
                      ->orWhere(function ($sub) {
                          $sub->where('tanggal_periksa', $this->tanggal_periksa)
                              ->where('id', '<', $this->id);
                      });
            })
            ->orderByDesc('tanggal_periksa')
            ->orderByDesc('id')
            ->first();
    }

    public function getConditionComparison(): array
    {
        $previous = $this->getPreviousInspection();

        if (!$previous) {
            return [
                'previous_kondisi' => null,
                'current_kondisi' => $this->kondisi,
                'status_perubahan' => null,
                'label_previous' => 'Tidak ada',
                'label_current' => $this->label_kondisi,
                'label_status' => 'Pemeriksaan Pertama',
            ];
        }

        $previousLevel = self::KONDISI_LEVEL[$previous->kondisi] ?? 0;
        $currentLevel = self::KONDISI_LEVEL[$this->kondisi] ?? 0;

        if ($currentLevel > $previousLevel) {
            $status = 'meningkat';
            $labelStatus = 'Meningkat';
        } elseif ($currentLevel === $previousLevel) {
            $status = 'tetap';
            $labelStatus = 'Tetap';
        } else {
            $status = 'menurun';
            $labelStatus = 'Menurun';
        }

        return [
            'previous_kondisi' => $previous->kondisi,
            'current_kondisi' => $this->kondisi,
            'status_perubahan' => $status,
            'label_previous' => $previous->label_kondisi,
            'label_current' => $this->label_kondisi,
            'label_status' => $labelStatus,
            'previous_inspection' => $previous,
            'tanggal_sebelumnya' => $previous->tanggal_periksa->format('d M Y'),
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (self $inspection) {
            try {
                $koleksi = $inspection->koleksi;

                if (! $koleksi) {
                    return;
                }

                if (in_array($inspection->kondisi, ['rusak_ringan', 'rusak_berat'], true)) {
                    if ($inspection->previous_status_sewa === null && $koleksi->status_sewa !== 'tidak') {
                        $inspection->previous_status_sewa = $koleksi->status_sewa;
                        $inspection->saveQuietly();
                    }

                    if ($koleksi->status_sewa !== 'tidak') {
                        $koleksi->status_sewa = 'tidak';
                        $koleksi->save();
                    }
                }

                if ($inspection->kondisi === 'baik' && $inspection->previous_status_sewa) {
                    if ($koleksi->status_sewa === 'tidak') {
                        $allowed = ['tidak', 'sewa', 'beli', 'sewa_beli'];

                        if (in_array($inspection->previous_status_sewa, $allowed, true)) {
                            $koleksi->status_sewa = $inspection->previous_status_sewa;
                            $koleksi->save();
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Swallow exceptions to avoid breaking save flow; logging can be added later.
            }
        });
    }

}