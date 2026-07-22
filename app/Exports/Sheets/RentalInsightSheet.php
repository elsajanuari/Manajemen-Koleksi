<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RentalInsightSheet
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Penyewaan';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 32,
            'C' => 25,
            'D' => 15,
            'E' => 12,
            'F' => 18,
            'G' => 18,
            'H' => 18,
            'I' => 18,
            'J' => 15,
            'K' => 20,
        ];
    }

    public function headings(): array
    {
        return [
            ['INSIGHT PENYEWAAN KOLEKSI'],
            ['Diekspor pada: '.now()->format('d M Y, H:i').' WIB'],
            [],
            ['No', 'Koleksi', 'Penyewa', 'Tipe Penyewa', 'Durasi (Hari)', 'Subtotal (Rp)', 'Deposit (Rp)', 'Ongkir (Rp)', 'Total Bayar (Rp)', 'Tgl Mulai', 'Tgl Selesai'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->mergeCells('A1:K1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1D4ED8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(24);

        $sheet->mergeCells('A2:K2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '64748B']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EFF6FF']],
        ]);

        $sheet->getStyle('A4:K4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1D4ED8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(20);

        // Format rupiah untuk kolom F, G, H, I
        $sheet->getStyle('F5:I1000')->getNumberFormat()->setFormatCode('"Rp "#,##0');

        $sheet->getStyle('A4:K500')->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']],
            ],
        ]);

        return [];
    }

    public function populateData(Worksheet $sheet, array $rows, int $startRow = 5): void
    {
        foreach ($rows as $i => $r) {
            $row = $startRow + $i;
            $bg  = $i % 2 === 0 ? 'FFFFFF' : 'EFF6FF';

            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $r['judul'] ?? '');
            $sheet->setCellValue("C{$row}", $r['penyewa'] ?? '');
            $sheet->setCellValue("D{$row}", ucfirst($r['rental_type'] ?? ''));
            $sheet->setCellValue("E{$row}", $r['duration_days'] ?? 0);
            $sheet->setCellValue("F{$row}", $r['subtotal'] ?? 0);
            $sheet->setCellValue("G{$row}", $r['deposit'] ?? 0);
            $sheet->setCellValue("H{$row}", $r['shipping_cost'] ?? 0);
            $sheet->setCellValue("I{$row}", $r['total_bayar'] ?? 0);
            $sheet->setCellValue("J{$row}", $r['start_date'] ?? '—');
            $sheet->setCellValue("K{$row}", $r['end_date'] ?? '—');

            $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
            ]);
        }

        if (!empty($rows)) {
            $totalRow = $startRow + count($rows);
            $sheet->setCellValue("B{$totalRow}", 'TOTAL');
            $sheet->setCellValue("F{$totalRow}", array_sum(array_column($rows, 'subtotal')));
            $sheet->setCellValue("G{$totalRow}", array_sum(array_column($rows, 'deposit')));
            $sheet->setCellValue("H{$totalRow}", array_sum(array_column($rows, 'shipping_cost')));
            $sheet->setCellValue("I{$totalRow}", array_sum(array_column($rows, 'total_bayar')));
            $sheet->getStyle("A{$totalRow}:K{$totalRow}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DBEAFE']],
            ]);
        }
    }
}