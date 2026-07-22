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

  .invoice-label {
    font-size: 26px;
    font-weight: bold;
    letter-spacing: 3px;
    color: #1a1a1a;
  }
  .invoice-sub {
    font-size: 9.5px;
    color: #666;
    line-height: 1.9;
    margin-top: 4px;
  }
  .invoice-sub span {
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

  /* ── BILLING GRID ── */
  .billing-grid { display: table; width: 100%; margin-bottom: 14px; }
  .billing-col  { display: table-cell; vertical-align: top; width: 50%; padding-right: 14px; }
  .billing-col:last-child { padding-right: 0; padding-left: 14px; }

  .info-table { width: 100%; border-collapse: collapse; }
  .info-table td {
    padding: 2px 0;
    font-size: 10px;
    vertical-align: top;
  }
  .info-table .lbl {
    color: #666;
    width: 120px;
    padding-right: 6px;
  }
  .info-table .val { color: #1a1a1a; font-weight: bold; }
  .info-table .val-normal { color: #1a1a1a; }

  /* ── ITEMS TABLE ── */
  .items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 0;
  }
  .items-table thead tr {
    background: #1a5c2e;
    color: #fff;
  }
  .items-table thead th {
    padding: 7px 9px;
    font-size: 9.5px;
    font-weight: bold;
    letter-spacing: 0.4px;
    text-align: left;
    border: none;
  }
  .items-table thead th.right { text-align: right; }
  .items-table thead th.center { text-align: center; }

  .items-table tbody tr { border-bottom: 1px solid #e5e5e5; }
  .items-table tbody tr:last-child { border-bottom: 2px solid #1a5c2e; }
  .items-table tbody td {
    padding: 9px 9px;
    font-size: 10px;
    vertical-align: top;
  }
  .items-table tbody td.center { text-align: center; }
  .items-table tbody td.right  { text-align: right; }

  .item-title { font-weight: bold; font-size: 11px; color: #1a1a1a; }
  .item-meta  { font-size: 9.5px; color: #777; line-height: 1.6; margin-top: 2px; }

  /* ── TOTALS ── */
  .totals-wrap { display: table; width: 100%; margin-top: 4px; }
  .totals-spacer { display: table-cell; width: 55%; }
  .totals-box {
    display: table-cell;
    width: 45%;
    vertical-align: top;
  }
  .totals-row {
    display: table;
    width: 100%;
    border-bottom: 1px solid #eee;
  }
  .totals-row-lbl, .totals-row-val {
    display: table-cell;
    padding: 4px 9px;
    font-size: 10px;
  }
  .totals-row-lbl { color: #555; }
  .totals-row-val { text-align: right; color: #1a1a1a; }

  .totals-final {
    display: table;
    width: 100%;
    background: #1a5c2e;
  }
  .totals-final-lbl, .totals-final-val {
    display: table-cell;
    padding: 8px 9px;
    font-size: 12px;
    font-weight: bold;
    color: #fff;
  }
  .totals-final-val { text-align: right; }

  /* ── NOTES ── */
  .notes-wrap { margin-top: 60px; }
  .note-box {
    padding: 5px 9px;
    border-left: 3px solid #1a5c2e;
    background: #f6fbf8;
    font-size: 8px;        /* dari 9.5px */
    color: #374151;
    line-height: 1.5;      /* dari 1.6 */
    margin-bottom: 4px;    /* dari 7px */
  }
  .note-box.warning {
      border-left-color: #d97706;
      background: #fffbeb;
      color: #78350f;
  }
  .note-title {
      font-weight: bold;
      margin-bottom: 1px;    /* dari 2px */
      font-size: 7.5px;      /* dari 9px */
      text-transform: uppercase;
      letter-spacing: 0.5px;
  }

  /* ── SIGNATURE ── */
  .signature-wrap {
    display: table;
    width: 100%;
    margin-top: 16px;
    border-top: 1px solid #e0e0e0;
    padding-top: 14px;
  }
  .sig-left  { display: table-cell; vertical-align: bottom; }
  .sig-right { display: table-cell; vertical-align: bottom; text-align: right; width: 200px; }

  .sig-label { font-size: 9px; color: #888; margin-bottom: 4px; }
  .sig-img   { height: 46px; width: auto; }
  .sig-line  { border-top: 1px solid #333; width: 150px; padding-top: 4px; margin-top: 2px; display: inline-block; }
  .sig-name  { font-size: 10.5px; font-weight: bold; }
  .sig-role  { font-size: 9px; color: #666; }

  .legal-notice {
    font-size: 8.5px;
    color: #aaa;
    line-height: 1.6;
  }

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

  /* ── DIVIDER ── */
  .divider { border: none; border-top: 1px solid #e5e5e5; margin: 10px 0; }

  /* ── UTILS ── */
  .fw-bold { font-weight: bold; }
</style>
</head>
<body>
<div class="page">

  @php
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];

    $formatTanggal = function($date) use ($bulan) {
        $dt = \Carbon\Carbon::parse($date);
        return $dt->format('d') . ' ' . $bulan[(int)$dt->format('n')] . ' ' . $dt->format('Y');
    };
    // Logo
    $logoPath   = public_path('images/logo_museum_mk_lesmana.png');
    $logoBase64 = file_exists($logoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : null;
    $invoiceNumber = $penyewaan->invoice_number ?? ('INV-SWA-' . str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT));
    $invoiceDate   = $penyewaan->invoice_generated_at ?? now();
    $subtotal      = ($penyewaan->painting->daily_rate ?? 0) * ($penyewaan->duration_days ?? 0);
    $deposit       = $subtotal > 0 ? (int) round($subtotal * 0.5) : 0;
    $ongkir        = (int) ($penyewaan->shipping_cost ?? 0);
    $total         = $subtotal + $deposit + $ongkir;

    // Nama & kontak penerima
    if ($penyewaan->rental_type === 'instansi') {
        $namaPenerima = $penyewaan->nama_pic ?? $penyewaan->contact_name ?? '-';
        $nomorHp      = $penyewaan->hp_pic ?? $penyewaan->contact_phone ?? '-';
        $emailContact = $penyewaan->email_pic ?? $penyewaan->email_instansi ?? '-';
    } else {
        $namaPenerima = $penyewaan->contact_name ?? '-';
        $nomorHp      = $penyewaan->contact_phone ?? '-';
        $emailContact = $penyewaan->contact_email ?? '-';
    }

    // Alamat pengiriman
    $alamatParts = array_filter([
        $penyewaan->alamat_lengkap ?? $penyewaan->alamat_domisili,
        $penyewaan->rt && $penyewaan->rw ? 'RT ' . $penyewaan->rt . '/RW ' . $penyewaan->rw : null,
        $penyewaan->kelurahan_desa,
        $penyewaan->kota_kabupaten,
        $penyewaan->provinsi,
        $penyewaan->kode_pos,
    ]);
    $alamatLengkap = implode(', ', $alamatParts) ?: '-';

    // Durasi sewa
    $startFormatted = $penyewaan->start_date ? $formatTanggal($penyewaan->start_date) : '-';
    $endFormatted   = $penyewaan->end_date   ? $formatTanggal($penyewaan->end_date)   : '-';
  @endphp

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
      <div class="invoice-label">INVOICE</div>
      <div class="invoice-sub">
        Nomor &nbsp;&nbsp;&nbsp;&nbsp;: <span>{{ $invoiceNumber }}</span><br>
        Tanggal &nbsp;&nbsp;: <span>{{ $formatTanggal($invoiceDate) }}</span><br>
        Jatuh Tempo: <span>{{ $formatTanggal(\Carbon\Carbon::parse($invoiceDate)->addDays(2)) }}</span>
      </div>
    </div>
  </div>

  {{-- DATA PENYEWA & ALAMAT PENGIRIMAN --}}
  <div class="billing-grid">

    <div class="billing-col">
      <div class="section-label">Ditagihkan Kepada</div>

      @if ($penyewaan->rental_type === 'instansi')
        <table class="info-table">
          <tr><td class="lbl">Nama Instansi</td><td class="val">{{ $penyewaan->nama_instansi ?? '-' }}</td></tr>
          <tr><td class="lbl">Jenis Instansi</td><td class="val-normal">{{ $penyewaan->jenis_instansi ?? '-' }}</td></tr>
          <tr><td class="lbl">Bidang Usaha</td><td class="val-normal">{{ $penyewaan->bidang_usaha ?? '-' }}</td></tr>
          <tr><td class="lbl">NPWP Instansi</td><td class="val-normal">{{ $penyewaan->npwp_instansi ?? '-' }}</td></tr>
          @if ($penyewaan->website_instansi)
          <tr><td class="lbl">Website</td><td class="val-normal">{{ $penyewaan->website_instansi }}</td></tr>
          @endif
          <tr><td colspan="2" style="padding-top:6px"></td></tr>
          <tr><td class="lbl" style="color:#1a5c2e;font-weight:bold">PIC</td><td class="val">{{ $penyewaan->nama_pic ?? '-' }}</td></tr>
          <tr><td class="lbl">Jabatan PIC</td><td class="val-normal">{{ $penyewaan->jabatan_pic ?? '-' }}</td></tr>
          <tr><td class="lbl">NIK PIC</td><td class="val-normal">{{ $penyewaan->nik_pic ?? '-' }}</td></tr>
          <tr><td class="lbl">Nomor HP PIC</td><td class="val-normal">{{ $penyewaan->hp_pic ?? '-' }}</td></tr>
          <tr><td class="lbl">Email PIC</td><td class="val-normal">{{ $penyewaan->email_pic ?? '-' }}</td></tr>
        </table>
      @else
        <table class="info-table">
          <tr><td class="lbl">Nama Lengkap</td><td class="val">{{ $penyewaan->contact_name ?? '-' }}</td></tr>
          <tr><td class="lbl">NIK</td><td class="val-normal">{{ $penyewaan->nik ?? '-' }}</td></tr>
          <tr>
            <td class="lbl">Tempat, Tgl. Lahir</td>
            <td class="val-normal">{{ $penyewaan->tempat_lahir ?? '-' }}, {{ $penyewaan->tanggal_lahir ? $formatTanggal($penyewaan->tanggal_lahir) : '-' }}</td>
          </tr>
          <tr><td class="lbl">Pekerjaan</td><td class="val-normal">{{ $penyewaan->pekerjaan ?? '-' }}</td></tr>
          @if ($penyewaan->npwp)
          <tr><td class="lbl">NPWP</td><td class="val-normal">{{ $penyewaan->npwp }}</td></tr>
          @endif
          <tr><td class="lbl">Nomor HP</td><td class="val-normal">{{ $penyewaan->contact_phone ?? '-' }}</td></tr>
          <tr><td class="lbl">Email</td><td class="val-normal">{{ $penyewaan->contact_email ?? '-' }}</td></tr>
        </table>
      @endif
    </div>

    <div class="billing-col">
      <div class="section-label">Alamat Pengiriman Koleksi</div>
      <table class="info-table">
        <tr>
          <td class="lbl">Alamat</td>
          <td class="val-normal">{{ $alamatLengkap }}</td>
        </tr>
        <tr>
          <td class="lbl">Penerima</td>
          <td class="val-normal">{{ $namaPenerima }}@if($penyewaan->rental_type === 'instansi') ({{ $penyewaan->nama_instansi ?? '' }})@endif</td>
        </tr>
        <tr><td class="lbl">Nomor HP</td><td class="val-normal">{{ $nomorHp }}</td></tr>
        <tr><td class="lbl">Email</td><td class="val-normal">{{ $emailContact }}</td></tr>
      </table>

      <hr class="divider">

      <div class="section-label">Referensi</div>
      <table class="info-table">
        <tr><td class="lbl">No. Pengajuan</td><td class="val-normal">#{{ str_pad($penyewaan->id, 6, '0', STR_PAD_LEFT) }}</td></tr>
        <tr>
          <td class="lbl">Tgl. Pengajuan</td>
          <td class="val-normal">{{ $penyewaan->submitted_at ? $formatTanggal($penyewaan->submitted_at) : '-' }}</td>
        </tr>
        <tr>
          <td class="lbl">Jenis Penyewa</td>
          <td class="val-normal">{{ $penyewaan->rental_type === 'instansi' ? 'Instansi / Lembaga' : 'Perseorangan' }}</td>
        </tr>
        <tr>
          <td class="lbl">Periode Sewa</td>
          <td class="val-normal">{{ $startFormatted }} s/d {{ $endFormatted }}</td>
        </tr>
      </table>
    </div>

  </div>

  {{-- TABEL ITEM --}}
  <div class="section-label">Rincian Koleksi yang Disewa</div>
  <table class="items-table">
    <thead>
      <tr>
        <th style="width:26px">No</th>
        <th>Deskripsi Koleksi</th>
        <th class="center" style="width:60px">Durasi</th>
        <th class="right" style="width:120px">Tarif/Hari (Rp)</th>
        <th class="right" style="width:140px">Subtotal (Rp)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="color:#aaa;font-size:9px;text-align:center">1</td>
        <td>
          <div class="item-title">{{ $penyewaan->painting->title ?? '-' }}</div>
          <div class="item-meta">
            Seniman &nbsp;&nbsp;&nbsp;: {{ $penyewaan->painting->artist ?? '-' }}<br>
            Tahun &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $penyewaan->painting->year_created ?? '-' }}<br>
            Teknik/Media : {{ $penyewaan->painting->media ?? '-' }}<br>
            Dimensi &nbsp;&nbsp;&nbsp;: {{ $penyewaan->painting->dimensions ?? '-' }}<br>
            Kategori &nbsp;&nbsp;: {{ $penyewaan->painting->category ?? '-' }}<br>
            Tujuan Sewa : {{ $penyewaan->tujuan_penyewaan ?? '-' }}
          </div>
        </td>
        <td class="center">{{ $penyewaan->duration_days ?? 0 }} hari</td>
        <td class="right">{{ number_format($penyewaan->painting->daily_rate ?? 0, 0, ',', '.') }}</td>
        <td class="right fw-bold">{{ number_format($subtotal, 0, ',', '.') }}</td>
      </tr>
    </tbody>
  </table>

  {{-- TOTAL --}}
  <div class="totals-wrap">
    <div class="totals-spacer"></div>
    <div class="totals-box">
      <div class="totals-row">
        <div class="totals-row-lbl">Subtotal Sewa</div>
        <div class="totals-row-val">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
      </div>
      <div class="totals-row">
        <div class="totals-row-lbl">
          Ongkos Kirim
          @if(!empty($penyewaan->shipping_method_type))
            @if($penyewaan->shipping_method_type === 'courier' && !empty($penyewaan->courier_name))
              ({{ $penyewaan->courier_name }})
            @elseif($penyewaan->shipping_method_type === 'manager')
              (Pengelola)
            @endif
          @endif
        </div>
        <div class="totals-row-val" style="{{ $ongkir === 0 ? 'color:#059669;' : '' }}">
          {{ $ongkir === 0 ? 'Gratis' : 'Rp ' . number_format($ongkir, 0, ',', '.') }}
        </div>
      </div>
      <div class="totals-row">
        <div class="totals-row-lbl">Deposit Jaminan (50%)</div>
        <div class="totals-row-val">Rp {{ number_format($deposit, 0, ',', '.') }}</div>
      </div>
      <div class="totals-final">
        <div class="totals-final-lbl">TOTAL PEMBAYARAN</div>
        <div class="totals-final-val">Rp {{ number_format($total, 0, ',', '.') }}</div>
      </div>
    </div>
  </div>

  {{-- CATATAN --}}
  <div class="notes-wrap">
    <div class="note-box warning">
      <div class="note-title">* Keterangan Pajak</div>
      Museum MK. Lesmana saat ini belum berstatus Pengusaha Kena Pajak (PKP), sehingga transaksi ini tidak dikenakan Pajak Pertambahan Nilai (PPN) sesuai ketentuan UU No. 42 Tahun 2009 tentang PPN dan PPnBM.
    </div>
    <div class="note-box">
      <div class="note-title">Keterangan Deposit</div>
      Deposit jaminan sebesar 50% dari biaya sewa akan dikembalikan setelah koleksi dikembalikan dalam kondisi baik sesuai perjanjian. Jika terdapat kerusakan, deposit dapat dipotong sesuai biaya perbaikan.
    </div>
    <div class="note-box">
      <div class="note-title">Cara Pembayaran</div>
      Pembayaran dilakukan secara elektronik melalui sistem pembayaran online yang tersedia di portal penyewaan Museum MK. Lesmana. Pastikan pembayaran diselesaikan sebelum <strong>{{ $formatTanggal(\Carbon\Carbon::parse($invoiceDate)->addDays(2)) }}</strong>.
    </div>
    @if (!empty($penyewaan->catatan_pengelola))
    <div class="note-box">
      <div class="note-title">Catatan dari Pengelola</div>
      {{ $penyewaan->catatan_pengelola }}
    </div>
    @endif
  </div>

</div>
</body>
</html>