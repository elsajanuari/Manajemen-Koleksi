<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Dokumen Pengembalian Koleksi</title>
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
    $isCancelled = $serahTerima->arrival_damage_manager_decision === 'setuju_batal';

    // Cek apakah ada kerusakan
    $damageItems = $serahTerima->damage_items_detail ?? [];
    $hasDamage = $serahTerima->has_damage ?? false;
    $damageCost = $serahTerima->final_damage_cost ?? $serahTerima->damage_cost ?? 0;
    $damageNotes = $serahTerima->damage_notes ?? $serahTerima->return_condition_notes ?? '-';

    $docNumber = $serahTerima->document_number ?? 'RTN-' . str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT);
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
            <div class="doc-label">Pengembalian Koleksi</div>
            <div class="doc-sub">
                Nomor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span>{{ $docNumber }}</span><br>
                Tanggal &nbsp;&nbsp;&nbsp;: <span>{{ $fmtTgl(now()) }}</span><br>
                Ref. Sewa &nbsp;: <span>SP-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>
    </div>

    {{-- DATA PENYEWA --}}
    <div class="two-col">
        <div class="col-left">
            <div class="section-label" style="margin-top:0">Data Penyewa</div>
            @if($isInstansi)
                <table class="info-table">
                    <tr><td class="lbl">Nama Instansi</td><td class="val">{{ $penyewaan->nama_instansi ?? '-' }}</td></tr>
                    <tr><td class="lbl">PIC / Perwakilan</td><td class="val">{{ $penyewaan->nama_pic ?? $penyewaan->contact_name ?? '-' }}</td></tr>
                    <tr><td class="lbl">Jabatan PIC</td><td class="val-normal">{{ $penyewaan->jabatan_pic ?? '-' }}</td></tr>
                    <tr><td class="lbl">Nomor HP</td><td class="val-normal">{{ $penyewaan->hp_pic ?? $penyewaan->contact_phone ?? '-' }}</td></tr>
                    <tr><td class="lbl">Email</td><td class="val-normal">{{ $penyewaan->email_pic ?? $penyewaan->contact_email ?? '-' }}</td></tr>
                </table>
            @else
                <table class="info-table">
                    <tr><td class="lbl">Nama Lengkap</td><td class="val">{{ $penyewaan->contact_name ?? '-' }}</td></tr>
                    <tr><td class="lbl">NIK</td><td class="val-normal">{{ $penyewaan->nik ?? '-' }}</td></tr>
                    <tr><td class="lbl">Nomor HP</td><td class="val-normal">{{ $penyewaan->contact_phone ?? '-' }}</td></tr>
                    <tr><td class="lbl">Email</td><td class="val-normal">{{ $penyewaan->contact_email ?? '-' }}</td></tr>
                </table>
            @endif
        </div>
        <div class="col-right">
            <div class="section-label" style="margin-top:0">Data Koleksi</div>
            <table class="info-table">
                <tr><td class="lbl">Nama Koleksi</td><td class="val">{{ $penyewaan->painting->title ?? '-' }}</td></tr>
                <tr><td class="lbl">Seniman</td><td class="val-normal">{{ $penyewaan->painting->artist ?? '-' }}</td></tr>
                <tr><td class="lbl">Periode Sewa</td><td class="val-normal">{{ $fmtTgl($penyewaan->start_date) }} s/d {{ $fmtTgl($penyewaan->end_date) }}</td></tr>
                <tr><td class="lbl">Durasi</td><td class="val-normal">{{ $penyewaan->duration_days ?? '-' }} Hari</td></tr>
                <tr><td class="lbl">Lokasi Penempatan</td><td class="val-normal">{{ $penyewaan->alamat_lengkap ?? '-' }}</td></tr>
            </table>
        </div>
    </div>

    {{-- INFORMASI PENGIRIMAN BALIK --}}
    <div class="section-label">Informasi Pengiriman Balik</div>
    <table style="width:100%; border-collapse:collapse; margin-top:4px;">
        <tr style="background:#f6fbf8;">
            <td style="padding:5px 8px; width:20%;">
                <div class="shipping-lbl">Metode Pengiriman</div>
                <div class="shipping-val">{{ $serahTerima->return_shipment_method ?? '-' }}</div>
            </td>
            <td style="padding:5px 8px; width:20%;">
                <div class="shipping-lbl">Pengirim</div>
                <div class="shipping-val">{{ $serahTerima->return_shipment_officer ?? '-' }}</div>
            </td>
            <td style="padding:5px 8px; width:20%;">
                <div class="shipping-lbl">No. Resi</div>
                <div class="shipping-val" style="font-family:monospace;">{{ $serahTerima->return_shipment_tracking ?? '-' }}</div>
            </td>
            <td style="padding:5px 8px; width:20%;">
                <div class="shipping-lbl">Rencana Kirim</div>
                <div class="shipping-val">{{ $fmtTglJam($serahTerima->return_shipment_scheduled_at) }}</div>
            </td>
            <td style="padding:5px 8px; width:20%;">
                <div class="shipping-lbl">Tiba di Museum</div>
                <div class="shipping-val">{{ $fmtTglJam($serahTerima->collection_arrived_at) }}</div>
            </td>
        </tr>
    </table>

    {{-- HASIL PEMERIKSAAN --}}
    <div class="section-label">Hasil Pemeriksaan Kondisi</div>
    <div style="margin-top:4px;">

        @if(!$hasDamage)
            {{-- Tidak ada kerusakan --}}
            <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:0.75rem;padding:12px 16px;">
                <div style="font-size:11px;font-weight:bold;color:#166534;">✅ Tidak Ada Kerusakan</div>
                <div style="font-size:10px;color:#4b7a5a;margin-top:4px;">
                    Koleksi dikembalikan dalam kondisi baik sesuai saat diserahterimakan.
                </div>
            </div>
        @elseif(!empty($damageItems))
            {{-- Ada kerusakan detail --}}
            <div style="margin-bottom:8px;">
                <div style="font-size:10px;font-weight:bold;color:#991b1b;margin-bottom:6px;">Kerusakan yang Ditemukan:</div>
                <table style="width:100%;border-collapse:collapse;font-size:10px;">
                    <tr style="background:#fef2f2;">
                        <th style="padding:5px 8px;text-align:left;border:1px solid #ddd;">Jenis Kerusakan</th>
                        <th style="padding:5px 8px;text-align:center;border:1px solid #ddd;">Tingkat</th>
                        <th style="padding:5px 8px;text-align:right;border:1px solid #ddd;">Biaya</th>
                    </tr>
                    @foreach($damageItems as $item)
                        <tr>
                            <td style="padding:5px 8px;border:1px solid #ddd;">{{ $item['label'] ?? $item['key'] ?? '-' }}</td>
                            <td style="padding:5px 8px;text-align:center;border:1px solid #ddd;">
                                <span style="font-weight:bold;color:#d97706;">{{ strtoupper($item['level'] ?? 'ringan') }}</span>
                            </td>
                            <td style="padding:5px 8px;text-align:right;border:1px solid #ddd;font-weight:bold;">
                                Rp {{ number_format($item['cost'] ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="background:#0b1d35;color:#fff;">
                        <td colspan="2" style="padding:6px 8px;border:1px solid #0b1d35;font-weight:bold;text-align:right;">
                            TOTAL BIAYA KERUSAKAN
                        </td>
                        <td style="padding:6px 8px;border:1px solid #0b1d35;text-align:right;font-weight:bold;color:#fff;">
                            Rp {{ number_format($damageCost, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>
            @if($damageNotes && $damageNotes !== '-')
                <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:0.75rem;padding:8px 12px;margin-top:6px;">
                    <div style="font-size:9px;font-weight:bold;color:#64748b;">Catatan Kerusakan</div>
                    <div style="font-size:10px;color:#334155;">{{ $damageNotes }}</div>
                </div>
            @endif
        @else
            {{-- Fallback --}}
            <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:0.75rem;padding:12px 16px;">
                <div style="font-size:11px;font-weight:bold;color:#991b1b;">⚠ Kerusakan Ditemukan</div>
                <div style="font-size:10px;color:#7f1d1d;margin-top:4px;">
                    Biaya kerusakan: Rp {{ number_format($damageCost, 0, ',', '.') }}
                </div>
            </div>
        @endif

        {{-- Periksa oleh --}}
        <div style="margin-top:8px;font-size:9px;color:#94a3b8;text-align:right;">
            Diperiksa oleh {{ $serahTerima->final_inspection_by ?? 'Pengelola Museum' }}
            {{ $serahTerima->final_inspection_at ? ' · ' . $fmtTglJam($serahTerima->final_inspection_at) : '' }}
        </div>
    </div>

</div>{{-- end page 1 --}}


{{-- ══════════════════════════════════════════
     HALAMAN 2: TANDA TANGAN
══════════════════════════════════════════ --}}
<div class="page-break">

    {{-- Header ringkas halaman 2 --}}
    <div style="text-align:center; margin-bottom:16px;">
    </div>

    <div class="section-label">Pernyataan Pengembalian Koleksi</div>

    <div style="margin-top:4px; padding:0 10px;">
        @if($isCancelled)
            <div class="note-box">
                <div class="note-title">Pembatalan Sewa</div>
                Penyewaan ini dibatalkan karena ditemukan kerusakan pada koleksi saat proses pengiriman ke penyewa, sebelum koleksi sempat digunakan. Koleksi telah dikembalikan kepada Museum sebagaimana tercantum di atas.
            </div>
            <div class="note-box">
                <div class="note-title">Pengembalian Deposit</div>
                Sehubungan dengan pembatalan ini, deposit yang telah dibayarkan penyewa akan dikembalikan secara penuh (100%) ke rekening penyewa.
            </div>
            <div class="note-box">
                <div class="note-title">Kekuatan Hukum</div>
                Dokumen ini menjadi bukti sah bahwa koleksi telah diterima kembali oleh Museum dan proses pembatalan sewa telah diselesaikan.
            </div>
        @else
            <div class="note-box">
                <div class="note-title">Pernyataan Pengembalian</div>
                Dengan ini dinyatakan bahwa koleksi telah dikembalikan kepada Museum dalam kondisi seperti tercantum pada hasil pemeriksaan di halaman sebelumnya.
            </div>
            <div class="note-box">
                <div class="note-title">Tanggung Jawab Penyewa</div>
                Segala kerusakan yang terjadi menjadi tanggung jawab penyewa sesuai perjanjian yang telah ditandatangani.
            </div>
            <div class="note-box">
                <div class="note-title">Kekuatan Hukum</div>
                Dokumen ini dibuat dalam dua rangkap asli dan memiliki kekuatan hukum yang sama bagi kedua pihak.
            </div>
        @endif
    </div>

    {{-- TANDA TANGAN --}}
    <div style="margin-top:20px; padding:0 10px; border-top:1px solid #e0e0e0; padding-top:16px;">

        <div style="display:table; width:100%;">

            {{-- Pihak Pertama: Museum --}}
            <div style="display:table-cell; width:50%; vertical-align:top; padding:0 16px; text-align:center;">
                <div style="font-size:8.5px; font-weight:bold; letter-spacing:1px; text-transform:uppercase; color:#fff; background:#1a5c2e; padding:3px 10px; margin-bottom:8px; display:inline-block; width:100%;">Pihak Pertama — Museum</div>
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

            {{-- Pihak Kedua: Penyewa --}}
            <div style="display:table-cell; width:50%; vertical-align:top; padding:0 16px; text-align:center;">
                <div style="font-size:8.5px; font-weight:bold; letter-spacing:1px; text-transform:uppercase; color:#fff; background:#1a5c2e; padding:3px 10px; margin-bottom:8px; display:inline-block; width:100%;">Pihak Kedua — Penyewa</div>
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

</body>
</html>