<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConservationAction;
use App\Models\PerawatanKoleksi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Koleksi extends Model
{
    use HasFactory;

    public static function statusSewaOptions(): array
    {
        return [
            'tidak' => [
                'label' => 'Tidak Bisa Disewa/Dibeli',
                'description' => 'Koleksi hanya untuk dipamerkan di museum. Tidak ditawarkan untuk disewa atau dibeli.',
            ],
            'sewa' => [
                'label' => 'Dapat Disewakan',
                'description' => 'Koleksi dapat disewakan oleh pengunjung.',
            ],
            'beli' => [
                'label' => 'Dapat Dibeli',
                'description' => 'Koleksi dapat dibeli oleh pengunjung.',
            ],
            'sewa_beli' => [
                'label' => 'Dapat Disewakan & Dibeli',
                'description' => 'Koleksi dapat disewakan dan dibeli oleh pengunjung.',
            ],
        ];
    }

    public static function lokasiOptions(): array
    {
        return [
            'disimpan' => [
                'label' => 'Ruang Penyimpanan',
                'description' => 'Koleksi disimpan di gudang atau ruang penyimpanan museum.',
            ],
            'dipamerkan' => [
                'label' => 'Ruang Pameran',
                'description' => 'Koleksi sedang dipajang di ruang pameran museum.',
            ],
        ];
    }

    public static function labelStatusSewa(?string $value): string
    {
        if ($value === 'disewa') {
            return static::statusSewaOptions()['tidak']['label'];
        }

        return static::statusSewaOptions()[$value]['label'] ?? (string) $value;
    }

    /**
     * Label ketersediaan sewa/beli untuk tampilan pengunjung (gallery).
     */
    public static function statusSewaPublicOptions(): array
    {
        return [
            'tidak' => ['label' => 'Hanya dipamerkan'],
            'sewa' => ['label' => 'Dapat disewa'],
            'beli' => ['label' => 'Dapat dibeli'],
            'sewa_beli' => ['label' => 'Dapat disewa & dibeli'],
        ];
    }

    public static function labelStatusSewaPublik(?string $value): string
    {
        if ($value === 'disewa') {
            return static::statusSewaPublicOptions()['tidak']['label'];
        }

        return static::statusSewaPublicOptions()[$value]['label'] ?? '-';
    }

    public function dapatDisewa(): bool
    {
        return in_array($this->status_sewa, ['sewa', 'sewa_beli'], true);
    }

    public function dapatDibeli(): bool
    {
        return in_array($this->status_sewa, ['beli', 'sewa_beli'], true);
    }

    public static function labelLokasi(?string $value): string
    {
        return static::lokasiOptions()[$value]['label'] ?? (string) $value;
    }

    public static function lokasiBadgeInfo(?string $lokasi): array
    {
        $options = [
            'dipamerkan' => [
                'label' => static::lokasiOptions()['dipamerkan']['label'],
                'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
                'bgColor' => 'bg-indigo-100',
                'textColor' => 'text-indigo-800',
            ],
            'disimpan' => [
                'label' => static::lokasiOptions()['disimpan']['label'],
                'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4',
                'bgColor' => 'bg-gray-100',
                'textColor' => 'text-gray-700',
            ],
        ];

        return $options[$lokasi] ?? $options['disimpan'];
    }

    protected $fillable = [
        'nama',
        'kategori',
        'nomor_inventaris',
        'seniman',
        'tahun',
        'teknik_media',
        'ukuran_lukisan',
        'deskripsi',
        'status_sewa',
        'lokasi',
        'kondisi',
        'foto',
        'price',
        'artist_name',
        'size',
        'media',
        'condition',
        'slug',
        'daily_rate',
        'sale_price',
        'for_sale',
        'for_rent',
        'weight_gram',
        'available',
        'gallery_paths',
        'extra_info',
    ];

    protected $casts = [
        'daily_rate'    => 'integer',
        'sale_price'    => 'integer',
        'for_sale'      => 'boolean',
        'for_rent'      => 'boolean',
        'available'     => 'boolean',
        'gallery_paths' => 'array',
        'extra_info'    => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function getTitleAttribute(): string
    {
        return $this->nama ?? '';
    }

    public function getArtistAttribute(): string
    {
        return $this->seniman ?? $this->artist_name ?? '';
    }

    public function getImagePathAttribute(): ?string
    {
        return $this->foto;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->foto) return null;
        if (str_starts_with($this->foto, 'http')) return $this->foto;
        return asset('storage/' . $this->foto);
    }

    public function getDailyRateAttribute(): int
    {
        return (int) ($this->attributes['daily_rate'] ?? 0);
    }

    public function getCategoryAttribute(): ?string
    {
        return $this->kategori;
    }

    public function getYearCreatedAttribute(): ?string
    {
        return $this->tahun;
    }

    public function getYearAttribute(): ?string
    {
        return $this->tahun;
    }

    public function getMediaAttribute(): ?string
    {
        return $this->teknik_media ?? $this->attributes['media'] ?? null;
    }

    public function getDimensionsAttribute(): ?string
    {
        return $this->ukuran_lukisan ?? $this->size ?? null;
    }

    public function getCollectionNumberAttribute(): ?string
    {
        return $this->nomor_inventaris;
    }

    public function isForSale(): bool
    {
        if (! $this->dapatDibeli()) {
            return false;
        }

        return ! is_null($this->sale_price) && (int) $this->sale_price > 0;
    }

    // Relasi ke penyewaan (via painting)
    public function paintings(): HasMany
    {
        return $this->hasMany(Painting::class, 'koleksi_id');
    }

    public function penyewaans(): HasMany
{
    return $this->hasMany(Penyewaan::class, 'koleksi_id');
}

    public function pembelians(): HasMany
    {
        return $this->hasMany(\App\Models\Pembelian::class, 'koleksi_id');
    }

    /**
     * Semua riwayat pemeriksaan kondisi, terbaru dahulu.
     */
    public function kondisis(): HasMany
    {
        return $this->hasMany(KondisiKoleksi::class)
            ->orderByDesc('tanggal_periksa')
            ->orderByDesc('id');
    }

    /**
     * Pemeriksaan kondisi paling terakhir.
     */
    public function kondisiTerakhir(): HasOne
    {
        return $this->hasOne(KondisiKoleksi::class)
            ->orderByDesc('tanggal_periksa')
            ->orderByDesc('id');
    }

    public static function getNextSequenceForCategory(string $kategori, ?string $year = null): int
    {
        $year = $year ?? date('Y');

        $lastSequence = static::query()
            ->where('kategori', $kategori)
            ->whereYear('created_at', $year)
            ->whereNotNull('nomor_inventaris')
            ->get()
            ->map(fn ($item) => (int) preg_replace('/^.*-(\d+)$/', '$1', $item->nomor_inventaris))
            ->max();

        return $lastSequence ? $lastSequence + 1 : 1;
    }

    public static function generateNomorInventaris(string $kategori, ?string $year = null, ?int $sequence = null): string
    {
        $categoryCodes = [
            'lukisan' => 'LUK',
            'buku' => 'BUK',
        ];

        $kategoriCode = $categoryCodes[$kategori] ?? strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $kategori), 0, 3));
        $year = $year ?? date('Y');
        $sequence = $sequence ?? self::getNextSequenceForCategory($kategori, $year);

        return sprintf('%s-%s-%s', $kategoriCode, $year, str_pad($sequence, 5, '0', STR_PAD_LEFT));
    }

    public function getCurrentKondisiAttribute(): ?string
    {
        if ($this->relationLoaded('kondisiTerakhir') && $this->kondisiTerakhir) {
            return $this->kondisiTerakhir->label_kondisi;
        }

        return $this->kondisi ? ucfirst(str_replace('_', ' ', $this->kondisi)) : null;
    }

    public function getFormattedNomorInventarisAttribute(): string
    {
        return $this->nomor_inventaris ?? 'Belum ditetapkan';
    }

    public function getStatusBadgeInfo(): array
    {
        $statusInfo = [
            'tidak' => [
                'label' => 'Tidak Bisa Disewa/Dibeli',
                'icon' => 'M13 16H11v-4h2m0-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'bgColor' => 'bg-gray-100',
                'textColor' => 'text-gray-700',
                'dotColor' => 'bg-gray-400',
                'description' => 'Koleksi hanya untuk dipamerkan di museum',
            ],
            'sewa' => [
                'label' => 'Dapat Disewakan',
                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m0 0v10l8 4',
                'bgColor' => 'bg-blue-100',
                'textColor' => 'text-blue-800',
                'dotColor' => 'bg-blue-500',
                'description' => 'Koleksi dapat disewakan oleh pengunjung',
            ],
            'beli' => [
                'label' => 'Dapat Dibeli',
                'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'bgColor' => 'bg-purple-100',
                'textColor' => 'text-purple-800',
                'dotColor' => 'bg-purple-500',
                'description' => 'Koleksi dapat dibeli oleh pengunjung',
            ],
            'sewa_beli' => [
                'label' => 'Dapat Disewakan & Dibeli',
                'icon' => 'M14 10h4.764a2 2 0 011.789 2.894l-3.646 7.23a2 2 0 01-1.789 1.106H2a2 2 0 01-2-2V8a2 2 0 012-2h2.4a1 1 0 00.988-.577l1.348-2.696a1 1 0 00-.986-1.404H10a2 2 0 00-2 2v2H5a2 2 0 00-2 2v3m14-5h2a2 2 0 012 2v3m-6-3a3 3 0 11-6 0 3 3 0 016 0z',
                'bgColor' => 'bg-indigo-100',
                'textColor' => 'text-indigo-800',
                'dotColor' => 'bg-indigo-500',
                'description' => 'Koleksi dapat disewakan dan dibeli oleh pengunjung',
            ],
        ];

        return $statusInfo[$this->status_sewa] ?? $statusInfo['tidak'];
    }

    public function getStatusBadges(): array
    {
        if ($this->status_sewa === 'sewa_beli') {
            return [
                'sewa' => $this->getStatusBadgeFor('sewa'),
                'beli' => $this->getStatusBadgeFor('beli'),
            ];
        }

        return [
            $this->getStatusBadgeFor($this->status_sewa),
        ];
    }

    public function getStatusBadgeFor(string $status): array
    {
        $statusInfo = [
            'tidak' => [
                'label' => 'Tidak Bisa Disewa/Dibeli',
                'icon' => 'M13 16H11v-4h2m0-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'bgColor' => 'bg-gray-100',
                'textColor' => 'text-gray-700',
                'dotColor' => 'bg-gray-400',
            ],
            'sewa' => [
                'label' => 'Dapat Disewakan',
                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m0 0v10l8 4',
                'bgColor' => 'bg-blue-100',
                'textColor' => 'text-blue-800',
                'dotColor' => 'bg-blue-500',
            ],
            'beli' => [
                'label' => 'Dapat Dibeli',
                'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'bgColor' => 'bg-purple-100',
                'textColor' => 'text-purple-800',
                'dotColor' => 'bg-purple-500',
            ],
            'sewa_beli' => [
                'label' => 'Dapat Disewakan & Dibeli',
                'icon' => 'M14 10h4.764a2 2 0 011.789 2.894l-3.646 7.23a2 2 0 01-1.789 1.106H2a2 2 0 01-2-2V8a2 2 0 012-2h2.4a1 1 0 00.988-.577l1.348-2.696a1 1 0 00-.986-1.404H10a2 2 0 00-2 2v2H5a2 2 0 00-2 2v3m14-5h2a2 2 0 012 2v3m-6-3a3 3 0 11-6 0 3 3 0 016 0z',
                'bgColor' => 'bg-indigo-100',
                'textColor' => 'text-indigo-800',
                'dotColor' => 'bg-indigo-500',
            ],
        ];

        return $statusInfo[$status] ?? $statusInfo['tidak'];
    }

    public function perawatans(): HasMany
    {
        return $this->hasMany(PerawatanKoleksi::class)->orderByDesc('jadwal_tanggal');
    }

    public function scopeEligibleForPemeliharaan(Builder $query): Builder
    {
        return $query
            ->where(function ($query) {
                $query->whereNull('kondisi')
                      ->orWhere('kondisi', 'baik');
            })
            ->whereDoesntHave('perawatans', fn ($query) => $query->where('status', PerawatanKoleksi::STATUS_TERJADWAL));
    }

    public function scopeEligibleForPemeriksaanUlang(Builder $query): Builder
    {
        return $query
            ->where(function ($query) {
                $query->whereNull('kondisi')
                      ->orWhere('kondisi', 'rusak_ringan');
            })
            ->whereDoesntHave('perawatans', fn ($query) => $query->where('status', PerawatanKoleksi::STATUS_TERJADWAL));
    }

    public function conservationActions(): HasMany
    {
        return $this->hasMany(ConservationAction::class)->orderByDesc('created_at');
    }
}
