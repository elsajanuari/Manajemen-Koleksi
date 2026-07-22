<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    protected $fillable = [
        'zone_name',
        'description',
        'manager_rate',
        'is_free',
    ];

    protected $casts = [
        'manager_rate' => 'decimal:0',
        'is_free'      => 'boolean',
    ];

    // ── Relasi ke mapping provinsi ───────────────────────────────
    public function provinces(): HasMany
    {
        return $this->hasMany(ShippingZoneProvince::class, 'zone_id');
    }

    // ── Relasi ke pembelian ──────────────────────────────────────
    public function pembelians(): HasMany
    {
        return $this->hasMany(Pembelian::class, 'shipping_zone_id');
    }

    // ── Helper: format harga ─────────────────────────────────────
    public function getFormattedRateAttribute(): string
    {
        if ($this->is_free) return 'Gratis';
        return 'Rp ' . number_format($this->manager_rate, 0, ',', '.');
    }
}