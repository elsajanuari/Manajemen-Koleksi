<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositRefund extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'deposit_amount',
        'damage_deduction',
        'refund_amount',
        'bank_name',
        'account_number',
        'account_holder',
        'refund_date',
        'transfer_proof_path',
        'notes',
        'status',
        'processed_by',
    ];

    protected $casts = [
        'deposit_amount'   => 'integer',
        'damage_deduction' => 'integer',
        'refund_amount'    => 'integer',
        'refund_date'      => 'date',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class);
    }

    // ─── Accessors ────────────────────────────────────

    public function getTransferProofUrlAttribute(): ?string
    {
        return $this->transfer_proof_path
            ? asset('storage/' . $this->transfer_proof_path)
            : null;
    }

    // ─── Helpers ──────────────────────────────────────

    public function isProcessed(): bool
    {
        return $this->status === 'processed';
    }
}