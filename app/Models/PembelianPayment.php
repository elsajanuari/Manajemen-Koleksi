<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembelianPayment extends Model
{
    protected $fillable = [
        'pembelian_id',
        'order_id',
        'gross_amount',
        'transaction_status',
        'payment_type',
        'transaction_id',
        'paid_at',
        'payload',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'payload' => 'array',
    ];

    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(Pembelian::class);
    }
}