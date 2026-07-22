<?php

namespace App\Services;

use App\Notifications\PemesananTiketRefundNotification;
use App\Notifications\PenyewaanStatusNotification;
use App\Notifications\PerawatanCreatedNotification;
use App\Notifications\PerawatanReminderNotification;
use App\Notifications\RentalReminderNotification;
use App\Notifications\SerahTerimaStatusNotification;
use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPresenter
{
    public function __construct(public DatabaseNotification $notification)
    {
    }

    public static function from(DatabaseNotification $notification): self
    {
        return new self($notification);
    }

    public function category(): string
    {
        $data = $this->notification->data;

        if (! empty($data['category'])) {
            return $data['category'];
        }

        return match ($this->notification->type) {
            PerawatanCreatedNotification::class => 'Jadwal Konservasi',
            PerawatanReminderNotification::class => 'Jadwal Konservasi',
            PenyewaanStatusNotification::class => 'Penyewaan',
            SerahTerimaStatusNotification::class => 'Serah Terima',
            RentalReminderNotification::class => 'Pengingat Penyewaan',
            PemesananTiketRefundNotification::class => 'Tiket',
            default => 'Umum',
        };
    }

    public function categoryBadgeClass(): string
    {
        return match ($this->category()) {
            'Jadwal Konservasi' => 'bg-amber-100 text-amber-800',
            'Penyewaan' => 'bg-blue-100 text-blue-800',
            'Serah Terima' => 'bg-emerald-100 text-emerald-800',
            'Pengingat Penyewaan' => 'bg-violet-100 text-violet-800',
            'Tiket' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function message(): string
    {
        $data = $this->notification->data;

        if ($this->notification->type === PemesananTiketRefundNotification::class) {
            return $this->pemesananTiketRefundMessage($data);
        }

        if (! empty($data['message'])) {
            return $data['message'];
        }

        return match ($this->notification->type) {
            PerawatanCreatedNotification::class => 'Jadwal tindak lanjut berhasil dibuat.',
            PerawatanReminderNotification::class => 'Pengingat jadwal konservasi baru.',
            PenyewaanStatusNotification::class => sprintf(
                'Pengajuan penyewaan diperbarui. Status: %s.',
                $data['status'] ?? '-'
            ),
            SerahTerimaStatusNotification::class => sprintf(
                'Status serah terima diperbarui (%s).',
                str_replace('_', ' ', $data['event'] ?? 'update')
            ),
            RentalReminderNotification::class => $this->rentalReminderMessage($data),
            PemesananTiketRefundNotification::class => $this->pemesananTiketRefundMessage($data),
            default => 'Notifikasi sistem.',
        };
    }

    /** @return array<string, string> */
    public function details(): array
    {
        $data = $this->notification->data;

        return match ($this->notification->type) {
            PerawatanCreatedNotification::class => array_filter([
                'Jenis Konservasi' => $data['jenis_perawatan'] ?? null,
                'Koleksi' => $data['koleksi_nama'] ?? null,
                'Tanggal Jadwal' => $this->formatDate($data['jadwal_tanggal'] ?? null),
                'Frekuensi' => $data['frekuensi'] ?? null,
                'Penanggung Jawab' => $data['penanggung_jawab'] ?? null,
            ]),
            PerawatanReminderNotification::class => array_filter([
                'Jenis Konservasi' => $data['jenis_perawatan'] ?? null,
                'Koleksi' => $data['koleksi_nama'] ?? null,
                'Tanggal Jadwal' => $this->formatDate($data['jadwal_tanggal'] ?? null),
                'Pengingat' => $data['reminder_for'] ?? null,
                'Penanggung Jawab' => $data['penanggung_jawab'] ?? null,
            ]),
            PenyewaanStatusNotification::class => array_filter([
                'Koleksi' => $data['painting_title'] ?? null,
                'Status Pengajuan' => $data['status'] ?? null,
                'Status Pembayaran' => $data['payment_status'] ?? null,
                'Tanggal Mulai' => $this->formatDate($data['start_date'] ?? null),
                'Tanggal Selesai' => $this->formatDate($data['end_date'] ?? null),
            ]),
            SerahTerimaStatusNotification::class => array_filter([
                'Nomor Pengajuan' => isset($data['penyewaan_id'])
                    ? 'SP-' . str_pad((string) $data['penyewaan_id'], 5, '0', STR_PAD_LEFT)
                    : null,
                'Koleksi' => $data['painting_title'] ?? null,
                'Peristiwa' => isset($data['event']) ? ucfirst(str_replace('_', ' ', $data['event'])) : null,
                'Status Serah Terima' => $data['status'] ?? null,
            ]),
            RentalReminderNotification::class => array_filter([
                'Koleksi' => $data['painting_title'] ?? null,
                'Jenis Pengingat' => $this->rentalReminderLabel($data['type'] ?? null),
                'Tanggal Akhir Sewa' => $this->formatDate($data['end_date'] ?? null),
            ]),
            PemesananTiketRefundNotification::class => array_filter([
                'Nomor Pemesanan' => isset($data['pemesanan_tiket_id'])
                    ? '#' . $data['pemesanan_tiket_id']
                    : null,
                'Tiket' => $data['ticket_name'] ?? null,
                'Tanggal Kunjungan' => $this->formatDateId($data['tanggal_kunjungan'] ?? null),
                'Peristiwa' => $this->pemesananTiketRefundEventLabel($data['event'] ?? null),
                'Status' => $this->pemesananTiketStatusLabel($data['status'] ?? null),
            ]),
            default => [],
        };
    }

    public function actionUrl(): ?string
    {
        $data = $this->notification->data;
        $role = auth()->user()?->role;

        if (! empty($data['pemesanan_tiket_id']) && $this->notification->type === PemesananTiketRefundNotification::class) {
            $event = $data['event'] ?? null;

            if ($role === 'pengelola' && $event === 'refund_requested') {
                return route('pengelola.detail-refund', ['pemesananTiket' => $data['pemesanan_tiket_id']]);
            }

            return route('pemesanan-tiket.show', ['pemesananTiket' => $data['pemesanan_tiket_id']]);
        }

        if (! empty($data['perawatan_id'])) {
            return route('jadwal-konservasi.show', ['perawatan' => $data['perawatan_id']]);
        }

        if (! empty($data['penyewaan_id']) && $this->notification->type === SerahTerimaStatusNotification::class) {
            return $role === 'pengelola'
                ? route('pengelola.penyewaan.handover.show', ['penyewaan' => $data['penyewaan_id']])
                : route('penyewaan.requests.handover.show', ['penyewaan' => $data['penyewaan_id']]);
        }

        if (! empty($data['penyewaan_id'])) {
            return $role === 'pengelola'
                ? route('pengelola.penyewaan.show', ['penyewaan' => $data['penyewaan_id']])
                : route('penyewaan.requests.show', ['penyewaan' => $data['penyewaan_id']]);
        }

        return null;
    }

    public function formattedCreatedAt(): string
    {
        $createdAt = $this->notification->created_at;

        if ($this->notification->type === PemesananTiketRefundNotification::class) {
            return $createdAt->locale('id')->translatedFormat('d F Y H:i');
        }

        return $createdAt->format('d M Y H:i');
    }

    public function actionLabel(): ?string
    {
        return match ($this->notification->type) {
            PerawatanCreatedNotification::class => 'Lihat Jadwal Konservasi',
            PerawatanReminderNotification::class => 'Lihat Jadwal Konservasi',
            PenyewaanStatusNotification::class => 'Lihat Pengajuan Penyewaan',
            SerahTerimaStatusNotification::class => 'Lihat Serah Terima',
            RentalReminderNotification::class => 'Lihat Pengajuan Penyewaan',
            PemesananTiketRefundNotification::class => $this->pemesananTiketRefundActionLabel($this->notification->data),
            default => null,
        };
    }

    /** @param array<string, mixed> $data */
    protected function pemesananTiketRefundMessage(array $data): string
    {
        $ticketName = $data['ticket_name'] ?? 'tiket';

        return match ($data['event'] ?? null) {
            'refund_requested' => "Pengajuan pembatalan & refund untuk {$ticketName} menunggu diproses pengelola.",
            'refund_completed' => "Refund untuk {$ticketName} telah dikirim. Bukti transfer tersedia di detail pemesanan.",
            default => 'Pembaruan refund pemesanan tiket.',
        };
    }

    protected function pemesananTiketRefundEventLabel(?string $event): ?string
    {
        return match ($event) {
            'refund_requested' => 'Proses Refund',
            'refund_completed' => 'Refund Berhasil',
            default => null,
        };
    }

    protected function pemesananTiketStatusLabel(?string $status): ?string
    {
        return match ($status) {
            'pending' => 'Menunggu Data',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'lunas' => 'Lunas',
            'dibatalkan' => 'Dibatalkan',
            'proses_pembatalan' => 'Proses Refund',
            'pengembalian_berhasil' => 'Refund Berhasil',
            default => $status ? ucfirst(str_replace('_', ' ', $status)) : null,
        };
    }

    /** @param array<string, mixed> $data */
    protected function pemesananTiketRefundActionLabel(array $data): string
    {
        $role = auth()->user()?->role;

        if ($role === 'pengelola' && ($data['event'] ?? null) === 'refund_requested') {
            return 'Proses Refund';
        }

        return 'Lihat Detail Pemesanan';
    }

    protected function rentalReminderMessage(array $data): string
    {
        $title = $data['painting_title'] ?? 'koleksi';

        return match ($data['type'] ?? null) {
            'h-3' => "Pengingat: sisa 3 hari sampai pengembalian {$title}.",
            'last-day' => "Pengingat: hari terakhir masa sewa {$title}.",
            default => "Informasi masa sewa {$title}.",
        };
    }

    protected function rentalReminderLabel(?string $type): ?string
    {
        return match ($type) {
            'h-3' => 'H-3 (3 hari sebelum pengembalian)',
            'last-day' => 'Hari terakhir masa sewa',
            default => null,
        };
    }

    protected function formatDate(?string $date): ?string
    {
        if (empty($date)) {
            return null;
        }

        return Carbon::parse($date)->format('d M Y');
    }

    protected function formatDateId(?string $date): ?string
    {
        if (empty($date)) {
            return null;
        }

        return Carbon::parse($date)->locale('id')->translatedFormat('d F Y');
    }
}
