<?php

namespace App\Exports\Sheets;

use App\Models\Pembelian;
use App\Models\Payment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Collection;

class SalesInsightSheet
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Penjualan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 35,
            'C' => 25,
            'D' => 15,
            'E' => 20,
            'F' => 18,
            'G' => 18,
            'H' => 18,
            'I' => 18,
        ];
    }

    public function headings(): array
    {
        return [
            ['INSIGHT PENJUALAN KOLEKSI'],
            ['Diekspor pada: '.now()->format('d M Y, H:i').' WIB'],
            [],
            ['No', 'Koleksi', 'Pembeli', 'Tipe Pembeli', 'Harga Beli (Rp)', 'Ongkos Kirim (Rp)', 'Total Bayar (Rp)', 'Tgl Selesai', 'Status'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Judul
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '15803D']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(24);

        // Info baris 2
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '64748B']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0FDF4']],
        ]);

        // Header kolom (row 4)
        $sheet->getStyle('A4:I4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '15803D']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'BBF7D0']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(20);

        // Data rows (5 dst) — styling dilakukan via event di controller jika banyak row
        // Set format rupiah untuk kolom E, F, G
        $sheet->getStyle('E5:G1000')->getNumberFormat()->setFormatCode('"Rp "#,##0');

        // Zebra stripe otomatis via styles array tidak bisa dinamis;
        // kita set border umum untuk range yang mungkin terisi
        $sheet->getStyle('A4:I500')->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']],
            ],
        ]);

        return [];
    }

    /**
     * Dipanggil dari controller karena butuh inject data ke sheet.
     * Sheet ini tidak pakai FromQuery agar bisa di-populate manual.
     */
    public function populateData(Worksheet $sheet, array $rows, int $startRow = 5): void
    {
        foreach ($rows as $i => $r) {
            $row = $startRow + $i;
            $bg  = $i % 2 === 0 ? 'FFFFFF' : 'F0FDF4';

            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $r['judul'] ?? '');
            $sheet->setCellValue("C{$row}", $r['pembeli'] ?? '');
            $sheet->setCellValue("D{$row}", strtoupper($r['buyer_type'] ?? ''));
            $sheet->setCellValue("E{$row}", $r['harga_beli'] ?? 0);
            $sheet->setCellValue("F{$row}", $r['shipping_cost'] ?? 0);
            $sheet->setCellValue("G{$row}", $r['total_bayar'] ?? 0);
            $sheet->setCellValue("H{$row}", $r['completed_at'] ?? '—');
            $sheet->setCellValue("I{$row}", 'Selesai');

            $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
            ]);
        }

        // Total baris
        if (!empty($rows)) {
            $totalRow = $startRow + count($rows);
            $sheet->setCellValue("A{$totalRow}", '');
            $sheet->setCellValue("B{$totalRow}", 'TOTAL');
            $sheet->setCellValue("E{$totalRow}", array_sum(array_column($rows, 'harga_beli')));
            $sheet->setCellValue("F{$totalRow}", array_sum(array_column($rows, 'shipping_cost')));
            $sheet->setCellValue("G{$totalRow}", array_sum(array_column($rows, 'total_bayar')));
            $sheet->getStyle("A{$totalRow}:I{$totalRow}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DCFCE7']],
            ]);
        }
    }
}