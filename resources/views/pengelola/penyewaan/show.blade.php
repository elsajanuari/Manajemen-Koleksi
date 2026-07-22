<x-app-layout>
    <x-slot name="header">{{-- kosong, hero di dalam --}}</x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    @php
        $status      = $penyewaan->status ?? 'draft';
        $serahTerima = $penyewaan->serahTerima;
        $isDamageCompensation = $serahTerima && $serahTerima->isDamageCompensation();
        $isDamageCancellation = $serahTerima && $serahTerima->isDamageCancellation();
        $sisaHari    = $penyewaan->end_date
            ? (int) now()->startOfDay()->diffInDays($penyewaan->end_date->startOfDay(), false)
            : null;

        $statusBadgeClass = match ($status) {
            'menunggu_verifikasi'            => 'st-amber',
            'menunggu_dokumen_perjanjian'    => 'st-blue',
            'verifikasi_dokumen_perjanjian'  => 'st-indigo',
            'menunggu_pembayaran'            => 'st-orange',
            'pengiriman'                     => 'st-sky',
            'siap_diserahkan'                => 'st-blue',
            'dalam_pengiriman'               => 'st-sky',   
            'menunggu_data_rekening'         => 'st-amber',
            'menunggu_review_kerusakan'      => 'st-red',
            'menunggu_refund_kerusakan'      => 'st-orange',
            'menunggu_penerimaan_koleksi'    => 'st-sky',
            'pengecekan_kondisi'             => 'st-amber',
            'menunggu_dokumen_serah_terima'  => 'st-slate',
            'verifikasi_serah_terima'        => 'st-purple',
            'aktif'                          => 'st-emerald',
            'pengembalian'                   => 'st-teal',
            'menunggu_konfirmasi_refund'     => 'st-teal',
            'menunggu_ttd_pengembalian'      => 'st-indigo',
            'menunggu_pembayaran_kerusakan'  => 'st-red',
            'menunggu_konfirmasi_selesai'    => 'st-emerald',
            'selesai'                        => 'st-green',
            'ditolak'                        => 'st-red',
            'dibatalkan'                     => 'st-slate',
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
            'menunggu_data_rekening'         => 'Menunggu Data Rekening',
            'menunggu_review_kerusakan'      => 'Review Kerusakan',
            'menunggu_refund_kerusakan'      => 'Proses Transfer Kompensasi',
            'menunggu_penerimaan_koleksi'    => 'Penerimaan Koleksi Balik',
            'pengecekan_kondisi'             => 'Pengecekan Kondisi',
            'menunggu_dokumen_serah_terima'  => 'Menunggu Dok. Serah Terima',
            'verifikasi_serah_terima'        => 'Verifikasi Serah Terima',
            'aktif'                          => 'Aktif',
            'pengembalian'                   => 'Proses Pengembalian',
            'menunggu_konfirmasi_refund'     => 'Proses Refund Deposit',
            'menunggu_ttd_pengembalian'      => 'TTD Dok. Pengembalian',
            'menunggu_pembayaran_kerusakan'  => 'Tagihan Kerusakan',
            'menunggu_konfirmasi_selesai'    => 'Konfirmasi Selesai',
            'selesai'                        => 'Selesai',
            'ditolak'                        => 'Ditolak',
            'dibatalkan'                     => 'Dibatalkan',
            default                          => ucfirst(str_replace('_', ' ', $status)),
        };

        $progressStep = match ($status) {
            'menunggu_verifikasi'                                             => 1,
            'menunggu_dokumen_perjanjian', 'verifikasi_dokumen_perjanjian'    => 2,
            'menunggu_pembayaran'                                             => 3,
            'pengiriman'                                                      => 4,
            'siap_diserahkan'                                                 => 4,
            'dalam_pengiriman', 'pengecekan_kondisi',
            'menunggu_review_kerusakan', 'menunggu_data_rekening',
            'menunggu_penerimaan_koleksi', 'menunggu_refund_kerusakan'        => 4,
            'menunggu_dokumen_serah_terima', 'verifikasi_serah_terima'        => 5,
            'aktif'                                                           => 6,
            'pengembalian',
            'menunggu_konfirmasi_refund',
            'menunggu_ttd_pengembalian',
            'menunggu_pembayaran_kerusakan',
            'menunggu_konfirmasi_selesai',
            'selesai'                                                         => 7,
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

        $hargaSewa = $penyewaan->painting->daily_rate ?? $penyewaan->painting->rental_price ?? 0;
        $durasi    = $penyewaan->duration_days;
        $subtotal  = $hargaSewa * $durasi;
        $deposit   = $penyewaan->calculateDeposit();
    @endphp

    <style>
        :root {
            --navy:   #0b1d35; --navy-2: #142744; --blue: #1d4ed8;
            --sky:    #38bdf8; --cream:  #f2f5f9; --slate: #64748b;
            --border: #e2e8f0; --white:  #ffffff;
        }
        * { box-sizing: border-box; }
        .ps-root { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; padding-bottom: 4rem; }

        /* ── HERO ── */
        .ps-hero { background: linear-gradient(140deg,#0b1d35 0%,#142744 55%,#1c3a68 100%); padding: 2.25rem 0; position: relative; overflow: hidden; }
        .ps-hero::before { content: ''; position: absolute; top: -60px; right: -80px; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events: none; }
        .ps-hero-inner { max-width: 1100px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 1; }
        .ps-hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; }

        .ps-breadcrumb { display: flex; align-items: center; gap: .45rem; margin-bottom: .85rem; }
        .ps-breadcrumb a { color: rgba(255,255,255,.45); font-size: .75rem; font-weight: 500; text-decoration: none; transition: color .15s; }
        .ps-breadcrumb a:hover { color: var(--sky); }
        .ps-breadcrumb-sep { color: rgba(255,255,255,.25); font-size: .7rem; }
        .ps-breadcrumb-cur { color: rgba(255,255,255,.7); font-size: .75rem; font-weight: 600; }

        .ps-hero-id { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; color: #fff; line-height: 1.2; margin: 0 0 .3rem; }
        .ps-hero-title { font-size: .88rem; color: rgba(255,255,255,.55); margin: 0; }

        .ps-hero-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: flex-start; padding-top: .25rem; }
        .ps-hero-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.2rem; border-radius: .875rem; font-size: .8rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .18s; border: none; cursor: pointer; white-space: nowrap; }
        .ps-hero-btn svg { width: 13px; height: 13px; }
        .ps-hero-btn-back { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15); color: rgba(255,255,255,.85); }
        .ps-hero-btn-back:hover { background: rgba(255,255,255,.17); }
        .ps-hero-btn-st { background: rgba(56,189,248,.15); border: 1px solid rgba(56,189,248,.3); color: var(--sky); }
        .ps-hero-btn-st:hover { background: rgba(56,189,248,.25); }

        /* STATUS BADGE */
        .ps-status-badge { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem 1rem; border-radius: 99px; font-size: .72rem; font-weight: 700; letter-spacing: .04em; margin-top: .75rem; }
        .ps-status-dot { width: 6px; height: 6px; border-radius: 50%; }
        .st-amber   { background: rgba(251,191,36,.15);  border: 1px solid rgba(251,191,36,.3);  color: #fbbf24; }
        .st-amber   .ps-status-dot { background: #fbbf24; }
        .st-orange  { background: rgba(249,115,22,.15);  border: 1px solid rgba(249,115,22,.3);  color: #fb923c; }
        .st-orange  .ps-status-dot { background: #fb923c; }
        .st-emerald { background: rgba(52,211,153,.15);  border: 1px solid rgba(52,211,153,.3);  color: #34d399; }
        .st-emerald .ps-status-dot { background: #34d399; }
        .st-blue    { background: rgba(96,165,250,.15);  border: 1px solid rgba(96,165,250,.3);  color: #60a5fa; }
        .st-blue    .ps-status-dot { background: #60a5fa; }
        .st-sky     { background: rgba(56,189,248,.15);  border: 1px solid rgba(56,189,248,.3);  color: var(--sky); }
        .st-sky     .ps-status-dot { background: var(--sky); }
        .st-indigo  { background: rgba(129,140,248,.15); border: 1px solid rgba(129,140,248,.3); color: #818cf8; }
        .st-indigo  .ps-status-dot { background: #818cf8; }
        .st-purple  { background: rgba(168,85,247,.15);  border: 1px solid rgba(168,85,247,.3);  color: #c084fc; }
        .st-purple  .ps-status-dot { background: #c084fc; }
        .st-teal    { background: rgba(45,212,191,.15);  border: 1px solid rgba(45,212,191,.3);  color: #2dd4bf; }
        .st-teal    .ps-status-dot { background: #2dd4bf; }
        .st-green   { background: rgba(74,222,128,.15);  border: 1px solid rgba(74,222,128,.3);  color: #4ade80; }
        .st-green   .ps-status-dot { background: #4ade80; }
        .st-red     { background: rgba(248,113,113,.15); border: 1px solid rgba(248,113,113,.3); color: #f87171; }
        .st-red     .ps-status-dot { background: #f87171; }
        .st-slate   { background: rgba(148,163,184,.1);  border: 1px solid rgba(148,163,184,.2); color: #94a3b8; }
        .st-slate   .ps-status-dot { background: #94a3b8; }

        /* CONTENT */
        .ps-content { max-width: 1100px; margin: 0 auto; padding: 1.75rem 2rem 0; display: grid; gap: 1.25rem; }

        /* FLASH */
        .ps-flash { border-radius: .875rem; padding: .85rem 1.2rem; font-size: .83rem; font-weight: 600; display: flex; align-items: center; gap: .55rem; }
        .ps-flash svg { width: 16px; height: 16px; flex-shrink: 0; }
        .ps-flash.ok  { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .ps-flash.err { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

        /* CARD */
        .ps-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.5rem; box-shadow: 0 4px 24px rgba(11,29,53,.05); overflow: hidden; }
        .ps-card-header { padding: 1.1rem 1.5rem; border-bottom: 1.5px solid #f0f4f8; display: flex; align-items: center; gap: .55rem; }
        .ps-card-header-accent { width: 3px; height: 16px; background: linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius: 99px; flex-shrink: 0; }
        .ps-card-header h3 { font-size: .76rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--navy); margin: 0; }
        .ps-card-body { padding: 1.5rem; }

        /* PROGRESS */
        .ps-step-circle { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .82rem; font-weight: 700; flex-shrink: 0; transition: all .3s; }
        .ps-step-circle.done    { background: linear-gradient(135deg,#059669,#10b981); color: #fff; box-shadow: 0 0 0 4px rgba(16,185,129,.12); }
        .ps-step-circle.active  { background: linear-gradient(135deg,#1d4ed8,#38bdf8); color: #fff; box-shadow: 0 0 0 4px rgba(29,78,216,.18); }
        .ps-step-circle.pending { background: #f1f5f9; color: #94a3b8; border: 2px solid #e2e8f0; }
        .ps-step-label { font-size: .68rem; font-weight: 600; text-align: center; white-space: nowrap; }
        .ps-step-label.done    { color: #059669; }
        .ps-step-label.active  { color: var(--blue); font-weight: 700; }
        .ps-step-label.pending { color: #94a3b8; }
        .ps-step-line { flex: 1; height: 2px; margin: 0 .25rem; margin-bottom: 1.3rem; border-radius: 99px; }
        .ps-step-line.done    { background: linear-gradient(90deg,#10b981,#34d399); }
        .ps-step-line.pending { background: #e2e8f0; }

        /* STATUS SECTIONS */
        .ps-status-section { border-radius: 1.25rem; padding: 1.5rem; }
        .ps-status-section .ps-eyebrow { font-size: .67rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; margin-bottom: .5rem; }
        .ps-status-section h2 { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: var(--navy); margin: 0 0 .5rem; }
        .ps-status-section p  { font-size: .84rem; line-height: 1.7; color: #475569; margin: 0; }

        .ps-section-verifikasi    { background: #fffbeb; border: 1.5px solid #fde68a; }
        .ps-section-verifikasi    .ps-eyebrow { color: #d97706; }
        .ps-section-perjanjian    { background: #eff6ff; border: 1.5px solid #bfdbfe; }
        .ps-section-perjanjian    .ps-eyebrow { color: #1d4ed8; }
        .ps-section-validasi-doc  { background: #f5f3ff; border: 1.5px solid #ddd6fe; }
        .ps-section-validasi-doc  .ps-eyebrow { color: #6d28d9; }
        .ps-section-pembayaran    { background: #fff7ed; border: 1.5px solid #fed7aa; }
        .ps-section-pembayaran    .ps-eyebrow { color: #c2410c; }
        .ps-section-pengiriman    { background: #f0f9ff; border: 1.5px solid #bae6fd; }
        .ps-section-pengiriman    .ps-eyebrow { color: #0369a1; }
        .ps-section-serah-terima  { background: #eef2ff; border: 1.5px solid #c7d2fe; }
        .ps-section-serah-terima  .ps-eyebrow { color: #4338ca; }
        .ps-section-validasi-st   { background: #faf5ff; border: 1.5px solid #e9d5ff; }
        .ps-section-validasi-st   .ps-eyebrow { color: #7c3aed; }
        .ps-section-aktif         { background: #f0fdf4; border: 1.5px solid #bbf7d0; }
        .ps-section-aktif         .ps-eyebrow { color: #166534; }
        .ps-section-pengembalian  { background: #f0fdfa; border: 1.5px solid #99f6e4; }
        .ps-section-pengembalian  .ps-eyebrow { color: #0f766e; }
        .ps-section-selesai       { background: #f0fdf4; border: 1.5px solid #bbf7d0; }
        .ps-section-selesai       .ps-eyebrow { color: #166534; }
        .ps-section-ditolak       { background: #fef2f2; border: 1.5px solid #fecaca; }
        .ps-section-ditolak       .ps-eyebrow { color: #dc2626; }
        .ps-section-dibatalkan    { background: #f8fafc; border: 1.5px solid #e2e8f0; }
        .ps-section-dibatalkan    .ps-eyebrow { color: #64748b; }

        /* META GRID */
        .ps-meta-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(160px,1fr)); gap: .875rem; margin-top: 1.1rem; }
        .ps-meta-cell { background: var(--white); border: 1.5px solid var(--border); border-radius: 1rem; padding: .9rem 1rem; }
        .ps-meta-cell .lbl { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .3rem; }
        .ps-meta-cell .val { font-size: .9rem; font-weight: 700; color: var(--navy); }
        .ps-meta-cell.highlight { background: linear-gradient(135deg,#eff6ff,#dbeafe); border-color: #bfdbfe; }
        .ps-meta-cell.highlight .val { color: var(--blue); }
        .ps-meta-cell.success   { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border-color: #bbf7d0; }
        .ps-meta-cell.success   .val { color: #059669; }
        .ps-meta-cell.warning   { background: linear-gradient(135deg,#fffbeb,#fef3c7); border-color: #fde68a; }
        .ps-meta-cell.warning   .val { color: #d97706; }
        .ps-meta-cell.danger  { background: linear-gradient(135deg,#fef2f2,#fee2e2); border-color: #fecaca; }
        .ps-meta-cell.danger  .val { color: #dc2626; }

        /* APPROVE / REJECT GRID */
        .ps-approve-grid { display: grid; grid-template-columns: 1fr; gap: 1rem; margin-top: 1.25rem; }
        @media(max-width:768px){ .ps-approve-grid { grid-template-columns: 1fr; } }
        .ps-approve-panel { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.25rem; padding: 1.25rem; display: flex; flex-direction: column; }
        .ps-approve-panel.approve-panel { border-color: #bbf7d0; }
        .ps-approve-panel.reject-panel  { border-color: #fecaca; }
        .ps-panel-tag { display: inline-flex; align-items: center; gap: .3rem; padding: .28rem .85rem; border-radius: 99px; font-size: .7rem; font-weight: 700; letter-spacing: .06em; align-self: flex-start; margin-bottom: .85rem; }
        .ps-panel-tag.approve { background: #d1fae5; color: #065f46; }
        .ps-panel-tag.reject  { background: #fee2e2; color: #991b1b; }
        .ps-approve-panel p { font-size: .83rem; color: #475569; line-height: 1.65; flex: 1; margin: 0 0 1rem; }

        /* SHIPPING INFO BOX */
        .ps-shipping-info { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; padding: .875rem 1rem; font-size: .8rem; color: #475569; margin-bottom: 1rem; }
        .ps-shipping-info strong { color: var(--navy); display: block; font-size: .72rem; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .4rem; }

        /* FORM ELEMENTS */
        .ps-form-label { display: block; font-size: .75rem; font-weight: 600; color: var(--navy); margin-bottom: .4rem; }
        .ps-form-label span.req { color: #ef4444; }
        .ps-form-input { width: 100%; border: 1.5px solid var(--border); border-radius: .875rem; padding: .6rem .9rem; font-size: .83rem; font-family: 'DM Sans', sans-serif; color: var(--navy); background: #f8fafc; outline: none; transition: border-color .2s, box-shadow .2s; }
        .ps-form-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(29,78,216,.09); background: var(--white); }
        .ps-form-input[readonly] { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }
        .ps-form-textarea { width: 100%; border: 1.5px solid var(--border); border-radius: .875rem; padding: .7rem .9rem; font-size: .83rem; font-family: 'DM Sans', sans-serif; color: var(--navy); background: #f8fafc; outline: none; resize: vertical; transition: border-color .2s; }
        .ps-form-textarea:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(29,78,216,.09); background: var(--white); }
        .ps-form-group { margin-bottom: .875rem; }
        .ps-radio-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; margin-bottom: .875rem; }
        .ps-radio-label { display: flex; align-items: center; gap: .6rem; background: var(--white); border: 1.5px solid var(--border); border-radius: .875rem; padding: .65rem .9rem; cursor: pointer; font-size: .82rem; font-weight: 500; transition: border-color .15s, background .15s; }
        .ps-radio-label:has(input:checked) { border-color: #10b981; background: #f0fdf4; }
        .ps-radio-label.courier:has(input:checked) { border-color: var(--blue); background: #eff6ff; }

        /* VALIDASI GRID */
        .ps-validasi-grid { display: grid; grid-template-columns: 1fr 360px; gap: 1.25rem; margin-top: 1.25rem; align-items: start; }
        @media(max-width:900px){ .ps-validasi-grid { grid-template-columns: 1fr; } }
        .ps-doc-preview-box { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.25rem; padding: 1.1rem; }
        .ps-doc-preview-box .preview-head { display: flex; align-items: center; justify-content: space-between; gap: .75rem; margin-bottom: .875rem; flex-wrap: wrap; }
        .ps-doc-preview-box .preview-title { font-size: .82rem; font-weight: 700; color: var(--navy); }
        .ps-doc-preview-box .preview-sub { font-size: .72rem; color: var(--slate); }
        .ps-doc-preview-actions { display: flex; gap: .4rem; flex-wrap: wrap; }

        /* ACTION BUTTONS */
        .ps-action-row { margin-top: 1.1rem; display: flex; gap: .65rem; flex-wrap: wrap; }
        .ps-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .65rem 1.35rem; border-radius: .875rem; font-size: .82rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .2s; border: none; cursor: pointer; }
        .ps-btn svg { width: 14px; height: 14px; }
        .ps-btn-navy    { background: var(--navy); color: #fff; }
        .ps-btn-navy:hover    { background: var(--blue); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(29,78,216,.3); }
        .ps-btn-blue    { background: linear-gradient(135deg,var(--blue),#2563eb); color: #fff; }
        .ps-btn-blue:hover    { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(29,78,216,.35); }
        .ps-btn-emerald { background: linear-gradient(135deg,#059669,#10b981); color: #fff; }
        .ps-btn-emerald:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(16,185,129,.3); }
        .ps-btn-green   { background: linear-gradient(135deg,#16a34a,#22c55e); color: #fff; }
        .ps-btn-green:hover   { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(34,197,94,.3); }
        .ps-btn-sky     { background: linear-gradient(135deg,#0284c7,var(--sky)); color: #fff; }
        .ps-btn-sky:hover     { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(56,189,248,.3); }
        .ps-btn-indigo  { background: linear-gradient(135deg,#4338ca,#6366f1); color: #fff; }
        .ps-btn-indigo:hover  { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(99,102,241,.3); }
        .ps-btn-violet  { background: linear-gradient(135deg,#7c3aed,#8b5cf6); color: #fff; }
        .ps-btn-violet:hover  { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(124,58,237,.3); }
        .ps-btn-teal    { background: linear-gradient(135deg,#0f766e,#14b8a6); color: #fff; }
        .ps-btn-teal:hover    { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(20,184,166,.3); }
        .ps-btn-amber   { background: linear-gradient(135deg,#d97706,#f59e0b); color: #fff; }
        .ps-btn-amber:hover   { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(245,158,11,.3); }
        .ps-btn-danger  { background: transparent; border: 1.5px solid #fca5a5; color: #dc2626; }
        .ps-btn-danger:hover  { background: #fef2f2; }
        .ps-btn-ghost   { background: transparent; border: 1.5px solid var(--border); color: var(--slate); }
        .ps-btn-ghost:hover   { background: #f8fafc; }

        /* DATA ROWS */
        .ps-data-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        @media(max-width:640px){ .ps-data-row { grid-template-columns: 1fr; } }
        .ps-field .lbl { font-size: .72rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .07em; margin-bottom: .25rem; }
        .ps-field .val { font-size: .88rem; color: var(--navy); font-weight: 500; line-height: 1.5; }

        /* ADDRESS BOX */
        .ps-address-box { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; padding: 1.1rem; }
        .ps-address-box h4 { font-size: .76rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--navy); margin: 0 0 .75rem; display: flex; align-items: center; gap: .4rem; }
        .ps-address-box h4::before { content: ''; width: 3px; height: 12px; background: linear-gradient(180deg,var(--blue),var(--sky)); border-radius: 99px; }
        .ps-address-main { font-size: .85rem; color: #334155; line-height: 1.65; margin-bottom: .75rem; }
        .ps-address-chips { display: flex; gap: .5rem; flex-wrap: wrap; }
        .ps-address-chip { background: var(--white); border: 1.5px solid var(--border); border-radius: .6rem; padding: .3rem .7rem; font-size: .72rem; color: var(--slate); }
        .ps-address-chip strong { color: var(--navy); }

        /* PAINTING */
        .ps-painting-grid { display: grid; grid-template-columns: 220px 1fr; gap: 1.25rem; align-items: start; }
        @media(max-width:640px){ .ps-painting-grid { grid-template-columns: 1fr; } }
        .ps-painting-thumb { border-radius: 1rem; overflow: hidden; aspect-ratio: 1; background: #f1f5f9; }
        .ps-painting-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .ps-painting-thumb-empty { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #b0bac6; font-size: .83rem; min-height: 200px; }
        .ps-painting-fields { display: grid; grid-template-columns: 1fr 1fr; gap: .875rem; }
        @media(max-width:480px){ .ps-painting-fields { grid-template-columns: 1fr; } }

        /* COST CARD */
        .ps-cost-wrap { background: linear-gradient(135deg,#0b1d35,#142744); border-radius: 1.25rem; padding: 1.5rem; }
        .ps-cost-row { display: flex; justify-content: space-between; align-items: center; padding: .5rem 0; border-bottom: 1px solid rgba(255,255,255,.07); font-size: .84rem; }
        .ps-cost-row:last-child { border-bottom: none; }
        .ps-cost-row .lbl { color: rgba(255,255,255,.55); }
        .ps-cost-row .val { font-weight: 600; color: #fff; }
        .ps-cost-total { margin-top: .75rem; padding-top: .75rem; display: flex; justify-content: space-between; align-items: center; border-top: 1.5px solid rgba(255,255,255,.12); }
        .ps-cost-total .lbl { font-size: .8rem; color: rgba(255,255,255,.5); font-weight: 600; }
        .ps-cost-total .val { font-family: 'Playfair Display', serif; font-size: 1.4rem; color: #fff; }
        .ps-info-box { background: rgba(56,189,248,.07); border: 1px solid rgba(56,189,248,.2); border-radius: .875rem; padding: .875rem 1.1rem; margin-top: .75rem; }
        .ps-info-box p { font-size: .78rem; color: rgba(255,255,255,.65); line-height: 1.65; margin: 0; }
        .ps-info-box strong { color: var(--sky); }

        /* DOCS */
        .ps-doc-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(200px,1fr)); gap: .875rem; }
        .ps-doc-card { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; padding: 1rem; }
        .ps-doc-card.has-file { border-color: #bfdbfe; background: #eff6ff; }
        .ps-doc-icon { width: 36px; height: 36px; border-radius: .65rem; display: flex; align-items: center; justify-content: center; font-size: 1rem; margin-bottom: .65rem; }
        .ps-doc-icon.has  { background: linear-gradient(135deg,#dbeafe,#bfdbfe); }
        .ps-doc-icon.none { background: #f1f5f9; }
        .ps-doc-label  { font-size: .82rem; font-weight: 700; color: var(--navy); margin-bottom: .2rem; }
        .ps-doc-status { font-size: .72rem; color: var(--slate); margin-bottom: .65rem; }
        .ps-doc-actions { display: flex; gap: .4rem; }
        .ps-doc-btn { display: inline-flex; align-items: center; gap: .25rem; padding: .35rem .75rem; border-radius: .55rem; font-size: .72rem; font-weight: 600; text-decoration: none; transition: all .15s; }
        .ps-doc-btn-primary { background: var(--navy); color: #fff; }
        .ps-doc-btn-primary:hover { background: var(--blue); }
        .ps-doc-btn-ghost { background: var(--white); border: 1.5px solid var(--border); color: var(--slate); }
        .ps-doc-btn-ghost:hover { border-color: var(--blue); color: var(--blue); }

        /* CATATAN */
        .ps-catatan { margin-top: 1rem; background: var(--white); border: 1.5px solid var(--border); border-radius: 1rem; padding: 1rem 1.1rem; }
        .ps-catatan .lbl { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .35rem; }
        .ps-catatan .val { font-size: .84rem; color: #334155; line-height: 1.65; }

        /* COURIER LIST */
        .ps-courier-list { display: flex; flex-direction: column; gap: .5rem; margin-top: .5rem; }
        .ps-courier-item { display: flex; align-items: center; justify-content: space-between; gap: .75rem; background: #f8fafc; border: 1.5px solid var(--border); border-radius: .875rem; padding: .7rem .9rem; cursor: pointer; font-size: .81rem; transition: border-color .15s, background .15s; }
        .ps-courier-item:has(input:checked) { border-color: var(--blue); background: #eff6ff; }
        .ps-courier-item-left { display: flex; align-items: center; gap: .6rem; flex: 1; min-width: 0; }
        .ps-courier-name { font-weight: 600; color: var(--navy); }
        .ps-courier-etd  { font-size: .71rem; color: var(--slate); }
        .ps-courier-cost { font-weight: 700; color: var(--blue); white-space: nowrap; }

        /* DOC INFO CARD (perjanjian/invoice) */
        .ps-doc-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .875rem; margin-top: 1rem; }
        @media(max-width:640px){ .ps-doc-info-grid { grid-template-columns: 1fr; } }
        .ps-doc-info-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.1rem; padding: 1.1rem; }
        .ps-doc-info-card .title { font-size: .84rem; font-weight: 700; color: var(--navy); margin-bottom: .25rem; }
        .ps-doc-info-card .sub   { font-size: .73rem; color: var(--slate); margin-bottom: .875rem; }

        /* CATATAN INLINE */
        .ps-inline-catatan { background: rgba(56,189,248,.06); border: 1px solid rgba(56,189,248,.2); border-radius: .875rem; padding: .75rem 1rem; margin-top: .875rem; font-size: .78rem; color: rgba(11,29,53,.75); line-height: 1.65; }

        @keyframes spin { to { transform: rotate(360deg); } }

        @media(max-width:768px){
            .ps-content    { padding: 1.25rem 1rem 0; }
            .ps-hero-inner { padding: 0 1rem; }
        }
    </style>

    <div class="ps-root">

        {{-- ── HERO ── --}}
        <div class="ps-hero">
            <div class="ps-hero-inner">
                <div class="ps-hero-top">
                    <div>
                        <div class="ps-breadcrumb">
                            <a href="{{ route('pengelola.penyewaan.index') }}">Daftar Penyewaan</a>
                            <span class="ps-breadcrumb-sep">/</span>
                            <span class="ps-breadcrumb-cur">{{ $penyewaan->nomor_pengajuan ?? 'SW-' . str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <h1 class="ps-hero-id">{{ $penyewaan->nomor_pengajuan ?? 'SW-' . str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</h1>
                        <p class="ps-hero-title">{{ $penyewaan->painting->title }} &mdash; {{ $penyewaan->painting->artist }}</p>
                        <div class="ps-status-badge {{ $statusBadgeClass }}">
                            <span class="ps-status-dot"></span>
                            {{ $statusLabel }}
                        </div>
                    </div>
                    <div class="ps-hero-actions">
                        <a href="{{ route('pengelola.penyewaan.index') }}" class="ps-hero-btn ps-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali
                        </a>
                        @if(in_array($status, [
                            'pengiriman','siap_diserahkan','dalam_pengiriman','pengecekan_kondisi',
                            'menunggu_data_rekening','menunggu_penerimaan_koleksi','menunggu_refund_kerusakan',
                            'menunggu_dokumen_serah_terima','verifikasi_serah_terima',
                            'aktif','pengembalian',
                            'menunggu_konfirmasi_refund','menunggu_ttd_pengembalian',
                            'menunggu_pembayaran_kerusakan','menunggu_konfirmasi_selesai',
                            'selesai','menunggu_review_kerusakan'
                        ]) && Route::has('pengelola.penyewaan.handover.show'))
                            <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-hero-btn ps-hero-btn-st">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                                Kelola Serah Terima
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

            {{-- ══════════════════════════════════════════
                 STATUS: menunggu_verifikasi
            ══════════════════════════════════════════ --}}
            @if($status === 'menunggu_verifikasi')
            <div class="ps-status-section ps-section-verifikasi">
                <div class="ps-eyebrow">⚡ Aksi Diperlukan</div>
                <h2>Verifikasi Pengajuan Penyewaan</h2>
                <p>Tinjau data penyewa dan koleksi di bawah, tentukan metode pengiriman, lalu pilih keputusan verifikasi.</p>

                <div class="ps-approve-grid">
                    {{-- PANEL SETUJUI --}}
                    <div class="ps-approve-panel approve-panel">
                        <div class="ps-panel-tag approve">✓ SETUJUI</div>
                        <p>Pengajuan disetujui, dokumen perjanjian digenerate otomatis dan dikirimkan ke penyewa.</p>

                        <form action="{{ route('pengelola.penyewaan.approve', $penyewaan) }}" method="POST" id="form-approve-sw">
                            @csrf

                            @if($zonaSummary)
                            <div class="ps-shipping-info">
                                <strong>📦 Info Pengiriman</strong>
                                Tujuan: <strong>{{ $penyewaan->kota_kabupaten }}, {{ $penyewaan->provinsi }}</strong><br>
                                @if($zonaSummary['is_free'])
                                    <span style="color:#059669;font-weight:600;">✅ Wilayah Purwakarta — Ongkir GRATIS</span>
                                @else
                                    Zona: <strong>{{ $zonaSummary['zone']->zone_name }}</strong>
                                    — {{ $zonaSummary['zone']->description }}<br>
                                    Tarif default pengelola: <strong>Rp {{ number_format($zonaSummary['default_rate'], 0, ',', '.') }}</strong>
                                @endif
                            </div>
                            @endif

                            <div class="ps-form-group">
                                <label class="ps-form-label">Metode Pengiriman <span class="req">*</span></label>
                                <div class="ps-radio-grid">
                                    <label class="ps-radio-label">
                                        <input type="radio" name="shipping_method_type" value="manager" checked onchange="sw_handleMethodChange('manager')">
                                        🏛️ <span>Dikirim Pengelola</span>
                                    </label>
                                    <label class="ps-radio-label courier">
                                        <input type="radio" name="shipping_method_type" value="courier" onchange="sw_handleMethodChange('courier')">
                                        🚚 <span>Kurir</span>
                                    </label>
                                </div>
                            </div>

                            <div id="sw-courier-section" style="display:none;" class="ps-form-group">
                                <div id="sw-courier-loading" style="display:none;align-items:center;gap:.5rem;font-size:.78rem;color:var(--slate);padding:.5rem 0;">
                                    <svg style="width:14px;height:14px;animation:spin .7s linear infinite;color:var(--blue);" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle opacity=".25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path opacity=".75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    Mengambil tarif kurir…
                                </div>
                                <div id="sw-courier-error" style="display:none;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.875rem;padding:.75rem 1rem;font-size:.8rem;color:#dc2626;">
                                    <span id="sw-courier-error-text"></span>
                                </div>
                                <div id="sw-courier-list" class="ps-courier-list"></div>
                                <input type="hidden" name="courier_name"    id="sw-input-courier-name">
                                <input type="hidden" name="courier_service" id="sw-input-courier-service">
                                <input type="hidden" name="courier_etd"     id="sw-input-courier-etd">
                            </div>

                            <div class="ps-form-group">
                                <label class="ps-form-label">
                                    Ongkos Kirim (Rp) <span class="req">*</span>
                                    <span id="sw-cost-hint" style="font-weight:400;color:#94a3b8;margin-left:.25rem;">— tarif pengelola</span>
                                </label>
                                <input type="number" name="shipping_cost" id="sw-shipping-cost"
                                       class="ps-form-input"
                                       value="{{ $zonaSummary ? ($zonaSummary['is_free'] ? 0 : $zonaSummary['default_rate']) : 0 }}"
                                       min="0" step="1000"
                                       {{ ($zonaSummary && $zonaSummary['is_free']) ? 'readonly' : '' }}>
                                @if($zonaSummary && $zonaSummary['is_free'])
                                    <p style="font-size:.73rem;color:#059669;margin-top:.3rem;">Otomatis 0 karena wilayah Purwakarta.</p>
                                @endif
                            </div>

                            {{-- Ringkasan biaya live --}}
                            <div class="ps-cost-wrap" style="margin-bottom:1rem;">
                                <div class="ps-cost-row">
                                    <span class="lbl">Subtotal Sewa ({{ $durasi }} hari)</span>
                                    <span class="val">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="ps-cost-row">
                                    <span class="lbl">Deposit (50% subtotal)</span>
                                    <span class="val">Rp {{ number_format($deposit, 0, ',', '.') }}</span>
                                </div>
                                <div class="ps-cost-row">
                                    <span class="lbl">Ongkos Kirim</span>
                                    <span class="val" id="sw-cost-display">
                                        {{ ($zonaSummary && $zonaSummary['is_free']) ? 'Gratis' : 'Rp ' . number_format($zonaSummary ? $zonaSummary['default_rate'] : 0, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="ps-cost-total">
                                    <span class="lbl">Estimasi Total</span>
                                    <span class="val" id="sw-total-display">
                                        Rp {{ number_format($subtotal + $deposit + ($zonaSummary ? ($zonaSummary['is_free'] ? 0 : $zonaSummary['default_rate']) : 0), 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="ps-info-box">
                                    <p><strong>ℹ️</strong> Total ini yang akan tertera di invoice penyewa.</p>
                                </div>
                            </div>

                            <div class="ps-form-group">
                                <label class="ps-form-label">Catatan untuk Penyewa (opsional)</label>
                                <textarea name="catatan_pengelola" rows="2" class="ps-form-textarea"
                                          placeholder="Misal: koleksi akan dikirim H+2 setelah konfirmasi."></textarea>
                            </div>

                            <button type="submit" class="ps-btn ps-btn-emerald" style="width:100%;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Setujui &amp; Generate Dokumen
                            </button>
                        </form>
                    </div>

                    {{-- PANEL TOLAK --}}
                    <div class="ps-approve-panel reject-panel">
                        <div class="ps-panel-tag reject">✗ TOLAK</div>
                        <p>Pengajuan akan ditolak dan penyewa akan diberitahu beserta alasan penolakan.</p>
                        <form action="{{ route('pengelola.penyewaan.reject', $penyewaan) }}" method="POST" style="display:flex;flex-direction:column;gap:.875rem;flex:1;">
                            @csrf
                            <div>
                                <label class="ps-form-label">Alasan Penolakan</label>
                                <textarea name="rejection_reason" rows="4" class="ps-form-textarea"
                                          placeholder="Alasan penolakan (opsional, akan dikirim ke penyewa)"></textarea>
                            </div>
                            <button type="submit" class="ps-btn ps-btn-danger" style="justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak Pengajuan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <script>
            {{-- PERBAIKAN: Gunakan destination_city_id (dari lokasi penempatan) --}}
            {{-- dan endpoint /api/rajaongkir/cost, konsisten dengan fitur pembelian --}}
            const SW_IS_FREE           = {{ ($zonaSummary && $zonaSummary['is_free']) ? 'true' : 'false' }};
            const SW_DEFAULT_RATE      = {{ $zonaSummary ? (int)$zonaSummary['default_rate'] : 0 }};
            const SW_DESTINATION_ID    = {{ (int) ($penyewaan->destination_city_id ?? 0) }};
            const SW_WEIGHT_GRAM       = {{ (int) ($penyewaan->painting->weight_gram ?? 5000) }};
            const SW_SUBTOTAL          = {{ (int) $subtotal }};
            const SW_DEPOSIT           = {{ (int) $deposit }};

            function sw_formatRupiah(n) { return parseInt(n).toLocaleString('id-ID'); }

            function sw_updateTotal() {
                const ongkir = parseInt(document.getElementById('sw-shipping-cost').value) || 0;
                const total  = SW_SUBTOTAL + SW_DEPOSIT + ongkir;
                document.getElementById('sw-cost-display').textContent =
                    ongkir === 0 ? 'Gratis' : 'Rp ' + sw_formatRupiah(ongkir);
                document.getElementById('sw-total-display').textContent =
                    'Rp ' + sw_formatRupiah(total);
            }

            function sw_handleMethodChange(method) {
                const courierSection = document.getElementById('sw-courier-section');
                const costInput      = document.getElementById('sw-shipping-cost');
                const hint           = document.getElementById('sw-cost-hint');
                if (SW_IS_FREE) {
                    costInput.value = 0;
                    courierSection.style.display = 'none';
                    hint.textContent = '— gratis (wilayah Purwakarta)';
                    sw_updateTotal();
                    return;
                }
                if (method === 'manager') {
                    courierSection.style.display = 'none';
                    costInput.value = SW_DEFAULT_RATE;
                    costInput.removeAttribute('readonly');
                    hint.textContent = '— tarif pengelola';
                    ['sw-input-courier-name','sw-input-courier-service','sw-input-courier-etd'].forEach(id => document.getElementById(id).value = '');
                    sw_updateTotal();
                } else {
                    courierSection.style.display = '';
                    hint.textContent = '— otomatis dari kurir';
                    costInput.value  = '';
                    costInput.setAttribute('readonly', 'readonly');
                    sw_updateTotal();
                    sw_fetchCourierRates();
                }
            }

            async function sw_fetchCourierRates() {
                {{-- PERBAIKAN: Cek destination_city_id, bukan city_name --}}
                if (!SW_DESTINATION_ID) {
                    sw_showCourierError('Data kota tujuan tidak tersedia. Isi ongkir manual atau pilih Pengelola.');
                    document.getElementById('sw-shipping-cost').removeAttribute('readonly');
                    return;
                }

                const loadingEl = document.getElementById('sw-courier-loading');
                const listEl    = document.getElementById('sw-courier-list');
                loadingEl.style.display = 'flex';
                sw_hideCourierError();
                listEl.innerHTML = '';

                try {
                    {{-- PERBAIKAN: Pakai endpoint rajaongkir dengan destination_city_id --}}
                    const res = await fetch('/api/rajaongkir/cost', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            destination_city_id: SW_DESTINATION_ID,
                            weight_gram: SW_WEIGHT_GRAM
                        }),
                    });
                    const data = await res.json();
                    loadingEl.style.display = 'none';

                    if (data.is_free) {
                        document.getElementById('sw-shipping-cost').value = 0;
                        sw_showCourierError('Wilayah ini gratis ongkir.');
                        sw_updateTotal();
                        return;
                    }
                    if (!data.services || data.services.length === 0) {
                        sw_showCourierError('Tidak ada layanan kurir tersedia. Isi ongkir manual atau pilih Pengelola.');
                        document.getElementById('sw-shipping-cost').removeAttribute('readonly');
                        return;
                    }
                    sw_renderCourierList(data.services);
                } catch(e) {
                    loadingEl.style.display = 'none';
                    sw_showCourierError('Gagal mengambil tarif. Isi ongkir manual.');
                    document.getElementById('sw-shipping-cost').removeAttribute('readonly');
                }
            }

            function sw_renderCourierList(services) {
                const listEl = document.getElementById('sw-courier-list');
                listEl.innerHTML = '';
                services.forEach((s,i) => {
                    const el = document.createElement('label');
                    el.className = 'ps-courier-item';
                    el.innerHTML = `<div class="ps-courier-item-left"><input type="radio" name="_sw_courier_pick" value="${i}" onchange="sw_selectCourier(this, ${JSON.stringify(s).replace(/"/g,'&quot;')})"><div><div class="ps-courier-name">${sw_esc(s.courier_name)} <span style="font-weight:400;color:var(--slate);">${sw_esc(s.service)}</span></div><div class="ps-courier-etd">ETD: ${sw_esc(String(s.etd))} hari kerja</div></div></div><div class="ps-courier-cost">Rp ${sw_formatRupiah(s.cost)}</div>`;
                    listEl.appendChild(el);
                });
            }

            function sw_selectCourier(radio, s) {
                document.getElementById('sw-shipping-cost').value         = s.cost;
                document.getElementById('sw-input-courier-name').value    = s.courier_name;
                document.getElementById('sw-input-courier-service').value = s.service;
                document.getElementById('sw-input-courier-etd').value     = s.etd;
                sw_updateTotal();
            }

            function sw_showCourierError(msg) { document.getElementById('sw-courier-error-text').textContent = msg; document.getElementById('sw-courier-error').style.display = ''; }
            function sw_hideCourierError()    { document.getElementById('sw-courier-error').style.display = 'none'; }
            function sw_esc(str) { const d = document.createElement('div'); d.textContent = str; return d.innerHTML; }

            document.getElementById('sw-shipping-cost').addEventListener('input', sw_updateTotal);
            document.getElementById('form-approve-sw').addEventListener('submit', function(e) {
                const method = document.querySelector('input[name="shipping_method_type"]:checked')?.value;
                if (!SW_IS_FREE) {
                    if (method === 'courier' && !document.getElementById('sw-input-courier-name').value) { e.preventDefault(); alert('Pilih layanan kurir terlebih dahulu.'); return; }
                    if (document.getElementById('sw-shipping-cost').value === '') { e.preventDefault(); alert('Ongkos kirim belum diisi.'); }
                }
            });
            document.addEventListener('DOMContentLoaded', () => sw_handleMethodChange('manager'));
            </script>
            @endif

            {{-- ══ STATUS: menunggu_dokumen_perjanjian ══ --}}
            @if($status === 'menunggu_dokumen_perjanjian')
            @php
                $docDitolak = $penyewaan->signed_agreement_status === 'rejected';
            @endphp
            <div class="ps-status-section {{ $docDitolak ? 'ps-section-ditolak' : 'ps-section-perjanjian' }}">
                @if($docDitolak)
                    <div class="ps-eyebrow">🔄 Menunggu Penyewa</div>
                    <h2>Menunggu Upload Ulang Dokumen Perjanjian</h2>
                    <p>Dokumen perjanjian yang diunggah penyewa telah ditolak. Penyewa sedang diminta memperbaiki dan mengunggah kembali.</p>

                    {{-- Catatan penolakan yang diberikan pengelola --}}
                    @if($penyewaan->signed_agreement_review_notes)
                    <div class="ps-catatan" style="margin-top:1rem;background:#fff;border-color:#fecaca;">
                        <div class="lbl" style="color:#dc2626;">Catatan Penolakan yang Dikirim ke Penyewa</div>
                        <div class="val">{{ $penyewaan->signed_agreement_review_notes }}</div>
                    </div>
                    @endif

                    {{-- Info dokumen yang tersedia --}}
                    <div class="ps-doc-info-grid">
                        @if($penyewaan->agreement_document_path)
                        <div class="ps-doc-info-card">
                            <div class="title">Surat Perjanjian (Original)</div>
                            <div class="sub">Dokumen yang dikirim ke penyewa.</div>
                            <a href="{{ asset('storage/' . $penyewaan->agreement_document_path) }}" target="_blank" class="ps-btn ps-btn-navy" style="font-size:.78rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                Preview Dokumen
                            </a>
                        </div>
                        @endif
                        @if($penyewaan->signed_agreement_path)
                        <div class="ps-doc-info-card" style="border-color:#fecaca;background:#fef2f2;">
                            <div class="title">Dokumen yang Ditolak</div>
                            <div class="sub">Dokumen terakhir yang diunggah penyewa.</div>
                            <a href="{{ asset('storage/' . $penyewaan->signed_agreement_path) }}" target="_blank" class="ps-btn ps-btn-sky" style="font-size:.78rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                Preview (Ditolak)
                            </a>
                        </div>
                        @endif
                    </div>

                @else
                    <div class="ps-eyebrow">⏳ Menunggu Penyewa</div>
                    <h2>Dokumen Perjanjian Dikirim ke Penyewa</h2>
                    <p>Pengajuan disetujui. Penyewa sedang diminta mengunduh, menandatangani, dan mengunggah kembali dokumen perjanjian.</p>

                    @if($penyewaan->shipping_method_type)
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell">
                            <div class="lbl">Metode Pengiriman</div>
                            <div class="val">{{ $penyewaan->shipping_method_label }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Ongkos Kirim</div>
                            <div class="val">{{ (int)$penyewaan->shipping_cost === 0 ? 'Gratis' : 'Rp ' . number_format($penyewaan->shipping_cost, 0, ',', '.') }}</div>
                        </div>
                        @if($penyewaan->courier_etd && $penyewaan->shipping_method_type === 'courier')
                        <div class="ps-meta-cell">
                            <div class="lbl">Estimasi Tiba</div>
                            <div class="val">{{ $penyewaan->courier_etd }} hari kerja</div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="ps-doc-info-grid">
                        @if($penyewaan->agreement_document_path)
                        <div class="ps-doc-info-card">
                            <div class="title">Surat Perjanjian</div>
                            <div class="sub">Dibuat otomatis saat persetujuan.</div>
                            <a href="{{ asset('storage/' . $penyewaan->agreement_document_path) }}" target="_blank" class="ps-btn ps-btn-navy">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                Preview Dokumen
                            </a>
                        </div>
                        @endif
                        @if($penyewaan->invoice_document_path)
                        <div class="ps-doc-info-card">
                            <div class="title">Invoice Pembayaran</div>
                            <div class="sub">Sudah termasuk ongkir &amp; deposit.</div>
                            <a href="{{ asset('storage/' . $penyewaan->invoice_document_path) }}" target="_blank" class="ps-btn ps-btn-blue">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                Preview Invoice
                            </a>
                        </div>
                        @endif
                    </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: verifikasi_dokumen_perjanjian ══ --}}
            @if($status === 'verifikasi_dokumen_perjanjian')
            <div class="ps-status-section ps-section-validasi-doc">
                <div class="ps-eyebrow">⚡ Aksi Diperlukan</div>
                <h2>Validasi Dokumen Perjanjian</h2>
                <p>Penyewa telah mengunggah dokumen perjanjian. Periksa dokumen lalu pilih tindakan.</p>

                @if($penyewaan->signed_agreement_path)
                <div class="ps-validasi-grid">
                    <div class="ps-doc-preview-box">
                        <div class="preview-head">
                            <div>
                                <div class="preview-title">Dokumen Perjanjian Ditandatangani</div>
                                <div class="preview-sub">Diunggah oleh penyewa</div>
                            </div>
                            <div class="ps-doc-preview-actions">
                                <a href="{{ asset('storage/' . $penyewaan->signed_agreement_path) }}" target="_blank" class="ps-doc-btn ps-doc-btn-primary">🔍 Buka Tab Baru</a>
                                <a href="{{ asset('storage/' . $penyewaan->signed_agreement_path) }}" download class="ps-doc-btn ps-doc-btn-ghost">↓ Unduh</a>
                            </div>
                        </div>
                        <iframe src="{{ asset('storage/' . $penyewaan->signed_agreement_path) }}" style="width:100%;height:320px;border:0;border-radius:.5rem;" title="Preview Dokumen Perjanjian"></iframe>
                    </div>

                    <div>
                        <form action="{{ route('pengelola.penyewaan.reviewSignedAgreement', $penyewaan) }}" method="POST" style="display:flex;flex-direction:column;gap:.875rem;">
                            @csrf
                            <div class="ps-radio-grid">
                                <label class="ps-radio-label">
                                    <input type="radio" name="action" value="accepted" checked>
                                    <span>✅ Setujui</span>
                                </label>
                                <label class="ps-radio-label">
                                    <input type="radio" name="action" value="rejected">
                                    <span>❌ Tolak</span>
                                </label>
                            </div>
                            <div>
                                <label class="ps-form-label">Catatan Validasi</label>
                                <textarea name="review_notes" rows="4" class="ps-form-textarea" placeholder="Catatan: Isikan alasan jika menolak."></textarea>
                            </div>
                            <button type="submit" class="ps-btn ps-btn-navy" style="justify-content:center;">Simpan Keputusan</button>
                        </form>
                    </div>
                </div>
                @else
                    <p style="margin-top:1rem;font-style:italic;color:var(--slate);">Dokumen belum tersedia.</p>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: menunggu_pembayaran ══ --}}
            @if($status === 'menunggu_pembayaran')
            <div class="ps-status-section ps-section-pembayaran">
                <div class="ps-eyebrow">⏳ Menunggu Penyewa</div>
                <h2>Menunggu Pembayaran</h2>
                <p>Dokumen perjanjian telah divalidasi. Penyewa sedang melakukan pembayaran. Status akan otomatis berubah ke <strong>Pengiriman</strong> setelah pembayaran diterima.</p>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell">
                        <div class="lbl">Status Pembayaran</div>
                        <div class="val">{{ ucfirst($penyewaan->payment_status ?? 'unpaid') }}</div>
                    </div>
                    <div class="ps-meta-cell highlight">
                        <div class="lbl">Total Tagihan</div>
                        <div class="val">{{ $penyewaan->total_bayar > 0 ? 'Rp ' . number_format($penyewaan->total_bayar, 0, ',', '.') : '-' }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Metode Pengiriman</div>
                        <div class="val">{{ $penyewaan->shipping_method_label }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Ongkos Kirim</div>
                        <div class="val">{{ (int)$penyewaan->shipping_cost === 0 ? 'Gratis' : 'Rp ' . number_format($penyewaan->shipping_cost, 0, ',', '.') }}</div>
                    </div>
                </div>
                @if($penyewaan->invoice_document_path)
                <div class="ps-action-row">
                    <a href="{{ asset('storage/' . $penyewaan->invoice_document_path) }}" target="_blank" class="ps-btn ps-btn-blue">
                        Preview Invoice
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: pengiriman — belum isi info pengiriman ══ --}}
            @if($status === 'pengiriman' && in_array($serahTerima?->handover_status, ['waiting_handover', null]))
            <div class="ps-status-section ps-section-pengiriman">
                <div class="ps-eyebrow">⚡ Aksi Diperlukan</div>
                <h2>Pembayaran Diterima — Siapkan Pengiriman</h2>
                <p>Pembayaran telah diterima. Lanjutkan ke halaman serah terima untuk mengisi informasi pengiriman koleksi ke penyewa.</p>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Pembayaran</div>
                        <div class="val">LUNAS ✓</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Metode Pengiriman</div>
                        <div class="val">{{ $penyewaan->shipping_method_label }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Ongkos Kirim</div>
                        <div class="val">{{ (int)$penyewaan->shipping_cost === 0 ? 'Gratis' : 'Rp ' . number_format($penyewaan->shipping_cost, 0, ',', '.') }}</div>
                    </div>
                </div>
                @if(Route::has('pengelola.penyewaan.handover.show'))
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-emerald">
                        Siapkan Pengiriman →
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: siap_diserahkan — info pengiriman sudah diisi (pengelola) ══ --}}
            @if(in_array($status, ['siap_diserahkan']) || ($status === 'pengiriman' && $serahTerima?->handover_status === 'preparing_delivery'))
            <div class="ps-status-section ps-section-pengiriman">
                <div class="ps-eyebrow">📦 Menunggu Pengiriman</div>
                <h2>Koleksi Siap Dikirim</h2>
                <p>
                    Informasi pengiriman sudah tersimpan.
                    @if($penyewaan->shipping_method_type === 'manager')
                        Tandai koleksi sudah berangkat saat petugas mulai mengirim ke penyewa.
                    @else
                        Menunggu proses pengiriman ke penyewa.
                    @endif
                </p>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell">
                        <div class="lbl">Petugas</div>
                        <div class="val">{{ $serahTerima?->delivery_officer ?? '-' }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Metode</div>
                        <div class="val">{{ $serahTerima?->delivery_method ?? '-' }}</div>
                    </div>
                    @if($serahTerima?->delivery_scheduled_at)
                    <div class="ps-meta-cell">
                        <div class="lbl">Rencana Kirim</div>
                        <div class="val">{{ \Carbon\Carbon::parse($serahTerima->delivery_scheduled_at)->format('d M Y H:i') }}</div>
                    </div>
                    @endif
                </div>
                @if(Route::has('pengelola.penyewaan.handover.show'))
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-blue">
                        Kelola Pengiriman & Serah Terima →
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: dalam_pengiriman ══ --}}
            @if($status === 'dalam_pengiriman' && !in_array($serahTerima?->handover_status, [
                'condition_checking', 'damage_reported', 'damage_reviewed',
                'cancelled_due_to_damage', 'handover_completed',
            ]))
            <div class="ps-status-section ps-section-pengiriman">
                <div class="ps-eyebrow">🚚 Dalam Pengiriman</div>
                <h2>Pantau Pengiriman Koleksi</h2>
                <p>
                    Koleksi sudah dikirim ke penyewa
                    @if($penyewaan->shipping_method_type === 'courier')
                        via kurir <strong>{{ $serahTerima?->delivery_method ?? $penyewaan->courier_name ?? '-' }}</strong>.
                        Pantau tracking resi di halaman serah terima.
                    @else
                        via petugas pengelola. Update sub-status pengiriman secara manual di halaman serah terima.
                    @endif
                    Menunggu penyewa mengkonfirmasi penerimaan.
                </p>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Pembayaran</div>
                        <div class="val">LUNAS ✓</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Metode Pengiriman</div>
                        <div class="val">{{ $penyewaan->shipping_method_label }}</div>
                    </div>
                    @if($penyewaan->shipping_method_type === 'courier' && $serahTerima?->delivery_tracking_number)
                    <div class="ps-meta-cell">
                        <div class="lbl">No. Resi</div>
                        <div class="val" style="font-family:monospace;font-size:.83rem;">{{ $serahTerima->delivery_tracking_number }}</div>
                    </div>
                    @endif
                    @if($serahTerima?->shipped_at)
                    <div class="ps-meta-cell">
                        <div class="lbl">Dikirim Pada</div>
                        <div class="val">{{ $serahTerima->shipped_at->format('d M Y H:i') }}</div>
                    </div>
                    @endif
                </div>
                @if(Route::has('pengelola.penyewaan.handover.show'))
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-sky">
                        {{ $penyewaan->shipping_method_type === 'courier' ? 'Lacak Pengiriman →' : 'Update Status Pengiriman →' }}
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: pengiriman + damage_reported ══ --}}
            @if(in_array($status, ['pengiriman','menunggu_review_kerusakan']) && $serahTerima?->handover_status === 'damage_reported')
            <div class="ps-status-section" style="background:#fef2f2;border:1.5px solid #fecaca;">
                <div class="ps-eyebrow" style="color:#dc2626;">⚡ Perlu Keputusan Pengelola</div>
                <h2>Laporan Kerusakan Masuk dari Penyewa</h2>
                <p>
                    Penyewa melaporkan kerusakan pada koleksi saat diterima pada
                    <strong>{{ $serahTerima->arrival_damage_reported_at?->format('d M Y, H:i') ?? '-' }}</strong>.
                    Tinjau laporan dan tentukan keputusan di halaman serah terima.
                </p>
                @php
                    $checkedItems = $serahTerima->getCheckedDamageItems();
                    $sevLabel = match($serahTerima->arrival_damage_severity) {
                        'ringan' => '🟡 Ringan', 'parah' => '🔴 Parah', default => '-'
                    };
                    $decLabel = match($serahTerima->arrival_damage_tenant_decision) {
                        'lanjutkan' => '✅ Ingin melanjutkan sewa',
                        'batalkan'  => '❌ Ingin membatalkan sewa',
                        default     => '-'
                    };
                @endphp
                @if(!empty($checkedItems))
                <div style="margin-top:1rem;">
                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#dc2626;margin-bottom:.5rem;">
                        Jenis Kerusakan Dilaporkan
                    </div>
                    <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                        @foreach($checkedItems as $item)
                            <span style="background:#fff;border:1.5px solid #fecaca;border-radius:.6rem;
                                        padding:.3rem .75rem;font-size:.76rem;font-weight:600;color:#991b1b;">
                                ⚠ {{ $item }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-danger" style="border-color:#dc2626;background:#dc2626;color:#fff;">
                        Tinjau & Putuskan Laporan Kerusakan →
                    </a>
                </div>
            </div>
            @endif

            {{-- ══ STATUS: damage_reviewed — keputusan sudah dibuat ══ --}}
            @if(in_array($status, ['pengiriman','menunggu_review_kerusakan','dibatalkan'])
                && $serahTerima?->handover_status === 'damage_reviewed'
                && $serahTerima?->arrival_damage_manager_decision)
            @php $dec = $serahTerima->arrival_damage_manager_decision; @endphp

                @if($dec === 'setuju_batal')
                {{-- Disetujui batal --}}
                <div class="ps-status-section" style="background:#fef2f2;border:1.5px solid #fecaca;">
                    <div class="ps-eyebrow" style="color:#dc2626;">❌ Pembatalan Disetujui</div>
                    <h2>Pengajuan Pembatalan Penyewa Disetujui</h2>
                    <p>
                        Pengelola telah menyetujui pembatalan sewa karena kerusakan saat pengecekan kondisi.
                        Deposit akan dikembalikan penuh ke penyewa. Pantau status di halaman serah terima.
                    </p>
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell danger">
                            <div class="lbl">Status Sewa</div>
                            <div class="val">Dibatalkan</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Deposit</div>
                            <div class="val">Dikembalikan Penuh</div>
                        </div>
                        @if($serahTerima->arrival_damage_decided_at)
                        <div class="ps-meta-cell">
                            <div class="lbl">Diputuskan Pada</div>
                            <div class="val">{{ $serahTerima->arrival_damage_decided_at->format('d M Y H:i') }}</div>
                        </div>
                        @endif
                        @if($serahTerima->arrival_damage_decided_by)
                        <div class="ps-meta-cell">
                            <div class="lbl">Diputuskan Oleh</div>
                            <div class="val">{{ $serahTerima->arrival_damage_decided_by }}</div>
                        </div>
                        @endif
                    </div>
                    @if($serahTerima->arrival_damage_manager_notes)
                    <div class="ps-catatan" style="margin-top:1rem;background:#fff;border-color:#fecaca;">
                        <div class="lbl">Catatan yang Dikirim ke Penyewa</div>
                        <div class="val">{{ $serahTerima->arrival_damage_manager_notes }}</div>
                    </div>
                    @endif
                    <div class="ps-action-row">
                        <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-red">
                            Kelola Pengembalian & Refund di Serah Terima →
                        </a>
                    </div>
                </div>

                @elseif($dec === 'tolak_lanjut')
                {{-- Ditolak, sewa lanjut --}}
                <div class="ps-status-section" style="background:#fffbeb;border:1.5px solid #fde68a;">
                    <div class="ps-eyebrow" style="color:#d97706;">⚠️ Pembatalan Ditolak — Sewa Dilanjutkan</div>
                    <h2>Pengajuan Pembatalan Ditolak</h2>
                    <p>
                        Pengelola telah menolak pengajuan pembatalan penyewa. Proses sewa dilanjutkan.
                        Penyewa akan diarahkan ke tahap unduh dan upload dokumen serah terima.
                    </p>
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell warning">
                            <div class="lbl">Keputusan Pembatalan</div>
                            <div class="val">Ditolak</div>
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
                        <div class="lbl">Catatan yang Dikirim ke Penyewa</div>
                        <div class="val">{{ $serahTerima->arrival_damage_manager_notes }}</div>
                    </div>
                    @endif
                    <div class="ps-action-row">
                        <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-sky">
                            Pantau Proses Serah Terima →
                        </a>
                    </div>
                </div>
                @endif
            @endif

            {{-- ══ STATUS: pengiriman + condition_checking ══ --}}
            @if($serahTerima?->handover_status === 'condition_checking' || $status === 'pengecekan_kondisi')
            <div class="ps-status-section" style="background:#fffbeb;border:1.5px solid #fde68a;">
                <div class="ps-eyebrow" style="color:#d97706;">⏳ Menunggu Penyewa</div>
                <h2>Penyewa Sedang Memeriksa Kondisi Koleksi</h2>
                <p>
                    Penyewa telah mengkonfirmasi penerimaan koleksi pada
                    <strong>{{ $serahTerima->confirmed_received_at?->format('d M Y, H:i') ?? '-' }}</strong>.
                    Menunggu hasil pengecekan kondisi koleksi dari penyewa.
                </p>
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-sky">
                        Lihat Detail Serah Terima →
                    </a>
                </div>
            </div>
            @endif

            {{-- ══ STATUS: menunggu_data_rekening ══ --}}
            @if($status === 'menunggu_data_rekening')
            <div class="ps-status-section ps-section-pengembalian">
                <div class="ps-eyebrow">⏳ Menunggu Penyewa</div>
                <h2>{{ $isDamageCancellation ? 'Menunggu Pengembalian Koleksi & Data Refund' : ($isDamageCompensation ? 'Menunggu Data Rekening Kompensasi' : 'Menunggu Data Rekening Penyewa') }}</h2>
                <p>
                    @if($isDamageCancellation)
                        Pembatalan disetujui. Penyewa diminta mengembalikan koleksi ke museum sekaligus mengisi data rekening dan ongkir pengembalian.
                    @elseif($isDamageCompensation)
                        Kompensasi disetujui. Penyewa diminta mengisi data rekening untuk proses transfer kompensasi.
                        Setelah kompensasi diterima, proses dilanjutkan ke dokumen serah terima tanpa pengembalian koleksi.
                    @else
                        Penyewa sedang menyiapkan rekening untuk menerima kompensasi/refund setelah keputusan kerusakan.
                    @endif
                </p>
                @if($isDamageCompensation && $serahTerima?->arrival_damage_compensation_amount)
                <div class="ps-meta-grid" style="margin-top:1rem;">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Kompensasi Disetujui</div>
                        <div class="val">Rp {{ number_format($serahTerima->arrival_damage_compensation_amount, 0, ',', '.') }}</div>
                    </div>
                </div>
                @if($serahTerima->arrival_damage_manager_notes)
                <div class="ps-catatan" style="margin-top:.875rem;">
                    <div class="lbl">Catatan Pengelola</div>
                    <div class="val">{{ $serahTerima->arrival_damage_manager_notes }}</div>
                </div>
                @endif
                @endif
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell warning">
                        <div class="lbl">Tahap Saat Ini</div>
                        <div class="val">Data Rekening Belum Masuk</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Langkah Selanjutnya</div>
                        <div class="val">Pantau halaman serah terima</div>
                    </div>
                </div>
                @if(Route::has('pengelola.penyewaan.handover.show'))
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-sky">
                        Buka Halaman Serah Terima →
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: menunggu_refund_kerusakan (setelah rekening masuk) ══ --}}
            @if($status === 'menunggu_refund_kerusakan')
            <div class="ps-status-section ps-section-pengembalian">
                <div class="ps-eyebrow">⚡ Aksi Diperlukan</div>
                <h2>{{ $serahTerima?->isDamageCompensation() ? 'Data Rekening Kompensasi Sudah Masuk' : 'Data Rekening Refund Sudah Masuk' }}</h2>
                <p>
                    @if($serahTerima?->isDamageCompensation())
                        Penyewa sudah mengirim rekening untuk menerima kompensasi. Setelah transfer selesai,
                        sistem akan mengarahkan kembali ke tahap unduh dan upload dokumen serah terima.
                    @else
                        Penyewa sudah mengirim rekening untuk proses refund. Pengelola dapat meninjau data ini
                        dan memproses transfer sesuai keputusan kerusakan.
                    @endif
                </p>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Status</div>
                        <div class="val">Rekening Sudah Masuk ✓</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Rekening Penyewa</div>
                        <div class="val">{{ $serahTerima?->refund_bank_name ?? $penyewaan->bank_name ?? '-' }} — {{ $serahTerima?->refund_account_number ?? $penyewaan->account_number ?? '-' }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Pemilik Rekening</div>
                        <div class="val">{{ $serahTerima?->refund_account_holder ?? $penyewaan->account_holder ?? '-' }}</div>
                    </div>
                </div>
                @if(Route::has('pengelola.penyewaan.handover.show'))
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-teal">
                        {{ $serahTerima?->isDamageCompensation() ? 'Lanjutkan Proses Kompensasi →' : 'Lanjutkan Proses Transfer Refund →' }}
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: menunggu_dokumen_serah_terima ══ --}}
            @if($status === 'menunggu_dokumen_serah_terima')
            @php $stDitolak = $serahTerima && $serahTerima->serah_terima_status === 'rejected'; @endphp
            <div class="ps-status-section {{ $stDitolak ? 'ps-section-ditolak' : 'ps-section-serah-terima' }}">
                @if($stDitolak)
                    <div class="ps-eyebrow">🔄 Menunggu Upload Ulang</div>
                    <h2>Dokumen Serah Terima Ditolak — Menunggu Penyewa Upload Ulang</h2>
                    <p>Dokumen serah terima yang diunggah penyewa telah ditolak. Penyewa sedang diminta memperbaiki dan mengunggah kembali.</p>
                    @if($serahTerima->validation_notes)
                    <div class="ps-catatan" style="background:#fff;border-color:#fecaca;margin-top:1rem;">
                        <div class="lbl" style="color:#dc2626;">Catatan Penolakan yang Dikirim ke Penyewa</div>
                        <div class="val">{{ $serahTerima->validation_notes }}</div>
                    </div>
                    @endif
                @elseif($isDamageCompensation && $serahTerima?->refund_confirmed_at)
                    <div class="ps-eyebrow">✅ Kompensasi Selesai</div>
                    <h2>Menunggu Dokumen Serah Terima</h2>
                    <p>Penyewa telah mengkonfirmasi penerimaan kompensasi pada <strong>{{ $serahTerima->refund_confirmed_at->format('d M Y H:i') }}</strong>. Menunggu penyewa mengunduh, menandatangani, dan mengunggah dokumen serah terima.</p>
                    @if($serahTerima->refund_amount)
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell success">
                            <div class="lbl">Kompensasi Ditransfer</div>
                            <div class="val">Rp {{ number_format($serahTerima->refund_amount, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endif
                @else
                    <div class="ps-eyebrow">⏳ Menunggu Penyewa</div>
                    <h2>Menunggu Dokumen Serah Terima</h2>
                    <p>Penyewa telah mengkonfirmasi penerimaan koleksi. Menunggu penyewa mengunduh, mengisi checklist, menandatangani, dan mengunggah kembali dokumen serah terima.</p>
                    @if($serahTerima?->arrival_damage_manager_decision === 'tolak_kompensasi' && $serahTerima->arrival_damage_manager_notes)
                    <div class="ps-catatan" style="margin-top:1rem;background:#fff;border-color:#fecaca;">
                        <div class="lbl" style="color:#dc2626;">❌ Klaim Kompensasi Ditolak</div>
                        <div class="val">{{ $serahTerima->arrival_damage_manager_notes }}</div>
                    </div>
                    @endif
                @endif
                @if(Route::has('pengelola.penyewaan.handover.show'))
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-indigo">
                        Lihat Detail Serah Terima →
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: verifikasi_serah_terima ══ --}}
            @if($status === 'verifikasi_serah_terima')
            <div class="ps-status-section ps-section-validasi-st">
                <div class="ps-eyebrow">⚡ Aksi Diperlukan</div>
                <h2>Validasi Dokumen Serah Terima</h2>
                <p>Periksa dan validasi dokumen untuk mengaktifkan masa penyewaan.</p>
                @if(Route::has('pengelola.penyewaan.handover.show'))
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-violet">
                        Validasi Dokumen Serah Terima
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: aktif ══ --}}
            @if($status === 'aktif')
            <div class="ps-status-section ps-section-aktif">
                <div class="ps-eyebrow">🎨 Masa Sewa Berjalan</div>
                <h2>Penyewaan Aktif</h2>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell">
                        <div class="lbl">Berakhir</div>
                        <div class="val">{{ $penyewaan->end_date?->format('d M Y') ?? '-' }}</div>
                    </div>
                    <div class="ps-meta-cell {{ $sisaHari !== null && $sisaHari <= 3 ? 'warning' : 'success' }}">
                        <div class="lbl">Sisa Hari</div>
                        <div class="val">
                            @if($sisaHari === null) -
                            @elseif($sisaHari > 0) {{ $sisaHari }} hari lagi
                            @else Sudah berakhir
                            @endif
                        </div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Metode Pengiriman</div>
                        <div class="val">{{ $penyewaan->shipping_method_label }}</div>
                    </div>
                </div>
                @if($sisaHari !== null && $sisaHari <= 0)
                <div class="ps-catatan" style="background:#fffbeb;border-color:#fde68a;margin-top:1rem;">
                    <div class="lbl" style="color:#d97706;">⚠ Masa Sewa Berakhir</div>
                    <div class="val" style="margin-bottom:.75rem;">Masa sewa telah berakhir — tandai sebagai pengembalian.</div>
                    <form method="POST" action="{{ route('pengelola.penyewaan.handover.mark-returning', $penyewaan) }}">
                        @csrf
                        <button type="submit" class="ps-btn ps-btn-amber">Tandai Pengembalian</button>
                    </form>
                </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: pengembalian ══ --}}
            @if($status === 'pengembalian')
            @php
                $stPengembalian  = $penyewaan->serahTerima;
                $shipmentDikirim = $stPengembalian?->return_shipment_submitted_at;
                $koleksiTiba     = $stPengembalian?->collection_arrived_at;
                $dokGenerated    = $stPengembalian?->return_document_path;
                $tahapPeriksa    = $koleksiTiba && !$dokGenerated;
            @endphp
            <div class="ps-status-section ps-section-pengembalian">
                @if($tahapPeriksa)
                    <div class="ps-eyebrow">⚡ Aksi Diperlukan</div>
                    <h2>Periksa Kondisi Koleksi</h2>
                    <p>Koleksi sudah tiba di museum. Lakukan pemeriksaan fisik dan generate dokumen pengembalian.</p>

                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell">
                            <div class="lbl">Koleksi Tiba di Museum</div>
                            <div class="val">{{ $koleksiTiba->format('d M Y H:i') }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Dokumen Pengembalian</div>
                            <div class="val" style="color:#d97706;">Belum Digenerate</div>
                        </div>
                    </div>

                @elseif(!$shipmentDikirim)
                    <div class="ps-eyebrow">⏳ Menunggu Penyewa</div>
                    <h2>Menunggu Info Pengiriman Balik</h2>
                    <p>Masa sewa berakhir. Menunggu penyewa mengirimkan informasi pengiriman balik koleksi.</p>

                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell">
                            <div class="lbl">Masa Sewa Berakhir</div>
                            <div class="val">{{ $penyewaan->end_date?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Penyewa</div>
                            <div class="val">{{ $penyewaan->rental_type === 'instansi' ? ($penyewaan->nama_instansi ?? '-') : ($penyewaan->contact_name ?? '-') }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Info Pengiriman Balik</div>
                            <div class="val" style="color:#d97706;">Belum Dikirim</div>
                        </div>
                    </div>

                @elseif(!$koleksiTiba)
                    <div class="ps-eyebrow">⚡ Aksi Diperlukan</div>
                    <h2>Pantau & Konfirmasi Pengiriman Balik</h2>
                    <p>Penyewa sudah mengirimkan info pengiriman balik. Pantau dan konfirmasi saat koleksi tiba di museum.</p>

                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell">
                            <div class="lbl">Masa Sewa Berakhir</div>
                            <div class="val">{{ $penyewaan->end_date?->format('d M Y') ?? '-' }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Info Pengiriman Balik</div>
                            <div class="val" style="color:#059669;">Sudah Dikirim ✓</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Koleksi Tiba di Museum</div>
                            <div class="val" style="color:#d97706;">Belum Dikonfirmasi</div>
                        </div>
                    </div>
                @endif

                @if(Route::has('pengelola.penyewaan.handover.show'))
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-teal">
                        {{ $tahapPeriksa ? 'Periksa Kondisi & Generate Dokumen →' : 'Proses Pengembalian →' }}
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: menunggu_penerimaan_koleksi (pembatalan karena kerusakan kirim) ══ --}}
            @if($status === 'menunggu_penerimaan_koleksi' && $serahTerima?->isArrivalDamageCancellation())
            @php
                $shipmentDikirimMpk = $serahTerima->return_shipment_submitted_at;
                $koleksiTibaMpk     = $serahTerima->collection_arrived_at;
            @endphp
            <div class="ps-status-section" style="background:#fff7ed;border:1.5px solid #fed7aa;">
                <div class="ps-eyebrow" style="color:#c2410c;">⚡ Aksi Diperlukan — Pembatalan Kerusakan Pengiriman</div>
                <h2>{{ $koleksiTibaMpk ? 'Koleksi Sudah Tiba — Lanjutkan Proses' : 'Konfirmasi Penerimaan Koleksi Balik' }}</h2>
                <p>
                    @if($koleksiTibaMpk)
                        Koleksi sudah dikonfirmasi tiba di museum. Lanjutkan ke pemeriksaan kondisi dan proses refund di halaman serah terima.
                    @elseif($shipmentDikirimMpk)
                        Penyewa telah mengirimkan informasi pengiriman balik koleksi. Pantau status pengiriman, lalu konfirmasi saat koleksi benar-benar sudah tiba di museum.
                    @else
                        Pembatalan disetujui karena kerusakan saat pengiriman. Menunggu penyewa mengirimkan informasi pengiriman balik koleksi.
                    @endif
                </p>
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell {{ $shipmentDikirimMpk ? 'success' : 'warning' }}">
                        <div class="lbl">Info Pengiriman Balik</div>
                        <div class="val">{{ $shipmentDikirimMpk ? 'Sudah Dikirim ✓' : 'Belum Dikirim' }}</div>
                    </div>
                    @if($shipmentDikirimMpk)
                    <div class="ps-meta-cell">
                        <div class="lbl">Metode</div>
                        <div class="val">{{ $serahTerima->return_shipment_method ?? '-' }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">No. Resi</div>
                        <div class="val" style="font-family:monospace;">{{ $serahTerima->return_shipment_tracking ?? '-' }}</div>
                    </div>
                    @endif
                    <div class="ps-meta-cell {{ $koleksiTibaMpk ? 'success' : '' }}">
                        <div class="lbl">Koleksi Tiba di Museum</div>
                        <div class="val">{{ $koleksiTibaMpk ? 'Sudah Dikonfirmasi ✓' : 'Belum Dikonfirmasi' }}</div>
                    </div>
                </div>
                @if(Route::has('pengelola.penyewaan.handover.show'))
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-amber">
                        {{ $koleksiTibaMpk ? 'Lanjutkan Proses Refund →' : 'Pantau & Konfirmasi Penerimaan →' }}
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: menunggu_konfirmasi_refund ══ --}}
            @if($status === 'menunggu_konfirmasi_refund' && $serahTerima?->refund_processed_at && ! $penyewaan->depositRefund)
            <div class="ps-status-section ps-section-pengembalian">
                <div class="ps-eyebrow">⏳ Menunggu Konfirmasi Penyewa</div>
                <h2>{{ $isDamageCompensation ? 'Kompensasi Telah Dikirim — Menunggu Konfirmasi' : 'Refund Telah Dikirim — Menunggu Konfirmasi' }}</h2>
                <p>
                    Bukti transfer {{ $isDamageCompensation ? 'kompensasi' : 'refund' }} telah diunggah pada
                    <strong>{{ $serahTerima->refund_processed_at->format('d M Y H:i') }}</strong>.
                    @if($isDamageCompensation)
                        Menunggu penyewa mengkonfirmasi penerimaan kompensasi sebelum melanjutkan ke dokumen serah terima.
                    @else
                        Menunggu penyewa mengkonfirmasi penerimaan dana. Proses pembatalan akan selesai setelah konfirmasi.
                    @endif
                </p>
                @if($serahTerima->refund_bank_name)
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell">
                        <div class="lbl">Bank</div>
                        <div class="val">{{ $serahTerima->refund_bank_name }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">No. Rekening</div>
                        <div class="val">{{ $serahTerima->refund_account_number }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Atas Nama</div>
                        <div class="val">{{ $serahTerima->refund_account_holder }}</div>
                    </div>
                    @if($serahTerima->refund_amount)
                    <div class="ps-meta-cell success">
                        <div class="lbl">{{ $isDamageCompensation ? 'Nominal Kompensasi' : 'Nominal Refund' }}</div>
                        <div class="val">Rp {{ number_format($serahTerima->refund_amount, 0, ',', '.') }}</div>
                    </div>
                    @endif
                </div>
                @endif
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-indigo">
                        Lihat Detail Bukti Transfer →
                    </a>
                </div>
            </div>
            @elseif($status === 'menunggu_konfirmasi_refund')
            @php
                $depositAmt = $penyewaan->deposit_amount ?: $penyewaan->calculateDeposit();
                $damageCost = (int) ($serahTerima?->final_damage_cost ?? $serahTerima?->damage_cost ?? 0);
                $sisaRefund = max(0, $depositAmt - $damageCost);
                $refund     = $penyewaan->depositRefund;
            @endphp
            <div class="ps-status-section ps-section-pengembalian">
                <div class="ps-eyebrow">
                    {{ $refund ? '⏳ Menunggu Penyewa' : '⚡ Aksi Diperlukan' }}
                </div>
                <h2>{{ $refund ? 'Menunggu Konfirmasi Refund dari Penyewa' : 'Input Bukti Transfer Refund Deposit' }}</h2>

                @if($refund)
                    <p>Bukti refund sudah diinput. Penyewa perlu mengkonfirmasi bahwa dana sudah diterima sebelum lanjut ke tahap TTD dokumen pengembalian.</p>
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell success">
                            <div class="lbl">Nominal Ditransfer</div>
                            <div class="val">Rp {{ number_format($refund->refund_amount, 0, ',', '.') }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Bank / Rekening Penyewa</div>
                            <div class="val">{{ $refund->bank_name }} — {{ $refund->account_number }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Tanggal Transfer</div>
                            <div class="val">{{ $refund->refund_date?->format('d M Y') ?? '-' }}</div>
                        </div>
                    </div>
                @else
                    <p>Pemeriksaan koleksi selesai. Proses pengembalian deposit ke rekening penyewa.</p>
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
                            <div class="lbl">Yang Harus Dikembalikan</div>
                            <div class="val">Rp {{ number_format($sisaRefund, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @if($penyewaan->bank_name)
                    <div class="ps-catatan" style="margin-top:1rem;">
                        <div class="lbl">Rekening Penyewa</div>
                        <div class="val">
                            {{ $penyewaan->bank_name }} — {{ $penyewaan->account_number }}
                            (a.n. {{ $penyewaan->account_holder }})
                        </div>
                    </div>
                    @endif
                @endif

                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-teal">
                        {{ $refund ? 'Lihat Status Refund →' : 'Input Bukti Transfer Refund →' }}
                    </a>
                </div>
            </div>
            @endif

            {{-- ══ STATUS: menunggu_ttd_pengembalian ══ --}}
            @if($status === 'menunggu_ttd_pengembalian')
            <div class="ps-status-section ps-section-serah-terima">
                <div class="ps-eyebrow">⏳ Menunggu Penyewa</div>
                <h2>Menunggu TTD Dokumen Pengembalian</h2>
                <p>Bukti refund sudah diproses. Penyewa sedang mengunduh dan menandatangani dokumen pengembalian.</p>

                @if($penyewaan->depositRefund)
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Refund Diproses</div>
                        <div class="val">Rp {{ number_format($penyewaan->depositRefund->refund_amount, 0, ',', '.') }} ✓</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">Tanggal Transfer</div>
                        <div class="val">{{ $penyewaan->depositRefund->refund_date?->format('d M Y') ?? '-' }}</div>
                    </div>
                </div>
                @endif

                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-indigo">
                        Lihat Detail Serah Terima →
                    </a>
                </div>
            </div>
            @endif

            {{-- ══ STATUS: menunggu_pembayaran_kerusakan ══ --}}
            @if($status === 'menunggu_pembayaran_kerusakan')
            @php $invoice = $penyewaan->damageInvoice; @endphp
            <div class="ps-status-section ps-section-ditolak">
                <div class="ps-eyebrow">⏳ Menunggu Penyewa</div>
                <h2>Menunggu Pelunasan Invoice Kerusakan</h2>
                <p>Biaya kerusakan melebihi deposit. Invoice tambahan sudah dikirim ke penyewa. Proses akan berlanjut setelah invoice dilunasi.</p>

                @if($invoice)
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell danger">
                        <div class="lbl">Tagihan Tambahan</div>
                        <div class="val">Rp {{ number_format($invoice->additional_charge, 0, ',', '.') }}</div>
                    </div>
                    <div class="ps-meta-cell">
                        <div class="lbl">No. Invoice</div>
                        <div class="val" style="font-family:monospace;font-size:.78rem;">{{ $invoice->invoice_number }}</div>
                    </div>
                    <div class="ps-meta-cell {{ $invoice->isPaid() ? 'success' : 'danger' }}">
                        <div class="lbl">Status</div>
                        <div class="val">{{ $invoice->isPaid() ? 'Lunas ✓' : 'Belum Dibayar' }}</div>
                    </div>
                </div>
                @endif

                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-sky">
                        Lihat Detail →
                    </a>
                </div>
            </div>
            @endif

            {{-- ══ STATUS: menunggu_konfirmasi_selesai ══ --}}
            @if($status === 'menunggu_konfirmasi_selesai')
            <div class="ps-status-section ps-section-pengembalian">
                <div class="ps-eyebrow">⚡ Langkah Terakhir</div>
                <h2>Konfirmasi Penyewaan Selesai</h2>
                <p>Penyewa telah menandatangani dan mengunggah dokumen pengembalian. Periksa dokumen lalu konfirmasi untuk menyelesaikan penyewaan.</p>

                @if($serahTerima?->tenant_signed_return_at)
                <div class="ps-meta-grid">
                    <div class="ps-meta-cell success">
                        <div class="lbl">Dok. TTD Diunggah</div>
                        <div class="val">{{ $serahTerima->tenant_signed_return_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
                @endif

                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-teal">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Periksa & Konfirmasi Selesai →
                    </a>
                </div>
            </div>
            @endif

            {{-- ══ STATUS: selesai ══ --}}
            @if($status === 'selesai')
            <div class="ps-status-section ps-section-selesai">
                <div class="ps-eyebrow">🎉 Penyewaan Selesai</div>
                <h2>Transaksi Telah Diarsipkan</h2>
                <p>Seluruh proses selesai. Koleksi kembali tersedia untuk penyewaan berikutnya.</p>
            </div>
            @endif

            {{-- ══ STATUS: ditolak ══ --}}
            @if($status === 'ditolak')
            <div class="ps-status-section ps-section-ditolak">
                <div class="ps-eyebrow">❌ Pengajuan Ditolak</div>
                <h2>Pengajuan Tidak Disetujui</h2>
                @if($penyewaan->rejection_reason)
                <div class="ps-catatan" style="background:#fff;border-color:#fecaca;">
                    <div class="lbl" style="color:#dc2626;">Alasan Penolakan</div>
                    <div class="val">{{ $penyewaan->rejection_reason }}</div>
                </div>
                @endif
            </div>
            @endif

            {{-- ══ STATUS: dibatalkan ══ --}}
            @if($status === 'dibatalkan' && !($serahTerima?->isArrivalDamageCancellation() && $serahTerima?->handover_status !== 'returned'))
            <div class="ps-status-section ps-section-dibatalkan">
                <div class="ps-eyebrow">🚫 Dibatalkan</div>
                <h2>Pengajuan Ini Telah Dibatalkan</h2>
                <p>Tidak ada aksi lebih lanjut yang diperlukan.</p>
            </div>
            @endif

            {{-- ══ STATUS: dibatalkan karena kerusakan — pantau pengembalian ══ --}}
            @if($status === 'dibatalkan' && $serahTerima?->isArrivalDamageCancellation() && $serahTerima?->handover_status !== 'returned')
            <div class="ps-status-section" style="background:#fef2f2;border:1.5px solid #fecaca;">
                <div class="ps-eyebrow" style="color:#dc2626;">⚡ Proses Pembatalan — Kerusakan Pengiriman</div>
                <h2>Pantau Pengembalian Koleksi & Refund</h2>
                <p>Penyewaan dibatalkan karena kerusakan saat pengiriman. Pantau pengembalian koleksi dan proses refund biaya sewa + deposit.</p>
                <div class="ps-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}" class="ps-btn ps-btn-red">
                        Kelola Pengembalian & Refund →
                    </a>
                </div>
            </div>
            @endif

            {{-- ── INFORMASI PENGAJUAN ── --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Informasi Pengajuan</h3>
                </div>
                <div class="ps-card-body">
                    <div class="ps-meta-grid">
                        <div class="ps-meta-cell">
                            <div class="lbl">Nomor Pengajuan</div>
                            <div class="val" style="font-family:'Playfair Display',serif;color:var(--blue);">{{ $penyewaan->nomor_pengajuan ?? 'SW-' . str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Diajukan Pada</div>
                            <div class="val">{{ $penyewaan->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Jenis Penyewa</div>
                            <div class="val">{{ $penyewaan->rental_type === 'instansi' ? 'Instansi' : 'Perseorangan' }}</div>
                        </div>
                        <div class="ps-meta-cell">
                            <div class="lbl">Status Pembayaran</div>
                            <div class="val">{{ ucfirst($penyewaan->payment_status ?? 'unpaid') }}</div>
                        </div>
                        @if($penyewaan->shipping_method_type)
                        <div class="ps-meta-cell highlight">
                            <div class="lbl">Pengiriman</div>
                            <div class="val">{{ $penyewaan->shipping_method_label }}</div>
                        </div>
                        @endif
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
                    <div>
                        <div class="ps-cost-wrap">
                            <div class="ps-cost-row">
                                <span class="lbl">Subtotal Sewa ({{ $durasi }} hari × Rp {{ number_format($hargaSewa, 0, ',', '.') }})</span>
                                <span class="val">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="ps-cost-row">
                                <span class="lbl">Deposit (50% subtotal)</span>
                                <span class="val">Rp {{ number_format($deposit, 0, ',', '.') }}</span>
                            </div>
                            @if($status === 'menunggu_verifikasi')
                                <div class="ps-cost-row">
                                    <span class="lbl">Ongkos Kirim</span>
                                    <span style="font-size:.78rem;color:rgba(255,255,255,.4);font-style:italic;">Ditentukan saat verifikasi</span>
                                </div>
                                <div class="ps-cost-total">
                                    <span class="lbl">Estimasi (tanpa ongkir)</span>
                                    <span class="val">Rp {{ number_format($subtotal + $deposit, 0, ',', '.') }}</span>
                                </div>
                                <div class="ps-info-box">
                                    <p><strong>ℹ️ Catatan:</strong> Ongkos kirim akan ditentukan saat pengelola menyetujui pengajuan.</p>
                                </div>
                            @else
                                <div class="ps-cost-row">
                                    <span class="lbl">
                                        Ongkos Kirim
                                        @if($penyewaan->shipping_method_type === 'courier' && $penyewaan->courier_name)
                                            ({{ $penyewaan->courier_name }})
                                        @elseif($penyewaan->shipping_method_type === 'manager')
                                            (Pengelola)
                                        @endif
                                    </span>
                                    <span class="val" style="{{ (int)$penyewaan->shipping_cost === 0 ? 'color:#34d399;' : '' }}">
                                        {{ (int)$penyewaan->shipping_cost === 0 ? 'Gratis' : 'Rp ' . number_format($penyewaan->shipping_cost, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="ps-cost-total">
                                    <span class="lbl">Total Bayar</span>
                                    <span class="val">Rp {{ number_format($penyewaan->total_bayar ?: ($subtotal + $deposit + (int)$penyewaan->shipping_cost), 0, ',', '.') }}</span>
                                </div>
                                <div class="ps-info-box">
                                    <p><strong>ℹ️ Informasi:</strong> Harga sudah final termasuk deposit yang akan dikembalikan setelah koleksi kembali dalam kondisi baik.</p>
                                </div>
                            @endif
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

                    <div class="ps-data-row">
                        <div class="ps-field"><div class="lbl">Nomor HP Kontak</div><div class="val">{{ $penyewaan->contact_phone ?? '-' }}</div></div>
                        <div class="ps-field"><div class="lbl">Email Kontak</div><div class="val">{{ $penyewaan->contact_email ?? '-' }}</div></div>
                    </div>

                    <div class="ps-address-box">
                        <h4>Alamat Domisili</h4>
                        <table style="width:100%;border-collapse:collapse;">
                            @foreach([
                                'Alamat Lengkap' => $penyewaan->alamat_domisili,
                                'RT / RW'        => ($penyewaan->rt ?? '-') . ' / ' . ($penyewaan->rw ?? '-'),
                                'Kel. / Desa'    => $penyewaan->kelurahan_desa,
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
                    <h3>Detail Koleksi &amp; Jadwal Sewa</h3>
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
                                'Tarif per Hari'  => 'Rp ' . number_format($hargaSewa, 0, ',', '.'),
                                'Tanggal Mulai'   => optional($penyewaan->start_date)->format('d M Y'),
                                'Tanggal Selesai' => optional($penyewaan->end_date)->format('d M Y'),
                                'Durasi'          => $durasi . ' hari',
                                'Indoor/Outdoor'  => $penyewaan->indoor_outdoor ?? '-',
                                'Jenis Tempat'    => $penyewaan->jenis_tempat ?? '-',
                            ] as $lbl => $val)
                                <div class="ps-field"><div class="lbl">{{ $lbl }}</div><div class="val">{{ $val ?? '-' }}</div></div>
                            @endforeach
                            @if($penyewaan->tujuan_penyewaan ?? $penyewaan->purpose ?? null)
                            <div class="ps-field" style="grid-column:1/-1;">
                                <div class="lbl">Tujuan Penyewaan</div>
                                <div class="val">{{ $penyewaan->tujuan_penyewaan ?? $penyewaan->purpose }}</div>
                            </div>
                            @endif
                            @if($penyewaan->deskripsi_kegiatan)
                            <div class="ps-field" style="grid-column:1/-1;">
                                <div class="lbl">Deskripsi Kegiatan</div>
                                <div class="val">{{ $penyewaan->deskripsi_kegiatan }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

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
                    </div>
                </div>
            </div>

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
                        <div class="ps-field" style="grid-column:1/-1;"><div class="lbl">Nama Pemilik Rekening</div><div class="val">{{ $penyewaan->account_holder ?? '-' }}</div></div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── DOKUMEN PENDUKUNG ── --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Dokumen Pendukung</h3>
                </div>
                <div class="ps-card-body">
                    @php
                        $documents = $penyewaan->rental_type === 'instansi'
                            ? [
                                ['label'=>'Surat Permohonan', 'icon'=>'📄', 'path'=>$penyewaan->upload_surat_pengajuan],
                                ['label'=>'KTP PIC',          'icon'=>'🪪', 'path'=>$penyewaan->upload_ktp_pic],
                                ['label'=>'NPWP Instansi',    'icon'=>'📋', 'path'=>$penyewaan->upload_npwp_instansi],
                                ['label'=>'Proposal Acara',   'icon'=>'📑', 'path'=>$penyewaan->upload_proposal],
                                ['label'=>'Foto Lokasi',      'icon'=>'📸', 'path'=>$penyewaan->upload_foto_lokasi],
                                ['label'=>'Denah Lokasi',     'icon'=>'🗺️', 'path'=>$penyewaan->upload_denah],
                              ]
                            : [
                                ['label'=>'KTP',          'icon'=>'🪪', 'path'=>$penyewaan->upload_ktp],
                                ['label'=>'NPWP',         'icon'=>'📋', 'path'=>$penyewaan->upload_npwp],
                                ['label'=>'Foto Lokasi',  'icon'=>'📸', 'path'=>$penyewaan->upload_foto_lokasi],
                                ['label'=>'Denah Lokasi', 'icon'=>'🗺️', 'path'=>$penyewaan->upload_denah],
                              ];
                    @endphp
                    <div class="ps-doc-grid">
                        @foreach($documents as $doc)
                        <div class="ps-doc-card {{ $doc['path'] ? 'has-file' : '' }}">
                            <div class="ps-doc-icon {{ $doc['path'] ? 'has' : 'none' }}">{{ $doc['icon'] }}</div>
                            <div class="ps-doc-label">{{ $doc['label'] }}</div>
                            <div class="ps-doc-status">{{ $doc['path'] ? 'Dokumen tersedia' : 'Tidak mengirimkan dokumen' }}</div>
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