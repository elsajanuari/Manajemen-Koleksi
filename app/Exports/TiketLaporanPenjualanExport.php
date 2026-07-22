<?php
// app/Exports/TiketLaporanPenjualanExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TiketLaporanPenjualanExport implements FromArray, WithTitle, ShouldAutoSize
{
    protected $data;
    protected $startDate;
    protected $endDate;

    public function __construct($data, $startDate = null, $endDate = null)
    {
        $this->data = $data;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function array(): array
    {
        $rows = [
            ['LAPORAN PENJUALAN TIKET MUSEUM'],
            ['Periode:', $this->startDate ?? '-', 's/d', $this->endDate ?? '-'],
            ['Tanggal Cetak:', now()->locale('id')->translatedFormat('d F Y H:i:s')],
            [''],
            ['No', 'Jenis Tiket', 'Harga', 'Jumlah Terjual', 'Total Pendapatan'],
        ];

        $no = 1;
        foreach ($this->data as $item) {
            $rows[] = [
                $no++,
                $item['jenis_tiket'],
                'Rp ' . number_format($item['harga'], 0, ',', '.'),
                $item['jumlah_terjual'],
                'Rp ' . number_format($item['total_pendapatan'], 0, ',', '.'),
            ];
        }

        $totalPendapatan = array_sum(array_column($this->data, 'total_pendapatan'));
        $rows[] = [''];
        $rows[] = ['', '', '', 'Total Keseluruhan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.')];

        return $rows;
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }
}