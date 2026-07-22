<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => 'Belum Dibayar',
            self::PENDING => 'Menunggu Pembayaran',
            self::PAID => 'Lunas',
            self::FAILED => 'Gagal',
            self::EXPIRED => 'Kadaluarsa',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::UNPAID => 'bg-slate-100 text-slate-700',
            self::PENDING => 'bg-amber-100 text-amber-700',
            self::PAID => 'bg-emerald-100 text-emerald-700',
            self::FAILED => 'bg-rose-100 text-rose-700',
            self::EXPIRED => 'bg-rose-100 text-rose-700',
        };
    }
}
