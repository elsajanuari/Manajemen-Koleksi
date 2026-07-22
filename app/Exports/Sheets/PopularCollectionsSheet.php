<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PopularCollectionsSheet
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Koleksi Populer';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 40,
            'C' => 18,
        ];
    }

    public function headings(): array
    {
        return [
            ['TOP KOLEKSI TERPOPULER (PENYEWAAN)'],
            ['Diekspor pada: '.now()->format('d M Y, H:i').' WIB'],
            [],
            ['Peringkat', 'Nama Koleksi', 'Jumlah Disewa'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '7C3AED']],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(24);

        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => '64748B']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FDF4FF']],
        ]);

        $sheet->getStyle('A4:C4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '7C3AED']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle('A4:C100')->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']],
            ],
        ]);

        return [];
    }

    public function populateData(Worksheet $sheet, array $rows, int $startRow = 5): void
    {
        $medals = ['🥇', '🥈', '🥉'];

        foreach ($rows as $i => $r) {
            $row   = $startRow + $i;
            $bg    = $i % 2 === 0 ? 'FFFFFF' : 'FDF4FF';
            $rank  = ($i < 3) ? ($medals[$i].' '.($i + 1)) : ($i + 1);

            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $r['title'] ?? ('#'.$r['id']));
            $sheet->setCellValue("C{$row}", $r['rent_count'] ?? 0);

            $sheet->getStyle("A{$row}:C{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ]);

            // Top 3 bold
            if ($i < 3) {
                $sheet->getStyle("A{$row}:C{$row}")->getFont()->setBold(true);
            }
        }
    }
}