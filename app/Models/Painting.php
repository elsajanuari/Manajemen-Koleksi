<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Painting extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'artist',
        'category',
        'description',
        'year_created',
        'media',
        'dimensions',
        'daily_rate',
        'sale_price',
        'available',
        'image_path',
        'gallery_paths',
        'extra_info',
        'koleksi_id',
        'for_sale',
        'weight_gram',
    ];

    protected $casts = [
        'available' => 'boolean',
        'daily_rate' => 'integer',
        'sale_price' => 'integer',
        'gallery_paths' => 'array',
    ];

    // Di Painting.php — override accessor agar prioritaskan data koleksi
    public function getTitleAttribute(): string
    {
        return $this->linkedKoleksi?->nama ?? $this->attributes['title'] ?? '';
    }

    public function getArtistAttribute(): string  
    {
        return $this->linkedKoleksi?->seniman ?? $this->attributes['artist'] ?? '';
    }

    public function getImagePathAttribute(): ?string
    {
        if ($this->linkedKoleksi?->foto) {
            return $this->linkedKoleksi->foto;
        }
        return $this->attributes['image_path'] ?? null;
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->linkedKoleksi?->deskripsi ?? $this->attributes['description'] ?? null;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        return asset('paintings/'.$this->image_path);
    }

    /**
     * URL gambar tambahan untuk galeri (path relatif folder public/paintings atau URL absolut).
     */
    public function getGalleryImageUrlsAttribute(): array
    {
        $paths = $this->gallery_paths ?? [];
        if (! is_array($paths)) {
            return [];
        }

        return collect($paths)->map(function ($path) {
            if (! $path) {
                return null;
            }
            if (str_starts_with($path, 'http')) {
                return $path;
            }

            return asset('paintings/'.$path);
        })->filter()->values()->all();
    }

    public function penyewaan()
    {
        return $this->hasMany(Penyewaan::class);
    }

    /**
     * Entri koleksi penjualan (jika dihubungkan) untuk tombol Beli.
     */
    public function linkedKoleksi(): BelongsTo
    {
        return $this->belongsTo(Koleksi::class, 'koleksi_id');
    }

    public function isPurchasable(): bool
    {
        return $this->koleksi_id
            && $this->linkedKoleksi
            && $this->linkedKoleksi->isAvailableForPurchase();
    }

    public function isForSale(): bool
    {
        if ((bool) $this->for_sale) {
            return true;
        }

        return ! is_null($this->sale_price) && $this->sale_price > 0;
    }
 
    /**
     * Relasi ke pengajuan pembelian.
     */
    public function pembelians()
    {
        return $this->hasMany(\App\Models\Pembelian::class);
    }
}
