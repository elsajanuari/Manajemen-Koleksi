<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Dokumen Serah Terima Penyewaan Koleksi</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 11px;
    color: #1a1a1a;
    background: #fff;
}

.page {
    padding: 28px 40px 28px 40px;
}

/* ── HEADER ── */
.header {
    display: table;
    width: 100%;
    border-bottom: 3px solid #1a5c2e;
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.header-left  { display: table-cell; vertical-align: middle; width: 58%; }
.header-right { display: table-cell; vertical-align: middle; text-align: right; }

.logo { height: 56px; width: auto; }

.museum-name {
    font-size: 13px;
    font-weight: bold;
    color: #1a5c2e;
    margin-top: 5px;
    letter-spacing: 0.3px;
}
.museum-address {
    font-size: 9px;
    color: #555;
    line-height: 1.6;
    margin-top: 2px;
}

.doc-label {
    font-size: 22px;
    font-weight: bold;
    letter-spacing: 2px;
    color: #1a1a1a;
    text-transform: uppercase;
}
.doc-sub {
    font-size: 9.5px;
    color: #666;
    line-height: 1.9;
    margin-top: 4px;
}
.doc-sub span {
    font-weight: bold;
    color: #1a1a1a;
}

/* ── SECTION LABEL ── */
.section-label {
    font-size: 8.5px;
    font-weight: bold;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #fff;
    background: #1a5c2e;
    padding: 3px 10px;
    margin-bottom: 8px;
    margin-top: 14px;
}

/* ── GRID 2 KOLOM ── */
.two-col { display: table; width: 100%; margin-bottom: 14px; }
.col-left  { display: table-cell; vertical-align: top; width: 50%; padding-right: 14px; }
.col-right { display: table-cell; vertical-align: top; width: 50%; padding-left: 14px; }

.info-table { width: 100%; border-collapse: collapse; }
.info-table td {
    padding: 2px 0;
    font-size: 10px;
    vertical-align: top;
}
.info-table .lbl {
    color: #666;
    width: 130px;
    padding-right: 6px;
}
.info-table .val { color: #1a1a1a; font-weight: bold; }
.info-table .val-normal { color: #1a1a1a; }

/* ── SHIPPING TABLE ── */
.shipping-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
.shipping-table td {
    padding: 5px 8px;
    font-size: 10px;
    vertical-align: top;
}
.shipping-table tr:nth-child(odd) { background: #f6fbf8; }
.shipping-lbl {
    font-size: 8.5px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #888;
    margin-bottom: 2px;
}
.shipping-val {
    font-size: 10px;
    color: #1a1a1a;
    font-weight: bold;
}

/* ── NOTES ── */
.note-box {
    padding: 7px 12px;
    border-left: 3px solid #1a5c2e;
    background: #f6fbf8;
    font-size: 9.5px;
    color: #374151;
    line-height: 1.6;
    margin-bottom: 7px;
}
.note-title { font-weight: bold; margin-bottom: 2px; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #1a5c2e; }

/* ── TANDA TANGAN ── */
.signature-wrap {
    display: table;
    width: 100%;
    margin-top: 20px;
    border-top: 1px solid #e0e0e0;
    padding-top: 16px;
}
.sig-cell {
    display: table-cell;
    width: 50%;
    vertical-align: top;
    padding: 0 16px;
    text-align: center;
}
.sig-cell:first-child { padding-left: 0; }
.sig-cell:last-child  { padding-right: 0; }

.sig-party {
    font-size: 8.5px;
    font-weight: bold;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #fff;
    background: #1a5c2e;
    padding: 3px 10px;
    margin-bottom: 8px;
    display: inline-block;
    width: 100%;
}
.sig-space { height: 52px; }
.sig-line {
    border-top: 1px solid #333;
    padding-top: 5px;
    margin-top: 2px;
}
.sig-name { font-size: 10.5px; font-weight: bold; }
.sig-role { font-size: 9px; color: #666; margin-top: 1px; }
.sig-img { height: 65px; width: auto; margin-bottom: 4px; }
.sig-box {
    border: 1px dashed #aaa;
    height: 70px;
    margin-bottom: 4px;
    display: block;
    background: #fafafa;
}
.sig-note { font-size: 8.5px; color: #888; font-style: italic; margin-top: 3px; }

/* ── FOOTER ── */
.footer {
    margin-top: 14px;
    border-top: 1px solid #e5e5e5;
    padding-top: 8px;
    text-align: center;
    font-size: 8.5px;
    color: #aaa;
    line-height: 1.7;
}

/* ── PAGE BREAK ── */
.page-break {
    page-break-before: always;
    padding-top: 20px;
}
</style>
</head>
<body>

@php
    $logoPath   = public_path('images/logo_museum_mk_lesmana.png');
    $logoBase64 = file_exists($logoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : null;

    /* ── TTD Pengelola dari public/images ── */
    $ttdPath   = public_path('images/ttd_pengelola.png');
    $ttdBase64 = file_exists($ttdPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPath))
        : null;

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
        return $dt->format('d') . ' ' . $namaBulan[(int)$dt->format('n')] . ' ' . $dt->format('Y, H:i');
    };

    $isInstansi = ($penyewaan->rental_type ?? '') === 'instansi';
    $namaPenyewa = $isInstansi ? ($penyewaan->nama_instansi ?? '-') : ($penyewaan->contact_name ?? '-');
    $alamatPenyewa = $isInstansi
        ? ($penyewaan->alamat_instansi ?? '-')
        : ($penyewaan->alamat_domisili ?? $penyewaan->alamat_lengkap ?? '-');

    $docNumber = $serahTerimaStub->document_number ?? 'ST-' . str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT);
@endphp

{{-- ══════════════════════════════════════════
     HALAMAN 1
══════════════════════════════════════════ --}}
<div class="page">

    {{-- HEADER --}}
    <div class="header">
        <div class="header-left">
            @if($logoBase64)
                <img class="logo" src="{{ $logoBase64 }}" alt="Logo Museum MK Lesmana">
            @else
                <div style="height:56px;width:56px;background:#1a5c2e;border-radius:4px;display:flex;align-items:center;justify-content:center;">
                    <span style="color:#fff;font-size:8px;font-weight:bold;">MKL</span>
                </div>
            @endif
            <div class="museum-name">Museum MK. Lesmana</div>
            <div class="museum-address">
                Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes<br>
                Kabupaten Purwakarta, Jawa Barat 41175
            </div>
        </div>
        <div class="header-right">
            <div class="doc-label">Serah Terima Sewa</div>
            <div class="doc-sub">
                Nomor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span>{{ $docNumber }}</span><br>
                Tanggal &nbsp;&nbsp;&nbsp;: <span>{{ $fmtTgl(now()) }}</span><br>
                Ref. Sewa &nbsp;: <span>SP-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>

    {{-- IDENTITAS PARA PIHAK --}}
    <div class="two-col">

        {{-- Pihak Pertama: Museum --}}
        <div class="col-left">
            <div class="section-label" style="margin-top:0">Pihak Pertama — Penyewa</div>
            <table class="info-table">
                <tr><td class="lbl">Nama Lembaga</td><td class="val">Museum MK. Lesmana</td></tr>
                <tr><td class="lbl">Diwakili oleh</td><td class="val-normal">Pengelola Museum</td></tr>
                <tr><td class="lbl">Alamat</td><td class="val-normal">Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175</td></tr>
                <tr><td class="lbl">Telepon</td><td class="val-normal">(0264) 1234567</td></tr>
            </table>
        </div>

        {{-- Pihak Kedua: Penyewa --}}
        <div class="col-right">
            <div class="section-label" style="margin-top:0">Pihak Kedua — Penyewa</div>
            @if($isInstansi)
                <table class="info-table">
                    <tr><td class="lbl">Nama Instansi</td><td class="val">{{ $penyewaan->nama_instansi ?? '-' }}</td></tr>
                    <tr><td class="lbl">Jenis Instansi</td><td class="val-normal">{{ $penyewaan->jenis_instansi ?? '-' }}</td></tr>
                    <tr><td class="lbl">NPWP</td><td class="val-normal">{{ $penyewaan->npwp_instansi ?? '-' }}</td></tr>
                    <tr><td class="lbl">PIC / Perwakilan</td><td class="val">{{ $penyewaan->nama_pic ?? $penyewaan->contact_name ?? '-' }}</td></tr>
                    <tr><td class="lbl">Jabatan PIC</td><td class="val-normal">{{ $penyewaan->jabatan_pic ?? '-' }}</td></tr>
                    <tr><td class="lbl">NIK PIC</td><td class="val-normal">{{ $penyewaan->nik_pic ?? '-' }}</td></tr>
                    <tr><td class="lbl">Nomor HP</td><td class="val-normal">{{ $penyewaan->hp_pic ?? $penyewaan->contact_phone ?? '-' }}</td></tr>
                    <tr><td class="lbl">Email</td><td class="val-normal">{{ $penyewaan->email_pic ?? $penyewaan->contact_email ?? '-' }}</td></tr>
                </table>
            @else
                <table class="info-table">
                    <tr><td class="lbl">Nama Lengkap</td><td class="val">{{ $penyewaan->contact_name ?? '-' }}</td></tr>
                    <tr><td class="lbl">NIK</td><td class="val-normal">{{ $penyewaan->nik ?? '-' }}</td></tr>
                    <tr><td class="lbl">Tempat, Tgl. Lahir</td><td class="val-normal">{{ $penyewaan->tempat_lahir ?? '-' }}{{ $penyewaan->tanggal_lahir ? ', ' . $fmtTgl($penyewaan->tanggal_lahir) : '' }}</td></tr>
                    <tr><td class="lbl">Pekerjaan</td><td class="val-normal">{{ $penyewaan->pekerjaan ?? '-' }}</td></tr>
                    <tr><td class="lbl">Nomor HP</td><td class="val-normal">{{ $penyewaan->contact_phone ?? '-' }}</td></tr>
                    <tr><td class="lbl">Email</td><td class="val-normal">{{ $penyewaan->contact_email ?? '-' }}</td></tr>
                </table>
            @endif
        </div>

    </div>

    {{-- RINCIAN KOLEKSI --}}
    <div class="section-label">Rincian Koleksi yang Disewakan</div>
    <div style="margin-top:6px;">
        <table style="width:100%; border-collapse:collapse;">
            <tr style="background:#1a5c2e; color:#fff;">
                <th style="padding:6px 8px; font-size:9px; text-align:left; width:26px;">No</th>
                <th style="padding:6px 8px; font-size:9px; text-align:left;">Deskripsi Koleksi</th>
                <th style="padding:6px 8px; font-size:9px; text-align:center; width:38px;">Qty</th>
                <th style="padding:6px 8px; font-size:9px; text-align:right; width:120px;">Tarif/Hari (Rp)</th>
                <th style="padding:6px 8px; font-size:9px; text-align:right; width:130px;">Total Sewa (Rp)</th>
            </tr>
            <tr style="border-bottom:2px solid #1a5c2e;">
                <td style="padding:8px; text-align:center; color:#aaa; font-size:9px;">1</td>
                <td style="padding:8px;">
                    <strong style="font-size:11px;">{{ $penyewaan->painting->title ?? '-' }}</strong><br>
                    <span style="color:#555; font-size:8.5px;">
                        Seniman : {{ $penyewaan->painting->artist ?? '-' }}<br>
                        Tahun &nbsp;&nbsp;: {{ $penyewaan->painting->year_created ?? '-' }}<br>
                        Media &nbsp;&nbsp;: {{ $penyewaan->painting->media ?? '-' }}<br>
                        Dimensi : {{ $penyewaan->painting->dimensions ?? '-' }}<br>
                        Kategori : {{ $penyewaan->painting->category ?? '-' }}<br>
                        No. Koleksi : {{ $penyewaan->painting->collection_number ?? '-' }}
                    </span>
                </td>
                <td style="padding:8px; text-align:center;">1</td>
                <td style="padding:8px; text-align:right; font-weight:bold;">
                    Rp {{ number_format($penyewaan->painting->daily_rate ?? 0, 0, ',', '.') }}
                </td>
                <td style="padding:8px; text-align:right; font-weight:bold;">
                    Rp {{ number_format($penyewaan->subtotal_amount ?? 0, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <br>

    {{-- INFORMASI PENYEWAAN --}}
    <div class="section-label">Informasi Penyewaan</div>
    <table style="width:100%; border-collapse:collapse; margin-top:4px;">
        <tr style="background:#f6fbf8;">
            <td style="padding:5px 8px; width:20%;">
                <div class="shipping-lbl">Tanggal Mulai</div>
                <div class="shipping-val">{{ $fmtTgl($penyewaan->start_date) }}</div>
            </td>
            <td style="padding:5px 8px; width:20%;">
                <div class="shipping-lbl">Tanggal Selesai</div>
                <div class="shipping-val">{{ $fmtTgl($penyewaan->end_date) }}</div>
            </td>
            <td style="padding:5px 8px; width:18%;">
                <div class="shipping-lbl">Durasi</div>
                <div class="shipping-val">{{ $penyewaan->duration_days ?? '-' }} Hari</div>
            </td>
            <td style="padding:5px 8px; width:20%;">
                <div class="shipping-lbl">Deposit</div>
                <div class="shipping-val">Rp {{ number_format($penyewaan->deposit_amount ?? 0, 0, ',', '.') }}</div>
            </td>
            <td style="padding:5px 8px; width:22%;">
                <div class="shipping-lbl">Total Bayar</div>
                <div class="shipping-val">Rp {{ number_format($penyewaan->total_bayar ?? 0, 0, ',', '.') }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px 8px;">
                <div class="shipping-lbl">Tujuan Penyewaan</div>
                <div class="shipping-val">{{ $penyewaan->tujuan_penyewaan ?? '-' }}</div>
            </td>
            <td colspan="3" style="padding:5px 8px;">
                <div class="shipping-lbl">Lokasi Penempatan</div>
                <div class="shipping-val">{{ $penyewaan->alamat_lengkap ?? $alamatPenyewa }}</div>
            </td>
        </tr>
    </table>

    {{-- INFO PENGIRIMAN --}}
    <div class="section-label">Informasi Pengiriman</div>
    <table style="width:100%; border-collapse:collapse; margin-top:4px;">
        <tr style="background:#f6fbf8;">
            <td style="padding:5px 8px; width:16%;">
                <div class="shipping-lbl">Metode Pengiriman</div>
                <div class="shipping-val">{{ $serahTerimaStub->delivery_method ?? $penyewaan->shipping_method_label ?? '-' }}</div>
            </td>
            <td style="padding:5px 8px; width:16%;">
                <div class="shipping-lbl">Petugas Pengiriman</div>
                <div class="shipping-val">{{ $serahTerimaStub->delivery_officer ?? '-' }}</div>
            </td>
            <td style="padding:5px 8px; width:18%;">
                <div class="shipping-lbl">No. Resi</div>
                <div class="shipping-val">{{ $serahTerimaStub->delivery_tracking_number ?? '-' }}</div>
            </td>
            </td>
            <td style="padding:5px 8px; width:16%;">
                <div class="shipping-lbl">Ongkos Kirim</div>
                <div class="shipping-val" style="color:{{ (int)($penyewaan->shipping_cost ?? 0) === 0 ? '#059669' : '#1a1a1a' }};">
                    @if((int)($penyewaan->shipping_cost ?? 0) === 0)
                        Gratis
                    @else
                        Rp {{ number_format($penyewaan->shipping_cost, 0, ',', '.') }}
                    @endif
                </div>
            </td>
            <td style="padding:5px 8px; width:18%;">
                <div class="shipping-lbl">Tanggal Dikirim</div>
                <div class="shipping-val">{{ $fmtTglJam($serahTerimaStub->shipped_at) }}</div>
            </td>
            <td style="padding:5px 8px; width:18%;">
                <div class="shipping-lbl">Tanggal Diterima</div>
                <div class="shipping-val">{{ $fmtTglJam($serahTerimaStub->confirmed_received_at) }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding:5px 8px;">
                <div class="shipping-lbl">Alamat Pengiriman</div>
                <div class="shipping-val">{{ $serahTerimaStub->delivery_location ?? $alamatPenyewa }}</div>
            </td>
            <td colspan="3" style="padding:5px 8px;">
                <div class="shipping-lbl">Nama Penerima</div>
                <div class="shipping-val">{{ $serahTerimaStub->recipient_name ?? $namaPenyewa }}</div>
            </td>
        </tr>
    </table>

    {{-- FOOTER halaman 1 --}}
    <div class="footer">
        Museum MK. Lesmana &bull; Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175<br>
        {{ $docNumber }} &bull; Ref. Sewa: SP-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }} &bull; Dicetak: {{ $fmtTglJam(now()) }} WIB
    </div>

</div>{{-- end page 1 --}}


{{-- ══════════════════════════════════════════
     HALAMAN 2: PERNYATAAN + TANDA TANGAN
══════════════════════════════════════════ --}}
<div class="page-break">

    {{-- Header ringkas halaman 2 --}}
    <div style="text-align:center; margin-bottom:16px;">
    </div>

    {{-- PERNYATAAN DAN KLAUSUL --}}
    <div class="section-label">Pernyataan dan Klausul</div>

    {{-- WRAPPER untuk klausul dengan padding kiri-kanan --}}
    <div style="padding:0 10px;">

        <div class="note-box">
            <div class="note-title">Pernyataan Serah Terima</div>
            Dengan ditandatanganinya dokumen ini, Pihak Pertama menyatakan telah menyerahkan koleksi lukisan sebagaimana tercantum di atas kepada Pihak Kedua untuk disewakan, dan Pihak Kedua menyatakan telah menerima koleksi tersebut dalam kondisi yang baik dan sesuai dengan dokumentasi yang tersimpan di sistem Museum MK. Lesmana.
        </div>

        <div class="note-box">
            <div class="note-title">Tanggung Jawab Selama Penyewaan</div>
            Selama masa penyewaan, Pihak Kedua bertanggung jawab penuh atas keamanan dan perawatan koleksi. Segala bentuk kerusakan, kehilangan, atau perubahan pada koleksi menjadi tanggung jawab Pihak Kedua dan akan dikenakan sanksi sesuai dengan ketentuan yang telah disepakati dalam Surat Perjanjian Sewa.
        </div>

        <div class="note-box">
            <div class="note-title">Hak Cipta dan Moral</div>
            Hak cipta dan hak moral atas karya lukisan tetap menjadi milik seniman sesuai ketentuan Undang-Undang No. 28 Tahun 2014 tentang Hak Cipta. Penyewaan fisik koleksi tidak memberikan hak kepada Pihak Kedua untuk menggandakan, memodifikasi, atau menggunakan citra koleksi untuk kepentingan komersial tanpa izin tertulis dari pemilik hak cipta.
        </div>

        <div class="note-box">
            <div class="note-title">Pengembalian Koleksi</div>
            Pihak Kedua wajib mengembalikan koleksi kepada Pihak Pertama dalam kondisi yang sama seperti saat diterima, pada atau sebelum tanggal berakhirnya masa sewa. Pihak Pertama berhak melakukan pemeriksaan kondisi koleksi saat pengembalian dan menetapkan sanksi jika ditemukan kerusakan di luar kewajaran.
        </div>

        <div class="note-box">
            <div class="note-title">Kekuatan Hukum</div>
            Dokumen Berita Acara Serah Terima ini dibuat dalam dua rangkap asli, masing-masing bermaterai cukup (Materai Rp10.000), dan memiliki kekuatan hukum yang sama bagi kedua pihak. Dokumen ini berlaku sebagai bukti sah penyerahan koleksi untuk disewakan.
        </div>

    </div>{{-- end wrapper --}}

    {{-- TANDA TANGAN --}}
    <div style="margin-top:20px; padding:0 10px; border-top:1px solid #e0e0e0; padding-top:16px;">

        <div style="display:table; width:100%;">

            {{-- Pihak Pertama --}}
            <div style="display:table-cell; width:50%; vertical-align:top; padding:0 16px; text-align:center;">
                <div style="font-size:8.5px; font-weight:bold; letter-spacing:1px; text-transform:uppercase; color:#fff; background:#1a5c2e; padding:3px 10px; margin-bottom:8px; display:inline-block; width:100%;">Pihak Pertama</div>
                <div style="height:40px;"></div>
                @if($ttdBase64)
                    <img src="{{ $ttdBase64 }}" alt="TTD Pengelola" style="height:65px; width:auto; margin-bottom:4px;">
                @else
                    <div style="border:1px dashed #aaa; height:70px; margin-bottom:4px; display:block; background:#fafafa;"></div>
                    <div style="font-size:8.5px; color:#888; font-style:italic; margin-top:3px;">(tanda tangan pengelola)</div>
                @endif
                <div style="border-top:1px solid #333; padding-top:5px; margin-top:2px;">
                    <div style="font-size:10.5px; font-weight:bold;">Pengelola Museum</div>
                    <div style="font-size:9px; color:#666; margin-top:1px;">Museum MK. Lesmana</div>
                </div>
            </div>

            {{-- Pihak Kedua --}}
            <div style="display:table-cell; width:50%; vertical-align:top; padding:0 16px; text-align:center;">
                <div style="font-size:8.5px; font-weight:bold; letter-spacing:1px; text-transform:uppercase; color:#fff; background:#1a5c2e; padding:3px 10px; margin-bottom:8px; display:inline-block; width:100%;">Pihak Kedua</div>
                <div style="height:40px;"></div>
                <div style="border:1px dashed #aaa; height:70px; margin-bottom:4px; display:block; background:#fafafa;"></div>
                <div style="font-size:8.5px; color:#888; font-style:italic; margin-top:3px;">(Tanda tangan &amp; Materai 10.000)</div>
                <div style="border-top:1px solid #333; padding-top:5px; margin-top:2px;">
                    @if($isInstansi)
                        <div style="font-size:10.5px; font-weight:bold;">{{ $penyewaan->nama_pic ?? '-' }}</div>
                        <div style="font-size:9px; color:#666; margin-top:1px;">PIC &mdash; {{ $penyewaan->nama_instansi ?? '-' }}</div>
                    @else
                        <div style="font-size:10.5px; font-weight:bold;">{{ $penyewaan->contact_name ?? '-' }}</div>
                        <div style="font-size:9px; color:#666; margin-top:1px;">Penyewa Perorangan</div>
                    @endif
                </div>
            </div>

        </div>{{-- end table --}}

    </div>{{-- end tanda tangan wrapper --}}

    {{-- FOOTER halaman 2 --}}
    <div class="footer" style="margin-top:20px;">
        Museum MK. Lesmana &bull; Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175<br>
        {{ $docNumber }} &bull; Ref. Sewa: SP-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }} &bull; Dicetak: {{ $fmtTglJam(now()) }} WIB
    </div>

</div>{{-- end page 2 --}}