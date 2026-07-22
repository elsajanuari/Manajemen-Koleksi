<?php
// app/Exports/TiketLaporanPendapatanExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TiketLaporanPendapatanExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rows = [
            ['LAPORAN PENDAPATAN MUSEUM'],
            ['Periode:', $this->data['start_date'], 's/d', $this->data['end_date']],
            ['Tanggal Cetak:', now()->locale('id')->translatedFormat('d F Y H:i:s')],
            [''],
            ['RINGKASAN'],
            ['Total Pendapatan', 'Rp ' . number_format($this->data['total_pendapatan'], 0, ',', '.')],
            ['Jumlah Transaksi', $this->data['jumlah_transaksi']],
            ['Rata-rata Transaksi', 'Rp ' . number_format($this->data['rata_transaksi'], 0, ',', '.')],
            [''],
            ['DETAIL PENDAPATAN PER PERIODE'],
            ['Periode', 'Pendapatan'],
        ];

        foreach ($this->data['chart_labels'] as $index => $label) {
            $rows[] = [$label, 'Rp ' . number_format($this->data['chart_data'][$index], 0, ',', '.')];
        }

        $rows[] = [''];
        $rows[] = ['Total Keseluruhan', 'Rp ' . number_format($this->data['total_pendapatan'], 0, ',', '.')];

        return $rows;
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Laporan Pendapatan';
    }
}