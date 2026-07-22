<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 13px;
    color: #0b1d35;
    background: #fff;
}

.page {
    padding: 18px 28px 18px 28px;
    position: relative;
}

/* ── WATERMARK ── */
.watermark {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: 100px;
    font-weight: bold;
    color: rgba(29, 78, 216, 0.04);
    letter-spacing: 10px;
    white-space: nowrap;
    pointer-events: none;
    z-index: 0;
}

/* ── BORDER DEKORATIF ── */
.outer-border {
    border: 2.5px solid #1d4ed8;
    padding: 14px;
    position: relative;
}
.inner-border {
    border: 0.8px solid #38bdf8;
    padding: 16px 20px;
}

/* ── ORNAMEN SUDUT ── */
.corner {
    position: absolute;
    width: 24px;
    height: 24px;
}
.corner-tl { top: 10px;    left: 10px;    border-top: 2.5px solid #38bdf8; border-left: 2.5px solid #38bdf8; }
.corner-tr { top: 10px;    right: 10px;   border-top: 2.5px solid #38bdf8; border-right: 2.5px solid #38bdf8; }
.corner-bl { bottom: 10px; left: 10px;    border-bottom: 2.5px solid #38bdf8; border-left: 2.5px solid #38bdf8; }
.corner-br { bottom: 10px; right: 10px;   border-bottom: 2.5px solid #38bdf8; border-right: 2.5px solid #38bdf8; }

/* ── HEADER ── */
.header {
    display: table;
    width: 100%;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 10px;
    margin-bottom: 12px;
}
.header-left  { display: table-cell; vertical-align: middle; width: 55%; }
.header-right { display: table-cell; vertical-align: middle; text-align: right; }

.museum-name {
    font-size: 16px;
    font-weight: bold;
    color: #1d4ed8;
    letter-spacing: 0.3px;
}
.museum-address {
    font-size: 10px;
    color: #64748b;
    line-height: 1.7;
    margin-top: 3px;
}
.cert-badge {
    font-size: 9px;
    font-weight: bold;
    color: #1d4ed8;
    border: 1px solid #1d4ed8;
    padding: 4px 12px;
    letter-spacing: 1px;
    text-transform: uppercase;
    display: inline-block;
    margin-bottom: 4px;
}

/* ── JUDUL TENGAH ── */
.title-section { text-align: center; margin-bottom: 10px; }
.title-eyebrow {
    font-size: 9px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 6px;
}
.title-main {
    font-size: 26px;
    font-weight: bold;
    color: #0b1d35;
    letter-spacing: 1px;
    margin-bottom: 5px;
}
.title-sub {
    font-size: 11px;
    color: #64748b;
    letter-spacing: 2.5px;
    text-transform: uppercase;
}

/* ── DIVIDER BIRU ── */
.divider-gold { display: table; width: 100%; margin: 8px 0; }
.divider-line { display: table-cell; border-top: 0.8px solid #38bdf8; vertical-align: middle; }
.divider-diamond-wrap { display: table-cell; width: 16px; text-align: center; padding: 0 6px; vertical-align: middle; }
.divider-diamond { width: 8px; height: 8px; background: #1d4ed8; display: inline-block; transform: rotate(45deg); }

/* ── HIGHLIGHT KOLEKSI ── */
.collection-highlight {
    border-left: 4px solid #1d4ed8;
    background: #f0f6ff;
    padding: 8px 12px;
    margin-bottom: 10px;
}
.collection-title {
    font-size: 18px;
    font-weight: bold;
    color: #0b1d35;
    margin-bottom: 5px;
}
.collection-meta {
    font-size: 11px;
    color: #64748b;
    line-height: 1.8;
}

/* ── LAYOUT TENGAH: FOTO + DATA GRID ── */
.mid-layout {
    display: table;
    width: 100%;
    margin-bottom: 10px;
}
.mid-photo {
    display: table-cell;
    vertical-align: top;
    width: 42%;
    padding-right: 18px;
}
.mid-photo img {
    width: 100%;
    max-height: 240px;
    object-fit: cover;
    border: 1.5px solid #bfdbfe;
    padding: 4px;
    background: #fff;
}
.mid-photo-label {
    font-size: 9px;
    color: #94a3b8;
    text-align: center;
    margin-top: 4px;
    letter-spacing: 0.5px;
}
.mid-photo-placeholder {
    width: 100%;
    height: 220px;
    background: #f0f6ff;
    border: 1.5px dashed #bfdbfe;
    display: table;
}
.mid-photo-placeholder-inner {
    display: table-cell;
    vertical-align: middle;
    text-align: center;
    font-size: 10px;
    color: #94a3b8;
}
.mid-data {
    display: table-cell;
    vertical-align: top;
    width: 58%;
}

/* ── FIELD DATA ── */
.field-wrap { margin-bottom: 10px; }
.field-label {
    font-size: 8.5px;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #94a3b8;
    font-weight: bold;
    margin-bottom: 3px;
}
.field-value {
    font-size: 12px;
    color: #0b1d35;
    font-weight: bold;
}
.field-value-accent {
    font-size: 13px;
    color: #1d4ed8;
    font-weight: bold;
    font-family: DejaVu Sans Mono, monospace;
    letter-spacing: 0.5px;
}

/* ── PERNYATAAN KEASLIAN ── */
.auth-statement {
    background: #eff6ff;
    border: 0.8px solid #bfdbfe;
    padding: 8px 12px;
    margin-bottom: 10px;
    text-align: center;
}
.auth-statement-text {
    font-size: 10.5px;
    color: #374151;
    line-height: 1.8;
}
.auth-statement-strong {
    font-weight: bold;
    color: #1d4ed8;
}

/* ── FOOTER: TTD + QR ── */
.footer-section {
    display: table;
    width: 100%;
    border-top: 0.8px solid #e2e8f0;
    padding-top: 10px;
    margin-top: 4px;
}
.footer-left  { display: table-cell; vertical-align: bottom; width: 60%; }
.footer-right { display: table-cell; vertical-align: bottom; text-align: right; width: 40%; }

.sig-label {
    font-size: 10px;
    color: #64748b;
    margin-bottom: 32px;
    line-height: 1.7;
}
.sig-img { height: 48px; width: auto; margin-bottom: 2px; }
.sig-line {
    border-top: 1px solid #0b1d35;
    padding-top: 6px;
    width: 230px;
    margin-top: 2px;
}
.sig-name { font-size: 14px; font-weight: bold; color: #0b1d35; margin-bottom: 3px; }
.sig-role  { font-size: 10px; color: #64748b; margin-top: 3px; }
.sig-date  { font-size: 9px;  color: #94a3b8; margin-top: 3px; }

.qr-block { display: inline-block; text-align: center; }
.qr-box {
    border: 0.8px solid #bfdbfe;
    padding: 8px;
    background: #fff;
    display: inline-block;
    margin-bottom: 5px;
}
.qr-box img { display: block; width: 80px; height: 80px; }
.qr-label   { font-size: 8.5px; color: #94a3b8; letter-spacing: 0.5px; }
.qr-cert-id { font-size: 8px; color: #64748b; margin-top: 2px; font-family: DejaVu Sans Mono, monospace; }

/* ── BOTTOM STRIP ── */
.bottom-strip {
    display: table;
    width: 100%;
    background: #eff6ff;
    border-top: 0.8px solid #bfdbfe;
    margin-top: 10px;
    padding: 5px 10px;
}
.strip-left  { display: table-cell; vertical-align: middle; }
.strip-right { display: table-cell; vertical-align: middle; text-align: right; }
.strip-url   { font-size: 8.5px; color: #64748b; font-family: DejaVu Sans Mono, monospace; }
.strip-badge {
    font-size: 8.5px;
    font-weight: bold;
    color: #1d4ed8;
    background: #dbeafe;
    border: 0.8px solid #1d4ed8;
    padding: 3px 10px;
    letter-spacing: 0.5px;
    display: inline-block;
}
</style>
</head>
<body>

<div class="page">
<div class="watermark">ASLI</div>

<div class="outer-border">
  <div class="corner corner-tl"></div>
  <div class="corner corner-tr"></div>
  <div class="corner corner-bl"></div>
  <div class="corner corner-br"></div>

  <div class="inner-border">

    <!-- ══ HEADER ══ -->
    <div class="header">
      <div class="header-left">
        <div class="museum-name">Museum MK. Lesmana</div>
        <div class="museum-address">
          Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes<br>
          Kabupaten Purwakarta, Jawa Barat 41175
        </div>
      </div>
      <div class="header-right">
        <div class="cert-badge">Sertifikat Resmi</div><br>
        <span style="font-size:9px; color:#94a3b8;">Diterbitkan oleh Museum MK. Lesmana</span>
      </div>
    </div>

    <!-- ══ JUDUL ══ -->
    <div class="title-section">
      <div class="title-eyebrow">Dengan ini menyatakan bahwa</div>
      <div class="title-main">Sertifikat Keaslian Koleksi</div>
      <div class="title-sub">Certificate of Authenticity</div>
    </div>

    <!-- ══ DIVIDER BIRU ══ -->
    <div class="divider-gold">
      <div class="divider-line"></div>
      <div class="divider-diamond-wrap"><div class="divider-diamond"></div></div>
      <div class="divider-line"></div>
    </div>

    <!-- ══ HIGHLIGHT KOLEKSI ══ -->
    <div class="collection-highlight">
      <div class="collection-title">{{ $pembelian->painting->title }}</div>
      <div class="collection-meta">
        @if($pembelian->painting->artist)Seniman: {{ $pembelian->painting->artist }} &nbsp;&bull;&nbsp; @endif
        @if($pembelian->painting->media)Media: {{ $pembelian->painting->media }} &nbsp;&bull;&nbsp; @endif
        @if($pembelian->painting->dimensions)Dimensi: {{ $pembelian->painting->dimensions }} @endif
        @if($pembelian->painting->year_created)<br>Tahun: {{ $pembelian->painting->year_created }} &nbsp;&bull;&nbsp; @endif
        @if($pembelian->painting->category)Kategori: {{ $pembelian->painting->category }} @endif
        @if($pembelian->painting->collection_number)<br>No. Koleksi: {{ $pembelian->painting->collection_number }} @endif
      </div>
    </div>

    <!-- ══ LAYOUT TENGAH: FOTO KIRI + DATA KANAN ══ -->
    <div class="mid-layout">

      {{-- Foto koleksi (diperbesar) --}}
      <div class="mid-photo">
        @if(isset($paintingImageBase64) && $paintingImageBase64)
          <img src="data:image/jpeg;base64,{{ $paintingImageBase64 }}" alt="Foto Koleksi">
        @else
          <div class="mid-photo-placeholder">
            <div class="mid-photo-placeholder-inner">Foto tidak tersedia</div>
          </div>
        @endif
        <div class="mid-photo-label">Foto Koleksi</div>
      </div>

      {{-- Data kanan --}}
      <div class="mid-data">

        <div class="field-wrap">
          <div class="field-label">Nomor Koleksi</div>
          <div class="field-value-accent">{{ $pembelian->painting->collection_number ?? 'MK-' . str_pad($pembelian->painting->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="field-wrap">
          <div class="field-label">Nomor Sertifikat</div>
          <div class="field-value-accent">{{ $certId }}</div>
        </div>

        @if($pembelian->invoice_number)
        <div class="field-wrap">
          <div class="field-label">Nomor Invoice</div>
          <div class="field-value-accent">{{ $pembelian->invoice_number }}</div>
        </div>
        @endif

      </div>
    </div>

    <!-- ══ PERNYATAAN KEASLIAN ══ -->
    <div class="auth-statement">
      <div class="auth-statement-text">
        <span class="auth-statement-strong">Museum MK. Lesmana</span> dengan ini menyatakan bahwa koleksi di atas adalah
        <span class="auth-statement-strong">karya asli</span> dan telah sah menjadi milik pemegang sertifikat ini.
        Dokumen ini merupakan bukti kepemilikan yang dapat diverifikasi keasliannya secara digital.
      </div>
    </div>

    <!-- ══ FOOTER: TTD + QR ══ -->
    <div class="footer-section">
      <div class="footer-left">
        <div class="sig-label">
          Purwakarta, {{ now()->translatedFormat('d F Y') }}<br>
          Seniman / Pelukis,
        </div>
        @if(isset($ttdBase64))
        <img class="sig-img" src="data:image/jpeg;base64,{{ $ttdBase64 }}" alt="TTD">
        @else
        <div style="height:40px;"></div>
        @endif
        <div class="sig-line">
          <div class="sig-name">{{ $pembelian->painting->artist ?? 'Tidak Diketahui' }}</div>
          <div class="sig-role">Seniman Koleksi</div>
          <div class="sig-date">{{ now()->translatedFormat('d F Y') }}</div>
        </div>
      </div>

      <div class="footer-right">
        <div class="qr-block">
          <div class="qr-box">
            <img src="{{ $qrCodeDataUri }}" alt="QR Code Verifikasi">
          </div>
          <div class="qr-label">Scan untuk verifikasi</div>
          <div class="qr-cert-id">{{ $certId }}</div>
        </div>
      </div>
    </div>

    <!-- ══ BOTTOM STRIP ══ -->
    <div class="bottom-strip">
      <div class="strip-left">
        <span class="strip-url">{{ url('/verify-certificate/' . $certId) }}</span>
      </div>
      <div class="strip-right">
        <span class="strip-badge">&#10003; Terverifikasi</span>
      </div>
    </div>

  </div>
</div>

</div>
</body>
</html>