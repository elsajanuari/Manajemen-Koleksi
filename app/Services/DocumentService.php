<?php

namespace App\Services;

use App\Models\Pembelian;
use App\Models\Penyewaan;
use App\Models\SerahTerima;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentService
{
    // ─── Dokumen Serah Terima Awal (PDF) ─────────────────────────────

    public function generateHandoverDocumentPdf(Penyewaan $penyewaan, SerahTerima $serahTerima): string
    {
        $path = 'handover_documents/' . $penyewaan->id . '/serah-terima-' . $serahTerima->document_number . '.pdf';
        
        // Hapus file lama jika ada
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // Generate PDF dari blade
        $pdf = Pdf::loadView('serah_terima.penyewaan', [
            'penyewaan' => $penyewaan,
            'serahTerimaStub' => $serahTerima,
        ]);
        $pdf->setPaper('A4', 'portrait');

        // Simpan ke storage
        $pdfContent = $pdf->output();
        Storage::disk('public')->put($path, $pdfContent);

        return $path;
    }
    // ─── Dokumen Serah Terima Awal (tahap 16) ─────────────────────────

    public function generateHandoverDocument(Penyewaan $penyewaan, SerahTerima $serahTerima): string
    {
        return $this->generateHandoverDocumentPdf($penyewaan, $serahTerima);
    }

    public function generatePurchaseHandoverDocument(Pembelian $pembelian): string
    {
        $phpWord = new PhpWord();

        // ── Font & warna global ──────────────────────────────────────
        $hijau        = '1a5c2e';
        $hitam        = '1a1a1a';
        $abu          = '555555';
        $hijauMuda    = 'f6fbf8';
        $warningBg    = 'fffbeb';
        $warningBorder = 'd97706';

        $phpWord->getDocInfo()->setCreator('Museum MK. Lesmana');
        $phpWord->getDocInfo()->setTitle('Berita Acara Serah Terima Pembelian');

        // ── Default font ─────────────────────────────────────────────
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(10);

        // ── Named styles ─────────────────────────────────────────────
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 14, 'color' => $hijau]);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 11, 'color' => $hitam]);

        // ── Section / margin ─────────────────────────────────────────
        $sectionStyle = [
            'marginTop'    => 1440,
            'marginBottom' => 1440,
            'marginLeft'   => 1440,
            'marginRight'  => 1440,
        ];
        $section = $phpWord->addSection($sectionStyle);

        // Lebar konten = 12240 - 2880 = 9360 (A4: 11906 - 2880 ≈ 9026 DXA)
        $contentWidth = 9026;
        $halfWidth    = intdiv($contentWidth, 2);

        // ════════════════════════════════════════════════════════════
        // HELPER STYLES
        // ════════════════════════════════════════════════════════════
        $styleLabel  = ['size' => 9, 'color' => $abu];
        $styleVal    = ['size' => 10, 'color' => $hitam, 'bold' => true];
        $styleValNormal = ['size' => 10, 'color' => $hitam];
        $styleSectionHeader = ['bold' => true, 'size' => 9, 'color' => 'FFFFFF'];
        $styleBody   = ['size' => 10, 'color' => $hitam];
        $styleSmall  = ['size' => 9, 'color' => $abu];

        $parCenter = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
        $parLeft   = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT];

        $borderGreen  = ['borderTopColor' => $hijau, 'borderTopSize' => 12,
                         'borderBottomColor' => $hijau, 'borderBottomSize' => 12,
                         'borderLeftColor' => $hijau, 'borderLeftSize' => 12,
                         'borderRightColor' => $hijau, 'borderRightSize' => 12];
        $borderGray   = ['borderTopColor' => 'CCCCCC', 'borderTopSize' => 4,
                         'borderBottomColor' => 'CCCCCC', 'borderBottomSize' => 4,
                         'borderLeftColor' => 'CCCCCC', 'borderLeftSize' => 4,
                         'borderRightColor' => 'CCCCCC', 'borderRightSize' => 4];
        $noBorder     = ['borderTopColor' => 'FFFFFF', 'borderTopSize' => 0,
                         'borderBottomColor' => 'FFFFFF', 'borderBottomSize' => 0,
                         'borderLeftColor' => 'FFFFFF', 'borderLeftSize' => 0,
                         'borderRightColor' => 'FFFFFF', 'borderRightSize' => 0];

        // ════════════════════════════════════════════════════════════
        // SECTION HEADER HELPER
        // ════════════════════════════════════════════════════════════
        $addSectionHeader = function (string $label) use ($section, $contentWidth, $hijau, $styleSectionHeader) {
            $tbl = $section->addTable(['cellMarginTop' => 60, 'cellMarginBottom' => 60,
                                       'cellMarginLeft' => 120, 'cellMarginRight' => 120]);
            $tbl->addRow();
            $cell = $tbl->addCell($contentWidth, [
                'bgColor'           => $hijau,
                'borderTopColor'    => $hijau, 'borderTopSize'    => 4,
                'borderBottomColor' => $hijau, 'borderBottomSize' => 4,
                'borderLeftColor'   => $hijau, 'borderLeftSize'   => 4,
                'borderRightColor'  => $hijau, 'borderRightSize'  => 4,
            ]);
            $cell->addText(strtoupper($label), $styleSectionHeader);
            $section->addTextBreak(0);
        };

        // ════════════════════════════════════════════════════════════
        // 1. HEADER DOKUMEN
        // ════════════════════════════════════════════════════════════
        // Tabel header: logo & nama museum | label dokumen
        $tblHeader = $section->addTable([
            'borderBottomColor' => $hijau, 'borderBottomSize' => 18,
            'borderTopColor'    => 'FFFFFF', 'borderTopSize' => 0,
            'borderLeftColor'   => 'FFFFFF', 'borderLeftSize' => 0,
            'borderRightColor'  => 'FFFFFF', 'borderRightSize' => 0,
            'cellMarginBottom'  => 120,
        ]);
        $tblHeader->addRow(800);

        // Kolom kiri: nama museum & alamat
        $cellLeft = $tblHeader->addCell(intdiv($contentWidth * 58, 100), $noBorder);
        $cellLeft->addText('Museum MK. Lesmana',
            ['bold' => true, 'size' => 13, 'color' => $hijau]);
        $cellLeft->addText('Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes',
            ['size' => 8, 'color' => $abu]);
        $cellLeft->addText('Kabupaten Purwakarta, Jawa Barat 41175',
            ['size' => 8, 'color' => $abu]);

        // Kolom kanan: label & nomor dokumen
        $cellRight = $tblHeader->addCell(intdiv($contentWidth * 42, 100), $noBorder);
        $cellRight->addText('BERITA ACARA SERAH TERIMA',
            ['bold' => true, 'size' => 16, 'color' => $hitam], $parCenter);
        $cellRight->addText('No. BAST/' . str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) . '/' . now()->format('Y'),
            ['size' => 9, 'color' => $abu], $parCenter);

        $section->addTextBreak(1);

        // Baris info dokumen (3 kolom)
        $tblInfo = $section->addTable([
            'borderTopColor'    => 'CCCCCC', 'borderTopSize'    => 4,
            'borderBottomColor' => 'CCCCCC', 'borderBottomSize' => 4,
            'borderLeftColor'   => 'CCCCCC', 'borderLeftSize'   => 4,
            'borderRightColor'  => 'CCCCCC', 'borderRightSize'  => 4,
            'cellMarginTop'     => 80, 'cellMarginBottom' => 80,
            'cellMarginLeft'    => 120, 'cellMarginRight'  => 120,
        ]);
        $tblInfo->addRow();
        $colW = intdiv($contentWidth, 3);

        $c1 = $tblInfo->addCell($colW, $borderGray);
        $c1->addText('Tanggal BAST', $styleLabel);
        $c1->addText(now()->translatedFormat('d F Y'), $styleVal);

        $c2 = $tblInfo->addCell($colW, $borderGray);
        $c2->addText('Ref. Invoice', $styleLabel);
        $c2->addText($pembelian->invoice_number ?? '-', $styleVal);

        $c3 = $tblInfo->addCell($colW, $borderGray);
        $c3->addText('No. Pengajuan', $styleLabel);
        $c3->addText('BLI-' . str_pad($pembelian->id, 5, '0', STR_PAD_LEFT), $styleVal);

        $section->addTextBreak(1);

        // ════════════════════════════════════════════════════════════
        // 2. IDENTITAS PARA PIHAK
        // ════════════════════════════════════════════════════════════
        $tblPihak = $section->addTable([
            'borderTopColor' => 'FFFFFF', 'borderTopSize' => 0,
            'borderBottomColor' => 'FFFFFF', 'borderBottomSize' => 0,
            'borderLeftColor' => 'FFFFFF', 'borderLeftSize' => 0,
            'borderRightColor' => 'FFFFFF', 'borderRightSize' => 0,
            'cellMarginTop' => 0, 'cellMarginBottom' => 0,
            'cellMarginLeft' => 0, 'cellMarginRight' => 120,
        ]);
        $tblPihak->addRow();

        // ── Pihak Pertama ──────────────────────────────────────────
        $cellP1 = $tblPihak->addCell($halfWidth - 60, $noBorder);

        // Header section hijau
        $tblP1H = $cellP1->addTable(['cellMarginTop' => 60, 'cellMarginBottom' => 60, 'cellMarginLeft' => 120, 'cellMarginRight' => 120]);
        $tblP1H->addRow();
        $tblP1H->addCell($halfWidth - 60, ['bgColor' => $hijau,
            'borderTopColor' => $hijau, 'borderTopSize' => 4,
            'borderBottomColor' => $hijau, 'borderBottomSize' => 4,
            'borderLeftColor' => $hijau, 'borderLeftSize' => 4,
            'borderRightColor' => $hijau, 'borderRightSize' => 4,
        ])->addText('PIHAK PERTAMA — PENJUAL', $styleSectionHeader);

        // Data pihak pertama
        $tblP1 = $cellP1->addTable(['cellMarginTop' => 60, 'cellMarginBottom' => 60, 'cellMarginLeft' => 120, 'cellMarginRight' => 80]);
        $p1Rows = [
            'Nama Lembaga'   => 'Museum MK. Lesmana',
            'Diwakili oleh'  => 'Pengelola Museum',
            'Alamat'         => 'Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175',
        ];
        foreach ($p1Rows as $lbl => $val) {
            $tblP1->addRow();
            $tblP1->addCell(1600, $noBorder)->addText($lbl, $styleLabel);
            $tblP1->addCell($halfWidth - 1700, $noBorder)->addText($val, $styleValNormal);
        }

        // ── Pihak Kedua ───────────────────────────────────────────
        $cellP2 = $tblPihak->addCell($halfWidth + 60, $noBorder);

        $tblP2H = $cellP2->addTable(['cellMarginTop' => 60, 'cellMarginBottom' => 60, 'cellMarginLeft' => 120, 'cellMarginRight' => 120]);
        $tblP2H->addRow();
        $tblP2H->addCell($halfWidth + 60, ['bgColor' => $hijau,
            'borderTopColor' => $hijau, 'borderTopSize' => 4,
            'borderBottomColor' => $hijau, 'borderBottomSize' => 4,
            'borderLeftColor' => $hijau, 'borderLeftSize' => 4,
            'borderRightColor' => $hijau, 'borderRightSize' => 4,
        ])->addText('PIHAK KEDUA — PEMBELI', $styleSectionHeader);

        $tblP2 = $cellP2->addTable(['cellMarginTop' => 60, 'cellMarginBottom' => 60, 'cellMarginLeft' => 120, 'cellMarginRight' => 80]);

        if ($pembelian->buyer_type === 'b2c') {
            $p2Rows = [
                'Nama Lengkap'     => $pembelian->nama_lengkap ?? '-',
                'NIK'              => $pembelian->nik ?? '-',
                'Tempat, Tgl. Lahir' => ($pembelian->tempat_lahir ?? '-') . ', ' . ($pembelian->tanggal_lahir?->translatedFormat('d F Y') ?? '-'),
                'Pekerjaan'        => $pembelian->pekerjaan ?? '-',
                'Nomor HP'         => $pembelian->nomor_hp ?? '-',
                'Email'            => $pembelian->email ?? '-',
                'Alamat'           => ($pembelian->alamat_pengiriman ?? '') . ', ' . ($pembelian->kota_kabupaten ?? '') . ', ' . ($pembelian->provinsi ?? ''),
            ];
            if ($pembelian->npwp) {
                $p2Rows['NPWP'] = $pembelian->npwp;
            }
        } else {
            $p2Rows = [
                'Nama Perusahaan'  => $pembelian->company_name ?? '-',
                'Jenis Perusahaan' => $pembelian->company_type ?? '-',
                'NPWP Perusahaan'  => $pembelian->company_npwp ?? '-',
                'Alamat Perusahaan' => ($pembelian->company_address ?? '') . ', ' . ($pembelian->company_city ?? '') . ', ' . ($pembelian->company_province ?? ''),
                'PIC / Perwakilan' => $pembelian->pic_name ?? '-',
                'Jabatan PIC'      => $pembelian->pic_position ?? '-',
                'NIK PIC'          => $pembelian->pic_nik ?? '-',
                'Nomor HP PIC'     => $pembelian->pic_phone ?? '-',
                'Email PIC'        => $pembelian->pic_email ?? '-',
            ];
        }

        foreach ($p2Rows as $lbl => $val) {
            $tblP2->addRow();
            $tblP2->addCell(1600, $noBorder)->addText($lbl, $styleLabel);
            $tblP2->addCell($halfWidth - 1600, $noBorder)->addText($val, $styleValNormal);
        }

        $section->addTextBreak(1);

        // ════════════════════════════════════════════════════════════
        // 3. DETAIL KOLEKSI
        // ════════════════════════════════════════════════════════════
        $addSectionHeader('Rincian Koleksi yang Diserahterimakan');
        $section->addTextBreak(0);

        // Tabel koleksi
        $colWidths = [300, 3200, 700, 2163, 1663]; // No | Deskripsi | Qty | Harga | Kondisi
        $tblKoleksi = $section->addTable([
            'borderTopColor' => 'CCCCCC', 'borderTopSize' => 4,
            'borderBottomColor' => $hijau, 'borderBottomSize' => 12,
            'borderLeftColor' => 'CCCCCC', 'borderLeftSize' => 4,
            'borderRightColor' => 'CCCCCC', 'borderRightSize' => 4,
            'cellMarginTop' => 80, 'cellMarginBottom' => 80,
            'cellMarginLeft' => 120, 'cellMarginRight' => 120,
        ]);

        // Header baris
        $tblKoleksi->addRow(400);
        $thStyle = ['bgColor' => $hijau,
            'borderTopColor' => $hijau, 'borderTopSize' => 4,
            'borderBottomColor' => $hijau, 'borderBottomSize' => 4,
            'borderLeftColor' => $hijau, 'borderLeftSize' => 4,
            'borderRightColor' => $hijau, 'borderRightSize' => 4,
        ];
        $thFont = ['bold' => true, 'size' => 9, 'color' => 'FFFFFF'];
        $tblKoleksi->addCell($colWidths[0], $thStyle)->addText('No', $thFont);
        $tblKoleksi->addCell($colWidths[1], $thStyle)->addText('Deskripsi Koleksi', $thFont);
        $tblKoleksi->addCell($colWidths[2], $thStyle)->addText('Qty', $thFont, $parCenter);
        $tblKoleksi->addCell($colWidths[3], $thStyle)->addText('Harga Beli (Rp)', $thFont, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);
        $tblKoleksi->addCell($colWidths[4], $thStyle)->addText('Kondisi', $thFont, $parCenter);

        // Baris data koleksi
        $tblKoleksi->addRow();
        $tdBorder = $borderGray;

        $tblKoleksi->addCell($colWidths[0], $tdBorder)->addText('1', $styleSmall, $parCenter);

        $cellDesc = $tblKoleksi->addCell($colWidths[1], $tdBorder);
        $cellDesc->addText($pembelian->painting->title ?? '-', ['bold' => true, 'size' => 10, 'color' => $hitam]);
        $metaRows = [
            'Seniman'      => $pembelian->painting->artist ?? '-',
            'Tahun'        => $pembelian->painting->year ?? ($pembelian->painting->year_created ?? '-'),
            'Teknik/Media' => $pembelian->painting->media ?? '-',
            'Dimensi'      => $pembelian->painting->dimensions ?? '-',
            'Kategori'     => $pembelian->painting->category ?? '-',
            'No. Koleksi'  => $pembelian->painting->collection_number ?? '-',
        ];
        foreach ($metaRows as $mk => $mv) {
            $cellDesc->addText($mk . ' : ' . $mv, ['size' => 9, 'color' => $abu]);
        }

        $tblKoleksi->addCell($colWidths[2], $tdBorder)->addText('1', $styleBody, $parCenter);
        $tblKoleksi->addCell($colWidths[3], $tdBorder)->addText(
            number_format($pembelian->harga_beli, 0, ',', '.'),
            ['bold' => true, 'size' => 10, 'color' => $hitam],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]
        );
        $tblKoleksi->addCell($colWidths[4], $tdBorder)->addText('Baik', ['size' => 9, 'color' => $hijau, 'bold' => true], $parCenter);

        $section->addTextBreak(1);

        // ════════════════════════════════════════════════════════════
        // 4. CHECKLIST KONDISI & INFO PENGIRIMAN (2 kolom)
        // ════════════════════════════════════════════════════════════
        $tblMid = $section->addTable([
            'borderTopColor' => 'FFFFFF', 'borderTopSize' => 0,
            'borderBottomColor' => 'FFFFFF', 'borderBottomSize' => 0,
            'borderLeftColor' => 'FFFFFF', 'borderLeftSize' => 0,
            'borderRightColor' => 'FFFFFF', 'borderRightSize' => 0,
            'cellMarginTop' => 0, 'cellMarginBottom' => 0,
            'cellMarginLeft' => 0, 'cellMarginRight' => 60,
        ]);
        $tblMid->addRow();
        $cellChk = $tblMid->addCell($halfWidth, $noBorder);
        $cellShip = $tblMid->addCell($halfWidth, $noBorder);

        // ── Checklist kondisi ──────────────────────────────────────
        $tblChkH = $cellChk->addTable(['cellMarginTop' => 60, 'cellMarginBottom' => 60, 'cellMarginLeft' => 120]);
        $tblChkH->addRow();
        $tblChkH->addCell($halfWidth, ['bgColor' => $hijau,
            'borderTopColor' => $hijau, 'borderTopSize' => 4,
            'borderBottomColor' => $hijau, 'borderBottomSize' => 4,
            'borderLeftColor' => $hijau, 'borderLeftSize' => 4,
            'borderRightColor' => $hijau, 'borderRightSize' => 4,
        ])->addText('PEMERIKSAAN KONDISI KOLEKSI', $styleSectionHeader);

        $chkItems = [
            'Frame / bingkai dalam kondisi aman'      => $pembelian->checklist_frame_safe ?? $pembelian->handover_checklist_frame_safe ?? false,
            'Tidak ada sobekan atau kerusakan fisik'   => $pembelian->checklist_no_tears ?? $pembelian->handover_checklist_no_tears ?? false,
            'Warna dan pigmen dalam kondisi normal'    => $pembelian->checklist_color_normal ?? $pembelian->handover_checklist_color_normal ?? false,
            'Kaca pelindung aman (jika ada)'           => $pembelian->checklist_glass_safe ?? $pembelian->handover_checklist_glass_safe ?? false,
            'Tidak terdapat jamur/kelembaban berlebih' => $pembelian->checklist_no_mold ?? $pembelian->handover_checklist_no_mold ?? false,
            'Sesuai dokumentasi & foto katalog'        => $pembelian->checklist_matches_documentation ?? $pembelian->handover_checklist_matches_documentation ?? false,
        ];

        $tblChk = $cellChk->addTable(['cellMarginTop' => 50, 'cellMarginBottom' => 50, 'cellMarginLeft' => 80]);
        $odd = true;
        foreach ($chkItems as $item => $val) {
            $tblChk->addRow();
            $rowBg = $odd ? $hijauMuda : 'FFFFFF';
            $tblChk->addCell(300, array_merge($noBorder, ['bgColor' => $rowBg]))->addText(
                $val ? "\u{2713}" : "\u{2717}",
                ['bold' => true, 'size' => 10, 'color' => $val ? $hijau : 'b91c1c']
            );
            $tblChk->addCell($halfWidth - 360, array_merge($noBorder, ['bgColor' => $rowBg]))->addText(
                $item, ['size' => 9, 'color' => $hitam]
            );
            $odd = !$odd;
        }

        if ($pembelian->handover_condition_notes) {
            $tblNote = $cellChk->addTable(['cellMarginTop' => 60, 'cellMarginBottom' => 60, 'cellMarginLeft' => 120]);
            $tblNote->addRow();
            $noteCell = $tblNote->addCell($halfWidth, [
                'borderLeftColor' => $warningBorder, 'borderLeftSize' => 12,
                'borderTopColor' => 'FFFFFF', 'borderTopSize' => 0,
                'borderBottomColor' => 'FFFFFF', 'borderBottomSize' => 0,
                'borderRightColor' => 'FFFFFF', 'borderRightSize' => 0,
                'bgColor' => $warningBg,
            ]);
            $noteCell->addText('CATATAN KONDISI DARI PEMBELI', ['bold' => true, 'size' => 8, 'color' => '78350f']);
            $noteCell->addText($pembelian->handover_condition_notes, ['size' => 9, 'color' => '78350f']);
        }

        // ── Info pengiriman ────────────────────────────────────────
        $tblShipH = $cellShip->addTable(['cellMarginTop' => 60, 'cellMarginBottom' => 60, 'cellMarginLeft' => 120]);
        $tblShipH->addRow();
        $tblShipH->addCell($halfWidth, ['bgColor' => $hijau,
            'borderTopColor' => $hijau, 'borderTopSize' => 4,
            'borderBottomColor' => $hijau, 'borderBottomSize' => 4,
            'borderLeftColor' => $hijau, 'borderLeftSize' => 4,
            'borderRightColor' => $hijau, 'borderRightSize' => 4,
        ])->addText('INFORMASI PENGIRIMAN', $styleSectionHeader);

        $shipRows = [
            'Metode Pengiriman' => $pembelian->delivery_method ?? '-',
            'Petugas Pengiriman'=> $pembelian->delivery_officer ?? '-',
            'No. Resi'          => $pembelian->delivery_tracking_number ?? '-',
            'Tanggal Dikirim'   => $pembelian->shipped_at?->translatedFormat('d F Y, H:i') ?? '-',
            'Tanggal Diterima'  => $pembelian->received_at?->translatedFormat('d F Y, H:i') ?? '-',
            'Alamat Pengiriman' => $pembelian->delivery_location ?? '-',
            'Nama Penerima'     => $pembelian->recipient_name ?? '-',
        ];

        $tblShip = $cellShip->addTable(['cellMarginTop' => 60, 'cellMarginBottom' => 60, 'cellMarginLeft' => 120]);
        foreach ($shipRows as $lbl => $val) {
            $tblShip->addRow();
            $tblShip->addCell(1600, $noBorder)->addText($lbl, $styleLabel);
            $tblShip->addCell($halfWidth - 1600, $noBorder)->addText($val, $styleValNormal);
        }

        $section->addTextBreak(1);

        // ════════════════════════════════════════════════════════════
        // 5. KLAUSUL PERNYATAAN
        // ════════════════════════════════════════════════════════════
        $addSectionHeader('Pernyataan dan Klausul Serah Terima');
        $section->addTextBreak(0);

        $klausul = [
            'Pernyataan Serah Terima' => [
                'bg' => $hijauMuda, 'border' => $hijau,
                'teks' => 'Dengan ditandatanganinya dokumen ini, Pihak Pertama menyatakan telah menyerahkan koleksi lukisan sebagaimana tercantum di atas kepada Pihak Kedua, dan Pihak Kedua menyatakan telah menerima koleksi tersebut dalam kondisi yang baik dan sesuai dokumentasi.',
            ],
            'Peralihan Hak Kepemilikan' => [
                'bg' => $hijauMuda, 'border' => $hijau,
                'teks' => 'Hak kepemilikan atas koleksi lukisan beralih sepenuhnya kepada Pihak Kedua sejak tanggal ditandatanganinya dokumen ini. Pihak Pertama menjamin bahwa koleksi adalah asli dan merupakan hak milik sah yang bebas dari sengketa hukum maupun klaim pihak lain.',
            ],
            'Hak Cipta' => [
                'bg' => $hijauMuda, 'border' => $hijau,
                'teks' => 'Hak cipta dan hak moral atas karya lukisan tetap menjadi milik seniman sesuai ketentuan Undang-Undang No. 28 Tahun 2014 tentang Hak Cipta, kecuali diperjanjikan lain secara tertulis. Peralihan kepemilikan fisik tidak serta merta mengalihkan hak cipta.',
            ],
            'Kekuatan Hukum' => [
                'bg' => $hijauMuda, 'border' => $hijau,
                'teks' => 'Dokumen Berita Acara Serah Terima ini dibuat dalam dua rangkap asli, masing-masing bermaterai cukup (Materai Rp10.000), dan memiliki kekuatan hukum yang sama bagi kedua pihak. Dokumen ini berlaku sebagai bukti sah pengalihan kepemilikan koleksi.',
            ],
        ];

        foreach ($klausul as $judul => $item) {
            $tblK = $section->addTable(['cellMarginTop' => 60, 'cellMarginBottom' => 60, 'cellMarginLeft' => 120, 'cellMarginRight' => 120]);
            $tblK->addRow();
            $kCell = $tblK->addCell($contentWidth, [
                'bgColor'           => $item['bg'],
                'borderLeftColor'   => $item['border'], 'borderLeftSize'   => 12,
                'borderTopColor'    => 'FFFFFF', 'borderTopSize'    => 0,
                'borderBottomColor' => 'FFFFFF', 'borderBottomSize' => 0,
                'borderRightColor'  => 'FFFFFF', 'borderRightSize'  => 0,
            ]);
            $kCell->addText(strtoupper($judul), ['bold' => true, 'size' => 8, 'color' => $hijau]);
            $kCell->addText($item['teks'], ['size' => 9, 'color' => $hitam]);
            $section->addTextBreak(0);
        }

        $section->addTextBreak(1);

        // ════════════════════════════════════════════════════════════
        // 6. TANDA TANGAN
        // ════════════════════════════════════════════════════════════
        $addSectionHeader('Tanda Tangan Para Pihak');
        $section->addTextBreak(0);

        $tblTtd = $section->addTable([
            'borderTopColor' => 'CCCCCC', 'borderTopSize' => 4,
            'borderBottomColor' => 'CCCCCC', 'borderBottomSize' => 4,
            'borderLeftColor' => 'CCCCCC', 'borderLeftSize' => 4,
            'borderRightColor' => 'CCCCCC', 'borderRightSize' => 4,
            'cellMarginTop' => 120, 'cellMarginBottom' => 120,
            'cellMarginLeft' => 200, 'cellMarginRight' => 200,
        ]);
        $tblTtd->addRow(4320); // ~3 inch tinggi

        // ── TTD Pihak Pertama (Pengelola) — sudah ter-generate ───
        $cellTtd1 = $tblTtd->addCell($halfWidth, [
            'borderRightColor' => 'CCCCCC', 'borderRightSize' => 4,
            'borderTopColor' => 'FFFFFF', 'borderTopSize' => 0,
            'borderBottomColor' => 'FFFFFF', 'borderBottomSize' => 0,
            'borderLeftColor' => 'FFFFFF', 'borderLeftSize' => 0,
        ]);
        $cellTtd1->addText('Purwakarta, ' . now()->translatedFormat('d F Y'), $styleSmall, $parCenter);
        $cellTtd1->addText('Pihak Pertama,', $styleSmall, $parCenter);
        $cellTtd1->addTextBreak(1);

        // Embed TTD pengelola (gambar base64 sama dengan invoice)
        //$ttdBase64 = '/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAYEBQYFBAYGBQYHBwYIChAKCgkJChQODwwQFxQYGBcUFhYaHSUfGhsjHBYWICwgIyYnKSopGR8tMC0oMCUoKSj/2wBDAQcHBwoIChMKChMoGhYaKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCj/wgARCABkASwDASIAAhEBAxEB/8QAGwABAAIDAQEAAAAAAAAAAAAAAAQFAgMGAQf/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAH2QAAAAAAAAAAAAAAAAAAAAAAAABFrIAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA//EACsQAAICAQMDBAICAgMAAAAAAAMEAgEABRASExQgMDQVITEyQSUjJCZQ/9oACAEBAAEFAvyNeUDRYps4bXO2IkhrVp4qnizrmpxkny3EH3fSpHHxkxxYBNsvx53P86jlykgPfX0k6QmwVnpqNVZ+CPe1NpE6Ei98yeVlBTJdJauhTkoGPZiA6SHprIJrtWgo9S6h1xs8uVHoecHe5j2vb+/u+GSk4gHjupTS0fr4o7aqqr7IkZElNWOKj1JWfW4xZXmBfvHuprZGTIlDIrX0hrquH28wzqWwxQhYF/vNtkL7uvR7BAvsnzSzJ2fonZCgBNTHtyfqkLpjou2hncEJTKQ9Xjjlk22qeKlnVQcfOR7He4wcrZA7ythFuvcA2VPoZ7hFzr4+dfGytjC5m7BH3jJgF7U5ERRzFWPzj62zJOTl457FAfsSFWooxlOLm6+SF0x0pYy5SQjB6FB9S74ZKGumCzRQ46H1VLtxPoceGLSAiLldjAZa0wc+u7Oku6QcrjtcUUfWsIZ7fBxGECRf04XGgjNtoqLapJlZ2252EubtsxyKbquPGu+nC4tChKuW1sV72a92m2HLItTImUMqepOkJYJfOOcfR20O1h9NIpwv2O4EWmFqUfwr4ddVRZK00dq4vPK+rtJvjA63G0KNONUlJZFOkkvqJMQHYTPKqq+HLSmIa0LWIRgiLeH2zmNdRMIEvb4+SAcDL8XNdTq+u641MCKxMhr8n1BD133ytPJfLygqLRx8vJktUo6WanEwmpoUnF+oqpSSQl9X2GcN7CUMD4In03/pysnJKB6+MjaaTEm/eEDpEMJHWiMCJYWN9cgEkcgXHGxC8FNUZ6+zSTRhdSjnJ/M9IWO2Bi/ASnylDpERGhx/U2Mrxo2K4RQEVGELl/Xw/QOrmolJWFkE3tUT+u640lHjpE+pd8KmZ+knIsUJkU28jMkjkgB4FOzjF1GopayNZEh6u1Hf5GLEXlia/U7UUxKPgKe2I+0OMREQIlz5B0VFIxw0JymT+Pum1/VPTQ6TEEdwK7Ymvj2PGeoWvQ48SjcpMNbTW/qJE1MIYQYmakE2NTT+JSfHEwHZEiFK9YopEVkls3HI+KLkiAAEAk9rIpGN0pOvjY5W7uAjqAFc6mtWd1udY2mt+JyZaFi0hMCMiTPOC+JqfcEUntS1OjyfLEy82mTaSpq4XigfprdTaLmgxsUnTTcEgSClE02ps+Fb4Jq3zKa/CJkDsrlb6ZZ44tG7Q67UcdJuidcallVwrN0v+20m/wCqakWx4muR7iys2gzxo/TBau/g9bsBIF/3qIx06qEMwQYo4YWjtmYx1xcjKEka2Yk2Mm7BagumunsMrQiTmuaqPXBDDCUxUHzUkU6tgLGcPsgSuMlAntuSD4On4+mp7MG7HbOsoui5zkli0EHIkorfZ26vlVdJJpU6Chim1I4pPHFZ4s0dY2sotsYVEZv0Xk9HvCLhJS49cQ4ctv18eGKyAqVF7KKm2RniiXVSpKoOuFKuDGTFQ+dz/wCu1YtMRUwpeVNjx6FDzblu9IQYthx6r6TTargW1jKkJWyVDIsTkl4soA9Kbb0ShCWm42bvI6UiTWSRNYJOIiINb1tKRxShGkwE0RVCfJIIVdVUiWTKsKg80+0aWLPHHkJMvyx8dKhNcpOR6eXswfF+xN5/MmiKTDnFGpa8erg2rpJrJRoaVNY1vomCpFozUC4XE1HpPG2MxJg+0IW1myhOupsG2L7QM1q20r3as0erihhKmcbvI+LJOcHrQybRhEBm/ScKmYOZAFIkw0CwJTJg3wAtdZ5kzl/wA5ccHdppJp5dVdUglV8Ppe7lZMFrShWnk9ozcBu2WPsLEomKGXmi2Mam36VU2qpsgAGqMTanXD5VDHVxSFAUcprSfBSGkkcqOm07ctPVnkT2eRPZ5E9nfnsHSnCHdLm8RgT1rrWeLm6wPwT1oNjnQoPLceMzGoMrGM5W+tdccMhgysM1dZtvgZBmOiDm54RPFGOLVehrpymAavxxLWgWWkCKllNqvU28ylSdPD7aHpXm2SFsrWI';

        //$ttdImageData = base64_decode($ttdBase64);
        //$tmpTtd = tempnam(sys_get_temp_dir(), 'ttd_') . '.jpg';
        //file_put_contents($tmpTtd, $ttdImageData);

        //$cellTtd1->addImage($tmpTtd, [
        //    'width'     => 100,
        //    'height'    => 50,
        //    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        //]);

        $cellTtd1->addTextBreak(0);
        $cellTtd1->addText('___________________________', $styleBody, $parCenter);
        $cellTtd1->addText('Pengelola Museum', ['bold' => true, 'size' => 10, 'color' => $hitam], $parCenter);
        $cellTtd1->addText('MK. Lesmana', $styleSmall, $parCenter);
        $cellTtd1->addText('Tanggal: ' . now()->translatedFormat('d F Y'), $styleSmall, $parCenter);

        // ── TTD Pihak Kedua (Pembeli) — kolom kosong ─────────────
        $cellTtd2 = $tblTtd->addCell($halfWidth, [
            'borderLeftColor' => 'CCCCCC', 'borderLeftSize' => 4,
            'borderTopColor' => 'FFFFFF', 'borderTopSize' => 0,
            'borderBottomColor' => 'FFFFFF', 'borderBottomSize' => 0,
            'borderRightColor' => 'FFFFFF', 'borderRightSize' => 0,
        ]);

        $tanggalKota = $pembelian->kota_kabupaten ? $pembelian->kota_kabupaten . ', .........................' : '................................., .........................';
        $cellTtd2->addText($tanggalKota, $styleSmall, $parCenter);
        $cellTtd2->addText('Pihak Kedua — Pembeli,', $styleSmall, $parCenter);
        $cellTtd2->addTextBreak(3);
        $cellTtd2->addText('___________________________', $styleBody, $parCenter);

        $namaPembeli = $pembelian->buyer_type === 'b2c'
            ? ($pembelian->nama_lengkap ?? '.................................')
            : ($pembelian->pic_name ?? '.................................');
        $rolePembeli = $pembelian->buyer_type === 'b2c'
            ? 'Pembeli Perorangan'
            : 'PIC — ' . ($pembelian->company_name ?? '-');

        $cellTtd2->addText($namaPembeli, ['bold' => true, 'size' => 10, 'color' => $hitam], $parCenter);
        $cellTtd2->addText($rolePembeli, $styleSmall, $parCenter);
        $cellTtd2->addText('Tanggal: .........................', $styleSmall, $parCenter);

        $section->addTextBreak(1);

        // ════════════════════════════════════════════════════════════
        // 7. FOOTER
        // ════════════════════════════════════════════════════════════
        $tblFoot = $section->addTable(['cellMarginTop' => 60, 'cellMarginBottom' => 60, 'cellMarginLeft' => 0]);
        $tblFoot->addRow();
        $footCell = $tblFoot->addCell($contentWidth, [
            'borderTopColor' => 'CCCCCC', 'borderTopSize' => 4,
            'borderBottomColor' => 'FFFFFF', 'borderBottomSize' => 0,
            'borderLeftColor' => 'FFFFFF', 'borderLeftSize' => 0,
            'borderRightColor' => 'FFFFFF', 'borderRightSize' => 0,
        ]);
        $footCell->addText(
            'Museum MK. Lesmana  •  Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175',
            ['size' => 7, 'color' => 'AAAAAA'], $parCenter
        );
        $footCell->addText(
            'BAST No. BAST/' . str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) . '/' . now()->format('Y') . '  •  Ref. Invoice: ' . ($pembelian->invoice_number ?? '-') . '  •  Dicetak: ' . now()->format('d/m/Y H:i') . ' WIB',
            ['size' => 7, 'color' => 'AAAAAA'], $parCenter
        );

        // ── Cleanup temp TTD file ────────────────────────────────
        //if (isset($tmpTtd) && file_exists($tmpTtd)) {
        //    @unlink($tmpTtd);
        //}

        return $this->saveDocx($phpWord, 'pembelian_handover_documents', 'pembelian_handover_' . $pembelian->id);
    }

