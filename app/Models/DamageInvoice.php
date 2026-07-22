<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'invoice_number',
        'damage_type',
        'damage_level',
        'restoration_cost',
        'deposit_amount',
        'deposit_used',
        'additional_charge',
        'damage_notes',
        'order_id',
        'snap_token',
        'transaction_id',
        'payment_type',
        'transaction_status',
        'paid_at',
        'payload',
        'status',
        'created_by',
    ];

    protected $casts = [
        'restoration_cost'  => 'integer',
        'deposit_amount'    => 'integer',
        'deposit_used'      => 'integer',
        'additional_charge' => 'integer',
        'paid_at'           => 'datetime',
        'payload'           => 'array',
    ];

    // ─── Labels ───────────────────────────────────────

    public const DAMAGE_LEVEL_LABELS = [
        'ringan' => 'Ringan',
        'sedang' => 'Sedang',
        'berat'  => 'Berat',
    ];

    public const STATUS_LABELS = [
        'unpaid'                   => 'Belum Dibayar',
        'pending'                  => 'Menunggu Pembayaran',
        'paid'                     => 'Lunas',
        'failed'                   => 'Gagal',
        'expired'                  => 'Kedaluwarsa',
        'not_required'             => 'Tidak Diperlukan',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class);
    }

    // ─── Helpers ──────────────────────────────────────

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isNotRequired(): bool
    {
        return $this->status === 'not_required';
    }

    public function requiresAdditionalPayment(): bool
    {
        return $this->additional_charge > 0;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function getDamageLevelLabelAttribute(): string
    {
        return self::DAMAGE_LEVEL_LABELS[$this->damage_level] ?? $this->damage_level;
    }
}