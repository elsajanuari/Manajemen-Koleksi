<?php

namespace App\Notifications;

use App\Models\Penyewaan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RentalReminderNotification extends Notification
{
    use Queueable;

    public function __construct(public Penyewaan $penyewaan, public string $type)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $p = $this->penyewaan;
        if ($this->type === 'h-3') {
            $subject = 'Pengingat: Sisa 3 hari sampai pengembalian koleksi';
            $line = 'Tanggal sewa Anda akan berakhir pada ' . $p->end_date->format('d M Y') . ' — sisa 3 hari.';
        } elseif ($this->type === 'last-day') {
            $subject = 'Pengingat: Hari terakhir masa sewa';
            $line = 'Hari ini adalah hari terakhir masa sewa Anda (' . $p->end_date->format('d M Y') . '). Mohon siapkan pengembalian koleksi.';
        } else {
            $subject = 'Informasi masa sewa';
            $line = 'Detail sewa untuk koleksi Anda.';
        }

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($line)
            ->line('Koleksi: ' . $p->painting->title)
            ->action('Lihat Detail', url(route('penyewaan.requests.show', $p)))
            ->line('Terima kasih.');
    }

    public function toDatabase(object $notifiable): array
    {
        $title = $this->penyewaan->painting->title;

        $message = match ($this->type) {
            'h-3' => "Pengingat: sisa 3 hari sampai pengembalian {$title}.",
            'last-day' => "Pengingat: hari terakhir masa sewa {$title}.",
            default => "Informasi masa sewa {$title}.",
        };

        return [
            'category' => 'Pengingat Penyewaan',
            'penyewaan_id' => $this->penyewaan->id,
            'type' => $this->type,
            'end_date' => $this->penyewaan->end_date->format('Y-m-d'),
            'painting_title' => $title,
            'message' => $message,
        ];
    }
}