public function generatePurchaseHandoverPdf(Pembelian $pembelian): string
    {
        $hijau     = '#1a5c2e';
        $hitam     = '#1a1a1a';
        $abu       = '#555555';
        $hijauMuda = '#f6fbf8';

        // ── Helper format tanggal Indonesia ──────────────────────────
        $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                      'Juli','Agustus','September','Oktober','November','Desember'];

        $fmtTgl = function($date) use ($namaBulan) {
            if (!$date) return '-';
            $dt = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
            return $dt->format('d') . ' ' . $namaBulan[(int)$dt->format('n')] . ' ' . $dt->format('Y');
        };

        $fmtTglJam = function($date) use ($namaBulan) {
            if (!$date) return '-';
            $dt = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
            $dt = $dt->setTimezone('Asia/Jakarta');
            return $dt->format('d') . ' ' . $namaBulan[(int)$dt->format('n')] . ' ' . $dt->format('Y') . ', pukul ' . $dt->format('H:i') . ' WIB';
        };

        // ── TTD Pengelola dari public/images ─────────────────────────
        $ttdPath   = public_path('images/ttd_pengelola.png');
        $ttdBase64 = null;
        if (file_exists($ttdPath)) {
            $ttdBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPath));
        }
        $ttdHtml = $ttdBase64
            ? "<img src='{$ttdBase64}' style='height:60px;width:auto;margin-bottom:4px;'>"
            : "<div style='height:60px;'></div>";

        // ── Data pembeli ──────────────────────────────────────────────
        if ($pembelian->buyer_type === 'b2c') {
            $pihakDuaHtml = "
            <tr><td style='color:{$abu};font-size:9pt;width:140px;padding:3px 0;'>Nama Lengkap</td><td style='font-size:9pt;font-weight:bold;'>{$pembelian->nama_lengkap}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>NIK</td><td style='font-size:9pt;'>{$pembelian->nik}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>Tempat, Tgl. Lahir</td><td style='font-size:9pt;'>{$pembelian->tempat_lahir}, {$fmtTgl($pembelian->tanggal_lahir)}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>Pekerjaan</td><td style='font-size:9pt;'>{$pembelian->pekerjaan}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>Nomor HP</td><td style='font-size:9pt;'>{$pembelian->nomor_hp}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>Email</td><td style='font-size:9pt;'>{$pembelian->email}</td></tr>
            " . ($pembelian->npwp ? "<tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>NPWP</td><td style='font-size:9pt;'>{$pembelian->npwp}</td></tr>" : '') . "
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>Alamat</td><td style='font-size:9pt;'>{$pembelian->alamat_pengiriman}, RT {$pembelian->rt}/RW {$pembelian->rw}, {$pembelian->kelurahan_desa}, {$pembelian->kota_kabupaten}, {$pembelian->provinsi}</td></tr>";
        } else {
            $pihakDuaHtml = "
            <tr><td style='color:{$abu};font-size:9pt;width:140px;padding:3px 0;'>Nama Perusahaan</td><td style='font-size:9pt;font-weight:bold;'>{$pembelian->company_name}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>Jenis Perusahaan</td><td style='font-size:9pt;'>{$pembelian->company_type}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>NPWP Perusahaan</td><td style='font-size:9pt;'>{$pembelian->company_npwp}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>Alamat Perusahaan</td><td style='font-size:9pt;'>{$pembelian->company_address}, {$pembelian->company_city}, {$pembelian->company_province}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>PIC / Perwakilan</td><td style='font-size:9pt;font-weight:bold;'>{$pembelian->pic_name}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>Jabatan PIC</td><td style='font-size:9pt;'>{$pembelian->pic_position}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>NIK PIC</td><td style='font-size:9pt;'>{$pembelian->pic_nik}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>Nomor HP PIC</td><td style='font-size:9pt;'>{$pembelian->pic_phone}</td></tr>
            <tr><td style='color:{$abu};font-size:9pt;padding:3px 0;'>Email PIC</td><td style='font-size:9pt;'>{$pembelian->pic_email}</td></tr>";
        }

        // ── Variabel umum ─────────────────────────────────────────────
        $noRef          = 'BAST/' . str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) . '/' . now()->format('Y');
        $noBli          = 'BLI-' . str_pad($pembelian->id, 5, '0', STR_PAD_LEFT);
        $tanggal        = $fmtTgl(now());
        $invoiceNumber  = $pembelian->invoice_number ?? '-';
        $hargaBeli      = $this->formatRupiah($pembelian->harga_beli);
        $ongkosKirim    = (int)($pembelian->shipping_cost ?? 0) === 0
                            ? 'Gratis'
                            : $this->formatRupiah($pembelian->shipping_cost);
        $ongkosKirimLabel = 'Ongkos Kirim';
        if ($pembelian->shipping_method_type === 'courier' && $pembelian->courier_name) {
            $ongkosKirimLabel .= ' (' . $pembelian->courier_name . ')';
        } elseif ($pembelian->shipping_method_type === 'manager') {
            $ongkosKirimLabel .= ' (Pengelola)';
        }
        $totalBayar     = $this->formatRupiah($pembelian->total_bayar);
        $deliveryMethod = $pembelian->delivery_method ?? '-';
        $deliveryOfficer= $pembelian->delivery_officer ?? '-';
        $noResi         = $pembelian->delivery_tracking_number ?? '-';
        $tglDikirim     = $fmtTglJam($pembelian->shipped_at);
        $tglDiterima    = $fmtTglJam($pembelian->received_at);
        $alamatKirim    = $pembelian->delivery_location ?? '-';
        $namaPenerima   = $pembelian->recipient_name ?? '-';
        $kotaPembeli    = $pembelian->kota_kabupaten ?? '...................';
        $namaPembeli    = $pembelian->buyer_type === 'b2c'
                            ? ($pembelian->nama_lengkap ?? '-')
                            : ($pembelian->pic_name ?? '-');
        $rolePembeli    = $pembelian->buyer_type === 'b2c'
                            ? 'Pembeli Perorangan'
                            : 'PIC — ' . ($pembelian->company_name ?? '-');
        $printedAt      = $fmtTglJam(now());
        $paintingTitle      = $pembelian->painting->title ?? '-';
        $paintingArtist     = $pembelian->painting->artist ?? '-';
        $paintingYear       = $pembelian->painting->year_created ?? ($pembelian->painting->year ?? '-');
        $paintingMedia      = $pembelian->painting->media ?? '-';
        $paintingDimensions = $pembelian->painting->dimensions ?? '-';
        $paintingCategory   = $pembelian->painting->category ?? '-';
        $paintingCollection = $pembelian->painting->collection_number ?? '-';
        $ongkosKirimColor = ($ongkosKirim === 'Gratis') ? '#059669' : $hitam;

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: Arial, sans-serif; font-size: 10pt; color: {$hitam}; }
.page { padding: 20px 24px; }

