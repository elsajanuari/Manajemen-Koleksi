<?php

namespace App\Notifications;

use App\Models\PerawatanKoleksi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class PerawatanReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected PerawatanKoleksi $perawatan
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'category'        => 'Jadwal Konservasi',
            'perawatan_id'    => $this->perawatan->id,
            'jenis_perawatan' => $this->perawatan->label_jenis,
            'koleksi_nama'   => $this->perawatan->koleksi->nama,
            'jadwal_tanggal' => $this->perawatan->jadwal_tanggal->toDateString(),
            'reminder_for'    => $this->perawatan->jadwal_tanggal->isToday() ? 'hari ini' : 'besok',
            'penanggung_jawab' => $this->perawatan->penanggung_jawab,
            'message'          => sprintf(
                'Pengingat: jadwal konservasi %s untuk koleksi %s pada %s %s. Penanggung jawab: %s.',
                $this->perawatan->label_jenis,
                $this->perawatan->koleksi->nama,
                $this->perawatan->jadwal_tanggal->format('d M Y'),
                $this->perawatan->jadwal_tanggal->isToday() ? 'hari ini' : 'besok',
                $this->perawatan->penanggung_jawab
            ),
        ];
    }
}
