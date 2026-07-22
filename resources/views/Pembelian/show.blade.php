<x-app-layout>
    <x-slot name="header">{{-- kosong, hero di dalam --}}</x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    @php
        $status = $pembelian->status;
        $isDamageCancellation = $pembelian->isDamageCancellation();
        $isDamageCompensation = $pembelian->isDamageCompensation();

        $statusBadgeClass = match($status) {
            'menunggu_verifikasi'            => 'st-amber',
            'menunggu_pembayaran'            => 'st-orange',
            'pembayaran_berhasil'            => 'st-emerald',
            'siap_diserahkan'                => 'st-blue',
            'dalam_pengiriman'               => 'st-sky',
            'pengecekan_kondisi'             => 'st-amber',
            'menunggu_review_kerusakan'      => 'st-orange',
            'menunggu_data_rekening'         => 'st-amber',
            'menunggu_penerimaan_koleksi'    => 'st-amber',
            'menunggu_refund_kerusakan'      => 'st-orange',
            'menunggu_konfirmasi_refund' => 'st-indigo', // badge class
            'menunggu_dokumen_serah_terima'  => 'st-slate',
            'menunggu_validasi_serah_terima' => 'st-amber',
            'diterima_pembeli'               => 'st-indigo',
            'selesai'                        => 'st-green',
            'selesai_dengan_kompensasi'       => 'st-green',
            'ditolak'                        => 'st-red',
            'dibatalkan'                     => 'st-slate',
            default                          => 'st-slate',
        };

        $statusLabel = match($status) {
            'menunggu_verifikasi'            => 'Menunggu Verifikasi',
            'menunggu_pembayaran'            => 'Menunggu Pembayaran',
            'pembayaran_berhasil'            => 'Pembayaran Berhasil',
            'siap_diserahkan'                => 'Siap Diserahkan',
            'dalam_pengiriman'               => 'Dalam Pengiriman',
            'pengecekan_kondisi'             => 'Pengecekan Kondisi',
            'menunggu_review_kerusakan'      => 'Review Kerusakan',
            'menunggu_data_rekening'         => 'Menunggu Data Rekening',
            'menunggu_penerimaan_koleksi'    => 'Menunggu Penerimaan Koleksi',
            'menunggu_refund_kerusakan'      => 'Menunggu Refund',
            'menunggu_konfirmasi_refund' => 'Menunggu Konfirmasi Refund', // label
            'menunggu_dokumen_serah_terima'  => 'Menunggu Dok. Serah Terima',
            'menunggu_validasi_serah_terima' => 'Menunggu Validasi',
            'diterima_pembeli'               => 'Diterima Pembeli',
            'selesai'                        => 'Selesai',
            'selesai_dengan_kompensasi'      => 'Selesai (Kompensasi)',
            'ditolak'                        => 'Ditolak',
            'dibatalkan'                     => 'Dibatalkan',
            default                          => ucfirst($status),
        };

        $progressStep = match($status) {
            'menunggu_verifikasi'            => 1,
            'menunggu_pembayaran'            => 2,
            'pembayaran_berhasil'            => 3,
            'siap_diserahkan'                => 3,
            'dalam_pengiriman'               => 4,
            'menunggu_dokumen_serah_terima'  => 5,
            'menunggu_validasi_serah_terima' => 5,
            'diterima_pembeli'               => 5,
            'selesai'                        => 6,
            default                          => 0,
        };

        $steps = [
            1 => ['label'=>'Verifikasi',  'icon'=>'🔍'],
            2 => ['label'=>'Pembayaran',  'icon'=>'💳'],
            3 => ['label'=>'Disiapkan',   'icon'=>'📦'],
            4 => ['label'=>'Dikirim',     'icon'=>'🚚'],
            5 => ['label'=>'Diterima',    'icon'=>'✅'],
            6 => ['label'=>'Selesai',     'icon'=>'🎉'],
        ];
    @endphp

    <style>
        :root {
            --navy:   #0b1d35; --navy-2: #142744; --blue: #1d4ed8;
            --sky:    #38bdf8; --cream:  #f2f5f9; --slate: #64748b;
            --border: #e2e8f0; --white:  #ffffff;
        }
        * { box-sizing: border-box; }
        .ps-root { font-family:'DM Sans',sans-serif; background:var(--cream); min-height:100vh; padding-bottom:4rem; }

        /* ── HERO ── */
        .ps-hero { background:linear-gradient(140deg,#0b1d35 0%,#142744 55%,#1c3a68 100%); padding:2.25rem 0; position:relative; overflow:hidden; }
        .ps-hero::before { content:''; position:absolute; top:-60px; right:-80px; width:400px; height:400px; border-radius:50%; background:radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events:none; }
        .ps-hero-inner { max-width:1100px; margin:0 auto; padding:0 2rem; position:relative; z-index:1; }
        .ps-hero-top { display:flex; align-items:flex-start; justify-content:space-between; gap:1.5rem; flex-wrap:wrap; }

        .ps-breadcrumb { display:flex; align-items:center; gap:.45rem; margin-bottom:.85rem; }
        .ps-breadcrumb a { color:rgba(255,255,255,.45); font-size:.75rem; font-weight:500; text-decoration:none; transition:color .15s; }
        .ps-breadcrumb a:hover { color:var(--sky); }
        .ps-breadcrumb-sep { color:rgba(255,255,255,.25); font-size:.7rem; }
        .ps-breadcrumb-cur { color:rgba(255,255,255,.7); font-size:.75rem; font-weight:600; }

        .ps-hero-id { font-family:'Playfair Display',serif; font-size:1.75rem; font-weight:700; color:#fff; line-height:1.2; margin:0 0 .3rem; }
        .ps-hero-title { font-size:.88rem; color:rgba(255,255,255,.55); margin:0; }

        .ps-hero-actions { display:flex; gap:.6rem; flex-wrap:wrap; align-items:flex-start; padding-top:.25rem; }
        .ps-hero-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.6rem 1.2rem; border-radius:.875rem; font-size:.8rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .18s; border:none; cursor:pointer; white-space:nowrap; }
        .ps-hero-btn svg { width:13px; height:13px; }
        .ps-hero-btn-back { background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15); color:rgba(255,255,255,.85); }
        .ps-hero-btn-back:hover { background:rgba(255,255,255,.17); }
        .ps-hero-btn-st { background:rgba(56,189,248,.15); border:1px solid rgba(56,189,248,.3); color:var(--sky); }
        .ps-hero-btn-st:hover { background:rgba(56,189,248,.25); }

        /* STATUS BADGE */
        .ps-status-badge { display:inline-flex; align-items:center; gap:.35rem; padding:.35rem 1rem; border-radius:99px; font-size:.72rem; font-weight:700; letter-spacing:.04em; margin-top:.75rem; }
        .ps-status-dot { width:6px; height:6px; border-radius:50%; }
        .st-amber  { background:rgba(251,191,36,.15);  border:1px solid rgba(251,191,36,.3);  color:#fbbf24; }
        .st-amber  .ps-status-dot { background:#fbbf24; }
        .st-orange { background:rgba(249,115,22,.15);  border:1px solid rgba(249,115,22,.3);  color:#fb923c; }
        .st-orange .ps-status-dot { background:#fb923c; }
        .st-emerald{ background:rgba(52,211,153,.15);  border:1px solid rgba(52,211,153,.3);  color:#34d399; }
        .st-emerald .ps-status-dot { background:#34d399; }
        .st-blue   { background:rgba(96,165,250,.15);  border:1px solid rgba(96,165,250,.3);  color:#60a5fa; }
        .st-blue   .ps-status-dot { background:#60a5fa; }
        .st-sky    { background:rgba(56,189,248,.15);  border:1px solid rgba(56,189,248,.3);  color:var(--sky); }
        .st-sky    .ps-status-dot { background:var(--sky); }
        .st-indigo { background:rgba(129,140,248,.15); border:1px solid rgba(129,140,248,.3); color:#818cf8; }
        .st-indigo .ps-status-dot { background:#818cf8; }
        .st-green  { background:rgba(74,222,128,.15);  border:1px solid rgba(74,222,128,.3);  color:#4ade80; }
        .st-green  .ps-status-dot { background:#4ade80; }
        .st-red    { background:rgba(248,113,113,.15); border:1px solid rgba(248,113,113,.3); color:#f87171; }
        .st-red    .ps-status-dot { background:#f87171; }
        .st-slate  { background:rgba(148,163,184,.1);  border:1px solid rgba(148,163,184,.2); color:#94a3b8; }
        .st-slate  .ps-status-dot { background:#94a3b8; }

        /* CONTENT */
        .ps-content { max-width:1100px; margin:0 auto; padding:1.75rem 2rem 0; display:grid; gap:1.25rem; }

        /* FLASH */
        .ps-flash { border-radius:.875rem; padding:.85rem 1.2rem; font-size:.83rem; font-weight:600; display:flex; align-items:center; gap:.55rem; }
        .ps-flash svg { width:16px; height:16px; flex-shrink:0; }
        .ps-flash.ok  { background:#d1fae5; border:1px solid #6ee7b7; color:#065f46; }
        .ps-flash.err { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; }

        /* CARD */
        .ps-card { background:var(--white); border:1.5px solid var(--border); border-radius:1.5rem; box-shadow:0 4px 24px rgba(11,29,53,.05); overflow:hidden; }
        .ps-card-header { padding:1.1rem 1.5rem; border-bottom:1.5px solid #f0f4f8; display:flex; align-items:center; gap:.55rem; }
        .ps-card-header-accent { width:3px; height:16px; background:linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius:99px; flex-shrink:0; }
        .ps-card-header h3 { font-size:.76rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--navy); margin:0; }
        .ps-card-body { padding:1.5rem; }

        /* PROGRESS */
        .ps-progress { display:flex; align-items:flex-start; gap:0; }
        .ps-step { display:flex; flex-direction:column; align-items:center; gap:.4rem; position:relative; z-index:1; }
        .ps-step-circle { width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.82rem; font-weight:700; flex-shrink:0; transition:all .3s; }
        .ps-step-circle.done { background:linear-gradient(135deg,#059669,#10b981); color:#fff; box-shadow:0 0 0 4px rgba(16,185,129,.12); }
        .ps-step-circle.active { background:linear-gradient(135deg,#1d4ed8,#38bdf8); color:#fff; box-shadow:0 0 0 4px rgba(29,78,216,.18); }
        .ps-step-circle.pending { background:#f1f5f9; color:#94a3b8; border:2px solid #e2e8f0; }
        .ps-step-label { font-size:.68rem; font-weight:600; text-align:center; white-space:nowrap; }
        .ps-step-label.done { color:#059669; }
        .ps-step-label.active { color:var(--blue); font-weight:700; }
        .ps-step-label.pending { color:#94a3b8; }
        .ps-step-line { flex:1; height:2px; margin:0 .25rem; margin-bottom:1.3rem; border-radius:99px; }
        .ps-step-line.done { background:linear-gradient(90deg,#10b981,#34d399); }
        .ps-step-line.pending { background:#e2e8f0; }

        /* STATUS SECTIONS */
        .ps-status-section { border-radius:1.25rem; padding:1.5rem; }
        .ps-status-section .ps-eyebrow { font-size:.67rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; margin-bottom:.5rem; }
        .ps-status-section h2 { font-family:'Playfair Display',serif; font-size:1.3rem; color:var(--navy); margin:0 0 .5rem; }
        .ps-status-section p { font-size:.84rem; line-height:1.7; color:#475569; margin:0; }

        .ps-section-verifikasi { background:#fffbeb; border:1.5px solid #fde68a; }
        .ps-section-verifikasi .ps-eyebrow { color:#d97706; }
        .ps-section-pembayaran { background:#fff7ed; border:1.5px solid #fed7aa; }
        .ps-section-pembayaran .ps-eyebrow { color:#c2410c; }
        .ps-section-bayar-berhasil { background:#f0fdf4; border:1.5px solid #bbf7d0; }
        .ps-section-bayar-berhasil .ps-eyebrow { color:#166534; }
        .ps-section-disiapkan { background:#eff6ff; border:1.5px solid #bfdbfe; }
        .ps-section-disiapkan .ps-eyebrow { color:#1d4ed8; }
        .ps-section-pengiriman { background:#f0f9ff; border:1.5px solid #bae6fd; }
        .ps-section-pengiriman .ps-eyebrow { color:#0369a1; }
        .ps-section-diterima { background:#eef2ff; border:1.5px solid #c7d2fe; }
        .ps-section-diterima .ps-eyebrow { color:#4338ca; }
        .ps-section-selesai { background:#f0fdf4; border:1.5px solid #bbf7d0; }
        .ps-section-selesai .ps-eyebrow { color:#166534; }
        .ps-section-ditolak { background:#fef2f2; border:1.5px solid #fecaca; }
        .ps-section-ditolak .ps-eyebrow { color:#dc2626; }
        .ps-section-dibatalkan { background:#f8fafc; border:1.5px solid #e2e8f0; }
        .ps-section-dibatalkan .ps-eyebrow { color:#64748b; }

        /* META GRID */
        .ps-meta-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:.875rem; margin-top:1.1rem; }
        .ps-meta-cell { background:var(--white); border:1.5px solid var(--border); border-radius:1rem; padding:.9rem 1rem; }
        .ps-meta-cell .lbl { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:.3rem; }
        .ps-meta-cell .val { font-size:.9rem; font-weight:700; color:var(--navy); }
        .ps-meta-cell.highlight { background:linear-gradient(135deg,#eff6ff,#dbeafe); border-color:#bfdbfe; }
        .ps-meta-cell.highlight .val { color:var(--blue); }
        .ps-meta-cell.success { background:linear-gradient(135deg,#f0fdf4,#dcfce7); border-color:#bbf7d0; }
        .ps-meta-cell.success .val { color:#059669; }

        /* CATATAN */
        .ps-catatan { margin-top:1rem; background:var(--white); border:1.5px solid var(--border); border-radius:1rem; padding:1rem 1.1rem; }
        .ps-catatan .lbl { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:.35rem; }
        .ps-catatan .val { font-size:.84rem; color:#334155; line-height:1.65; }

        /* ACTION BUTTONS */
        .ps-action-row { margin-top:1.1rem; display:flex; gap:.65rem; flex-wrap:wrap; }
        .ps-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.65rem 1.35rem; border-radius:.875rem; font-size:.82rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .2s; border:none; cursor:pointer; }
        .ps-btn svg { width:14px; height:14px; }
        .ps-btn-navy { background:var(--navy); color:#fff; }
        .ps-btn-navy:hover { background:var(--blue); transform:translateY(-1px); box-shadow:0 4px 14px rgba(29,78,216,.3); }
        .ps-btn-blue { background:linear-gradient(135deg,var(--blue),#2563eb); color:#fff; }
        .ps-btn-blue:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(29,78,216,.35); }
        .ps-btn-emerald { background:linear-gradient(135deg,#059669,#10b981); color:#fff; }
        .ps-btn-emerald:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(16,185,129,.3); }
        .ps-btn-green { background:linear-gradient(135deg,#16a34a,#22c55e); color:#fff; }
        .ps-btn-green:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(34,197,94,.3); }
        .ps-btn-sky { background:linear-gradient(135deg,#0284c7,var(--sky)); color:#fff; }
        .ps-btn-sky:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(56,189,248,.3); }
        .ps-btn-danger { background:transparent; border:1.5px solid #fca5a5; color:#dc2626; }
        .ps-btn-danger:hover { background:#fef2f2; }
        .ps-btn-ghost { background:transparent; border:1.5px solid var(--border); color:var(--slate); }
        .ps-btn-ghost:hover { background:#f8fafc; }

        /* INVOICE */
        .ps-invoice-bar { display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; background:linear-gradient(135deg,#eff6ff,#dbeafe); border:1.5px solid #bfdbfe; border-radius:1.1rem; padding:1rem 1.25rem; }
        .ps-invoice-bar-left { display:flex; align-items:center; gap:.75rem; }
        .ps-invoice-icon { width:36px; height:36px; background:linear-gradient(135deg,var(--blue),#38bdf8); border-radius:.65rem; display:flex; align-items:center; justify-content:center; font-size:.95rem; }
        .ps-invoice-bar-left h4 { font-size:.88rem; font-weight:700; color:var(--navy); margin:0 0 .15rem; }
        .ps-invoice-bar-left p { font-size:.73rem; color:var(--slate); margin:0; }

        /* DATA ROWS */
        .ps-data-row { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
        @media(max-width:640px){ .ps-data-row { grid-template-columns:1fr; } }
        .ps-field { }
        .ps-field .lbl { font-size:.72rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; margin-bottom:.25rem; }
        .ps-field .val { font-size:.88rem; color:var(--navy); font-weight:500; line-height:1.5; }
        .ps-input { width:100%; border:1.5px solid var(--border); border-radius:.65rem; padding:.55rem .75rem; font-size:.85rem; font-family:'DM Sans',sans-serif; }

        /* ADDRESS BOX */
        .ps-address-box { background:#f8fafc; border:1.5px solid var(--border); border-radius:1rem; padding:1.1rem; }
        .ps-address-box h4 { font-size:.76rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--navy); margin:0 0 .75rem; display:flex; align-items:center; gap:.4rem; }
        .ps-address-box h4::before { content:''; width:3px; height:12px; background:linear-gradient(180deg,var(--blue),var(--sky)); border-radius:99px; }
        .ps-address-main { font-size:.85rem; color:#334155; line-height:1.65; margin-bottom:.75rem; }
        .ps-address-chips { display:flex; gap:.5rem; flex-wrap:wrap; }
        .ps-address-chip { background:var(--white); border:1.5px solid var(--border); border-radius:.6rem; padding:.3rem .7rem; font-size:.72rem; color:var(--slate); }
        .ps-address-chip strong { color:var(--navy); }

        /* PAINTING */
        .ps-painting-grid { display:grid; grid-template-columns:220px 1fr; gap:1.25rem; align-items:start; }
        @media(max-width:640px){ .ps-painting-grid { grid-template-columns:1fr; } }
        .ps-painting-thumb { border-radius:1rem; overflow:hidden; aspect-ratio:1; background:#f1f5f9; }
        .ps-painting-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
        .ps-painting-thumb-empty { width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#b0bac6; font-size:.83rem; min-height:200px; }
        .ps-painting-fields { display:grid; grid-template-columns:1fr 1fr; gap:.875rem; }
        @media(max-width:480px){ .ps-painting-fields { grid-template-columns:1fr; } }

        /* COST CARD */
        .ps-cost-wrap { background:linear-gradient(135deg,#0b1d35,#142744); border-radius:1.25rem; padding:1.5rem; }
        .ps-cost-row { display:flex; justify-content:space-between; align-items:center; padding:.5rem 0; border-bottom:1px solid rgba(255,255,255,.07); font-size:.84rem; }
        .ps-cost-row:last-child { border-bottom:none; }
        .ps-cost-row .lbl { color:rgba(255,255,255,.55); }
        .ps-cost-row .val { font-weight:600; color:#fff; }
        .ps-cost-total { margin-top:.75rem; padding-top:.75rem; border-top:1.5px solid rgba(255,255,255,.12); display:flex; justify-content:space-between; align-items:center; }
        .ps-cost-total .lbl { font-size:.8rem; color:rgba(255,255,255,.5); font-weight:600; }
        .ps-cost-total .val { font-family:'Playfair Display',serif; font-size:1.4rem; color:#fff; }

        /* INFO BOX */
        .ps-info-box { background:rgba(56,189,248,.07); border:1px solid rgba(56,189,248,.2); border-radius:.875rem; padding:.875rem 1.1rem; margin-top:.75rem; }
        .ps-info-box p { font-size:.78rem; color:rgba(255,255,255,.65); line-height:1.65; margin:0; }
        .ps-info-box strong { color:var(--sky); }

        /* DOCS */
        .ps-doc-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:.875rem; }
        .ps-doc-card { background:#f8fafc; border:1.5px solid var(--border); border-radius:1rem; padding:1rem; }
        .ps-doc-card.has-file { border-color:#bfdbfe; background:#eff6ff; }
        .ps-doc-icon { width:36px; height:36px; border-radius:.65rem; display:flex; align-items:center; justify-content:center; font-size:1rem; margin-bottom:.65rem; }
        .ps-doc-icon.has { background:linear-gradient(135deg,#dbeafe,#bfdbfe); }
        .ps-doc-icon.none { background:#f1f5f9; }
        .ps-doc-label { font-size:.82rem; font-weight:700; color:var(--navy); margin-bottom:.2rem; }
        .ps-doc-status { font-size:.72rem; color:var(--slate); margin-bottom:.65rem; }
        .ps-doc-actions { display:flex; gap:.4rem; }
        .ps-doc-btn { display:inline-flex; align-items:center; gap:.25rem; padding:.35rem .75rem; border-radius:.55rem; font-size:.72rem; font-weight:600; text-decoration:none; transition:all .15s; }
        .ps-doc-btn-primary { background:var(--navy); color:#fff; }
        .ps-doc-btn-primary:hover { background:var(--blue); }
        .ps-doc-btn-ghost { background:var(--white); border:1.5px solid var(--border); color:var(--slate); }
        .ps-doc-btn-ghost:hover { border-color:var(--blue); color:var(--blue); }

        /* PAYMENTS TABLE */
        .ps-table { width:100%; border-collapse:collapse; font-size:.82rem; }
        .ps-table thead th { padding:.6rem 1rem; text-align:left; font-size:.67rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#94a3b8; border-bottom:1.5px solid #f0f4f8; }
        .ps-table tbody td { padding:.875rem 1rem; color:var(--navy); border-bottom:1px solid #f8fafc; vertical-align:middle; }
        .ps-table tbody tr:last-child td { border-bottom:none; }
        .ps-table tbody tr:hover td { background:#fafbff; }
        .ps-pay-badge { display:inline-flex; align-items:center; padding:.22rem .65rem; border-radius:99px; font-size:.68rem; font-weight:700; }
        .ps-pay-badge.paid { background:#d1fae5; color:#065f46; }
        .ps-pay-badge.pending { background:#fef3c7; color:#92400e; }
        .ps-pay-badge.other { background:#f1f5f9; color:#475569; }

        @media(max-width:768px){
            .ps-content { padding:1.25rem 1rem 0; }
            .ps-hero-inner { padding:0 1rem; }
        }
    </style>

    <div class="ps-root">

        {{-- ── HERO ── --}}
        <div class="ps-hero">
            <div class="ps-hero-inner">
                <div class="ps-hero-top">
                    <div>
                        <div class="ps-breadcrumb">
                            <a href="{{ route('pembelian.index') }}">Pembelian Saya</a>
                            <span class="ps-breadcrumb-sep">/</span>
                            <span class="ps-breadcrumb-cur">BLI-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h1 class="ps-hero-id">Detail Pengajuan</h1>
                        <p class="ps-hero-title">{{ $pembelian->painting->title }} &mdash; {{ $pembelian->painting->artist }}</p>
                        <div class="ps-status-badge {{ $statusBadgeClass }}">
                            <span class="ps-status-dot"></span>
                            {{ $statusLabel }}
                        </div>
                    </div>
                    <div class="ps-hero-actions">
                        <a href="{{ route('pembelian.index') }}" class="ps-hero-btn ps-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali
                        </a>
                        @if($status === 'pembayaran_berhasil')
                            <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-hero-btn ps-hero-btn-st">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                Pantau Pengiriman
                            </a>
                        @endif
                        @if(in_array($status, ['dalam_pengiriman', 'pengecekan_kondisi', 'menunggu_review_kerusakan', 'menunggu_data_rekening', 'menunggu_penerimaan_koleksi', 'menunggu_refund_kerusakan', 'menunggu_dokumen_serah_terima', 'menunggu_validasi_serah_terima', 'diterima_pembeli', 'menunggu_konfirmasi_refund', 'selesai', 'selesai_dengan_kompensasi']))
                            <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-hero-btn ps-hero-btn-st">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                                Lihat Serah Terima
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="ps-content">

            {{-- FLASH --}}
            @if(session('success'))
                <div class="ps-flash ok">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="ps-flash err">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── INVOICE BAR ── --}}
            @if($pembelian->invoice_path && $status === 'menunggu_pembayaran')
            <div class="ps-invoice-bar">
                <div class="ps-invoice-bar-left">
                    <div class="ps-invoice-icon">📄</div>
                    <div>
                        <h4>Invoice Tersedia</h4>
                        <p>No. {{ $pembelian->invoice_number }} &mdash; {{ $pembelian->invoice_generated_at?->format('d M Y') }}</p>
                    </div>
                </div>
                <a href="{{ route('pembelian.invoice', $pembelian) }}" target="_blank" class="ps-btn ps-btn-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    Lihat Invoice
                </a>
            </div>
            @endif

            {{-- ── STATUS SECTIONS ── --}}
            @if($status === 'menunggu_verifikasi')
            <div class="ps-status-section ps-section-verifikasi">
                <div class="ps-eyebrow">⏳ Menunggu Pengelola</div>
                <h2>Pengajuan Sedang Diverifikasi</h2>
                <p>Pengajuan pembelian Anda sedang ditinjau oleh pengelola museum. Anda akan mendapat notifikasi setelah diproses. Proses biasanya memakan waktu 1–3 hari kerja.</p>
                <div class="ps-action-row">
                    <form action="{{ route('pembelian.cancel', $pembelian) }}" method="POST"
                          onsubmit="return confirm('Batalkan pengajuan pembelian ini?')">
                        @csrf
                        <button type="submit" class="ps-btn ps-btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Batalkan Pengajuan
                        </button>
                    </form>
                </div>
            </div>
            @endif

            @if($status === 'menunggu_pembayaran')
            <div class="ps-status-section ps-section-pembayaran">
                <h2>Selesaikan Pembayaran Anda</h2>
                <p>Pengajuan Anda telah disetujui oleh pengelola. Silakan lanjutkan ke halaman pembayaran untuk menyelesaikan transaksi.</p>
                @if($pembelian->catatan_pengelola)
                @endif
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell highlight">
                        <div class="lbl">Total Tagihan</div>
                        <div class="val">Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Status Pembayaran</div>
                        <div class="val">{{ ucfirst($pembelian->payment_status ?? 'Belum Dibayar') }}</div>
                    </div>
                </div>
                <div class="ps-action-row">
                    <a href="{{ route('pembelian.payment', $pembelian) }}" class="ps-btn ps-btn-blue">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        Lakukan Pembayaran
                    </a>
                </div>
            </div>
            @endif

            @if($status === 'pembayaran_berhasil')
            <div class="ps-status-section ps-section-bayar-berhasil">
                <h2>Pembayaran Berhasil!</h2>
                <p>Terima kasih, pembayaran Anda telah dikonfirmasi. Pengelola sedang mempersiapkan koleksi untuk dikirimkan ke alamat Anda.</p>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Status</div>
                        <div class="val">LUNAS ✓</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Dibayar Pada</div>
                        <div class="val">{{ $pembelian->paid_at?->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Total Dibayar</div>
                        <div class="val">Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}</div>
                    </div>
                </div>

                {{-- Riwayat Transaksi Midtrans --}}
                @if($pembelian->payments->isNotEmpty())
                @php
                    $paymentMethodLabels = [
                        'credit_card'    => 'Kartu Kredit',
                        'bank_transfer'  => 'Transfer Bank',
                        'echannel'       => 'Mandiri Bill',
                        'bca_klikpay'    => 'BCA KlikPay',
                        'cimb_clicks'    => 'CIMB Clicks',
                        'danamon_online' => 'Danamon Online',
                        'gopay'          => 'GoPay',
                        'shopeepay'      => 'ShopeePay',
                        'qris'           => 'QRIS',
                        'indomaret'      => 'Indomaret',
                        'alfamart'       => 'Alfamart',
                        'akulaku'        => 'Akulaku',
                        'cstore'         => 'Convenience Store',
                    ];
                    $paymentStatusLabels = [
                        'capture'    => ['label' => 'Berhasil',    'class' => 'paid'],
                        'settlement' => ['label' => 'Berhasil',    'class' => 'paid'],
                        'pending'    => ['label' => 'Pending',     'class' => 'pending'],
                        'deny'       => ['label' => 'Ditolak',     'class' => 'other'],
                        'cancel'     => ['label' => 'Dibatalkan',  'class' => 'other'],
                        'expire'     => ['label' => 'Kedaluwarsa', 'class' => 'other'],
                        'failure'    => ['label' => 'Gagal',       'class' => 'other'],
                    ];
                @endphp
                <div class="ps-card" style="margin-top:1.1rem;">
                    <div class="ps-card-header">
                        <div class="ps-card-header-accent"></div>
                        <h3>Riwayat Pembayaran Transaksi</h3>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="ps-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Jumlah</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pembelian->payments->sortByDesc('created_at') as $pay)
                                @php
                                    $tsKey   = $pay->transaction_status ?? '';
                                    $psBadge = $paymentStatusLabels[$tsKey] ?? ['label' => ucfirst($tsKey), 'class' => 'other'];
                                    $method  = $paymentMethodLabels[$pay->payment_type ?? ''] ?? ucwords(str_replace('_', ' ', $pay->payment_type ?? '-'));
                                @endphp
                                <tr>
                                    <td style="font-family:monospace;font-size:.76rem;">{{ $pay->order_id }}</td>
                                    <td>{{ $method }}</td>
                                    <td>
                                        <span class="ps-pay-badge {{ $psBadge['class'] }}">
                                            {{ $psBadge['label'] }}
                                        </span>
                                    </td>
                                    <td style="font-weight:700;">Rp {{ number_format($pay->gross_amount, 0, ',', '.') }}</td>
                                    <td style="color:var(--slate);font-size:.78rem;">{{ $pay->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
            @endif


            @if($status === 'dalam_pengiriman')
            @if($pembelian->shipping_method_type === 'manager')
                <div class="ps-status-section ps-section-pengiriman">
                    <h2>Pantau Pengiriman Koleksi</h2>
                    <p>Koleksi sedang dalam proses untuk dikirimkan oleh pengelola museum.</p>
                    <div class="ps-action-row">
                        <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-sky">
                            Lacak &amp; Konfirmasi Penerimaan →
                        </a>
                    </div>
                </div>
            @else
                <div class="ps-status-section ps-section-pengiriman">
                    <h2>Pantau Pengiriman Koleksi</h2>
                    <p>Koleksi sedang dalam proses pengiriman via kurir.</p>
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell"><div class="lbl">Metode Pengiriman</div><div class="val">{{ $pembelian->delivery_method ?? '-' }}</div></div>
                        <div class="ps-meta-cell"><div class="lbl">No. Resi</div><div class="val" style="font-family:monospace;">{{ $pembelian->delivery_tracking_number ?? '-' }}</div></div>
                        <div class="ps-meta-cell">
                        <div class="lbl">Estimasi Tiba</div>
                        <div class="val">
                            @php
                                $estimasiTiba = null;

                                // Prioritas 1: dari delivery_scheduled_at di pembelian
                                if ($pembelian->delivery_scheduled_at) {
                                    $estimasiTiba = \Carbon\Carbon::parse($pembelian->delivery_scheduled_at)->format('d M Y');
                                }
                                // Prioritas 2: dari serahTerima jika ada
                                elseif ($pembelian->serahTerima?->delivery_scheduled_at) {
                                    $estimasiTiba = \Carbon\Carbon::parse($pembelian->serahTerima->delivery_scheduled_at)->format('d M Y');
                                }
                                // Prioritas 3: hitung dari shipped_at + courier_etd
                                elseif ($pembelian->courier_etd && $pembelian->shipped_at) {
                                    preg_match('/(\d+)/', $pembelian->courier_etd, $m);
                                    $etdDays = isset($m[1]) ? (int)$m[1] : null;
                                    if ($etdDays) {
                                        $estimasiTiba = \Carbon\Carbon::parse($pembelian->shipped_at)->addDays($etdDays)->format('d M Y');
                                    }
                                }
                                // Prioritas 4: dari serahTerima shipped_at + etd
                                elseif ($pembelian->serahTerima?->shipped_at && $pembelian->courier_etd) {
                                    preg_match('/(\d+)/', $pembelian->courier_etd, $m);
                                    $etdDays = isset($m[1]) ? (int)$m[1] : null;
                                    if ($etdDays) {
                                        $estimasiTiba = \Carbon\Carbon::parse($pembelian->serahTerima->shipped_at)->addDays($etdDays)->format('d M Y');
                                    }
                                }
                            @endphp
                            {{ $estimasiTiba ?? '-' }}
                        </div>
                    </div>               
                    </div>
                    <div class="ps-action-row">
                        <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-sky">
                            Lacak &amp; Konfirmasi Penerimaan →
                        </a>
                    </div>
                </div>
            @endif
        @endif

            @if($status === 'siap_diserahkan')
            <div class="ps-status-section ps-section-disiapkan">
                <div class="ps-eyebrow">📦 Menunggu Pengiriman</div>
                <h2>Koleksi Sedang Disiapkan</h2>
                <p>Pengelola sedang mempersiapkan koleksi untuk dikirimkan. Anda akan mendapat notifikasi saat koleksi dikirim.</p>
                <div class="ps-action-row">
                    <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-blue">
                        Lihat Detail Serah Terima →
                    </a>
                </div>
            </div>
        @endif
                    

            @if($status === 'diterima_pembeli')
            <div class="ps-status-section ps-section-diterima">
                <div class="ps-eyebrow">🎁 Hampir Selesai</div>
                <h2>Penerimaan Dikonfirmasi</h2>
                <p>Terima kasih telah mengkonfirmasi penerimaan koleksi. Pengelola sedang memproses penyelesaian transaksi dan menyiapkan sertifikat keaslian.</p>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell"><div class="lbl">Diterima Pada</div><div class="val">{{ $pembelian->received_at?->format('d M Y H:i') ?? '-' }}</div></div>
                </div>
            </div>
            @endif

            @if($status === 'pengecekan_kondisi')
            <div class="ps-status-section ps-section-pengiriman">
                <div class="ps-eyebrow">🔍 Tindakan Diperlukan</div>
                <h2>Pengecekan Kondisi Koleksi</h2>
                <p>Silakan periksa kondisi koleksi dan nyatakan apakah dalam kondisi baik atau mengalami kerusakan sebelum melanjutkan ke dokumen serah terima.</p>
                <div class="ps-action-row">
                    <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-sky">Buka Pengecekan Kondisi →</a>
                </div>
            </div>
            @endif

            @if($status === 'menunggu_review_kerusakan')
            <div class="ps-status-section ps-section-ditolak">
                <div class="ps-eyebrow">⏳ Menunggu Pengelola</div>
                <h2>Review Laporan Kerusakan</h2>
                <p>Laporan kerusakan telah dikirim. Proses dokumen serah terima ditahan sementara. Pengelola akan meninjau bukti yang Anda lampirkan.</p>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell">
                        <div class="lbl">Dilaporkan Pada</div>
                        <div class="val">{{ $pembelian->arrival_damage_reported_at?->format('d M Y, H:i') ?? '-' }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Keputusan Anda</div>
                        <div class="val" style="color:{{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#059669' : '#dc2626' }};">
                            {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? 'Ajukan Kompensasi' : 'Ajukan Pembatalan' }}
                        </div>
                    </div>
                </div>
                <div class="ps-action-row">
                    <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-sky">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                        Lihat Detail Laporan di Serah Terima
                    </a>
                </div>
            </div>
            @endif

            @if($status === 'menunggu_data_rekening')
            <div class="ps-status-section ps-section-pengiriman">
                <div class="ps-eyebrow">🏦 Tindakan Diperlukan</div>
                <h2>{{ $isDamageCancellation ? 'Kembalikan Koleksi & Isi Data Refund' : 'Isi Data Rekening untuk Refund' }}</h2>
                <p>
                    @if($isDamageCancellation)
                        Pembatalan disetujui. Kembalikan koleksi ke museum sekaligus isi data rekening refund dan ongkir pengembalian di halaman serah terima.
                    @else
                        Pengelola telah meninjau laporan kerusakan Anda. Silakan isi data rekening di halaman serah terima untuk proses refund kompensasi.
                    @endif
                </p>

                {{-- Info kompensasi yang disetujui --}}
                @if($isDamageCompensation && $pembelian->arrival_damage_compensation_amount)
                <div style="margin-top:.875rem;display:flex;flex-direction:column;gap:.65rem;">
                    <div class="ps-meta-cell" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#bbf7d0;">
                        <div class="lbl" style="color:#166534;">Kompensasi Disetujui</div>
                        <div class="val" style="color:#059669;">Rp {{ number_format($pembelian->arrival_damage_compensation_amount, 0, ',', '.') }}</div>
                    </div>
                    @if($pembelian->arrival_damage_manager_notes)
                    <div class="ps-catatan">
                        <div class="lbl">Catatan Pengelola</div>
                        <div class="val">{{ $pembelian->arrival_damage_manager_notes }}</div>
                    </div>
                    @endif
                </div>
                @endif
                <div class="ps-action-row">
                    <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-sky">
                        {{ $isDamageCancellation ? 'Proses Pengembalian di Halaman Serah Terima →' : 'Isi Data Rekening di Halaman Serah Terima →' }}
                    </a>
                </div>
            </div>
            @endif

            @if($status === 'menunggu_penerimaan_koleksi')
            <div class="ps-status-section ps-section-pengiriman">
                <div class="ps-eyebrow">📦 Pengembalian Koleksi</div>
                <h2>Menunggu Konfirmasi Museum</h2>
                <p>Data pengembalian koleksi dan rekening refund sudah terkirim. Pantau pengiriman balik di halaman serah terima. Refund akan diproses setelah pengelola mengkonfirmasi koleksi tiba di museum.</p>
                <div class="ps-action-row">
                    <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-sky">
                        Pantau Pengembalian di Halaman Serah Terima →
                    </a>
                </div>
            </div>
            @endif

            @if($status === 'menunggu_refund_kerusakan')
            <div class="ps-status-section ps-section-diterima">
                <div class="ps-eyebrow">⏳ Menunggu Transfer</div>
                <h2>{{ $isDamageCompensation ? 'Kompensasi Sedang Diproses' : 'Refund Sedang Diproses' }}</h2>
                <p>
                    @if($isDamageCompensation)
                        Data rekening telah dikirim. Pengelola akan mentransfer kompensasi dan mengunggah bukti ke sistem.
                    @elseif($isDamageCancellation && $pembelian->collection_arrived_at)
                        Koleksi sudah dikonfirmasi tiba di museum. Pengelola akan mentransfer refund (termasuk ongkir pengembalian) dan mengunggah bukti ke sistem.
                    @else
                        Data rekening telah dikirim. Pengelola akan melakukan transfer manual dan mengunggah bukti ke sistem.
                    @endif
                </p>
                @if($pembelian->refund_bank_name)
                    <div class="ps-meta-grid" style="margin-top:.75rem;">
                        <div class="ps-meta-cell"><div class="lbl">Bank</div><div class="val">{{ $pembelian->refund_bank_name }}</div></div>
                        <div class="ps-meta-cell"><div class="lbl">Rekening</div><div class="val">{{ $pembelian->refund_account_number }} a.n. {{ $pembelian->refund_account_holder }}</div></div>
                    </div>
                @endif
                <div class="ps-action-row">
                    <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-sky">
                        Lihat Detail di Halaman Serah Terima →
                    </a>
                </div>
            </div>
            @endif

            @if($status === 'menunggu_konfirmasi_refund')
            <div class="ps-status-section ps-section-diterima">
                <div class="ps-eyebrow">{{ $isDamageCompensation ? '💰 Kompensasi Telah Dikirim' : '💸 Refund Telah Dikirim' }}</div>
                <h2>{{ $isDamageCompensation ? 'Konfirmasi Penerimaan Kompensasi' : 'Konfirmasi Penerimaan Refund' }}</h2>
                <p>
                    @if($isDamageCompensation)
                        Pengelola telah mentransfer kompensasi ke rekening Anda. Silakan cek rekening dan konfirmasi penerimaan, lalu lanjutkan upload dokumen serah terima.
                    @else
                        Pengelola telah mentransfer refund ke rekening Anda. Silakan cek rekening dan konfirmasi penerimaan untuk menyelesaikan proses pembatalan.
                    @endif
                </p>
                @if($pembelian->refund_bank_name)
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell"><div class="lbl">Bank</div><div class="val">{{ $pembelian->refund_bank_name }}</div></div>
                        <div class="ps-meta-cell"><div class="lbl">No. Rekening</div><div class="val">{{ $pembelian->refund_account_number }}</div></div>
                        <div class="ps-meta-cell"><div class="lbl">Atas Nama</div><div class="val">{{ $pembelian->refund_account_holder }}</div></div>
                        @if($pembelian->refund_amount)
                        <div class="ps-meta-cell success"><div class="lbl">{{ $isDamageCompensation ? 'Nominal Kompensasi' : 'Nominal Refund' }}</div><div class="val">Rp {{ number_format($pembelian->refund_amount, 0, ',', '.') }}</div></div>
                        @endif
                    </div>
                @endif
                <div class="ps-action-row">
                    <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-navy">
                        Lihat Bukti Transfer →
                    </a>
                </div>
            </div>
            @endif

            @if($status === 'dibatalkan')
            <div class="ps-status-section ps-section-dibatalkan">
                @if($pembelian->refund_confirmed_at)
                    <div class="ps-eyebrow">✅ Pembatalan Selesai</div>
                    <h2>Proses Pembatalan Telah Selesai</h2>
                    <p>Refund telah dikonfirmasi diterima pada {{ $pembelian->refund_confirmed_at->format('d M Y H:i') }}. Terima kasih atas kepercayaan Anda.</p>
                    <div class="ps-action-row">
                        <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-emerald">
                            Lihat Detail Serah Terima →
                        </a>
                    </div>
                @else
                    <div class="ps-eyebrow">🚫 Pengajuan Dibatalkan</div>
                    <h2>Pengajuan Ini Telah Dibatalkan</h2>
                    <p>Pengajuan telah dibatalkan. Silakan ajukan pembelian baru jika Anda masih tertarik dengan koleksi ini.</p>
                    <div class="ps-action-row">
                        <a href="{{ route('gallery.show', $pembelian->painting) }}" class="ps-btn ps-btn-navy">Lihat Koleksi →</a>
                    </div>
                @endif
            </div>
            @endif

            @if($status === 'menunggu_dokumen_serah_terima')
                @if($isDamageCompensation && $pembelian->refund_confirmed_at && !$pembelian->handover_validated_at)
                    <div class="ps-status-section ps-section-selesai">
                        <div class="ps-eyebrow">✅ Kompensasi Berhasil</div>
                        <h2>Lanjut ke Dokumen Serah Terima</h2>
                        <p>Kompensasi telah dikonfirmasi diterima pada <strong>{{ $pembelian->refund_confirmed_at->format('d M Y H:i') }}</strong>. Silakan unduh dokumen serah terima, tandatangani, lalu upload kembali melalui halaman serah terima.</p>
                        @if($pembelian->refund_amount)
                        <div class="ps-meta-grid" style="margin-top:.75rem;">
                            <div class="ps-meta-cell success">
                                <div class="lbl">Nominal Kompensasi</div>
                                <div class="val">Rp {{ number_format($pembelian->refund_amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="ps-action-row">
                            <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-sky">
                                Buka Halaman Serah Terima →
                            </a>
                        </div>
                    </div>
                @elseif($pembelian->handover_validated_at)
                    {{-- Dokumen pernah ditolak, minta upload ulang --}}
                    <div class="ps-status-section ps-section-ditolak">
                        <div class="ps-eyebrow">❌ Dokumen Ditolak</div>
                        <h2>Dokumen Serah Terima Ditolak</h2>
                        <p>Dokumen serah terima yang Anda kirimkan tidak disetujui oleh pengelola. Silakan upload ulang dokumen yang sudah diperbaiki.</p>
                        @if($pembelian->handover_validation_notes)
                            <div class="ps-catatan" style="margin-top:1rem;background:#fff;border-color:#fecaca;">
                                <div class="lbl" style="color:#dc2626;">Alasan Penolakan</div>
                                <div class="val">{{ $pembelian->handover_validation_notes }}</div>
                            </div>
                        @endif
                        <div class="ps-meta-grid">
                            <div class="ps-meta-cell">
                                <div class="lbl">Ditolak Pada</div>
                                <div class="val">{{ $pembelian->handover_validated_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        <div class="ps-action-row">
                            <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-sky">
                                Upload Ulang Dokumen →
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Belum pernah upload dokumen --}}
                    <div class="ps-status-section ps-section-pengiriman">
                        <div class="ps-eyebrow">📄 Tindakan Diperlukan</div>
                        <h2>Upload Dokumen Serah Terima</h2>
                        @if(in_array($pembelian->arrival_damage_manager_decision, ['tolak_kompensasi', 'tolak_pembatalan']) && $pembelian->arrival_damage_manager_notes)
                        <div style="margin-top:.75rem;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.875rem;padding:.875rem 1rem;">
                            <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#dc2626;margin-bottom:.35rem;">
                                ❌ {{ $pembelian->arrival_damage_manager_decision === 'tolak_kompensasi' ? 'Klaim Kompensasi Ditolak' : 'Klaim Pembatalan Ditolak' }}
                            </div>
                            <div style="font-size:.84rem;color:#334155;line-height:1.65;">{{ $pembelian->arrival_damage_manager_notes }}</div>
                            <div style="font-size:.75rem;color:#7f1d1d;margin-top:.5rem;">Silakan lanjutkan proses serah terima dokumen seperti transaksi normal.</div>
                        </div>
                        @endif                        <div class="ps-action-row">
                            <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-sky">
                                Buka Halaman Serah Terima →
                            </a>
                        </div>
                    </div>
                @endif
            @endif

            @if($status === 'menunggu_validasi_serah_terima')
            <div class="ps-status-section ps-section-diterima">
                <div class="ps-eyebrow">⏳ Menunggu Validasi</div>
                <h2>Dokumen Sedang Diperiksa</h2>
                <p>Dokumen serah terima Anda sedang diperiksa oleh pengelola. Anda akan diberitahu jika disetujui atau perlu upload ulang.</p>
                <div class="ps-action-row">
                    <a href="{{ route('pembelian.serah-terima', $pembelian) }}" class="ps-btn ps-btn-sky">
                        Lihat Status Dokumen →
                    </a>
                </div>
            </div>
            @endif

            @if($status === 'selesai' || $status === 'selesai_dengan_kompensasi')
            <div class="ps-status-section ps-section-selesai">
                <div class="ps-eyebrow">🎉 Transaksi Selesai</div>
                <h2>Pembelian Telah Diselesaikan</h2>
                <p>Koleksi resmi menjadi milik Anda per {{ $pembelian->completed_at?->setTimezone('Asia/Jakarta')->translatedFormat('d M Y H:i') ?? now()->setTimezone('Asia/Jakarta')->translatedFormat('d M Y H:i') }}. Sertifikat keaslian telah diterbitkan sebagai bukti autentik koleksi Anda.</p>
            </div>

            <div class="ps-catatan" style="background:#f0fdf4;border-color:#bbf7d0;">
                <div class="lbl" style="color:#166534;">Sertifikat Keaslian</div>
                <div class="val" style="margin-bottom:.75rem;">Sertifikat keaslian telah diterbitkan oleh museum sebagai bukti autentik koleksi.</div>

                <iframe
                    src="{{ route('pembelian.serah-terima.certificate.preview', $pembelian) }}"
                    style="width:100%;height:500px;border:1.5px solid #bbf7d0;border-radius:.75rem;margin-bottom:.75rem;display:block;"
                    title="Preview Sertifikat Keaslian">
                </iframe>

                <div class="ps-action-row" style="margin-top:.5rem;">
                    <a href="{{ route('pembelian.serah-terima.certificate.preview', $pembelian) }}" target="_blank" class="ps-btn ps-btn-emerald">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                        Buka Tab Baru
                    </a>
                    <a href="{{ route('pembelian.serah-terima.certificate.download', $pembelian) }}" class="ps-btn ps-btn-green">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        Unduh Sertifikat
                    </a>
                </div>
            </div>
            @endif

            @if($status === 'ditolak')
            <div class="ps-status-section ps-section-ditolak">
                <div class="ps-eyebrow">❌ Pengajuan Ditolak</div>
                <h2>Pengajuan Tidak Disetujui</h2>
                <p>Maaf, pengajuan pembelian Anda tidak dapat disetujui. Silakan hubungi pengelola untuk informasi lebih lanjut.</p>
                @if($pembelian->catatan_pengelola)
                    <div class="ps-catatan" style="margin-top:1rem;background:#fff;border-color:#fecaca;">
                        <div class="lbl" style="color:#dc2626;">Alasan Penolakan</div>
                        <div class="val">{{ $pembelian->catatan_pengelola }}</div>
                    </div>
                @endif
            </div>
            @endif

            {{-- ── INFO PENGAJUAN ── --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Informasi Pengajuan</h3>
                </div>
                <div class="ps-card-body">
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell">
                            <div class="lbl">Nomor Pengajuan</div>
                            <div class="val" style="font-family:'Playfair Display',serif;color:var(--blue);">BLI-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Diajukan Pada</div>
                            <div class="val">{{ ($pembelian->submitted_at ?? $pembelian->created_at)->format('d M Y H:i') }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Jenis Pembeli</div>
                            <div class="val">{{ $pembelian->buyer_type === 'b2b' ? 'Instansi / Perusahaan' : 'Perorangan' }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Status Pembayaran</div>
                            <div class="val">{{ ucfirst($pembelian->payment_status ?? 'Belum Dibayar') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── DATA PEMBELI ── --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Data {{ $pembelian->buyer_type === 'b2b' ? 'Instansi / Perusahaan' : 'Pembeli' }}</h3>
                </div>
                <div class="ps-card-body" style="display:grid;gap:1.5rem;">
                    @if($pembelian->buyer_type === 'b2c')
                    <div class="ps-data-row">
                        @foreach(['Nama Lengkap'=>$pembelian->nama_lengkap,'NIK'=>$pembelian->nik,'Tempat Lahir'=>$pembelian->tempat_lahir,'Tanggal Lahir'=>$pembelian->tanggal_lahir?\Carbon\Carbon::parse($pembelian->tanggal_lahir)->format('d M Y'):'-','Jenis Kelamin'=>$pembelian->jenis_kelamin,'Pekerjaan'=>$pembelian->pekerjaan] as $lbl=>$val)
                            <div class="ps-field"><div class="lbl">{{ $lbl }}</div><div class="val">{{ $val ?? '-' }}</div></div>
                        @endforeach
                    </div>
                    <div class="ps-data-row">
                        <div class="ps-field"><div class="lbl">Nomor HP</div><div class="val">{{ $pembelian->nomor_hp }}</div></div>
                        <div class="ps-field"><div class="lbl">Email</div><div class="val">{{ $pembelian->email }}</div></div>
                    </div>
                    <div class="ps-address-box">
                        <h4>Alamat Domisili</h4>
                        <table style="width:100%;border-collapse:collapse;">
                            @foreach([
                                'Alamat Lengkap' => $pembelian->alamat_domisili,
                                'RT / RW'        => ($pembelian->dom_rt && $pembelian->dom_rw) ? ($pembelian->dom_rt . ' / ' . $pembelian->dom_rw) : null,
                                'Kel. / Desa'    => $pembelian->dom_kelurahan_desa,
                                'Kecamatan'      => $pembelian->dom_kecamatan,
                                'Kota / Kab.'    => $pembelian->dom_kota_kabupaten,
                                'Provinsi'       => $pembelian->dom_provinsi,
                                'Kode Pos'       => $pembelian->dom_kode_pos,
                            ] as $lbl => $val)
                                @if($val && $val !== '-')
                                <tr style="border-bottom:1px solid var(--border);">
                                    <td style="padding:.45rem .75rem .45rem .1rem;font-size:.72rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;width:38%;vertical-align:top;">{{ $lbl }}</td>
                                    <td style="padding:.45rem 0;font-size:.82rem;color:var(--navy);font-weight:500;line-height:1.5;">{{ $val }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                    @else
                    <div class="ps-data-row">
                        @foreach(['Nama Perusahaan'=>$pembelian->company_name,'Jenis Instansi'=>$pembelian->company_type,'Bidang Usaha'=>$pembelian->business_field,'NPWP Perusahaan'=>$pembelian->company_npwp] as $lbl=>$val)
                            <div class="ps-field"><div class="lbl">{{ $lbl }}</div><div class="val">{{ $val ?? '-' }}</div></div>
                        @endforeach
                    </div>
                    <div class="ps-address-box">
                        <h4>Alamat Perusahaan</h4>
                        <table style="width:100%;border-collapse:collapse;">
                            @foreach([
                                'Alamat Lengkap' => $pembelian->company_address,
                                'RT / RW'        => ($pembelian->company_rt && $pembelian->company_rw) ? ($pembelian->company_rt . ' / ' . $pembelian->company_rw) : null,
                                'Kel. / Desa'    => $pembelian->company_kelurahan_desa,
                                'Kecamatan'      => $pembelian->company_kecamatan,
                                'Kota / Kab.'    => $pembelian->company_city,
                                'Provinsi'       => $pembelian->company_province,
                                'Kode Pos'       => $pembelian->company_postal_code,
                            ] as $lbl => $val)
                                @if($val && $val !== '-')
                                <tr style="border-bottom:1px solid var(--border);">
                                    <td style="padding:.45rem .75rem .45rem .1rem;font-size:.72rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;width:38%;vertical-align:top;">{{ $lbl }}</td>
                                    <td style="padding:.45rem 0;font-size:.82rem;color:var(--navy);font-weight:500;line-height:1.5;">{{ $val }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                    <div class="ps-data-row">
                        @foreach(['Nama PIC'=>$pembelian->pic_name,'Jabatan PIC'=>$pembelian->pic_position,'NIK PIC'=>$pembelian->pic_nik,'Nomor HP PIC'=>$pembelian->pic_phone,'Email PIC'=>$pembelian->pic_email] as $lbl=>$val)
                            <div class="ps-field"><div class="lbl">{{ $lbl }}</div><div class="val">{{ $val ?? '-' }}</div></div>
                        @endforeach
                    </div>
                    <div class="ps-data-row">
                        <div class="ps-field"><div class="lbl">Nomor HP</div><div class="val">{{ $pembelian->nomor_hp }}</div></div>
                        <div class="ps-field"><div class="lbl">Email</div><div class="val">{{ $pembelian->email }}</div></div>
                    </div>
                    <div class="ps-address-box">
                        <h4>Alamat Domisili PIC</h4>
                        <table style="width:100%;border-collapse:collapse;">
                            @foreach([
                                'Alamat Lengkap' => $pembelian->pic_alamat_domisili,
                                'RT / RW'        => ($pembelian->pic_rt && $pembelian->pic_rw) ? ($pembelian->pic_rt . ' / ' . $pembelian->pic_rw) : null,
                                'Kel. / Desa'    => $pembelian->pic_kelurahan_desa,
                                'Kecamatan'      => $pembelian->pic_kecamatan,
                                'Kota / Kab.'    => $pembelian->pic_kota_kabupaten,
                                'Provinsi'       => $pembelian->pic_provinsi,
                                'Kode Pos'       => $pembelian->pic_kode_pos,
                            ] as $lbl => $val)
                                @if($val && $val !== '-')
                                <tr style="border-bottom:1px solid var(--border);">
                                    <td style="padding:.45rem .75rem .45rem .1rem;font-size:.72rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;width:38%;vertical-align:top;">{{ $lbl }}</td>
                                    <td style="padding:.45rem 0;font-size:.82rem;color:var(--navy);font-weight:500;line-height:1.5;">{{ $val }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                    @endif

                    <div class="ps-address-box">
                        <h4>Alamat Pengiriman</h4>
                        <table style="width:100%;border-collapse:collapse;">
                            @foreach([
                                'Alamat Lengkap' => $pembelian->alamat_pengiriman,
                                'RT / RW'        => ($pembelian->rt && $pembelian->rw) ? ($pembelian->rt . ' / ' . $pembelian->rw) : null,
                                'Kel. / Desa'    => $pembelian->kelurahan_desa,
                                'Kecamatan'      => $pembelian->kecamatan,
                                'Kota / Kab.'    => $pembelian->kota_kabupaten,
                                'Provinsi'       => $pembelian->provinsi,
                                'Kode Pos'       => $pembelian->kode_pos,
                            ] as $lbl => $val)
                                @if($val && $val !== '-')
                                <tr style="border-bottom:1px solid var(--border);">
                                    <td style="padding:.45rem .75rem .45rem .1rem;font-size:.72rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;width:38%;vertical-align:top;">{{ $lbl }}</td>
                                    <td style="padding:.45rem 0;font-size:.82rem;color:var(--navy);font-weight:500;line-height:1.5;">{{ $val }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            {{-- ── DATA KOLEKSI ── --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Detail Koleksi</h3>
                </div>
                <div class="ps-card-body">
                    <div class="ps-painting-grid">
                        <div class="ps-painting-thumb">
                            @if($pembelian->painting->image_url ?? null)
                                <img src="{{ $pembelian->painting->image_url }}" alt="{{ $pembelian->painting->title }}">
                            @else
                                <div class="ps-painting-thumb-empty">Tidak ada foto</div>
                            @endif
                        </div>
                        <div class="ps-painting-fields">
                            @foreach(['Nama Koleksi'=>$pembelian->painting->title,'Seniman'=>$pembelian->painting->artist??'-','Kategori'=>$pembelian->painting->category??'-','Media'=>$pembelian->painting->media??'-','Ukuran'=>$pembelian->painting->dimensions??'-','Tahun'=>$pembelian->painting->year_created??'-'] as $lbl=>$val)
                                <div class="ps-field"><div class="lbl">{{ $lbl }}</div><div class="val">{{ $val }}</div></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── RINGKASAN BIAYA ── --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Ringkasan Biaya</h3>
                </div>
                <div class="ps-card-body">
                    <div class="ps-cost-wrap">
                        <div class="ps-cost-row">
                            <span class="lbl">Harga Beli Koleksi</span>
                            <span class="val">Rp {{ number_format($pembelian->harga_beli, 0, ',', '.') }}</span>
                        </div>

                        @if($status === 'menunggu_verifikasi')
                            {{-- Ongkir belum ditentukan --}}
                            <div class="ps-cost-total">
                                <span class="lbl">Total Biaya Sementara</span>
                                <span class="val">Rp {{ number_format($pembelian->harga_beli, 0, ',', '.') }}</span>
                            </div>
                            <div class="ps-info-box">
                                <p><strong>ℹ️ Informasi:</strong> Harga di atas masih belum final. Ongkos kirim akan ditentukan oleh pengelola saat memverifikasi pengajuan.</p>
                            </div>
                        @else
                            {{-- Sudah diverifikasi, tampilkan breakdown lengkap --}}
                            @if($pembelian->shipping_cost !== null)
                            <div class="ps-cost-row">
                                <span class="lbl">
                                    Ongkos Kirim
                                    @if($pembelian->shipping_method_type === 'courier' && $pembelian->courier_name)
                                        ({{ $pembelian->courier_name }})
                                    @elseif($pembelian->shipping_method_type === 'manager')
                                        (Pengelola)
                                    @endif
                                </span>
                                <span class="val" style="{{ (int)$pembelian->shipping_cost === 0 ? 'color:#34d399;' : '' }}">
                                    {{ (int)$pembelian->shipping_cost === 0 ? 'Gratis' : 'Rp '.number_format($pembelian->shipping_cost, 0, ',', '.') }}
                                </span>
                            </div>
                            @endif
                            <div class="ps-cost-total">
                                <span class="lbl">Total Bayar</span>
                                <span class="val">Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="ps-info-box">
                                <p><strong>ℹ️ Informasi:</strong> Harga di atas sudah final. Tidak ada PPN atau PPh — Museum MK Lesmana belum berstatus PKP.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── DOKUMEN VERIFIKASI ── --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Dokumen Verifikasi</h3>
                </div>
                <div class="ps-card-body">
                    @php
                        $docs = $pembelian->buyer_type === 'b2b'
                            ? [['label'=>'NPWP Perusahaan','icon'=>'📋','path'=>$pembelian->upload_npwp_company],['label'=>'Surat Pembelian','icon'=>'📄','path'=>$pembelian->upload_purchase_request_letter],['label'=>'KTP PIC','icon'=>'🪪','path'=>$pembelian->upload_pic_ktp]]
                            : [['label'=>'KTP','icon'=>'🪪','path'=>$pembelian->upload_ktp],['label'=>'NPWP','icon'=>'📋','path'=>$pembelian->upload_npwp]];
                    @endphp
                    <div class="ps-doc-grid">
                        @foreach($docs as $doc)
                        <div class="ps-doc-card {{ $doc['path'] ? 'has-file' : '' }}">
                            <div class="ps-doc-icon {{ $doc['path'] ? 'has' : 'none' }}">{{ $doc['icon'] }}</div>
                            <div class="ps-doc-label">{{ $doc['label'] }}</div>
                            <div class="ps-doc-status">{{ $doc['path'] ? 'Dokumen tersedia' : 'Belum diunggah' }}</div>
                            @if($doc['path'])
                            <div class="ps-doc-actions">
                                <a href="{{ asset('storage/'.$doc['path']) }}" target="_blank" class="ps-doc-btn ps-doc-btn-primary">Preview</a>
                                <a href="{{ asset('storage/'.$doc['path']) }}" download class="ps-doc-btn ps-doc-btn-ghost">Download</a>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ── DOKUMENTASI KONDISI KOLEKSI SAAT DIKIRIM ── --}}
            @if($pembelian->dispatch_front_photo || $pembelian->dispatch_back_photo || $pembelian->dispatch_packing_photos || $pembelian->dispatch_video_path)
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Dokumentasi Kondisi Koleksi Saat Dikirim</h3>
                </div>
                <div class="ps-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

                    {{-- Foto Depan & Belakang --}}
                    @if($pembelian->dispatch_front_photo || $pembelian->dispatch_back_photo)
                    <div>
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Koleksi</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                            @if($pembelian->dispatch_front_photo)
                            <div>
                                <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Depan</div>
                                <img src="{{ asset('storage/' . $pembelian->dispatch_front_photo) }}"
                                    style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;cursor:zoom-in;"
                                    alt="Foto Depan Koleksi"
                                    onclick="openDispatchLightbox(this.src, this.alt)">
                            </div>
                            @endif
                            @if($pembelian->dispatch_back_photo)
                            <div>
                                <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Belakang</div>
                                <img src="{{ asset('storage/' . $pembelian->dispatch_back_photo) }}"
                                    style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;cursor:zoom-in;"
                                    alt="Foto Belakang Koleksi"
                                    onclick="openDispatchLightbox(this.src, this.alt)">
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                {{-- Foto Packing & Video — sebaris --}}
                @if(($pembelian->dispatch_packing_photos && count($pembelian->dispatch_packing_photos) > 0) || $pembelian->dispatch_video_path)
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                    @if($pembelian->dispatch_packing_photos && count($pembelian->dispatch_packing_photos) > 0)
                    <div>
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Packing</div>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:.6rem;">
                            @foreach($pembelian->dispatch_packing_photos as $photo)
                            <img src="{{ asset('storage/' . $photo) }}"
                                style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;height:220px;cursor:zoom-in;"
                                alt="Foto Packing"
                                onclick="openDispatchLightbox(this.src, this.alt)">
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($pembelian->dispatch_video_path)
                    <div>
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Video Kondisi Koleksi</div>
                        <video controls
                            style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);height:220px;background:#000;">
                            <source src="{{ asset('storage/' . $pembelian->dispatch_video_path) }}" type="video/mp4">
                            Browser Anda tidak mendukung pemutaran video.
                        </video>
                    </div>
                    @endif
                </div>
                @endif

                </div>
            </div>
            @endif

            {{-- Lightbox khusus dokumentasi dispatch --}}
            <div id="dispatch-lightbox-overlay" onclick="closeDispatchLightbox(event)"
                style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.85);backdrop-filter:blur(4px);align-items:center;justify-content:center;cursor:zoom-out;">
                <span onclick="closeDispatchLightbox({target:this})"
                    style="position:absolute;top:1.25rem;right:1.5rem;color:#fff;font-size:2rem;font-weight:300;cursor:pointer;line-height:1;opacity:.7;">&times;</span>
                <img id="dispatch-lightbox-img" src="" alt=""
                    style="max-width:90vw;max-height:90vh;border-radius:1rem;box-shadow:0 24px 80px rgba(0,0,0,.6);object-fit:contain;"
                    onclick="event.stopPropagation()">
            </div>
            <script>
                function openDispatchLightbox(src, alt) {
                    const ov = document.getElementById('dispatch-lightbox-overlay');
                    document.getElementById('dispatch-lightbox-img').src = src;
                    document.getElementById('dispatch-lightbox-img').alt = alt || '';
                    ov.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
                function closeDispatchLightbox(e) {
                    const ov  = document.getElementById('dispatch-lightbox-overlay');
                    const img = document.getElementById('dispatch-lightbox-img');
                    if (e.target === ov || e.target.tagName === 'SPAN') {
                        ov.style.display = 'none';
                        document.body.style.overflow = '';
                        img.src = '';
                    }
                }
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape') {
                        const ov = document.getElementById('dispatch-lightbox-overlay');
                        if (ov) { ov.style.display = 'none'; document.getElementById('dispatch-lightbox-img').src=''; document.body.style.overflow=''; }
                    }
                });
            </script>

            @include('Pembelian.partials.condition-good-documentation')
            @include('pengelola.pembelian.partials.damage-handling')
        </div>
    </div>
</x-app-layout>