/* HEADER */
.header-table { width: 100%; border-bottom: 3px solid {$hijau}; padding-bottom: 10px; margin-bottom: 10px; }
.museum-name { font-size: 13pt; font-weight: bold; color: {$hijau}; }
.museum-addr { font-size: 8pt; color: {$abu}; margin-top: 2px; }
.doc-title { font-size: 14pt; font-weight: bold; text-align: right; }
.doc-subtitle { font-size: 9pt; color: {$abu}; text-align: right; margin-top: 2px; }

/* INFO BAR */
.info-bar { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
.info-bar td { border: 1px solid #CCCCCC; padding: 6px 10px; width: 33.33%; }
.info-label { font-size: 8pt; color: {$abu}; }
.info-val { font-size: 10pt; font-weight: bold; color: {$hitam}; margin-top: 2px; }

/* SECTION HEADER */
.sec-header { background: {$hijau}; color: #FFFFFF; font-size: 9pt; font-weight: bold;
              padding: 5px 10px; margin: 10px 0 0 0; letter-spacing: 0.5px; }

/* PARA PIHAK */
.pihak-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
.pihak-inner { width: 100%; border-collapse: collapse; }
.pihak-inner td { padding: 3px 0; }

/* KOLEKSI */
.koleksi-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
.koleksi-table th { background: {$hijau}; color: #fff; font-size: 9pt; padding: 6px 8px; text-align: left; }
.koleksi-table td { border: 1px solid #CCCCCC; padding: 7px 8px; font-size: 9pt; vertical-align: top; }

/* INFO PENGIRIMAN full-width */
.ship-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
.ship-table td { padding: 5px 8px; font-size: 9pt; border: 1px solid #e5e5e5; vertical-align: top; }
.ship-table tr:nth-child(odd) td { background: {$hijauMuda}; }
.ship-lbl { font-size: 8pt; color: {$abu}; margin-bottom: 2px; }
.ship-val { font-size: 9pt; color: {$hitam}; }

/* KLAUSUL */
.klausul { border-left: 4px solid {$hijau}; background: {$hijauMuda};
           padding: 6px 10px; margin-bottom: 6px; }
.klausul-title { font-size: 8pt; font-weight: bold; color: {$hijau}; text-transform: uppercase; }
.klausul-text { font-size: 9pt; color: {$hitam}; margin-top: 3px; line-height: 1.5; }

/* HALAMAN 2: TTD */
.page-break { page-break-before: always; }
.ttd-page { padding: 80px 24px 20px; } /* jeda kosong atas */
.ttd-sec-title { background: {$hijau}; color: #fff; font-size: 10pt; font-weight: bold;
                 padding: 6px 10px; text-align: center; letter-spacing: 1px;
                 margin-bottom: 28px; }
.ttd-table { width: 100%; border-collapse: collapse; }
.ttd-table td { width: 50%; padding: 16px 30px; vertical-align: top; text-align: center; font-size: 9pt; }
.ttd-border-right { border-right: 1px solid #CCCCCC; }
.ttd-line { border-top: 1px solid {$hitam}; margin: 6px auto 6px; width: 200px; padding-top: 5px; }

/* FOOTER */
.footer { border-top: 1px solid #CCCCCC; padding-top: 6px; text-align: center;
          font-size: 7pt; color: #AAAAAA; margin-top: 16px; }
</style>
</head>
<body>

<!-- ══════════ HALAMAN 1 ══════════ -->
<div class="page">

<!-- HEADER -->
<table class="header-table">
    <tr>
        <td style="width:55%;">
            <div class="museum-name">Museum MK. Lesmana</div>
            <div class="museum-addr">Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes</div>
            <div class="museum-addr">Kabupaten Purwakarta, Jawa Barat 41175</div>
        </td>
        <td style="width:45%; text-align:right;">
            <div class="doc-title">SERAH TERIMA</div>
            <div class="doc-subtitle">No. {$noRef}</div>
            <div class="doc-subtitle" style="margin-top:3px;">Tanggal: <strong style="color:{$hitam};">{$tanggal}</strong> &nbsp;|&nbsp; Ref. Invoice: <strong style="color:{$hitam};">{$invoiceNumber}</strong></div>
        </td>
    </tr>
</table>

<!-- PARA PIHAK -->
<div class="sec-header">IDENTITAS PARA PIHAK</div>
<table class="pihak-table" style="margin-top:6px;">
    <tr>
        <td style="width:50%; padding-right:8px; vertical-align:top;">
            <div style="background:{$hijau};color:#fff;font-size:9pt;font-weight:bold;padding:4px 8px;margin-bottom:5px;">PIHAK PERTAMA — PENJUAL</div>
            <table class="pihak-inner">
                <tr><td style="color:{$abu};font-size:9pt;width:120px;">Nama Lembaga</td><td style="font-size:9pt;font-weight:bold;">Museum MK. Lesmana</td></tr>
                <tr><td style="color:{$abu};font-size:9pt;">Diwakili oleh</td><td style="font-size:9pt;">Pengelola Museum</td></tr>
                <tr><td style="color:{$abu};font-size:9pt;">Alamat</td><td style="font-size:9pt;">Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175</td></tr>
            </table>
        </td>
        <td style="width:50%; padding-left:8px; vertical-align:top;">
            <div style="background:{$hijau};color:#fff;font-size:9pt;font-weight:bold;padding:4px 8px;margin-bottom:5px;">PIHAK KEDUA — PEMBELI</div>
            <table class="pihak-inner">
                {$pihakDuaHtml}
            </table>
        </td>
    </tr>
</table>

<!-- DETAIL KOLEKSI -->
<div class="sec-header">RINCIAN KOLEKSI YANG DISERAHTERIMAKAN</div>
<table class="koleksi-table" style="margin-top:6px;">
    <tr>
        <th style="width:28px;">No</th>
        <th>Deskripsi Koleksi</th>
        <th style="width:38px; text-align:center;">Qty</th>
        <th style="width:145px; text-align:right;">Harga Beli (Rp)</th>
        <th style="width:135px; text-align:right;">{$ongkosKirimLabel} (Rp)</th>
        <th style="width:145px; text-align:right;">Total (Rp)</th>
    </tr>
    <tr>
        <td style="text-align:center; color:#aaa; font-size:8pt;">1</td>
        <td>
            <strong style="font-size:10.5pt;">{$paintingTitle}</strong><br>
            <span style="color:{$abu};font-size:8pt;">
                Seniman &nbsp;: {$paintingArtist}<br>
                Tahun &nbsp;&nbsp;&nbsp;: {$paintingYear}<br>
                Media &nbsp;&nbsp;&nbsp;: {$paintingMedia}<br>
                Dimensi &nbsp;: {$paintingDimensions}<br>
                Kategori : {$paintingCategory}<br>
                No. Koleksi : {$paintingCollection}
            </span>
        </td>
        <td style="text-align:center;">1</td>
        <td style="text-align:right; font-weight:bold;">{$hargaBeli}</td>
        <td style="text-align:right; font-weight:bold; color:{$ongkosKirimColor};">{$ongkosKirim}</td>        <td style="text-align:right; font-weight:bold;">{$totalBayar}</td>
    </tr>
</table>

<!-- INFO PENGIRIMAN (full-width) -->
<div class="sec-header">INFORMASI PENGIRIMAN</div>
<table class="ship-table" style="margin-top:6px;">
    <tr>
        <td style="width:18%;">
            <div class="ship-lbl">Metode Pengiriman</div>
            <div class="ship-val">{$deliveryMethod}</div>
        </td>
        <td style="width:18%;">
            <div class="ship-lbl">Petugas Pengiriman</div>
            <div class="ship-val">{$deliveryOfficer}</div>
        </td>
        <td style="width:20%;">
            <div class="ship-lbl">No. Resi</div>
            <div class="ship-val">{$noResi}</div>
        </td>
        <td style="width:22%;">
            <div class="ship-lbl">Tanggal Dikirim</div>
            <div class="ship-val">{$tglDikirim}</div>
        </td>
        <td style="width:22%;">
            <div class="ship-lbl">Tanggal Diterima</div>
            <div class="ship-val">{$tglDiterima}</div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div class="ship-lbl">Alamat Pengiriman</div>
            <div class="ship-val">{$alamatKirim}</div>
        </td>
        <td colspan="3">
            <div class="ship-lbl">Nama Penerima</div>
            <div class="ship-val">{$namaPenerima}</div>
        </td>
    </tr>
</table>

<!-- KLAUSUL -->
<div class="sec-header">PERNYATAAN DAN KLAUSUL SERAH TERIMA</div>
<div style="margin-top:6px;">
    <div class="klausul">
        <div class="klausul-title">Pernyataan Serah Terima</div>
        <div class="klausul-text">Dengan ditandatanganinya dokumen ini, Pihak Pertama menyatakan telah menyerahkan koleksi lukisan sebagaimana tercantum di atas kepada Pihak Kedua, dan Pihak Kedua menyatakan telah menerima koleksi tersebut dalam kondisi yang baik dan sesuai dokumentasi.</div>
    </div>
    <div class="klausul">
        <div class="klausul-title">Peralihan Hak Kepemilikan</div>
        <div class="klausul-text">Hak kepemilikan atas koleksi lukisan beralih sepenuhnya kepada Pihak Kedua sejak tanggal ditandatanganinya dokumen ini. Pihak Pertama menjamin bahwa koleksi adalah asli dan merupakan hak milik sah yang bebas dari sengketa hukum maupun klaim pihak lain.</div>
    </div>
    <div class="klausul">
        <div class="klausul-title">Hak Cipta</div>
        <div class="klausul-text">Hak cipta dan hak moral atas karya lukisan tetap menjadi milik seniman sesuai ketentuan Undang-Undang No. 28 Tahun 2014 tentang Hak Cipta, kecuali diperjanjikan lain secara tertulis. Peralihan kepemilikan fisik tidak serta merta mengalihkan hak cipta.</div>
    </div>
    <div class="klausul">
        <div class="klausul-title">Kekuatan Hukum</div>
        <div class="klausul-text">Dokumen Berita Acara Serah Terima ini dibuat dalam dua rangkap asli, masing-masing bermaterai cukup (Materai Rp10.000), dan memiliki kekuatan hukum yang sama bagi kedua pihak. Dokumen ini berlaku sebagai bukti sah pengalihan kepemilikan koleksi.</div>
    </div>
</div>

<!-- FOOTER HALAMAN 1 -->
<div class="footer">
    Museum MK. Lesmana &bull; Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175<br>
    {$noRef} &bull; Ref. Invoice: {$invoiceNumber} &bull; Dicetak: {$printedAt} WIB
</div>

</div><!-- end page 1 -->


<!-- ══════════ HALAMAN 2: TANDA TANGAN ══════════ -->
<div class="page-break">
<div class="ttd-page">

    <div class="ttd-sec-title">TANDA TANGAN PARA PIHAK</div>

    <table class="ttd-table">
        <tr>
            <!-- Pihak Pertama: TTD Pengelola (dari public/images) -->
            <td class="ttd-border-right">
                <div style="margin-bottom:50px;">
                    <div style="font-size:9pt; color:{$abu};">Purwakarta, {$tanggal}</div>
                    <div style="font-size:9pt; color:{$abu}; margin-top:2px;">Pihak Pertama — Penjual,</div>
                </div>
                {$ttdHtml}
                <div class="ttd-line">
                    <div style="font-weight:bold; font-size:10.5pt;">Pengelola Museum</div>
                    <div style="font-size:9pt; color:{$abu};">Museum MK. Lesmana</div>
                </div>
            </td>

            <!-- Pihak Kedua: Kolom kosong untuk TTD pembeli -->
            <td>
                <div style="margin-bottom:50px;">
                    <div style="font-size:9pt; color:{$abu};">{$kotaPembeli}, .................................</div>
                    <div style="font-size:9pt; color:{$abu}; margin-top:2px;">Pihak Kedua — Pembeli,</div>
                </div>
                <div style="border: 1px dashed #aaa; height:70px; background:#fafafa; margin-bottom:4px;"></div>
                <div style="font-size:8pt; color:#888; font-style:italic; margin-bottom:4px;">(Tanda tangan &amp; Materai 10.000)</div>
                <div class="ttd-line">
                    <div style="font-weight:bold; font-size:10.5pt;">{$namaPembeli}</div>
                    <div style="font-size:9pt; color:{$abu};">{$rolePembeli}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Footer halaman 2 -->
    <div class="footer" style="margin-top:40px;">
        Museum MK. Lesmana &bull; Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175<br>
        {$noRef} &bull; Ref. Invoice: {$invoiceNumber} &bull; Dicetak: {$printedAt} WIB
    </div>

</div>
</div><!-- end halaman 2 -->

</body>
</html>
HTML;

        // Generate PDF dengan DomPDF
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();

        $fileName = 'pembelian_handover_' . $pembelian->id . '_' . now()->format('Ymd_His') . '.pdf';
        $path     = 'pembelian_handover_documents/' . $fileName;

        Storage::disk('public')->put($path, $pdfContent);

        return $path;
    }

    public function generateAuthenticityCertificatePdf(Pembelian $pembelian): string
    {
        // ── 1. Buat / ambil certificate_id unik ──────────────────────
        if ($pembelian->certificate_document_path) {
            // Ambil cert ID dari path yang sudah ada jika tersimpan
            // Format path: certificate_documents/certificate_CERTID_timestamp.pdf
            preg_match('/certificate_([A-Z0-9\-]+)_\d{8}/', basename($pembelian->certificate_document_path), $m);
            $certId = $m[1] ?? $this->makeCertId($pembelian);
        } else {
            $certId = $this->makeCertId($pembelian);
        }
    
        // ── 2. Generate QR Code ───────────────────────────────────────
        $verifyUrl    = url('/verify-certificate/' . $certId);
        $qrCodeDataUri = $this->generateQrCodeDataUri($verifyUrl);
    
        $ttdBase64 = base64_encode(
            file_get_contents(storage_path('app/public/ttd/TTD.jpg'))
        );

        $logoBase64 = base64_encode(
            file_get_contents(public_path('images/logo_museum_mk_lesmana.png'))
        );

        $paintingImageBase64 = null;
        if ($pembelian->painting->image_path &&
            file_exists(storage_path('app/public/' . $pembelian->painting->image_path))) {
            $paintingImageBase64 = base64_encode(
                file_get_contents(storage_path('app/public/' . $pembelian->painting->image_path))
            );
        }
        
        // ── 3. Render blade → HTML ────────────────────────────────────
        $html = view('pembelian.certificate-pdf', [
            'pembelian'     => $pembelian,
            'certId'        => $certId,
            'qrCodeDataUri' => $qrCodeDataUri,
            'ttdBase64'     => $ttdBase64,
            'logoBase64'    => $logoBase64,
            'paintingImageBase64' => $paintingImageBase64,
        ])->render();
    
        // ── 4. HTML → PDF via DomPDF ──────────────────────────────────
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        $pdfContent = $dompdf->output();
    
        // ── 5. Simpan ke storage ──────────────────────────────────────
        $fileName = 'certificate_' . $certId . '_' . now()->format('Ymd_His') . '.pdf';
        $path     = 'certificate_documents/' . $fileName;
    
        Storage::disk('public')->put($path, $pdfContent);
    
        // ── 6. Simpan path ke model ───────────────────────────────────
        $pembelian->update(['certificate_document_path' => $path]);
    
        return $path;
    }
    
    /**
     * Buat certificate ID unik: COA-{TAHUN}-{HASH8}.
     */
    private function makeCertId(Pembelian $pembelian): string
    {
        $hash = strtoupper(substr(md5('cert-' . $pembelian->id . '-' . $pembelian->created_at), 0, 8));
        return 'COA-' . now()->format('Y') . '-' . $hash;
    }
    
    /**
     * Generate QR code sebagai data URI (base64 PNG).
     * Menggunakan endroid/qr-code (direkomendasikan) atau fallback ke
     * Google Charts API (tidak butuh library tambahan).
     */
    private function generateQrCodeDataUri(string $url): string
    {
        // ── Opsi A: endroid/qr-code (composer require endroid/qr-code) ──
        if (class_exists(\Endroid\QrCode\QrCode::class)) {
            $qrCode = new \Endroid\QrCode\QrCode(
                data: $url,
                encoding: new \Endroid\QrCode\Encoding\Encoding('UTF-8'),
                errorCorrectionLevel: \Endroid\QrCode\ErrorCorrectionLevel::High,
                size: 200,
                margin: 4,
                foregroundColor: new \Endroid\QrCode\Color\Color(26, 92, 46),
                backgroundColor: new \Endroid\QrCode\Color\Color(255, 255, 255),
            );

            $writer = new \Endroid\QrCode\Writer\PngWriter();
            $result = $writer->write($qrCode);

            return 'data:image/png;base64,' . base64_encode($result->getString());
        }
        // ── Opsi B: simplesoftwareio/simple-qrcode ────────────────────
        //if (class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class)) {
        //    $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(200)->generate($url);
        //    return 'data:image/svg+xml;base64,' . base64_encode($svg);
        //}
    
        // ── Opsi C: fallback Google Charts (online, tidak perlu library) ─
        // Berguna saat testing lokal tanpa install library
        $encodedUrl = urlencode($url);
        $chartUrl   = "https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl={$encodedUrl}&choe=UTF-8";
    
        // Coba fetch gambar QR dari Google Charts
        $context = stream_context_create(['http' => ['timeout' => 5]]);
        $imgData = @file_get_contents($chartUrl, false, $context);
    
        if ($imgData !== false) {
            return 'data:image/png;base64,' . base64_encode($imgData);
        }
    
        // ── Fallback akhir: placeholder kosong ────────────────────────
        // Jika semua cara gagal, kembalikan 1x1 pixel transparan
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
    }
    
    // Helper format rupiah
    private function formatRupiah(int|float $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    public function generateAuthenticityCertificate(Pembelian $pembelian): string
    {
        $phpWord = new PhpWord();
        $phpWord->getDocInfo()->setCreator('Museum System');
        $phpWord->getDocInfo()->setTitle('Sertifikat Keaslian Koleksi');

        $section = $phpWord->addSection();

        $section->addText('SERTIFIKAT KEASLIAN KOLEKSI MUSEUM', ['bold' => true, 'size' => 18], ['alignment' => 'center']);
        $section->addTextBreak(1);

        $section->addText('Nomor Sertifikat: CERT-' . str_pad($pembelian->id, 5, '0', STR_PAD_LEFT));
        $section->addText('Nomor Registrasi Koleksi: REG-' . str_pad($pembelian->painting->id, 5, '0', STR_PAD_LEFT));
        $section->addText('Tanggal Transaksi: ' . ($pembelian->completed_at?->format('d F Y') ?? now()->format('d F Y')));
        $section->addTextBreak(1);

        if ($pembelian->painting->image_path) {
            $imagePath = storage_path('app/public/' . $pembelian->painting->image_path);
            if (file_exists($imagePath)) {
                $section->addImage($imagePath, ['width' => 320, 'height' => 240, 'alignment' => 'center']);
                $section->addTextBreak(1);
            }
        }

        $section->addText('DATA KOLEKSI', ['bold' => true, 'size' => 14]);
        $section->addText('Judul: ' . $pembelian->painting->title);
        $section->addText('Pelukis: ' . $pembelian->painting->artist);
        $section->addText('Media: ' . ($pembelian->painting->media ?? '-'));
        $section->addText('Ukuran: ' . ($pembelian->painting->dimensions ?? '-'));
        $section->addText('Tahun: ' . ($pembelian->painting->year ?? '-'));
        $section->addTextBreak(1);

        $section->addText('PENERIMA', ['bold' => true, 'size' => 14]);
        $section->addText('Nama Pembeli: ' . $pembelian->nama_lengkap);
        $section->addText('Email: ' . $pembelian->email);
        $section->addText('Telepon: ' . $pembelian->nomor_hp);
        $section->addTextBreak(1);

        $section->addText('PERNYATAAN KEASLIAN', ['bold' => true, 'size' => 14]);
        $section->addText('Dengan ini museum menyatakan bahwa lukisan tersebut merupakan karya asli dan bagian dari koleksi resmi museum.');
        $section->addTextBreak(2);

        $section->addText('TANDA TANGAN', ['bold' => true, 'size' => 14]);
        $section->addTextBreak(1);
        $section->addText('Pengelola Museum,');
        $section->addTextBreak(4);
        $section->addText('(________________________)');
        $section->addText('Tanggal: ' . ($pembelian->completed_at?->format('d F Y') ?? now()->format('d F Y')));

        return $this->saveDocx($phpWord, 'certificate_documents', 'certificate_' . $pembelian->id);
    }


    // ─── Dokumen Serah Terima Pengembalian (tahap 23) ─────────────────

    public function generateReturnDocument(Penyewaan $penyewaan, SerahTerima $serahTerima): string
    {
        return $this->generateReturnDocumentPdf($penyewaan, $serahTerima);
    }

// ─── Dokumen Pengembalian (PDF) ──────────────────────────────────

    public function generateReturnDocumentPdf(Penyewaan $penyewaan, SerahTerima $serahTerima): string
    {
        $path = 'return_documents/' . $penyewaan->id . '/pengembalian-' . $serahTerima->document_number . '.pdf';
        
        // Hapus file lama jika ada
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // Generate PDF dari blade
        $pdf = Pdf::loadView('serah_terima.pengembalian', [
            'penyewaan' => $penyewaan,
            'serahTerima' => $serahTerima,
        ]);
        $pdf->setPaper('A4', 'portrait');

        $pdfContent = $pdf->output();
        Storage::disk('public')->put($path, $pdfContent);

        return $path;
    }

    // ─── Dokumen Pengembalian Awal untuk ditandatangani penyewa ──────────

    public function generateInitialReturnDocument(Penyewaan $penyewaan, SerahTerima $serahTerima): string
    {
        $phpWord = new PhpWord();
        $phpWord->getDocInfo()->setCreator('Museum System');
        $phpWord->getDocInfo()->setTitle('Dokumen Pengembalian Koleksi');

        $section = $phpWord->addSection();

        $section->addText('DOKUMEN PENGEMBALIAN KOLEKSI MUSEUM', ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $section->addTextBreak(1);

        $section->addText('Nomor Dokumen: RTN-' . $penyewaan->id . '-' . now()->format('YmdHis'));
        $section->addText('Nomor Penyewaan: SP-' . str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT));
        $section->addText('Tanggal Dibuat: ' . now()->format('d F Y'));
        $section->addTextBreak(1);

        $section->addText('DATA PENYEWA', ['bold' => true, 'size' => 14]);
        $section->addText('Nama: ' . ($penyewaan->contact_name ?? $penyewaan->nama_instansi));
        $section->addText('Email: ' . $penyewaan->contact_email);
        $section->addText('Telepon: ' . $penyewaan->contact_phone);
        $section->addTextBreak(1);

        $section->addText('DATA KOLEKSI LUKISAN', ['bold' => true, 'size' => 14]);
        $section->addText('Judul: ' . $penyewaan->painting->title);
        $section->addText('Pelukis: ' . $penyewaan->painting->artist);
        $section->addText('Periode Sewa: ' . $penyewaan->start_date->format('d M Y') . ' s/d ' . $penyewaan->end_date->format('d M Y'));
        $section->addTextBreak(1);

        $section->addText('KONDISI AWAL SAAT DIPINJAM (Referensi)', ['bold' => true, 'size' => 14]);
        $section->addText('Frame aman: ' . ($serahTerima->checklist_frame_safe ? 'Ya' : 'Tidak'));
        $section->addText('Tidak ada sobekan: ' . ($serahTerima->checklist_no_tears ? 'Ya' : 'Tidak'));
        $section->addText('Warna normal: ' . ($serahTerima->checklist_color_normal ? 'Ya' : 'Tidak'));
        $section->addText('Kaca aman: ' . ($serahTerima->checklist_glass_safe ? 'Ya' : 'Tidak'));
        $section->addText('Tidak ada jamur: ' . ($serahTerima->checklist_no_mold ? 'Ya' : 'Tidak'));
        $section->addText('Sesuai dokumentasi: ' . ($serahTerima->checklist_matches_documentation ? 'Ya' : 'Tidak'));
        $section->addTextBreak(1);

        $section->addText('INFO PENGIRIMAN BALIK', ['bold' => true, 'size' => 14]);
        $section->addText('Metode: ' . ($serahTerima->return_shipment_method ?? '-'));
        $section->addText('Pengirim: ' . ($serahTerima->return_shipment_officer ?? '-'));
        $section->addText('No. Resi: ' . ($serahTerima->return_shipment_tracking ?? '-'));
        $section->addText('Tanggal Kirim: ' . ($serahTerima->return_shipped_at?->format('d M Y H:i') ?? '-'));
        $section->addText('Tanggal Diterima Museum: ' . ($serahTerima->collection_returned_at?->format('d M Y H:i') ?? '-'));
        $section->addTextBreak(1);

        $section->addText('PERNYATAAN PENGEMBALIAN', ['bold' => true, 'size' => 14]);
        $section->addText('Dengan ini saya menyatakan telah mengembalikan koleksi lukisan kepada Museum dalam kondisi baik.');
        $section->addText('Saya bertanggung jawab atas segala kerusakan yang terjadi selama masa penyewaan.');
        $section->addTextBreak(2);

        $section->addText('TANDA TANGAN', ['bold' => true, 'size' => 14]);
        $section->addTextBreak(1);
        $section->addText('Penyewa,                                          Pengelola Museum,');
        $section->addTextBreak(3);
        $section->addText('(________________________)                         (________________________)');
        $section->addText('Tanggal: _______________                           Tanggal: _______________');

        return $this->saveDocx($phpWord, 'return_documents', 'initial_return_' . $penyewaan->id);
    }

    // ─── Dokumen Invoice Kerusakan ─────────────────────────────────
 
    public function generateDamageInvoiceDocument(
        \App\Models\Penyewaan $penyewaan,
        \App\Models\DamageInvoice $invoice
    ): string {
        $phpWord = new PhpWord();
        $phpWord->getDocInfo()->setCreator('Museum System');
        $phpWord->getDocInfo()->setTitle('Invoice Kerusakan Koleksi');
 
        $section = $phpWord->addSection();
 
        // Header
        $section->addText(
            'INVOICE TAGIHAN KERUSAKAN KOLEKSI MUSEUM',
            ['bold' => true, 'size' => 16],
            ['alignment' => 'center']
        );
        $section->addTextBreak(1);
 
        // Nomor & tanggal
        $section->addText('Nomor Invoice   : ' . $invoice->invoice_number);
        $section->addText('Nomor Penyewaan : SP-' . str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT));
        $section->addText('Tanggal Dibuat  : ' . now()->format('d F Y'));
        $section->addTextBreak(1);
 
        // Data penyewa
        $section->addText('DATA PENYEWA', ['bold' => true, 'size' => 13]);
        $section->addText('Nama    : ' . ($penyewaan->contact_name ?? $penyewaan->nama_instansi));
        $section->addText('Email   : ' . $penyewaan->contact_email);
        $section->addText('Telepon : ' . $penyewaan->contact_phone);
        $section->addTextBreak(1);
 
        // Data koleksi
        $section->addText('DATA KOLEKSI', ['bold' => true, 'size' => 13]);
        $section->addText('Judul    : ' . $penyewaan->painting->title);
        $section->addText('Pelukis  : ' . $penyewaan->painting->artist);
        $section->addText('Periode  : ' . $penyewaan->start_date->format('d M Y')
            . ' s/d ' . $penyewaan->end_date->format('d M Y'));
        $section->addTextBreak(1);
 
        // Detail kerusakan
        $section->addText('DETAIL KERUSAKAN', ['bold' => true, 'size' => 13]);
        $section->addText('Jenis Kerusakan    : ' . $invoice->damage_type);
        $section->addText('Tingkat Kerusakan  : ' . ucfirst($invoice->damage_level));
        if ($invoice->damage_notes) {
            $section->addText('Keterangan         : ' . $invoice->damage_notes);
        }
        $section->addTextBreak(1);
 
        // Rincian biaya
        $section->addText('RINCIAN TAGIHAN', ['bold' => true, 'size' => 13]);
        $section->addText('Total Biaya Restorasi  : Rp '
            . number_format($invoice->restoration_cost, 0, ',', '.'));
        $section->addText('Deposit Penyewa (Hangus): Rp '
            . number_format($invoice->deposit_used, 0, ',', '.'));
        $section->addText(
            'TAGIHAN YANG HARUS DIBAYAR: Rp ' . number_format($invoice->additional_charge, 0, ',', '.'),
            ['bold' => true, 'size' => 12]
        );
        $section->addTextBreak(1);
 
        // Instruksi pembayaran
        $section->addText('CARA PEMBAYARAN', ['bold' => true, 'size' => 13]);
        $section->addText('Silakan lakukan pembayaran melalui sistem online penyewaan museum.');
        $section->addText('Login ke akun Anda → Detail Penyewaan → Bayar Tagihan Kerusakan.');
        $section->addText('Order ID: ' . $invoice->order_id);
        $section->addTextBreak(1);
 
        // Keterangan hukum
        $section->addText('KETERANGAN', ['bold' => true, 'size' => 13]);
        $section->addText('1. Invoice ini diterbitkan berdasarkan hasil pemeriksaan akhir koleksi oleh pengelola museum.');
        $section->addText('2. Deposit penyewa telah hangus seluruhnya untuk menutup sebagian biaya kerusakan.');
        $section->addText('3. Tagihan harus dilunasi dalam waktu 7 (tujuh) hari kerja sejak invoice diterbitkan.');
        $section->addText('4. Keterlambatan pembayaran dapat dikenakan sanksi sesuai perjanjian penyewaan.');
        $section->addTextBreak(2);
 
        // Tanda tangan
        $section->addText('Diterbitkan oleh,');
        $section->addTextBreak(3);
        $section->addText('(________________________)');
        $section->addText('Pengelola Museum');
        $section->addText('Tanggal: ' . now()->format('d F Y'));
 
        return $this->saveDocx($phpWord, 'damage_invoices', 'damage_invoice_' . $penyewaan->id);
    }

    // ─── Helper ───────────────────────────────────────────────────────

    private function saveDocx(PhpWord $phpWord, string $folder, string $prefix): string
    {
        $fileName = $prefix . '_' . now()->format('Ymd_His') . '.docx';
        $path     = $folder . '/' . $fileName;

        $tempFile = tempnam(sys_get_temp_dir(), 'museum_doc');
        $writer   = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        Storage::disk('public')->put($path, file_get_contents($tempFile));
        unlink($tempFile);

        if (! $this->isValidWordDocument($path)) {
            throw new \RuntimeException('Generated DOCX failed validation: ' . $path);
        }

        return $path;
    }

    public function isValidWordDocument(string $storagePath): bool
    {
        $absolute = storage_path('app/public/' . $storagePath);
        if (! file_exists($absolute)) {
            return false;
        }

        $zip = new \ZipArchive();
        if ($zip->open($absolute) !== true) {
            return false;
        }

        $requiredEntries = [
            '[Content_Types].xml',
            '_rels/.rels',
            'word/document.xml',
        ];

        foreach ($requiredEntries as $entry) {
            if ($zip->locateName($entry) === false) {
                $zip->close();
                return false;
            }
        }

        $zip->close();
        return true;
    }

    /**
     * Convert an uploaded Word document to PDF and store it on the public disk.
     * Accepts storage-relative path (public disk) and returns the new pdf path.
     */
    public function convertToPdf(string $storagePath): string
    {
        // If already PDF, return original
        if (str_ends_with(strtolower($storagePath), '.pdf')) {
            return $storagePath;
        }

        $absolute = storage_path('app/public/' . $storagePath);
        if (! file_exists($absolute)) {
            throw new \RuntimeException('Source file not found for conversion: ' . $absolute);
        }

        // Prepare temp files
        $tempDoc = tempnam(sys_get_temp_dir(), 'museum_doc_src');
        $tempPdf = tempnam(sys_get_temp_dir(), 'museum_doc_pdf') . '.pdf';

        copy($absolute, $tempDoc);

        // Configure PDF renderer to DomPDF (vendor/dompdf)
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));

        $phpWord = IOFactory::load($tempDoc);
        $xmlWriter = IOFactory::createWriter($phpWord, 'PDF');
        $xmlWriter->save($tempPdf);

        // Store to public disk
        $folder = 'pembelian/serah-terima/signed_pdf';
        $fileName = pathinfo($storagePath, PATHINFO_FILENAME) . '_' . now()->format('Ymd_His') . '.pdf';
        $destPath = $folder . '/' . $fileName;

        \Illuminate\Support\Facades\Storage::disk('public')->put($destPath, file_get_contents($tempPdf));

        @unlink($tempDoc);
        @unlink($tempPdf);

        return $destPath;
    }
}