<?php

namespace App\Notifications;

use App\Models\PerawatanKoleksi;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PerawatanCreatedNotification extends Notification
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
        $this->perawatan->loadMissing('koleksi');

        return [
            'category'         => 'Jadwal Konservasi',
            'perawatan_id'     => $this->perawatan->id,
            'jenis_perawatan'  => $this->perawatan->label_jenis,
            'koleksi_nama'     => $this->perawatan->koleksi->nama,
            'jadwal_tanggal'   => $this->perawatan->jadwal_tanggal->toDateString(),
            'penanggung_jawab' => $this->perawatan->penanggung_jawab,
            'frekuensi'        => $this->perawatan->label_frekuensi,
            'message'          => sprintf(
                'Jadwal tindak lanjut %s untuk koleksi %s berhasil dibuat. Pelaksanaan dijadwalkan pada %s. Penanggung jawab: %s.',
                $this->perawatan->label_jenis,
                $this->perawatan->koleksi->nama,
                $this->perawatan->jadwal_tanggal->format('d M Y'),
                $this->perawatan->penanggung_jawab
            ),
        ];
    }
}
