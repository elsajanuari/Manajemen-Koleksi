<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Surat Perjanjian Penyewaan Koleksi Lukisan</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 10px;
    color: #1a1a1a;
    background: #fff;
    line-height: 1.6;
  }

  .page { padding: 36px 40px 36px 40px; }

  /* ── HEADER ── */
  .header {
    display: table;
    width: 100%;
    border-bottom: 3px solid #1a5c2e;
    padding-bottom: 10px;
    margin-bottom: 10px;
  }
  .header-left  { display: table-cell; vertical-align: middle; width: 62%; }
  .header-right { display: table-cell; vertical-align: middle; text-align: right; }
  .logo { height: 48px; width: auto; }
  .museum-name { font-size: 12px; font-weight: bold; color: #1a5c2e; margin-top: 4px; letter-spacing: 0.3px; }
  .museum-address { font-size: 8.5px; color: #555; line-height: 1.55; margin-top: 2px; }
  .doc-label { font-size: 16px; font-weight: bold; letter-spacing: 2px; color: #1a1a1a; text-transform: uppercase; }
  .doc-sub { font-size: 8.5px; color: #666; line-height: 1.8; margin-top: 3px; }
  .doc-sub span { font-weight: bold; color: #1a1a1a; }

  /* ── JUDUL ── */
  .doc-title { text-align: center; margin-bottom: 10px; padding: 6px 0 10px; border-bottom: 1px solid #e5e7eb; }
  .doc-title h1 { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
  .doc-title .doc-number { font-size: 8.5px; color: #1a5c2e; font-weight: bold; letter-spacing: 1px; margin-top: 3px; }

  .preamble { font-size: 9px; color: #1a1a1a; text-align: justify; margin-bottom: 10px; }

  /* ── SECTION LABEL ── */
  .section-label {
    font-size: 8px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase;
    color: #fff; background: #1a5c2e; padding: 3px 8px; margin-bottom: 6px; margin-top: 10px;
  }

  /* ── PASAL TITLE ── */
  .pasal-title {
    font-size: 8.5px; font-weight: bold; letter-spacing: 0.4px; text-transform: uppercase;
    color: #1a5c2e; border-left: 3px solid #1a5c2e; padding-left: 6px;
    margin-bottom: 4px; margin-top: 9px;
  }

  /* ── PAGE BREAK DENGAN JEDA KOSONG ── */
  .page-break-section {
    page-break-before: always;
    padding-top: 0;
  }
  .page-break-spacer {
    height: 80px;
    display: block;
  }

  /* ── PIHAK GRID ── */
  .pihak-grid { display: table; width: 100%; margin-bottom: 8px; border-collapse: separate; border-spacing: 8px 0; }
  .pihak-col {
    display: table-cell; vertical-align: top; width: 50%;
    background: #f6fbf8; border: 1px solid #c6e4d0; border-left: 3px solid #1a5c2e; padding: 7px 9px;
  }
  .pihak-col:last-child { background: #f8fafc; border-color: #cbd5e1; border-left-color: #64748b; }
  .pihak-head {
    font-size: 8px; font-weight: bold; letter-spacing: 0.8px; text-transform: uppercase;
    color: #1a5c2e; margin-bottom: 5px; padding-bottom: 3px; border-bottom: 1px solid #c6e4d0;
  }
  .pihak-col:last-child .pihak-head { color: #475569; border-bottom-color: #cbd5e1; }
  .pihak-footer {
    margin-top: 5px; padding-top: 4px; border-top: 1px dashed #c6e4d0;
    font-size: 8px; font-weight: bold; text-align: right;
  }
  .pihak-col:last-child .pihak-footer { border-top-color: #cbd5e1; }

  /* ── INFO TABLE ── */
  .info-table { width: 100%; border-collapse: collapse; }
  .info-table td { padding: 1.5px 0; font-size: 9px; vertical-align: top; }
  .info-table .lbl { color: #666; width: 90px; padding-right: 5px; }
  .info-table .val { color: #1a1a1a; font-weight: bold; }
  .info-table .val-normal { color: #1a1a1a; }

  /* ── DATA TABLE ── */
  .data-table { width: 100%; border-collapse: collapse; margin-top: 4px; margin-bottom: 4px; }
  .data-table th, .data-table td { border: 1px solid #d1d5db; padding: 4px 7px; vertical-align: top; font-size: 9px; }
  .data-table th { background: #f0f7f2; color: #1a5c2e; font-weight: bold; width: 32%; text-align: left; }
  .data-table tr:last-child th, .data-table tr:last-child td { border-bottom: 2px solid #1a5c2e; }

  /* ── BIAYA FULL WIDTH ── */
  .biaya-box { width: 100%; border: 1px solid #e5e7eb; border-collapse: collapse; margin-top: 5px; margin-bottom: 5px; }
  .biaya-row { display: table; width: 100%; border-bottom: 1px solid #e5e7eb; }
  .biaya-lbl, .biaya-val { display: table-cell; padding: 5px 10px; font-size: 9px; }
  .biaya-lbl { color: #555; }
  .biaya-val { text-align: right; color: #1a1a1a; font-weight: bold; white-space: nowrap; }
  .biaya-final { display: table; width: 100%; background: #1a5c2e; }
  .biaya-final-lbl, .biaya-final-val { display: table-cell; padding: 6px 10px; font-size: 9.5px; font-weight: bold; color: #fff; }
  .biaya-final-val { text-align: right; }
  .biaya-note { font-size: 8.5px; color: #555; text-align: justify; margin-top: 6px; }

  /* ── PASAL CONTENT ── */
  .pasal-content { font-size: 9px; color: #1a1a1a; text-align: justify; }
  .pasal-content ul, .pasal-content ol { padding-left: 16px; margin-top: 3px; }
  .pasal-content ul li, .pasal-content ol li { margin-bottom: 2px; }
  .pasal-content p { margin-bottom: 2px; }

  /* ── NOTE BOX ── */
  .note-box {
    background: #fffbeb; border-left: 3px solid #d97706;
    padding: 5px 8px; font-size: 8px; color: #78350f; margin-top: 8px;
    line-height: 1.5; text-align: justify;
  }

  .divider { border: none; border-top: 1px solid #e5e7eb; margin: 8px 0; }

  /* ── SIGNATURE ── */
  .signature-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
  .signature-table td { width: 50%; vertical-align: top; padding: 0 8px; }
  .signature-table td:first-child { padding-left: 0; }
  .signature-table td:last-child  { padding-right: 0; }
  .sig-box { border: 1px solid #d1d5db; border-top: 3px solid #1a5c2e; padding: 7px 9px; }
  .sig-box.pihak-dua { border-top-color: #64748b; }
  .sig-head { font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px; color: #1a5c2e; margin-bottom: 3px; }
  .sig-box.pihak-dua .sig-head { color: #475569; }
  .sig-sub { font-size: 8px; color: #555; margin-bottom: 4px; }
  .sig-img-wrap { height: 56px; display: block; }
  .sig-img { height: 52px; width: auto; }
  .sig-area { height: 52px; }
  .sig-line { border-top: 1px solid #333; padding-top: 4px; margin-top: 4px; }
  .sig-name { font-size: 9px; font-weight: bold; color: #1a1a1a; }
  .sig-role { font-size: 8px; color: #666; }

  /* ── FOOTER ── */
  .footer {
    margin-top: 10px; border-top: 1px solid #e5e5e5; padding-top: 6px;
    text-align: center; font-size: 7.5px; color: #aaa; line-height: 1.6;
  }
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

    $hariNamaMap = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa',
                    'Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
    $namaHari   = $hariNamaMap[now()->format('l')] ?? now()->format('l');
    $tglHariIni = now()->format('d') . ' ' . $bulan[(int)now()->format('n')] . ' ' . now()->format('Y');

    $logoPath   = public_path('images/logo_museum_mk_lesmana.png');
    $logoBase64 = file_exists($logoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : null;

    $ttdPath   = public_path('images/ttd_pengelola.png');
    $ttdBase64 = file_exists($ttdPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($ttdPath))
        : null;

    $nomorSP  = 'SP-' . str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT);
    $subtotal = ($penyewaan->painting->daily_rate ?? 0) * ($penyewaan->duration_days ?? 0);
    $deposit  = $subtotal > 0 ? (int) round($subtotal * 0.5) : 0;
    $ongkir   = (int) ($penyewaan->shipping_cost ?? 0);
    $total    = $subtotal + $deposit + $ongkir;

    $startFormatted = $penyewaan->start_date ? $formatTanggal($penyewaan->start_date) : '-';
    $endFormatted   = $penyewaan->end_date   ? $formatTanggal($penyewaan->end_date)   : '-';

    $namaPenyewa = $penyewaan->rental_type === 'instansi'
        ? ($penyewaan->nama_pic ?? $penyewaan->contact_name ?? '-')
        : ($penyewaan->contact_name ?? '-');

    $jabatanPenyewa = $penyewaan->rental_type === 'instansi'
        ? ($penyewaan->jabatan_pic ?? 'Penanggung Jawab') . ' ' . ($penyewaan->nama_instansi ?? '')
        : 'Penyewa Perseorangan';

    $buildAlamat = function (array $parts) {
        $clean = array_filter(array_map('trim', array_map('strval', $parts)), function ($v) {
            return $v !== '' && $v !== '-' && $v !== null;
        });
        return count($clean) ? implode(', ', $clean) : '-';
    };

    // ── Alamat Domisili Perorangan ──
    $rtRwDomisili = (!empty($penyewaan->rt) && !empty($penyewaan->rw))
        ? 'RT. ' . $penyewaan->rt . ' / RW. ' . $penyewaan->rw
        : null;
    $alamatDomisiliLengkap = $buildAlamat([
        $penyewaan->alamat_domisili ?? null,
        $rtRwDomisili,
        !empty($penyewaan->kelurahan_desa) ? 'Kel. ' . $penyewaan->kelurahan_desa : null,
        !empty($penyewaan->kecamatan)      ? 'Kec. ' . $penyewaan->kecamatan      : null,
        $penyewaan->kota_kabupaten ?? null,
        $penyewaan->provinsi       ?? null,
        $penyewaan->kode_pos       ?? null,
    ]);

    // ── Alamat Instansi ──
    $rtRwInstansi = (!empty($penyewaan->rt_instansi) && !empty($penyewaan->rw_instansi))
        ? 'RT. ' . $penyewaan->rt_instansi . ' / RW. ' . $penyewaan->rw_instansi
        : null;
    $alamatInstansiLengkap = $buildAlamat([
        $penyewaan->alamat_instansi ?? null,
        $rtRwInstansi,
        !empty($penyewaan->kelurahan_desa_instansi) ? 'Kel. ' . $penyewaan->kelurahan_desa_instansi : null,
        !empty($penyewaan->kecamatan_instansi)      ? 'Kec. ' . $penyewaan->kecamatan_instansi      : null,
        $penyewaan->kota_instansi     ?? null,
        $penyewaan->provinsi_instansi ?? null,
        $penyewaan->kode_pos_instansi ?? null,
    ]);

    // ── Alamat Lokasi Penempatan — LENGKAP dengan RT/RW dll ──
    $rtRwLokasi = (!empty($penyewaan->rt_lokasi) && !empty($penyewaan->rw_lokasi))
        ? 'RT. ' . $penyewaan->rt_lokasi . ' / RW. ' . $penyewaan->rw_lokasi
        : null;
    $alamatLokasiLengkap = $buildAlamat([
        $penyewaan->alamat_lengkap ?? null,
        $rtRwLokasi,
        !empty($penyewaan->kelurahan_desa_lokasi) ? 'Kel. ' . $penyewaan->kelurahan_desa_lokasi : null,
        !empty($penyewaan->kecamatan_lokasi)      ? 'Kec. ' . $penyewaan->kecamatan_lokasi      : null,
        $penyewaan->kota_lokasi       ?? null,
        $penyewaan->provinsi_lokasi   ?? null,
        $penyewaan->kode_pos_lokasi   ?? null,
    ]);
  @endphp

  {{-- HEADER --}}
  <div class="header">
    <div class="header-left">
      @if($logoBase64)
        <img class="logo" src="{{ $logoBase64 }}" alt="Logo Museum MK Lesmana">
      @else
        <div style="height:48px;width:48px;background:#1a5c2e;display:inline-block;text-align:center;line-height:48px;">
          <span style="color:#fff;font-size:8px;font-weight:bold;">MKL</span>
        </div>
      @endif
      <div class="museum-name">Museum MK. Lesmana</div>
      <div class="museum-address">
        Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes, Kabupaten Purwakarta, Jawa Barat 41175
      </div>
    </div>
    <div class="header-right">
      <div class="doc-label">Surat Perjanjian</div>
      <div class="doc-sub">
        Nomor &nbsp;&nbsp;: <span>{{ $nomorSP }}</span><br>
        Tanggal : <span>{{ $tglHariIni }}</span><br>
        Hari &nbsp;&nbsp;&nbsp;&nbsp;: <span>{{ $namaHari }}</span>
      </div>
    </div>
  </div>

  {{-- JUDUL --}}
  <div class="doc-title">
    <h1>Perjanjian Penyewaan Koleksi Lukisan</h1>
    <div class="doc-number">Nomor: {{ $nomorSP }}</div>
  </div>

  {{-- PEMBUKAAN --}}
  <div class="preamble">
    Pada hari ini, <strong>{{ $namaHari }}</strong>, tanggal <strong>{{ $tglHariIni }}</strong>, kami yang bertanda tangan di bawah ini telah sepakat untuk mengadakan dan menandatangani Perjanjian Penyewaan Koleksi Lukisan (selanjutnya disebut &ldquo;Perjanjian&rdquo;) yang dibuat melalui sistem layanan daring (website) Museum MK. Lesmana, dengan ketentuan dan syarat-syarat sebagaimana diatur di bawah ini.
  </div>

  {{-- PARA PIHAK --}}
  <div class="section-label">Identitas Para Pihak</div>
  <div class="pihak-grid">
    <div class="pihak-col">
      <div class="pihak-head">1. Pihak Pertama — Pemilik Koleksi</div>
      <table class="info-table">
        <tr><td class="lbl">Nama</td><td class="val">Museum MK. Lesmana</td></tr>
        <tr><td class="lbl">Alamat</td><td class="val-normal">Kp. Legok Barong, RT.10/RW.05, Desa Pusakamulya, Kec. Kiarapedes, Kab. Purwakarta, Jawa Barat 41175</td></tr>
        <tr><td class="lbl">Diwakili oleh</td><td class="val-normal">Pengelola Museum MK. Lesmana</td></tr>
        <tr><td class="lbl">Jabatan</td><td class="val-normal">Direktur Pengelola</td></tr>
      </table>
      <div class="pihak-footer">Selanjutnya disebut PIHAK PERTAMA</div>
    </div>
    <div class="pihak-col">
      <div class="pihak-head">2. Pihak Kedua — Penyewa</div>
      @if($penyewaan->rental_type === 'instansi')
        <table class="info-table">
          <tr><td class="lbl">Instansi</td><td class="val">{{ $penyewaan->nama_instansi ?? '-' }}</td></tr>
          <tr><td class="lbl">Jenis Instansi</td><td class="val-normal">{{ $penyewaan->jenis_instansi ?? '-' }}</td></tr>
          <tr><td class="lbl">Alamat</td><td class="val-normal">{{ $alamatInstansiLengkap }}</td></tr>
          <tr><td class="lbl">NPWP</td><td class="val-normal">{{ $penyewaan->npwp_instansi ?? '-' }}</td></tr>
          <tr><td class="lbl">PIC</td><td class="val">{{ $penyewaan->nama_pic ?? '-' }}</td></tr>
          <tr><td class="lbl">Jabatan PIC</td><td class="val-normal">{{ $penyewaan->jabatan_pic ?? '-' }}</td></tr>
          <tr><td class="lbl">NIK PIC</td><td class="val-normal">{{ $penyewaan->nik_pic ?? '-' }}</td></tr>
          <tr><td class="lbl">No. HP</td><td class="val-normal">{{ $penyewaan->hp_pic ?? '-' }}</td></tr>
          <tr><td class="lbl">Email</td><td class="val-normal">{{ $penyewaan->email_pic ?? '-' }}</td></tr>
        </table>
      @else
        <table class="info-table">
          <tr><td class="lbl">Nama</td><td class="val">{{ $penyewaan->contact_name ?? '-' }}</td></tr>
          <tr><td class="lbl">NIK</td><td class="val-normal">{{ $penyewaan->nik ?? '-' }}</td></tr>
          <tr><td class="lbl">Tempat, Tgl Lahir</td><td class="val-normal">{{ $penyewaan->tempat_lahir ?? '-' }}, {{ $penyewaan->tanggal_lahir ? $formatTanggal($penyewaan->tanggal_lahir) : '-' }}</td></tr>
          <tr><td class="lbl">Pekerjaan</td><td class="val-normal">{{ $penyewaan->pekerjaan ?? '-' }}</td></tr>
          <tr><td class="lbl">Alamat Domisili</td><td class="val-normal">{{ $alamatDomisiliLengkap }}</td></tr>
          <tr><td class="lbl">No. HP</td><td class="val-normal">{{ $penyewaan->contact_phone ?? '-' }}</td></tr>
          <tr><td class="lbl">Email</td><td class="val-normal">{{ $penyewaan->contact_email ?? '-' }}</td></tr>
        </table>
      @endif
      <div class="pihak-footer">Selanjutnya disebut PIHAK KEDUA</div>
    </div>
  </div>

  <div class="preamble" style="margin-top:0;">
    PIHAK PERTAMA dan PIHAK KEDUA (selanjutnya bersama-sama disebut &ldquo;Para Pihak&rdquo;) dengan ini menyatakan telah sepakat untuk mengikatkan diri dalam Perjanjian Penyewaan Koleksi Lukisan dengan ketentuan-ketentuan sebagai berikut:
  </div>

  <hr class="divider">

  {{-- PASAL 1 --}}
  <div class="pasal-title">Pasal 1 — Objek Penyewaan</div>
  <div class="pasal-content">
    <p>PIHAK PERTAMA dengan ini menyewakan kepada PIHAK KEDUA, dan PIHAK KEDUA dengan ini menyewa dari PIHAK PERTAMA, koleksi lukisan dengan rincian sebagai berikut:</p>
    <table class="data-table">
      <tr><th>Nama Koleksi</th><td>{{ $penyewaan->painting->title ?? '-' }}</td></tr>
      <tr><th>Kode Koleksi</th><td>P-{{ str_pad($penyewaan->painting->id ?? $penyewaan->koleksi_id ?? 0, 5, '0', STR_PAD_LEFT) }}</td></tr>
      <tr><th>Seniman</th><td>{{ $penyewaan->painting->artist ?? '-' }}</td></tr>
      <tr><th>Tahun Karya</th><td>{{ $penyewaan->painting->year_created ?? $penyewaan->painting->year ?? '-' }}</td></tr>
      <tr><th>Tarif Sewa per Hari</th><td>Rp {{ number_format($penyewaan->painting->daily_rate ?? 0, 0, ',', '.') }}</td></tr>
      <tr><th>Kondisi Awal Koleksi</th><td>{{ $penyewaan->kondisi_koleksi ?? 'Baik' }}</td></tr>
    </table>
    <p>Kondisi awal koleksi sebagaimana tercatat di atas menjadi acuan dalam pemeriksaan pengembalian koleksi pada akhir masa sewa.</p>
  </div>

  {{-- PASAL 2 --}}
  <div class="pasal-title">Pasal 2 — Jangka Waktu Penyewaan</div>
  <div class="pasal-content">
    <p>Masa sewa atas koleksi sebagaimana dimaksud dalam Pasal 1 berlangsung selama <strong>{{ $penyewaan->duration_days ?? '-' }} ({{ $penyewaan->duration_days ?? '-' }}) hari</strong>, terhitung sejak tanggal <strong>{{ $startFormatted }}</strong> sampai dengan tanggal <strong>{{ $endFormatted }}</strong>.</p>
    <p style="margin-top:3px;">Apabila PIHAK KEDUA memerlukan perpanjangan masa sewa, permohonan perpanjangan wajib diajukan kepada PIHAK PERTAMA paling lambat 3 (tiga) hari sebelum berakhirnya masa sewa, dan perpanjangan tersebut hanya berlaku setelah disetujui oleh PIHAK PERTAMA serta dituangkan dalam adendum atau perjanjian tambahan.</p>
  </div>

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- HALAMAN 2 — Pasal 3 s/d 8, page break + jeda kosong dulu --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  <div class="page-break-section">
    <div class="page-break-spacer"></div>

    {{-- PASAL 3 --}}
    <div class="pasal-title" style="margin-top:0;">Pasal 3 — Lokasi dan Penempatan Koleksi</div>
    <div class="pasal-content">
      <p>Selama masa sewa, PIHAK KEDUA wajib menempatkan dan memajang koleksi pada lokasi sebagai berikut:</p>
      <table class="data-table">
        <tr><th>Alamat Lokasi</th><td>{{ $alamatLokasiLengkap }}</td></tr>
        <tr><th>Jenis Tempat</th><td>{{ $penyewaan->jenis_tempat ?? '-' }} ({{ $penyewaan->indoor_outdoor ?? '-' }})</td></tr>
        <tr><th>Tujuan Penggunaan</th><td>{{ $penyewaan->tujuan_penyewaan ?? $penyewaan->purpose ?? '-' }}</td></tr>
      </table>
      <p>Perpindahan lokasi penempatan koleksi dari alamat tersebut di atas hanya dapat dilakukan dengan persetujuan tertulis terlebih dahulu dari PIHAK PERTAMA.</p>
    </div>

    {{-- PASAL 4 --}}
    <div class="pasal-title">Pasal 4 — Nilai Sewa dan Tata Cara Pembayaran</div>
    <div class="pasal-content">
      <p>Nilai sewa serta komponen biaya lain yang telah disepakati Para Pihak adalah sebagai berikut:</p>
      <table class="biaya-box">
        <tr><td>
          <div class="biaya-row">
            <div class="biaya-lbl">Biaya Sewa ({{ $penyewaan->duration_days ?? 0 }} hari)</div>
            <div class="biaya-val">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
          </div>
          <div class="biaya-row">
            <div class="biaya-lbl">
              Biaya Pengiriman
              @if(!empty($penyewaan->shipping_method_type))
                @if($penyewaan->shipping_method_type === 'courier' && !empty($penyewaan->courier_name))
                  ({{ $penyewaan->courier_name }})
                @elseif($penyewaan->shipping_method_type === 'manager')
                  (Pengelola)
                @endif
              @endif
            </div>
            <div class="biaya-val">{{ $ongkir === 0 ? 'Gratis' : 'Rp ' . number_format($ongkir, 0, ',', '.') }}</div>
          </div>
          <div class="biaya-row">
            <div class="biaya-lbl">Deposit Jaminan (50%)</div>
            <div class="biaya-val">Rp {{ number_format($deposit, 0, ',', '.') }}</div>
          </div>
          <div class="biaya-final">
            <div class="biaya-final-lbl">Total Pembayaran</div>
            <div class="biaya-final-val">Rp {{ number_format($total, 0, ',', '.') }}</div>
          </div>
        </td></tr>
      </table>
      <ol class="biaya-note" style="padding-left:16px;">
        <li>Pembayaran wajib dilakukan oleh PIHAK KEDUA secara penuh melalui sistem pembayaran resmi yang disediakan oleh PIHAK PERTAMA pada platform Museum MK. Lesmana sebelum koleksi dikirim atau diserahkan.</li>
        <li>Deposit Jaminan akan dikembalikan sepenuhnya kepada PIHAK KEDUA setelah koleksi diterima kembali oleh PIHAK PERTAMA dalam kondisi baik dan sesuai dengan kondisi awal sebagaimana tercatat dalam Pasal 1.</li>
        <li>Apabila terdapat kerusakan, kehilangan, atau penurunan mutu koleksi, Deposit Jaminan dapat digunakan untuk menutupi biaya perbaikan, restorasi, atau penggantian sebagaimana diatur dalam Pasal 6.</li>
      </ol>
    </div>

    {{-- PASAL 5 --}}
    <div class="pasal-title">Pasal 5 — Hak dan Kewajiban Pihak Kedua</div>
    <div class="pasal-content">
      <p>Selama masa sewa berlangsung, PIHAK KEDUA berhak menggunakan dan memajang koleksi sesuai dengan tujuan penggunaan yang telah disepakati, serta wajib:</p>
      <ul>
        <li>Menjaga keamanan, kebersihan, dan kondisi koleksi sebagaimana saat diterima;</li>
        <li>Tidak memindahkan, memindahtangankan, atau mengubah lokasi penempatan koleksi tanpa izin tertulis dari PIHAK PERTAMA;</li>
        <li>Menyediakan tempat penyimpanan dan/atau pemajangan yang aman, sesuai dengan ketentuan dalam Pasal 3;</li>
        <li>Tidak menggunakan koleksi untuk kegiatan yang bertentangan dengan hukum, kesusilaan, atau ketertiban umum;</li>
        <li>Mengembalikan koleksi kepada PIHAK PERTAMA pada akhir masa sewa dalam kondisi baik dan sesuai dengan kondisi awal.</li>
      </ul>
    </div>

    {{-- PASAL 6 --}}
    <div class="pasal-title">Pasal 6 — Tanggung Jawab atas Kerusakan dan Kehilangan</div>
    <div class="pasal-content">
      <p>PIHAK KEDUA bertanggung jawab penuh atas segala kerusakan, kehilangan, atau penurunan mutu koleksi yang terjadi selama masa sewa, sejak koleksi diterima hingga dikembalikan kepada PIHAK PERTAMA.</p>
      <p>Besaran biaya perbaikan, restorasi, atau penggantian akibat kerusakan atau kehilangan ditetapkan oleh PIHAK PERTAMA berdasarkan penilaian yang wajar, dan dapat diperhitungkan terlebih dahulu dengan Deposit Jaminan. Apabila biaya tersebut melebihi nilai Deposit Jaminan, PIHAK KEDUA wajib melunasi kekurangannya kepada PIHAK PERTAMA.</p>
    </div>

    {{-- PASAL 7 --}}
    <div class="pasal-title">Pasal 7 — Larangan</div>
    <div class="pasal-content">
      <p>Selama masa sewa, PIHAK KEDUA dilarang untuk:</p>
      <ul>
        <li>Menjual, menggadaikan, menyewakan kembali, atau memindahtangankan koleksi kepada pihak lain dengan cara apa pun;</li>
        <li>Mengubah bentuk, warna, bahan, bingkai, atau kondisi fisik koleksi dalam bentuk apa pun;</li>
        <li>Menggunakan koleksi untuk kegiatan promosi, komersial, atau kegiatan lain di luar tujuan penggunaan yang telah disepakati tanpa persetujuan tertulis dari PIHAK PERTAMA;</li>
        <li>Menggunakan koleksi dengan cara yang dapat merugikan nama baik, reputasi, atau kepentingan Museum MK. Lesmana.</li>
      </ul>
    </div>

    {{-- PASAL 8 --}}
    <div class="pasal-title">Pasal 8 — Keadaan Kahar (Force Majeure)</div>
    <div class="pasal-content">
      <p>Keadaan Kahar adalah peristiwa di luar kemampuan dan kendali wajar Para Pihak, termasuk namun tidak terbatas pada bencana alam, kebakaran, kerusuhan, peperangan, pandemi, dan kebijakan pemerintah, yang secara langsung menghambat pelaksanaan Perjanjian ini.</p>
      <p>Dalam hal terjadi Keadaan Kahar, Para Pihak dibebaskan dari tanggung jawab atas keterlambatan atau kegagalan pelaksanaan kewajiban sepanjang dapat dibuktikan secara wajar, dan Para Pihak akan menyelesaikan dampaknya secara musyawarah untuk mencapai mufakat.</p>
    </div>

  </div>{{-- end halaman 2 --}}

  {{-- ═══════════════════════════════════════════════════════════ --}}
  {{-- HALAMAN 3 — Pasal 9 s/d 10 + TTD, page break + jeda dulu --}}
  {{-- ═══════════════════════════════════════════════════════════ --}}
  <div class="page-break-section">
    <div class="page-break-spacer"></div>

    {{-- PASAL 9 --}}
    <div class="pasal-title" style="margin-top:0;">Pasal 9 — Penyelesaian Sengketa</div>
    <div class="pasal-content">
      <p>Apabila terjadi perselisihan atau perbedaan penafsiran dalam pelaksanaan Perjanjian ini, Para Pihak sepakat untuk menyelesaikannya terlebih dahulu secara musyawarah untuk mencapai mufakat.</p>
      <p>Apabila penyelesaian secara musyawarah tidak tercapai, maka Para Pihak sepakat untuk menyelesaikan perselisihan tersebut sesuai dengan ketentuan hukum yang berlaku di wilayah Republik Indonesia.</p>
    </div>

    {{-- PASAL 10 --}}
    <div class="pasal-title">Pasal 10 — Penutup</div>
    <div class="pasal-content">
      <p>Perjanjian ini dibuat dengan sebenar-benarnya dalam keadaan sadar dan tanpa paksaan dari pihak mana pun, serta mulai berlaku dan mengikat Para Pihak sejak tanggal ditandatangani.</p>
      <p>Hal-hal yang belum atau belum cukup diatur dalam Perjanjian ini akan diatur lebih lanjut oleh Para Pihak dalam bentuk adendum yang merupakan bagian tidak terpisahkan dari Perjanjian ini.</p>
    </div>

    <div class="note-box">
      <strong>Catatan Pajak:</strong> Museum MK. Lesmana belum berstatus sebagai Pengusaha Kena Pajak (PKP), sehingga atas transaksi ini tidak dikenakan Pajak Pertambahan Nilai (PPN) sesuai dengan Undang-Undang Nomor 42 Tahun 2009 tentang Pajak Pertambahan Nilai Barang dan Jasa dan Pajak Penjualan atas Barang Mewah.
    </div>

    {{-- TANDA TANGAN --}}
    <div class="section-label" style="margin-top:10px;">Tanda Tangan Para Pihak</div>
    <table class="signature-table">
      <tr>
        <td>
          <div class="sig-box">
            <div class="sig-head">Pihak Pertama</div>
            <div class="sig-sub">Tanda Tangan</div>
            @if($ttdBase64)
              <div class="sig-img-wrap">
                <img class="sig-img" src="{{ $ttdBase64 }}" alt="TTD Pengelola">
              </div>
            @else
              <div class="sig-area"></div>
            @endif
            <div class="sig-line">
              <div class="sig-name">MK Lesmana</div>
              <div class="sig-role">Pengelola Museum MK. Lesmana</div>
            </div>
          </div>
        </td>
        <td>
          <div class="sig-box pihak-dua">
            <div class="sig-head">Pihak Kedua</div>
            <div class="sig-sub">Materai &amp; Tanda Tangan</div>
            <div class="sig-area"></div>
            <div class="sig-line">
              <div class="sig-name">{{ $namaPenyewa }}</div>
              <div class="sig-role">{{ $jabatanPenyewa }}</div>
            </div>
          </div>
        </td>
      </tr>
    </table>
  </div>{{-- end halaman 3 --}}

</div>
</body>
</html>