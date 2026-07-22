<?php

namespace App\Notifications;

use App\Models\Penyewaan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PenyewaanStatusNotification extends Notification
{
    use Queueable;

    public function __construct(public Penyewaan $penyewaan)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->penyewaan->status;
        $message = $this->penyewaan->status_label;
        $statusLabel = $this->penyewaan->status_label;

        $mail = (new MailMessage)
            ->subject('Status Pengajuan dan Pembayaran')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Status pengajuan Anda saat ini: ' . $statusLabel . '.')
            ->line('Koleksi: ' . $this->penyewaan->painting->title)
            ->line('Tanggal sewa: ' . $this->penyewaan->start_date->format('d M Y') . ' sampai ' . $this->penyewaan->end_date->format('d M Y'));

        if ($this->penyewaan->signed_agreement_status === 'accepted') {
            $mail->line('Dokumen perjanjian Anda telah divalidasi oleh pengelola.');
        }

        if ($this->penyewaan->signed_agreement_status === 'rejected') {
            $mail->line('Dokumen perjanjian Anda ditolak dan perlu diunggah ulang.');
        }

        if ($this->penyewaan->payment_status === 'pending') {
            $mail->line('Pembayaran Anda sedang menunggu. Silakan selesaikan transaksi melalui Midtrans.');
        }

        if ($this->penyewaan->payment_status === 'paid') {
            $mail->line('Pembayaran Anda telah berhasil diterima.');
        }

        if ($this->penyewaan->payment_status === 'failed') {
            $mail->line('Pembayaran Anda gagal. Silakan coba lagi melalui halaman invoice.');
        }

        if ($this->penyewaan->payment_status === 'expired') {
            $mail->line('Pembayaran Anda telah kadaluarsa. Silakan buat ulang transaksi jika ingin melanjutkan.');
        }

        return $mail
            ->action('Lihat Detail Pengajuan', url(route('penyewaan.requests.show', $this->penyewaan)))
            ->line('Terima kasih telah menggunakan layanan Museum MK Lesmana.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'category' => 'Penyewaan',
            'penyewaan_id' => $this->penyewaan->id,
            'status' => $this->penyewaan->status,
            'payment_status' => $this->penyewaan->payment_status,
            'painting_title' => $this->penyewaan->painting->title,
            'start_date' => $this->penyewaan->start_date->format('Y-m-d'),
            'end_date' => $this->penyewaan->end_date->format('Y-m-d'),
            'message' => 'Pengajuan Anda saat ini: ' . $this->penyewaan->status_label . '. Pembayaran: ' . ($this->penyewaan->payment_status ?? 'unpaid') . '.',
        ];
    }
}
