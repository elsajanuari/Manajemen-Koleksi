<?php

namespace App\Notifications;

use App\Models\PemesananTiket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PemesananTiketRefundNotification extends Notification
{
    use Queueable;

    public function __construct(
        public PemesananTiket $pemesanan,
        public string $event,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $pemesanan = $this->pemesanan->loadMissing('ticket');
        $ticketName = $pemesanan->ticket?->nama_tiket ?? 'Tiket museum';
        $visitDate = $pemesanan->tanggal_pemesanan?->locale('id')->translatedFormat('d F Y') ?? '-';

        $subject = match ($this->event) {
            'refund_requested' => 'Pengajuan Pembatalan dan Refund Tiket',
            'refund_completed' => 'Bukti Transfer Refund Tiket',
            default => 'Pembaruan Refund Pemesanan Tiket',
        };

        $line = match ($this->event) {
            'refund_requested' => 'Pengunjung mengajukan pembatalan pemesanan lunas. Silakan proses refund dan unggah bukti transfer.',
            'refund_completed' => 'Pengelola telah mengunggah bukti transfer refund. Silakan cek detail pemesanan Anda.',
            default => 'Status refund pemesanan tiket diperbarui.',
        };

        $actionUrl = match ($this->event) {
            'refund_requested' => route('pengelola.detail-refund', $pemesanan),
            'refund_completed' => route('pemesanan-tiket.show', $pemesanan),
            default => route('pemesanan-tiket.show', $pemesanan),
        };

        $actionLabel = match ($this->event) {
            'refund_requested' => 'Proses Refund',
            'refund_completed' => 'Lihat Detail Pemesanan',
            default => 'Lihat Pemesanan',
        };

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($line)
            ->line('Tiket: ' . $ticketName)
            ->line('Tanggal kunjungan: ' . $visitDate)
            ->line('Nomor pemesanan: #' . $pemesanan->id)
            ->action($actionLabel, url($actionUrl))
            ->line('Terima kasih telah menggunakan layanan Museum MK Lesmana.');
    }

    public function toDatabase(object $notifiable): array
    {
        $pemesanan = $this->pemesanan->loadMissing('ticket');

        return [
            'category' => 'Tiket',
            'pemesanan_tiket_id' => $pemesanan->id,
            'event' => $this->event,
            'ticket_name' => $pemesanan->ticket?->nama_tiket,
            'tanggal_kunjungan' => $pemesanan->tanggal_pemesanan?->format('Y-m-d'),
            'status' => $pemesanan->status,
            'message' => $this->databaseMessage(),
        ];
    }

    protected function databaseMessage(): string
    {
        $ticketName = $this->pemesanan->ticket?->nama_tiket ?? 'tiket';

        return match ($this->event) {
            'refund_requested' => "Pengajuan pembatalan dan refund untuk {$ticketName} menunggu diproses pengelola.",
            'refund_completed' => "Refund untuk {$ticketName} telah dikirim. Bukti transfer tersedia di detail pemesanan.",
            default => "Pembaruan refund pemesanan tiket (#{$this->pemesanan->id}).",
        };
    }
}
