<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Dokumen Serah Terima Penyewaan Koleksi</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: DejaVu Sans, sans-serif;
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

  /* ── SECTION HEADER ── */
  .section-label {
    font-size: 8.5px;
    font-weight: bold;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #fff;
    background: #1a5c2e;
    padding: 3px 10px;
    margin-bottom: 8px;
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
  .lbl { color: #666; width: 130px; padding-right: 6px; }
  .val { color: #1a1a1a; font-weight: bold; }
  .val-normal { color: #1a1a1a; }

  /* ── TABEL KOLEKSI ── */
  .items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 0;
  }
  .items-table thead tr { background: #1a5c2e; color: #fff; }
  .items-table thead th {
    padding: 7px 9px;
    font-size: 9.5px;
    font-weight: bold;
    letter-spacing: 0.4px;
    text-align: left;
  }
  .items-table thead th.center { text-align: center; }
  .items-table thead th.right { text-align: right; }
  .items-table tbody tr { border-bottom: 1px solid #e5e5e5; }
  .items-table tbody tr:last-child { border-bottom: 2px solid #1a5c2e; }
  .items-table tbody td {
    padding: 9px 9px;
    font-size: 10px;
    vertical-align: top;
  }
  .items-table tbody td.center { text-align: center; }
  .items-table tbody td.right { text-align: right; }
  .item-title { font-weight: bold; font-size: 11px; color: #1a1a1a; }
  .item-meta  { font-size: 9.5px; color: #777; line-height: 1.6; margin-top: 2px; }

  /* ── INFO PENGIRIMAN (full width) ── */
  .shipping-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
  .shipping-table td {
    padding: 5px 9px;
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
  .notes-wrap { margin-top: 14px; }
  .note-box {
    padding: 7px 12px;
    border-left: 3px solid #1a5c2e;
    background: #f6fbf8;
    font-size: 9.5px;
    color: #374151;
    line-height: 1.6;
    margin-bottom: 7px;
  }
  .note-box.warning {
    border-left-color: #d97706;
    background: #fffbeb;
    color: #78350f;
  }
  .note-title { font-weight: bold; margin-bottom: 2px; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }

  /* ── HALAMAN 2: TANDA TANGAN ── */
  .signature-page {
    page-break-before: always;
    padding-top: 80px;
  }

  .signature-section-title {
    font-size: 10px;
    font-weight: bold;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #fff;
    background: #1a5c2e;
    padding: 4px 10px;
    margin-bottom: 28px;
    text-align: center;
  }

  .signature-wrap {
    display: table;
    width: 100%;
    margin-top: 10px;
  }
  .sig-cell {
    display: table-cell;
    vertical-align: bottom;
    width: 50%;
    text-align: center;
    padding: 0 20px;
  }
  .sig-label { font-size: 9px; color: #555; margin-bottom: 50px; line-height: 1.7; }
  .sig-img   { height: 60px; width: auto; margin-bottom: 2px; }
  .sig-line  { border-top: 1px solid #333; padding-top: 4px; margin-top: 2px; }
  .sig-name  { font-size: 10.5px; font-weight: bold; }
  .sig-role  { font-size: 9px; color: #666; }
  .sig-box   {
    border: 1px dashed #aaa;
    height: 70px;
    margin-bottom: 4px;
    display: block;
    background: #fafafa;
  }
  .sig-note  { font-size: 8.5px; color: #888; font-style: italic; margin-top: 3px; }

  /* ── DIVIDER ── */
  .divider { border: none; border-top: 1px solid #e5e5e5; margin: 10px 0; }

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

  .fw-bold { font-weight: bold; }
  .text-center { text-align: center; }
  .badge-status {
    display: inline-block;
    background: #f6fbf8;
    border: 1px solid #1a5c2e;
    color: #1a5c2e;
    font-size: 9px;
    font-weight: bold;
    padding: 2px 8px;
    letter-spacing: 0.5px;
  }
</style>
</head>
<body>

@php
  /* ── Helper format tanggal Indonesia ── */
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

  /* ── TTD Pengelola dari public/images ── */
  $ttdPath   = public_path('images/ttd_pengelola.png');
  $ttdBase64 = file_exists($ttdPath)
      ? 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPath))
      : null;

  /* ── Logo ── */
  $logoPath   = public_path('images/logo_museum_mk_lesmana.png');
  $logoBase64 = file_exists($logoPath)
      ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
      : null;

  /* ── Data dari variabel yang dikirim ── */
  $penyewaan = $penyewaan ?? null;
  $serahTerimaStub = $serahTerimaStub ?? null;

  // Nomor dokumen
  $docNumber = $serahTerimaStub->document_number ?? 'ST-' . str_pad($penyewaan->id ?? 0, 6, '0', STR_PAD_LEFT);
  $invoiceNumber = $penyewaan->invoice_number ?? 'INV-' . str_pad($penyewaan->id ?? 0, 5, '0', STR_PAD_LEFT);

  // Data penyewa
  $isInstansi = ($penyewaan->rental_type ?? '') === 'instansi';
  $namaPenyewa = $isInstansi ? ($penyewaan->nama_instansi ?? '-') : ($penyewaan->contact_name ?? '-');
  $namaPIC = $penyewaan->nama_pic ?? $penyewaan->contact_name ?? '-';
  $jabatanPIC = $penyewaan->jabatan_pic ?? ($isInstansi ? 'PIC' : 'Penyewa');
  $alamatPenyewa = $isInstansi
      ? ($penyewaan->alamat_instansi ?? '-')
      : ($penyewaan->alamat_domisili ?? $penyewaan->alamat_lengkap ?? '-');

  // Data koleksi
  $painting = $penyewaan->painting ?? null;
  $startDate = $penyewaan->start_date ?? null;
  $endDate = $penyewaan->end_date ?? null;

  // Data pengiriman dari serah terima
  $st = $serahTerimaStub;
  $deliveryMethod = $st->delivery_method ?? $penyewaan->shipping_method_label ?? '-';
  $trackingNumber = $st->delivery_tracking_number ?? '-';
  $shippedAt = $st->shipped_at ?? null;
  $receivedAt = $st->confirmed_received_at ?? null;
  $recipientName = $st->recipient_name ?? $namaPenyewa;
  $deliveryLocation = $st->delivery_location ?? $alamatPenyewa;
  $deliveryOfficer = $st->delivery_officer ?? '-';
  $deliveryNotes = $st->delivery_notes ?? '-';
@endphp

<div class="page">

  {{-- ══════════════════════════════════════════
       HALAMAN 1
  ══════════════════════════════════════════ --}}

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
        Nomor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span>ST/{{ str_pad($penyewaan->id ?? 0, 5, '0', STR_PAD_LEFT) }}/{{ now()->format('Y') }}</span><br>
        Tanggal &nbsp;&nbsp;&nbsp;: <span>{{ $fmtTgl(now()) }}</span><br>
        Ref. Sewa &nbsp;: <span>{{ $docNumber }}</span>
      </div>
    </div>
  </div>

  {{-- IDENTITAS PARA PIHAK --}}
  <div class="two-col">

    {{-- Pihak Pertama: Museum --}}
    <div class="col-left">
      <div class="section-label">Pihak Pertama — Penyewa</div>
      <table class="info-table">
        <tr><td class="lbl">Nama Lembaga</td><td class="val">Museum MK. Lesmana</td></tr>
        <tr><td class="lbl">Diwakili oleh</td><td class="val-normal">Pengelola Museum</td></tr>
        <tr><td class="lbl">Alamat</td><td class="val-normal">Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175</td></tr>
        <tr><td class="lbl">Telepon</td><td class="val-normal">(0264) 1234567</td></tr>
      </table>
    </div>

    {{-- Pihak Kedua: Penyewa --}}
    <div class="col-right">
      <div class="section-label">Pihak Kedua — Penyewa</div>
      @if($isInstansi)
        <table class="info-table">
          <tr><td class="lbl">Nama Instansi</td><td class="val">{{ $penyewaan->nama_instansi ?? '-' }}</td></tr>
          <tr><td class="lbl">Jenis Instansi</td><td class="val-normal">{{ $penyewaan->jenis_instansi ?? '-' }}</td></tr>
          <tr><td class="lbl">NPWP</td><td class="val-normal">{{ $penyewaan->npwp_instansi ?? '-' }}</td></tr>
          <tr><td class="lbl">PIC / Perwakilan</td><td class="val">{{ $namaPIC }}</td></tr>
          <tr><td class="lbl">Jabatan PIC</td><td class="val-normal">{{ $jabatanPIC }}</td></tr>
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
  <table class="items-table">
    <thead>
      <tr>
        <th style="width:26px">No</th>
        <th>Deskripsi Koleksi</th>
        <th style="width:50px; text-align:center">Qty</th>
        <th style="width:120px; text-align:right">Tarif/Hari (Rp)</th>
        <th style="width:130px; text-align:right">Total Sewa (Rp)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="color:#aaa; font-size:9px; text-align:center">1</td>
        <td>
          <div class="item-title">{{ $painting->title ?? '-' }}</div>
          <div class="item-meta">
            Seniman &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $painting->artist ?? '-' }}<br>
            Tahun &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $painting->year_created ?? '-' }}<br>
            Teknik/Media : {{ $painting->media ?? '-' }}<br>
            Dimensi &nbsp;&nbsp;&nbsp;&nbsp;: {{ $painting->dimensions ?? '-' }}<br>
            Kategori &nbsp;&nbsp;&nbsp;: {{ $painting->category ?? '-' }}<br>
            No. Koleksi &nbsp;: {{ $painting->collection_number ?? '-' }}
          </div>
        </td>
        <td style="text-align:center">1</td>
        <td style="text-align:right; font-weight:bold">
          Rp {{ number_format($painting->daily_rate ?? 0, 0, ',', '.') }}
        </td>
        <td style="text-align:right; font-weight:bold">
          Rp {{ number_format($penyewaan->subtotal_amount ?? 0, 0, ',', '.') }}
        </td>
      </tr>
    </tbody>
  </table>

  <br>

  {{-- INFORMASI PENYEWAAN --}}
  <div class="section-label">Informasi Penyewaan</div>
  <table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
    <tr style="background:#f6fbf8;">
      <td style="padding:5px 9px; font-size:10px; width:20%;">
        <div class="shipping-lbl">Tanggal Mulai</div>
        <div class="shipping-val">{{ $fmtTgl($startDate) }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:20%;">
        <div class="shipping-lbl">Tanggal Selesai</div>
        <div class="shipping-val">{{ $fmtTgl($endDate) }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:18%;">
        <div class="shipping-lbl">Durasi</div>
        <div class="shipping-val">{{ $penyewaan->duration_days ?? '-' }} Hari</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:20%;">
        <div class="shipping-lbl">Deposit</div>
        <div class="shipping-val">Rp {{ number_format($penyewaan->deposit_amount ?? 0, 0, ',', '.') }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:22%;">
        <div class="shipping-lbl">Total Bayar</div>
        <div class="shipping-val">Rp {{ number_format($penyewaan->total_bayar ?? 0, 0, ',', '.') }}</div>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="padding:5px 9px; font-size:10px;">
        <div class="shipping-lbl">Tujuan Penyewaan</div>
        <div class="shipping-val">{{ $penyewaan->tujuan_penyewaan ?? '-' }}</div>
      </td>
      <td colspan="3" style="padding:5px 9px; font-size:10px;">
        <div class="shipping-lbl">Lokasi Penempatan</div>
        <div class="shipping-val">{{ $penyewaan->alamat_lengkap ?? $alamatPenyewa }}</div>
      </td>
    </tr>
  </table>

  {{-- INFO PENGIRIMAN --}}
  <div class="section-label">Informasi Pengiriman</div>
  <table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
    <tr style="background:#f6fbf8;">
      <td style="padding:5px 9px; font-size:10px; width:16%;">
        <div class="shipping-lbl">Metode Pengiriman</div>
        <div class="shipping-val">{{ $deliveryMethod }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:16%;">
        <div class="shipping-lbl">Petugas Pengiriman</div>
        <div class="shipping-val">{{ $deliveryOfficer }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:18%;">
        <div class="shipping-lbl">No. Resi</div>
        <div class="shipping-val">{{ $trackingNumber }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:18%;">
        <div class="shipping-lbl">Tanggal Dikirim</div>
        <div class="shipping-val">{{ $fmtTglJam($shippedAt) }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:18%;">
        <div class="shipping-lbl">Tanggal Diterima</div>
        <div class="shipping-val">{{ $fmtTglJam($receivedAt) }}</div>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="padding:5px 9px; font-size:10px;">
        <div class="shipping-lbl">Alamat Pengiriman</div>
        <div class="shipping-val">{{ $deliveryLocation }}</div>
      </td>
      <td colspan="3" style="padding:5px 9px; font-size:10px;">
        <div class="shipping-lbl">Nama Penerima</div>
        <div class="shipping-val">{{ $recipientName }}</div>
      </td>
    </tr>
  </table>

  {{-- KLAUSUL & PERNYATAAN --}}
  <div class="notes-wrap">
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
  </div>

  {{-- FOOTER halaman 1 --}}
  <div class="footer">
    Museum MK. Lesmana &nbsp;&bull;&nbsp; Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175<br>
    ST/{{ str_pad($penyewaan->id ?? 0, 5, '0', STR_PAD_LEFT) }}/{{ now()->format('Y') }}
    &nbsp;&bull;&nbsp; Ref. Sewa: {{ $docNumber }}
    &nbsp;&bull;&nbsp; Dicetak: {{ $fmtTglJam(now()) }} WIB
  </div>

</div>{{-- end page 1 --}}


{{-- ══════════════════════════════════════════
     HALAMAN 2: TANDA TANGAN PARA PIHAK
══════════════════════════════════════════ --}}
<div class="signature-page">

  {{-- Header ringkas halaman 2 --}}
  <div style="text-align:center; margin-bottom:24px;">
    <div style="font-size:11px; color:#888;">
      ST/{{ str_pad($penyewaan->id ?? 0, 5, '0', STR_PAD_LEFT) }}/{{ now()->format('Y') }}
      &nbsp;&mdash;&nbsp; Ref. Sewa: {{ $docNumber }}
    </div>
    <div style="font-size:9px; color:#bbb; margin-top:3px;">Lanjutan — Halaman 2</div>
  </div>

  <div class="signature-section-title">Tanda Tangan Para Pihak</div>

  <div class="signature-wrap">

    {{-- Pihak Pertama: TTD Pengelola (dari storage) --}}
    <div class="sig-cell">
      <div class="sig-label">
        Purwakarta, {{ $fmtTgl(now()) }}<br>
        Pihak Pertama — Penyewa,
      </div>

      @if($ttdBase64)
        <img class="sig-img" src="{{ $ttdBase64 }}" alt="TTD Pengelola" style="height:65px; width:auto; margin-bottom:4px;">
      @else
        {{-- fallback: kotak kosong jika file tidak ditemukan --}}
        <div class="sig-box"></div>
        <div class="sig-note">(tanda tangan pengelola)</div>
      @endif

      <div class="sig-line">
        <div class="sig-name">Pengelola Museum</div>
        <div class="sig-role">Museum MK. Lesmana</div>
      </div>
    </div>

    {{-- Pihak Kedua: Kolom TTD Penyewa (kosong, diisi manual) --}}
    <div class="sig-cell">
      <div class="sig-label">
        ........................., .................................<br>
        Pihak Kedua — Penyewa,
      </div>
      <div class="sig-box"></div>
      <div class="sig-note">(Tanda tangan &amp; nama terang)</div>
      <div class="sig-line" style="margin-top:6px">
        @if($isInstansi)
          <div class="sig-name">{{ $namaPIC }}</div>
          <div class="sig-role">PIC &mdash; {{ $penyewaan->nama_instansi ?? '-' }}</div>
        @else
          <div class="sig-name">{{ $penyewaan->contact_name ?? '-' }}</div>
          <div class="sig-role">Penyewa Perorangan</div>
        @endif
      </div>
    </div>

  </div>

  {{-- Footer halaman 2 --}}
  <div class="footer" style="margin-top:40px;">
    Museum MK. Lesmana &nbsp;&bull;&nbsp; Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175<br>
    ST/{{ str_pad($penyewaan->id ?? 0, 5, '0', STR_PAD_LEFT) }}/{{ now()->format('Y') }}
    &nbsp;&bull;&nbsp; Ref. Sewa: {{ $docNumber }}
    &nbsp;&bull;&nbsp; Dicetak: {{ $fmtTglJam(now()) }} WIB
  </div>

</div>{{-- end signature-page --}}

</body>
</html>