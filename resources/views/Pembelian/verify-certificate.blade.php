<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verifikasi Sertifikat — Museum MK. Lesmana</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<style>
    :root {
        --navy:   #0b1d35; --navy-2: #142744; --blue: #1d4ed8;
        --sky:    #38bdf8; --cream:  #f2f5f9; --slate: #64748b;
        --border: #e2e8f0; --white:  #ffffff;
    }
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'DM Sans', sans-serif;
        background: var(--cream);
        color: var(--navy);
        min-height: 100vh;
        -webkit-font-smoothing: antialiased;
    }

    /* ── HERO ── */
    .vc-hero {
        background: linear-gradient(140deg, #0b1d35 0%, #142744 55%, #1c3a68 100%);
        padding: 2.25rem 0;
        position: relative;
        overflow: hidden;
    }
    .vc-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -80px;
        width: 400px; height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56,189,248,.07) 0%, transparent 70%);
        pointer-events: none;
    }
    .vc-hero-inner {
        max-width: 860px;
        margin: 0 auto;
        padding: 0 2rem;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .vc-museum-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.2;
        margin: 0 0 .3rem;
    }
    .vc-museum-sub {
        font-size: .82rem;
        color: rgba(255,255,255,.5);
        margin: 0;
    }
    .vc-official-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .4rem 1rem;
        border-radius: 99px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .06em;
        background: rgba(251,191,36,.12);
        border: 1px solid rgba(251,191,36,.35);
        color: #fbbf24;
        align-self: flex-start;
        margin-top: .25rem;
    }
    .vc-official-badge-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: #fbbf24;
    }

    /* ── CONTENT ── */
    .vc-content {
        max-width: 860px;
        margin: 0 auto;
        padding: 1.75rem 2rem 4rem;
        display: grid;
        gap: 1.25rem;
    }

    /* ── STATUS BANNER ── */
    .vc-status-banner {
        border-radius: 1.25rem;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: fadeUp .28s ease both;
    }
    .vc-status-banner.valid {
        background: #f0fdf4;
        border: 1.5px solid #bbf7d0;
    }
    .vc-status-banner.invalid {
        background: #fef2f2;
        border: 1.5px solid #fecaca;
    }
    .vc-status-icon {
        width: 44px; height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .vc-status-icon.valid  { background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 0 0 4px rgba(16,185,129,.12); }
    .vc-status-icon.invalid { background: linear-gradient(135deg, #dc2626, #ef4444); box-shadow: 0 0 0 4px rgba(220,38,38,.1); }
    .vc-status-icon svg { width: 20px; height: 20px; }
    .vc-status-title { font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 700; line-height: 1.2; }
    .vc-status-title.valid   { color: #166534; }
    .vc-status-title.invalid { color: #991b1b; }
    .vc-status-sub { font-size: .8rem; margin-top: .2rem; }
    .vc-status-sub.valid   { color: #059669; }
    .vc-status-sub.invalid { color: #dc2626; }

    /* ── CARD ── */
    .vc-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 1.5rem;
        box-shadow: 0 4px 24px rgba(11,29,53,.05);
        overflow: hidden;
        animation: fadeUp .28s ease both;
    }
    .vc-card:nth-of-type(2) { animation-delay: .06s; }
    .vc-card:nth-of-type(3) { animation-delay: .10s; }
    .vc-card:nth-of-type(4) { animation-delay: .14s; }

    .vc-card-header {
        padding: 1.1rem 1.5rem;
        border-bottom: 1.5px solid #f0f4f8;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
    }
    .vc-card-header-left { display: flex; align-items: center; gap: .55rem; }
    .vc-card-header-accent {
        width: 3px; height: 16px;
        background: linear-gradient(180deg, #1d4ed8, #38bdf8);
        border-radius: 99px;
        flex-shrink: 0;
    }
    .vc-card-header h3 {
        font-size: .76rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: var(--navy);
        margin: 0;
    }
    .vc-verified-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .28rem .85rem;
        border-radius: 99px;
        font-size: .7rem;
        font-weight: 700;
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }
    .vc-card-body { padding: 1.5rem; }

    /* ── KOLEKSI ── */
    .vc-painting-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 1.25rem;
        line-height: 1.3;
    }
    .vc-meta-table { width: 100%; border-collapse: collapse; }
    .vc-meta-table tr { border-bottom: 1px solid #f0f4f8; }
    .vc-meta-table tr:last-child { border-bottom: none; }
    .vc-meta-table td { padding: .6rem 0; font-size: .84rem; vertical-align: top; }
    .vc-meta-label { color: #94a3b8; font-weight: 600; width: 38%; padding-right: 1rem; font-size: .78rem; text-transform: uppercase; letter-spacing: .06em; }
    .vc-meta-value { color: var(--navy); font-weight: 600; }

    /* ── INFO GRID ── */
    .vc-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: .875rem;
    }
    .vc-info-cell {
        background: #f8fafc;
        border: 1.5px solid var(--border);
        border-radius: 1rem;
        padding: .9rem 1rem;
    }
    .vc-info-cell .lbl {
        font-size: .67rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #94a3b8;
        margin-bottom: .3rem;
    }
    .vc-info-cell .val {
        font-size: .88rem;
        font-weight: 700;
        color: var(--navy);
        line-height: 1.35;
    }
    .vc-info-cell .val.mono {
        font-family: 'DM Mono', 'Courier New', monospace;
        font-size: .8rem;
        color: var(--blue);
        font-weight: 500;
        letter-spacing: .02em;
    }

    /* ── PROVENANCE ── */
    .vc-prov-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: .9rem 1.5rem;
        font-size: .84rem;
        border-bottom: 1px solid #f0f4f8;
    }
    .vc-prov-item:last-child { border-bottom: none; }
    .vc-prov-dot {
        width: 12px; height: 12px;
        border-radius: 50%;
        border: 2px solid var(--border);
        background: var(--white);
        flex-shrink: 0;
    }
    .vc-prov-dot.active {
        background: linear-gradient(135deg, var(--blue), var(--sky));
        border-color: transparent;
        box-shadow: 0 0 0 3px rgba(29,78,216,.12);
    }
    .vc-prov-text { flex: 1; color: var(--slate); }
    .vc-prov-text strong { color: var(--navy); font-weight: 700; }
    .vc-prov-date { font-size: .75rem; color: #94a3b8; white-space: nowrap; font-weight: 600; }

    /* ── NOT FOUND ── */
    .vc-not-found {
        padding: 3.5rem 1.5rem;
        text-align: center;
    }
    .vc-not-found-icon {
        width: 64px; height: 64px;
        border-radius: 50%;
        background: #fef2f2;
        border: 1.5px solid #fecaca;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }
    .vc-not-found-icon svg { width: 28px; height: 28px; stroke: #dc2626; fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .vc-not-found-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #991b1b;
        margin-bottom: .75rem;
    }
    .vc-not-found-desc { font-size: .84rem; color: var(--slate); line-height: 1.75; }
    .vc-cert-mono {
        display: inline-block;
        font-family: monospace;
        font-size: .78rem;
        background: #f1f5f9;
        border: 1px solid var(--border);
        color: var(--slate);
        padding: .15rem .65rem;
        border-radius: .4rem;
        margin: .2rem 0;
    }
    .vc-contact-tag {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        margin-top: 1.25rem;
        font-size: .8rem;
        font-weight: 600;
        color: var(--blue);
        background: #eff6ff;
        border: 1.5px solid #bfdbfe;
        padding: .55rem 1.25rem;
        border-radius: 99px;
        text-decoration: none;
        transition: all .18s;
    }
    .vc-contact-tag:hover { background: #dbeafe; }
    .vc-contact-tag svg { width: 13px; height: 13px; stroke: var(--blue); fill: none; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }

    /* ── FOOTER ── */
    .vc-footer {
        text-align: center;
        padding-top: 1.5rem;
        border-top: 1.5px solid var(--border);
        margin-top: .5rem;
    }
    .vc-footer-brand {
        font-family: 'Playfair Display', serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: .4rem;
    }
    .vc-footer-addr { font-size: .78rem; color: #94a3b8; line-height: 1.8; margin-bottom: .875rem; }
    .vc-footer-id {
        display: inline-block;
        font-family: monospace;
        font-size: .75rem;
        color: var(--slate);
        background: #f1f5f9;
        border: 1.5px solid var(--border);
        padding: .25rem .875rem;
        border-radius: .5rem;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: none; }
    }

    @media (max-width: 600px) {
        .vc-hero-inner { padding: 0 1rem; }
        .vc-content { padding: 1.25rem 1rem 3rem; }
        .vc-info-grid { grid-template-columns: 1fr 1fr; }
        .vc-painting-title { font-size: 1.25rem; }
    }
</style>
</head>
<body>

{{-- ── HERO ── --}}
<header>
    <div class="vc-hero">
        <div class="vc-hero-inner">
            <div>
                <p class="vc-museum-sub" style="margin-bottom:.3rem;">Sistem Verifikasi Sertifikat Digital</p>
                <h1 class="vc-museum-name">Museum MK. Lesmana</h1>
                <p class="vc-museum-sub">Purwakarta, Jawa Barat</p>
            </div>
            <div class="vc-official-badge">
                <span class="vc-official-badge-dot"></span>
                Halaman Resmi
            </div>
        </div>
    </div>
</header>

{{-- ── CONTENT ── --}}
<main class="vc-content">

    @if($valid)

    {{-- STATUS VALID --}}
    <div class="vc-status-banner valid">
        <div class="vc-status-icon valid">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>
        <div>
            <div class="vc-status-title valid">Sertifikat valid &amp; asli</div>
            <div class="vc-status-sub valid">
                Diterbitkan oleh Museum MK. Lesmana
                &bull; {{ $pembelian->completed_at?->translatedFormat('d F Y') ?? '-' }}
            </div>
        </div>
    </div>

    {{-- DETAIL KOLEKSI --}}
    <div class="vc-card">
        <div class="vc-card-header">
            <div class="vc-card-header-left">
                <div class="vc-card-header-accent"></div>
                <h3>Detail Koleksi</h3>
            </div>
        </div>
        <div class="vc-card-body">
            <div class="vc-painting-title">{{ $pembelian->painting->title }}</div>
            <table class="vc-meta-table">
                <tbody>
                    @if($pembelian->painting->artist)
                    <tr>
                        <td class="vc-meta-label">Seniman</td>
                        <td class="vc-meta-value">{{ $pembelian->painting->artist }}</td>
                    </tr>
                    @endif
                    @if($pembelian->painting->media)
                    <tr>
                        <td class="vc-meta-label">Media / Teknik</td>
                        <td class="vc-meta-value">{{ $pembelian->painting->media }}</td>
                    </tr>
                    @endif
                    @if($pembelian->painting->dimensions)
                    <tr>
                        <td class="vc-meta-label">Dimensi</td>
                        <td class="vc-meta-value">{{ $pembelian->painting->dimensions }}</td>
                    </tr>
                    @endif
                    @if($pembelian->painting->year_created)
                    <tr>
                        <td class="vc-meta-label">Tahun Dibuat</td>
                        <td class="vc-meta-value">{{ $pembelian->painting->year_created }}</td>
                    </tr>
                    @endif
                    @if($pembelian->painting->category)
                    <tr>
                        <td class="vc-meta-label">Kategori</td>
                        <td class="vc-meta-value">{{ $pembelian->painting->category }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- INFORMASI SERTIFIKAT --}}
    <div class="vc-card">
        <div class="vc-card-header">
            <div class="vc-card-header-left">
                <div class="vc-card-header-accent"></div>
                <h3>Informasi Sertifikat</h3>
            </div>
            <span class="vc-verified-badge">
                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Terverifikasi
            </span>
        </div>
        <div class="vc-card-body">
            <div class="vc-info-grid">
                <div class="vc-info-cell">
                    <div class="lbl">Nomor Sertifikat</div>
                    <div class="val mono">{{ $certId }}</div>
                </div>
                <div class="vc-info-cell">
                    <div class="lbl">No. Koleksi</div>
                    <div class="val mono">{{ $pembelian->painting->collection_number ?? 'MK-' . str_pad($pembelian->painting->id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div class="vc-info-cell">
                    <div class="lbl">Pemilik Sah</div>
                    <div class="val">
                        @if($pembelian->buyer_type === 'b2c')
                            {{ $pembelian->nama_lengkap }}
                        @else
                            {{ $pembelian->company_name }}
                        @endif
                    </div>
                </div>
                <div class="vc-info-cell">
                    <div class="lbl">Tanggal Pembelian</div>
                    <div class="val">{{ $pembelian->completed_at?->translatedFormat('d F Y') ?? '-' }}</div>
                </div>
                <div class="vc-info-cell">
                    <div class="lbl">No. Invoice</div>
                    <div class="val mono">{{ $pembelian->invoice_number ?? '-' }}</div>
                </div>
                <div class="vc-info-cell">
                    <div class="lbl">Diterbitkan oleh</div>
                    <div class="val">Museum MK. Lesmana</div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIWAYAT KEPEMILIKAN --}}
    <div class="vc-card">
        <div class="vc-card-header">
            <div class="vc-card-header-left">
                <div class="vc-card-header-accent"></div>
                <h3>Riwayat Kepemilikan</h3>
            </div>
        </div>
        <div class="vc-prov-item">
            <div class="vc-prov-dot active"></div>
            <div class="vc-prov-text">
                Dibeli oleh
                <strong>
                    @if($pembelian->buyer_type === 'b2c')
                        {{ $pembelian->nama_lengkap }}
                    @else
                        {{ $pembelian->company_name }}
                    @endif
                </strong>
            </div>
            <div class="vc-prov-date">{{ $pembelian->completed_at?->translatedFormat('d M Y') ?? '-' }}</div>
        </div>
        <div class="vc-prov-item">
            <div class="vc-prov-dot"></div>
            <div class="vc-prov-text">Koleksi Museum MK. Lesmana (asal)</div>
            <div class="vc-prov-date">—</div>
        </div>
    </div>

    @else

    {{-- STATUS TIDAK VALID --}}
    <div class="vc-status-banner invalid">
        <div class="vc-status-icon invalid">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </div>
        <div>
            <div class="vc-status-title invalid">Sertifikat tidak ditemukan</div>
            <div class="vc-status-sub invalid">Nomor ini tidak terdaftar dalam sistem museum</div>
        </div>
    </div>

    <div class="vc-card">
        <div class="vc-not-found">
            <div class="vc-not-found-icon">
                <svg viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    <line x1="11" y1="8" x2="11" y2="12"/>
                    <line x1="11" y1="16" x2="11.01" y2="16"/>
                </svg>
            </div>
            <div class="vc-not-found-title">Sertifikat tidak dapat ditemukan</div>
            <div class="vc-not-found-desc">
                Nomor sertifikat <span class="vc-cert-mono">{{ $certId }}</span> tidak ditemukan<br>
                dalam basis data kami. Pastikan Anda memindai QR code<br>
                yang tercetak pada sertifikat fisik yang asli.
            </div>
            <div>
                <span class="vc-contact-tag">
                    <svg viewBox="0 0 24 24">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16z"/>
                    </svg>
                    Hubungi museum untuk konfirmasi lebih lanjut
                </span>
            </div>
        </div>
    </div>

    @endif

    {{-- FOOTER --}}
    <div class="vc-footer">
        <div class="vc-footer-brand">Museum MK. Lesmana</div>
        <p class="vc-footer-addr">
            Kp. Legok Barong, RT.10/RW.05, Pusakamulya, Kec. Kiarapedes<br>
            Kabupaten Purwakarta, Jawa Barat 41175<br>
            Halaman ini dapat diakses secara publik untuk keperluan verifikasi keaslian sertifikat.
        </p>
        <span class="vc-footer-id">{{ $certId }}</span>
    </div>

</main>
</body>
</html>