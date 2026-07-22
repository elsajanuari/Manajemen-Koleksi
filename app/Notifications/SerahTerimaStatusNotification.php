<?php

namespace App\Notifications;

use App\Models\SerahTerima;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SerahTerimaStatusNotification extends Notification
{
    use Queueable;

    public function __construct(public SerahTerima $handover, public string $event)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = match ($this->event) {
            'generated' => 'Dokumen serah terima awal telah dibuat setelah pembayaran berhasil.',
            'created' => 'Dokumen serah terima awal telah dibuat dan status serah terima ditetapkan.',
            'preparing_delivery' => 'Koleksi sedang dipersiapkan untuk pengiriman.',
            'in_delivery' => 'Koleksi sedang dalam pengiriman.',
            'delivered' => 'Koleksi telah dikirim oleh pengelola.',
            'uploaded' => 'Dokumen serah terima telah diupload oleh penyewa. Penyewaan sekarang aktif.',
            'completed' => 'Proses serah terima koleksi telah selesai.',
            default => 'Status serah terima koleksi telah diperbarui.',
        };

        return (new MailMessage)
            ->subject('Pembaharuan Serah Terima Koleksi Lukisan')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($message)
            ->line('Nomor pengajuan: SP-' . str_pad($this->handover->penyewaan->id, 5, '0', STR_PAD_LEFT))
            ->line('Koleksi: ' . $this->handover->penyewaan->painting->title)
            ->action('Lihat Status Serah Terima', url(route('penyewaan.requests.handover.show', $this->handover->penyewaan)))
            ->line('Terima kasih telah menggunakan layanan Museum MK Lesmana.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'category' => 'Serah Terima',
            'serah_terima_id' => $this->handover->id,
            'painting_title' => $this->handover->penyewaan->painting->title,
            'penyewaan_id' => $this->handover->penyewaan->id,
            'status' => $this->handover->handover_status,
            'event' => $this->event,
            'message' => ucfirst(str_replace('_', ' ', $this->event)) . ' - ' . $this->getLogMessage(),
        ];
    }

    protected function getLogMessage(): string
    {
        return match ($this->event) {
            'created' => 'Dokumen serah terima awal telah dibuat.',
            'preparing_delivery' => 'Koleksi sedang dipersiapkan untuk pengiriman.',
            'in_delivery' => 'Koleksi dalam pengiriman.',
            'delivered' => 'Koleksi telah diterima oleh penyewa.',
            'uploaded' => 'Penyewa mengunggah dokumen serah terima.',
            'completed' => 'Serah terima koleksi telah selesai.',
            default => 'Status serah terima diperbarui.',
        };
    }
}
