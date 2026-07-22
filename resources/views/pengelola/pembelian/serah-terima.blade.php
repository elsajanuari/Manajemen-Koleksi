<x-app-layout>
    <x-slot name="header">{{-- kosong, hero di dalam --}}</x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    @php $isPengelola = auth()->user()->role === 'pengelola'; @endphp

    @php
        $status = $pembelian->status;
        $isDamageCancellation = $pembelian->isDamageCancellation();
        $isDamageCompensation = $pembelian->isDamageCompensation();

        $showDamageDetailOnSerahTerima = $pembelian->condition_check_status === 'damaged'
            && $pembelian->arrival_damage_reported_at
            && in_array($status, [
                'pengecekan_kondisi',
                'menunggu_review_kerusakan',
                'menunggu_data_rekening',
                'menunggu_penerimaan_koleksi',
                'menunggu_refund_kerusakan',
            ], true);

        $stepOrder = [
            'pembayaran_berhasil'             => 0,
            'siap_diserahkan'                 => 1,
            'dalam_pengiriman'                => 2,
            'pengecekan_kondisi'              => 2,
            'menunggu_review_kerusakan'       => 2,
            'menunggu_data_rekening'          => 2,
            'menunggu_penerimaan_koleksi'     => 2,
            'menunggu_refund_kerusakan'       => 2,
            'menunggu_konfirmasi_refund'      => 2,
            'dibatalkan'                      => 2,
            'menunggu_dokumen_serah_terima'   => 3,
            'menunggu_validasi_serah_terima'  => 4,
            'diterima_pembeli'                => 5,
            'selesai'                         => 6,
        ];
        $currentStep = $stepOrder[$status] ?? 0;

        $steps = [
            0 => ['label' => 'Pembayaran', 'icon' => '💳'],
            1 => ['label' => 'Siap Kirim',  'icon' => '📦'],
            2 => ['label' => 'Dikirim',     'icon' => '🚚'],
            3 => ['label' => 'Upload ST',   'icon' => '📄'],
            4 => ['label' => 'Validasi',    'icon' => '🔍'],
            5 => ['label' => 'Diterima',    'icon' => '✅'],
            6 => ['label' => 'Selesai',     'icon' => '🎉'],
        ];

        $statusBadgeClass = match($status) {
            'pembayaran_berhasil'             => 'st-emerald',
            'siap_diserahkan'                 => 'st-blue',
            'dalam_pengiriman'                => 'st-sky',
            'menunggu_dokumen_serah_terima'   => 'st-slate',
            'menunggu_validasi_serah_terima'  => 'st-amber',
            'menunggu_review_kerusakan'       => 'st-orange',
            'menunggu_data_rekening'          => 'st-amber',
            'menunggu_penerimaan_koleksi'     => 'st-amber',
            'menunggu_refund_kerusakan'       => 'st-orange',
            'menunggu_konfirmasi_refund' => 'st-indigo',
            'diterima_pembeli'                => 'st-indigo',
            'selesai'                         => 'st-green',
            default                           => 'st-slate',
        };

        $statusLabel = match($status) {
            'pembayaran_berhasil'             => 'Pembayaran Berhasil',
            'siap_diserahkan'                 => 'Siap Diserahkan',
            'dalam_pengiriman'                => 'Dalam Pengiriman',
            'menunggu_dokumen_serah_terima'   => 'Menunggu Dok. Serah Terima',
            'menunggu_review_kerusakan'       => 'Menunggu Review Kerusakan',
            'menunggu_data_rekening'          => 'Menunggu Data Rekening',
            'menunggu_penerimaan_koleksi'     => 'Menunggu Penerimaan Koleksi',
            'menunggu_refund_kerusakan'       => 'Menunggu Refund',
            'menunggu_konfirmasi_refund' => 'Menunggu Konfirmasi Refund',
            'menunggu_validasi_serah_terima'  => 'Menunggu Validasi',
            'diterima_pembeli'                => 'Diterima Pembeli',
            'selesai'                         => 'Selesai',
            default                           => ucfirst(str_replace('_', ' ', $status)),
        };
    @endphp

    <style>
        :root {
            --navy:   #0b1d35; --navy-2: #142744; --blue: #1d4ed8;
            --sky:    #38bdf8; --cream:  #f2f5f9; --slate: #64748b;
            --border: #e2e8f0; --white:  #ffffff;
        }
        * { box-sizing: border-box; }
        .st-root { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; padding-bottom: 4rem; }

        /* ── HERO ── */
        .st-hero { background: linear-gradient(140deg,#0b1d35 0%,#142744 55%,#1c3a68 100%); padding: 2.25rem 0; position: relative; overflow: hidden; }
        .st-hero::before { content: ''; position: absolute; top: -60px; right: -80px; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events: none; }
        .st-hero-inner { max-width: 1100px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 1; }
        .st-hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; }

        .st-breadcrumb { display: flex; align-items: center; gap: .45rem; margin-bottom: .85rem; }
        .st-breadcrumb a { color: rgba(255,255,255,.45); font-size: .75rem; font-weight: 500; text-decoration: none; transition: color .15s; }
        .st-breadcrumb a:hover { color: var(--sky); }
        .st-breadcrumb-sep { color: rgba(255,255,255,.25); font-size: .7rem; }
        .st-breadcrumb-cur { color: rgba(255,255,255,.7); font-size: .75rem; font-weight: 600; }

        .st-hero-id { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; color: #fff; line-height: 1.2; margin: 0 0 .3rem; }
        .st-hero-title { font-size: .88rem; color: rgba(255,255,255,.55); margin: 0; }

        .st-hero-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: flex-start; padding-top: .25rem; }
        .st-hero-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.2rem; border-radius: .875rem; font-size: .8rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .18s; border: none; cursor: pointer; white-space: nowrap; }
        .st-hero-btn svg { width: 13px; height: 13px; }
        .st-hero-btn-back { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15); color: rgba(255,255,255,.85); }
        .st-hero-btn-back:hover { background: rgba(255,255,255,.17); }
        .st-hero-btn-track { background: rgba(56,189,248,.15); border: 1px solid rgba(56,189,248,.3); color: var(--sky); }
        .st-hero-btn-track:hover { background: rgba(56,189,248,.25); }

        /* STATUS BADGE */
        .st-status-badge { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem 1rem; border-radius: 99px; font-size: .72rem; font-weight: 700; letter-spacing: .04em; margin-top: .75rem; }
        .st-status-dot { width: 6px; height: 6px; border-radius: 50%; }
        .st-amber   { background: rgba(251,191,36,.15);  border: 1px solid rgba(251,191,36,.3);  color: #fbbf24; }
        .st-amber   .st-status-dot { background: #fbbf24; }
        .st-orange   { background: rgba(249,115,22,.15);  border: 1px solid rgba(249,115,22,.3);  color: #fb923c; }
        .st-orange   .st-status-dot { background: #fb923c; }
        .st-emerald { background: rgba(52,211,153,.15);  border: 1px solid rgba(52,211,153,.3);  color: #34d399; }
        .st-emerald .st-status-dot { background: #34d399; }
        .st-blue    { background: rgba(96,165,250,.15);  border: 1px solid rgba(96,165,250,.3);  color: #60a5fa; }
        .st-blue    .st-status-dot { background: #60a5fa; }
        .st-sky     { background: rgba(56,189,248,.15);  border: 1px solid rgba(56,189,248,.3);  color: var(--sky); }
        .st-sky     .st-status-dot { background: var(--sky); }
        .st-indigo  { background: rgba(129,140,248,.15); border: 1px solid rgba(129,140,248,.3); color: #818cf8; }
        .st-indigo  .st-status-dot { background: #818cf8; }
        .st-green   { background: rgba(74,222,128,.15);  border: 1px solid rgba(74,222,128,.3);  color: #4ade80; }
        .st-green   .st-status-dot { background: #4ade80; }
        .st-slate   { background: rgba(148,163,184,.1);  border: 1px solid rgba(148,163,184,.2); color: #94a3b8; }
        .st-slate   .st-status-dot { background: #94a3b8; }

        /* CONTENT */
        .st-content { max-width: 1100px; margin: 0 auto; padding: 1.75rem 2rem 0; display: grid; gap: 1.25rem; }

        /* FLASH */
        .st-flash { border-radius: .875rem; padding: .85rem 1.2rem; font-size: .83rem; font-weight: 600; display: flex; align-items: center; gap: .55rem; animation: flashIn .35s ease; }
        @keyframes flashIn { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:none} }
        .st-flash svg { width: 16px; height: 16px; flex-shrink: 0; }
        .st-flash.ok  { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .st-flash.err { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

        /* CARD */
        .st-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.5rem; box-shadow: 0 4px 24px rgba(11,29,53,.05); overflow: hidden; }
        .st-card-header { padding: 1.1rem 1.5rem; border-bottom: 1.5px solid #f0f4f8; display: flex; align-items: center; gap: .55rem; }
        .st-card-header-accent { width: 3px; height: 16px; background: linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius: 99px; flex-shrink: 0; }
        .st-card-header h3 { font-size: .76rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--navy); margin: 0; }
        .st-card-body { padding: 1.5rem; }

        /* PROGRESS STEPS */
        .st-step-circle { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .82rem; font-weight: 700; flex-shrink: 0; transition: all .3s; }
        .st-step-circle.done    { background: linear-gradient(135deg,#059669,#10b981); color: #fff; box-shadow: 0 0 0 4px rgba(16,185,129,.12); }
        .st-step-circle.active  { background: linear-gradient(135deg,#1d4ed8,#38bdf8); color: #fff; box-shadow: 0 0 0 4px rgba(29,78,216,.18); }
        .st-step-circle.pending { background: #f1f5f9; color: #94a3b8; border: 2px solid #e2e8f0; }
        .st-step-label { font-size: .68rem; font-weight: 600; text-align: center; white-space: nowrap; }
        .st-step-label.done    { color: #059669; }
        .st-step-label.active  { color: var(--blue); font-weight: 700; }
        .st-step-label.pending { color: #94a3b8; }
        .st-step-line { flex: 1; height: 2px; margin: 0 .25rem; margin-bottom: 1.3rem; border-radius: 99px; }
        .st-step-line.done    { background: linear-gradient(90deg,#10b981,#34d399); }
        .st-step-line.pending { background: #e2e8f0; }

        /* STATUS SECTIONS */
        .st-section { border-radius: 1.25rem; padding: 1.5rem; }
        .st-section .st-eyebrow { font-size: .67rem; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; margin-bottom: .5rem; }
        .st-section h2 { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: var(--navy); margin: 0 0 .5rem; }
        .st-section p  { font-size: .84rem; line-height: 1.7; color: #475569; margin: 0; }

        .st-section-emerald { background: #f0fdf4; border: 1.5px solid #bbf7d0; }
        .st-section-emerald .st-eyebrow { color: #166534; }
        .st-section-blue    { background: #eff6ff; border: 1.5px solid #bfdbfe; }
        .st-section-blue    .st-eyebrow { color: #1d4ed8; }
        .st-section-sky     { background: #f0f9ff; border: 1.5px solid #bae6fd; }
        .st-section-sky     .st-eyebrow { color: #0369a1; }
        .st-section-violet  { background: #f5f3ff; border: 1.5px solid #ddd6fe; }
        .st-section-violet  .st-eyebrow { color: #6d28d9; }
        .st-section-amber   { background: #fffbeb; border: 1.5px solid #fde68a; }
        .st-section-amber   .st-eyebrow { color: #d97706; }
        .st-section-indigo  { background: #eef2ff; border: 1.5px solid #c7d2fe; }
        .st-section-indigo  .st-eyebrow { color: #4338ca; }
        .st-section-green   { background: #f0fdf4; border: 1.5px solid #bbf7d0; }
        .st-section-green   .st-eyebrow { color: #166534; }
        .st-section-orange  { background: #fff7ed; border: 1.5px solid #fed7aa; }
        .st-section-orange  .st-eyebrow { color: #c2410c; }
        .st-section-slate   { background: #f8fafc; border: 1.5px solid #e2e8f0; }
        .st-section-slate   .st-eyebrow { color: #64748b; }

        /* META GRID (info cards) */
        .st-meta-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(160px,1fr)); gap: .875rem; margin-top: 1.1rem; }
        .st-meta-cell { background: var(--white); border: 1.5px solid var(--border); border-radius: 1rem; padding: .9rem 1rem; }
        .st-meta-cell .lbl { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .3rem; }
        .st-meta-cell .val { font-size: .9rem; font-weight: 700; color: var(--navy); }

        /* FORM */
        .st-form-label { display: block; font-size: .75rem; font-weight: 600; color: var(--navy); margin-bottom: .4rem; }
        .st-form-label .req { color: #ef4444; }
        .st-form-label .opt { color: #94a3b8; font-weight: 400; }
        .st-form-input { width: 100%; border: 1.5px solid var(--border); border-radius: .875rem; padding: .6rem .9rem; font-size: .83rem; font-family: 'DM Sans', sans-serif; color: var(--navy); background: #f8fafc; outline: none; transition: border-color .2s, box-shadow .2s; }
        .st-form-input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(29,78,216,.09); background: var(--white); }
        .st-form-textarea { width: 100%; border: 1.5px solid var(--border); border-radius: .875rem; padding: .7rem .9rem; font-size: .83rem; font-family: 'DM Sans', sans-serif; color: var(--navy); background: #f8fafc; outline: none; resize: vertical; transition: border-color .2s; }
        .st-form-textarea:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(29,78,216,.09); background: var(--white); }
        .st-form-group { margin-bottom: .875rem; }
        .st-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .875rem; }
        @media(max-width:640px){ .st-form-grid { grid-template-columns: 1fr; } }
        .st-errors { background: #fef2f2; border: 1.5px solid #fecaca; border-radius: .875rem; padding: .875rem 1rem; margin-bottom: 1rem; }
        .st-errors ul { margin: 0; padding-left: 1.25rem; font-size: .81rem; color: #991b1b; }

        /* RADIO GRID */
        .st-radio-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; margin-bottom: .875rem; }
        .st-radio-label { display: flex; align-items: center; gap: .6rem; background: var(--white); border: 1.5px solid var(--border); border-radius: .875rem; padding: .75rem .9rem; cursor: pointer; font-size: .82rem; font-weight: 500; transition: border-color .15s, background .15s; }
        .st-radio-label:has(input:checked) { border-color: #10b981; background: #f0fdf4; }

        /* CHECKLIST GRID */
        .st-check-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .4rem; }
        .st-check-label { display: inline-flex; align-items: center; gap: .5rem; font-size: .81rem; color: #334155; padding: .4rem .2rem; }
        .st-check-label input { accent-color: #059669; }

        /* BUTTONS */
        .st-action-row { margin-top: 1.1rem; display: flex; gap: .65rem; flex-wrap: wrap; align-items: center; }
        .st-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .65rem 1.35rem; border-radius: .875rem; font-size: .82rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .2s; border: none; cursor: pointer; }
        .st-btn svg { width: 14px; height: 14px; }
        .st-btn-navy    { background: var(--navy); color: #fff; }
        .st-btn-navy:hover    { background: var(--blue); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(29,78,216,.3); }
        .st-btn-blue    { background: linear-gradient(135deg,var(--blue),#2563eb); color: #fff; }
        .st-btn-blue:hover    { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(29,78,216,.35); }
        .st-btn-emerald { background: linear-gradient(135deg,#059669,#10b981); color: #fff; }
        .st-btn-emerald:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(16,185,129,.3); }
        .st-btn-sky     { background: linear-gradient(135deg,#0284c7,var(--sky)); color: #fff; }
        .st-btn-sky:hover     { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(56,189,248,.3); }
        .st-btn-indigo  { background: linear-gradient(135deg,#4338ca,#6366f1); color: #fff; }
        .st-btn-indigo:hover  { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(99,102,241,.3); }
        .st-btn-violet  { background: linear-gradient(135deg,#6d28d9,#7c3aed); color: #fff; }
        .st-btn-violet:hover  { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(109,40,217,.3); }
        .st-btn-amber   { background: linear-gradient(135deg,#d97706,#f59e0b); color: #fff; }
        .st-btn-amber:hover   { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(217,119,6,.3); }
        .st-btn-danger  { background: linear-gradient(135deg,#dc2626,#ef4444); color: #fff; }
        .st-btn-danger:hover  { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(220,38,38,.3); }
        .st-btn-ghost   { background: transparent; border: 1.5px solid var(--border); color: var(--slate); }
        .st-btn-ghost:hover   { background: #f8fafc; border-color: #94a3b8; }

        /* TIMELINE (manager status) */
        .st-timeline { display: flex; flex-direction: column; gap: .625rem; margin-top: 1rem; }
        .st-timeline-item { display: flex; align-items: flex-start; gap: .75rem; background: var(--white); border: 1.5px solid #ddd6fe; border-radius: 1rem; padding: .875rem 1rem; }
        .st-timeline-dot { width: 8px; height: 8px; border-radius: 50%; background: #7c3aed; margin-top: .35rem; flex-shrink: 0; }
        .st-timeline-body .tlabel { font-size: .83rem; font-weight: 600; color: var(--navy); }
        .st-timeline-body .tnote  { font-size: .74rem; color: var(--slate); margin-top: .15rem; }
        .st-timeline-body .tmeta  { font-size: .71rem; color: #94a3b8; margin-top: .25rem; }

        /* DOC PREVIEW BOX */
        .st-doc-preview-box { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.25rem; padding: 1.1rem; }
        .st-doc-preview-head { display: flex; align-items: center; justify-content: space-between; gap: .75rem; margin-bottom: .875rem; flex-wrap: wrap; }
        .st-doc-preview-title { font-size: .82rem; font-weight: 700; color: var(--navy); }
        .st-doc-preview-sub   { font-size: .72rem; color: var(--slate); }
        .st-doc-preview-actions { display: flex; gap: .4rem; flex-wrap: wrap; }

        /* VALIDASI GRID */
        .st-validasi-grid { display: grid; grid-template-columns: 1fr 360px; gap: 1.25rem; margin-top: 1.25rem; align-items: start; }
        @media(max-width:900px){ .st-validasi-grid { grid-template-columns: 1fr; } }

        /* SHIPPING INFO (static) */
        .st-info-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(160px,1fr)); gap: .875rem; }
        .st-info-cell .lbl { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .3rem; }
        .st-info-cell .val { font-size: .83rem; font-weight: 600; color: var(--navy); }

        /* CATATAN BOX */
        .st-catatan { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; padding: .875rem 1rem; margin-top: .875rem; }
        .st-catatan .lbl { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .3rem; }
        .st-catatan .val { font-size: .83rem; color: #334155; line-height: 1.65; }

        /* SUMMARY CARD */
        .st-summary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        @media(max-width:640px){ .st-summary-grid { grid-template-columns: 1fr; } }
        .st-cost-wrap { background: linear-gradient(135deg,#0b1d35,#142744); border-radius: 1.1rem; padding: 1.25rem; }
        .st-cost-row { display: flex; justify-content: space-between; align-items: center; padding: .45rem 0; border-bottom: 1px solid rgba(255,255,255,.07); font-size: .83rem; }
        .st-cost-row:last-child { border-bottom: none; }
        .st-cost-row .lbl { color: rgba(255,255,255,.55); }
        .st-cost-row .val { font-weight: 600; color: #fff; }
        .st-cost-total { display: flex; justify-content: space-between; align-items: center; padding-top: .75rem; margin-top: .5rem; border-top: 1px solid rgba(255,255,255,.1); }
        .st-cost-total .lbl { font-size: .78rem; color: rgba(255,255,255,.5); font-weight: 600; }
        .st-cost-total .val { font-family: 'Playfair Display',serif; font-size: 1.3rem; color: #fff; }

        .st-field .lbl { font-size: .72rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .07em; margin-bottom: .25rem; }
        .st-field .val { font-size: .88rem; color: var(--navy); font-weight: 500; line-height: 1.5; }

        @media(max-width:768px){
            .st-content { padding: 1.25rem 1rem 0; }
            .st-hero-inner { padding: 0 1rem; }
        }

        /* LIGHTBOX */
        .st-lightbox-overlay {
            display: none; position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,.85); backdrop-filter: blur(4px);
            align-items: center; justify-content: center; cursor: zoom-out;
        }
        .st-lightbox-overlay.active { display: flex; }
        .st-lightbox-overlay img {
            max-width: 90vw; max-height: 90vh; border-radius: 1rem;
            box-shadow: 0 24px 80px rgba(0,0,0,.6); cursor: default;
            object-fit: contain;
        }
        .st-lightbox-close {
            position: absolute; top: 1.25rem; right: 1.5rem;
            color: #fff; font-size: 2rem; font-weight: 300;
            cursor: pointer; line-height: 1; opacity: .7; transition: opacity .15s;
        }
        .st-lightbox-close:hover { opacity: 1; }
        .st-zoomable { cursor: zoom-in; transition: opacity .15s; }
        .st-zoomable:hover { opacity: .85; }
    </style>

    <div class="st-root">

        {{-- ── HERO ── --}}
        <div class="st-hero">
            <div class="st-hero-inner">
                <div class="st-hero-top">
                    <div>
                        <div class="st-breadcrumb">
                            @if($isPengelola)
                                <a href="{{ route('pengelola.pembelian.index') }}">Daftar Pengajuan</a>
                                <span class="st-breadcrumb-sep">/</span>
                                <a href="{{ route('pengelola.pembelian.show', $pembelian) }}">BLI-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</a>
                            @else
                                <a href="{{ route('pembelian.show', $pembelian) }}">BLI-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</a>
                            @endif
                            <span class="st-breadcrumb-sep">/</span>
                            <span class="st-breadcrumb-cur">Serah Terima</span>
                        </div>
                        <h1 class="st-hero-id">Pengiriman & Serah Terima Koleksi</h1>
                        <p class="st-hero-title">{{ $pembelian->painting->title }} &mdash; {{ $pembelian->painting->artist }}</p>
                        <div class="st-status-badge {{ $statusBadgeClass }}">
                            <span class="st-status-dot"></span>
                            {{ $statusLabel }}
                        </div>
                    </div>
                    <div class="st-hero-actions">
                        <a href="{{ $isPengelola ? route('pengelola.pembelian.show', $pembelian) : route('pembelian.show', $pembelian) }}"
                           class="st-hero-btn st-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="st-content">

            {{-- FLASH --}}
            @if(session('success'))
                <div class="st-flash ok">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error') && !in_array($status, ['selesai', 'selesai_dengan_kompensasi']))
                <div class="st-flash err">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ════════════════════════════════════════════════════════════
                PEMBELI: Isi Data Rekening / Pengembalian Koleksi
            ════════════════════════════════════════════════════════════ --}}
            @if(!$isPengelola && $status === 'menunggu_data_rekening')
                @if($isDamageCancellation)
                    <div class="st-section st-section-amber">
                        <div class="st-eyebrow">⚡ Aksi Diperlukan</div>
                        <h2>Kembalikan Koleksi ke Museum</h2>
                        <p>Pembatalan disetujui. Kembalikan koleksi ke museum, isi informasi pengiriman balik, data rekening refund, dan nominal ongkir pengembalian beserta buktinya. Refund akan diproses setelah pengelola mengkonfirmasi koleksi tiba di museum.</p>
                    </div>
                    <div class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-header-accent" style="background:linear-gradient(180deg,#d97706,#f59e0b);"></div>
                            <h3>Proses Pengembalian Koleksi &amp; Data Refund</h3>
                        </div>
                        <div class="st-card-body" style="display:grid;gap:1.25rem;">
                            @if($errors->any())
                                <div class="st-errors" style="margin-bottom:1rem;">
                                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                                </div>
                            @endif

                            <form action="{{ route('pembelian.submit-bank-account', $pembelian) }}" method="POST" enctype="multipart/form-data" style="display:grid;gap:1rem;">
                                @csrf
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;">Informasi Pengiriman Balik</div>
                                <div class="st-form-grid">
                                    <div class="st-form-group">
                                        <label class="st-form-label">Metode Pengiriman <span class="req">*</span></label>
                                        <input type="text" name="return_shipment_method" class="st-form-input" required
                                            value="{{ old('return_shipment_method') }}" placeholder="JNE, TIKI, kurir museum, dll">
                                    </div>
                                    <div class="st-form-group">
                                        <label class="st-form-label">Nama Pengirim / Petugas <span class="req">*</span></label>
                                        <input type="text" name="return_shipment_officer" class="st-form-input" required
                                            value="{{ old('return_shipment_officer') }}">
                                    </div>
                                    <div class="st-form-group">
                                        <label class="st-form-label">Nomor Resi <span class="opt">(opsional)</span></label>
                                        <input type="text" name="return_shipment_tracking" class="st-form-input"
                                            value="{{ old('return_shipment_tracking') }}">
                                    </div>
                                    <div class="st-form-group">
                                        <label class="st-form-label">Rencana Tanggal Kirim <span class="req">*</span></label>
                                        <input type="datetime-local" name="return_shipment_scheduled_at" class="st-form-input" required
                                            value="{{ old('return_shipment_scheduled_at') }}">
                                    </div>
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Catatan Pengiriman <span class="opt">(opsional)</span></label>
                                    <textarea name="return_shipment_notes" class="st-form-textarea" rows="2" placeholder="Catatan khusus terkait pengiriman balik...">{{ old('return_shipment_notes') }}</textarea>
                                </div>

                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-top:.5rem;">Ongkir Pengembalian</div>
                                <div class="st-form-grid">
                                    <div class="st-form-group">
                                        <label class="st-form-label">Nominal Ongkir (Rp) <span class="req">*</span></label>
                                        <input type="number" name="return_shipping_cost" class="st-form-input" required min="0"
                                            value="{{ old('return_shipping_cost', 0) }}">
                                    </div>
                                    <div class="st-form-group">
                                        <label class="st-form-label">Bukti Ongkir <span class="req">*</span></label>
                                        <input type="file" name="return_shipping_proof" class="st-form-input" style="padding:.5rem .75rem;" accept="image/*,.pdf" required>
                                    </div>
                                </div>

                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-top:.5rem;">Data Rekening Refund</div>
                                <div class="st-form-grid">
                                    <div class="st-form-group">
                                        <label class="st-form-label">Nama Bank <span class="req">*</span></label>
                                        <input type="text" name="refund_bank_name" class="st-form-input" required
                                            value="{{ old('refund_bank_name') }}" placeholder="Contoh: BCA, Mandiri, BNI">
                                    </div>
                                    <div class="st-form-group">
                                        <label class="st-form-label">Nomor Rekening <span class="req">*</span></label>
                                        <input type="text" name="refund_account_number" class="st-form-input" required
                                            value="{{ old('refund_account_number') }}">
                                    </div>
                                    <div class="st-form-group" style="grid-column:1/-1;">
                                        <label class="st-form-label">Nama Pemilik Rekening <span class="req">*</span></label>
                                        <input type="text" name="refund_account_holder" class="st-form-input" required
                                            value="{{ old('refund_account_holder') }}">
                                    </div>
                                </div>

                                <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:.875rem;padding:.75rem 1rem;">
                                    <p style="font-size:.72rem;color:#92400e;margin:0;line-height:1.6;">
                                        ℹ️ Estimasi refund dasar (total bayar − ongkir awal):
                                        <strong>Rp {{ number_format($pembelian->calculateBaseDamageRefundAmount(), 0, ',', '.') }}</strong>.
                                        Ongkir pengembalian akan ditambahkan setelah koleksi diterima museum.
                                    </p>
                                </div>

                                <div style="display:flex;justify-content:flex-end;">
                                    <button type="submit" class="st-btn st-btn-amber"
                                        onclick="return confirm('Kirim data pengembalian koleksi, ongkir, dan rekening refund?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Kirim Data Pengembalian &amp; Rekening
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#0284c7,#38bdf8);"></div>
                        <h3>Isi Data Rekening untuk Refund</h3>
                    </div>
                    <div class="st-card-body">
                        <p style="font-size:.84rem;color:#475569;margin:0 0 1.25rem;line-height:1.7;">
                            Pengelola telah meninjau laporan kerusakan Anda. Silakan isi data rekening di bawah ini
                            untuk proses refund kompensasi manual. Pengelola akan melakukan transfer dan mengunggah bukti transfer
                            setelah data ini terkirim.
                        </p>

                        @if($errors->any())
                            <div class="st-errors" style="margin-bottom:1rem;">
                                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <form action="{{ route('pembelian.submit-bank-account', $pembelian) }}" method="POST" style="display:grid;gap:.875rem;">                            @csrf
                            <div class="st-form-group">
                                <label class="st-form-label">Nama Bank <span class="req">*</span></label>
                                <input type="text" name="refund_bank_name" class="st-form-input" required
                                    value="{{ old('refund_bank_name') }}" placeholder="Contoh: BCA, Mandiri, BNI">
                            </div>
                            <div class="st-form-group">
                                <label class="st-form-label">Nomor Rekening <span class="req">*</span></label>
                                <input type="text" name="refund_account_number" class="st-form-input" required
                                    value="{{ old('refund_account_number') }}">
                            </div>
                            <div class="st-form-group">
                                <label class="st-form-label">Nama Pemilik Rekening <span class="req">*</span></label>
                                <input type="text" name="refund_account_holder" class="st-form-input" required
                                    value="{{ old('refund_account_holder') }}">
                            </div>
                            <div>
                                <button type="submit" class="st-btn st-btn-sky">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Kirim Data Rekening
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            @endif

            {{-- PEMBELI: Menunggu konfirmasi penerimaan koleksi di museum --}}
            @if(!$isPengelola && $status === 'menunggu_penerimaan_koleksi')
                <div class="st-section st-section-sky">
                    <div class="st-eyebrow">📦 Pengembalian Koleksi</div>
                    <h2>Menunggu Konfirmasi Museum</h2>
                    <p>Data pengembalian koleksi dan rekening refund sudah terkirim. Pantau pengiriman balik di bawah. Proses refund akan dilanjutkan setelah pengelola mengkonfirmasi koleksi tiba di museum.</p>
                </div>
                @include('Pembelian.partials.damage-return-tracking', ['isPengelola' => false])
            @endif

            {{-- ════════════════════════════════════════════════════════════
                PEMBELI: Menunggu Proses Refund (transfer manual oleh pengelola)
            ════════════════════════════════════════════════════════════ --}}
            @if(!$isPengelola && $status === 'menunggu_refund_kerusakan')
                <div class="st-section st-section-sky">
                    <div class="st-eyebrow">⏳ Menunggu Transfer</div>
                    <h2>{{ $isDamageCompensation ? 'Kompensasi Sedang Diproses' : 'Refund Sedang Diproses' }}</h2>
                    <p>
                        @if($isDamageCompensation)
                            Data rekening Anda telah kami terima. Pengelola akan melakukan transfer kompensasi dan mengunggah bukti transfer ke sistem ini.
                        @elseif($isDamageCancellation && $pembelian->collection_arrived_at)
                            Koleksi sudah dikonfirmasi tiba di museum. Pengelola akan melakukan transfer refund
                            (termasuk ongkir pengembalian) dan mengunggah bukti transfer ke sistem ini.
                        @else
                            Data rekening Anda telah kami terima. Pengelola akan melakukan transfer manual dan mengunggah bukti transfer ke sistem ini.
                        @endif
                    </p>
                    @if($pembelian->refund_bank_name)
                        <div class="st-meta-grid">
                            <div class="st-meta-cell">
                                <div class="lbl">Bank</div>
                                <div class="val">{{ $pembelian->refund_bank_name }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">No. Rekening</div>
                                <div class="val">{{ $pembelian->refund_account_number }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">Atas Nama</div>
                                <div class="val">{{ $pembelian->refund_account_holder }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Pembeli: Konfirmasi Penerimaan Refund --}}
            @if(!$isPengelola && $status === 'menunggu_konfirmasi_refund')
                <div class="st-section st-section-emerald">
                    <div class="st-eyebrow">{{ $isDamageCompensation ? '💰 Kompensasi Telah Dikirim' : '💸 Refund Telah Dikirim' }}</div>
                    <h2>{{ $isDamageCompensation ? 'Konfirmasi Penerimaan Kompensasi' : 'Konfirmasi Penerimaan Refund' }}</h2>
                    <p>
                        @if($isDamageCompensation)
                            Pengelola telah mentransfer kompensasi. Cek detail di bawah dan konfirmasi setelah dana masuk ke rekening Anda. Setelah konfirmasi, Anda dapat melanjutkan proses dokumen serah terima.
                        @else
                            Pengelola telah mentransfer refund. Cek detail di bawah dan konfirmasi setelah dana masuk ke rekening Anda.
                        @endif
                    </p>
                </div>

                {{-- Detail bukti transfer --}}
                @if($pembelian->refund_transfer_proof_path)
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#059669,#10b981);"></div>
                        <h3>{{ $isDamageCompensation ? 'Bukti Transfer Kompensasi' : 'Bukti Transfer Refund' }}</h3>
                    </div>
                    <div class="st-card-body">
                        <div class="st-meta-grid" style="margin-bottom:1.25rem;">
                            @if($pembelian->refund_bank_name)
                            <div class="st-meta-cell">
                                <div class="lbl">Bank</div>
                                <div class="val">{{ $pembelian->refund_bank_name }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">No. Rekening</div>
                                <div class="val">{{ $pembelian->refund_account_number }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">Atas Nama</div>
                                <div class="val">{{ $pembelian->refund_account_holder }}</div>
                            </div>
                            @endif
                            @if($pembelian->refund_amount)
                            <div class="st-meta-cell" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#bbf7d0;">
                                <div class="lbl">{{ $isDamageCompensation ? 'Nominal Kompensasi' : 'Nominal Refund' }}</div>
                                <div class="val" style="color:#059669;">Rp {{ number_format($pembelian->refund_amount, 0, ',', '.') }}</div>
                            </div>
                            @endif
                            @if($pembelian->refund_date)
                            <div class="st-meta-cell">
                                <div class="lbl">Tanggal Transfer</div>
                                <div class="val">{{ \Carbon\Carbon::parse($pembelian->refund_date)->format('d M Y') }}</div>
                            </div>
                            @endif
                            @if($pembelian->refund_processed_at)
                            <div class="st-meta-cell">
                                <div class="lbl">Diproses Pada</div>
                                <div class="val">{{ $pembelian->refund_processed_at->format('d M Y H:i') }}</div>
                            </div>
                            @endif
                        </div>

                        {{-- Preview bukti transfer --}}
                        @php
                            $proofExt = pathinfo($pembelian->refund_transfer_proof_path, PATHINFO_EXTENSION);
                        @endphp
                        @if(in_array(strtolower($proofExt), ['jpg','jpeg','png']))
                            <div style="margin-bottom:1rem;">
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.5rem;">Bukti Transfer</div>
                                <img src="{{ asset('storage/' . $pembelian->refund_transfer_proof_path) }}"
                                    style="max-width:100%;border-radius:.875rem;border:1.5px solid var(--border);max-height:400px;object-fit:contain;"
                                    alt="Bukti Transfer"
                                    class="st-zoomable"
                                    onclick="openLightbox(this.src, this.alt)">
                            </div>
                        @elseif(strtolower($proofExt) === 'pdf')
                            <div style="margin-bottom:1rem;">
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.5rem;">Bukti Transfer (PDF)</div>
                                <iframe src="{{ asset('storage/' . $pembelian->refund_transfer_proof_path) }}"
                                    style="width:100%;height:380px;border:1.5px solid var(--border);border-radius:.875rem;"
                                    title="Bukti Transfer"></iframe>
                            </div>
                        @endif

                        @if($pembelian->refund_notes)
                        <div class="st-catatan">
                            <div class="lbl">Catatan Pengelola</div>
                            <div class="val">{{ $pembelian->refund_notes }}</div>
                        </div>
                        @endif

                        <div class="st-action-row" style="margin-top:1.25rem;">
                            <form action="{{ route('pembelian.confirm-refund-received', $pembelian) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('{{ $isDamageCompensation ? 'Konfirmasi bahwa Anda sudah menerima kompensasi? Setelah ini Anda dapat melanjutkan upload dokumen serah terima.' : 'Konfirmasi bahwa Anda sudah menerima refund? Tindakan ini tidak dapat dibatalkan.' }}')"
                                    class="st-btn st-btn-emerald">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $isDamageCompensation ? 'Konfirmasi Kompensasi Sudah Diterima' : 'Konfirmasi Refund Sudah Diterima' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @endif

            {{-- Pembeli: Pembatalan selesai (refund sudah dikonfirmasi) --}}
            @if(!$isPengelola && $status === 'dibatalkan' && $pembelian->refund_confirmed_at)
                <div class="st-section st-section-green">
                    <div class="st-eyebrow">✅ Pembatalan Selesai</div>
                    <h2>Proses Pembatalan Telah Selesai</h2>
                    <p>Refund dikonfirmasi diterima pada <strong>{{ $pembelian->refund_confirmed_at->format('d M Y H:i') }}</strong>. Proses pembatalan transaksi ini telah selesai sepenuhnya.</p>
                    <div class="st-meta-grid">
                        @if($pembelian->refund_amount)
                        <div class="st-meta-cell">
                            <div class="lbl">Total Refund</div>
                            <div class="val" style="color:#059669;">Rp {{ number_format($pembelian->refund_amount, 0, ',', '.') }}</div>
                        </div>
                        @endif
                        @if($pembelian->refund_bank_name)
                        <div class="st-meta-cell">
                            <div class="lbl">Rekening</div>
                            <div class="val">{{ $pembelian->refund_bank_name }} - {{ $pembelian->refund_account_number }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Tetap tampilkan detail bukti transfer untuk referensi --}}
                @if($pembelian->refund_transfer_proof_path)
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#059669,#10b981);"></div>
                        <h3>Detail Refund</h3>
                    </div>
                    <div class="st-card-body">
                        <div class="st-meta-grid" style="margin-bottom:1.25rem;">
                            @if($pembelian->refund_bank_name)
                            <div class="st-meta-cell"><div class="lbl">Bank</div><div class="val">{{ $pembelian->refund_bank_name }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">No. Rekening</div><div class="val">{{ $pembelian->refund_account_number }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">Atas Nama</div><div class="val">{{ $pembelian->refund_account_holder }}</div></div>
                            @endif
                            @if($pembelian->refund_amount)
                            <div class="st-meta-cell" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#bbf7d0;">
                                <div class="lbl">{{ $isDamageCompensation ? 'Nominal Kompensasi' : 'Nominal Refund' }}</div>
                                <div class="val" style="color:#059669;">Rp {{ number_format($pembelian->refund_amount, 0, ',', '.') }}</div>
                            </div>
                            @endif
                            @if($pembelian->refund_date)
                            <div class="st-meta-cell"><div class="lbl">Tanggal Transfer</div><div class="val">{{ \Carbon\Carbon::parse($pembelian->refund_date)->format('d M Y') }}</div></div>
                            @endif
                        </div>

                        @php $proofExt2 = pathinfo($pembelian->refund_transfer_proof_path, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($proofExt2), ['jpg','jpeg','png']))
                            <img src="{{ asset('storage/' . $pembelian->refund_transfer_proof_path) }}"
                                style="max-width:100%;border-radius:.875rem;border:1.5px solid var(--border);max-height:400px;object-fit:contain;"
                                alt="Bukti Transfer"
                                class="st-zoomable"
                                onclick="openLightbox(this.src, this.alt)">
                        @elseif(strtolower($proofExt2) === 'pdf')
                            <iframe src="{{ asset('storage/' . $pembelian->refund_transfer_proof_path) }}"
                                style="width:100%;height:380px;border:1.5px solid var(--border);border-radius:.875rem;"
                                title="Bukti Transfer"></iframe>
                        @endif
                    </div>
                </div>
                @endif
            @endif

            {{-- ══════════════════════════════════════════════════════════
                LAPORAN KERUSAKAN (tampil jika ada laporan)
            ══════════════════════════════════════════════════════════ --}}
            @if(!$isPengelola && $showDamageDetailOnSerahTerima)
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#dc2626,#ef4444);"></div>
                        <h3>Laporan Kerusakan</h3>
                        <span style="margin-left:auto;font-size:.7rem;font-weight:600;color:#94a3b8;">
                            Dilaporkan {{ $pembelian->arrival_damage_reported_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                    <div class="st-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

                        {{-- Keputusan pembeli --}}
                        <div style="display:flex;gap:.75rem;align-items:center;padding:.875rem 1rem;border-radius:1rem;
                            background:{{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#f0fdf4' : '#fef2f2' }};
                            border:1.5px solid {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#bbf7d0' : '#fecaca' }};">
                            <span style="font-size:1.25rem;">
                                {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '✅' : '❌' }}
                            </span>
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.2rem;">Keputusan</div>
                                <div style="font-size:.88rem;font-weight:700;color:{{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#059669' : '#dc2626' }};">
                                    {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? 'Ajukan Kompensasi' : 'Ajukan Pembatalan' }}
                                </div>
                            </div>
                        </div>

                        {{-- Jenis kerusakan --}}
                        @if($pembelian->arrival_damage_items && count($pembelian->arrival_damage_items) > 0)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Jenis Kerusakan</div>
                                <div style="display:flex;flex-direction:column;gap:.4rem;">
                                    @foreach($pembelian->arrival_damage_items as $item)
                                        @if(!empty($item['checked']))
                                            <div style="display:flex;gap:.6rem;align-items:flex-start;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.75rem;padding:.65rem .875rem;">
                                                <span style="color:#dc2626;flex-shrink:0;margin-top:.1rem;">⚠️</span>
                                                <div>
                                                    <div style="font-size:.83rem;font-weight:600;color:#0b1d35;">{{ $item['label'] }}</div>
                                                    @if(!empty($item['description']))
                                                        <div style="font-size:.75rem;color:#64748b;margin-top:.2rem;">{{ $item['description'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Deskripsi umum --}}
                        @if($pembelian->arrival_damage_description)
                            <div class="st-catatan">
                                <div class="lbl">Deskripsi Umum Kerusakan</div>
                                <div class="val">{{ $pembelian->arrival_damage_description }}</div>
                            </div>
                        @endif

                        {{-- Foto depan & belakang --}}
                        @if($pembelian->condition_front_photo || $pembelian->condition_back_photo)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Koleksi</div>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                                    @if($pembelian->condition_front_photo)
                                        <div>
                                            <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Depan</div>
                                            <img src="{{ asset('storage/' . $pembelian->condition_front_photo) }}"
                                                style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;"
                                                alt="Foto Depan Koleksi"
                                                class="st-zoomable"
                                                onclick="openLightbox(this.src, this.alt)">
                                        </div>
                                    @endif
                                    @if($pembelian->condition_back_photo)
                                        <div>
                                            <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Belakang</div>
                                            <img src="{{ asset('storage/' . $pembelian->condition_back_photo) }}"
                                                style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;"
                                                alt="Foto Belakang Koleksi"
                                                class="st-zoomable"
                                                onclick="openLightbox(this.src, this.alt)">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Foto packing + Video dalam grid 2 kolom --}}
                        @if(($pembelian->packing_condition_photos && count($pembelian->packing_condition_photos) > 0) || $pembelian->damage_video_path)
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                            @if($pembelian->packing_condition_photos && count($pembelian->packing_condition_photos) > 0)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Packing</div>
                                @php $packingCount = count($pembelian->packing_condition_photos); @endphp
                                <div style="display:grid;grid-template-columns:repeat({{ $packingCount > 1 ? 2 : 1 }},1fr);gap:.5rem;">
                                    @foreach($pembelian->packing_condition_photos as $photo)
                                        <img src="{{ asset('storage/' . $photo) }}"
                                            style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;height:{{ $packingCount > 1 ? '105px' : '220px' }};cursor:zoom-in;"
                                            alt="Foto Packing"
                                            class="st-zoomable"
                                            onclick="openLightbox(this.src, this.alt)">
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($pembelian->damage_video_path)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Video Bukti Kerusakan</div>
                                <video controls style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);height:220px;background:#000;">
                                    <source src="{{ asset('storage/' . $pembelian->damage_video_path) }}" type="video/mp4">
                                    Browser Anda tidak mendukung pemutaran video.
                                </video>
                            </div>
                            @endif
                        </div>
                        @endif

                        {{-- Foto bukti kurir (jika ada) --}}
                        @if($pembelian->courier_receipt_photos && count($pembelian->courier_receipt_photos) > 0)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Bukti Penerimaan dari Kurir</div>
                                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:.6rem;">
                                    @foreach($pembelian->courier_receipt_photos as $photo)
                                        <img src="{{ asset('storage/' . $photo) }}"
                                            style="width:100%;border-radius:.75rem;border:1.5px solid var(--border);object-fit:cover;height:120px;"
                                            alt="Bukti Kurir"
                                            class="st-zoomable"
                                            onclick="openLightbox(this.src, this.alt)">
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- ════════════════════════════════════════════════════════════
                PENGELOLA: Review Kerusakan — Detail + Form Keputusan
            ════════════════════════════════════════════════════════════ --}}
            @if($isPengelola && $status === 'menunggu_review_kerusakan')

                {{-- Detail Laporan Pembeli --}}
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#dc2626,#ef4444);"></div>
                        <h3>Detail Laporan Kerusakan dari Pembeli</h3>
                        <span style="margin-left:auto;font-size:.7rem;font-weight:600;color:#94a3b8;">
                            Dilaporkan {{ $pembelian->arrival_damage_reported_at?->format('d M Y, H:i') }}
                        </span>
                    </div>
                    <div class="st-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

                        {{-- Keputusan pembeli --}}
                        <div style="display:flex;gap:.75rem;align-items:center;padding:.875rem 1rem;border-radius:1rem;
                            background:{{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#f0fdf4' : '#fef2f2' }};
                            border:1.5px solid {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#bbf7d0' : '#fecaca' }};">
                            <span style="font-size:1.25rem;">
                                {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '✅' : '❌' }}
                            </span>
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.2rem;">Keputusan Pembeli</div>
                                <div style="font-size:.88rem;font-weight:700;color:{{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#059669' : '#dc2626' }};">
                                    {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? 'Ajukan Kompensasi' : 'Ajukan Pembatalan' }}
                                </div>
                            </div>
                        </div>

                        {{-- Jenis kerusakan --}}
                        @if($pembelian->arrival_damage_items && count($pembelian->arrival_damage_items) > 0)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Jenis Kerusakan yang Dilaporkan</div>
                                <div style="display:flex;flex-direction:column;gap:.4rem;">
                                    @foreach($pembelian->arrival_damage_items as $item)
                                        @if(!empty($item['checked']))
                                            <div style="display:flex;gap:.6rem;align-items:flex-start;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.75rem;padding:.65rem .875rem;">
                                                <span style="color:#dc2626;flex-shrink:0;margin-top:.1rem;">⚠️</span>
                                                <div>
                                                    <div style="font-size:.83rem;font-weight:600;color:#0b1d35;">{{ $item['label'] }}</div>
                                                    @if(!empty($item['description']))
                                                        <div style="font-size:.75rem;color:#64748b;margin-top:.2rem;">{{ $item['description'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Deskripsi umum --}}
                        @if($pembelian->arrival_damage_description)
                            <div class="st-catatan">
                                <div class="lbl">Deskripsi Umum Kerusakan</div>
                                <div class="val">{{ $pembelian->arrival_damage_description }}</div>
                            </div>
                        @endif

                        {{-- Foto depan & belakang --}}
                        @if($pembelian->condition_front_photo || $pembelian->condition_back_photo)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Koleksi</div>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                                    @if($pembelian->condition_front_photo)
                                        <div>
                                            <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Depan</div>
                                            <img src="{{ asset('storage/' . $pembelian->condition_front_photo) }}"
                                                style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;"
                                                alt="Foto Depan Koleksi"
                                                class="st-zoomable"
                                                onclick="openLightbox(this.src, this.alt)">
                                        </div>
                                    @endif
                                    @if($pembelian->condition_back_photo)
                                        <div>
                                            <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Belakang</div>
                                            <img src="{{ asset('storage/' . $pembelian->condition_back_photo) }}"
                                                style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;"
                                                alt="Foto Belakang Koleksi"
                                                class="st-zoomable"
                                                onclick="openLightbox(this.src, this.alt)">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Foto packing + Video dalam grid 2 kolom --}}
                        @if(($pembelian->packing_condition_photos && count($pembelian->packing_condition_photos) > 0) || $pembelian->damage_video_path)
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                            @if($pembelian->packing_condition_photos && count($pembelian->packing_condition_photos) > 0)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Packing</div>
                                @php $packingCount = count($pembelian->packing_condition_photos); @endphp
                                <div style="display:grid;grid-template-columns:repeat({{ $packingCount > 1 ? 2 : 1 }},1fr);gap:.5rem;">
                                    @foreach($pembelian->packing_condition_photos as $photo)
                                        <img src="{{ asset('storage/' . $photo) }}"
                                            style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;height:{{ $packingCount > 1 ? '105px' : '220px' }};cursor:zoom-in;"
                                            alt="Foto Packing"
                                            class="st-zoomable"
                                            onclick="openLightbox(this.src, this.alt)">
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if($pembelian->damage_video_path)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Video Bukti Kerusakan</div>
                                <video controls style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);height:220px;background:#000;">
                                    <source src="{{ asset('storage/' . $pembelian->damage_video_path) }}" type="video/mp4">
                                    Browser Anda tidak mendukung pemutaran video.
                                </video>
                            </div>
                            @endif
                        </div>
                        @endif

                    </div>
                </div>

                {{-- Form Keputusan Pengelola --}}
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#d97706,#f59e0b);"></div>
                        <h3>Keputusan Pengelola</h3>
                    </div>
                    <div class="st-card-body">
                        <p style="font-size:.84rem;color:#475569;margin:0 0 1.25rem;line-height:1.7;">
                            Tinjau bukti kerusakan di atas lalu tentukan keputusan.
                            @if($pembelian->arrival_damage_buyer_decision === 'batalkan')
                                Pembeli mengajukan <strong>pembatalan transaksi</strong>. Setujui jika bukti kerusakan valid, atau tolak jika bukti tidak mencukupi.
                            @else
                                Pembeli meminta <strong>kompensasi parsial</strong> dan tetap menerima koleksi. Setujui dengan menentukan nominal, atau tolak jika bukti tidak valid.
                            @endif
                        </p>

                        @if($errors->any())
                            <div class="st-errors" style="margin-bottom:1rem;">
                                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        @if($pembelian->arrival_damage_buyer_decision === 'batalkan')
                            {{-- ── PEMBELI AJUKAN PEMBATALAN: Setujui atau Tolak ── --}}
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">

                                {{-- Panel: Setujui Pembatalan --}}
                                <div id="panel-setujui"
                                    style="border:2px solid var(--border);border-radius:1rem;padding:1.25rem;cursor:pointer;transition:all .15s;background:#fff;"
                                    onclick="selectDecision2('setujui')">
                                    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.6rem;">
                                        <div id="dot-setujui" style="width:16px;height:16px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .15s;"></div>
                                        <span style="font-size:.88rem;font-weight:700;color:#059669;">✅ Setujui Pembatalan</span>
                                    </div>
                                    <div style="font-size:.75rem;color:#475569;line-height:1.6;padding-left:1.6rem;">
                                        Bukti kerusakan valid. Pembeli wajib mengembalikan koleksi ke museum.
                                        Refund dasar (total bayar − ongkir awal), ditambah ongkir pengembalian setelah koleksi diterima.
                                        <div style="margin-top:.5rem;padding:.5rem .75rem;background:#f0fdf4;border-radius:.6rem;font-size:.73rem;font-weight:600;color:#059669;">
                                            Estimasi refund dasar: Rp {{ number_format($pembelian->calculateBaseDamageRefundAmount(), 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Panel: Tolak Pembatalan --}}
                                <div id="panel-tolak"
                                    style="border:2px solid var(--border);border-radius:1rem;padding:1.25rem;cursor:pointer;transition:all .15s;background:#fff;"
                                    onclick="selectDecision2('tolak')">
                                    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.6rem;">
                                        <div id="dot-tolak" style="width:16px;height:16px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .15s;"></div>
                                        <span style="font-size:.88rem;font-weight:700;color:#dc2626;">❌ Tolak Pembatalan</span>
                                    </div>
                                    <div style="font-size:.75rem;color:#475569;line-height:1.6;padding-left:1.6rem;">
                                        Bukti tidak mencukupi. Transaksi tetap sah, pembeli lanjut ke proses serah terima.
                                        <div style="margin-top:.5rem;padding:.5rem .75rem;background:#fef2f2;border-radius:.6rem;font-size:.73rem;font-weight:600;color:#dc2626;">
                                            Pembeli tetap wajib upload dokumen serah terima
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('pengelola.pembelian.serah-terima.decide-damage', $pembelian) }}"
                                method="POST" style="display:grid;gap:.875rem;" id="form-keputusan-batal">
                                @csrf
                                <input type="hidden" name="final_severity" value="parah">
                                <input type="hidden" name="manager_decision" id="input-manager-decision" value="">

                                <div class="st-form-group">
                                    <label class="st-form-label">
                                        Alasan / Catatan untuk Pembeli <span class="req">*</span>
                                    </label>
                                    <textarea name="notes" rows="4" class="st-form-textarea"
                                            id="notes-field"
                                            placeholder="Wajib diisi — jelaskan alasan keputusan Anda kepada pembeli..."
                                            required>{{ old('notes') }}</textarea>
                                    <p id="notes-hint" style="font-size:.72rem;color:#64748b;margin-top:.3rem;display:none;"></p>
                                </div>

                                <div>
                                    <button type="submit" id="btn-submit-keputusan"
                                            class="st-btn st-btn-amber"
                                            style="opacity:.4;pointer-events:none;"
                                            onclick="return confirmKeputusan()">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Simpan Keputusan
                                    </button>
                                </div>
                            </form>

                            <script>
                            function selectDecision2(val) {
                                const panels = { setujui: 'panel-setujui', tolak: 'panel-tolak' };
                                const dots   = { setujui: 'dot-setujui',   tolak: 'dot-tolak'   };
                                const colors = { setujui: '#059669',        tolak: '#dc2626'     };
                                const bgs    = { setujui: '#f0fdf4',        tolak: '#fef2f2'     };
                                const hints  = {
                                    setujui: 'Pembeli akan dinotifikasi bahwa pembatalan disetujui dan refund akan segera diproses.',
                                    tolak:   'Pembeli akan dinotifikasi bahwa klaim kerusakan ditolak dan diminta melanjutkan proses serah terima.'
                                };

                                Object.keys(panels).forEach(k => {
                                    const panel = document.getElementById(panels[k]);
                                    const dot   = document.getElementById(dots[k]);
                                    if (k === val) {
                                        panel.style.border     = '2px solid ' + colors[k];
                                        panel.style.background = bgs[k];
                                        dot.style.border       = '5px solid ' + colors[k];
                                        dot.style.background   = k === 'setujui' ? '#d1fae5' : '#fee2e2';
                                    } else {
                                        panel.style.border     = '2px solid var(--border)';
                                        panel.style.background = '#fff';
                                        dot.style.border       = '2px solid #d1d5db';
                                        dot.style.background   = 'transparent';
                                    }
                                });

                                document.getElementById('input-manager-decision').value = val;

                                const btn  = document.getElementById('btn-submit-keputusan');
                                btn.style.opacity        = '1';
                                btn.style.pointerEvents  = 'auto';
                                btn.className = val === 'setujui'
                                    ? 'st-btn st-btn-emerald'
                                    : 'st-btn st-btn-danger';

                                const hint = document.getElementById('notes-hint');
                                hint.textContent    = hints[val];
                                hint.style.display  = '';

                                // Update placeholder textarea
                                document.getElementById('notes-field').placeholder = val === 'setujui'
                                    ? 'Contoh: Bukti kerusakan valid. Kami akan memproses refund dalam 3-5 hari kerja.'
                                    : 'Contoh: Setelah ditinjau, bukti yang dikirimkan tidak mencukupi untuk membuktikan kerusakan. Transaksi tetap dilanjutkan.';
                            }

                            function confirmKeputusan() {
                                const val = document.getElementById('input-manager-decision').value;
                                if (!val) { alert('Pilih keputusan terlebih dahulu.'); return false; }
                                const notes = document.getElementById('notes-field').value.trim();
                                if (!notes) { alert('Alasan / catatan untuk pembeli wajib diisi.'); return false; }
                                const msg = val === 'setujui'
                                    ? 'Setujui pembatalan dan proses refund Rp {{ number_format($pembelian->calculateFullDamageRefundAmount(), 0, ',', '.') }}?'
                                    : 'Tolak pembatalan? Pembeli akan diminta melanjutkan proses serah terima.';
                                return confirm(msg);
                            }

                            // Auto-restore jika ada old input
                            @if(old('manager_decision'))
                                document.addEventListener('DOMContentLoaded', () => selectDecision2('{{ old('manager_decision') }}'));
                            @endif
                            </script>

                        @else
                            {{-- ── PEMBELI MINTA KOMPENSASI: Setujui atau Tolak ── --}}
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">

                                <div id="panel-komp-setujui"
                                    style="border:2px solid var(--border);border-radius:1rem;padding:1.25rem;cursor:pointer;transition:all .15s;background:#fff;"
                                    onclick="selectKompDecision('setujui')">
                                    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.6rem;">
                                        <div id="dot-komp-setujui" style="width:16px;height:16px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .15s;"></div>
                                        <span style="font-size:.88rem;font-weight:700;color:#059669;">✅ Setujui Kompensasi</span>
                                    </div>
                                    <div style="font-size:.75rem;color:#475569;line-height:1.6;padding-left:1.6rem;">
                                        Bukti kerusakan valid. Tentukan nominal kompensasi, lalu pembeli diminta mengisi rekening untuk transfer.
                                        <div style="margin-top:.5rem;padding:.5rem .75rem;background:#f0fdf4;border-radius:.6rem;font-size:.73rem;font-weight:600;color:#059669;">
                                            Maks kompensasi: Rp {{ number_format($pembelian->calculateBaseDamageRefundAmount(), 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                <div id="panel-komp-tolak"
                                    style="border:2px solid var(--border);border-radius:1rem;padding:1.25rem;cursor:pointer;transition:all .15s;background:#fff;"
                                    onclick="selectKompDecision('tolak')">
                                    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.6rem;">
                                        <div id="dot-komp-tolak" style="width:16px;height:16px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .15s;"></div>
                                        <span style="font-size:.88rem;font-weight:700;color:#dc2626;">❌ Tolak Klaim Kerusakan</span>
                                    </div>
                                    <div style="font-size:.75rem;color:#475569;line-height:1.6;padding-left:1.6rem;">
                                        Bukti tidak mencukupi. Transaksi tetap sah, pembeli lanjut ke proses serah terima tanpa kompensasi.
                                        <div style="margin-top:.5rem;padding:.5rem .75rem;background:#fef2f2;border-radius:.6rem;font-size:.73rem;font-weight:600;color:#dc2626;">
                                            Pembeli tetap wajib upload dokumen serah terima
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('pengelola.pembelian.serah-terima.decide-damage', $pembelian) }}"
                                method="POST" style="display:grid;gap:.875rem;" id="form-keputusan-komp">
                                @csrf
                                <input type="hidden" name="final_severity" value="ringan">
                                <input type="hidden" name="manager_decision" id="input-komp-decision" value="">

                                <div class="st-form-group" id="komp-amount-wrap" style="display:none;">
                                    <label class="st-form-label">Jumlah Kompensasi (Rp) <span class="req">*</span></label>
                                    <input type="number" name="compensation_amount" id="komp-amount-field" class="st-form-input"
                                        value="{{ old('compensation_amount') }}"
                                        min="1" max="{{ $pembelian->calculateBaseDamageRefundAmount() }}"
                                        placeholder="Masukkan jumlah kompensasi">
                                    <p style="font-size:.72rem;color:#64748b;margin-top:.3rem;">
                                        Maks kompensasi: Rp {{ number_format($pembelian->calculateBaseDamageRefundAmount(), 0, ',', '.') }}
                                    </p>
                                </div>

                                <div class="st-form-group">
                                    <label class="st-form-label">Alasan / Catatan untuk Pembeli <span class="req">*</span></label>
                                    <textarea name="notes" rows="4" class="st-form-textarea" id="komp-notes-field"
                                        placeholder="Wajib diisi — jelaskan alasan keputusan Anda kepada pembeli..."
                                        required>{{ old('notes') }}</textarea>
                                    <p id="komp-notes-hint" style="font-size:.72rem;color:#64748b;margin-top:.3rem;display:none;"></p>
                                </div>

                                <div>
                                    <button type="submit" id="btn-submit-komp"
                                            class="st-btn st-btn-slate" disabled style="opacity:.45;pointer-events:none;"
                                            onclick="return confirmKompKeputusan()">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Simpan Keputusan
                                    </button>
                                </div>
                            </form>

                            <script>
                            function selectKompDecision(val) {
                                document.getElementById('input-komp-decision').value = val;
                                ['setujui','tolak'].forEach(v => {
                                    const panel = document.getElementById('panel-komp-' + v);
                                    const dot   = document.getElementById('dot-komp-' + v);
                                    if (!panel) return;
                                    const active = v === val;
                                    panel.style.border     = '2px solid ' + (active ? (v === 'setujui' ? '#059669' : '#ef4444') : 'var(--border)');
                                    panel.style.background = active ? (v === 'setujui' ? '#f0fdf4' : '#fef2f2') : '#fff';
                                    if (dot) {
                                        dot.style.border     = active ? '5px solid ' + (v === 'setujui' ? '#059669' : '#ef4444') : '2px solid #d1d5db';
                                        dot.style.background = active ? (v === 'setujui' ? '#d1fae5' : '#fee2e2') : 'transparent';
                                    }
                                });
                                const amountWrap = document.getElementById('komp-amount-wrap');
                                const amountField = document.getElementById('komp-amount-field');
                                if (amountWrap) amountWrap.style.display = val === 'setujui' ? 'block' : 'none';
                                if (amountField) amountField.required = val === 'setujui';

                                const hints = {
                                    setujui: 'Pembeli akan diminta mengisi rekening untuk menerima transfer kompensasi.',
                                    tolak:   'Pembeli akan dinotifikasi bahwa klaim kompensasi ditolak dan diminta melanjutkan proses serah terima.'
                                };
                                const hint = document.getElementById('komp-notes-hint');
                                if (hint) { hint.textContent = hints[val] || ''; hint.style.display = val ? '' : 'none'; }

                                const btn = document.getElementById('btn-submit-komp');
                                if (btn) {
                                    btn.disabled = !val;
                                    btn.style.opacity = val ? '1' : '.45';
                                    btn.style.pointerEvents = val ? 'auto' : 'none';
                                    btn.className = val === 'setujui' ? 'st-btn st-btn-amber' : (val === 'tolak' ? 'st-btn st-btn-danger' : 'st-btn st-btn-slate');
                                }

                                document.getElementById('komp-notes-field').placeholder = val === 'setujui'
                                    ? 'Contoh: Kompensasi diberikan atas goresan pada bingkai. Akan ditransfer setelah pembeli mengisi rekening.'
                                    : (val === 'tolak' ? 'Contoh: Bukti yang dikirimkan tidak mencukupi untuk membuktikan kerusakan. Transaksi tetap dilanjutkan.' : '');
                            }

                            function confirmKompKeputusan() {
                                const val = document.getElementById('input-komp-decision').value;
                                if (!val) { alert('Pilih keputusan terlebih dahulu.'); return false; }
                                const notes = document.getElementById('komp-notes-field').value.trim();
                                if (!notes) { alert('Alasan / catatan untuk pembeli wajib diisi.'); return false; }
                                if (val === 'setujui') {
                                    const amt = document.getElementById('komp-amount-field').value;
                                    if (!amt || parseInt(amt) < 1) { alert('Jumlah kompensasi wajib diisi.'); return false; }
                                    return confirm('Setujui kompensasi Rp ' + parseInt(amt).toLocaleString('id-ID') + '?');
                                }
                                return confirm('Tolak klaim kompensasi? Pembeli akan diminta melanjutkan proses serah terima tanpa kompensasi.');
                            }

                            @if(old('manager_decision'))
                                document.addEventListener('DOMContentLoaded', () => selectKompDecision('{{ old('manager_decision') }}'));
                            @endif
                            </script>
                        @endif

                    </div>
                </div>

                <script>
                function toggleStCompensation() {
                    const sel  = document.getElementById('st-final-severity');
                    const wrap = document.getElementById('st-compensation-wrap');
                    if (!sel || !wrap) return;
                    wrap.style.display = sel.value === 'ringan' ? 'block' : 'none';
                }
                document.addEventListener('DOMContentLoaded', toggleStCompensation);
                </script>

            @endif

            {{-- ════════════════════════════════════════════════════════════
                PENGELOLA: Menunggu Data Rekening Pembeli
            ════════════════════════════════════════════════════════════ --}}
            @if($isPengelola && $status === 'menunggu_data_rekening')
                <div class="st-section st-section-amber">
                    <div class="st-eyebrow">⏳ Menunggu Pembeli</div>
                    <h2>{{ $isDamageCancellation ? 'Menunggu Pengembalian Koleksi & Data Refund' : 'Menunggu Data Rekening Pembeli' }}</h2>
                    <p>
                        @if($isDamageCancellation)
                            Pembatalan disetujui. Pembeli perlu mengembalikan koleksi ke museum sekaligus mengisi data rekening refund dan ongkir pengembalian.
                            Setelah data terkirim, pantau pengiriman balik dan konfirmasi penerimaan koleksi di museum sebelum memproses refund.
                        @else
                            Keputusan review kerusakan sudah disimpan. Pembeli sedang diminta mengisi data rekening
                            untuk proses refund kompensasi manual.
                        @endif
                    </p>
                </div>
            @endif

            {{-- PENGELOLA: Pantau pengembalian koleksi --}}
            @if($isPengelola && $status === 'menunggu_penerimaan_koleksi')
                <div class="st-section st-section-orange">
                    <div class="st-eyebrow">⚡ Tracking Pengembalian</div>
                    <h2>Pantau Pengembalian Koleksi ke Museum</h2>
                    <p>Pembeli telah mengirimkan informasi pengembalian koleksi. Pantau status pengiriman balik, lalu konfirmasi saat koleksi benar-benar tiba di museum.</p>
                </div>
                @include('Pembelian.partials.damage-return-tracking', ['isPengelola' => true])
            @endif

            {{-- ════════════════════════════════════════════════════════════
                PENGELOLA: Pengecekan Kondisi — Sedang Menunggu Pembeli
            ════════════════════════════════════════════════════════════ --}}
            @if($isPengelola && $status === 'pengecekan_kondisi')
                <div class="st-section st-section-sky">
                    <div class="st-eyebrow">🔍 Pengecekan Kondisi</div>
                    <h2>Menunggu Pembeli Memeriksa Kondisi Koleksi</h2>
                    <p>
                        Koleksi telah diterima oleh pembeli pada
                        <strong>{{ $pembelian->received_at?->format('d M Y, H:i') ?? '-' }}</strong>.
                        Pembeli sedang melakukan pengecekan kondisi — apakah koleksi tiba dalam kondisi
                        baik atau terdapat kerusakan. Tidak ada aksi yang diperlukan dari pengelola saat ini.
                    </p>
                </div>

                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#0284c7,#38bdf8);"></div>
                        <h3>Detail Proses Pengecekan</h3>
                    </div>
                    <div class="st-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

                        {{-- Info pengiriman --}}
                        <div class="st-meta-grid">
                            @if($pembelian->delivery_method)
                            <div class="st-meta-cell">
                                <div class="lbl">Metode Pengiriman</div>
                                <div class="val">
                                    {{ $pembelian->shipping_method_type === 'courier' ? 'Kurir' : 'Pengelola' }}
                                    @if($pembelian->delivery_method)
                                        — {{ $pembelian->delivery_method }}
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if($pembelian->recipient_name)
                            <div class="st-meta-cell">
                                <div class="lbl">Diterima Oleh</div>
                                <div class="val">{{ $pembelian->recipient_name }}</div>
                            </div>
                            @endif
                            @if($pembelian->shipped_at)
                            <div class="st-meta-cell">
                                <div class="lbl">Dikirim Pada</div>
                                <div class="val">{{ $pembelian->shipped_at->format('d M Y H:i') }}</div>
                            </div>
                            @endif
                            @if($pembelian->received_at)
                            <div class="st-meta-cell" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border-color:#bae6fd;">
                                <div class="lbl">Diterima Pada</div>
                                <div class="val" style="color:#0369a1;">{{ $pembelian->received_at->format('d M Y H:i') }}</div>
                            </div>
                            @endif
                        </div>

                        {{-- Alur selanjutnya --}}
                        <div style="background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:1rem;padding:1.1rem;">
                            <div style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#0369a1;margin-bottom:.75rem;">
                                📋 Alur Selanjutnya
                            </div>
                            <div style="display:flex;flex-direction:column;gap:.6rem;">
                                <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                    <div style="width:22px;height:22px;border-radius:50%;background:#0284c7;color:#fff;font-size:.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.05rem;">1</div>
                                    <div>
                                        <div style="font-size:.83rem;font-weight:600;color:#0b1d35;">Pembeli memeriksa kondisi koleksi</div>
                                        <div style="font-size:.74rem;color:#64748b;margin-top:.1rem;">Pembeli memilih apakah kondisi baik atau ada kerusakan.</div>
                                    </div>
                                </div>
                                <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                    <div style="width:22px;height:22px;border-radius:50%;background:#e2e8f0;color:#94a3b8;font-size:.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.05rem;">2a</div>
                                    <div>
                                        <div style="font-size:.83rem;font-weight:600;color:#475569;">Jika kondisi baik</div>
                                        <div style="font-size:.74rem;color:#64748b;margin-top:.1rem;">Pembeli langsung lanjut ke proses unduh dan upload dokumen serah terima.</div>
                                    </div>
                                </div>
                                <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                    <div style="width:22px;height:22px;border-radius:50%;background:#e2e8f0;color:#94a3b8;font-size:.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.05rem;">2b</div>
                                    <div>
                                        <div style="font-size:.83rem;font-weight:600;color:#475569;">Jika ada kerusakan</div>
                                        <div style="font-size:.74rem;color:#64748b;margin-top:.1rem;">Pembeli melaporkan kerusakan beserta bukti. Status akan berubah ke <em>Menunggu Review Kerusakan</em> dan pengelola perlu meninjau laporan tersebut.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:1rem;padding:.875rem 1rem;display:flex;gap:.6rem;align-items:flex-start;">
                            <span style="font-size:1rem;flex-shrink:0;">⏳</span>
                            <p style="font-size:.78rem;color:#92400e;margin:0;line-height:1.6;">
                                Tidak ada aksi yang diperlukan dari pihak pengelola saat ini.
                                Halaman ini akan otomatis diperbarui setelah pembeli menyelesaikan pengecekan kondisi.
                            </p>
                        </div>

                    </div>
                </div>
            @endif

            {{-- ════════════════════════════════════════════════════════════
                PENGELOLA: Proses Refund Kerusakan (transfer manual)
            ════════════════════════════════════════════════════════════ --}}
            @if($isPengelola && $status === 'menunggu_refund_kerusakan')
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#d97706,#f59e0b);"></div>
                        <h3>{{ $isDamageCompensation ? 'Proses Transfer Kompensasi' : 'Proses Refund Kerusakan' }}</h3>
                    </div>
                    <div class="st-card-body">
                        <p style="font-size:.84rem;color:#475569;margin:0 0 1.25rem;line-height:1.7;">
                            Transfer ke rekening pembeli sesuai data di bawah, lalu unggah bukti transfer.
                            @if($pembelian->isFinalSeverityParah())
                                Refund penuh:
                                <strong>Rp {{ number_format($pembelian->calculateFullDamageRefundAmount(), 0, ',', '.') }}</strong>
                                (dasar Rp {{ number_format($pembelian->calculateBaseDamageRefundAmount(), 0, ',', '.') }}
                                @if((int)($pembelian->return_shipping_cost ?? 0) > 0)
                                    + ongkir pengembalian Rp {{ number_format($pembelian->return_shipping_cost, 0, ',', '.') }}
                                @endif
                                )
                            @else
                                Kompensasi: <strong>Rp {{ number_format($pembelian->arrival_damage_compensation_amount ?? 0, 0, ',', '.') }}</strong>
                            @endif
                        </p>

                        @if($pembelian->refund_bank_name)
                            <div class="st-meta-grid" style="margin-bottom:1rem;">
                                <div class="st-meta-cell">
                                    <div class="lbl">Bank</div>
                                    <div class="val">{{ $pembelian->refund_bank_name }}</div>
                                </div>
                                <div class="st-meta-cell">
                                    <div class="lbl">No. Rekening</div>
                                    <div class="val">{{ $pembelian->refund_account_number }}</div>
                                </div>
                                <div class="st-meta-cell">
                                    <div class="lbl">Atas Nama</div>
                                    <div class="val">{{ $pembelian->refund_account_holder }}</div>
                                </div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="st-errors" style="margin-bottom:1rem;">
                                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <form action="{{ route('pengelola.pembelian.serah-terima.store-refund-proof', $pembelian) }}"
                            method="POST" enctype="multipart/form-data" style="display:grid;gap:.875rem;">
                            @csrf
                            <div class="st-form-grid">
                                <div class="st-form-group">
                                    <label class="st-form-label">Nominal Transfer (Rp) <span class="req">*</span></label>
                                    <input type="number" name="refund_amount" class="st-form-input" required
                                        value="{{ $pembelian->isFinalSeverityParah() ? $pembelian->calculateFullDamageRefundAmount() : $pembelian->arrival_damage_compensation_amount }}">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Tanggal Transfer <span class="req">*</span></label>
                                    <input type="date" name="refund_date" class="st-form-input" required value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="st-form-group" style="grid-column:1/-1;">
                                    <label class="st-form-label">Bukti Transfer <span class="req">*</span></label>
                                    <input type="file" name="transfer_proof" class="st-form-input" style="padding:.5rem .75rem;" accept="image/*,.pdf" required>
                                </div>
                                <div class="st-form-group" style="grid-column:1/-1;">
                                    <label class="st-form-label">Catatan <span class="opt">(opsional)</span></label>
                                    <textarea name="refund_notes" class="st-form-textarea" rows="2" placeholder="Contoh: Transfer berhasil, refund pembatalan kerusakan."></textarea>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="st-btn st-btn-amber">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Kirim Bukti Transfer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- ════════════════════════════════════════════════════════════
                PENGELOLA: Menunggu Konfirmasi Refund dari Pembeli
            ════════════════════════════════════════════════════════════ --}}
            @if($isPengelola && $status === 'menunggu_konfirmasi_refund')
                <div class="st-section st-section-indigo">
                    <div class="st-eyebrow">⏳ Menunggu Konfirmasi Pembeli</div>
                    <h2>{{ $isDamageCompensation ? 'Bukti Transfer Kompensasi Sudah Diunggah' : 'Bukti Transfer Sudah Diunggah' }}</h2>
                    <p>
                        Bukti transfer {{ $isDamageCompensation ? 'kompensasi' : 'refund' }} telah diunggah pada
                        <strong>{{ $pembelian->refund_processed_at?->format('d M Y H:i') }}</strong>.
                        @if($isDamageCompensation)
                            Menunggu pembeli mengkonfirmasi penerimaan kompensasi. Setelah konfirmasi, pembeli akan melanjutkan ke dokumen serah terima.
                        @else
                            Menunggu pembeli mengkonfirmasi penerimaan dana. Status akan otomatis berubah ke
                            <em>Dibatalkan</em> setelah pembeli mengkonfirmasi.
                        @endif
                    </p>
                </div>

                @if($pembelian->refund_transfer_proof_path)
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#4338ca,#6366f1);"></div>
                        <h3>Detail Bukti Transfer yang Diunggah</h3>
                    </div>
                    <div class="st-card-body">
                        <div class="st-meta-grid" style="margin-bottom:1.25rem;">
                            @if($pembelian->refund_bank_name)
                            <div class="st-meta-cell"><div class="lbl">Bank</div><div class="val">{{ $pembelian->refund_bank_name }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">No. Rekening</div><div class="val">{{ $pembelian->refund_account_number }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">Atas Nama</div><div class="val">{{ $pembelian->refund_account_holder }}</div></div>
                            @endif
                            @if($pembelian->refund_amount)
                            <div class="st-meta-cell" style="background:linear-gradient(135deg,#eef2ff,#e0e7ff);border-color:#c7d2fe;">
                                <div class="lbl">{{ $isDamageCompensation ? 'Nominal Kompensasi' : 'Nominal Refund' }}</div>
                                <div class="val" style="color:#4338ca;">Rp {{ number_format($pembelian->refund_amount, 0, ',', '.') }}</div>
                            </div>
                            @endif
                            @if($pembelian->refund_date)
                            <div class="st-meta-cell">
                                <div class="lbl">Tanggal Transfer</div>
                                <div class="val">{{ \Carbon\Carbon::parse($pembelian->refund_date)->format('d M Y') }}</div>
                            </div>
                            @endif
                            @if($pembelian->refund_processed_at)
                            <div class="st-meta-cell">
                                <div class="lbl">Diunggah Pada</div>
                                <div class="val">{{ $pembelian->refund_processed_at->format('d M Y H:i') }}</div>
                            </div>
                            @endif
                        </div>

                        @php $proofExt = pathinfo($pembelian->refund_transfer_proof_path, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($proofExt), ['jpg','jpeg','png']))
                            <div style="margin-bottom:1rem;">
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.5rem;">Bukti Transfer</div>
                                <img src="{{ asset('storage/' . $pembelian->refund_transfer_proof_path) }}"
                                    style="max-width:100%;border-radius:.875rem;border:1.5px solid var(--border);max-height:400px;object-fit:contain;"
                                    alt="Bukti Transfer"
                                    class="st-zoomable"
                                    onclick="openLightbox(this.src, this.alt)">
                            </div>
                        @elseif(strtolower($proofExt) === 'pdf')
                            <div style="margin-bottom:1rem;">
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.5rem;">Bukti Transfer (PDF)</div>
                                <iframe src="{{ asset('storage/' . $pembelian->refund_transfer_proof_path) }}"
                                    style="width:100%;height:380px;border:1.5px solid var(--border);border-radius:.875rem;"
                                    title="Bukti Transfer"></iframe>
                            </div>
                        @endif

                        @if($pembelian->refund_notes)
                        <div class="st-catatan">
                            <div class="lbl">Catatan</div>
                            <div class="val">{{ $pembelian->refund_notes }}</div>
                        </div>
                        @endif

                        <div class="st-catatan" style="margin-top:.875rem;background:#eef2ff;border-color:#c7d2fe;">
                            <div class="lbl" style="color:#4338ca;">ℹ️ Status</div>
                            <div class="val" style="color:#4338ca;">
                                Menunggu konfirmasi dari pembeli. Tidak ada aksi lebih lanjut yang diperlukan dari pihak pengelola.
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endif

            {{-- ════════════════════════════════════════════════════════════
                PENGELOLA: Pembatalan Selesai (refund dikonfirmasi pembeli)
            ════════════════════════════════════════════════════════════ --}}
            @if($isPengelola && $status === 'dibatalkan' && $pembelian->refund_confirmed_at)
                <div class="st-section st-section-green">
                    <div class="st-eyebrow">✅ Pembatalan Selesai</div>
                    <h2>Proses Pembatalan Telah Selesai</h2>
                    <p>Refund dikonfirmasi diterima oleh pembeli pada <strong>{{ $pembelian->refund_confirmed_at->format('d M Y H:i') }}</strong>. Proses pembatalan transaksi ini telah selesai sepenuhnya.</p>
                    <div class="st-meta-grid">
                        @if($pembelian->refund_amount)
                        <div class="st-meta-cell">
                            <div class="lbl">Total Refund</div>
                            <div class="val" style="color:#059669;">Rp {{ number_format($pembelian->refund_amount, 0, ',', '.') }}</div>
                        </div>
                        @endif
                        @if($pembelian->refund_bank_name)
                        <div class="st-meta-cell">
                            <div class="lbl">Rekening</div>
                            <div class="val">{{ $pembelian->refund_bank_name }} – {{ $pembelian->refund_account_number }}</div>
                        </div>
                        @endif
                        @if($pembelian->refund_account_holder)
                        <div class="st-meta-cell">
                            <div class="lbl">Atas Nama</div>
                            <div class="val">{{ $pembelian->refund_account_holder }}</div>
                        </div>
                        @endif
                        @if($pembelian->refund_date)
                        <div class="st-meta-cell">
                            <div class="lbl">Tanggal Transfer</div>
                            <div class="val">{{ \Carbon\Carbon::parse($pembelian->refund_date)->format('d M Y') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Detail bukti transfer --}}
                @if($pembelian->refund_transfer_proof_path)
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#059669,#10b981);"></div>
                        <h3>Detail Refund</h3>
                    </div>
                    <div class="st-card-body">
                        <div class="st-meta-grid" style="margin-bottom:1.25rem;">
                            @if($pembelian->refund_bank_name)
                            <div class="st-meta-cell"><div class="lbl">Bank</div><div class="val">{{ $pembelian->refund_bank_name }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">No. Rekening</div><div class="val">{{ $pembelian->refund_account_number }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">Atas Nama</div><div class="val">{{ $pembelian->refund_account_holder }}</div></div>
                            @endif
                            @if($pembelian->refund_amount)
                            <div class="st-meta-cell" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#bbf7d0;">
                                <div class="lbl">{{ $isDamageCompensation ? 'Nominal Kompensasi' : 'Nominal Refund' }}</div>
                                <div class="val" style="color:#059669;">Rp {{ number_format($pembelian->refund_amount, 0, ',', '.') }}</div>
                            </div>
                            @endif
                            @if($pembelian->refund_date)
                            <div class="st-meta-cell"><div class="lbl">Tanggal Transfer</div><div class="val">{{ \Carbon\Carbon::parse($pembelian->refund_date)->format('d M Y') }}</div></div>
                            @endif
                            @if($pembelian->refund_confirmed_at)
                            <div class="st-meta-cell"><div class="lbl">Dikonfirmasi Pembeli</div><div class="val">{{ $pembelian->refund_confirmed_at->format('d M Y H:i') }}</div></div>
                            @endif
                        </div>

                        @php $proofExtMgr = pathinfo($pembelian->refund_transfer_proof_path, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($proofExtMgr), ['jpg','jpeg','png']))
                            <img src="{{ asset('storage/' . $pembelian->refund_transfer_proof_path) }}"
                                style="max-width:100%;border-radius:.875rem;border:1.5px solid var(--border);max-height:400px;object-fit:contain;"
                                alt="Bukti Transfer"
                                class="st-zoomable"
                                onclick="openLightbox(this.src, this.alt)">
                        @elseif(strtolower($proofExtMgr) === 'pdf')
                            <iframe src="{{ asset('storage/' . $pembelian->refund_transfer_proof_path) }}"
                                style="width:100%;height:380px;border:1.5px solid var(--border);border-radius:.875rem;"
                                title="Bukti Transfer"></iframe>
                        @endif

                        @if($pembelian->refund_notes)
                        <div class="st-catatan" style="margin-top:1rem;">
                            <div class="lbl">Catatan</div>
                            <div class="val">{{ $pembelian->refund_notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            @endif

            {{-- ════════════════════════════════════
                 AKSI PENGELOLA
            ════════════════════════════════════ --}}

            {{-- Tahap 1: Isi info pengiriman --}}
            @if($isPengelola && $status === 'pembayaran_berhasil')
                @php
                    $isKurir   = $pembelian->shipping_method_type === 'courier';
                    $isManager = $pembelian->shipping_method_type === 'manager';
                    $defaultRecipient = $pembelian->buyer_type === 'b2b' ? $pembelian->pic_name : $pembelian->nama_lengkap;
                    $defaultLocation  = $pembelian->alamat_pengiriman . ', ' . $pembelian->kota_kabupaten . ', ' . $pembelian->provinsi;
                @endphp

                <div class="st-section {{ $isKurir ? 'st-section-blue' : 'st-section-emerald' }}">
                    <div class="st-eyebrow">⚡ Aksi Diperlukan</div>

                    @if($isKurir)
                        {{-- ── INFO METODE KURIR (dari verifikasi) ── --}}
                        <h2>Konfirmasi Pengiriman via Kurir</h2>
                        <p>Metode pengiriman sudah ditentukan saat verifikasi. Isi data kurir dan nomor resi setelah koleksi diserahkan ke kurir — submit form ini berarti koleksi sudah dikirim.</p>

                        <div class="st-meta-grid">
                            <div class="st-meta-cell">
                                <div class="lbl">Kurir Dipilih</div>
                                <div class="val">{{ $pembelian->courier_name ?? '-' }}
                                    @if($pembelian->courier_service)
                                        <span style="font-weight:400;font-size:.78rem;color:var(--slate);"> — {{ $pembelian->courier_service }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">Ongkos Kirim</div>
                                <div class="val">{{ (int)$pembelian->shipping_cost === 0 ? 'Gratis' : 'Rp ' . number_format($pembelian->shipping_cost, 0, ',', '.') }}</div>
                            </div>
                            @if($pembelian->courier_etd)
                            <div class="st-meta-cell">
                                <div class="lbl">Estimasi Tiba</div>
                                <div class="val">{{ $pembelian->courier_etd }} hari kerja</div>
                            </div>
                            @endif
                        </div>
                    @else
                        <h2>Siapkan &amp; Isi Info Pengiriman</h2>
                        <p>Pembayaran telah diterima. Isi data pengiriman yang akan dilakukan oleh pengelola.</p>
                    @endif

                    @if($errors->any())
                        <div class="st-errors" style="margin-top:1rem;">
                            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('pengelola.pembelian.serah-terima.delivery-info', $pembelian) }}"
                        method="POST" enctype="multipart/form-data" style="margin-top:1.25rem;">
                        @csrf

                        <div class="st-form-grid">
                            @if($isKurir)
                                {{-- Kurir: nama kurir (pre-filled, bisa diedit), nama petugas, nomor resi (wajib), alamat, penerima --}}
                                <div class="st-form-group">
                                    <label class="st-form-label">Nama Kurir &amp; Layanan <span class="req">*</span></label>
                                    <select name="delivery_method" required class="st-form-input">
                                        <option value="">Pilih kurir</option>
                                        @php
                                            $selectedKurir = old('delivery_method', trim(($pembelian->courier_name ?? '') . ' ' . ($pembelian->courier_service ?? '')));
                                        @endphp
                                        @foreach([
                                            'JNE'           => 'JNE',
                                            'J&T Express'   => 'J&T Express',
                                            'SiCepat'       => 'SiCepat',
                                            'ID Express'    => 'ID Express',
                                            'POS Indonesia' => 'POS Indonesia',
                                            'TIKI'          => 'TIKI',
                                            'AnterAja'      => 'AnterAja',
                                            'Lion Parcel'   => 'Lion Parcel',
                                            'SAP Express'   => 'SAP Express',
                                            'Ninja Xpress'  => 'Ninja Xpress',
                                            'Wahana'        => 'Wahana',
                                        ] as $value => $label)
                                            <option value="{{ $value }}" {{ $selectedKurir === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Nomor Resi <span class="req">*</span></label>
                                    <input name="delivery_tracking_number" required
                                        value="{{ old('delivery_tracking_number') }}"
                                        class="st-form-input" placeholder="Nomor resi dari kurir">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Petugas Pengirim <span class="req">*</span></label>
                                    <input name="delivery_officer" required value="{{ old('delivery_officer') }}" class="st-form-input" placeholder="Nama staf yang mengantar ke kurir">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Nama Penerima <span class="req">*</span></label>
                                    <input name="recipient_name" required
                                        value="{{ old('recipient_name', $defaultRecipient) }}"
                                        class="st-form-input">
                                </div>
                                <div class="st-form-group" style="grid-column:1/-1;">
                                    <label class="st-form-label">Alamat Pengiriman <span class="req">*</span></label>
                                    <input name="delivery_location" required
                                        value="{{ old('delivery_location', $defaultLocation) }}"
                                        class="st-form-input">
                                </div>
                            @else
                                {{-- Manager: metode, petugas, alamat, penerima, rencana tanggal, catatan --}}
                                <div class="st-form-group">
                                    <label class="st-form-label">Metode Pengiriman <span class="req">*</span></label>
                                    <input name="delivery_method" required value="{{ old('delivery_method') }}" class="st-form-input" placeholder="Contoh: Kendaraan Operasional Museum">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Petugas Pengiriman <span class="req">*</span></label>
                                    <input name="delivery_officer" required value="{{ old('delivery_officer') }}" class="st-form-input" placeholder="Nama petugas">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Alamat Pengiriman <span class="req">*</span></label>
                                    <input name="delivery_location" required
                                        value="{{ old('delivery_location', $defaultLocation) }}"
                                        class="st-form-input">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Nama Penerima <span class="req">*</span></label>
                                    <input name="recipient_name" required
                                        value="{{ old('recipient_name', $defaultRecipient) }}"
                                        class="st-form-input">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Rencana Tanggal Kirim <span class="opt">(opsional)</span></label>
                                    <input type="datetime-local" name="delivery_scheduled_at" value="{{ old('delivery_scheduled_at') }}" class="st-form-input">
                                </div>
                            @endif
                        </div>

                        <div class="st-form-group">
                            <label class="st-form-label">Catatan Pengiriman <span class="opt">(opsional)</span></label>
                            <textarea name="delivery_notes" rows="3" class="st-form-textarea"
                                placeholder="{{ $isKurir ? 'Contoh: Diserahkan ke counter JNE Purwakarta pukul 14.00' : 'Instruksi packing khusus, catatan kondisi koleksi, dll.' }}">{{ old('delivery_notes') }}</textarea>
                        </div>

                        {{-- ── Upload Foto & Video Kondisi Koleksi Saat Dikirim ── --}}
                        <div style="background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:1rem;padding:1.1rem;margin-bottom:1rem;">
                            <div style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#0369a1;margin-bottom:.75rem;">
                                📸 Dokumentasi Kondisi Koleksi Saat Dikirim
                            </div>
                            <p style="font-size:.78rem;color:#0369a1;margin:0 0 1rem;line-height:1.6;">
                                Dokumentasikan kondisi koleksi sebelum dikirimkan sebagai bukti resmi kondisi awal koleksi.
                            </p>

                            <div class="st-form-grid">
                                <div class="st-form-group">
                                    <label class="st-form-label">Foto Depan Koleksi <span class="req">*</span></label>
                                    <p style="font-size:.73rem;color:var(--slate);margin:0 0 .4rem;">Foto tampak depan koleksi sebelum dikemas/dikirim.</p>
                                    <input type="file" name="dispatch_front_photo"
                                        accept="image/jpg,image/jpeg,image/png" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Foto Belakang Koleksi <span class="req">*</span></label>
                                    <p style="font-size:.73rem;color:var(--slate);margin:0 0 .4rem;">Foto tampak belakang koleksi sebelum dikemas/dikirim.</p>
                                    <input type="file" name="dispatch_back_photo"
                                        accept="image/jpg,image/jpeg,image/png" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Foto Kondisi Packing <span class="req">*</span></label>
                                    <p style="font-size:.73rem;color:var(--slate);margin:0 0 .4rem;">Foto kondisi packing koleksi (bisa lebih dari satu).</p>
                                    <input type="file" name="dispatch_packing_photos[]"
                                        accept="image/jpg,image/jpeg,image/png" required multiple
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Video Kondisi Koleksi <span class="opt">(opsional)</span></label>
                                    <p style="font-size:.73rem;color:var(--slate);margin:0 0 .4rem;">Video singkat kondisi koleksi saat dikirim. Maks 50MB (MP4/MOV/AVI).</p>
                                    <input type="file" name="dispatch_video"
                                        accept="video/mp4,video/quicktime,video/avi"
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>
                            </div>
                        </div>

                        <div style="display:flex;justify-content:flex-end;margin-top:.5rem;">
                            @if($isKurir)
                                <button type="submit"
                                    onclick="return confirm('Konfirmasi koleksi sudah diserahkan ke kurir dan siap dikirim?')"
                                    class="st-btn st-btn-blue">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                    Koleksi Sudah Diserahkan ke Kurir
                                </button>
                            @else
                                <button type="submit" class="st-btn st-btn-emerald">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/></svg>
                                    Simpan &amp; Lanjutkan
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            @endif

            {{-- Update status pengiriman pengelola (SATU ALUR UTUH) --}}
            @if($isPengelola && $pembelian->shipping_method_type === 'manager' 
                && in_array($status, ['siap_diserahkan', 'dalam_pengiriman', 'menunggu_dokumen_serah_terima'])
                && !($status === 'menunggu_dokumen_serah_terima' && $isDamageCompensation && $pembelian->refund_confirmed_at)
                && !($status === 'menunggu_dokumen_serah_terima' && $pembelian->handover_validated_at)
                && !($status === 'menunggu_dokumen_serah_terima' && in_array($pembelian->arrival_damage_manager_decision, ['tolak_kompensasi', 'tolak_pembatalan'])))

                {{-- Section header --}}
                <div class="st-section st-section-violet">
                    <div class="st-eyebrow">🚚 Kelola Pengiriman</div>
                    <h2>Update Status Pengiriman</h2>
                    <p>Perbarui status pengiriman koleksi secara berurutan agar pembeli bisa memantau progress.</p>
                </div>

                {{-- Card timeline + form --}}
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#6d28d9,#7c3aed);"></div>
                        <h3>Status Pengiriman</h3>
                    </div>
                    <div class="st-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

                        @php
                            $statuses = [
                                'dikemas'          => '📦 Dikemas',
                                'siap_dikirim'     => '✅ Siap Kirim',
                                'dalam_perjalanan' => '🚗 Dalam Perjalanan',
                                'tiba_di_tujuan'   => '🏁 Tiba di Tujuan'
                            ];
                            $currentStatus = $pembelian->manager_delivery_status ?? null;
                            $statusKeys    = array_keys($statuses);
                            $currentIndex  = $currentStatus ? array_search($currentStatus, $statusKeys) : -1;
                        @endphp

                        {{-- Riwayat timeline --}}
                        @if($pembelian->manager_delivery_timeline && count($pembelian->manager_delivery_timeline) > 0)
                            <div class="st-timeline">
                                <div style="margin-bottom:.5rem;font-size:.7rem;font-weight:600;color:#64748b;">📋 RIWAYAT STATUS</div>
                                @foreach(array_reverse($pembelian->manager_delivery_timeline) as $entry)
                                    <div class="st-timeline-item">
                                        <div class="st-timeline-dot" style="background:
                                            @switch($entry['status'])
                                                @case('dikemas') #f59e0b @break
                                                @case('siap_dikirim') #3b82f6 @break
                                                @case('dalam_perjalanan') #8b5cf6 @break
                                                @case('tiba_di_tujuan') #10b981 @break
                                                @default #94a3b8
                                            @endswitch
                                        "></div>
                                        <div class="st-timeline-body">
                                            <div class="tlabel">{{ $entry['label'] }}</div>
                                            @if($entry['catatan'])<div class="tnote">{{ $entry['catatan'] }}</div>@endif
                                            <div class="tmeta">{{ \Carbon\Carbon::parse($entry['timestamp'])->format('d M Y, H:i') }} &bull; {{ $entry['by'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="st-catatan">
                                <div class="lbl">Belum ada update status</div>
                                <div class="val">Mulai update status "Sedang Dikemas" untuk memulai proses pengiriman.</div>
                            </div>
                        @endif

                        {{-- Form update --}}
                        @if($currentStatus !== 'tiba_di_tujuan')
                            <form action="{{ route('pengelola.pembelian.serah-terima.manager-status', $pembelian) }}"
                                method="POST">
                                @csrf

                                <div class="st-form-group">
                                    <label class="st-form-label">Update ke status berikutnya:</label>
                                    <div class="st-radio-grid" style="grid-template-columns:repeat(2,1fr);">
                                        @foreach($statuses as $value => $label)
                                            @php
                                                $statusIdx  = array_search($value, $statusKeys);
                                                $isDisabled = $statusIdx <= $currentIndex;
                                                $isNext     = $statusIdx === $currentIndex + 1;
                                            @endphp
                                            <label class="st-radio-label"
                                                style="{{ $isNext ? 'border-color:#f59e0b;background:#fffbeb;' : ($isDisabled ? 'opacity:0.5;' : '') }}">
                                                <input type="radio" name="manager_delivery_status" value="{{ $value }}"
                                                    {{ $isNext ? 'checked' : '' }}
                                                    {{ $isDisabled ? 'disabled' : '' }}>
                                                <span>{{ $label }}</span>
                                                @if($isNext)
                                                    <span style="margin-left:auto;font-size:.65rem;background:#f59e0b20;color:#d97706;padding:.15rem .4rem;border-radius:99px;">BERIKUTNYA</span>
                                                @endif
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="st-form-group">
                                    <label class="st-form-label">Catatan <span class="opt">(opsional)</span></label>
                                    <input type="text" name="catatan_status" class="st-form-input"
                                        placeholder="Contoh: Koleksi sudah sampai di alamat tujuan">
                                </div>

                                <div style="display:flex;justify-content:flex-end;margin-top:.5rem;">
                                    <button type="submit" class="st-btn st-btn-violet">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                        </svg>
                                        Update Status
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="st-catatan" style="background:#d1fae5;border-color:#6ee7b7;">
                                <div class="lbl" style="color:#065f46;">✅ Pengiriman Selesai</div>
                                <div class="val">Koleksi telah tiba di tujuan. Menunggu konfirmasi penerimaan dari pembeli.</div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif

            {{-- ── PENGELOLA: dalam_pengiriman ── --}}

            {{-- Kurir: info pengiriman + tracking Binderbyte --}}
            @if($isPengelola && $status === 'dalam_pengiriman' && $pembelian->shipping_method_type === 'courier')
                <div class="st-section st-section-sky">
                    <div class="st-eyebrow">🚚 Dalam Pengiriman via Kurir</div>
                    <h2>Koleksi Sedang Dikirim</h2>
                    <p>Koleksi sedang dalam perjalanan ke pembeli. Pantau status pengiriman di bawah. Pembeli akan mengkonfirmasi penerimaan setelah koleksi tiba.</p>
                    <div class="st-meta-grid">
                        <div class="st-meta-cell">
                            <div class="lbl">Kurir</div>
                            <div class="val">{{ $pembelian->delivery_method ?? '-' }}</div>
                        </div>
                        <div class="st-meta-cell">
                            <div class="lbl">No. Resi</div>
                            <div class="val" style="font-family:monospace;">{{ $pembelian->delivery_tracking_number ?? '-' }}</div>
                        </div>
                        <div class="st-meta-cell">
                            <div class="lbl">Dikirim Pada</div>
                            <div class="val">{{ $pembelian->shipped_at?->format('d M Y H:i') ?? '-' }}</div>
                        </div>
                        <div class="st-meta-cell">
                            <div class="lbl">Penerima</div>
                            <div class="val">{{ $pembelian->recipient_name ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Tracking card Binderbyte --}}
                <div class="st-card" id="tracking-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent"></div>
                        <h3>Lacak Pengiriman</h3>
                        <div style="margin-left:auto;">
                            <button onclick="loadTracking(true)" id="btn-refresh"
                                style="display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .85rem;border:1.5px solid var(--border);border-radius:.6rem;background:var(--white);font-size:.73rem;font-weight:600;color:var(--slate);cursor:pointer;transition:all .15s;"
                                onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
                                onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--slate)'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                Refresh
                            </button>
                        </div>
                    </div>
                    <div class="st-card-body" id="tracking-body">
                        <div id="tracking-loading" style="display:flex;flex-direction:column;gap:.75rem;">
                            @for($i = 0; $i < 3; $i++)
                            <div style="height:48px;background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%);background-size:200% 100%;border-radius:.75rem;animation:shimmer 1.5s infinite;"></div>
                            @endfor
                        </div>
                        <div id="tracking-error" style="display:none;" class="st-catatan">
                            <div class="lbl" style="color:#dc2626;">Gagal Memuat</div>
                            <div class="val" id="tracking-error-msg"></div>
                        </div>
                        <div id="tracking-content" style="display:none;">
                            <div id="tracking-summary" style="margin-bottom:1.25rem;"></div>
                            <div id="tracking-history"></div>
                        </div>
                    </div>
                </div>
                <style>@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}</style>
                <script>
                const TRACKING_URL = "{{ route('pengelola.pembelian.serah-terima.tracking-data', $pembelian) }}";

                async function loadTracking(refresh = false) {
                    const loadingEl  = document.getElementById('tracking-loading');
                    const errorEl    = document.getElementById('tracking-error');
                    const contentEl  = document.getElementById('tracking-content');
                    const btnRefresh = document.getElementById('btn-refresh');

                    loadingEl.style.display = 'flex';
                    errorEl.style.display   = 'none';
                    contentEl.style.display = 'none';
                    if (btnRefresh) btnRefresh.disabled = true;

                    try {
                        const res    = await fetch(TRACKING_URL + (refresh ? '?refresh=1' : ''), {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (!res.ok) throw new Error('HTTP ' + res.status);
                        const result = await res.json();
                        loadingEl.style.display = 'none';

                        if (!result.success || !result.data) {
                            document.getElementById('tracking-error-msg').textContent =
                                result.message || 'Data tracking tidak tersedia saat ini.';
                            errorEl.style.display = '';
                            return;
                        }
                        renderTracking(result.data);
                        contentEl.style.display = '';
                    } catch (err) {
                        loadingEl.style.display = 'none';
                        document.getElementById('tracking-error-msg').textContent =
                            'Gagal memuat data tracking. Coba klik tombol Refresh.';
                        errorEl.style.display = '';
                    } finally {
                        if (btnRefresh) btnRefresh.disabled = false;
                    }
                }

                function renderTracking(data) {
                    const isDelivered = data.delivered;
                    document.getElementById('tracking-summary').innerHTML = `
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:.75rem;margin-bottom:1rem;">
                            <div class="st-meta-cell">
                                <div class="lbl">Kurir</div>
                                <div class="val">${esc(data.courier)}${data.service ? ' <span style="font-weight:400;font-size:.78rem;color:var(--slate);">— ' + esc(data.service) + '</span>' : ''}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">No. Resi</div>
                                <div class="val" style="font-family:monospace;font-size:.82rem;">${esc(data.awb)}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">Status</div>
                                <div class="val">
                                    <span style="display:inline-flex;align-items:center;gap:.35rem;padding:.35rem 1rem;border-radius:99px;font-size:.75rem;font-weight:700;
                                        background:${isDelivered ? '#d1fae5' : '#eff6ff'};
                                        border:1.5px solid ${isDelivered ? '#6ee7b7' : '#bfdbfe'};
                                        color:${isDelivered ? '#065f46' : '#1d4ed8'};">
                                        ${isDelivered ? '✓' : '→'} ${esc(data.status)}
                                    </span>
                                </div>
                            </div>
                            ${data.destination ? `<div class="st-meta-cell"><div class="lbl">Tujuan</div><div class="val">${esc(data.destination)}</div></div>` : ''}
                        </div>`;

                    const historyEl = document.getElementById('tracking-history');
                    if (!data.history || data.history.length === 0) {
                        historyEl.innerHTML = `<div class="st-catatan"><div class="lbl">Riwayat Pengiriman</div><div class="val">Belum ada update dari kurir.</div></div>`;
                        return;
                    }
                    let html = `<div style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">Riwayat Pengiriman</div>`;
                    data.history.forEach((item, i) => {
                        html += `<div style="display:flex;gap:.875rem;padding:.75rem 0;border-bottom:1px solid #f0f4f8;">
                            <div style="width:8px;height:8px;border-radius:50%;background:${i===0?'#1d4ed8':'#38bdf8'};margin-top:.35rem;flex-shrink:0;${i===0?'box-shadow:0 0 0 3px rgba(29,78,216,.15);':''}"></div>
                            <div style="flex:1;">
                                <div style="font-size:.83rem;font-weight:500;color:#0b1d35;line-height:1.4;">${esc(item.description)}</div>
                                <div style="font-size:.72rem;color:#94a3b8;margin-top:.2rem;">${item.city ? esc(item.city) + ' &bull; ' : ''}${esc(item.datetime)}</div>
                            </div>
                        </div>`;
                    });
                    document.getElementById('tracking-history').innerHTML = html;
                }

                function esc(str) {
                    if (!str) return '';
                    const d = document.createElement('div');
                    d.textContent = String(str);
                    return d.innerHTML;
                }

                document.addEventListener('DOMContentLoaded', () => loadTracking(false));
                </script>
            @endif

            {{-- Manager: menunggu konfirmasi pembeli setelah tiba_di_tujuan --}}
            @if($isPengelola && $status === 'dalam_pengiriman'
                && $pembelian->shipping_method_type === 'manager'
                && $pembelian->manager_delivery_status === 'tiba_di_tujuan')
                <div class="st-section st-section-sky">
                    <div class="st-eyebrow">⏳ Menunggu Pembeli</div>
                    <h2>Menunggu Konfirmasi Penerimaan</h2>
                    <p>Koleksi telah tiba di tujuan. Menunggu konfirmasi penerimaan dari pembeli. Status akan berubah otomatis setelah pembeli mengkonfirmasi.</p>
                    <div class="st-meta-grid">
                        <div class="st-meta-cell">
                            <div class="lbl">Dikirim Pada</div>
                            <div class="val">{{ $pembelian->shipped_at?->format('d M Y H:i') ?? '-' }}</div>
                        </div>
                        <div class="st-meta-cell">
                            <div class="lbl">Penerima</div>
                            <div class="val">{{ $pembelian->recipient_name ?? '-' }}</div>
                        </div>
                        <div class="st-meta-cell">
                            <div class="lbl">Metode</div>
                            <div class="val">{{ $pembelian->delivery_method ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Menunggu upload dokumen dari pembeli --}}
            @if($isPengelola && $status === 'menunggu_dokumen_serah_terima')
                @if($pembelian->handover_validated_at)
                    {{-- Sudah ada penolakan dokumen serah terima --}}
                    <div class="st-section st-section-orange">
                        <div class="st-eyebrow">⏳ Menunggu Upload Ulang</div>
                        <h2>Menunggu Upload Ulang Dokumen Serah Terima</h2>
                        <p>Dokumen serah terima yang dikirim pembeli telah ditolak. Pembeli sedang diminta untuk mengunggah ulang dokumen yang sudah ditandatangani dengan benar.</p>
                    </div>

                    <div class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-header-accent" style="background:linear-gradient(180deg,#dc2626,#ef4444);"></div>
                            <h3>Detail Penolakan Dokumen</h3>
                            <span style="margin-left:auto;font-size:.7rem;font-weight:600;color:#94a3b8;">
                                Ditolak {{ $pembelian->handover_validated_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        <div class="st-card-body">
                            <div style="display:flex;gap:.75rem;align-items:flex-start;background:#fef2f2;border:1.5px solid #fecaca;border-radius:1rem;padding:1rem 1.1rem;">
                                <span style="font-size:1.25rem;flex-shrink:0;">❌</span>
                                <div>
                                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#dc2626;margin-bottom:.35rem;">
                                        Alasan Penolakan yang Dikirim ke Pembeli
                                    </div>
                                    <div style="font-size:.84rem;color:#334155;line-height:1.65;">
                                        {{ $pembelian->handover_validation_notes ?? '-' }}
                                    </div>
                                    <div style="font-size:.73rem;color:#94a3b8;margin-top:.5rem;">
                                        Pembeli akan mengunggah ulang dokumen setelah menerima notifikasi penolakan ini.
                                    </div>
                                </div>
                            </div>
                            <div class="st-meta-grid" style="margin-top:1rem;">
                                <div class="st-meta-cell">
                                    <div class="lbl">Dokumen Ditolak Pada</div>
                                    <div class="val">{{ $pembelian->handover_validated_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($isDamageCompensation && $pembelian->refund_confirmed_at)
                    {{-- Kompensasi sudah dikonfirmasi, menunggu upload dokumen pertama kali --}}
                    <div class="st-section st-section-emerald">
                        <div class="st-eyebrow">✅ Kompensasi Selesai</div>
                        <h2>Menunggu Dokumen Serah Terima</h2>
                        <p>Pembeli telah mengkonfirmasi penerimaan kompensasi pada <strong>{{ $pembelian->refund_confirmed_at->format('d M Y H:i') }}</strong>. Menunggu pembeli mengunduh, menandatangani, dan mengunggah dokumen serah terima.</p>
                        @if($pembelian->refund_amount)
                        <div class="st-meta-grid" style="margin-top:.75rem;">
                            <div class="st-meta-cell" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#bbf7d0;">
                                <div class="lbl">Nominal Kompensasi</div>
                                <div class="val" style="color:#059669;">Rp {{ number_format($pembelian->refund_amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                @else
                    {{-- Belum ada upload dokumen sama sekali --}}
                    <div class="st-section st-section-slate">
                        <div class="st-eyebrow">⏳ Menunggu Pembeli</div>
                        <h2>Menunggu Upload Dokumen dari Pembeli</h2>
                        <p>Koleksi telah diterima pembeli. Menunggu pembeli mengunduh, menandatangani, dan mengunggah dokumen serah terima.</p>
                    </div>

                    @if(in_array($pembelian->arrival_damage_manager_decision, ['tolak_kompensasi', 'tolak_pembatalan']) && $pembelian->arrival_damage_manager_notes)
                    <div class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-header-accent" style="background:linear-gradient(180deg,#dc2626,#ef4444);"></div>
                            <h3>{{ $pembelian->arrival_damage_manager_decision === 'tolak_kompensasi' ? 'Klaim Kompensasi Ditolak' : 'Klaim Pembatalan Ditolak' }}</h3>
                        </div>
                        <div class="st-card-body">
                            <div style="display:flex;gap:.75rem;align-items:flex-start;background:#fef2f2;border:1.5px solid #fecaca;border-radius:1rem;padding:1rem 1.1rem;">
                                <span style="font-size:1.25rem;flex-shrink:0;">❌</span>
                                <div>
                                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#dc2626;margin-bottom:.35rem;">Keputusan Pengelola</div>
                                    <div style="font-size:.84rem;color:#334155;line-height:1.65;">{{ $pembelian->arrival_damage_manager_notes }}</div>
                                    <div style="font-size:.73rem;color:#94a3b8;margin-top:.5rem;">Pembeli diminta melanjutkan proses serah terima dokumen seperti transaksi normal.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endif
            @endif

            {{-- Validasi dokumen serah terima --}}
            @if($isPengelola && $status === 'menunggu_validasi_serah_terima')
                <div class="st-section st-section-amber">
                    <div class="st-eyebrow">⚡ Validasi Diperlukan</div>
                    <h2>Periksa Dokumen Serah Terima</h2>
                    <p>Unduh dokumen yang dikirim pembeli dan verifikasi kondisi koleksi sebelum menyetujui atau menolak.</p>

                    <div class="st-doc-preview-actions" style="margin-top:1rem;display:flex;gap:.5rem;flex-wrap:wrap;">
                        @if($pembelian->handover_document_path)
                            <a href="{{ route('pembelian.serah-terima.download', $pembelian) }}" class="st-btn st-btn-ghost">Unduh Dokumen Awal</a>
                        @endif
                        @if($pembelian->handover_signed_document_path)
                            <a href="{{ route('pengelola.pembelian.serah-terima.uploaded.download', $pembelian) }}" class="st-btn st-btn-amber">↓ Unduh Ditandatangani</a>
                        @endif
                    </div>

                    <div class="st-validasi-grid">
                        <div class="st-doc-preview-box">
                            <div class="st-doc-preview-head">
                                <div>
                                    <div class="st-doc-preview-title">Dokumen Serah Terima</div>
                                    <div class="st-doc-preview-sub">Diunggah oleh pembeli</div>
                                </div>
                                <div class="st-doc-preview-actions">
                                    @if($pembelian->handover_signed_document_path)
                                        <a href="{{ route('pengelola.pembelian.serah-terima.uploaded.preview', $pembelian) }}" target="_blank" class="st-btn st-btn-ghost" style="padding:.35rem .75rem;font-size:.72rem;">🔍 Tab Baru</a>
                                    @endif
                                </div>
                            </div>
                            @if($pembelian->handover_signed_document_path)
                                <iframe src="{{ route('pengelola.pembelian.serah-terima.uploaded.preview', $pembelian) }}"
                                    style="width:100%;height:380px;border:0;border-radius:.75rem;" title="Preview Dokumen Serah Terima"></iframe>
                                <div class="st-meta-grid" style="margin-top:.875rem;">
                                    <div class="st-meta-cell">
                                        <div class="lbl">Nama File</div>
                                        <div class="val" style="font-size:.78rem;word-break:break-all;">📄 {{ basename($pembelian->handover_signed_document_path) }}</div>
                                    </div>
                                    <div class="st-meta-cell">
                                        <div class="lbl">Diunggah Pada</div>
                                        <div class="val">{{ $pembelian->handover_document_uploaded_at?->format('d M Y, H:i') ?? '-' }}</div>
                                    </div>
                                </div>
                            @else
                                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:200px;color:#94a3b8;font-size:.83rem;background:#f8fafc;border-radius:.75rem;">
                                    <span style="font-size:2.5rem;margin-bottom:.75rem;">📄</span>
                                    Pembeli belum mengunggah dokumen serah terima yang ditandatangani.
                                </div>
                            @endif
                        </div>

                        <div>
                            <form action="{{ route('pengelola.pembelian.serah-terima.validate', $pembelian) }}" method="POST" style="display:flex;flex-direction:column;gap:.875rem;">
                                @csrf
                                <div class="st-radio-grid">
                                    <label class="st-radio-label">
                                        <input type="radio" name="action" value="validate" checked>
                                        <span>✅ Validasi</span>
                                    </label>
                                    <label class="st-radio-label">
                                        <input type="radio" name="action" value="reject">
                                        <span>❌ Tolak</span>
                                    </label>
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Catatan Validasi</label>
                                    <textarea name="validation_notes" rows="4" class="st-form-textarea" placeholder="Opsional: berikan alasan jika menolak dokumen."></textarea>
                                </div>
                                <button type="submit" class="st-btn st-btn-navy" style="justify-content:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Simpan Keputusan
                                </button>
                            </form>

                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const formValidasi = document.querySelector('form[action*="validate"]');
                                if (!formValidasi) return;
                                formValidasi.addEventListener('submit', function(e) {
                                    const action = formValidasi.querySelector('input[name="action"]:checked')?.value;
                                    const notes  = formValidasi.querySelector('textarea[name="validation_notes"]').value.trim();
                                    if (action === 'reject' && !notes) {
                                        e.preventDefault();
                                        alert('Catatan penolakan wajib diisi jika dokumen ditolak.');
                                        formValidasi.querySelector('textarea[name="validation_notes"]').focus();
                                    }
                                });
                            });
                            </script>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tahap 4: Selesaikan transaksi --}}
            @if($isPengelola && $status === 'diterima_pembeli')
                <div class="st-section st-section-indigo">
                    <div class="st-eyebrow">⚡ Aksi Terakhir</div>
                    <h2>Pembeli Telah Menerima Koleksi</h2>
                    <p>Pembeli telah mengkonfirmasi penerimaan koleksi pada <strong>{{ $pembelian->received_at?->format('d M Y H:i') }}</strong>. Selesaikan transaksi untuk menandai koleksi sebagai terjual.</p>
                    <div class="st-action-row">
                        <form action="{{ route('pengelola.pembelian.serah-terima.complete', $pembelian) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Selesaikan transaksi? Koleksi akan ditandai sebagai TERJUAL.')" class="st-btn st-btn-indigo">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Selesaikan Transaksi
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            {{-- Transaksi Selesai (Pengelola) --}}
            @if($isPengelola && ($status === 'selesai' || $status === 'selesai_dengan_kompensasi'))
                <div class="st-section st-section-green">
                    <div class="st-eyebrow">🎉 Transaksi Selesai</div>
                    <h2>Pembelian Telah Diselesaikan</h2>
                    <p>Koleksi resmi menjadi milik pembeli per {{ $pembelian->completed_at?->format('d M Y H:i') }}.</p>

                    {{-- Info validasi dokumen serah terima --}}
                    @if($pembelian->handover_validated_at)
                    <div style="margin-top:1rem;background:#fff;border:1.5px solid #bbf7d0;border-radius:1rem;padding:1rem 1.1rem;">
                        <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#166534;margin-bottom:.35rem;">✅ Dokumen Serah Terima</div>
                        <div style="font-size:.84rem;color:#334155;line-height:1.65;">
                            Dokumen serah terima telah divalidasi oleh pengelola pada
                            <strong>{{ $pembelian->handover_validated_at->format('d M Y, H:i') }}</strong>.                        </div>
                        @if($pembelian->handover_validation_notes)
                        <div style="margin-top:.5rem;font-size:.78rem;color:#475569;">
                            Catatan: {{ $pembelian->handover_validation_notes }}
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Sertifikat Keaslian (sudah ada sebelumnya) --}}
                    <div style="margin-top:1.25rem;background:#fff;border:1.5px solid #bbf7d0;border-radius:1.25rem;padding:1.25rem;">
                        <div style="font-size:.67rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#166534;margin-bottom:.5rem;">📜 Sertifikat Keaslian</div>
                        <p style="font-size:.84rem;color:#475569;margin-bottom:1rem;">Sertifikat keaslian telah diterbitkan sebagai bukti autentik kepemilikan koleksi.</p>

                        <iframe
                            src="{{ route('pengelola.pembelian.serah-terima.certificate.preview', $pembelian) }}"
                            style="width:100%;height:500px;border:1.5px solid #bbf7d0;border-radius:.75rem;margin-bottom:.75rem;display:block;"
                            title="Preview Sertifikat Keaslian">
                        </iframe>

                        <div class="st-action-row">
                            <a href="{{ route('pengelola.pembelian.serah-terima.certificate.preview', $pembelian) }}" target="_blank" class="st-btn st-btn-emerald">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                Buka Tab Baru
                            </a>
                            <a href="{{ route('pengelola.pembelian.serah-terima.certificate.download', $pembelian) }}" class="st-btn st-btn-emerald">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                Unduh Sertifikat
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ════════════════════════════════════
                AKSI PENGGUNA
            ════════════════════════════════════ --}}

            {{-- Menunggu pengiriman --}}
            @if(!$isPengelola && in_array($status, ['pembayaran_berhasil', 'siap_diserahkan']))
                <div class="st-section st-section-slate">
                    <div class="st-eyebrow">⏳ Menunggu Pengelola</div>
                    <h2>Koleksi Sedang Disiapkan</h2>
                    <p>Pembayaran Anda telah diterima. Pengelola sedang mempersiapkan koleksi untuk dikirimkan. Anda akan mendapat notifikasi saat koleksi dikirim.</p>
                </div>
            @endif

            {{-- Dalam pengiriman --}}
            @if(!$isPengelola && $status === 'dalam_pengiriman')
                @php $mds = $pembelian->manager_delivery_status ?? null; @endphp

                @if($pembelian->shipping_method_type === 'manager')
                    {{-- Card atas sesuai sub-status manager --}}
                    @if($mds === 'tiba_di_tujuan')
                        <div class="st-section st-section-emerald">
                            <div class="st-eyebrow">🏁 Koleksi Telah Tiba</div>
                            <h2>Koleksi Sudah di Lokasi Anda</h2>
                            <p>Pengelola mencatat koleksi sudah tiba di tujuan. Silakan periksa kondisi koleksi dan konfirmasi penerimaan.</p>
                            <div class="st-meta-grid">
                                <div class="st-meta-cell">
                                    <div class="lbl">Metode Pengiriman</div>
                                    <div class="val">{{ $pembelian->delivery_method ?? '-' }}</div>
                                </div>
                                <div class="st-meta-cell">
                                    <div class="lbl">Petugas Pengirim</div>
                                    <div class="val">{{ $pembelian->delivery_officer ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="st-action-row" style="margin-top:1.25rem;">
                                <form action="{{ route('pembelian.confirm-received', $pembelian) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Konfirmasi bahwa koleksi sudah Anda terima? Setelah konfirmasi, Anda akan diarahkan ke pengecekan kondisi koleksi.')"
                                        class="st-btn st-btn-emerald">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Konfirmasi Penerimaan Koleksi
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($mds === 'dalam_perjalanan')
                        <div class="st-section st-section-sky">
                            <div class="st-eyebrow">🚚 Koleksi Dalam Perjalanan</div>
                            <h2>Koleksi Sedang Dikirimkan</h2>
                            <p>Koleksi sedang dalam perjalanan menuju alamat Anda.</p>
                            <div class="st-meta-grid">
                                <div class="st-meta-cell">
                                    <div class="lbl">Metode Pengiriman</div>
                                    <div class="val">{{ $pembelian->delivery_method ?? '-' }}</div>
                                </div>
                                <div class="st-meta-cell">
                                    <div class="lbl">Estimasi Tiba</div>
                                    <div class="val">{{ $pembelian->delivery_scheduled_at ? \Carbon\Carbon::parse($pembelian->delivery_scheduled_at)->format('d M Y') : '-' }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- dikemas / siap_dikirim / null --}}
                        <div class="st-section st-section-slate">
                            <div class="st-eyebrow">📦 Sedang Dipersiapkan</div>
                            <h2>Koleksi Sedang Dikemas</h2>
                            <p>Pengelola sedang mempersiapkan koleksi untuk dikirimkan. Pantau status pengiriman di bawah.</p>
                        </div>
                    @endif

                    {{-- Timeline pengiriman pengelola --}}
                    @if(!empty($pembelian->manager_delivery_timeline))
                        <div class="st-card">
                            <div class="st-card-header">
                                <div class="st-card-header-accent"></div>
                                <h3>Status Pengiriman</h3>
                            </div>
                            <div class="st-card-body">
                                <div class="st-timeline">
                                    @foreach(array_reverse($pembelian->manager_delivery_timeline) as $entry)
                                        <div class="st-timeline-item">
                                            <div class="st-timeline-dot" style="background:
                                                @switch($entry['status'])
                                                    @case('dikemas') #f59e0b @break
                                                    @case('siap_dikirim') #3b82f6 @break
                                                    @case('dalam_perjalanan') #8b5cf6 @break
                                                    @case('tiba_di_tujuan') #10b981 @break
                                                    @default #94a3b8
                                                @endswitch
                                            "></div>
                                            <div class="st-timeline-body">
                                                <div class="tlabel">{{ $entry['label'] }}</div>
                                                @if($entry['catatan'])<div class="tnote">{{ $entry['catatan'] }}</div>@endif
                                                <div class="tmeta">{{ \Carbon\Carbon::parse($entry['timestamp'])->format('d M Y, H:i') }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                @else
                    {{-- Kurir --}}
                    <div class="st-section st-section-sky">
                        <div class="st-eyebrow">🚚 Dalam Pengiriman</div>
                        <h2>Koleksi Sedang Dikirim via Kurir</h2>
                        <p>Koleksi Anda sedang dalam perjalanan. Pantau status pengiriman melalui lacak pengiriman di bawah.</p>
                        <div class="st-meta-grid">
                            <div class="st-meta-cell">
                                <div class="lbl">Kurir</div>
                                <div class="val">{{ $pembelian->delivery_method ?? '-' }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">No. Resi</div>
                                <div class="val" style="font-family:monospace;">{{ $pembelian->delivery_tracking_number ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tracking kurir --}}
                    <div class="st-card" id="tracking-card">
                        <div class="st-card-header">
                            <div class="st-card-header-accent"></div>
                            <h3>Lacak Pengiriman</h3>
                            <div style="margin-left:auto;">
                                <button onclick="loadTracking(true)" id="btn-refresh"
                                    style="display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .85rem;border:1.5px solid var(--border);border-radius:.6rem;background:var(--white);font-size:.73rem;font-weight:600;color:var(--slate);cursor:pointer;"
                                    onmouseover="this.style.borderColor='var(--blue)';this.style.color='var(--blue)'"
                                    onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--slate)'">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="12" height="12"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                    Refresh
                                </button>
                            </div>
                        </div>
                        <div class="st-card-body" id="tracking-body">
                            <div id="tracking-loading" style="display:flex;flex-direction:column;gap:.75rem;">
                                @for($i = 0; $i < 3; $i++)
                                <div style="height:48px;background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%);background-size:200% 100%;border-radius:.75rem;animation:shimmer 1.5s infinite;"></div>
                                @endfor
                            </div>
                            <div id="tracking-error" style="display:none;" class="st-catatan">
                                <div class="lbl" style="color:#dc2626;">Gagal Memuat</div>
                                <div class="val" id="tracking-error-msg"></div>
                            </div>
                            <div id="tracking-content" style="display:none;">
                                <div id="tracking-summary" style="margin-bottom:1.25rem;"></div>
                                <div id="tracking-history"></div>
                            </div>
                        </div>
                    </div>
                    <style>@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}</style>
                    <script>
                    const TRACKING_URL="{{route('pembelian.serah-terima.tracking-data',$pembelian)}}";
                    async function loadTracking(r=false){const l=document.getElementById('tracking-loading'),e=document.getElementById('tracking-error'),c=document.getElementById('tracking-content'),b=document.getElementById('btn-refresh');l.style.display='flex';e.style.display='none';c.style.display='none';if(b)b.disabled=true;try{const res=await fetch(TRACKING_URL+(r?'?refresh=1':''),{headers:{'X-Requested-With':'XMLHttpRequest'}});if(!res.ok)throw new Error();const d=await res.json();l.style.display='none';if(!d.success||!d.data){document.getElementById('tracking-error-msg').textContent=d.message||'Data tidak tersedia.';e.style.display='';return;}renderTracking(d.data);c.style.display='';}catch{l.style.display='none';document.getElementById('tracking-error-msg').textContent='Gagal memuat.';e.style.display='';}finally{if(b)b.disabled=false;}}
                    function renderTracking(d){const ok=d.delivered;document.getElementById('tracking-summary').innerHTML=`<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:.75rem"><div class="st-meta-cell"><div class="lbl">Kurir</div><div class="val">${esc(d.courier)}</div></div><div class="st-meta-cell"><div class="lbl">No. Resi</div><div class="val" style="font-family:monospace">${esc(d.awb)}</div></div><div class="st-meta-cell"><div class="lbl">Status</div><div class="val"><span style="display:inline-flex;padding:.35rem 1rem;border-radius:99px;font-size:.75rem;font-weight:700;background:${ok?'#d1fae5':'#eff6ff'};border:1.5px solid ${ok?'#6ee7b7':'#bfdbfe'};color:${ok?'#065f46':'#1d4ed8'}">${esc(d.status)}</span></div></div></div>`;const h=document.getElementById('tracking-history');if(!d.history||!d.history.length){h.innerHTML='<div class="st-catatan"><div class="lbl">Riwayat</div><div class="val">Belum ada update dari kurir.</div></div>';return;}let html='';d.history.forEach((item,i)=>{html+=`<div style="display:flex;gap:.875rem;padding:.75rem 0;border-bottom:1px solid #f0f4f8"><div style="width:8px;height:8px;border-radius:50%;background:${i===0?'#1d4ed8':'#38bdf8'};margin-top:.35rem;flex-shrink:0"></div><div><div style="font-size:.83rem;font-weight:500;color:#0b1d35">${esc(item.description)}</div><div style="font-size:.72rem;color:#94a3b8">${item.city?esc(item.city)+' · ':''}${esc(item.datetime)}</div></div></div>`;});h.innerHTML=html;}
                    function esc(s){if(!s)return'';const d=document.createElement('div');d.textContent=String(s);return d.innerHTML;}
                    document.addEventListener('DOMContentLoaded',()=>loadTracking(false));
                    </script>

                    {{-- Tombol konfirmasi untuk kurir --}}
                    <div class="st-section st-section-blue">
                        <div class="st-eyebrow">⚡ Aksi Diperlukan</div>
                        <h2>Konfirmasi Penerimaan Koleksi</h2>
                        <p>Setelah koleksi tiba, tekan tombol konfirmasi. Form pengecekan kondisi akan langsung muncul di halaman ini.</p>                        <div class="st-action-row">
                            <form action="{{ route('pembelian.confirm-received', $pembelian) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Konfirmasi bahwa koleksi sudah Anda terima?')" class="st-btn st-btn-blue">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Koleksi Sudah Saya Terima
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif

            {{-- ══════════════════════════════════════════════════════════
                PENGECEKAN KONDISI (inline di halaman serah terima)
            ══════════════════════════════════════════════════════════ --}}
            @if(!$isPengelola && $status === 'pengecekan_kondisi')

                {{-- Panduan --}}
                <div style="background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:1.25rem;padding:1.25rem;">
                    <div style="font-size:.67rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#0369a1;margin-bottom:.5rem;">📋 Panduan Penilaian Kerusakan</div>
                    <p style="font-size:.82rem;color:#0369a1;margin:0 0 .35rem;"><strong>Ringan:</strong> goresan halus, noda kecil, retak minor pada bingkai yang tidak mempengaruhi nilai utama koleksi.</p>
                    <p style="font-size:.82rem;color:#0369a1;margin:0;"><strong>Parah:</strong> sobekan kanvas, pecah kaca pelindung, retak signifikan, deformasi fisik berat. Transaksi diarahkan ke pembatalan dengan refund penuh (dikurangi ongkir).</p>
                </div>

                {{-- Pilihan kondisi --}}
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent"></div>
                        <h3>Kondisi Koleksi Saat Diterima</h3>
                    </div>
                    <div class="st-card-body">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                            {{-- Panel Kondisi Baik --}}
                            <div style="background:#f0fdf4;border:2px solid #bbf7d0;border-radius:1rem;padding:1.25rem;">
                                <h3 style="font-family:'Playfair Display',serif;font-size:1.1rem;margin:0 0 .35rem;color:var(--navy);">✅ Kondisi Baik</h3>
                                <p style="font-size:.8rem;color:#475569;margin:0 0 1rem;line-height:1.6;">Tidak ada kerusakan. Lanjut ke proses unduh dan upload dokumen serah terima.</p>
                                <button type="button" onclick="showConditionForm('good')"
                                    style="display:inline-flex;align-items:center;gap:.4rem;padding:.65rem 1.25rem;border-radius:.75rem;font-size:.82rem;font-weight:600;background:#059669;color:#fff;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;">
                                    Konfirmasi Kondisi Baik
                                </button>
                            </div>
                            {{-- Panel Ada Kerusakan --}}
                            <div style="background:#fef2f2;border:2px solid #fecaca;border-radius:1rem;padding:1.25rem;">
                                <h3 style="font-family:'Playfair Display',serif;font-size:1.1rem;margin:0 0 .35rem;color:var(--navy);">⚠️ Ada Kerusakan</h3>
                                <p style="font-size:.8rem;color:#475569;margin:0 0 1rem;line-height:1.6;">Laporkan kerusakan beserta bukti lengkap dalam satu kali pengisian.</p>
                                <button type="button" onclick="showConditionForm('damage')"
                                    style="display:inline-flex;align-items:center;gap:.4rem;padding:.65rem 1.25rem;border-radius:.75rem;font-size:.82rem;font-weight:600;background:#dc2626;color:#fff;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;">
                                    Laporkan Kerusakan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form: Konfirmasi Kondisi Baik --}}
                <div id="cek-form-good" style="display:none;">
                    <div class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-header-accent"></div>
                            <h3>Konfirmasi Kondisi Baik</h3>
                        </div>
                        <div class="st-card-body">
                            <p style="font-size:.84rem;color:#475569;margin:0 0 1.25rem;line-height:1.7;">
                                Anda menyatakan koleksi diterima dalam kondisi baik. Unggah foto sebagai dokumentasi penerimaan, lalu lanjutkan ke dokumen serah terima.
                            </p>

                            @if($errors->any() && !old('arrival_damage_severity'))
                                <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:.875rem;padding:.875rem 1rem;margin-bottom:1rem;">
                                    <ul style="margin:0;padding-left:1.25rem;font-size:.81rem;color:#991b1b;">
                                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('pembelian.condition-good', $pembelian) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- Foto Depan --}}
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        1. Foto Depan Koleksi <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Foto tampak depan koleksi saat diterima sebagai dokumentasi kondisi.</p>
                                    <input type="file" name="condition_front_photo"
                                        accept="image/jpg,image/jpeg,image/png" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- Foto Belakang --}}
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        2. Foto Belakang Koleksi <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Foto tampak belakang koleksi saat diterima.</p>
                                    <input type="file" name="condition_back_photo"
                                        accept="image/jpg,image/jpeg,image/png" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- Video (opsional) --}}
                                <div style="margin-bottom:1.5rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        3. Video Kondisi Koleksi <span style="font-weight:400;color:#94a3b8;">(opsional)</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Rekam video singkat kondisi koleksi jika diperlukan. Maks 50MB (MP4/MOV/AVI).</p>
                                    <input type="file" name="condition_video"
                                        accept="video/mp4,video/quicktime,video/avi"
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- Info box --}}
                                <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:.875rem;padding:.875rem 1rem;margin-bottom:1.25rem;display:flex;gap:.65rem;align-items:flex-start;">
                                    <span style="font-size:1rem;flex-shrink:0;">ℹ️</span>
                                    <p style="font-size:.78rem;color:#166534;margin:0;line-height:1.6;">
                                        Foto ini akan disimpan sebagai dokumentasi resmi penerimaan koleksi. Pastikan foto jelas dan menunjukkan kondisi koleksi secara keseluruhan.
                                    </p>
                                </div>

                                <div style="display:flex;gap:.65rem;flex-wrap:wrap;">
                                    <button type="submit"
                                        onclick="return confirm('Konfirmasi koleksi dalam kondisi baik dan lanjut ke dokumen serah terima?')"
                                        class="st-btn st-btn-emerald">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Lanjut ke Dokumen Serah Terima
                                    </button>
                                    <button type="button" onclick="hideConditionForm()" class="st-btn st-btn-ghost">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Form: Laporan Kerusakan --}}
                <div id="cek-form-damage" style="display:none;">
                    <div class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-header-accent"></div>
                            <h3>Form Laporan Kerusakan</h3>
                        </div>
                        <div class="st-card-body">
                            @if($errors->any())
                                <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:.875rem;padding:.875rem 1rem;margin-bottom:1rem;">
                                    <ul style="margin:0;padding-left:1.25rem;font-size:.81rem;color:#991b1b;">
                                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('pembelian.condition-damage', $pembelian) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- ── 1. Foto Depan ── --}}
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        1. Foto Depan Koleksi <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Foto tampak depan koleksi saat diterima.</p>
                                    <input type="file" name="condition_front_photo"
                                        accept="image/jpg,image/jpeg,image/png" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- ── 2. Foto Belakang ── --}}
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        2. Foto Belakang Koleksi <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Foto tampak belakang koleksi saat diterima.</p>
                                    <input type="file" name="condition_back_photo"
                                        accept="image/jpg,image/jpeg,image/png" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- ── 3. Video Kerusakan ── --}}
                                <div style="margin-bottom:1.25rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        3. Video Bukti Kerusakan <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Rekam video yang menunjukkan kerusakan secara jelas. Maks 50MB (MP4/MOV/AVI).</p>
                                    <input type="file" name="damage_video"
                                        accept="video/mp4,video/quicktime,video/avi" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- ── 4. Checklist Jenis Kerusakan ── --}}
                                <div style="margin-bottom:1.25rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.5rem;">
                                        4. Jenis Kerusakan <span style="color:#ef4444">*</span>
                                    </label>
                                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                                        @foreach($damageChecklistItems as $key => $label)
                                            <div style="display:flex;gap:.5rem;align-items:flex-start;background:#f8fafc;border:1.5px solid var(--border);border-radius:.75rem;padding:.75rem;">
                                                <input type="checkbox"
                                                    name="arrival_damage_checklist[{{ $key }}]"
                                                    value="{{ $key }}" id="chk-{{ $key }}"
                                                    @if($loop->last) data-target="desc-lainnya" onchange="toggleDescById(this)" @endif
                                                    style="margin-top:.15rem;flex-shrink:0;">
                                                <div style="flex:1;">
                                                    <label for="chk-{{ $key }}" style="font-size:.82rem;font-weight:600;color:var(--navy);cursor:pointer;">{{ $label }}</label>
                                                    @if($loop->last)
                                                        <div id="desc-lainnya" style="display:none;margin-top:.4rem;">
                                                            <textarea name="item_descriptions[{{ $key }}]" rows="2"
                                                                    style="width:100%;border:1.5px solid var(--border);border-radius:.55rem;padding:.45rem .65rem;font-size:.78rem;font-family:'DM Sans',sans-serif;resize:vertical;"
                                                                    placeholder="Jelaskan jenis kerusakan lainnya..."></textarea>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- ── 5. Deskripsi umum ── --}}
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        5. Deskripsi Umum Kerusakan
                                    </label>
                                    <textarea name="arrival_damage_description" rows="3"
                                            class="st-form-textarea"
                                            placeholder="Ceritakan kondisi kerusakan secara umum...">{{ old('arrival_damage_description') }}</textarea>
                                </div>

                                {{-- ── 6. Keputusan Anda ── --}}
                                <div style="margin-bottom:1.25rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.25rem;">
                                        6. Keputusan Anda <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.75rem;color:var(--slate);margin:0 0 .75rem;line-height:1.6;">
                                        Pilih keputusan Anda terkait kerusakan yang ditemukan. Keputusan ini akan diverifikasi terlebih dahulu oleh pengelola — jika pengelola menilai kerusakan tidak terbukti, maka pembatalan dapat ditolak.
                                    </p>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                                        <label id="dec-lanjut"
                                            style="border:2px solid var(--border);border-radius:.875rem;padding:1rem;cursor:pointer;transition:all .15s;background:#fff;"
                                            onclick="selectDecision('lanjut')">
                                            <input type="radio" name="buyer_decision" value="lanjut" style="display:none;">
                                            <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.5rem;">
                                                <div id="dec-lanjut-dot" style="width:16px;height:16px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .15s;"></div>
                                                <span style="font-size:.88rem;font-weight:700;color:#059669;">✅ Ajukan Kompensasi</span>
                                            </div>
                                            <div style="font-size:.74rem;color:var(--slate);line-height:1.6;padding-left:1.6rem;">
                                                Saya menerima koleksi dan mengajukan kompensasi atas kerusakan yang terjadi.<br>                                            </div>
                                        </label>
                                        <label id="dec-batalkan"
                                            style="border:2px solid var(--border);border-radius:.875rem;padding:1rem;cursor:pointer;transition:all .15s;background:#fff;"
                                            onclick="selectDecision('batalkan')">
                                            <input type="radio" name="buyer_decision" value="batalkan" style="display:none;">
                                            <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.5rem;">
                                                <div id="dec-batalkan-dot" style="width:16px;height:16px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .15s;"></div>
                                                <span style="font-size:.88rem;font-weight:700;color:#dc2626;">❌ Ajukan Pembatalan</span>
                                            </div>
                                            <div style="font-size:.74rem;color:var(--slate);line-height:1.6;padding-left:1.6rem;">
                                                Saya mengajukan pembatalan transaksi dan pengembalian dana.<br>                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- ── 8. Foto Packing ── --}}
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        8. Foto Kondisi Packing <span style="color:#ef4444">*</span>
                                    </label>
                                    <input type="file" name="packing_condition_photos[]" multiple
                                        accept="image/*" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- ── 9. Bukti Kurir (jika kurir) ── --}}
                                @if($isKurir)
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        9. Bukti Penerimaan dari Kurir <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Foto tanda terima atau kondisi paket saat diterima dari kurir.</p>
                                    <input type="file" name="courier_receipt_photos[]" multiple
                                        accept="image/*" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>
                                @endif

                                <div style="display:flex;gap:.65rem;margin-top:1.5rem;flex-wrap:wrap;">
                                    <button type="submit"
                                        onclick="return validateDamageForm()"
                                        style="display:inline-flex;align-items:center;gap:.4rem;padding:.65rem 1.35rem;border-radius:.875rem;font-size:.82rem;font-weight:600;background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;">
                                        Kirim Laporan Kerusakan
                                    </button>
                                    <button type="button" onclick="hideConditionForm()" class="st-btn st-btn-ghost">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                function showConditionForm(type) {
                    document.getElementById('cek-form-good').style.display   = 'none';
                    document.getElementById('cek-form-damage').style.display = 'none';
                    const el = document.getElementById('cek-form-' + type);
                    if (el) { el.style.display = ''; el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
                }
                function hideConditionForm() {
                    document.getElementById('cek-form-good').style.display   = 'none';
                    document.getElementById('cek-form-damage').style.display = 'none';
                }
                function handleChecklistChange(el) {
                    if (el.dataset.hasDesc !== 'true') return;
                    const descId = 'desc-' + el.value;
                    const desc = document.getElementById(descId);
                    if (desc) desc.style.display = el.checked ? 'block' : 'none';
                }
                function toggleDescById(el) {
                    const targetId = el.getAttribute('data-target');
                    const desc = document.getElementById(targetId);
                    if (desc) desc.style.display = el.checked ? 'block' : 'none';
                }

                // Pilih severity → tampilkan opsi keputusan yang sesuai
                function selectSev(v) {
                    ['ringan','parah'].forEach(s => {
                        const el = document.getElementById('sev-' + s);
                        if (!el) return;
                        if (s === v) {
                            el.style.border    = '2px solid ' + (s === 'ringan' ? '#f59e0b' : '#ef4444');
                            el.style.background = s === 'ringan' ? '#fffbeb' : '#fef2f2';
                        } else {
                            el.style.border    = '2px solid var(--border)';
                            el.style.background = 'transparent';
                        }
                        const inp = el.querySelector('input');
                        if (inp) inp.checked = (s === v);
                    });

                    const decisionBox   = document.getElementById('decision-box');
                    const decRingan     = document.getElementById('decision-ringan');
                    const decParah      = document.getElementById('decision-parah');
                    const parahInput    = document.getElementById('decision-parah-input');

                    decisionBox.style.display = '';

                    if (v === 'ringan') {
                        decRingan.style.display = 'grid';
                        decParah.style.display  = 'none';
                        if (parahInput) parahInput.disabled = true;
                    } else {
                        decRingan.style.display = 'none';
                        decParah.style.display  = '';
                        if (parahInput) parahInput.disabled = false;
                        // Reset pilihan ringan jika ada
                        ['lanjut','batalkan'].forEach(d => {
                            const el = document.getElementById('dec-' + d);
                            if (el) { el.style.border = '2px solid var(--border)'; el.style.background = 'transparent'; }
                            const inp = el?.querySelector('input');
                            if (inp) inp.checked = false;
                        });
                    }
                }

                // Pilih keputusan (hanya untuk ringan)
                function selectDecision(v) {
                    ['lanjut','batalkan'].forEach(d => {
                        const el  = document.getElementById('dec-' + d);
                        const dot = document.getElementById('dec-' + d + '-dot');
                        if (!el) return;
                        if (d === v) {
                            el.style.border     = '2px solid ' + (d === 'lanjut' ? '#059669' : '#ef4444');
                            el.style.background = d === 'lanjut' ? '#f0fdf4' : '#fef2f2';
                            if (dot) {
                                dot.style.border     = '5px solid ' + (d === 'lanjut' ? '#059669' : '#ef4444');
                                dot.style.background = d === 'lanjut' ? '#d1fae5' : '#fee2e2';
                            }
                        } else {
                            el.style.border     = '2px solid var(--border)';
                            el.style.background = '#fff';
                            if (dot) {
                                dot.style.border     = '2px solid #d1d5db';
                                dot.style.background = 'transparent';
                            }
                        }
                        const inp = el.querySelector('input');
                        if (inp) inp.checked = (d === v);
                    });
                }

                // Validasi sebelum submit
                function validateDamageForm() {
                    const decision = document.querySelector('input[name="buyer_decision"]:checked');
                    if (!decision) { alert('Pilih keputusan Anda terlebih dahulu (Lanjut Beli atau Batalkan).'); return false; }

                    const checklist = document.querySelectorAll('input[name^="arrival_damage_checklist"]:checked');
                    if (checklist.length === 0) { alert('Pilih minimal satu jenis kerusakan.'); return false; }

                    return confirm('Kirim laporan kerusakan? Data yang sudah dikirim tidak dapat diubah.');
                }

                // Auto-buka form damage jika ada error validasi
                @if($errors->any() && old('arrival_damage_severity') !== null)
                    document.addEventListener('DOMContentLoaded', () => {
                        showConditionForm('damage');
                        const oldSev = '{{ old('arrival_damage_severity') }}';
                        if (oldSev) selectSev(oldSev);
                        const oldDec = '{{ old('buyer_decision') }}';
                        if (oldDec && oldSev === 'ringan') selectDecision(oldDec);
                    });
                @endif
                </script>

            @endif

            {{-- Upload dokumen serah terima (Pembeli) --}}
            @if(!$isPengelola && $status === 'menunggu_dokumen_serah_terima')

                @if($pembelian->handover_validated_at)
                    {{-- Sudah ada penolakan dokumen serah terima — sembunyikan card klaim kompensasi --}}
                    @if($pembelian->handover_validation_notes)
                    <div class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-header-accent" style="background:linear-gradient(180deg,#dc2626,#ef4444);"></div>
                            <h3>Dokumen Sebelumnya Ditolak</h3>
                            <span style="margin-left:auto;font-size:.7rem;font-weight:600;color:#94a3b8;">
                                {{ $pembelian->handover_validated_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        <div class="st-card-body">
                            <div style="display:flex;gap:.75rem;align-items:flex-start;background:#fef2f2;border:1.5px solid #fecaca;border-radius:1rem;padding:1rem 1.1rem;">
                                <span style="font-size:1.25rem;flex-shrink:0;">❌</span>
                                <div>
                                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#dc2626;margin-bottom:.35rem;">
                                        Alasan Penolakan dari Pengelola
                                    </div>
                                    <div style="font-size:.84rem;color:#334155;line-height:1.65;">
                                        {{ $pembelian->handover_validation_notes }}
                                    </div>
                                    <div style="font-size:.73rem;color:#94a3b8;margin-top:.5rem;">
                                        Pastikan dokumen sudah ditandatangani dengan benar sebelum mengunggah ulang.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="st-section st-section-orange">
                        <div class="st-eyebrow">❌ Dokumen Ditolak</div>
                        <h2>Dokumen Sebelumnya Ditolak</h2>
                        <p>Dokumen serah terima yang Anda kirimkan sebelumnya tidak disetujui oleh pengelola. Silakan unggah ulang dokumen yang sudah ditandatangani dengan benar.</p>
                    </div>
                    @endif

                @elseif($isDamageCompensation && $pembelian->refund_confirmed_at)
                    {{-- Kompensasi sudah dikonfirmasi, belum pernah ada penolakan dokumen --}}
                    <div class="st-section st-section-emerald">
                        <div class="st-eyebrow">✅ Kompensasi Berhasil</div>
                        <h2>Lanjut ke Dokumen Serah Terima</h2>
                        <p>Kompensasi telah dikonfirmasi diterima pada <strong>{{ $pembelian->refund_confirmed_at->format('d M Y H:i') }}</strong>. Silakan unduh dokumen serah terima, tandatangani, lalu upload kembali.</p>
                        @if($pembelian->refund_amount)
                        <div class="st-meta-grid" style="margin-top:.75rem;">
                            <div class="st-meta-cell" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#bbf7d0;">
                                <div class="lbl">Nominal Kompensasi</div>
                                <div class="val" style="color:#059669;">Rp {{ number_format($pembelian->refund_amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        @endif
                    </div>

                @else
                    {{-- Kondisi baik atau kompensasi ditolak, belum pernah ada penolakan dokumen --}}
                    @if(in_array($pembelian->arrival_damage_manager_decision, ['tolak_kompensasi', 'tolak_pembatalan']) && $pembelian->arrival_damage_manager_notes)
                    <div class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-header-accent" style="background:linear-gradient(180deg,#dc2626,#ef4444);"></div>
                            <h3>{{ $pembelian->arrival_damage_manager_decision === 'tolak_kompensasi' ? 'Klaim Kompensasi Ditolak' : 'Klaim Pembatalan Ditolak' }}</h3>
                        </div>
                        <div class="st-card-body">
                            <div style="display:flex;gap:.75rem;align-items:flex-start;background:#fef2f2;border:1.5px solid #fecaca;border-radius:1rem;padding:1rem 1.1rem;">
                                <span style="font-size:1.25rem;flex-shrink:0;">❌</span>
                                <div>
                                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#dc2626;margin-bottom:.35rem;">Keputusan Pengelola</div>
                                    <div style="font-size:.84rem;color:#334155;line-height:1.65;">{{ $pembelian->arrival_damage_manager_notes }}</div>
                                    <div style="font-size:.73rem;color:#94a3b8;margin-top:.5rem;">Silakan lanjutkan proses serah terima dokumen seperti transaksi normal.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endif

                {{-- Form unduh & upload dokumen — selalu tampil di semua kondisi --}}
                <div class="st-section st-section-amber">
                    <div class="st-eyebrow">📄 Langkah Serah Terima</div>
                    <h2>Unduh &amp; Upload Dokumen Serah Terima</h2>
                    <p>Silakan unduh dokumen serah terima, tanda tangani, lalu upload kembali sebagai bukti penerimaan.</p>
                    <div class="st-action-row">
                        <a href="{{ route('pembelian.serah-terima.download', $pembelian) }}" class="st-btn st-btn-amber">Unduh Dokumen Serah Terima</a>
                        @if($pembelian->handover_signed_document_path)
                            <a href="{{ asset('storage/' . $pembelian->handover_signed_document_path) }}" target="_blank" class="st-btn st-btn-sky">Lihat Dokumen Ditandatangani</a>
                        @endif
                    </div>

                    <form action="{{ route('pembelian.serah-terima.upload', $pembelian) }}" method="POST" enctype="multipart/form-data" style="margin-top:1.25rem;">
                        @csrf
                        <div class="st-form-group">
                            <label class="st-form-label">Dokumen Serah Terima</label>
                            <input type="file" name="signed_handover_document" accept=".pdf,.doc,.docx" required class="st-form-input" style="padding:.5rem .75rem; width:100%; display:block;">
                        </div>
                        <div style="display:flex;justify-content:flex-end;">
                            <button type="submit" class="st-btn st-btn-amber">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                Upload Dokumen
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Menunggu validasi (Pembeli) --}}
            @if(!$isPengelola && $status === 'menunggu_validasi_serah_terima')
                <div class="st-section st-section-amber">
                    <div class="st-eyebrow">⏳ Menunggu Validasi</div>
                    <h2>Dokumen Serah Terima Telah Diunggah</h2>
                    <p>Dokumen serah terima Anda sedang diperiksa oleh pengelola. Anda akan diberitahu jika disetujui atau diminta upload ulang.</p>
                    <div class="st-action-row">
                        @if($pembelian->handover_document_path)
                            <a href="{{ route('pembelian.serah-terima.download', $pembelian) }}" class="st-btn st-btn-sky">Unduh Dokumen Serah Terima</a>
                        @endif
                        @if($pembelian->handover_signed_document_path)
                            <a href="{{ asset('storage/' . $pembelian->handover_signed_document_path) }}" target="_blank" class="st-btn st-btn-amber">Lihat Dokumen Ditandatangani</a>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Menunggu penyelesaian pengelola (Pembeli) --}}
            @if(!$isPengelola && $status === 'diterima_pembeli')
                <div class="st-section st-section-indigo">
                    <div class="st-eyebrow">🏁 Hampir Selesai</div>
                    <h2>Penerimaan Dikonfirmasi</h2>
                    <p>Terima kasih telah mengkonfirmasi penerimaan koleksi. Pengelola sedang memproses penyelesaian transaksi.</p>
                </div>
            @endif

            {{-- Selesai (Pengguna) --}}
            @if(!$isPengelola && ($status === 'selesai' || $status === 'selesai_dengan_kompensasi'))
                <div class="st-section st-section-green">
                    <div class="st-eyebrow">🎉 Transaksi Selesai</div>
                    <h2>Selamat! Koleksi Resmi Milik Anda</h2>
                    <p>Transaksi pembelian telah selesai per {{ $pembelian->completed_at?->format('d M Y H:i') }}. Koleksi <strong>{{ $pembelian->painting->title }}</strong> kini resmi menjadi milik Anda.</p>

                    {{-- Info validasi dokumen --}}
                    @if($pembelian->handover_validated_at)
                    <div style="margin-top:1rem;background:#fff;border:1.5px solid #bbf7d0;border-radius:1rem;padding:1rem 1.1rem;">
                        <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#166534;margin-bottom:.35rem;">✅ Dokumen Serah Terima</div>
                        <div style="font-size:.84rem;color:#334155;line-height:1.65;">
                            Dokumen serah terima Anda telah diverifikasi dan disetujui oleh pengelola pada
                            <strong>{{ $pembelian->handover_validated_at->format('d M Y, H:i') }}</strong>.                        </div>
                    </div>
                    @endif
                </div>

                {{-- Sertifikat --}}
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#059669,#10b981);"></div>
                        <h3>Sertifikat Keaslian</h3>
                    </div>
                    <div class="st-card-body">
                        <p style="font-size:.84rem;color:#475569;margin:0 0 1rem;line-height:1.7;">
                            Sertifikat keaslian telah diterbitkan oleh museum sebagai bukti autentik koleksi milik Anda.
                        </p>
                        <iframe
                            src="{{ route('pembelian.serah-terima.certificate.preview', $pembelian) }}"
                            style="width:100%;height:500px;border:1.5px solid #bbf7d0;border-radius:.75rem;margin-bottom:.875rem;display:block;"
                            title="Preview Sertifikat Keaslian">
                        </iframe>
                        <div class="st-action-row">
                            <a href="{{ route('pembelian.serah-terima.certificate.preview', $pembelian) }}" target="_blank" class="st-btn st-btn-emerald">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                Buka Tab Baru
                            </a>
                            <a href="{{ route('pembelian.serah-terima.certificate.download', $pembelian) }}" class="st-btn st-btn-emerald">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                Unduh Sertifikat
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- LIGHTBOX (global, selalu ada) --}}
        <div class="st-lightbox-overlay" id="st-lightbox" onclick="closeLightbox(event)">
            <span class="st-lightbox-close" onclick="closeLightbox({target:this})">&times;</span>
            <img id="st-lightbox-img" src="" alt="" onclick="event.stopPropagation()">
        </div>

    </div>{{-- akhir .st-root --}}

    <script>
    function openLightbox(src, alt) {
        const lb = document.getElementById('st-lightbox');
        document.getElementById('st-lightbox-img').src = src;
        document.getElementById('st-lightbox-img').alt = alt || '';
        lb.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox(e) {
        const lb  = document.getElementById('st-lightbox');
        const img = document.getElementById('st-lightbox-img');
        if (e.target === lb || e.target === lb.querySelector('.st-lightbox-close')) {
            lb.classList.remove('active');
            document.body.style.overflow = '';
            img.src = '';
        }
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.getElementById('st-lightbox').classList.remove('active');
            document.getElementById('st-lightbox-img').src = '';
            document.body.style.overflow = '';
        }
    });
    </script>
    </div>
</x-app-layout>