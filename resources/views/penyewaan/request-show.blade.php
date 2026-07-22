<x-app-layout>
    <x-slot name="header">{{-- kosong, hero di dalam --}}</x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    @php
        $status      = $penyewaan->status ?? 'draft';
        $serahTerima = $penyewaan->serahTerima;
        $isCompensationFlow = $serahTerima && $serahTerima->isDamageCompensation();
        $isCancellationFlow = $serahTerima && $serahTerima->isArrivalDamageCancellation();
        $sisaHari    = $penyewaan->end_date
            ? (int) now()->startOfDay()->diffInDays($penyewaan->end_date->startOfDay(), false)
            : null;

        // ── Semua status termasuk fase refund/pengembalian ──
        $statusBadgeClass = match ($status) {
            'menunggu_verifikasi'            => 'st-amber',
            'menunggu_dokumen_perjanjian'    => 'st-blue',
            'verifikasi_dokumen_perjanjian'  => 'st-indigo',
            'menunggu_pembayaran'            => 'st-orange',
            'pengiriman'                     => 'st-sky',
            'siap_diserahkan'                => 'st-blue',
            'dalam_pengiriman'               => 'st-sky',
            'pengecekan_kondisi'             => 'st-amber',
            'menunggu_review_kerusakan'      => 'st-red',
            'menunggu_data_rekening'         => 'st-amber',
            'menunggu_refund_kerusakan'      => 'st-orange',
            'menunggu_penerimaan_koleksi'    => 'st-sky',            'menunggu_dokumen_serah_terima'  => 'st-indigo',
            'verifikasi_serah_terima'        => 'st-indigo',
            'aktif'                          => 'st-emerald',
            'pengembalian'                   => 'st-sky',
            'menunggu_konfirmasi_refund' => $isCompensationFlow ? 'st-emerald' : 'st-teal',
            'menunggu_ttd_pengembalian'      => 'st-indigo',
            'menunggu_pembayaran_kerusakan'  => 'st-red',
            'menunggu_konfirmasi_selesai'    => 'st-emerald',
            'selesai'                        => 'st-green',
            'ditolak'                        => 'st-red',
            'dibatalkan'                     => 'st-slate',            'condition_checking'      => 'st-amber',
            'damage_reported'         => 'st-red',
            'damage_reviewed'         => 'st-indigo', 
            default                          => 'st-slate',
        };

        $statusLabel = match ($status) {
            'menunggu_verifikasi'            => 'Menunggu Verifikasi',
            'menunggu_dokumen_perjanjian'    => 'Upload Dok. Perjanjian',
            'verifikasi_dokumen_perjanjian'  => 'Verifikasi Perjanjian',
            'menunggu_pembayaran'            => 'Menunggu Pembayaran',
            'pengiriman'                     => 'Persiapan Pengiriman',
            'siap_diserahkan'                => 'Siap Diserahkan',
            'dalam_pengiriman'               => 'Dalam Pengiriman',
            'pengecekan_kondisi'             => 'Pengecekan Kondisi',
            'menunggu_review_kerusakan'      => 'Review Kerusakan',
            'menunggu_data_rekening'         => 'Isi Data Rekening',
            'menunggu_refund_kerusakan'      => 'Proses Kompensasi',
            'menunggu_penerimaan_koleksi'    => 'Pengembalian Koleksi',
            'menunggu_dokumen_serah_terima'  => 'Upload Dok. Serah Terima',
            'verifikasi_serah_terima'        => 'Verifikasi Serah Terima',
            'aktif'                          => 'Aktif',
            'pengembalian'                   => 'Proses Pengembalian',
            'menunggu_konfirmasi_refund'     => $isCompensationFlow ? 'Konfirmasi Kompensasi' : 'Konfirmasi Refund Deposit',
            'menunggu_ttd_pengembalian'      => 'TTD Dokumen Pengembalian',
            'menunggu_pembayaran_kerusakan'  => 'Pembayaran Kerusakan',
            'menunggu_konfirmasi_selesai'    => 'Menunggu Konfirmasi Selesai',
            'selesai'                        => 'Selesai',
            'ditolak'                        => 'Ditolak',
            'dibatalkan'                     => 'Dibatalkan',            'condition_checking'      => 'Pengecekan Kondisi',
            'damage_reported'         => 'Laporan Kerusakan Dikirim',
            'damage_reviewed'         => 'Kerusakan Ditinjau Pengelola',
            'cancelled_due_to_damage' => 'Dibatalkan — Kerusakan Pengiriman',
            default                          => ucfirst(str_replace('_', ' ', $status)),
        };

        // Status refund/pengembalian lanjutan → step 7 (Selesai)
        $progressStep = match ($status) {
            'menunggu_verifikasi'                                             => 1,
            'menunggu_dokumen_perjanjian', 'verifikasi_dokumen_perjanjian'    => 2,
            'menunggu_pembayaran'                                             => 3,
            'pengiriman'                                                      => 4,
            'menunggu_dokumen_serah_terima', 'verifikasi_serah_terima'        => 5,
            'aktif'                                                           => 6,
            'pengembalian',
            'menunggu_konfirmasi_refund',
            'menunggu_ttd_pengembalian',
            'menunggu_pembayaran_kerusakan',
            'menunggu_konfirmasi_selesai',
            'selesai'                                                         => 7,            'condition_checking'      => 4,
            'damage_reported'         => 4,
            'cancelled_due_to_damage' => 0, 
            default                                                           => 0,
        };

        $steps = [
            1 => ['label' => 'Verifikasi',   'icon' => '🔍'],
            2 => ['label' => 'Perjanjian',   'icon' => '📝'],
            3 => ['label' => 'Pembayaran',   'icon' => '💳'],
            4 => ['label' => 'Pengiriman',   'icon' => '🚚'],
            5 => ['label' => 'Serah Terima', 'icon' => '✅'],
            6 => ['label' => 'Aktif',        'icon' => '🎨'],
            7 => ['label' => 'Selesai',      'icon' => '🎉'],
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
        .ps-hero-btn-primary { background:rgba(56,189,248,.15); border:1px solid rgba(56,189,248,.3); color:var(--sky); }
        .ps-hero-btn-primary:hover { background:rgba(56,189,248,.25); }

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
        .st-teal   { background: rgba(45,212,191,.15);  border: 1px solid rgba(45,212,191,.3);  color: #2dd4bf; }
        .st-teal   .ps-status-dot { background: #2dd4bf; }
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
        .ps-progress { display:flex; align-items:flex-start; gap:0; overflow-x:auto; padding-bottom:.25rem; }
        .ps-step { display:flex; flex-direction:column; align-items:center; gap:.4rem; position:relative; z-index:1; }
        .ps-step-circle { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.78rem; font-weight:700; flex-shrink:0; transition:all .3s; }
        .ps-step-circle.done { background:linear-gradient(135deg,#059669,#10b981); color:#fff; box-shadow:0 0 0 4px rgba(16,185,129,.12); }
        .ps-step-circle.active { background:linear-gradient(135deg,#1d4ed8,#38bdf8); color:#fff; box-shadow:0 0 0 4px rgba(29,78,216,.18); }
        .ps-step-circle.pending { background:#f1f5f9; color:#94a3b8; border:2px solid #e2e8f0; }
        .ps-step-label { font-size:.63rem; font-weight:600; text-align:center; white-space:nowrap; }
        .ps-step-label.done { color:#059669; }
        .ps-step-label.active { color:var(--blue); font-weight:700; }
        .ps-step-label.pending { color:#94a3b8; }
        .ps-step-line { flex:1; height:2px; margin:0 .2rem; margin-bottom:1.3rem; border-radius:99px; min-width:20px; }
        .ps-step-line.done { background:linear-gradient(90deg,#10b981,#34d399); }
        .ps-step-line.pending { background:#e2e8f0; }

        /* STATUS SECTIONS */
        .ps-status-section { border-radius:1.25rem; padding:1.5rem; }
        .ps-status-section .ps-eyebrow { font-size:.67rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; margin-bottom:.5rem; }
        .ps-status-section h2 { font-family:'Playfair Display',serif; font-size:1.3rem; color:var(--navy); margin:0 0 .5rem; }
        .ps-status-section p { font-size:.84rem; line-height:1.7; color:#475569; margin:0; }

        .ps-section-verifikasi   { background:#fffbeb; border:1.5px solid #fde68a; }
        .ps-section-verifikasi .ps-eyebrow { color:#d97706; }
        .ps-section-perjanjian   { background:#eff6ff; border:1.5px solid #bfdbfe; }
        .ps-section-perjanjian .ps-eyebrow { color:#1d4ed8; }
        .ps-section-pembayaran   { background:#fff7ed; border:1.5px solid #fed7aa; }
        .ps-section-pembayaran .ps-eyebrow { color:#c2410c; }
        .ps-section-pengiriman   { background:#f0f9ff; border:1.5px solid #bae6fd; }
        .ps-section-pengiriman .ps-eyebrow { color:#0369a1; }
        .ps-section-serahterima  { background:#eef2ff; border:1.5px solid #c7d2fe; }
        .ps-section-serahterima .ps-eyebrow { color:#4338ca; }
        .ps-section-aktif        { background:#f0fdf4; border:1.5px solid #bbf7d0; }
        .ps-section-aktif .ps-eyebrow { color:#166534; }
        .ps-section-aktif-urgent { background:#fffbeb; border:1.5px solid #fde68a; }
        .ps-section-aktif-urgent .ps-eyebrow { color:#d97706; }
        .ps-section-aktif-expired{ background:#fef2f2; border:1.5px solid #fecaca; }
        .ps-section-aktif-expired .ps-eyebrow { color:#dc2626; }
        .ps-section-pengembalian { background:#f0fdfa; border:1.5px solid #99f6e4; }
        .ps-section-pengembalian .ps-eyebrow { color:#0f766e; }
        .ps-section-selesai      { background:#f0fdf4; border:1.5px solid #bbf7d0; }
        .ps-section-selesai .ps-eyebrow { color:#166534; }
        .ps-section-ditolak      { background:#fef2f2; border:1.5px solid #fecaca; }
        .ps-section-ditolak .ps-eyebrow { color:#dc2626; }
        .ps-section-dibatalkan   { background:#f8fafc; border:1.5px solid #e2e8f0; }
        .ps-section-dibatalkan .ps-eyebrow { color:#64748b; }
        /* ── KONFIRMASI KOMPENSASI ── */
        .ps-section-konfirmasi { background:#f0fdfa; border:1.5px solid #99f6e4; }
        .ps-section-konfirmasi .ps-eyebrow { color:#0f766e; }
        .ps-section-konfirmasi h2 { color:#065f46; }
        .ps-section-konfirmasi p { color:#166534; }

        /* META GRID */
        .ps-meta-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:.875rem; margin-top:1.1rem; }
        .ps-meta-cell { background:var(--white); border:1.5px solid var(--border); border-radius:1rem; padding:.9rem 1rem; }
        .ps-meta-cell .lbl { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:.3rem; }
        .ps-meta-cell .val { font-size:.9rem; font-weight:700; color:var(--navy); }
        .ps-meta-cell.highlight { background:linear-gradient(135deg,#eff6ff,#dbeafe); border-color:#bfdbfe; }
        .ps-meta-cell.highlight .val { color:var(--blue); }
        .ps-meta-cell.success { background:linear-gradient(135deg,#f0fdf4,#dcfce7); border-color:#bbf7d0; }
        .ps-meta-cell.success .val { color:#059669; }
        .ps-meta-cell.warning { background:linear-gradient(135deg,#fffbeb,#fef3c7); border-color:#fde68a; }
        .ps-meta-cell.warning .val { color:#d97706; }
        .ps-meta-cell.danger { background:linear-gradient(135deg,#fef2f2,#fee2e2); border-color:#fecaca; }
        .ps-meta-cell.danger .val { color:#dc2626; }

        /* CATATAN */
        .ps-catatan { margin-top:1rem; background:var(--white); border:1.5px solid var(--border); border-radius:1rem; padding:1rem 1.1rem; }
        .ps-catatan .lbl { font-size:.67rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin-bottom:.35rem; }
        .ps-catatan .val { font-size:.84rem; color:#334155; line-height:1.65; }

        /* ACTION BUTTONS */
        .ps-action-row { margin-top:1.1rem; display:flex; gap:.65rem; flex-wrap:wrap; align-items:center; }
        .ps-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.65rem 1.35rem; border-radius:.875rem; font-size:.82rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .2s; border:none; cursor:pointer; }
        .ps-btn svg { width:14px; height:14px; }
        .ps-btn-navy    { background:var(--navy); color:#fff; }
        .ps-btn-navy:hover { background:var(--blue); transform:translateY(-1px); box-shadow:0 4px 14px rgba(29,78,216,.3); }
        .ps-btn-blue    { background:linear-gradient(135deg,var(--blue),#2563eb); color:#fff; }
        .ps-btn-blue:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(29,78,216,.35); }
        .ps-btn-emerald { background:linear-gradient(135deg,#059669,#10b981); color:#fff; }
        .ps-btn-emerald:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(16,185,129,.3); }
        .ps-btn-sky     { background:linear-gradient(135deg,#0284c7,var(--sky)); color:#fff; }
        .ps-btn-sky:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(56,189,248,.3); }
        .ps-btn-teal    { background:linear-gradient(135deg,#0f766e,#14b8a6); color:#fff; }
        .ps-btn-teal:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(20,184,166,.3); }
        .ps-btn-orange  { background:linear-gradient(135deg,#ea580c,#f97316); color:#fff; }
        .ps-btn-orange:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(249,115,22,.3); }
        .ps-btn-danger  { background:transparent; border:1.5px solid #fca5a5; color:#dc2626; }
        .ps-btn-danger:hover { background:#fef2f2; }
        .ps-btn-ghost   { background:transparent; border:1.5px solid var(--border); color:var(--slate); }
        .ps-btn-ghost:hover { background:#f8fafc; }

        /* UPLOAD FORM */
        .ps-upload-box { margin-top:1rem; background:var(--white); border:1.5px solid var(--border); border-radius:1.1rem; padding:1.25rem; }
        .ps-upload-box p.title { font-size:.85rem; font-weight:700; color:var(--navy); margin:0 0 .3rem; }
        .ps-upload-box p.sub { font-size:.75rem; color:var(--slate); margin:0 0 .85rem; }
        .ps-upload-box input[type=file] { width:100%; border-radius:.75rem; border:1.5px solid var(--border); background:#f8fafc; padding:.65rem .9rem; font-size:.82rem; font-family:'DM Sans',sans-serif; color:var(--navy); }
        .ps-upload-box .ps-error { font-size:.72rem; color:#dc2626; margin:.25rem 0 0; }

        /* DOWNLOAD ROW */
        .ps-download-row { display:flex; align-items:center; justify-content:space-between; gap:1rem; background:#f8fafc; border:1.5px solid var(--border); border-radius:.875rem; padding:.85rem 1.1rem; margin-bottom:.75rem; flex-wrap:wrap; }
        .ps-download-row .info p { font-size:.8rem; font-weight:700; color:var(--navy); margin:0 0 .15rem; }
        .ps-download-row .info span { font-size:.72rem; color:var(--slate); }

        /* DATA ROWS */
        .ps-data-row { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
        @media(max-width:640px){ .ps-data-row { grid-template-columns:1fr; } }
        .ps-field .lbl { font-size:.72rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.07em; margin-bottom:.25rem; }
        .ps-field .val { font-size:.88rem; color:var(--navy); font-weight:500; line-height:1.5; }

        /* ADDRESS BOX */
        .ps-address-box { background:#f8fafc; border:1.5px solid var(--border); border-radius:1rem; padding:1.1rem; }
        .ps-address-box h4 { font-size:.76rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--navy); margin:0 0 .75rem; display:flex; align-items:center; gap:.4rem; }
        .ps-address-box h4::before { content:''; width:3px; height:12px; background:linear-gradient(180deg,var(--blue),var(--sky)); border-radius:99px; }
        .ps-address-main { font-size:.85rem; color:#334155; line-height:1.65; margin-bottom:.75rem; }
        .ps-address-chips { display:flex; gap:.5rem; flex-wrap:wrap; }
        .ps-address-chip { background:var(--white); border:1.5px solid var(--border); border-radius:.6rem; padding:.3rem .7rem; font-size:.72rem; color:var(--slate); }
        .ps-address-chip strong { color:var(--navy); }

        /* PAINTING GRID */
        .ps-painting-grid { display:grid; grid-template-columns:200px 1fr; gap:1.25rem; align-items:start; }
        @media(max-width:640px){ .ps-painting-grid { grid-template-columns:1fr; } }
        .ps-painting-thumb { border-radius:1rem; overflow:hidden; aspect-ratio:1; background:#f1f5f9; }
        .ps-painting-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
        .ps-painting-thumb-empty { width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#b0bac6; font-size:.83rem; min-height:180px; }
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
        .ps-info-box { background:rgba(56,189,248,.07); border:1px solid rgba(56,189,248,.2); border-radius:.875rem; padding:.875rem 1.1rem; margin-top:.75rem; }
        .ps-info-box p { font-size:.78rem; color:rgba(255,255,255,.65); line-height:1.65; margin:0; }
        .ps-info-box strong { color:var(--sky); }

        /* DOCS */
        .ps-doc-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(190px,1fr)); gap:.875rem; }
        .ps-doc-card { background:#f8fafc; border:1.5px solid var(--border); border-radius:1rem; padding:1rem; }
        .ps-doc-card.has-file { border-color:#bfdbfe; background:#eff6ff; }
        .ps-doc-icon { width:36px; height:36px; border-radius:.65rem; display:flex; align-items:center; justify-content:center; font-size:1rem; margin-bottom:.65rem; }
        .ps-doc-icon.has  { background:linear-gradient(135deg,#dbeafe,#bfdbfe); }
        .ps-doc-icon.none { background:#f1f5f9; }
        .ps-doc-label  { font-size:.82rem; font-weight:700; color:var(--navy); margin-bottom:.2rem; }
        .ps-doc-status { font-size:.72rem; color:var(--slate); margin-bottom:.65rem; }
        .ps-doc-actions { display:flex; gap:.4rem; }
        .ps-doc-btn { display:inline-flex; align-items:center; gap:.25rem; padding:.35rem .75rem; border-radius:.55rem; font-size:.72rem; font-weight:600; text-decoration:none; transition:all .15s; }
        .ps-doc-btn-primary { background:var(--navy); color:#fff; }
        .ps-doc-btn-primary:hover { background:var(--blue); }
        .ps-doc-btn-ghost { background:var(--white); border:1.5px solid var(--border); color:var(--slate); }
        .ps-doc-btn-ghost:hover { border-color:var(--blue); color:var(--blue); }

        /* STEP CARD (pengembalian) */
        .ps-step-card { background:var(--white); border:1.5px solid var(--border); border-radius:1rem; padding:1rem 1.1rem; display:flex; align-items:flex-start; gap:.75rem; }
        .ps-step-card .ps-step-icon { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.8rem; flex-shrink:0; }
        .ps-step-card .ps-step-icon.ok     { background:#d1fae5; }
        .ps-step-card .ps-step-icon.wait   { background:#fef3c7; }
        .ps-step-card .ps-step-icon.err    { background:#fee2e2; }
        .ps-step-card .ps-step-title { font-size:.83rem; font-weight:700; color:var(--navy); margin:0 0 .15rem; }
        .ps-step-card .ps-step-sub   { font-size:.74rem; color:var(--slate); margin:0; }

        @media(max-width:768px){
            .ps-content { padding:1.25rem 1rem 0; }
            .ps-hero-inner { padding:0 1rem; }
            .ps-progress { gap:0; }
        }
    </style>

    <div class="ps-root">

        {{-- ── HERO ── --}}
        <div class="ps-hero">
            <div class="ps-hero-inner">
                <div class="ps-hero-top">
                    <div>
                        <div class="ps-breadcrumb">
                            <a href="{{ route('penyewaan.requests') }}">Pengajuan Saya</a>
                            <span class="ps-breadcrumb-sep">/</span>
                            <span class="ps-breadcrumb-cur">SW-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h1 class="ps-hero-id">Detail Pengajuan Penyewaan</h1>
                        <p class="ps-hero-title">
                            {{ $penyewaan->painting->title }}
                            @if($penyewaan->painting->artist)
                                &mdash; {{ $penyewaan->painting->artist }}
                            @endif
                        </p>
                        <div class="ps-status-badge {{ $statusBadgeClass }}">
                            <span class="ps-status-dot"></span>
                            {{ $statusLabel }}
                        </div>
                    </div>
                    <div class="ps-hero-actions">
                        <a href="{{ route('penyewaan.requests') }}" class="ps-hero-btn ps-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali
                        </a>
                        @if(in_array($status, [
                            'pengiriman','siap_diserahkan','dalam_pengiriman','pengecekan_kondisi',
                            'menunggu_data_rekening','menunggu_refund_kerusakan',
                            'menunggu_dokumen_serah_terima','verifikasi_serah_terima',
                            'aktif','pengembalian','menunggu_penerimaan_koleksi',
                            'menunggu_konfirmasi_refund','menunggu_ttd_pengembalian',
                            'menunggu_pembayaran_kerusakan','menunggu_konfirmasi_selesai',
                            'selesai','menunggu_review_kerusakan'
                        ]) && Route::has('penyewaan.requests.handover.show'))
                            <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-hero-btn ps-hero-btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                                Detail Serah Terima
                            </a>
                        @endif
                        @if($status === 'menunggu_pembayaran' && $penyewaan->payment_status !== 'paid')
                            <a href="{{ route('penyewaan.requests.payment', ['penyewaan' => $penyewaan->id]) }}" class="ps-hero-btn ps-hero-btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                                Bayar Sekarang
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

            {{-- ════════════════════════════
                 STATUS SECTIONS
            ════════════════════════════ --}}

            @php
                $hsNow = $serahTerima?->handover_status;

                $showDamageReviewedCard   = in_array($status, ['menunggu_review_kerusakan', 'menunggu_data_rekening', 'menunggu_penerimaan_koleksi', 'menunggu_refund_kerusakan'], true) && $hsNow === 'damage_reviewed';
                $showDamageReportedCard   = ($status === 'menunggu_review_kerusakan' && $hsNow === 'damage_reported');
                $showConditionCheckingCard= ($status === 'pengecekan_kondisi' || $hsNow === 'condition_checking');
                $showInDeliveryCard       = ($status === 'dalam_pengiriman' && $hsNow === 'in_delivery');
                $showSiapDiserahkanCard   = in_array($status, ['siap_diserahkan'], true)
                    || ($status === 'pengiriman' && $hsNow === 'preparing_delivery');
                $showPersiapanPengirimanCard = ($status === 'pengiriman' && in_array($hsNow, ['waiting_handover', null], true));

                $showGenericInlineCard    = !$showDamageReviewedCard
                                        && !$showDamageReportedCard
                                        && !$showConditionCheckingCard
                                        && !$showInDeliveryCard
                                        && !$showSiapDiserahkanCard
                                        && !$showPersiapanPengirimanCard
                                        && !$isCancellationFlow;
                $showReviewKerusakanCard  = ($status === 'menunggu_review_kerusakan') && !$showDamageReportedCard;
            @endphp

            {{-- menunggu_verifikasi --}}
            @if($status === 'menunggu_verifikasi')
            <div class="ps-status-section ps-section-verifikasi">
                <div class="ps-eyebrow">⏳ Menunggu Pengelola</div>
                <h2>Pengajuan Sedang Diverifikasi</h2>
                <p>Pengelola sedang memeriksa kelengkapan data dan dokumen Anda. Anda akan mendapat notifikasi setelah proses verifikasi selesai.</p>
                <div class="ps-action-row">
                    <form action="{{ route('penyewaan.requests.cancel', $penyewaan) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                        @csrf
                        <button type="submit" class="ps-btn ps-btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Batalkan Pengajuan
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- ditolak --}}
            @if($status === 'ditolak')
            <div class="ps-status-section ps-section-ditolak">
                <div class="ps-eyebrow">❌ Pengajuan Ditolak</div>
                <h2>Pengajuan Tidak Disetujui</h2>
                <p>Maaf, pengajuan penyewaan Anda tidak dapat disetujui. Silakan hubungi pengelola untuk informasi lebih lanjut.</p>
                @if($penyewaan->rejection_reason ?? $penyewaan->verification_notes ?? null)
                    <div class="ps-catatan" style="background:#fff;border-color:#fecaca;">
                        <div class="lbl" style="color:#dc2626;">Alasan Penolakan</div>
                        <div class="val">{{ $penyewaan->rejection_reason ?? $penyewaan->verification_notes }}</div>
                    </div>
                @endif
            </div>
            @endif

            {{-- dibatalkan --}}
            @if($status === 'dibatalkan' && !($serahTerima?->isArrivalDamageCancellation() && $serahTerima?->handover_status !== 'returned'))
            <div class="ps-status-section ps-section-dibatalkan">
                <div class="ps-eyebrow">🚫 Pengajuan Dibatalkan</div>
                <h2>Pengajuan Ini Telah Dibatalkan</h2>
                <p>Tidak ada aksi lebih lanjut yang diperlukan. Silakan ajukan penyewaan baru jika Anda masih tertarik.</p>
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.index') }}" class="ps-btn ps-btn-navy">Lihat Katalog →</a>
                </div>
            </div>
            @endif

            {{-- dibatalkan karena kerusakan saat pengiriman — proses pengembalian + refund --}}
            @if(in_array($status, ['dibatalkan', 'menunggu_penerimaan_koleksi']) && $serahTerima?->isArrivalDamageCancellation() && $serahTerima?->handover_status !== 'returned')
            @php
                $shipmentDikirim = $serahTerima->return_shipment_submitted_at;
                $koleksiTiba    = $serahTerima->collection_arrived_at;
                $refundDone     = $serahTerima->refund_confirmed_at;
            @endphp
            <div class="ps-status-section ps-section-dibatalkan">
                <div class="ps-eyebrow">🚫 Dibatalkan — Kerusakan Pengiriman</div>
                <h2>Penyewaan Dibatalkan karena Kerusakan</h2>
                <p>
                    Pengelola menyetujui pembatalan. Kembalikan koleksi ke museum.
                    Biaya sewa + deposit akan dikembalikan penuh (ongkir tidak dikembalikan).
                </p>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell {{ $shipmentDikirim ? 'success' : 'warning' }}">
                        <div class="lbl">Info Pengiriman Balik</div>
                        <div class="val">{{ $shipmentDikirim ? 'Sudah Diisi ✓' : 'Belum Diisi' }}</div>
                    </div>
                    <div class="ps-meta-cell {{ $koleksiTiba ? 'success' : '' }}">
                        <div class="lbl">Koleksi Tiba di Museum</div>
                        <div class="val">{{ $koleksiTiba ? 'Sudah Dikonfirmasi ✓' : 'Menunggu' }}</div>
                    </div>
                    @if($koleksiTiba)
                    <div class="ps-meta-cell {{ $penyewaan->depositRefund ? 'success' : 'warning' }}">
                        <div class="lbl">Refund</div>
                        <div class="val">{{ $refundDone ? 'Selesai ✓' : ($penyewaan->depositRefund ? 'Menunggu Konfirmasi' : 'Diproses Pengelola') }}</div>
                    </div>
                    @endif
                </div>
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-teal">
                        {{ $shipmentDikirim ? 'Lihat Proses Pengembalian →' : 'Isi Data Pengembalian Koleksi →' }}
                    </a>
                </div>
            </div>
            @endif

            {{-- menunggu_konfirmasi_refund karena pembatalan kerusakan --}}
            @if($status === 'menunggu_konfirmasi_refund' && $serahTerima?->isDamageCancellation())
            @php $hasArrivalRefundProof = $serahTerima?->refund_transfer_proof_path; @endphp
            <div class="ps-status-section" style="background:{{ $hasArrivalRefundProof ? '#f0fdfa' : '#fffbeb' }};border:1.5px solid {{ $hasArrivalRefundProof ? '#99f6e4' : '#fde68a' }};">
                <div class="ps-eyebrow">{{ $hasArrivalRefundProof ? '⚡ Aksi Diperlukan' : '⏳ Menunggu Pengelola' }}</div>
                <h2>{{ $hasArrivalRefundProof ? 'Konfirmasi Penerimaan Refund' : 'Menunggu Proses Refund' }}</h2>
                <p>{{ $hasArrivalRefundProof
                    ? 'Pengelola telah mentransfer refund biaya sewa + deposit. Konfirmasi bahwa dana sudah diterima.'
                    : 'Koleksi sudah diterima kembali. Pengelola sedang memproses pengembalian biaya sewa + deposit.' }}</p>
                @if($hasArrivalRefundProof)
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-emerald">
                        Konfirmasi Refund →
                    </a>
                </div>
                @else
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-ghost">
                        Lihat Detail →
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- menunggu_dokumen_perjanjian --}}
            @if($status === 'menunggu_dokumen_perjanjian')
            @php $docDitolak = $penyewaan->signed_agreement_status === 'rejected'; @endphp
            <div class="ps-status-section {{ $docDitolak ? 'ps-section-ditolak' : 'ps-section-perjanjian' }}">
                <div class="ps-eyebrow">{{ $docDitolak ? '❌ Dokumen Ditolak' : '📄 Aksi Diperlukan' }}</div>
                <h2>{{ $docDitolak ? 'Dokumen Perjanjian Anda Ditolak' : 'Upload Dokumen Perjanjian' }}</h2>
                <p>
                    @if($docDitolak)
                        Dokumen yang Anda unggah sebelumnya <strong>ditolak</strong> oleh pengelola.
                        Periksa catatan di bawah, perbaiki dokumen, lalu upload ulang.
                    @else
                        Pengajuan Anda disetujui. Unduh dokumen perjanjian, tanda tangani dan tambahkan e-materai, lalu upload kembali.
                    @endif
                </p>

                {{-- Catatan penolakan — hanya tampil jika ditolak --}}
                @if($docDitolak)
                <div class="ps-catatan" style="background:#fff;border-color:#fecaca;margin-top:1rem;">
                    <div class="lbl" style="color:#dc2626;">📋 Catatan dari Pengelola</div>
                    <div class="val">
                        {{ $penyewaan->signed_agreement_review_notes
                            ?? 'Pengelola tidak memberikan catatan spesifik. Pastikan dokumen sudah ditandatangani dengan benar dan menggunakan e-materai.' }}
                    </div>
                </div>
                @endif

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:.875rem;margin-top:1rem;">
                    @if($penyewaan->agreement_document_path)
                    <div class="ps-download-row" style="margin-bottom:0;">
                        <div class="info">
                            <p>Surat Perjanjian</p>
                            <span>{{ $docDitolak ? 'Unduh ulang, perbaiki, lalu tanda tangani kembali' : 'Unduh, tanda tangani dengan e-materai' }}</span>
                        </div>
                        <a href="{{ route('penyewaan.requests.agreement.download', $penyewaan) }}"
                        class="ps-btn ps-btn-navy" style="flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            Unduh
                        </a>
                    </div>
                    @endif
                    @if(!$docDitolak && $penyewaan->invoice_document_path)
                    <div class="ps-download-row" style="margin-bottom:0;">
                        <div class="info">
                            <p>Invoice Pembayaran</p>
                            <span>Unduh sebagai referensi biaya</span>
                        </div>
                        <a href="{{ route('penyewaan.requests.invoice.download', ['penyewaan' => $penyewaan->id]) }}"
                        class="ps-btn ps-btn-sky" style="flex-shrink:0;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            Unduh
                        </a>
                    </div>
                    @endif
                </div>

                @if(in_array($penyewaan->signed_agreement_status ?? 'pending', ['pending', 'rejected', null]))
                <form action="{{ route('penyewaan.requests.signedAgreement.upload', $penyewaan) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="ps-upload-box" style="margin-top:1rem;{{ $docDitolak ? 'border-color:#fca5a5;background:#fff8f8;' : '' }}">
                        <p class="title">{{ $docDitolak ? '🔄 Upload Ulang Dokumen yang Sudah Diperbaiki' : 'Upload Dokumen yang Sudah Ditandatangani' }}</p>
                        <p class="sub">Format: PDF Maksimal 10MB.</p>
                        @error('signed_agreement')
                            <p class="ps-error">{{ $message }}</p>
                        @enderror
                        <input type="file" name="signed_agreement" accept=".pdf,.doc,.docx" required>
                        <div class="ps-action-row" style="margin-top:.85rem;">
                            <button type="submit" class="ps-btn ps-btn-blue">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                {{ $docDitolak ? 'Upload Ulang Dokumen Perjanjian' : 'Upload Dokumen Perjanjian' }}
                            </button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
            @endif

            {{-- verifikasi_dokumen_perjanjian --}}
            @if($status === 'verifikasi_dokumen_perjanjian')
            <div class="ps-status-section ps-section-serahterima">
                <div class="ps-eyebrow">⏳ Menunggu Pengelola</div>
                <h2>Dokumen Perjanjian Sedang Diverifikasi</h2>
                <p>Dokumen perjanjian Anda sedang diperiksa oleh pengelola. Anda akan diberitahu setelah proses verifikasi selesai.</p>
            </div>
            @endif

            {{-- menunggu_pembayaran --}}
            @if($status === 'menunggu_pembayaran')
            <div class="ps-status-section ps-section-pembayaran">
                <div class="ps-eyebrow">💳 Aksi Diperlukan</div>
                <h2>Selesaikan Pembayaran</h2>
                <p>Dokumen perjanjian telah disetujui. Silakan lanjutkan ke pembayaran untuk mengaktifkan penyewaan Anda.</p>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Status Perjanjian</div>
                        <div class="val">Disetujui ✓</div>
                    </div>
                    <div class="ps-meta-cell highlight">
                        <div class="lbl">Status Pembayaran</div>
                        <div class="val">
                            @php
                                echo match($penyewaan->payment_status) {
                                    'pending' => '⏳ Menunggu Konfirmasi',
                                    'paid'    => '✅ Lunas',
                                    'failed'  => '❌ Gagal',
                                    'expired' => '⌛ Kedaluwarsa',
                                    default   => 'Belum Dibayar',
                                };
                            @endphp
                        </div>
                    </div>
                    @if($penyewaan->payment_reference)
                    <div class="ps-meta-cell">
                        <div class="lbl">Referensi</div>
                        <div class="val" style="font-family:monospace;font-size:.8rem;">{{ $penyewaan->payment_reference }}</div>
                    </div>
                    @endif
                </div>
                @if($penyewaan->payment_status !== 'paid')
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.payment', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-orange">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        Lakukan Pembayaran
                    </a>
                </div>
                @endif

                {{-- Tombol invoice --}}
                @if($penyewaan->invoice_document_path)
                <div class="ps-download-row" style="margin-top:1rem;">
                    <div class="info">
                        <p>Invoice Pembayaran</p>
                        <span>Unduh sebagai referensi biaya sebelum pembayaran</span>
                    </div>
                    <a href="{{ route('penyewaan.requests.invoice.download', ['penyewaan' => $penyewaan->id]) }}" 
                       class="ps-btn ps-btn-sky" style="flex-shrink:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                        </svg>
                        Unduh Invoice
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- persiapan pengiriman — menunggu pengelola isi info --}}
            @if($showPersiapanPengirimanCard)
            <div class="ps-status-section ps-section-pengiriman">
                <div class="ps-eyebrow">✅ Pembayaran Berhasil</div>
                <h2>Menunggu Pengiriman Koleksi</h2>
                <p>Pembayaran Anda telah diterima. Pengelola sedang mempersiapkan koleksi untuk dikirimkan ke alamat Anda.</p>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Pembayaran</div>
                        <div class="val">LUNAS ✓</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Metode Pengiriman</div>
                        <div class="val">{{ $penyewaan->shipping_method_label }}</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- siap diserahkan — info pengiriman sudah diisi --}}
            @if($showSiapDiserahkanCard)
            <div class="ps-status-section ps-section-pengiriman">
                <div class="ps-eyebrow">📦 Menunggu Pengiriman</div>
                <h2>Koleksi Sedang Disiapkan</h2>
                <p>Pengelola sedang mempersiapkan koleksi untuk dikirimkan. Anda akan mendapat notifikasi saat koleksi dikirim.</p>
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-blue">
                        Lihat Detail Serah Terima →
                    </a>
                </div>
            </div>
            @endif

            {{-- Status khusus: menunggu data rekening untuk kompensasi / pembatalan --}}
            @if($status === 'menunggu_data_rekening')
            @php
                $isCompensation = $serahTerima?->isDamageCompensation();
                $isCancellation = $serahTerima?->isArrivalDamageCancellation();
            @endphp
            <div class="ps-status-section {{ $isCompensation ? 'ps-section-serahterima' : 'ps-section-pengiriman' }}">
                <div class="ps-eyebrow">{{ $isCompensation ? '💰 Aksi Diperlukan' : '📦 Aksi Diperlukan' }}</div>
                <h2>{{ $isCompensation ? 'Isi Data Rekening untuk Kompensasi' : 'Isi Data Pengembalian dan Data Rekening untuk Pengembalian' }}</h2>
                <p>
                    @if($isCompensation)
                        Pengelola sudah menyetujui kompensasi sebesar
                        <strong>Rp {{ number_format($serahTerima?->arrival_damage_compensation_amount ?? 0, 0, ',', '.') }}</strong>.
                        Lengkapi data rekening di halaman serah terima agar transfer kompensasi dapat diproses.
                        Proses penyewaan akan dilanjutkan tanpa pengembalian koleksi ke museum.
                    @elseif($isCancellation)
                        Pengelola sudah menyetujui pembatalan. Lengkapi data rekening serta informasi pengembalian koleksi di halaman serah terima.
                    @else
                        Pengelola sedang menunggu data rekening Anda untuk proses refund.
                    @endif
                </p>
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-sky">
                        Buka Halaman Serah Terima →
                    </a>
                </div>
            </div>
            @endif

            {{-- Status khusus: menunggu proses transfer kompensasi / refund --}}
            @if($status === 'menunggu_refund_kerusakan')
            <div class="ps-status-section ps-section-pengiriman">
                <div class="ps-eyebrow">⏳ Menunggu Pengelola</div>
                <h2>{{ $serahTerima?->isDamageCompensation() ? 'Transfer Kompensasi Sedang Diproses' : 'Refund Sedang Diproses' }}</h2>
                <p>
                    @if($serahTerima?->isDamageCompensation())
                        Data rekening sudah Anda kirim. Pengelola sedang menyiapkan transfer kompensasi.
                        Setelah bukti transfer terunggah, Anda akan diminta mengonfirmasi penerimaan dan
                        melanjutkan ke proses unduh serta upload dokumen serah terima.
                    @else
                        Data rekening sudah Anda kirim. Pengelola sedang memproses refund manual dan
                        akan mengunggah bukti transfer setelah selesai.
                    @endif
                </p>
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-sky">
                        {{ $serahTerima?->isDamageCompensation() ? 'Lihat Detail Kompensasi →' : 'Lihat Detail Proses →' }}
                    </a>
                </div>
            </div>
            @endif

            {{-- Status khusus: konfirmasi penerimaan kompensasi kerusakan --}}
            @if($status === 'menunggu_konfirmasi_refund' && $isCompensationFlow)
            @php $hasCompProof = $serahTerima?->refund_transfer_proof_path; @endphp
            <div class="ps-status-section {{ $hasCompProof ? 'ps-section-konfirmasi' : 'ps-section-pengiriman' }}">
                <div class="ps-eyebrow">{{ $hasCompProof ? '⚡ Aksi Diperlukan' : '⏳ Menunggu Pengelola' }}</div>
                <h2>{{ $hasCompProof ? 'Konfirmasi Penerimaan Kompensasi' : 'Menunggu Transfer Kompensasi' }}</h2>
                <p>
                    @if($hasCompProof)
                        Pengelola telah mentransfer kompensasi ke rekening Anda.
                        Periksa saldo rekening dan konfirmasi bahwa dana sudah diterima.
                        Setelah konfirmasi, Anda akan diarahkan untuk melanjutkan upload dokumen serah terima.
                    @else
                        Data rekening sudah Anda kirim. Pengelola sedang memproses transfer kompensasi.
                    @endif
                </p>
                @if($hasCompProof && $serahTerima?->refund_amount)
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Nominal Kompensasi</div>
                        <div class="val">Rp {{ number_format($serahTerima->refund_amount, 0, ',', '.') }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Bank Tujuan</div>
                        <div class="val">{{ $serahTerima->refund_bank_name }} — {{ $serahTerima->refund_account_number }}</div>
                    </div>
                </div>
                @endif
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn {{ $hasCompProof ? 'ps-btn-emerald' : 'ps-btn-sky' }}">
                        {{ $hasCompProof ? 'Konfirmasi Kompensasi →' : 'Lihat Detail Kompensasi →' }}
                    </a>
                </div>
            </div>
            @endif

            {{-- ── Status: pengecekan kondisi (alur baru, inline di serah terima) ── --}}
            @if($status === 'menunggu_penerimaan_koleksi' && !$isCompensationFlow && !$isCancellationFlow)
                <div class="ps-status-section ps-section-pengiriman">
                    <div class="ps-eyebrow">⚡ Aksi Diperlukan</div>
                    <h2>Proses Pengecekan Kondisi / Kerusakan</h2>
                    <p>Lanjutkan proses pengecekan kondisi atau penanganan kerusakan di halaman serah terima.</p>
                    <div class="ps-action-row">
                        <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-sky">
                            Buka Halaman Serah Terima →
                        </a>
                    </div>
                </div>
            @endif

            {{-- menunggu_review_kerusakan — versi ringkasan generik.
                 Hanya tampil jika card "Laporan Kerusakan Sudah Terkirim" (damage_reported)
                 di bawah TIDAK menampilkan informasi untuk kombinasi status yang sama,
                 supaya tidak duplikat. --}}
            @if($showReviewKerusakanCard)
            <div class="ps-status-section" style="background:#fffbeb;border:1.5px solid #fde68a;">
                <div class="ps-eyebrow" style="color:#d97706;">⏳ Menunggu Pengelola</div>
                <h2>Laporan Kerusakan Sedang Ditinjau</h2>
                <p>
                    Laporan kerusakan dan bukti yang Anda kirimkan sedang ditinjau oleh pengelola
                    pada <strong>{{ $serahTerima?->arrival_damage_reported_at?->format('d M Y, H:i') ?? '-' }}</strong>.
                    Anda akan mendapat notifikasi setelah pengelola memutuskan.
                </p>

                @php
                    $checkedItems = collect($serahTerima?->arrival_damage_items ?? [])
                        ->filter(fn($item) => !empty($item['checked']))
                        ->pluck('label');
                    $decLabel = match($serahTerima?->arrival_damage_buyer_decision) {
                        'lanjut'   => '✅ Ajukan Kompensasi',
                        'batalkan' => '❌ Ajukan Pembatalan',
                        default    => '-',
                    };
                @endphp

                <div class="ps-meta-grid">
                    <div class="ps-meta-cell warning">
                        <div class="lbl">Keputusan Anda</div>
                        <div class="val">{{ $decLabel }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Dilaporkan Pada</div>
                        <div class="val">{{ $serahTerima?->arrival_damage_reported_at?->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                </div>

                @if($checkedItems->isNotEmpty())
                <div style="margin-top:1rem;display:flex;gap:.4rem;flex-wrap:wrap;">
                    @foreach($checkedItems as $item)
                        <span style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:.6rem;
                                    padding:.3rem .75rem;font-size:.76rem;font-weight:600;color:#92400e;">
                            ⚠ {{ $item }}
                        </span>
                    @endforeach
                </div>
                @endif

                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}"
                    class="ps-btn ps-btn-sky">
                        Lihat Detail Serah Terima →
                    </a>
                </div>
            </div>
            @endif

            {{-- ── Status: condition_checking (legacy) ── --}}
            @if($showConditionCheckingCard)
            <div class="ps-status-section ps-section-pengiriman">
                <div class="ps-eyebrow">⚡ Aksi Diperlukan</div>
                <h2>Periksa Kondisi Koleksi</h2>
                <p>
                    Koleksi telah kamu terima. Sebelum melanjutkan ke dokumen serah terima,
                    periksa kondisi koleksi terlebih dahulu — apakah baik-baik saja atau ada kerusakan.
                </p>
            
                {{-- Info pengiriman singkat --}}
                @if($serahTerima)
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Diterima Pada</div>
                        <div class="val">{{ $serahTerima->confirmed_received_at?->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Metode Pengiriman</div>
                        <div class="val">
                            @if($penyewaan->shipping_method_type === 'courier')
                                Kurir — {{ $serahTerima->delivery_method ?? '-' }}
                            @else
                                Pengelola Museum
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}"
                    class="ps-btn ps-btn-sky">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                        Mulai Pengecekan Kondisi Koleksi →
                    </a>
                </div>
            </div>
            @endif

            {{-- ── Status: in_delivery — manager delivery / courier tracking ── --}}
            @if($showInDeliveryCard)
                @if($penyewaan->shipping_method_type === 'manager')
                    @php $mds = $serahTerima?->manager_delivery_status; @endphp
                    <div class="ps-status-section ps-section-pengiriman">
                        <div class="ps-eyebrow">🚚 Informasi Pengiriman</div>
                        <h2>
                            @if($mds === 'tiba_di_tujuan') Koleksi Sudah Tiba di Lokasi Anda
                            @elseif($mds === 'dalam_perjalanan') Koleksi Sedang Dalam Perjalanan
                            @elseif($mds === 'siap_dikirim') Koleksi Siap Dikirim
                            @else Koleksi Sedang Dikemas untuk Dikirim
                            @endif
                        </h2>
                        <p>
                            @if($mds === 'tiba_di_tujuan')
                                Pengelola mencatat koleksi sudah tiba di lokasi Anda. Silakan buka halaman Serah Terima untuk mengkonfirmasi penerimaan.
                            @elseif($mds === 'dalam_perjalanan')
                                Koleksi sedang dalam perjalanan menuju alamat Anda menggunakan kendaraan pengelola.
                            @elseif($mds === 'siap_dikirim')
                                Koleksi sudah dikemas dan siap dikirimkan. Pengelola segera mengirimkan ke alamat Anda.
                            @else
                                Pengelola sedang mempersiapkan dan mengemas koleksi sebelum dikirimkan ke alamat Anda.
                            @endif
                        </p>
                        <div class="ps-meta-grid">
                            <div class="ps-meta-cell success">
                                <div class="lbl">Pembayaran</div>
                                <div class="val">LUNAS ✓</div>
                            </div>
                            <div class="ps-meta-cell">
                                <div class="lbl">Metode Pengiriman</div>
                                <div class="val">Dikirim Pengelola</div>
                            </div>
                            @if($serahTerima?->delivery_officer)
                            <div class="ps-meta-cell">
                                <div class="lbl">Petugas Pengirim</div>
                                <div class="val">{{ $serahTerima->delivery_officer }}</div>
                            </div>
                            @endif
                            @if($serahTerima?->delivery_scheduled_at)
                            <div class="ps-meta-cell">
                                <div class="lbl">Rencana Tiba</div>
                                <div class="val">{{ \Carbon\Carbon::parse($serahTerima->delivery_scheduled_at)->format('d M Y') }}</div>
                            </div>
                            @endif
                        </div>
                        <div class="ps-action-row">
                            <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-sky">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                Pantau Pengiriman di Halaman Serah Terima →
                            </a>
                        </div>
                    </div>
                @else
                    <div class="ps-status-section ps-section-pengiriman">
                        <div class="ps-eyebrow">🚚 Dalam Pengiriman via Kurir</div>
                        <h2>Koleksi Sedang Dikirim</h2>
                        <p>Koleksi Anda sedang dalam perjalanan. Pantau status pengiriman lengkap di halaman Serah Terima.</p>
                        <div class="ps-meta-grid">
                            <div class="ps-meta-cell success">
                                <div class="lbl">Pembayaran</div>
                                <div class="val">LUNAS ✓</div>
                            </div>
                            <div class="ps-meta-cell">
                                <div class="lbl">Kurir</div>
                                <div class="val">{{ $serahTerima?->delivery_method ?? '-' }}</div>
                            </div>
                            <div class="ps-meta-cell">
                                <div class="lbl">Nomor Resi</div>
                                <div class="val" style="font-family:monospace;">{{ $serahTerima?->delivery_tracking_number ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="ps-action-row">
                            <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}"
                               class="ps-btn ps-btn-sky">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                Lacak Pengiriman di Halaman Serah Terima →
                            </a>
                        </div>
                    </div>
                @endif
            @endif
            
            {{-- ── Status: damage_reported — menunggu keputusan pengelola ── --}}
            @if($showDamageReportedCard)
            @php
                $isPengajuanBatal = $serahTerima?->arrival_damage_buyer_decision === 'batalkan';
                $decLabel = match($serahTerima?->arrival_damage_buyer_decision) {
                    'lanjut'   => '✅ Ajukan Kompensasi',
                    'batalkan' => '❌ Ajukan Pembatalan',
                    default    => '-',
                };
            @endphp
            <div class="ps-status-section" style="background:#fffbeb;border:1.5px solid #fde68a;">
                <div class="ps-eyebrow" style="color:#d97706;">⏳ Menunggu Pengelola</div>
                <h2>Laporan Kerusakan Sudah Terkirim</h2>
                <p>
                    Laporan kerusakan kamu sudah diterima pengelola pada
                    <strong>{{ $serahTerima?->arrival_damage_reported_at?->format('d M Y, H:i') ?? '-' }}</strong>.
                    Kamu telah mengajukan <strong>{{ $isPengajuanBatal ? 'pembatalan sewa' : 'kompensasi kerusakan' }}</strong> karena kondisi kerusakan yang ditemukan.
                    Tunggu keputusan pengelola sebelum proses dilanjutkan.
                </p>

                @php
                    $checkedItems = $serahTerima->getCheckedDamageItems();
                    $sevLabel = match($serahTerima->arrival_damage_severity ?? '') {
                        'ringan' => '🟡 Ringan', 'parah' => '🔴 Parah', default => '-'
                    };
                @endphp

                <div class="ps-meta-grid">
                    <div class="ps-meta-cell warning">
                        <div class="lbl">Keputusan Kamu</div>
                        <div class="val">{{ $decLabel }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Dilaporkan Pada</div>
                        <div class="val">{{ $serahTerima?->arrival_damage_reported_at?->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                    <div class="ps-meta-cell warning">
                        <div class="lbl">Status Keputusan Pengelola</div>
                        <div class="val">⏳ Menunggu Tinjauan</div>
                    </div>
                </div>

                @if(!empty($checkedItems))
                <div style="margin-top:1rem;">
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#92400e;margin-bottom:.5rem;">Kerusakan yang Dilaporkan</div>
                    <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                        @foreach($checkedItems as $item)
                            <span style="background:#fff;border:1.5px solid #fde68a;border-radius:.6rem;padding:.3rem .75rem;font-size:.76rem;font-weight:600;color:#92400e;">⚠ {{ $item }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', $penyewaan) }}" class="ps-btn ps-btn-sky">
                        Lihat Detail Laporan di Serah Terima →
                    </a>
                </div>
            </div>
            @endif

            {{-- ── Status: damage_reviewed — pengelola sudah putuskan ── --}}
            @if($showDamageReviewedCard)
            @php $managerDec = $serahTerima->arrival_damage_manager_decision; @endphp

                {{-- DISETUJUI: sewa dibatalkan --}}
                @if($managerDec === 'setuju_batal')
                <div class="ps-status-section ps-section-dibatalkan">
                    <div class="ps-eyebrow">❌ Pengajuan Pembatalan Disetujui</div>
                    <h2>Pengelola Menyetujui Pembatalan Sewa</h2>
                    <p>
                        Pengelola sudah meninjau laporan kerusakan dan <strong>menyetujui pembatalan</strong>.
                        Deposit akan dikembalikan penuh ke rekening kamu (ongkir tidak dikembalikan).
                        Tidak ada tindakan lebih lanjut yang diperlukan dari kamu.
                    </p>
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell danger">
                            <div class="lbl">Status</div>
                            <div class="val">Sewa Dibatalkan</div>
                        </div>
                        <div class="ps-meta-cell success">
                            <div class="lbl">Deposit</div>
                            <div class="val">Akan Dikembalikan Penuh</div>
                        </div>
                        @if($serahTerima->arrival_damage_decided_at)
                        <div class="ps-meta-cell">
                            <div class="lbl">Diputuskan Pada</div>
                            <div class="val">{{ $serahTerima->arrival_damage_decided_at->format('d M Y H:i') }}</div>
                        </div>
                        @endif
                    </div>
                    @if($serahTerima->arrival_damage_manager_notes)
                    <div class="ps-catatan" style="margin-top:1rem;background:#fff;border-color:#e2e8f0;">
                        <div class="lbl">Catatan dari Pengelola</div>
                        <div class="val">{{ $serahTerima->arrival_damage_manager_notes }}</div>
                    </div>
                    @endif
                    <div class="ps-action-row">
                        <a href="{{ route('penyewaan.requests.handover.show', $penyewaan) }}" class="ps-btn ps-btn-ghost">
                            Lihat Detail Serah Terima →
                        </a>
                    </div>
                </div>

                {{-- DITOLAK: sewa dilanjutkan --}}
                @elseif($managerDec === 'tolak_lanjut')
                <div class="ps-status-section ps-section-serahterima">
                    <div class="ps-eyebrow">⚠️ Pengajuan Pembatalan Ditolak</div>
                    <h2>Pengelola Menolak Pembatalan — Sewa Dilanjutkan</h2>
                    <p>
                        Pengelola sudah meninjau laporan kerusakan dan <strong>menolak pengajuan pembatalan</strong>.
                        Proses sewa tetap dilanjutkan. Silakan lanjutkan ke proses unduh dan upload dokumen serah terima.
                    </p>
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell warning">
                            <div class="lbl">Keputusan Pembatalan</div>
                            <div class="val">Ditolak Pengelola</div>
                        </div>
                        <div class="ps-meta-cell success">
                            <div class="lbl">Status Sewa</div>
                            <div class="val">Dilanjutkan ✓</div>
                        </div>
                        @if($serahTerima->arrival_damage_decided_at)
                        <div class="ps-meta-cell">
                            <div class="lbl">Diputuskan Pada</div>
                            <div class="val">{{ $serahTerima->arrival_damage_decided_at->format('d M Y H:i') }}</div>
                        </div>
                        @endif
                    </div>
                    @if($serahTerima->arrival_damage_manager_notes)
                    <div class="ps-catatan" style="margin-top:1rem;background:#fff;">
                        <div class="lbl">Catatan dari Pengelola</div>
                        <div class="val">{{ $serahTerima->arrival_damage_manager_notes }}</div>
                    </div>
                    @endif
                    <div class="ps-action-row">
                        <a href="{{ route('penyewaan.requests.handover.show', $penyewaan) }}" class="ps-btn ps-btn-blue">
                            Lanjut ke Proses Serah Terima →
                        </a>
                    </div>
                </div>
                @endif
            @endif
            
            {{-- ── Status: cancelled_due_to_damage (legacy — ditangani di blok dibatalkan di atas) ── --}}
            @if($serahTerima?->handover_status === 'cancelled_due_to_damage' && $status !== 'dibatalkan')
            <div class="ps-status-section ps-section-dibatalkan">
                <div class="ps-eyebrow">🚫 Dibatalkan — Kerusakan Pengiriman</div>
                <h2>Penyewaan Dibatalkan</h2>
                <p>
                    Penyewaan ini dibatalkan karena ditemukan kerusakan pada koleksi saat pengiriman.
                    Deposit akan dikembalikan penuh ke rekening kamu.
                </p>
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.index') }}" class="ps-btn ps-btn-navy">Lihat Katalog →</a>
                </div>
            </div>
            @endif

            {{-- menunggu_dokumen_serah_terima --}}
            @if($status === 'menunggu_dokumen_serah_terima')
            @php $stDitolak = $serahTerima && $serahTerima->serah_terima_status === 'rejected'; @endphp
            <div class="ps-status-section {{ $stDitolak ? 'ps-section-ditolak' : 'ps-section-serahterima' }}">
                @if($stDitolak)
                    <div class="ps-eyebrow">❌ Dokumen Ditolak</div>
                    <h2>Upload Ulang Dokumen Serah Terima</h2>
                    <p>Dokumen serah terima yang Anda unggah sebelumnya <strong>ditolak</strong> oleh pengelola. Periksa catatan penolakan di bawah, perbaiki, lalu upload ulang.</p>
                    @if($serahTerima->validation_notes)
                    <div class="ps-catatan" style="background:#fff;border-color:#fecaca;margin-top:1rem;">
                        <div class="lbl" style="color:#dc2626;">📋 Catatan Penolakan dari Pengelola</div>
                        <div class="val">{{ $serahTerima->validation_notes }}</div>
                    </div>
                    @endif
                @elseif($isCompensationFlow && $serahTerima?->refund_confirmed_at)
                    <div class="ps-eyebrow">✅ Kompensasi Selesai</div>
                    <h2>Upload Dokumen Serah Terima</h2>
                    <p>
                        Anda telah mengkonfirmasi penerimaan kompensasi pada
                        <strong>{{ $serahTerima->refund_confirmed_at->format('d M Y H:i') }}</strong>.
                        Silakan unduh dokumen serah terima, periksa kondisi koleksi, tanda tangani, lalu upload kembali.
                    </p>
                    @if($serahTerima->refund_amount)
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell success">
                            <div class="lbl">Kompensasi Diterima</div>
                            <div class="val">Rp {{ number_format($serahTerima->refund_amount, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endif
                @elseif($serahTerima?->arrival_damage_manager_decision === 'tolak_kompensasi')
                    <div class="ps-eyebrow">⚠️ Kompensasi Ditolak</div>
                    <h2>Upload Dokumen Serah Terima</h2>
                    <p>
                        Pengelola menolak pengajuan kompensasi kerusakan. Proses penyewaan tetap berlanjut —
                        silakan unduh dokumen serah terima, periksa kondisi koleksi, tanda tangani, lalu upload kembali.
                    </p>
                    @if($serahTerima->arrival_damage_manager_notes)
                    <div class="ps-catatan" style="background:#fff;border-color:#fecaca;margin-top:1rem;">
                        <div class="lbl" style="color:#dc2626;">❌ Klaim Kompensasi Ditolak — Catatan Pengelola</div>
                        <div class="val">{{ $serahTerima->arrival_damage_manager_notes }}</div>
                    </div>
                    @endif
                @else
                    <div class="ps-eyebrow">📋 Aksi Diperlukan</div>
                    <h2>Upload Dokumen Serah Terima</h2>
                    <p>Koleksi telah dikonfirmasi diterima. Unduh dokumen serah terima, periksa kondisi koleksi, isi checklist, tanda tangani, lalu upload kembali.</p>
                @endif
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-blue">
                        {{ $stDitolak ? 'Upload Ulang Dokumen Serah Terima →' : 'Unduh & Upload Dokumen Serah Terima →' }}
                    </a>
                </div>
            </div>
            @endif

            {{-- verifikasi_serah_terima --}}
            @if($status === 'verifikasi_serah_terima')
            <div class="ps-status-section ps-section-serahterima">
                <div class="ps-eyebrow">⏳ Menunggu Pengelola</div>
                <h2>Dokumen Serah Terima Sedang Diverifikasi</h2>
                <p>Dokumen serah terima Anda sedang diperiksa oleh pengelola. Anda akan mendapat notifikasi setelah divalidasi.</p>
            </div>
            @endif

            {{-- aktif --}}
            @if($status === 'aktif')
            @php
                $isUrgent  = $sisaHari !== null && $sisaHari <= 3 && $sisaHari >= 0;
                $isExpired = $sisaHari !== null && $sisaHari < 0;
                $aktifSection = $isExpired ? 'ps-section-aktif-expired' : ($isUrgent ? 'ps-section-aktif-urgent' : 'ps-section-aktif');
                $aktifEyebrow = $isExpired ? 'text-red-700' : ($isUrgent ? '#d97706' : '#166534');
            @endphp
            <div class="ps-status-section {{ $aktifSection }}">
                <div class="ps-eyebrow">{{ $isExpired ? '⚠️ Masa Sewa Berakhir' : '🎨 Masa Sewa Berjalan' }}</div>
                <h2>{{ $isExpired ? 'Segera Kembalikan Koleksi' : 'Penyewaan Aktif' }}</h2>
                @if($isExpired)
                    <p>Masa sewa telah berakhir. Segera kembalikan koleksi ke museum.</p>
                @else
                    <p>Penyewaan sedang berjalan. Pastikan koleksi dijaga dengan baik selama masa sewa.</p>
                @endif
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell">
                        <div class="lbl">Berakhir Pada</div>
                        <div class="val">{{ $penyewaan->end_date?->format('d M Y') ?? '-' }}</div>
                    </div>
                    <div class="ps-meta-cell {{ $isUrgent || $isExpired ? 'danger' : 'success' }}">
                        <div class="lbl">Sisa Hari</div>
                        <div class="val">
                            @if($isExpired) Sudah berakhir
                            @elseif($sisaHari !== null) {{ $sisaHari }} hari lagi
                            @else -
                            @endif
                        </div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Mulai Aktif</div>
                        <div class="val">{{ $penyewaan->rental_started_at?->format('d M Y') ?? '-' }}</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- pengembalian --}}
            @if($status === 'pengembalian')
            @php
                $stPengembalian = $penyewaan->serahTerima;
                $shipmentDikirim = $stPengembalian?->return_shipment_submitted_at;
                $koleksiTiba    = $stPengembalian?->collection_arrived_at;
                $dokGenerated   = $stPengembalian?->return_document_path;
            @endphp
            <div class="ps-status-section ps-section-pengembalian">
                @if($koleksiTiba && $dokGenerated)
                    <div class="ps-eyebrow">⏳ Menunggu Pengelola</div>
                    <h2>Koleksi Sedang Diperiksa</h2>
                    <p>Koleksi sudah tiba di museum. Pengelola sedang melakukan pemeriksaan kondisi dan menyiapkan dokumen pengembalian untuk Anda tandatangani.</p>
                @elseif($koleksiTiba)
                    <div class="ps-eyebrow">⏳ Menunggu Pengelola</div>
                    <h2>Koleksi Tiba — Sedang Diperiksa</h2>
                    <p>Koleksi sudah dikonfirmasi tiba di museum. Pengelola sedang melakukan pemeriksaan kondisi.</p>
                @elseif($shipmentDikirim)
                    <div class="ps-eyebrow">🔄 Proses Pengembalian</div>
                    <h2>Koleksi Dalam Proses Pengiriman Balik</h2>
                    <p>Info pengiriman balik sudah terkirim. Menunggu pengelola mengkonfirmasi koleksi tiba di museum.</p>
                @else
                    <div class="ps-eyebrow">⚡ Aksi Diperlukan</div>
                    <h2>Koleksi Dalam Proses Pengembalian</h2>
                    <p>Masa sewa telah berakhir. Kirimkan koleksi kembali ke museum dan isi informasi pengiriman.</p>
                @endif

                <div class="ps-meta-grid">
                    <div class="ps-meta-cell">
                        <div class="lbl">Masa Sewa Berakhir</div>
                        <div class="val">{{ $penyewaan->end_date?->format('d M Y') ?? '-' }}</div>
                    </div>
                    <div class="ps-meta-cell {{ $shipmentDikirim ? 'success' : 'warning' }}">
                        <div class="lbl">Info Pengiriman Balik</div>
                        <div class="val">{{ $shipmentDikirim ? 'Sudah Diisi ✓' : 'Belum Diisi' }}</div>
                    </div>
                    <div class="ps-meta-cell {{ $koleksiTiba ? 'success' : '' }}">
                        <div class="lbl">Koleksi Tiba di Museum</div>
                        <div class="val">{{ $koleksiTiba ? 'Sudah Dikonfirmasi ✓' : 'Menunggu' }}</div>
                    </div>
                    @if($koleksiTiba)
                    <div class="ps-meta-cell {{ $dokGenerated ? 'success' : 'warning' }}">
                        <div class="lbl">Dokumen Pengembalian</div>
                        <div class="val">{{ $dokGenerated ? 'Siap Ditandatangani ✓' : 'Sedang Disiapkan...' }}</div>
                    </div>
                    @endif
                </div>

                @if(Route::has('penyewaan.requests.handover.show'))
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-teal">
                        Lanjut ke Proses Pengembalian →
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- selesai --}}
            @if($status === 'selesai')
            <div class="ps-status-section ps-section-selesai">
                <div class="ps-eyebrow">🎉 Penyewaan Selesai</div>
                <h2>Terima Kasih!</h2>
                <p>Penyewaan ini telah selesai. Koleksi sudah dikembalikan ke museum.</p>
                <div class="ps-meta-grid">
                    @if($penyewaan->rental_started_at)
                    <div class="ps-meta-cell">
                        <div class="lbl">Tanggal Mulai</div>
                        <div class="val">{{ $penyewaan->rental_started_at->format('d M Y') }}</div>
                    </div>
                    @endif
                    @if($penyewaan->end_date)
                    <div class="ps-meta-cell">
                        <div class="lbl">Tanggal Selesai</div>
                        <div class="val">{{ $penyewaan->end_date->format('d M Y') }}</div>
                    </div>
                    @endif
                    <div class="ps-meta-cell success">
                        <div class="lbl">Durasi</div>
                        <div class="val">{{ $penyewaan->duration_days }} hari</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ════════════════════════════════════════════════════════════════
                DEPOSIT FLOW — 3 KASUS
                Letakkan bagian ini di show.blade.php (penyewa), menggantikan
                blok @if($status === 'menunggu_konfirmasi_refund') yang lama.
            ════════════════════════════════════════════════════════════════ --}}

            {{-- ── menunggu_konfirmasi_refund ──
                Kasus 1: Tidak ada kerusakan → refund penuh
                Kasus 2: Kerusakan ≤ deposit → refund sisa
                Penyewa harus konfirmasi bahwa dana sudah diterima.
            ──────────────────────────────────────────────────────────────── --}}
            @if($status === 'menunggu_konfirmasi_refund' && !$isCancellationFlow && !$isCompensationFlow)
            @php
                $depositAmt = $penyewaan->deposit_amount ?: 0;
                $damageCost = (int) ($serahTerima?->final_damage_cost ?? $serahTerima?->damage_cost ?? 0);
                $sisaRefund = max(0, $depositAmt - $damageCost);
                $refund     = $penyewaan->depositRefund;
                $isFullRefund = $damageCost === 0;
            @endphp
            <div class="ps-status-section"
                style="background:{{ $refund ? '#f0fdfa' : '#f8fafc' }};
                        border:1.5px solid {{ $refund ? '#99f6e4' : '#e2e8f0' }};">

                <div class="ps-eyebrow" style="color:{{ $refund ? '#0f766e' : '#64748b' }};">
                    {{ $refund ? '⚡ Aksi Diperlukan' : '⏳ Menunggu Pengelola' }}
                </div>
                <h2>{{ $refund ? 'Konfirmasi Penerimaan Refund Deposit' : 'Menunggu Proses Refund Deposit' }}</h2>

                @if($refund)
                    <p>Pengelola telah mentransfer refund deposit ke rekening Anda. Periksa saldo rekening, lalu konfirmasi penerimaan di halaman serah terima.</p>

                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell">
                            <div class="lbl">Total Deposit Awal</div>
                            <div class="val">Rp {{ number_format($depositAmt, 0, ',', '.') }}</div>
                        </div>
                        @if($damageCost > 0)
                        <div class="ps-meta-cell danger">
                            <div class="lbl">Potongan Kerusakan</div>
                            <div class="val">− Rp {{ number_format($damageCost, 0, ',', '.') }}</div>
                        </div>
                        @endif
                        <div class="ps-meta-cell success">
                            <div class="lbl">{{ $isFullRefund ? 'Refund Penuh' : 'Sisa yang Dikembalikan' }}</div>
                            <div class="val">Rp {{ number_format($refund->refund_amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Bank Tujuan</div>
                            <div class="val">{{ $refund->bank_name }} — {{ $refund->account_number }}</div>
                        </div>
                    </div>

                    <div class="ps-action-row">
                        <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-emerald">
                            Konfirmasi Penerimaan Refund →
                        </a>
                    </div>

                @else
                    <p>Pemeriksaan koleksi selesai. Pengelola sedang memproses pengembalian deposit ke rekening Anda.</p>

                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell">
                            <div class="lbl">Total Deposit</div>
                            <div class="val">Rp {{ number_format($depositAmt, 0, ',', '.') }}</div>
                        </div>
                        @if($damageCost > 0)
                        <div class="ps-meta-cell danger">
                            <div class="lbl">Potongan Kerusakan</div>
                            <div class="val">Rp {{ number_format($damageCost, 0, ',', '.') }}</div>
                        </div>
                        @endif
                        <div class="ps-meta-cell highlight">
                            <div class="lbl">Yang Akan Dikembalikan</div>
                            <div class="val">Rp {{ number_format($sisaRefund, 0, ',', '.') }}</div>
                        </div>
                        @if($penyewaan->bank_name)
                        <div class="ps-meta-cell">
                            <div class="lbl">Rekening Tujuan</div>
                            <div class="val">{{ $penyewaan->bank_name }} — {{ $penyewaan->account_number }}</div>
                        </div>
                        @endif
                    </div>

                    <div class="ps-action-row">
                        <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-sky">
                            Lihat Detail →
                        </a>
                    </div>
                @endif
            </div>
            @endif

            {{-- ── menunggu_ttd_pengembalian ──
                Berlaku untuk kasus 1, 2, dan 3.
                Kasus 1 & 2: setelah penyewa konfirmasi refund.
                Kasus 3: setelah invoice kerusakan lunas via Midtrans.
            ──────────────────────────────────────────────────────────────── --}}
            @if($status === 'menunggu_ttd_pengembalian')
            @php
                $refund      = $penyewaan->depositRefund;
                $damageInv   = $penyewaan->damageInvoice;
                $isKasus3    = $damageInv && $damageInv->isPaid(); // kerusakan > deposit, sudah lunas
            @endphp
            <div class="ps-status-section" style="background:#eef2ff;border:1.5px solid #c7d2fe;">
                <div class="ps-eyebrow" style="color:#4338ca;">⚡ Aksi Diperlukan</div>
                <h2>Tandatangani Dokumen Pengembalian</h2>
                <p>
                    @if($isKasus3)
                        Pembayaran tagihan kerusakan berhasil. Unduh dokumen pengembalian,
                        tandatangani, lalu upload kembali untuk menyelesaikan proses.
                    @elseif($refund && $refund->refund_amount > 0)
                        Refund deposit sudah Anda konfirmasi. Unduh dokumen pengembalian,
                        tandatangani, lalu upload kembali.
                    @else
                        Unduh dokumen pengembalian, tandatangani, lalu upload kembali.
                    @endif
                </p>

                {{-- Ringkasan resolusi deposit --}}
                @if($isKasus3)
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Tagihan Kerusakan</div>
                        <div class="val">Lunas ✓</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Deposit</div>
                        <div class="val">Hangus (biaya kerusakan)</div>
                    </div>
                </div>
                @elseif($refund)
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Refund Deposit</div>
                        <div class="val">Rp {{ number_format($refund->refund_amount, 0, ',', '.') }} ✓</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Dikonfirmasi Pada</div>
                        <div class="val">{{ $penyewaan->serahTerima?->refund_confirmed_at?->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                </div>
                @endif

                {{-- Langkah unduh & upload --}}
                <div class="ps-action-row" style="margin-top:1.25rem;">
                    <a href="{{ route('penyewaan.requests.handover.show', $penyewaan) }}" class="ps-btn ps-btn-blue">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        Unduh & Upload Dokumen Pengembalian →
                    </a>
                </div>
            </div>
            @endif

            {{-- ── menunggu_konfirmasi_selesai ──
                Sama untuk semua kasus: penyewa sudah TTD, tinggal tunggu pengelola.
            ──────────────────────────────────────────────────────────────── --}}
            @if($status === 'menunggu_konfirmasi_selesai')
            <div class="ps-status-section" style="background:#f0fdf4;border:1.5px solid #bbf7d0;">
                <div class="ps-eyebrow" style="color:#166534;">⏳ Hampir Selesai</div>
                <h2>Dokumen Berhasil Diunggah</h2>
                <p>
                    Dokumen pengembalian yang sudah ditandatangani berhasil diunggah.
                    Pengelola akan memeriksa dan mengkonfirmasi untuk menyelesaikan penyewaan.
                </p>
                @if($serahTerima?->tenant_signed_return_at)
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Dokumen Diunggah Pada</div>
                        <div class="val">{{ $serahTerima->tenant_signed_return_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
                @endif
                <div class="ps-action-row">
                    <a href="{{ route('penyewaan.requests.handover.show', $penyewaan) }}"
                    class="ps-btn ps-btn-sky">
                        Lihat Detail Serah Terima →
                    </a>
                </div>
            </div>
            @endif

            {{-- ── menunggu_pembayaran_kerusakan ── --}}
            @if($status === 'menunggu_pembayaran_kerusakan')
            @php $invoice = $penyewaan->damageInvoice; @endphp
            <div class="ps-status-section" style="background:#fef2f2;border:1.5px solid #fecaca;">
                <div class="ps-eyebrow" style="color:#dc2626;">⚡ Aksi Diperlukan</div>
                <h2>Lunasi Tagihan Kerusakan</h2>
                <p>Biaya kerusakan koleksi melebihi deposit Anda. Ada tagihan tambahan yang perlu dilunasi sebelum dokumen pengembalian bisa ditandatangani.</p>

                @if($invoice)
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell danger">
                        <div class="lbl">Tagihan Tambahan</div>
                        <div class="val">Rp {{ number_format($invoice->additional_charge, 0, ',', '.') }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Total Kerusakan</div>
                        <div class="val">Rp {{ number_format($invoice->restoration_cost, 0, ',', '.') }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Deposit Hangus</div>
                        <div class="val">Rp {{ number_format($invoice->deposit_used, 0, ',', '.') }}</div>
                    </div>
                    <div class="ps-meta-cell {{ $invoice->isPaid() ? 'success' : 'danger' }}">
                        <div class="lbl">Status Invoice</div>
                        <div class="val">{{ $invoice->isPaid() ? 'Lunas ✓' : 'Belum Dibayar' }}</div>
                    </div>
                </div>
                @endif

                <div class="ps-action-row">
                    @if($invoice && !$invoice->isPaid())
                    <a href="{{ route('penyewaan.requests.deposit.damage-payment', $penyewaan) }}" class="ps-btn ps-btn-danger">
                        💳 Bayar Sekarang — Rp {{ number_format($invoice->additional_charge ?? 0, 0, ',', '.') }}
                    </a>
                    @endif
                    <a href="{{ route('penyewaan.requests.handover.show', ['penyewaan' => $penyewaan->id]) }}" class="ps-btn ps-btn-sky">
                        Lihat Detail →
                    </a>
                </div>
            </div>
            @endif

            {{-- ════════════════════════════
                 INFO PENGAJUAN
            ════════════════════════════ --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Informasi Pengajuan</h3>
                </div>
                <div class="ps-card-body">
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell">
                            <div class="lbl">Nomor Pengajuan</div>
                            <div class="val" style="font-family:'Playfair Display',serif;color:var(--blue);">
                                {{ $penyewaan->nomor_pengajuan ?? 'SW-' . str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}
                            </div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Diajukan Pada</div>
                            <div class="val">{{ ($penyewaan->submitted_at ?? $penyewaan->created_at)->format('d M Y H:i') }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Tipe Penyewa</div>
                            <div class="val">{{ $penyewaan->rental_type === 'instansi' ? 'Instansi / Perusahaan' : 'Perseorangan' }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Status Pembayaran</div>
                            <div class="val">
                                @php
                                    echo match($penyewaan->payment_status) {
                                        'paid'    => 'Lunas',
                                        'pending' => 'Menunggu',
                                        'failed'  => 'Gagal',
                                        'expired' => 'Kedaluwarsa',
                                        default   => 'Belum Dibayar',
                                    };
                                @endphp
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════════════════════════
                DATA INSTANSI
            ════════════════════════════ --}}
            @if($penyewaan->rental_type === 'instansi')
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Data Instansi / Perusahaan</h3>
                </div>
                <div class="ps-card-body" style="display:grid;gap:1.5rem;">
                    <div class="ps-data-row">
                        @foreach([
                            'Nama Instansi'  => $penyewaan->nama_instansi,
                            'Jenis Instansi' => $penyewaan->jenis_instansi,
                            'Bidang Usaha'   => $penyewaan->bidang_usaha,
                            'Email Instansi' => $penyewaan->email_instansi,
                            'Telepon Kantor' => $penyewaan->telepon_kantor,
                            'NPWP Instansi'  => $penyewaan->npwp_instansi ?? 'Tidak disediakan',
                        ] as $lbl => $val)
                            <div class="ps-field"><div class="lbl">{{ $lbl }}</div><div class="val">{{ $val ?? '-' }}</div></div>
                        @endforeach
                    </div>

                    {{-- Alamat Instansi --}}
                    <div class="ps-address-box">
                        <h4>Alamat Instansi</h4>
                        <table style="width:100%;border-collapse:collapse;">
                            @foreach([
                                'Alamat Lengkap' => $penyewaan->alamat_instansi,
                                'RT / RW'        => ($penyewaan->rt_instansi && $penyewaan->rw_instansi)
                                                    ? ($penyewaan->rt_instansi . ' / ' . $penyewaan->rw_instansi)
                                                    : null,
                                'Kel. / Desa'    => $penyewaan->kelurahan_desa_instansi,
                                'Kecamatan'      => $penyewaan->kecamatan_instansi,
                                'Kota / Kab.'    => $penyewaan->kota_instansi,
                                'Provinsi'       => $penyewaan->provinsi_instansi,
                                'Kode Pos'       => $penyewaan->kode_pos_instansi,
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

            {{-- ════════════════════════════
                DATA PIC
            ════════════════════════════ --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Data PIC (Person In Charge)</h3>
                </div>
                <div class="ps-card-body" style="display:grid;gap:1.5rem;">
                    <div class="ps-data-row">
                        @foreach([
                            'Nama PIC'    => $penyewaan->nama_pic,
                            'Jabatan PIC' => $penyewaan->jabatan_pic,
                            'NIK PIC'     => $penyewaan->nik_pic,
                            'Nomor HP'    => $penyewaan->hp_pic,
                            'Email PIC'   => $penyewaan->email_pic,
                        ] as $lbl => $val)
                            <div class="ps-field"><div class="lbl">{{ $lbl }}</div><div class="val">{{ $val ?? '-' }}</div></div>
                        @endforeach
                    </div>

                    {{-- Nomor HP & Email kontak utama --}}
                    <div class="ps-data-row">
                        <div class="ps-field"><div class="lbl">Nomor HP</div><div class="val">{{ $penyewaan->contact_phone ?? '-' }}</div></div>
                        <div class="ps-field"><div class="lbl">Email</div><div class="val">{{ $penyewaan->contact_email ?? '-' }}</div></div>
                    </div>

                    {{-- Alamat Domisili —— tabel kecil seperti pembelian --}}
                    <div class="ps-address-box">
                        <h4>Alamat Domisili</h4>
                        <table style="width:100%;border-collapse:collapse;">
                            @foreach([
                                'Alamat Lengkap' => $penyewaan->alamat_domisili,
                                'RT / RW'        => ($penyewaan->rt ?? '-') . ' / ' . ($penyewaan->rw ?? '-'),
                                'Kel. / Desa'    => $penyewaan->kelurahan_desa,
                                'Kecamatan'      => $penyewaan->kecamatan,
                                'Kota / Kab.'    => $penyewaan->kota_kabupaten,
                                'Provinsi'       => $penyewaan->provinsi,
                                'Kode Pos'       => $penyewaan->kode_pos,
                            ] as $lbl => $val)
                                @if($val && $val !== '-')
                                <tr style="border-bottom:1px solid var(--border);">
                                    <td style="padding:.45rem .75rem .45rem .1rem;font-size:.72rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;width:38%;vertical-align:top;">{{ $lbl }}</td>
                                    <td style="padding:.45rem 0;font-size:.82rem;color:var(--navy);font-weight:500;line-height:1.5;">{{ $val ?? '-' }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            @else
            {{-- ════════════════════════════
                DATA PENYEWA PERSEORANGAN
            ════════════════════════════ --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Data Penyewa</h3>
                </div>
                <div class="ps-card-body" style="display:grid;gap:1.5rem;">
                    <div class="ps-data-row">
                        @foreach([
                            'Nama Lengkap'  => $penyewaan->contact_name,
                            'NIK'           => $penyewaan->nik,
                            'Tempat Lahir'  => $penyewaan->tempat_lahir,
                            'Tanggal Lahir' => optional($penyewaan->tanggal_lahir)->format('d M Y'),
                            'Jenis Kelamin' => $penyewaan->jenis_kelamin,
                            'Pekerjaan'     => $penyewaan->pekerjaan,
                        ] as $lbl => $val)
                            <div class="ps-field"><div class="lbl">{{ $lbl }}</div><div class="val">{{ $val ?? '-' }}</div></div>
                        @endforeach
                    </div>

                    <div class="ps-data-row">
                        <div class="ps-field"><div class="lbl">Nomor HP</div><div class="val">{{ $penyewaan->contact_phone ?? '-' }}</div></div>
                        <div class="ps-field"><div class="lbl">Email</div><div class="val">{{ $penyewaan->contact_email ?? '-' }}</div></div>
                    </div>

                    <div class="ps-address-box">
                        <h4>Alamat Domisili</h4>
                        <table style="width:100%;border-collapse:collapse;">
                            @foreach([
                                'Alamat Lengkap' => $penyewaan->alamat_domisili,
                                'RT / RW'        => ($penyewaan->rt ?? '-') . ' / ' . ($penyewaan->rw ?? '-'),
                                'Kel. / Desa'    => $penyewaan->kelurahan_desa,
                                'Kecamatan'      => $penyewaan->kecamatan,
                                'Kota / Kab.'    => $penyewaan->kota_kabupaten,
                                'Provinsi'       => $penyewaan->provinsi,
                                'Kode Pos'       => $penyewaan->kode_pos,
                            ] as $lbl => $val)
                                @if($val && $val !== '-')
                                <tr style="border-bottom:1px solid var(--border);">
                                    <td style="padding:.45rem .75rem .45rem .1rem;font-size:.72rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;width:38%;vertical-align:top;">{{ $lbl }}</td>
                                    <td style="padding:.45rem 0;font-size:.82rem;color:var(--navy);font-weight:500;line-height:1.5;">{{ $val ?? '-' }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- ════════════════════════════
                DETAIL KOLEKSI & JADWAL
            ════════════════════════════ --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Detail Koleksi & Jadwal Sewa</h3>
                </div>
                <div class="ps-card-body" style="display:grid;gap:1.25rem;">
                    <div class="ps-painting-grid">
                        <div class="ps-painting-thumb">
                            @if($penyewaan->painting->image_url ?? $penyewaan->painting->image_path ?? null)
                                <img src="{{ $penyewaan->painting->image_url ?? asset('storage/' . $penyewaan->painting->image_path) }}"
                                    alt="{{ $penyewaan->painting->title }}">
                            @else
                                <div class="ps-painting-thumb-empty">Tidak ada foto</div>
                            @endif
                        </div>
                        <div class="ps-painting-fields">
                            @foreach([
                                'Nama Koleksi'    => $penyewaan->painting->title,
                                'Seniman'         => $penyewaan->painting->artist ?? '-',
                                'Tarif per Hari'  => 'Rp ' . number_format($penyewaan->painting->daily_rate ?? 0, 0, ',', '.'),
                                'Tanggal Mulai'   => optional($penyewaan->start_date)->format('d M Y'),
                                'Tanggal Selesai' => optional($penyewaan->end_date)->format('d M Y'),
                                'Durasi'          => ($penyewaan->duration_days ?? '-') . ' hari',
                                'Indoor/Outdoor'  => $penyewaan->indoor_outdoor ?? '-',
                                'Jenis Tempat'    => $penyewaan->jenis_tempat ?? '-',
                            ] as $lbl => $val)
                                <div class="ps-field"><div class="lbl">{{ $lbl }}</div><div class="val">{{ $val ?? '-' }}</div></div>
                            @endforeach
                            @if($penyewaan->tujuan_penyewaan)
                            <div class="ps-field" style="grid-column:1/-1;">
                                <div class="lbl">Tujuan Penyewaan</div>
                                <div class="val">{{ $penyewaan->tujuan_penyewaan }}</div>
                            </div>
                            @endif
                            @if($penyewaan->deskripsi_kegiatan)
                            <div class="ps-field" style="grid-column:1/-1;">
                                <div class="lbl">Deskripsi Kegiatan</div>
                                <div class="val">{{ $penyewaan->deskripsi_kegiatan }}</div>
                            </div>
                            @endif
                        </div>
                    </div>{{-- /ps-painting-grid --}}

                    {{-- Alamat Lokasi Penempatan --}}
                    <div class="ps-address-box">
                        <h4>Alamat Lokasi Penempatan</h4>
                        <table style="width:100%;border-collapse:collapse;">
                            @foreach([
                                'Alamat Lengkap' => $penyewaan->alamat_lengkap,
                                'RT / RW'        => ($penyewaan->rt && $penyewaan->rw)
                                                    ? ($penyewaan->rt . ' / ' . $penyewaan->rw)
                                                    : null,
                                'Kel. / Desa'    => $penyewaan->kelurahan_desa,
                                'Kecamatan'      => $penyewaan->kecamatan,
                                'Kota / Kab.'    => $penyewaan->kota_kabupaten,
                                'Provinsi'       => $penyewaan->provinsi,
                                'Kode Pos'       => $penyewaan->kode_pos,
                            ] as $lbl => $val)
                                @if($val && $val !== '-')
                                <tr style="border-bottom:1px solid var(--border);">
                                    <td style="padding:.45rem .75rem .45rem .1rem;font-size:.72rem;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;white-space:nowrap;width:38%;vertical-align:top;">{{ $lbl }}</td>
                                    <td style="padding:.45rem 0;font-size:.82rem;color:var(--navy);font-weight:500;line-height:1.5;">{{ $val }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>{{-- /ps-address-box --}}

                </div>{{-- /ps-card-body --}}
            </div>{{-- /ps-card Detail Koleksi --}}


            {{-- ════════════════════════════
                KONDISI KEAMANAN TEMPAT
            ════════════════════════════ --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Kondisi Keamanan Tempat</h3>
                </div>
                <div class="ps-card-body">
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell {{ $penyewaan->cctv === 'ya' ? 'success' : ($penyewaan->cctv === 'tidak' ? 'danger' : '') }}">
                            <div class="lbl">CCTV</div>
                            <div class="val">
                                @if($penyewaan->cctv === 'ya') ✓ Ada
                                @elseif($penyewaan->cctv === 'tidak') ✗ Tidak Ada
                                @else -
                                @endif
                            </div>
                        </div>
                        <div class="ps-meta-cell {{ $penyewaan->keamanan === 'ya' ? 'success' : ($penyewaan->keamanan === 'tidak' ? 'danger' : '') }}">
                            <div class="lbl">Petugas Keamanan</div>
                            <div class="val">
                                @if($penyewaan->keamanan === 'ya') ✓ Ada
                                @elseif($penyewaan->keamanan === 'tidak') ✗ Tidak Ada
                                @else -
                                @endif
                            </div>
                        </div>
                        <div class="ps-meta-cell {{ $penyewaan->ber_ac === 'ya' ? 'success' : ($penyewaan->ber_ac === 'tidak' ? 'warning' : '') }}">
                            <div class="lbl">Ber-AC</div>
                            <div class="val">
                                @if($penyewaan->ber_ac === 'ya') ✓ Ya
                                @elseif($penyewaan->ber_ac === 'tidak') Tidak
                                @else -
                                @endif
                            </div>
                        </div>
                        <div class="ps-meta-cell {{ $penyewaan->risiko_cuaca === 'tidak' ? 'success' : ($penyewaan->risiko_cuaca === 'ya' ? 'danger' : '') }}">
                            <div class="lbl">Risiko Cuaca</div>
                            <div class="val">
                                @if($penyewaan->risiko_cuaca === 'ya') ⚠ Ada Risiko
                                @elseif($penyewaan->risiko_cuaca === 'tidak') ✓ Aman
                                @else -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ════════════════════════════
                REKENING PENGEMBALIAN DEPOSIT
            ════════════════════════════ --}}
            @if($penyewaan->bank_name || $penyewaan->account_number)
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Rekening Pengembalian Deposit</h3>
                </div>
                <div class="ps-card-body">
                    <div class="ps-data-row">
                        <div class="ps-field"><div class="lbl">Nama Bank</div><div class="val">{{ $penyewaan->bank_name ?? '-' }}</div></div>
                        <div class="ps-field"><div class="lbl">Nomor Rekening</div><div class="val" style="font-family:monospace;">{{ $penyewaan->account_number ?? '-' }}</div></div>
                        <div class="ps-field ps-span-2" style="grid-column:1/-1;"><div class="lbl">Nama Pemilik Rekening</div><div class="val">{{ $penyewaan->account_holder ?? '-' }}</div></div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ════════════════════════════
                 RINGKASAN BIAYA
            ════════════════════════════ --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Ringkasan Biaya</h3>
                </div>
                <div class="ps-card-body">
                    @php
                        $subtotal = ($penyewaan->painting->daily_rate ?? 0) * ($penyewaan->duration_days ?? 0);
                        $deposit  = $penyewaan->deposit_amount !== null
                            ? (int)$penyewaan->deposit_amount
                            : (int)round($subtotal * 0.5);
                        $ongkir   = $penyewaan->shipping_cost ?? null; // null = belum ditentukan
                        $total    = $penyewaan->total_bayar !== null
                            ? (int)$penyewaan->total_bayar
                            : ($subtotal + $deposit + ($ongkir ?? 0));
                    @endphp

                    <div class="ps-cost-wrap">
                        {{-- Baris: Durasi --}}
                        <div class="ps-cost-row">
                            <span class="lbl">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" style="width:13px;height:13px;display:inline;vertical-align:middle;margin-right:.3rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25"/>
                                </svg>
                                Durasi Sewa
                            </span>
                            <span class="val">{{ $penyewaan->duration_days ?? 0 }} hari</span>
                        </div>

                        {{-- Baris: Subtotal --}}
                        <div class="ps-cost-row">
                            <span class="lbl">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" style="width:13px;height:13px;display:inline;vertical-align:middle;margin-right:.3rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                                </svg>
                                Subtotal Sewa
                                <span style="font-size:.72rem;opacity:.7;">({{ $penyewaan->duration_days ?? 0 }} hari × Rp {{ number_format($penyewaan->painting->daily_rate ?? 0, 0, ',', '.') }})</span>
                            </span>
                            <span class="val">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        {{-- Baris: Deposit --}}
                        <div class="ps-cost-row">
                            <span class="lbl">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" style="width:13px;height:13px;display:inline;vertical-align:middle;margin-right:.3rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757"/>
                                </svg>
                                Deposit Keamanan (50%)
                            </span>
                            <span class="val">Rp {{ number_format($deposit, 0, ',', '.') }}</span>
                        </div>

                        {{-- Baris: Ongkir --}}
                        <div class="ps-cost-row">
                            <span class="lbl">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" style="width:13px;height:13px;display:inline;vertical-align:middle;margin-right:.3rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                                </svg>
                                Ongkos Kirim
                            </span>
                            @if($ongkir !== null)
                                <span class="val">Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                            @else
                                <span style="font-size:.78rem;color:rgba(255,255,255,.45);font-style:italic;">
                                    Ditentukan pengelola saat verifikasi
                                </span>
                            @endif
                        </div>

                        {{-- Total --}}
                        <div class="ps-cost-total">
                            <span class="lbl">
                                @if($status === 'menunggu_verifikasi' || $ongkir === null)
                                    Total Estimasi
                                @else
                                    Total Pembayaran
                                @endif
                            </span>
                            <span class="val">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        {{-- Info box --}}
                        @if($status === 'menunggu_verifikasi')
                        <div class="ps-info-box">
                            <p>
                                <strong>ℹ️ Informasi:</strong>
                                Biaya di atas adalah estimasi awal. Ongkos kirim dan total final akan ditentukan oleh pengelola saat memverifikasi pengajuan.
                            </p>
                        </div>
                        @elseif($ongkir === null)
                        <div class="ps-info-box">
                            <p>
                                <strong>ℹ️ Informasi:</strong>
                                Ongkos kirim belum ditentukan. Total di atas belum termasuk ongkos kirim.
                                Deposit akan dikembalikan setelah koleksi diperiksa dan dinyatakan dalam kondisi baik.
                            </p>
                        </div>
                        @else
                        <div class="ps-info-box">
                            <p>
                                <strong>ℹ️ Informasi:</strong>
                                Deposit akan dikembalikan setelah koleksi diperiksa dan dinyatakan dalam kondisi baik.
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>{{-- /ps-card --}}


            {{-- ════════════════════════════
                 DOKUMEN PENDUKUNG
            ════════════════════════════ --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Dokumen Pendukung</h3>
                </div>
                <div class="ps-card-body">
                    @php
                        $documents = $penyewaan->rental_type === 'instansi'
                            ? [
                                ['label'=>'Surat Permohonan','icon'=>'📄','path'=>$penyewaan->upload_surat_pengajuan],
                                ['label'=>'KTP PIC',         'icon'=>'🪪','path'=>$penyewaan->upload_ktp_pic],
                                ['label'=>'NPWP Instansi',   'icon'=>'📋','path'=>$penyewaan->upload_npwp_instansi],
                                ['label'=>'Proposal Acara',  'icon'=>'📑','path'=>$penyewaan->upload_proposal],
                                ['label'=>'Foto Lokasi',     'icon'=>'📸','path'=>$penyewaan->upload_foto_lokasi],
                                ['label'=>'Denah Lokasi',    'icon'=>'🗺️','path'=>$penyewaan->upload_denah],
                              ]
                            : [
                                ['label'=>'KTP',          'icon'=>'🪪','path'=>$penyewaan->upload_ktp],
                                ['label'=>'NPWP',         'icon'=>'📋','path'=>$penyewaan->upload_npwp],
                                ['label'=>'Foto Lokasi',  'icon'=>'📸','path'=>$penyewaan->upload_foto_lokasi],
                                ['label'=>'Denah Lokasi', 'icon'=>'🗺️','path'=>$penyewaan->upload_denah],
                              ];
                    @endphp
                    <div class="ps-doc-grid">
                        @foreach($documents as $doc)
                        <div class="ps-doc-card {{ $doc['path'] ? 'has-file' : '' }}">
                            <div class="ps-doc-icon {{ $doc['path'] ? 'has' : 'none' }}">{{ $doc['icon'] }}</div>
                            <div class="ps-doc-label">{{ $doc['label'] }}</div>
                            <div class="ps-doc-status">{{ $doc['path'] ? 'Dokumen tersedia' : 'Tidak mengirimkan' }}</div>
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

            {{-- ════════════════════════════
                 RIWAYAT DOKUMEN
            ════════════════════════════ --}}
            @if($penyewaan->agreement_document_path || $penyewaan->invoice_document_path || $serahTerima?->handover_document_path || $serahTerima?->return_document_path)
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Riwayat Dokumen</h3>
                </div>
                <div class="ps-card-body">
                    <div class="ps-doc-grid">
                        @if($penyewaan->agreement_document_path)
                        <div class="ps-doc-card has-file">
                            <div class="ps-doc-icon has">📝</div>
                            <div class="ps-doc-label">Surat Perjanjian</div>
                            <div class="ps-doc-status">Dokumen tersedia</div>
                            <div class="ps-doc-actions">
                                <a href="{{ route('penyewaan.requests.agreement.download', $penyewaan) }}" class="ps-doc-btn ps-doc-btn-primary">Unduh</a>
                            </div>
                        </div>
                        @endif
                        @if($penyewaan->invoice_document_path)
                        <div class="ps-doc-card has-file">
                            <div class="ps-doc-icon has">🧾</div>
                            <div class="ps-doc-label">Invoice Pembayaran</div>
                            <div class="ps-doc-status">Bukti transaksi pembayaran</div>
                            <div class="ps-doc-actions">
                                <a href="{{ route('penyewaan.requests.invoice.download', ['penyewaan' => $penyewaan->id]) }}" class="ps-doc-btn ps-doc-btn-primary">Unduh</a>
                            </div>
                        </div>
                        @endif
                        @if($serahTerima?->handover_document_path)
                        <div class="ps-doc-card has-file">
                            <div class="ps-doc-icon has">📦</div>
                            <div class="ps-doc-label">Dokumen Serah Terima</div>
                            <div class="ps-doc-status">Dokumen serah terima koleksi</div>
                            <div class="ps-doc-actions">
                                <a href="{{ route('penyewaan.requests.handover.download', ['penyewaan' => $penyewaan->id]) }}" class="ps-doc-btn ps-doc-btn-primary">Unduh</a>
                            </div>
                        </div>
                        @endif
                        @if($serahTerima?->return_document_path)
                        <div class="ps-doc-card has-file">
                            <div class="ps-doc-icon has">🔄</div>
                            <div class="ps-doc-label">Dokumen Pengembalian</div>
                            <div class="ps-doc-status">Dokumen serah terima pengembalian</div>
                            <div class="ps-doc-actions">
                                <a href="{{ route('penyewaan.requests.handover.download-initial-return', ['penyewaan' => $penyewaan->id]) }}" class="ps-doc-btn ps-doc-btn-primary">Unduh</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- ── DOKUMENTASI KONDISI KOLEKSI SAAT DIKIRIM ── --}}
            @if($serahTerima && ($serahTerima->dispatch_front_photo || $serahTerima->dispatch_back_photo || $serahTerima->dispatch_packing_photos || $serahTerima->dispatch_video_path))
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Dokumentasi Kondisi Koleksi Saat Dikirim</h3>
                </div>
                <div class="ps-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

                    @if($serahTerima->dispatch_front_photo || $serahTerima->dispatch_back_photo)
                    <div>
                        <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Koleksi</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                            @if($serahTerima->dispatch_front_photo)
                            <div>
                                <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Depan</div>
                                <img src="{{ asset('storage/' . $serahTerima->dispatch_front_photo) }}"
                                    style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;cursor:zoom-in;"
                                    alt="Foto Depan Koleksi"
                                    onclick="openDispatchLightbox(this.src, this.alt)">
                            </div>
                            @endif
                            @if($serahTerima->dispatch_back_photo)
                            <div>
                                <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Belakang</div>
                                <img src="{{ asset('storage/' . $serahTerima->dispatch_back_photo) }}"
                                    style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;cursor:zoom-in;"
                                    alt="Foto Belakang Koleksi"
                                    onclick="openDispatchLightbox(this.src, this.alt)">
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(($serahTerima->dispatch_packing_photos && count($serahTerima->dispatch_packing_photos) > 0) || $serahTerima->dispatch_video_path)
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                        @if($serahTerima->dispatch_packing_photos && count($serahTerima->dispatch_packing_photos) > 0)
                        <div>
                            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Packing</div>
                            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(100px,1fr));gap:.5rem;">
                                @foreach($serahTerima->dispatch_packing_photos as $photo)
                                <img src="{{ asset('storage/' . $photo) }}"
                                    style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;height:220px;cursor:zoom-in;"
                                    alt="Foto Packing"
                                    onclick="openDispatchLightbox(this.src, this.alt)">
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($serahTerima->dispatch_video_path)
                        <div>
                            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Video Kondisi Koleksi</div>
                            <video controls style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);height:220px;background:#000;">
                                <source src="{{ asset('storage/' . $serahTerima->dispatch_video_path) }}" type="video/mp4">
                            </video>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Lightbox dokumentasi dispatch --}}
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
                                if (ov && ov.style.display !== 'none') {
                                    ov.style.display = 'none';
                                    document.getElementById('dispatch-lightbox-img').src = '';
                                    document.body.style.overflow = '';
                                }
                            }
                        });
                    </script>
                </div>
            </div>
            @endif

            @include('penyewaan.partials.condition-documentation')

        </div>
    </div>
</x-app-layout>