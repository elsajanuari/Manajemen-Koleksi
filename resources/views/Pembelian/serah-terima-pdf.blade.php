<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
  .items-table tbody tr { border-bottom: 1px solid #e5e5e5; }
  .items-table tbody tr:last-child { border-bottom: 2px solid #1a5c2e; }
  .items-table tbody td {
    padding: 9px 9px;
    font-size: 10px;
    vertical-align: top;
  }
  .item-title { font-weight: bold; font-size: 11px; color: #1a1a1a; }
  .item-meta  { font-size: 9.5px; color: #777; line-height: 1.6; margin-top: 2px; }

  /* ── INFO PENGIRIMAN (full width) ── */
  .shipping-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
  .shipping-table td {
    padding: 4px 8px;
    font-size: 10px;
    vertical-align: top;
    width: 16.66%;
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
    padding-top: 80px; /* jeda kosong di atas */
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
        <div class="museum-name">Museum MK. Lesmana</div>
      @endif
      <div class="museum-name">Museum MK. Lesmana</div>
      <div class="museum-address">
        Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes<br>
        Kabupaten Purwakarta, Jawa Barat 41175
      </div>
    </div>
    <div class="header-right">
      <div class="doc-label">Serah Terima</div>
      <div class="doc-sub">
        Nomor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span>BAST/{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}/{{ now()->format('Y') }}</span><br>
        Tanggal &nbsp;&nbsp;&nbsp;: <span>{{ $fmtTgl(now()) }}</span><br>
        Ref. Invoice : <span>{{ $pembelian->invoice_number }}</span>
      </div>
    </div>
  </div>

  {{-- IDENTITAS PARA PIHAK --}}
  <div class="two-col">

    {{-- Pihak Pertama: Penjual --}}
    <div class="col-left">
      <div class="section-label">Pihak Pertama — Penjual</div>
      <table class="info-table">
        <tr><td class="lbl">Nama Lembaga</td><td class="val">Museum MK. Lesmana</td></tr>
        <tr><td class="lbl">Diwakili oleh</td><td class="val-normal">Pengelola Museum</td></tr>
        <tr><td class="lbl">Alamat</td><td class="val-normal">Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175</td></tr>
      </table>
    </div>

    {{-- Pihak Kedua: Pembeli --}}
    <div class="col-right">
      <div class="section-label">Pihak Kedua — Pembeli</div>
      @if($pembelian->buyer_type === 'b2c')
        <table class="info-table">
          <tr><td class="lbl">Nama Lengkap</td><td class="val">{{ $pembelian->nama_lengkap }}</td></tr>
          <tr><td class="lbl">NIK</td><td class="val-normal">{{ $pembelian->nik }}</td></tr>
          <tr><td class="lbl">Tempat, Tgl. Lahir</td><td class="val-normal">{{ $pembelian->tempat_lahir }}, {{ $fmtTgl($pembelian->tanggal_lahir) }}</td></tr>
          <tr><td class="lbl">Pekerjaan</td><td class="val-normal">{{ $pembelian->pekerjaan }}</td></tr>
          @if($pembelian->npwp)
          <tr><td class="lbl">NPWP</td><td class="val-normal">{{ $pembelian->npwp }}</td></tr>
          @endif
          <tr><td class="lbl">Nomor HP</td><td class="val-normal">{{ $pembelian->nomor_hp }}</td></tr>
          <tr><td class="lbl">Email</td><td class="val-normal">{{ $pembelian->email }}</td></tr>
          <tr><td class="lbl">Alamat</td><td class="val-normal">{{ $pembelian->alamat_pengiriman }}, RT {{ $pembelian->rt }}/RW {{ $pembelian->rw }}, {{ $pembelian->kelurahan_desa }}, {{ $pembelian->kota_kabupaten }}, {{ $pembelian->provinsi }}</td></tr>
        </table>
      @else
        <table class="info-table">
          <tr><td class="lbl">Nama Perusahaan</td><td class="val">{{ $pembelian->company_name }}</td></tr>
          <tr><td class="lbl">Jenis Perusahaan</td><td class="val-normal">{{ $pembelian->company_type }}</td></tr>
          <tr><td class="lbl">NPWP Perusahaan</td><td class="val-normal">{{ $pembelian->company_npwp }}</td></tr>
          <tr><td class="lbl">Alamat Perusahaan</td><td class="val-normal">{{ $pembelian->company_address }}, {{ $pembelian->company_city }}, {{ $pembelian->company_province }}</td></tr>
          <tr><td class="lbl">PIC / Perwakilan</td><td class="val">{{ $pembelian->pic_name }}</td></tr>
          <tr><td class="lbl">Jabatan PIC</td><td class="val-normal">{{ $pembelian->pic_position }}</td></tr>
          <tr><td class="lbl">NIK PIC</td><td class="val-normal">{{ $pembelian->pic_nik }}</td></tr>
          <tr><td class="lbl">Nomor HP PIC</td><td class="val-normal">{{ $pembelian->pic_phone }}</td></tr>
        </table>
      @endif
    </div>

  </div>

  {{-- RINCIAN KOLEKSI --}}
  <div class="section-label">Rincian Koleksi yang Diserahterimakan</div>
  <table class="items-table">
    <thead>
      <tr>
        <th style="width:26px">No</th>
        <th>Deskripsi Koleksi</th>
        <th style="width:44px; text-align:center">Qty</th>
        <th style="width:155px; text-align:right">Harga Beli (Rp)</th>
        <th style="width:120px; text-align:right">Ongkos Kirim (Rp)</th>
        <th style="width:155px; text-align:right">Total (Rp)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="color:#aaa; font-size:9px; text-align:center">1</td>
        <td>
          <div class="item-title">{{ $pembelian->painting->title }}</div>
          <div class="item-meta">
            Seniman &nbsp;&nbsp;&nbsp;&nbsp;: {{ $pembelian->painting->artist ?? '-' }}<br>
            Tahun &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $pembelian->painting->year_created ?? '-' }}<br>
            Teknik/Media : {{ $pembelian->painting->media ?? '-' }}<br>
            Dimensi &nbsp;&nbsp;&nbsp;&nbsp;: {{ $pembelian->painting->dimensions ?? '-' }}<br>
            Kategori &nbsp;&nbsp;&nbsp;: {{ $pembelian->painting->category ?? '-' }}<br>
            No. Koleksi &nbsp;: {{ $pembelian->painting->collection_number ?? '-' }}
          </div>
        </td>
        <td style="text-align:center">1</td>
        <td style="text-align:right; font-weight:bold">
          {{ number_format($pembelian->harga_beli, 0, ',', '.') }}
        </td>
        <td style="text-align:right; font-weight:bold">
          @if((int)($pembelian->shipping_cost ?? 0) === 0)
            Gratis
          @else
            {{ number_format($pembelian->shipping_cost, 0, ',', '.') }}
          @endif
        </td>
        <td style="text-align:right; font-weight:bold">
          {{ number_format($pembelian->total_bayar, 0, ',', '.') }}
        </td>
      </tr>
    </tbody>
  </table>

  <br>

  {{-- INFO PENGIRIMAN (full width, horizontal) --}}
  <div class="section-label">Informasi Pengiriman</div>
  <table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
    <tr style="background:#f6fbf8;">
      <td style="padding:5px 9px; font-size:10px; width:16%;">
        <div class="shipping-lbl">Metode Pengiriman</div>
        <div class="shipping-val">{{ $pembelian->delivery_method ?? '-' }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:16%;">
        <div class="shipping-lbl">Petugas Pengiriman</div>
        <div class="shipping-val">{{ $pembelian->delivery_officer ?? '-' }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:18%;">
        <div class="shipping-lbl">No. Resi</div>
        <div class="shipping-val">{{ $pembelian->delivery_tracking_number ?? '-' }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:18%;">
        <div class="shipping-lbl">Tanggal Dikirim</div>
        <div class="shipping-val">{{ $fmtTglJam($pembelian->shipped_at) }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:18%;">
        <div class="shipping-lbl">Tanggal Diterima</div>
        <div class="shipping-val">{{ $fmtTglJam($pembelian->received_at) }}</div>
      </td>
    </tr>
    <tr>
      <td colspan="2" style="padding:5px 9px; font-size:10px;">
        <div class="shipping-lbl">Alamat Pengiriman</div>
        <div class="shipping-val">{{ $pembelian->delivery_location ?? '-' }}</div>
      </td>
      <td colspan="3" style="padding:5px 9px; font-size:10px;">
        <div class="shipping-lbl">Nama Penerima</div>
        <div class="shipping-val">{{ $pembelian->recipient_name ?? '-' }}</div>
      </td>
    </tr>
  </table>

  {{-- KLAUSUL & PERNYATAAN --}}
  <div class="notes-wrap">
    <div class="note-box">
      <div class="note-title">Pernyataan Serah Terima</div>
      Dengan ditandatanganinya dokumen ini, Pihak Pertama menyatakan telah menyerahkan koleksi lukisan sebagaimana tercantum di atas kepada Pihak Kedua, dan Pihak Kedua menyatakan telah menerima koleksi tersebut dalam kondisi yang baik dan sesuai dokumentasi.
    </div>
    <div class="note-box">
      <div class="note-title">Peralihan Hak Kepemilikan</div>
      Hak kepemilikan atas koleksi lukisan beralih sepenuhnya kepada Pihak Kedua sejak tanggal ditandatanganinya dokumen berita acara ini. Pihak Pertama menjamin bahwa koleksi adalah asli dan merupakan hak milik sah yang bebas dari sengketa hukum maupun klaim pihak lain.
    </div>
    <div class="note-box">
      <div class="note-title">Hak Cipta</div>
      Hak cipta dan hak moral atas karya lukisan tetap menjadi milik seniman sesuai ketentuan Undang-Undang No. 28 Tahun 2014 tentang Hak Cipta, kecuali diperjanjikan lain secara tertulis. Peralihan kepemilikan fisik tidak serta merta mengalihkan hak cipta.
    </div>
    <div class="note-box">
      <div class="note-title">Kekuatan Hukum</div>
      Dokumen Berita Acara Serah Terima ini dibuat dalam dua rangkap asli, masing-masing bermaterai cukup (Materai Rp10.000), dan memiliki kekuatan hukum yang sama bagi kedua pihak. Dokumen ini berlaku sebagai bukti sah pengalihan kepemilikan koleksi.
    </div>
  </div>

  {{-- FOOTER halaman 1 --}}
  <div class="footer">
    Museum MK. Lesmana &nbsp;&bull;&nbsp; Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175<br>
    BAST No. BAST/{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}/{{ now()->format('Y') }}
    &nbsp;&bull;&nbsp; Ref. Invoice: {{ $pembelian->invoice_number }}
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
      BAST/{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}/{{ now()->format('Y') }}
      &nbsp;&mdash;&nbsp; Ref. Invoice: {{ $pembelian->invoice_number }}
    </div>
    <div style="font-size:9px; color:#bbb; margin-top:3px;">Lanjutan — Halaman 2</div>
  </div>

  <div class="signature-section-title">Tanda Tangan Para Pihak</div>

  <div class="signature-wrap">

    {{-- Pihak Pertama: TTD Pengelola (dari storage) --}}
    <div class="sig-cell">
      <div class="sig-label">
        Purwakarta, {{ $fmtTgl(now()) }}<br>
        Pihak Pertama — Penjual,
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

    {{-- Pihak Kedua: Kolom TTD Pembeli (kosong, diisi manual) --}}
    <div class="sig-cell">
      <div class="sig-label">
        ........................., .................................<br>
        Pihak Kedua — Pembeli,
      </div>
      <div class="sig-box"></div>
      <div class="sig-note">(Tanda tangan &amp; nama terang)</div>
      <div class="sig-line" style="margin-top:6px">
        @if($pembelian->buyer_type === 'b2c')
          <div class="sig-name">{{ $pembelian->nama_lengkap }}</div>
          <div class="sig-role">Pembeli Perorangan</div>
        @else
          <div class="sig-name">{{ $pembelian->pic_name }}</div>
          <div class="sig-role">PIC &mdash; {{ $pembelian->company_name }}</div>
        @endif
      </div>
    </div>

  </div>

  {{-- Footer halaman 2 --}}
  <div class="footer" style="margin-top:40px;">
    Museum MK. Lesmana &nbsp;&bull;&nbsp; Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175<br>
    BAST No. BAST/{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}/{{ now()->format('Y') }}
    &nbsp;&bull;&nbsp; Ref. Invoice: {{ $pembelian->invoice_number }}
    &nbsp;&bull;&nbsp; Dicetak: {{ $fmtTglJam(now()) }} WIB
  </div>

</div>{{-- end signature-page --}}

</body>
</html>