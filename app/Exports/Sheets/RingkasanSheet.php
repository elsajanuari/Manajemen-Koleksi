<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RingkasanSheet
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 25,
        ];
    }

    public function array(): array
    {
        $dateFrom = $this->filters['date_from'] ?? null;
        $dateTo   = $this->filters['date_to']   ?? null;
        $type     = $this->filters['type']       ?? 'all';
        $kpi      = $this->filters['kpi']        ?? [];

        $filterLabel = $dateFrom || $dateTo || $type !== 'all'
            ? implode(' | ', array_filter([
                $type !== 'all' ? 'Tipe: '.ucfirst($type) : null,
                $dateFrom ? 'Dari: '.$dateFrom : null,
                $dateTo   ? 'Sampai: '.$dateTo  : null,
              ]))
            : 'Semua data (tanpa filter)';

        return [
            ['DASHBOARD TRANSAKSI — RINGKASAN KEUANGAN', ''],
            ['Diekspor pada', now()->format('d M Y, H:i').' WIB'],
            ['Filter aktif', $filterLabel],
            ['', ''],
            ['METRIK', 'NILAI'],
            ['Total Pendapatan (Rp)',     $kpi['totalPendapatan']     ?? 0],
            ['Pendapatan Penyewaan (Rp)', $kpi['pendapatanSewa']      ?? 0],
            ['Pendapatan Penjualan (Rp)', $kpi['pendapatanBeli']      ?? 0],
            ['Deposit Ditahan (Rp)',      $kpi['totalDepositDitahan'] ?? 0],
            ['Refund Deposit (Rp)',       $kpi['refundDeposit']       ?? 0],
            ['', ''],
            ['TRANSAKSI', 'JUMLAH'],
            ['Total Penyewaan',     $kpi['totalSewa']          ?? 0],
            ['Total Pembelian',     $kpi['totalBeli']          ?? 0],
            ['Sewa Aktif',          $kpi['penyewaanAktif']     ?? 0],
            ['Sewa Selesai',        $kpi['sewaSelesai']        ?? 0],
            ['Penjualan Selesai',   $kpi['penjualanSelesai']   ?? 0],
            ['Perlu Verifikasi',    $kpi['menungguVerifikasi'] ?? 0],
            ['Dalam Pengiriman',    $kpi['sedangPengiriman']   ?? 0],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Judul utama
        $sheet->mergeCells('A1:B1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0B1D35']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Row info
        foreach ([2, 3] as $row) {
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '64748B']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFC']],
            ]);
        }

        // Header tabel keuangan (row 5)
        $sheet->getStyle('A5:B5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1D4ED8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Data keuangan rows 6–10
        foreach (range(6, 10) as $i => $row) {
            $bg = $i % 2 === 0 ? 'FFFFFF' : 'F8FAFC';
            $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
            ]);
            // Format angka rupiah
            $sheet->getStyle("B{$row}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
        }

        // Header tabel transaksi (row 12)
        $sheet->getStyle('A12:B12')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0B1D35']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Data transaksi rows 13–19
        foreach (range(13, 19) as $i => $row) {
            $bg = $i % 2 === 0 ? 'FFFFFF' : 'F8FAFC';
            $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
            ]);
        }

        // Border semua data
        $sheet->getStyle('A5:B19')->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']],
            ],
        ]);

        return [];
    }
}