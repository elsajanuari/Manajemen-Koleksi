<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'invoice_id',
        'order_id',
        'transaction_id',
        'payment_type',
        'gross_amount',
        'transaction_status',
        'paid_at',
        'payload',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'payload' => 'array',
    ];

    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class, 'penyewaan_id');
    }
}
