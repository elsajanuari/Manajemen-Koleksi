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

  .page { padding: 28px 40px 28px 40px; }

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
    font-size: 20px;
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
  .doc-sub span { font-weight: bold; color: #1a1a1a; }

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
  .items-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
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

  /* ── INFO PENGIRIMAN ── */
  .shipping-lbl {
    font-size: 8.5px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #888;
    margin-bottom: 2px;
  }
  .shipping-val { font-size: 10px; color: #1a1a1a; font-weight: bold; }

  /* ── NOTES / KLAUSUL — diperkecil agar muat halaman 1 ── */
  .notes-wrap { margin-top: 10px; }
  .note-box {
    padding: 4px 10px;
    border-left: 3px solid #1a5c2e;
    background: #f6fbf8;
    font-size: 8.5px;
    color: #374151;
    line-height: 1.5;
    margin-bottom: 5px;
  }
  .note-box.warning {
    border-left-color: #d97706;
    background: #fffbeb;
    color: #78350f;
  }
  .note-title {
    font-weight: bold;
    margin-bottom: 1px;
    font-size: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  /* ── KERUSAKAN ── */
  .damage-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
  .damage-table th {
    background: #fef3c7;
    color: #78350f;
    font-size: 9px;
    padding: 5px 8px;
    text-align: left;
    border: 1px solid #fde68a;
  }
  .damage-table td {
    font-size: 9.5px;
    padding: 5px 8px;
    border: 1px solid #e5e5e5;
    vertical-align: top;
  }
  .damage-table tr:nth-child(even) td { background: #fffbeb; }

  /* ── HALAMAN 2: TANDA TANGAN ── */
  .signature-page {
    page-break-before: always;
    padding: 80px 40px 28px 40px;
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
  .signature-wrap { display: table; width: 100%; margin-top: 10px; }
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
</style>
</head>
<body>

@php
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
      return $dt->format('d') . ' ' . $namaBulan[(int)$dt->format('n')] . ' ' . $dt->format('Y') . ', ' . $dt->format('H:i') . ' WIB';
  };

  /* TTD & logo */
  $ttdPath   = public_path('images/ttd_pengelola.png');
  $ttdBase64 = file_exists($ttdPath)
      ? 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPath))
      : null;

  $logoPath   = public_path('images/logo_museum_mk_lesmana.png');
  $logoBase64 = file_exists($logoPath)
      ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
      : null;

  /* Nomor dokumen pengembalian */
  $noRef = 'RTN/' . str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) . '/' . now()->format('Y');

  /* Konteks dokumen */
  $isCancelledDueToDamage = $serahTerima->isArrivalDamageCancellation();

  /* Biaya kerusakan */
  $hasDamage   = $serahTerima->has_damage;
  $damageCost  = (int) ($serahTerima->final_damage_cost ?? $serahTerima->damage_cost ?? 0);
  $depositAmount = $penyewaan->deposit_amount ?? $penyewaan->calculateDeposit();
  $sisaRefund  = max(0, $depositAmount - $damageCost);

  /* Data pengembalian */
  $returnMethod    = $serahTerima->return_shipment_method ?? '-';
  $returnOfficer   = $serahTerima->return_shipment_officer ?? '-';
  $returnTracking  = $serahTerima->return_shipment_tracking ?? '-';
  $returnScheduled = $fmtTgl($serahTerima->return_shipment_scheduled_at);
  $returnNotes     = $serahTerima->return_shipment_notes ?? '-';
  $returnShipped   = $fmtTglJam($serahTerima->return_shipped_at ?? $serahTerima->return_shipment_submitted_at);
  $collectionArrived = $fmtTglJam($serahTerima->collection_arrived_at);

  /* Nama penyewa */
  $namaPenyewa = $penyewaan->contact_name ?? $penyewaan->nama_instansi ?? '-';
  $kotaPenyewa = $penyewaan->lokasi ?? '-';

  /* Alamat penyewa — tampilkan untuk perorangan maupun instansi */
  $alamatPenyewa = $penyewaan->alamat_ktp
      ?? $penyewaan->lokasi_lengkap
      ?? $penyewaan->alamat
      ?? '-';

  /* Biaya sewa — ambil dari berbagai kemungkinan field */
  $biayaSewa = $penyewaan->total_biaya
      ?? $penyewaan->rental_cost
      ?? $penyewaan->biaya_sewa
      ?? $penyewaan->harga_sewa
      ?? 0;
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
      @endif
      <div class="museum-name">Museum MK. Lesmana</div>
      <div class="museum-address">
        Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes<br>
        Kabupaten Purwakarta, Jawa Barat 41175
      </div>
    </div>
    <div class="header-right">
      <div class="doc-label">
        {{ $isCancelledDueToDamage ? 'Pengembalian Koleksi' : 'Serah Terima Pengembalian' }}
      </div>
      <div class="doc-sub">
        Nomor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span>{{ $noRef }}</span><br>
        Tanggal &nbsp;&nbsp;&nbsp;: <span>{{ $fmtTgl(now()) }}</span><br>
        No. Penyewaan : <span>SP-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</span>
      </div>
    </div>
  </div>

  {{-- IDENTITAS PARA PIHAK --}}
  <div class="two-col">

    {{-- Pihak Pertama: Museum --}}
    <div class="col-left">
      <div class="section-label">Pihak Pertama — Museum</div>
      <table class="info-table">
        <tr><td class="lbl">Nama Lembaga</td><td class="val">Museum MK. Lesmana</td></tr>
        <tr><td class="lbl">Diwakili oleh</td><td class="val-normal">Pengelola Museum</td></tr>
        <tr><td class="lbl">Alamat</td><td class="val-normal">Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175</td></tr>
      </table>
    </div>

    {{-- Pihak Kedua: Penyewa --}}
    <div class="col-right">
      <div class="section-label">Pihak Kedua — Penyewa</div>
      <table class="info-table">
        <tr>
          <td class="lbl">Nama</td>
          <td class="val">{{ $namaPenyewa }}</td>
        </tr>
        <tr>
          <td class="lbl">Email</td>
          <td class="val-normal">{{ $penyewaan->contact_email ?? '-' }}</td>
        </tr>
        <tr>
          <td class="lbl">Telepon</td>
          <td class="val-normal">{{ $penyewaan->contact_phone ?? '-' }}</td>
        </tr>
        @if($penyewaan->nama_instansi && $penyewaan->contact_name)
        <tr>
          <td class="lbl">Instansi</td>
          <td class="val-normal">{{ $penyewaan->nama_instansi }}</td>
        </tr>
        @endif
        <tr>
          <td class="lbl">Alamat</td>
          <td class="val-normal">{{ $alamatPenyewa }}</td>
        </tr>
      </table>
    </div>

  </div>

  {{-- RINCIAN KOLEKSI --}}
  <div class="section-label">Rincian Koleksi yang Dikembalikan</div>
  <table class="items-table">
    <thead>
      <tr>
        <th style="width:26px">No</th>
        <th>Deskripsi Koleksi</th>
        <th style="width:120px">Periode Sewa</th>
        <th style="width:130px; text-align:right">Biaya Sewa (Rp)</th>
        <th style="width:100px; text-align:center">Status</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="color:#aaa; font-size:9px; text-align:center">1</td>
        <td>
          <div class="item-title">{{ $penyewaan->painting->title ?? '-' }}</div>
          <div class="item-meta">
            Seniman &nbsp;&nbsp;&nbsp;&nbsp;: {{ $penyewaan->painting->artist ?? '-' }}<br>
            Tahun &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $penyewaan->painting->year ?? $penyewaan->painting->year_created ?? '-' }}<br>
            Teknik/Media : {{ $penyewaan->painting->media ?? '-' }}<br>
            Dimensi &nbsp;&nbsp;&nbsp;&nbsp;: {{ $penyewaan->painting->dimensions ?? '-' }}<br>
            No. Koleksi &nbsp;: {{ $penyewaan->painting->collection_number ?? '-' }}
          </div>
        </td>
        <td style="font-size:9.5px; vertical-align:middle;">
          {{ $fmtTgl($penyewaan->start_date) }}<br>
          s/d<br>
          {{ $fmtTgl($penyewaan->end_date) }}
        </td>
        <td style="text-align:right; font-weight:bold; vertical-align:middle;">
          {{ number_format($biayaSewa, 0, ',', '.') }}
        </td>
        <td style="text-align:center; vertical-align:middle;">
          @if($isCancelledDueToDamage)
            <span style="color:#b91c1c; font-weight:bold; font-size:9px;">Dibatalkan</span>
          @else
            <span style="color:#1a5c2e; font-weight:bold; font-size:9px;">Dikembalikan</span>
          @endif
        </td>
      </tr>
    </tbody>
  </table>

  <br>

  {{-- INFO PENGIRIMAN BALIK --}}
  <div class="section-label">Informasi Pengiriman Balik Koleksi</div>
  <table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
    <tr style="background:#f6fbf8;">
      <td style="padding:5px 9px; font-size:10px; width:20%;">
        <div class="shipping-lbl">Metode Pengiriman</div>
        <div class="shipping-val">{{ $returnMethod }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:20%;">
        <div class="shipping-lbl">Petugas / Pengirim</div>
        <div class="shipping-val">{{ $returnOfficer }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:20%;">
        <div class="shipping-lbl">No. Resi</div>
        <div class="shipping-val">{{ $returnTracking }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:20%;">
        <div class="shipping-lbl">Jadwal Pengiriman</div>
        <div class="shipping-val">{{ $returnScheduled }}</div>
      </td>
      <td style="padding:5px 9px; font-size:10px; width:20%;">
        <div class="shipping-lbl">Koleksi Tiba di Museum</div>
        <div class="shipping-val">{{ $collectionArrived }}</div>
      </td>
    </tr>
    @if($returnNotes !== '-')
    <tr>
      <td colspan="5" style="padding:5px 9px; font-size:9.5px; color:#555;">
        <span style="font-weight:bold; text-transform:uppercase; font-size:8.5px; color:#888;">Catatan: </span>{{ $returnNotes }}
      </td>
    </tr>
    @endif
  </table>

  {{-- KERUSAKAN (jika ada) --}}
  @if($hasDamage && !$isCancelledDueToDamage)
  <div class="section-label" style="background:#d97706;">Laporan Kerusakan Koleksi</div>
  <table class="damage-table" style="margin-bottom:14px;">
    <thead>
      <tr>
        <th style="width:30%">Jenis Kerusakan</th>
        <th style="width:20%">Tingkat</th>
        <th style="width:30%">Keterangan</th>
        <th style="width:20%; text-align:right">Biaya (Rp)</th>
      </tr>
    </thead>
    <tbody>
      @forelse($serahTerima->damage_items_detail ?? [] as $item)
      <tr>
        <td>{{ $item['label'] ?? '-' }}</td>
        <td>{{ ucfirst($item['level'] ?? '-') }}</td>
        <td>{{ $item['note'] ?? '-' }}</td>
        <td style="text-align:right; font-weight:bold;">{{ number_format($item['cost'] ?? 0, 0, ',', '.') }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="4" style="color:#888; font-style:italic;">Detail kerusakan tidak tersedia.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  {{-- Ringkasan deposit --}}
  <table style="width:60%; margin-left:auto; border-collapse:collapse; margin-bottom:14px;">
    <tr style="background:#f6fbf8;">
      <td style="padding:5px 10px; font-size:10px; color:#555;">Deposit Penyewa</td>
      <td style="padding:5px 10px; font-size:10px; text-align:right; font-weight:bold;">Rp {{ number_format($depositAmount, 0, ',', '.') }}</td>
    </tr>
    <tr>
      <td style="padding:5px 10px; font-size:10px; color:#b91c1c;">Potongan Biaya Kerusakan</td>
      <td style="padding:5px 10px; font-size:10px; text-align:right; font-weight:bold; color:#b91c1c;">(Rp {{ number_format($damageCost, 0, ',', '.') }})</td>
    </tr>
    <tr style="background:#f6fbf8; border-top:2px solid #1a5c2e;">
      <td style="padding:6px 10px; font-size:10.5px; font-weight:bold; color:#1a5c2e;">Sisa Deposit Dikembalikan</td>
      <td style="padding:6px 10px; font-size:10.5px; text-align:right; font-weight:bold; color:#1a5c2e;">Rp {{ number_format($sisaRefund, 0, ',', '.') }}</td>
    </tr>
  </table>
  @endif

  {{-- KLAUSUL & PERNYATAAN --}}
  <div class="notes-wrap">

    @if($isCancelledDueToDamage)
    {{-- Konteks: pembatalan akibat kerusakan saat pengiriman --}}
    <div class="note-box warning">
      <div class="note-title">Latar Belakang Pembatalan</div>
      Sewa atas koleksi di atas dinyatakan <strong>dibatalkan</strong> karena ditemukan kerusakan pada koleksi saat proses pengiriman ke penyewa, sebelum koleksi sempat digunakan. Pembatalan ini disepakati oleh kedua pihak setelah melalui proses pelaporan dan verifikasi kerusakan.
    </div>
    <div class="note-box">
      <div class="note-title">Pernyataan Pengembalian</div>
      Dengan ditandatanganinya dokumen ini, Pihak Kedua menyatakan telah mengembalikan koleksi lukisan kepada Pihak Pertama dalam kondisi sebagaimana diterima. Pihak Pertama menyatakan telah menerima kembali koleksi tersebut di museum.
    </div>
    <div class="note-box">
      <div class="note-title">Pengembalian Biaya Sewa &amp; Deposit</div>
      Sehubungan dengan pembatalan ini, seluruh biaya sewa dan deposit yang telah dibayarkan oleh Pihak Kedua akan dikembalikan secara penuh ke rekening yang telah disepakati, dikurangi ongkos kirim awal sesuai ketentuan.
    </div>
    @else
    {{-- Konteks: pengembalian normal --}}
    <div class="note-box">
      <div class="note-title">Pernyataan Pengembalian</div>
      Dengan ditandatanganinya dokumen ini, Pihak Kedua menyatakan telah mengembalikan koleksi lukisan kepada Pihak Pertama (Museum MK. Lesmana) sesuai ketentuan yang telah disepakati dalam perjanjian sewa.
    </div>
    @if($hasDamage)
    <div class="note-box warning">
      <div class="note-title">Tanggung Jawab Kerusakan</div>
      Berdasarkan hasil pemeriksaan kondisi koleksi oleh pengelola museum, ditemukan kerusakan sebagaimana tercantum di atas. Biaya restorasi/perbaikan telah dipotong dari deposit Pihak Kedua sesuai dengan perjanjian sewa yang berlaku.
    </div>
    @else
    <div class="note-box">
      <div class="note-title">Kondisi Koleksi</div>
      Berdasarkan hasil pemeriksaan kondisi koleksi oleh pengelola museum, koleksi dinyatakan dikembalikan dalam kondisi baik tanpa kerusakan yang berarti. Deposit Pihak Kedua akan dikembalikan secara penuh.
    </div>
    @endif
    @endif

    <div class="note-box">
      <div class="note-title">Pelepasan Tanggung Jawab</div>
      Dengan selesainya proses pengembalian ini, hak dan kewajiban kedua pihak terkait penyewaan koleksi lukisan tersebut dinyatakan telah terpenuhi. Koleksi kembali menjadi tanggung jawab sepenuhnya Pihak Pertama (Museum MK. Lesmana).
    </div>
    <div class="note-box">
      <div class="note-title">Kekuatan Hukum</div>
      Dokumen ini dibuat dalam dua rangkap asli, masing-masing bermaterai cukup (Materai Rp10.000), dan memiliki kekuatan hukum yang sama bagi kedua pihak. Dokumen ini berlaku sebagai bukti sah selesainya proses penyewaan koleksi.
    </div>

  </div>

  {{-- FOOTER halaman 1 --}}
  <div class="footer">
    Museum MK. Lesmana &nbsp;&bull;&nbsp; Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175<br>
    {{ $noRef }} &nbsp;&bull;&nbsp; No. Penyewaan: SP-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}
    &nbsp;&bull;&nbsp; Dicetak: {{ $fmtTglJam(now()) }}
  </div>

</div>{{-- end page 1 --}}


{{-- ══════════════════════════════════════════
     HALAMAN 2: TANDA TANGAN PARA PIHAK
══════════════════════════════════════════ --}}
<div class="signature-page">

  {{-- Header ringkas halaman 2 --}}
  <div style="text-align:center; margin-bottom:24px;">
    <div style="font-size:11px; color:#888;">
      {{ $noRef }} &nbsp;&mdash;&nbsp; No. Penyewaan: SP-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}
    </div>
    <div style="font-size:9px; color:#bbb; margin-top:3px;">Lanjutan — Halaman 2</div>
  </div>

  <div class="signature-section-title">Tanda Tangan Para Pihak</div>

  <div class="signature-wrap">

    {{-- Pihak Pertama: TTD Pengelola --}}
    <div class="sig-cell">
      <div class="sig-label">
        Purwakarta, {{ $fmtTgl(now()) }}<br>
        Pihak Pertama — Museum,
      </div>

      @if($ttdBase64)
        <img class="sig-img" src="{{ $ttdBase64 }}" alt="TTD Pengelola" style="height:65px; width:auto; margin-bottom:4px;">
      @else
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
      <div class="sig-note">(Tanda tangan &amp; Materai 10.000)</div>
      <div class="sig-line" style="margin-top:6px;">
        <div class="sig-name">{{ $namaPenyewa }}</div>
        @if($penyewaan->nama_instansi && $penyewaan->contact_name)
          <div class="sig-role">{{ $penyewaan->nama_instansi }}</div>
        @else
          <div class="sig-role">Penyewa Koleksi</div>
        @endif
      </div>
    </div>

  </div>

  {{-- Footer halaman 2 --}}
  <div class="footer" style="margin-top:40px;">
    Museum MK. Lesmana &nbsp;&bull;&nbsp; Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175<br>
    {{ $noRef }} &nbsp;&bull;&nbsp; No. Penyewaan: SP-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}
    &nbsp;&bull;&nbsp; Dicetak: {{ $fmtTglJam(now()) }}
  </div>

</div>{{-- end signature-page --}}

</body>
</html>