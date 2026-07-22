<?php

namespace App\Services;

use App\Models\PemesananTiket;
use App\Models\TicketQuota;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PemesananTiketModifikasiService
{
    public static function batasWaktuModifikasi(PemesananTiket $pemesanan): Carbon
    {
        return $pemesanan->tanggal_pemesanan
            ->copy()
            ->startOfDay()
            ->subHours((int) config('museum.batas_modifikasi_jam', 48));
    }

    public static function masihDalamBatasWaktu(PemesananTiket $pemesanan): bool
    {
        return now()->lte(self::batasWaktuModifikasi($pemesanan));
    }

    public static function dapatReschedule(PemesananTiket $pemesanan): bool
    {
        $pemesanan->loadMissing('ticket');

        if (! $pemesanan->ticket->boleh_reschedule) {
            return false;
        }

        if ($pemesanan->isCancelled() || $pemesanan->isTiketTerpakai()) {
            return false;
        }

        if (! in_array($pemesanan->status, ['lunas', 'menunggu_pembayaran'], true)) {
            return false;
        }

        return self::masihDalamBatasWaktu($pemesanan);
    }

    public static function dapatCancel(PemesananTiket $pemesanan): bool
    {
        $pemesanan->loadMissing('ticket');

        if (! $pemesanan->ticket->boleh_cancel) {
            return false;
        }

        if ($pemesanan->isCancelled() || $pemesanan->isTiketTerpakai()) {
            return false;
        }

        if (! in_array($pemesanan->status, ['lunas', 'menunggu_pembayaran'], true)) {
            return false;
        }

        return self::masihDalamBatasWaktu($pemesanan);
    }

    public static function pesanBatasWaktu(PemesananTiket $pemesanan): string
    {
        $jam = (int) config('museum.batas_modifikasi_jam', 48);

        return 'Perubahan hanya dapat dilakukan paling lambat ' . $jam
            . ' jam sebelum tanggal kunjungan (batas: '
            . self::batasWaktuModifikasi($pemesanan)->locale('id')->translatedFormat('d F Y H:i') . ').';
    }

    public static function releaseKuota(PemesananTiket $pemesanan, ?string $tanggal = null): void
    {
        if (! $pemesanan->isPaid()) {
            return;
        }

        $tanggal = $tanggal ?? $pemesanan->tanggal_pemesanan->toDateString();

        $quota = TicketQuota::query()
            ->where('ticket_id', $pemesanan->ticket_id)
            ->where('tanggal', $tanggal)
            ->first();

        if ($quota) {
            $quota->update([
                'kuota_terjual' => max(0, $quota->kuota_terjual - $pemesanan->jumlah_tiket),
            ]);
        }
    }

    public static function reserveKuota(PemesananTiket $pemesanan, string $tanggalBaru): bool
    {
        if (! $pemesanan->isPaid()) {
            return true;
        }

        $quota = TicketQuota::query()
            ->where('ticket_id', $pemesanan->ticket_id)
            ->where('tanggal', $tanggalBaru)
            ->first();

        if (! $quota || $quota->kuota_sisa < $pemesanan->jumlah_tiket) {
            return false;
        }

        $quota->update([
            'kuota_terjual' => $quota->kuota_terjual + $pemesanan->jumlah_tiket,
        ]);

        return true;
    }

    /**
     * Validasi tanggal baru untuk reschedule (DENGAN CEK HARI LIBUR)
     */
    public static function validasiTanggalBaru(PemesananTiket $pemesanan, string $tanggalBaru): ?string
    {
        if ($tanggalBaru === $pemesanan->tanggal_pemesanan->toDateString()) {
            return 'Pilih tanggal kunjungan yang berbeda dari tanggal saat ini.';
        }

        $visit = Carbon::parse($tanggalBaru)->startOfDay();
        if ($visit->lt(now()->startOfDay())) {
            return 'Tanggal kunjungan tidak boleh di masa lalu.';
        }

        $pemesanan->loadMissing('ticket');
        
        // CEK APAKAH TANGGAL BARU ADALAH HARI LIBUR NASIONAL
        if (TicketQuota::isHoliday($tanggalBaru, $visit->year)) {
            $holidayName = TicketQuota::getHolidayName($tanggalBaru, $visit->year);
            return "Tanggal {$visit->locale('id')->translatedFormat('d F Y')} adalah hari libur nasional ({$holidayName}). Museum tutup, silakan pilih tanggal lain.";
        }
        
        $quota = $pemesanan->ticket->quotas()
            ->where('tanggal', $tanggalBaru)
            ->first();

        if (! $quota || $quota->kuota_sisa < $pemesanan->jumlah_tiket) {
            return 'Kuota pada tanggal tersebut tidak mencukupi.';
        }

        $batas = $visit->copy()->subHours((int) config('museum.batas_modifikasi_jam', 48));
        if (now()->gt($batas)) {
            return 'Tanggal baru tidak memenuhi batas waktu minimal ' . config('museum.batas_modifikasi_jam', 48) . ' jam sebelum kunjungan.';
        }

        return null;
    }

    public static function reschedule(PemesananTiket $pemesanan, string $tanggalBaru): void
    {
        DB::transaction(function () use ($pemesanan, $tanggalBaru): void {
            /** @var PemesananTiket $row */
            $row = PemesananTiket::query()->lockForUpdate()->findOrFail($pemesanan->id);

            if (! self::dapatReschedule($row)) {
                throw new \RuntimeException('Pemesanan ini tidak dapat di-reschedule.');
            }

            $error = self::validasiTanggalBaru($row, $tanggalBaru);
            if ($error) {
                throw new \RuntimeException($error);
            }

            $tanggalLama = $row->tanggal_pemesanan->toDateString();

            if ($row->isPaid()) {
                self::releaseKuota($row, $tanggalLama);
                if (! self::reserveKuota($row, $tanggalBaru)) {
                    self::reserveKuota($row, $tanggalLama);
                    throw new \RuntimeException('Kuota pada tanggal baru tidak mencukupi.');
                }
            }

            $row->forceFill([
                'tanggal_pemesanan' => $tanggalBaru,
                'reschedule_pada' => now(),
            ])->save();
        });
    }

    public static function batalkanDenganRefund(PemesananTiket $pemesanan): void
    {
        DB::transaction(function () use ($pemesanan): void {
            /** @var PemesananTiket $row */
            $row = PemesananTiket::query()->lockForUpdate()->findOrFail($pemesanan->id);

            if (! self::dapatCancel($row)) {
                throw new \RuntimeException('Pemesanan ini tidak dapat dibatalkan.');
            }

            if ($row->isPaid()) {
                self::releaseKuota($row);
                
                $row->forceFill([
                    'status' => 'dibatalkan',
                    'dibatalkan_pada' => now(),
                ])->save();

                return;
            }

            if ($row->isWaitingPayment()) {
                $row->forceFill([
                    'status' => 'dibatalkan',
                    'dibatalkan_pada' => now(),
                ])->save();
            }
        });
    }
}