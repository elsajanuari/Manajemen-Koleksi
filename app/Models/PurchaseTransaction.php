<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseTransaction extends Model
{
    protected $fillable = [
        'transaction_code',
        'user_id',
        'koleksi_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'buyer_address',
        'price',
        'notes',
        'status',
        'cancelled_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Generate unique transaction code
     */
    public static function generateTransactionCode(): string
    {
        $date = now()->format('Ymd');
        $lastTransaction = static::whereDate('created_at', now()->toDateString())
            ->orderByDesc('id')
            ->first();
        
        $sequence = ($lastTransaction ? (int)substr($lastTransaction->transaction_code, -4) : 0) + 1;
        
        return 'TRX-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Relationship: User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Koleksi
     */
    public function koleksi(): BelongsTo
    {
        return $this->belongsTo(Koleksi::class);
    }

    /**
     * Check if transaction can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return $this->status === 'menunggu_verifikasi';
    }

    /**
     * Cancel the transaction
     */
    public function cancel(): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        // Update transaction status
        $this->status = 'dibatalkan';
        $this->cancelled_at = now();
        $this->save();

        // Update koleksi status back to available
        if ($this->koleksi) {
            $this->koleksi->update(['status_sewa' => 'tidak']);
        }

        return true;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'menunggu_verifikasi' => 'bg-yellow-100 text-yellow-800',
            'diproses' => 'bg-blue-100 text-blue-800',
            'selesai' => 'bg-green-100 text-green-800',
            'dibatalkan' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get readable status label
     */
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'diproses' => 'Diproses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => 'Unknown',
        };
    }
}
