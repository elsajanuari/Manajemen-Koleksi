<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConservationAction extends Model
{
    use HasFactory;

    public const TYPE_PREVENTIF = 'preventif';
    public const TYPE_KURATIF = 'kuratif';

    public const STATUS_DIRENCANAKAN = 'direncanakan';
    public const STATUS_SEDANG_BERJALAN = 'sedang_berjalan';
    public const STATUS_SELESAI = 'selesai';

    public const TYPE_OPTIONS = [
        self::TYPE_PREVENTIF => 'Preventif',
        self::TYPE_KURATIF => 'Kuratif',
    ];

    public const STATUS_OPTIONS = [
        self::STATUS_DIRENCANAKAN => 'Belum Ada Pelaksanaan',
        self::STATUS_SEDANG_BERJALAN => 'Pelaksanaan Tercatat',
        self::STATUS_SELESAI => 'Dokumentasi Lengkap',
    ];

    protected $fillable = [
        'koleksi_id',
        'kondisi_koleksi_id',
        'perawatan_koleksi_id',
        'jenis_konservasi',
        'status',
        'created_by',
    ];

    public function koleksi(): BelongsTo
    {
        return $this->belongsTo(Koleksi::class);
    }

    public function kondisiKoleksi(): BelongsTo
    {
        return $this->belongsTo(KondisiKoleksi::class, 'kondisi_koleksi_id');
    }

    public function perawatanKoleksi(): BelongsTo
    {
        return $this->belongsTo(PerawatanKoleksi::class, 'perawatan_koleksi_id');
    }

    public function plan(): HasOne
    {
        return $this->hasOne(ConservationPlan::class);
    }

    public function implementations(): HasMany
    {
        return $this->hasMany(ConservationImplementation::class)->orderByDesc('tanggal_pelaksanaan');
    }

    public function result(): HasOne
    {
        return $this->hasOne(ConservationResult::class);
    }

    public function getJenisKonservasiLabelAttribute(): string
    {
        return self::TYPE_OPTIONS[$this->jenis_konservasi] ?? ucfirst(str_replace('_', ' ', $this->jenis_konservasi));
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_DIRENCANAKAN => 'bg-blue-100 text-blue-800',
            self::STATUS_SEDANG_BERJALAN => 'bg-amber-100 text-amber-800',
            self::STATUS_SELESAI => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
