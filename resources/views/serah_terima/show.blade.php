<x-app-layout>
    <x-slot name="header">{{-- kosong, hero di dalam --}}</x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    @php $isPengelola = auth()->user()->role === 'pengelola'; @endphp

    @php
        $handoverStatusLabels = [
            'waiting_handover'          => 'Persiapan Pengiriman',
            'preparing_delivery'        => 'Siap Dikirim',
            'in_delivery'               => 'Sedang Dikirim',
            'delivered'                 => 'Sudah Diterima Penyewa',
            'handover_completed'        => 'Serah Terima Selesai',
            'return_shipment_submitted' => 'Info Pengiriman Balik Terkirim',
            'collection_arrived'        => 'Koleksi Tiba di Museum',
            'waiting_refund_proof'      => 'Menunggu Bukti Refund',
            'waiting_damage_payment'    => 'Menunggu Pembayaran Kerusakan',
            'waiting_return_signature'  => 'Menunggu TTD Penyewa',
            'return_document_uploaded'  => 'Dokumen TTD Terunggah',
            'returned'                  => 'Dikembalikan',
            'condition_checking'      => 'Pengecekan Kondisi',
            'damage_reported'         => 'Laporan Kerusakan Masuk',
            'damage_reviewed'         => 'Kerusakan Sudah Ditinjau',
            'cancelled_due_to_damage' => 'Dibatalkan — Kerusakan Pengiriman',
        ];

        $hs            = $serahTerima->handover_status;
        $handoverLabel = $handoverStatusLabels[$hs] ?? ucfirst(str_replace('_', ' ', $hs));

        $statusBadgeClass = match($hs) {
            'waiting_handover'          => 'st-amber',
            'preparing_delivery'        => 'st-blue',
            'in_delivery'               => 'st-sky',
            'delivered'                 => 'st-indigo',
            'handover_completed'        => 'st-emerald',
            'return_shipment_submitted' => 'st-orange',
            'collection_arrived'        => 'st-teal',
            'waiting_refund_proof'      => 'st-amber',
            'waiting_damage_payment'    => 'st-red',
            'waiting_return_signature'  => 'st-amber',
            'return_document_uploaded'  => 'st-teal',
            'returned'                  => 'st-slate',
            default                     => 'st-slate',
            'condition_checking'      => 'st-amber',
            'damage_reported'         => 'st-red',
            'damage_reviewed'         => 'st-indigo',
            'cancelled_due_to_damage' => 'st-red',
        };

        $stepOrder  = ['waiting_handover', 'preparing_delivery', 'in_delivery', 'delivered', 'handover_completed'];
        $currentIdx = array_search($serahTerima->handover_status, $stepOrder);
        if ($currentIdx === false) $currentIdx = count($stepOrder);

        $steps = [0 => 'Persiapan', 1 => 'Siap Kirim', 2 => 'Dikirim', 3 => 'Diterima', 4 => 'Aktif'];

        $sisaHari = $penyewaan->end_date
            ? (int) now()->startOfDay()->diffInDays($penyewaan->end_date->startOfDay(), false)
            : null;

        $st          = $serahTerima;
        $status      = $penyewaan->status;
        $isKurir     = $penyewaan->shipping_method_type === 'courier';
        $isManager   = $penyewaan->shipping_method_type === 'manager';
        $isCancellationReturn = $st->isArrivalDamageCancellation()
            && $st->handover_status !== 'returned'
            && in_array($penyewaan->status, ['dibatalkan', 'menunggu_konfirmasi_refund'], true);
        $returnPhaseStatuses = [
            'return_shipment_submitted', 'collection_arrived', 'waiting_refund_proof',
            'waiting_damage_payment', 'waiting_return_signature', 'return_document_uploaded', 'returned',
        ];
        $isReturnPhase = in_array($hs, $returnPhaseStatuses, true);
    @endphp

    {{-- ════ STYLE (identik dengan pembelian, ditambah style baru) ════ --}}
    <style>
        :root {
            --navy: #0b1d35; --navy-2: #142744; --blue: #1d4ed8;
            --sky: #38bdf8; --cream: #f2f5f9; --slate: #64748b;
            --border: #e2e8f0; --white: #ffffff;
        }
        * { box-sizing: border-box; }
        .st-root { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; padding-bottom: 4rem; }

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

        .st-status-badge { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem 1rem; border-radius: 99px; font-size: .72rem; font-weight: 700; letter-spacing: .04em; margin-top: .75rem; }
        .st-status-dot { width: 6px; height: 6px; border-radius: 50%; }
        .st-amber   { background: rgba(251,191,36,.15);  border: 1px solid rgba(251,191,36,.3);  color: #fbbf24; }
        .st-amber   .st-status-dot { background: #fbbf24; }
        .st-emerald { background: rgba(52,211,153,.15);  border: 1px solid rgba(52,211,153,.3);  color: #34d399; }
        .st-emerald .st-status-dot { background: #34d399; }
        .st-blue    { background: rgba(96,165,250,.15);  border: 1px solid rgba(96,165,250,.3);  color: #60a5fa; }
        .st-blue    .st-status-dot { background: #60a5fa; }
        .st-sky     { background: rgba(56,189,248,.15);  border: 1px solid rgba(56,189,248,.3);  color: var(--sky); }
        .st-sky     .st-status-dot { background: var(--sky); }
        .st-indigo  { background: rgba(129,140,248,.15); border: 1px solid rgba(129,140,248,.3); color: #818cf8; }
        .st-indigo  .st-status-dot { background: #818cf8; }
        .st-teal    { background: rgba(45,212,191,.15);  border: 1px solid rgba(45,212,191,.3);  color: #2dd4bf; }
        .st-teal    .st-status-dot { background: #2dd4bf; }
        .st-red     { background: rgba(248,113,113,.15); border: 1px solid rgba(248,113,113,.3); color: #f87171; }
        .st-red     .st-status-dot { background: #f87171; }
        .st-orange  { background: rgba(251,146,60,.15);  border: 1px solid rgba(251,146,60,.3);  color: #fb923c; }
        .st-orange  .st-status-dot { background: #fb923c; }
        .st-green   { background: rgba(74,222,128,.15);  border: 1px solid rgba(74,222,128,.3);  color: #4ade80; }
        .st-green   .st-status-dot { background: #4ade80; }
        .st-slate   { background: rgba(148,163,184,.1);  border: 1px solid rgba(148,163,184,.2); color: #94a3b8; }
        .st-slate   .st-status-dot { background: #94a3b8; }

        .st-content { max-width: 1100px; margin: 0 auto; padding: 1.75rem 2rem 0; display: grid; gap: 1.25rem; }

        .st-flash { border-radius: .875rem; padding: .85rem 1.2rem; font-size: .83rem; font-weight: 600; display: flex; align-items: center; gap: .55rem; animation: flashIn .35s ease; }
        @keyframes flashIn { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:none} }
        .st-flash svg { width: 16px; height: 16px; flex-shrink: 0; }
        .st-flash.ok   { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .st-flash.err  { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
        .st-flash.info { background: #dbeafe; border: 1px solid #93c5fd; color: #1e40af; }

        .st-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.5rem; box-shadow: 0 4px 24px rgba(11,29,53,.05); overflow: hidden; }
        .st-card-header { padding: 1.1rem 1.5rem; border-bottom: 1.5px solid #f0f4f8; display: flex; align-items: center; gap: .55rem; }
        .st-card-header-accent { width: 3px; height: 16px; background: linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius: 99px; flex-shrink: 0; }
        .st-card-header h3 { font-size: .76rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--navy); margin: 0; }
        .st-card-body { padding: 1.5rem; }

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
        .st-section-orange  { background: #fff7ed; border: 1.5px solid #fed7aa; }
        .st-section-orange  .st-eyebrow { color: #c2410c; }
        .st-section-teal    { background: #f0fdfa; border: 1.5px solid #99f6e4; }
        .st-section-teal    .st-eyebrow { color: #0f766e; }
        .st-section-red     { background: #fef2f2; border: 1.5px solid #fecaca; }
        .st-section-red     .st-eyebrow { color: #dc2626; }
        .st-section-green   { background: #f0fdf4; border: 1.5px solid #bbf7d0; }
        .st-section-green   .st-eyebrow { color: #166534; }
        .st-section-slate   { background: #f8fafc; border: 1.5px solid #e2e8f0; }
        .st-section-slate   .st-eyebrow { color: #64748b; }

        .st-meta-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(160px,1fr)); gap: .875rem; margin-top: 1.1rem; }
        .st-meta-cell { background: var(--white); border: 1.5px solid var(--border); border-radius: 1rem; padding: .9rem 1rem; }
        .st-meta-cell .lbl { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .3rem; }
        .st-meta-cell .val { font-size: .9rem; font-weight: 700; color: var(--navy); }

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

        .st-radio-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; margin-bottom: .875rem; }
        .st-radio-label { display: flex; align-items: center; gap: .6rem; background: var(--white); border: 1.5px solid var(--border); border-radius: .875rem; padding: .75rem .9rem; cursor: pointer; font-size: .82rem; font-weight: 500; transition: border-color .15s, background .15s; }
        .st-radio-label:has(input:checked) { border-color: #10b981; background: #f0fdf4; }

        .st-check-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }
        .st-check-item { display: flex; align-items: center; gap: .6rem; background: var(--white); border: 1.5px solid var(--border); border-radius: .875rem; padding: .75rem .9rem; font-size: .82rem; color: #334155; }
        .st-check-item.pass { border-color: #bbf7d0; background: #f0fdf4; }
        .st-check-item.fail { border-color: #fecaca; background: #fef2f2; }
        .st-check-icon-pass { color: #059669; font-weight: 700; }
        .st-check-icon-fail { color: #dc2626; font-weight: 700; }

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
        .st-btn-teal    { background: linear-gradient(135deg,#0f766e,#14b8a6); color: #fff; }
        .st-btn-teal:hover    { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(20,184,166,.3); }
        .st-btn-amber   { background: linear-gradient(135deg,#d97706,#f59e0b); color: #fff; }
        .st-btn-amber:hover   { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(217,119,6,.3); }
        .st-btn-orange  { background: linear-gradient(135deg,#c2410c,#ea580c); color: #fff; }
        .st-btn-orange:hover  { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(194,65,12,.3); }
        .st-btn-red     { background: linear-gradient(135deg,#dc2626,#ef4444); color: #fff; }
        .st-btn-red:hover     { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(220,38,38,.3); }
        .st-btn-violet  { background: linear-gradient(135deg,#6d28d9,#7c3aed); color: #fff; }
        .st-btn-violet:hover  { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(109,40,217,.3); }
        .st-btn-ghost   { background: transparent; border: 1.5px solid var(--border); color: var(--slate); }
        .st-btn-ghost:hover   { background: #f8fafc; border-color: #94a3b8; }
        .st-btn-slate   { background: #1e293b; color: #fff; }
        .st-btn-slate:hover   { background: #334155; transform: translateY(-1px); }

        .st-timeline { display: flex; flex-direction: column; gap: .625rem; margin-top: 1rem; }
        .st-timeline-item { display: flex; align-items: flex-start; gap: .75rem; background: var(--white); border: 1.5px solid #ddd6fe; border-radius: 1rem; padding: .875rem 1rem; }
        .st-timeline-dot { width: 8px; height: 8px; border-radius: 50%; background: #7c3aed; margin-top: .35rem; flex-shrink: 0; }
        .st-timeline-body .tlabel { font-size: .83rem; font-weight: 600; color: var(--navy); }
        .st-timeline-body .tnote  { font-size: .74rem; color: var(--slate); margin-top: .15rem; }
        .st-timeline-body .tmeta  { font-size: .71rem; color: #94a3b8; margin-top: .25rem; }

        .st-catatan { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; padding: .875rem 1rem; margin-top: .875rem; }
        .st-catatan .lbl { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .3rem; }
        .st-catatan .val { font-size: .83rem; color: #334155; line-height: 1.65; }

        .st-cost-wrap { background: linear-gradient(135deg,#0b1d35,#142744); border-radius: 1.1rem; padding: 1.25rem; }
        .st-cost-row { display: flex; justify-content: space-between; align-items: center; padding: .45rem 0; border-bottom: 1px solid rgba(255,255,255,.07); font-size: .83rem; }
        .st-cost-row:last-child { border-bottom: none; }
        .st-cost-row .lbl { color: rgba(255,255,255,.55); }
        .st-cost-row .val { font-weight: 600; color: #fff; }
        .st-cost-total { display: flex; justify-content: space-between; align-items: center; padding-top: .75rem; margin-top: .5rem; border-top: 1px solid rgba(255,255,255,.1); }
        .st-cost-total .lbl { font-size: .78rem; color: rgba(255,255,255,.5); font-weight: 600; }
        .st-cost-total .val { font-family: 'Playfair Display',serif; font-size: 1.25rem; color: #fff; }

        .st-info-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(160px,1fr)); gap: .875rem; }
        .st-info-cell .lbl { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .3rem; }
        .st-info-cell .val { font-size: .83rem; font-weight: 600; color: var(--navy); }

        .st-form-error { font-size: .73rem; color: #991b1b; margin-top: .35rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: .5rem; padding: .4rem .6rem; }
        .st-error-box { background: #fef2f2; border: 1.5px solid #fecaca; border-radius: .875rem; padding: 1rem; margin-bottom: 1rem; }
        .st-error-box .ef-title { font-size: .75rem; font-weight: 700; color: #991b1b; margin-bottom: .5rem; }
        .st-error-box ul { margin: 0; padding-left: 1.25rem; font-size: .81rem; color: #991b1b; list-style: disc; }
        .st-error-box li { margin-bottom: .3rem; }
        .st-note-box { background: #f0f9ff; border: 1.5px solid #bae6fd; border-radius: .875rem; padding: 1rem; font-size: .83rem; color: #0369a1; line-height: 1.6; }

        @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

        @media(max-width:768px){
            .st-content { padding: 1.25rem 1rem 0; }
            .st-hero-inner { padding: 0 1rem; }
            .st-check-grid { grid-template-columns: 1fr; }
        }

        .st-validasi-grid { display: grid; grid-template-columns: 1fr 360px; gap: 1.25rem; align-items: start; }
        @media(max-width:900px){ .st-validasi-grid { grid-template-columns: 1fr; } }
    </style>

    <div class="st-root">

        {{-- ── HERO ── --}}
        <div class="st-hero">
            <div class="st-hero-inner">
                <div class="st-hero-top">
                    <div>
                        <div class="st-breadcrumb">
                            @if($isPengelola)
                                <a href="{{ route('pengelola.penyewaan.index') }}">Daftar Penyewaan</a>
                                <span class="st-breadcrumb-sep">/</span>
                                <a href="{{ route('pengelola.penyewaan.show', $penyewaan) }}">SWA-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</a>
                            @else
                                <a href="{{ route('penyewaan.requests.show', $penyewaan) }}">SWA-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</a>
                            @endif
                            <span class="st-breadcrumb-sep">/</span>
                            <span class="st-breadcrumb-cur">Serah Terima</span>
                        </div>
                        <h1 class="st-hero-id">Pengiriman &amp; Serah Terima Koleksi</h1>
                        <p class="st-hero-title">{{ $penyewaan->painting->title }} &mdash; {{ $penyewaan->painting->artist ?? '' }}</p>
                        <div class="st-status-badge {{ $statusBadgeClass }}">
                            <span class="st-status-dot"></span>
                            {{ $handoverLabel }}
                            @if($isReturnPhase && $st->return_shipment_method)
                                &nbsp;·&nbsp; <span style="opacity:.75;">via {{ $st->return_shipment_method }}</span>
                            @elseif(!$isReturnPhase && $isKurir)
                                &nbsp;·&nbsp; <span style="opacity:.75;">via Kurir</span>
                            @elseif(!$isReturnPhase && $isManager)
                                &nbsp;·&nbsp; <span style="opacity:.75;">via Pengelola</span>
                            @endif
                        </div>
                    </div>
                    <div class="st-hero-actions">
                        <a href="{{ $isPengelola
                                ? route('pengelola.penyewaan.show', $penyewaan)
                                : route('penyewaan.requests.show', $penyewaan) }}"
                           class="st-hero-btn st-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Detail
                        </a>
                        <a href="{{ $isPengelola
                                ? route('pengelola.penyewaan.handover.track', $penyewaan)
                                : route('penyewaan.requests.handover.track', $penyewaan) }}"
                           class="st-hero-btn st-hero-btn-track">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            Lihat Timeline
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="st-content">
            {{-- FLASH --}}
            @if(session('success'))
                <div class="st-flash ok">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="st-flash err">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="st-flash info">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    {{ session('info') }}
                </div>
            @endif

            {{-- Pengecekan kondisi & alur kerusakan (sama dengan pembelian) --}}
            @include('serah_terima.partials.condition-check-flow')

            {{-- ════════════════════════════════════════════════════════════════
                 AKSI PENGELOLA
            ════════════════════════════════════════════════════════════════ --}}

            {{-- ── Tahap 13: Isi info pengiriman — PERCABANGAN courier vs manager ── --}}
            @if($isPengelola && $penyewaan->status === 'pengiriman' && $st->handover_status === 'waiting_handover')
                @php
                    $defaultRecipient = $penyewaan->rental_type === 'instansi'
                        ? ($penyewaan->nama_pic ?? $penyewaan->contact_name)
                        : $penyewaan->contact_name;
                    $defaultLocation  = $penyewaan->alamat_lengkap
                        . ', ' . ($penyewaan->kota_lokasi ?? $penyewaan->kota_kabupaten);
                @endphp

                <div class="st-section {{ $isKurir ? 'st-section-blue' : 'st-section-amber' }}">
                    <div class="st-eyebrow">⚡ Aksi Diperlukan</div>

                    @if($isKurir)
                        {{-- Kurir: tampilkan info kurir yang sudah dipilih saat verifikasi --}}
                        <h2>Konfirmasi Pengiriman via Kurir</h2>
                        <p>Metode pengiriman sudah ditentukan saat verifikasi. Isi nomor resi setelah koleksi diserahkan ke kurir — submit form ini berarti koleksi sudah dikirim.</p>

                        <div class="st-meta-grid">
                            <div class="st-meta-cell">
                                <div class="lbl">Kurir Dipilih</div>
                                <div class="val">{{ $penyewaan->courier_name ?? '-' }}
                                    @if($penyewaan->courier_service)
                                        <span style="font-weight:400;font-size:.78rem;color:var(--slate);"> — {{ $penyewaan->courier_service }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">Ongkos Kirim</div>
                                <div class="val">{{ (int)$penyewaan->shipping_cost === 0 ? 'Gratis' : 'Rp ' . number_format($penyewaan->shipping_cost, 0, ',', '.') }}</div>
                            </div>
                            @if($penyewaan->courier_etd)
                                <div class="st-meta-cell">
                                    <div class="lbl">Estimasi Tiba</div>
                                    <div class="val">{{ $penyewaan->courier_etd }} hari kerja</div>
                                </div>
                            @endif
                        </div>
                    @else
                        <h2>Isi Informasi Pengiriman</h2>
                        <p>Pembayaran telah diterima. Siapkan koleksi dan isi data pengiriman ke penyewa menggunakan kendaraan/petugas pengelola.</p>
                    @endif

                    @if($errors->any())
                        <div class="st-errors" style="margin-top:1rem;">
                            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <form action="{{ route('pengelola.penyewaan.handover.delivery-info', $penyewaan) }}"
                        method="POST" enctype="multipart/form-data" style="margin-top:1.25rem;">
                        @csrf
                        <div class="st-form-grid">

                            @if($isKurir)
                                {{-- ── KURIR: field mirip pembelian ── --}}
                                <div class="st-form-group">
                                    <label class="st-form-label">Nama Kurir <span class="req">*</span></label>
                                    <select name="delivery_method" required class="st-form-input">
                                        <option value="">Pilih kurir</option>
                                        @php
                                            $selectedKurir = old('delivery_method', $penyewaan->courier_name ?? '');
                                        @endphp
                                        @foreach(['JNE','J&T Express','SiCepat','ID Express','POS Indonesia','TIKI','AnterAja','Lion Parcel','SAP Express','Ninja Xpress','Wahana'] as $kurir)
                                            <option value="{{ $kurir }}" {{ $selectedKurir === $kurir ? 'selected' : '' }}>{{ $kurir }}</option>
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
                                    <input name="delivery_officer" required value="{{ old('delivery_officer') }}"
                                           class="st-form-input" placeholder="Nama staf yang mengantar ke kurir">
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
                                {{-- ── MANAGER: field lengkap + tanggal kirim ── --}}
                                <div class="st-form-group">
                                    <label class="st-form-label">Metode Pengiriman <span class="req">*</span></label>
                                    <input name="delivery_method" required value="{{ old('delivery_method') }}"
                                           class="st-form-input" placeholder="Contoh: Kendaraan Operasional Museum">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Petugas Pengiriman <span class="req">*</span></label>
                                    <input name="delivery_officer" required value="{{ old('delivery_officer') }}"
                                           class="st-form-input" placeholder="Nama petugas">
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
                                    <input type="datetime-local" name="delivery_scheduled_at"
                                           value="{{ old('delivery_scheduled_at') }}" class="st-form-input">
                                </div>
                            @endif

                        </div>

                        <div class="st-form-group">
                            <label class="st-form-label">Catatan Pengiriman <span class="opt">(opsional)</span></label>
                            <textarea name="delivery_notes" rows="3" class="st-form-textarea"
                                placeholder="{{ $isKurir ? 'Diserahkan ke counter JNE pukul 14.00, dll.' : 'Instruksi packing khusus, catatan kondisi koleksi, dll.' }}">{{ old('delivery_notes') }}</textarea>
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
                                    <p style="font-size:.73rem;color:var(--slate);margin:0 0 .4rem;">Foto tampak depan sebelum dikemas/dikirim.</p>
                                    <input type="file" name="dispatch_front_photo"
                                        accept="image/jpg,image/jpeg,image/png" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Foto Belakang Koleksi <span class="req">*</span></label>
                                    <p style="font-size:.73rem;color:var(--slate);margin:0 0 .4rem;">Foto tampak belakang sebelum dikemas/dikirim.</p>
                                    <input type="file" name="dispatch_back_photo"
                                        accept="image/jpg,image/jpeg,image/png" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Foto Kondisi Packing <span class="req">*</span></label>
                                    <p style="font-size:.73rem;color:var(--slate);margin:0 0 .4rem;">Foto kondisi packing (bisa lebih dari satu).</p>
                                    <input type="file" name="dispatch_packing_photos[]"
                                        accept="image/jpg,image/jpeg,image/png" required multiple
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Video Kondisi Koleksi <span class="opt">(opsional)</span></label>
                                    <p style="font-size:.73rem;color:var(--slate);margin:0 0 .4rem;">Video singkat kondisi koleksi. Maks 50MB (MP4/MOV/AVI).</p>
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
                                <button type="submit" class="st-btn st-btn-amber">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3"/></svg>
                                    Simpan &amp; Lanjutkan
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            @endif

            {{-- ── Tahap 14 MANAGER: Kelola Pengiriman (preparing_delivery + in_delivery) ── --}}
            @if($isPengelola && $isManager
                && in_array($st->handover_status, ['preparing_delivery', 'in_delivery'])
                && in_array($penyewaan->status, ['pengiriman', 'dalam_pengiriman', 'siap_diserahkan']))

                <div class="st-section st-section-violet">
                    <div class="st-eyebrow">🚚 Kelola Pengiriman</div>
                    <h2>Update Status Pengiriman</h2>
                    <p>Perbarui status pengiriman koleksi secara berurutan agar penyewa bisa memantau progress.</p>
                </div>

                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#6d28d9,#7c3aed);"></div>
                        <h3>Status Pengiriman</h3>
                    </div>
                    <div class="st-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

                        @php
                            $managerStatuses = \App\Models\SerahTerima::managerDeliveryStatuses();
                            $currentMds      = $st->manager_delivery_status;
                            $mdsKeys         = array_keys($managerStatuses);
                            $currentMdsIdx   = $currentMds ? array_search($currentMds, $mdsKeys) : -1;
                        @endphp

                        {{-- Riwayat timeline --}}
                        @if(!empty($st->manager_delivery_timeline))
                            <div class="st-timeline">
                                <div style="margin-bottom:.5rem;font-size:.7rem;font-weight:600;color:#64748b;">📋 RIWAYAT STATUS</div>
                                @foreach(array_reverse($st->manager_delivery_timeline) as $entry)
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
                                <div class="val">Mulai dari status "📦 Dikemas" untuk memulai proses pengiriman.</div>
                            </div>
                        @endif

                        {{-- Tombol konfirmasi kirim (preparing_delivery → in_delivery) --}}
                        @if($st->handover_status === 'preparing_delivery')
                            <form action="{{ route('pengelola.penyewaan.handover.mark-shipped', $penyewaan) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Konfirmasi koleksi sudah dikirim ke penyewa?')"
                                    class="st-btn st-btn-blue">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                    Mulai Update Status Pengiriman
                                </button>
                            </form>
                        @endif

                        {{-- Form update sub-status (in_delivery & belum tiba) --}}
                        @if($st->handover_status === 'in_delivery' && $currentMds !== 'tiba_di_tujuan')
                            <form action="{{ route('pengelola.penyewaan.handover.manager-status', $penyewaan) }}" method="POST">
                                @csrf
                                <div class="st-form-group">
                                    <label class="st-form-label">Update ke status berikutnya:</label>
                                    <div class="st-radio-grid" style="grid-template-columns:repeat(2,1fr);">
                                        @foreach($managerStatuses as $value => $label)
                                            @php
                                                $sIdx       = array_search($value, $mdsKeys);
                                                $isDisabled = $sIdx <= $currentMdsIdx;
                                                $isNext     = $sIdx === $currentMdsIdx + 1;
                                            @endphp
                                            <label class="st-radio-label"
                                                style="{{ $isNext ? 'border-color:#f59e0b;background:#fffbeb;' : ($isDisabled ? 'opacity:.45;' : '') }}">
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
                                        placeholder="Contoh: Koleksi sedang dalam perjalanan / Sudah tiba di depan pintu">
                                </div>
                                <div style="display:flex;justify-content:flex-end;">
                                    <button type="submit" class="st-btn st-btn-violet">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                        Update Status
                                    </button>
                                </div>
                            </form>
                        @elseif($st->handover_status === 'in_delivery' && $currentMds === 'tiba_di_tujuan')
                            <div class="st-catatan" style="background:#d1fae5;border-color:#6ee7b7;">
                                <div class="lbl" style="color:#065f46;">✅ Pengiriman Selesai</div>
                                <div class="val">Koleksi telah tiba di tujuan. Menunggu konfirmasi penerimaan dari penyewa.</div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif

            {{-- ── Tahap 14 KURIR: Pengelola — info + tracking + konfirmasi (GABUNGAN) ── --}}
            @if($isPengelola && $isKurir && $st->handover_status === 'in_delivery')
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#38bdf8,#0284c7);"></div>
                        <h3>🚚 Dalam Pengiriman via Kurir</h3>
                    </div>
                    <div class="st-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

                        {{-- Informasi pengiriman --}}
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:.75rem;">
                            <div class="st-meta-cell"><div class="lbl">Kurir</div><div class="val">{{ $st->delivery_method ?? '-' }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">No. Resi</div><div class="val" style="font-family:monospace;">{{ $st->delivery_tracking_number ?? '-' }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">Dikirim Pada</div><div class="val">{{ $st->shipped_at?->format('d M Y H:i') ?? '-' }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">Penerima</div><div class="val">{{ $st->recipient_name ?? '-' }}</div></div>
                        </div>

                        {{-- Tracking --}}
                        @include('penyewaan.partials.tracking-card', [
                            'trackingUrl' => route('pengelola.penyewaan.handover.tracking-data', $penyewaan),
                        ])

                    </div>
                </div>
            @endif

            {{-- ── Pengelola: Manager tiba_di_tujuan — GABUNGAN dengan konfirmasi ── --}}
            @if($isPengelola && $isManager
                && $st->handover_status === 'in_delivery'
                && ($currentMds ?? null) === 'tiba_di_tujuan')
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#10b981,#059669);"></div>
                        <h3>🏁 Koleksi Tiba di Tujuan</h3>
                    </div>
                    <div class="st-card-body" style="display:flex;flex-direction:column;gap:1rem;">
                        <div style="padding:.875rem 1rem;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:.875rem;">
                            <div style="font-size:.8rem;font-weight:600;color:#065f46;">✅ Pengiriman Selesai — Menunggu Konfirmasi Penyewa</div>
                            <div style="font-size:.78rem;color:#4b7a5a;margin-top:.25rem;">
                                Koleksi telah tiba di tujuan. Penyewa akan mengkonfirmasi penerimaan dan melakukan pengecekan kondisi.
                            </div>
                        </div>
                        <div class="st-meta-grid">
                            <div class="st-meta-cell"><div class="lbl">Metode</div><div class="val">{{ $st->delivery_method ?? '-' }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">Petugas</div><div class="val">{{ $st->delivery_officer ?? '-' }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">Status</div><div class="val" style="color:#059669;">Tiba di Tujuan</div></div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Pengelola: Menunggu penyewa upload dokumen ST ── --}}
            @if($isPengelola && $penyewaan->status === 'menunggu_dokumen_serah_terima')
            @php $stDitolak = $st->serah_terima_status === 'rejected'; @endphp
            <div class="st-section {{ $stDitolak ? 'st-section-red' : 'st-section-indigo' }}">
                @if($stDitolak)
                    <div class="st-eyebrow">🔄 Menunggu Upload Ulang</div>
                    <h2>Menunggu Penyewa Upload Ulang Dokumen Serah Terima</h2>
                    <p>Dokumen yang diunggah penyewa telah ditolak. Penyewa sedang diminta memperbaiki dan mengunggah kembali dokumen serah terima.</p>
                    @if($st->validation_notes)
                    <div class="st-catatan" style="background:var(--white);border-color:#fecaca;margin-top:1rem;">
                        <div class="lbl" style="color:#dc2626;">Catatan Penolakan yang Dikirim ke Penyewa</div>
                        <div class="val">{{ $st->validation_notes }}</div>
                    </div>
                    @endif
                @else
                    <div class="st-eyebrow">⏳ Menunggu Penyewa</div>
                    <h2>Menunggu Dokumen Serah Terima</h2>
                    <p>Penyewa telah mengkonfirmasi penerimaan koleksi. Menunggu penyewa mengunduh, mengisi checklist, menandatangani, dan mengunggah kembali dokumen serah terima.</p>
                @endif
                <div class="st-meta-grid">
                    <div class="st-meta-cell">
                        <div class="lbl">Penerimaan Dikonfirmasi</div>
                        <div class="val">{{ $st->confirmed_received_at?->format('d M Y H:i') ?? '-' }}</div>
                    </div>
                    <div class="st-meta-cell">
                        <div class="lbl">Penerima</div>
                        <div class="val">{{ $st->recipient_name ?? '-' }}</div>
                    </div>
                    <div class="st-meta-cell">
                        <div class="lbl">Status Dokumen ST</div>
                        <div class="val" style="color:{{ $stDitolak ? '#dc2626' : '#d97706' }};">
                            {{ $stDitolak ? 'Ditolak — Menunggu Upload Ulang' : 'Belum Diunggah' }}
                        </div>
                    </div>
                </div>
                @if($st->handover_document_path)
                <div class="st-action-row">
                    <a href="{{ route('pengelola.penyewaan.handover.download', $penyewaan) }}" class="st-btn st-btn-navy">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        Lihat Dokumen ST yang Dikirim ke Penyewa
                    </a>
                </div>
                @endif
            </div>
            @endif

            {{-- ── Pengelola: Melihat status pengecekan kondisi penyewa (legacy) ── --}}
            @if($isPengelola && $st->handover_status === 'condition_checking' && $penyewaan->status === 'pengiriman')
                <div class="st-section st-section-amber">
                    <div class="st-eyebrow">⏳ Menunggu Penyewa</div>
                    <h2>Penyewa Sedang Memeriksa Kondisi Koleksi</h2>
                    <p>
                        Penyewa telah mengkonfirmasi penerimaan koleksi pada
                        <strong>{{ $st->confirmed_received_at?->format('d M Y, H:i') ?? '-' }}</strong>.
                        Mereka sedang melakukan pengecekan kondisi koleksi.
                    </p>
                    <div class="st-meta-grid">
                        <div class="st-meta-cell">
                            <div class="lbl">Dikonfirmasi Terima</div>
                            <div class="val">{{ $st->confirmed_received_at?->format('d M Y H:i') ?? '-' }}</div>
                        </div>
                        <div class="st-meta-cell">
                            <div class="lbl">Metode Pengiriman</div>
                            <div class="val">{{ $isKurir ? 'Kurir — ' . ($st->delivery_method ?? '-') : 'Pengelola' }}</div>
                        </div>
                        <div class="st-meta-cell">
                            <div class="lbl">Status Cek Kondisi</div>
                            <div class="val" style="color:#d97706;">Sedang Dilakukan...</div>
                        </div>
                    </div>
                </div>
            @endif
            
            {{-- ── Pengelola: Ada laporan kerusakan masuk — perlu diputuskan (legacy) ── --}}
            @if($isPengelola && $st->handover_status === 'damage_reported' && $st->arrival_damage_manager_decision === null && $penyewaan->status === 'pengiriman')
            
                @php
                    $checkedDamageItems = collect($st->arrival_damage_checklist ?? [])
                        ->filter(fn($item) => !empty($item['checked']));
                    $severityLabel = match($st->arrival_damage_severity) {
                        'ringan' => '🟡 Ringan',
                        'parah'  => '🔴 Parah',
                        default  => '-',
                    };
                    $decisionLabel = match($st->arrival_damage_tenant_decision) {
                        'lanjutkan' => '✅ Ingin melanjutkan sewa',
                        'batalkan'  => '❌ Ingin membatalkan sewa',
                        default     => '-',
                    };
                @endphp
            
                <div class="st-section st-section-red">
                    <div class="st-eyebrow">⚡ Perlu Keputusan Pengelola</div>
                    <h2>Laporan Kerusakan Saat Penerimaan</h2>
                    <p>
                        Penyewa melaporkan kerusakan pada koleksi saat diterima pada
                        <strong>{{ $st->arrival_damage_reported_at?->format('d M Y, H:i') ?? '-' }}</strong>.
                        Tinjau laporan dan tentukan keputusan.
                    </p>
                </div>
            
                {{-- Detail laporan kerusakan --}}
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#dc2626,#ef4444);"></div>
                        <h3>Detail Laporan Kerusakan dari Penyewa</h3>
                    </div>
                    <div class="st-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">
            
                        {{-- Ringkasan singkat --}}
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:.75rem;">
                            <div class="st-meta-cell">
                                <div class="lbl">Tingkat Keparahan</div>
                                <div class="val">{{ $severityLabel }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">Keputusan Penyewa</div>
                                <div class="val" style="font-size:.82rem;">{{ $decisionLabel }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">Dilaporkan Pada</div>
                                <div class="val">{{ $st->arrival_damage_reported_at?->format('d M Y H:i') ?? '-' }}</div>
                            </div>
                        </div>
            
                        {{-- Checklist kerusakan --}}
                        @if($checkedDamageItems->count() > 0)
                        <div>
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">
                                Jenis Kerusakan yang Dilaporkan
                            </div>
                            <div style="display:flex;gap:.45rem;flex-wrap:wrap;">
                                @foreach($checkedDamageItems as $item)
                                    <span style="display:inline-flex;align-items:center;gap:.3rem;
                                                background:#fef2f2;border:1.5px solid #fecaca;
                                                border-radius:.6rem;padding:.35rem .85rem;
                                                font-size:.78rem;font-weight:600;color:#991b1b;">
                                        ⚠ {{ $item['label'] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
            
                        {{-- Deskripsi penyewa --}}
                        @if($st->arrival_damage_description)
                        <div class="st-catatan">
                            <div class="lbl">Deskripsi Kerusakan dari Penyewa</div>
                            <div class="val">{{ $st->arrival_damage_description }}</div>
                        </div>
                        @endif
            
                        {{-- Foto kondisi depan & belakang --}}
                        @if($st->arrival_condition_front_photo || $st->arrival_condition_back_photo)
                        <div>
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.75rem;">
                                Foto Kondisi Koleksi (Depan & Belakang)
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                                @if($st->arrival_condition_front_photo)
                                <div>
                                    <div style="font-size:.72rem;color:#64748b;margin-bottom:.35rem;font-weight:600;">Tampak Depan</div>
                                    <img src="{{ asset('storage/' . $st->arrival_condition_front_photo) }}"
                                        alt="Foto Depan"
                                        style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);
                                                object-fit:cover;max-height:220px;cursor:pointer;"
                                        onclick="openPhotoModal(this.src)">
                                </div>
                                @endif
                                @if($st->arrival_condition_back_photo)
                                <div>
                                    <div style="font-size:.72rem;color:#64748b;margin-bottom:.35rem;font-weight:600;">Tampak Belakang</div>
                                    <img src="{{ asset('storage/' . $st->arrival_condition_back_photo) }}"
                                        alt="Foto Belakang"
                                        style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);
                                                object-fit:cover;max-height:220px;cursor:pointer;"
                                        onclick="openPhotoModal(this.src)">
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
            
                        {{-- Foto/video bukti kerusakan --}}
                        @if(!empty($st->arrival_damage_photos))
                        <div>
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.75rem;">
                                Foto / Video Bukti Kerusakan ({{ count($st->arrival_damage_photos) }} file)
                            </div>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:.6rem;">
                                @foreach($st->arrival_damage_photos as $i => $photoPath)
                                    @php $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION)); @endphp
                                    @if(in_array($ext, ['jpg','jpeg','png']))
                                        <div style="position:relative;">
                                            <img src="{{ asset('storage/' . $photoPath) }}"
                                                alt="Bukti Kerusakan {{ $i+1 }}"
                                                style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:.875rem;
                                                        border:1.5px solid var(--border);cursor:pointer;"
                                                onclick="openPhotoModal(this.src)">
                                            <span style="position:absolute;top:.4rem;left:.4rem;background:rgba(0,0,0,.55);
                                                        color:#fff;font-size:.65rem;font-weight:700;padding:.15rem .4rem;
                                                        border-radius:.4rem;">{{ $i+1 }}</span>
                                        </div>
                                    @elseif(in_array($ext, ['mp4','mov']))
                                        <div style="background:#f1f5f9;border:1.5px solid var(--border);border-radius:.875rem;
                                                    display:flex;flex-direction:column;align-items:center;justify-content:center;
                                                    aspect-ratio:1;gap:.4rem;">
                                            <span style="font-size:1.5rem;">🎥</span>
                                            <a href="{{ asset('storage/' . $photoPath) }}" target="_blank"
                                            style="font-size:.72rem;color:var(--blue);font-weight:600;text-decoration:none;">
                                                Video {{ $i+1 }}
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
            
                    </div>
                </div>
            
                {{-- Form keputusan pengelola --}}
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#6d28d9,#7c3aed);"></div>
                        <h3>Keputusan Pengelola</h3>
                    </div>
                    <div class="st-card-body">
            
                        @if($st->arrival_damage_tenant_decision === 'batalkan' || $st->arrival_damage_severity === 'parah')
                        <div class="st-note-box" style="margin-bottom:1.25rem;background:#fef2f2;border-color:#fecaca;color:#991b1b;">
                            <strong>⚠ Penyewa ingin membatalkan sewa{{ $st->arrival_damage_severity === 'parah' ? ' (kerusakan parah — wajib dikembalikan)' : '' }}.</strong>
                            Verifikasi laporan kerusakan dan tentukan apakah pembatalan disetujui.
                            Jika disetujui, biaya sewa + deposit akan dikembalikan penuh (ongkir tidak dikembalikan).
                        </div>
                        @endif
            
                        @if($errors->has('decision'))
                            <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:.875rem;padding:.75rem 1rem;margin-bottom:1rem;font-size:.8rem;color:#991b1b;">
                                ⚠ {{ $errors->first('decision') }}
                            </div>
                        @endif
            
                        <form action="{{ route('pengelola.penyewaan.handover.decide-damage', $penyewaan) }}"
                            method="POST">
                            @csrf
            
                            {{-- Pilihan keputusan --}}
                            <div style="margin-bottom:1.1rem;">
                                <label class="st-form-label" style="margin-bottom:.65rem;">Keputusan <span class="req">*</span></label>
                                <div style="display:flex;flex-direction:column;gap:.5rem;">

                                    {{-- setuju_batal --}}
                                    <label style="display:flex;align-items:flex-start;gap:.75rem;
                                                padding:1rem 1.1rem;border:1.5px solid var(--border);
                                                background:var(--white);border-radius:1rem;cursor:pointer;
                                                transition:all .15s;"
                                        id="dec-label-setuju_batal"
                                        onclick="styleDecision('setuju_batal')">
                                        <input type="radio" name="decision" value="setuju_batal"
                                            {{ old('decision', 'setuju_batal') === 'setuju_batal' ? 'checked' : '' }}
                                            style="margin-top:.2rem;flex-shrink:0;accent-color:#dc2626;">
                                        <div>
                                            <div style="font-size:.88rem;font-weight:700;color:#dc2626;">
                                                ✅ Setujui Pembatalan Sewa
                                            </div>
                                            <div style="font-size:.76rem;color:#64748b;margin-top:.2rem;line-height:1.5;">
                                                Kerusakan diverifikasi. Sewa dibatalkan, biaya sewa + deposit dikembalikan penuh
                                                (ongkir tidak dikembalikan). Penyewa wajib mengembalikan koleksi ke museum.
                                            </div>
                                        </div>
                                    </label>

                                    {{-- tolak_lanjut --}}
                                    <label style="display:flex;align-items:flex-start;gap:.75rem;
                                                padding:1rem 1.1rem;border:1.5px solid var(--border);
                                                background:var(--white);border-radius:1rem;cursor:pointer;
                                                transition:all .15s;"
                                        id="dec-label-tolak_lanjut"
                                        onclick="styleDecision('tolak_lanjut')">
                                        <input type="radio" name="decision" value="tolak_lanjut"
                                            {{ old('decision') === 'tolak_lanjut' ? 'checked' : '' }}
                                            style="margin-top:.2rem;flex-shrink:0;accent-color:#d97706;">
                                        <div>
                                            <div style="font-size:.88rem;font-weight:700;color:#d97706;">
                                                ⚠️ Tolak Pembatalan — Sewa Tetap Lanjut
                                            </div>
                                            <div style="font-size:.76rem;color:#64748b;margin-top:.2rem;line-height:1.5;">
                                                Kerusakan tidak memenuhi syarat pembatalan. Sewa tetap dilanjutkan
                                                dan penyewa diminta melanjutkan proses serah terima.
                                            </div>
                                        </div>
                                    </label>

                                </div>
                            </div>
            
                            {{-- Catatan --}}
                            <div class="st-form-group">
                                <label class="st-form-label">
                                    Catatan untuk Penyewa
                                    <span class="opt">(opsional, tapi disarankan jika menolak)</span>
                                </label>
                                <textarea name="notes" rows="3" class="st-form-textarea"
                                        placeholder="Contoh: Kerusakan sudah kami verifikasi dan terjadi saat pengiriman. Mohon maaf atas ketidaknyamanan ini...">{{ old('notes') }}</textarea>
                            </div>
            
                            <div style="display:flex;gap:.65rem;justify-content:flex-end;margin-top:.5rem;">
                                <button type="submit"
                                        onclick="return confirmDecision()"
                                        class="st-btn st-btn-navy">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Simpan Keputusan
                                </button>
                            </div>
            
                        </form>
                    </div>
                </div>
            
                {{-- Modal preview foto --}}
                <div id="photo-modal" onclick="this.style.display='none'"
                    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);z-index:9999;
                            display:none;align-items:center;justify-content:center;cursor:zoom-out;">
                    <img id="photo-modal-img" src="" alt=""
                        style="max-width:90vw;max-height:90vh;border-radius:1rem;object-fit:contain;">
                </div>
            
                <script>
                function openPhotoModal(src) {
                    const modal = document.getElementById('photo-modal');
                    document.getElementById('photo-modal-img').src = src;
                    modal.style.display = 'flex';
                }
            
                function styleDecision(value) {
                    const configs = {
                        'tolak_lanjut':  { border: '#f59e0b', bg: '#fffbeb' },
                        'setuju_batal':  { border: '#ef4444', bg: '#fef2f2' },
                    };
                    ['tolak_lanjut','setuju_batal'].forEach(key => {
                        const el = document.getElementById('dec-label-' + key);
                        if (!el) return;
                        if (key === value) {
                            el.style.borderColor = configs[key].border;
                            el.style.background  = configs[key].bg;
                        } else {
                            el.style.borderColor = 'var(--border)';
                            el.style.background  = 'var(--white)';
                        }
                    });
                }
            
                function confirmDecision() {
                    const selected = document.querySelector('input[name="decision"]:checked');
                    if (!selected) {
                        alert('Pilih salah satu keputusan terlebih dahulu.');
                        return false;
                    }
                    const messages = {
                        'tolak_lanjut':  'Pembatalan DITOLAK. Sewa tetap dilanjutkan.',
                        'setuju_batal':  'Pembatalan DISETUJUI. Biaya sewa + deposit akan dikembalikan. Tindakan ini tidak dapat diurungkan.',
                    };
                    return confirm('Keputusan: ' + messages[selected.value] + '\n\nLanjutkan?');
                }
            
                // Init: style keputusan yang sudah dipilih (saat old input)
                document.addEventListener('DOMContentLoaded', () => {
                    const checked = document.querySelector('input[name="decision"]:checked');
                    if (checked) styleDecision(checked.value);
            
                    // Keyboard: ESC tutup modal
                    document.addEventListener('keydown', e => {
                        if (e.key === 'Escape') {
                            document.getElementById('photo-modal').style.display = 'none';
                        }
                    });
                });
                </script>
            
            @endif
            
            {{-- ── Pengelola: Kerusakan sudah diputuskan — tampilkan ringkasan ── --}}
            @if($isPengelola && $st->handover_status === 'damage_reviewed' && $st->arrival_damage_manager_decision)
                @php
                    $decConfigs = [
                        'setuju_lanjut'      => ['bg'=>'#f0fdf4','border'=>'#bbf7d0','color'=>'#166534','icon'=>'✅','label'=>'Kerusakan Diakui — Sewa Dilanjutkan'],
                        'setujui_kompensasi'  => ['bg'=>'#f0fdf4','border'=>'#bbf7d0','color'=>'#166534','icon'=>'✅','label'=>'Kompensasi Disetujui — Sewa Dilanjutkan'],
                        'tolak_lanjut'        => ['bg'=>'#fffbeb','border'=>'#fde68a','color'=>'#d97706','icon'=>'⚠️','label'=>'Kerusakan Tidak Diakui — Sewa Dilanjutkan'],
                        'tolak_kompensasi'    => ['bg'=>'#fffbeb','border'=>'#fde68a','color'=>'#d97706','icon'=>'⚠️','label'=>'Klaim Kompensasi Ditolak — Sewa Dilanjutkan'],
                        'setuju_batal'        => ['bg'=>'#fef2f2','border'=>'#fecaca','color'=>'#dc2626','icon'=>'❌','label'=>'Sewa Dibatalkan'],
                    ];
                    $dc = $decConfigs[$st->arrival_damage_manager_decision] ?? $decConfigs['tolak_lanjut'];
                @endphp
                <div style="background:{{ $dc['bg'] }};border:1.5px solid {{ $dc['border'] }};border-radius:1.25rem;padding:1.25rem 1.5rem;">
                    <div style="font-size:.67rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:{{ $dc['color'] }};margin-bottom:.4rem;">
                        Keputusan Pengelola atas Kerusakan Penerimaan
                    </div>
                    @if($st->arrival_damage_manager_notes)
                    <div style="font-size:.82rem;color:#475569;margin-top:.5rem;line-height:1.65;">
                        Catatan: {{ $st->arrival_damage_manager_notes }}
                    </div>
                    @endif
                    <div style="font-size:.73rem;color:#94a3b8;margin-top:.5rem;">
                        Diputuskan pada {{ $st->arrival_damage_decided_at?->format('d M Y, H:i') ?? '-' }}
                    </div>
                </div>
            @endif

            {{-- ── Tahap 18: Validasi dokumen ST ── --}}
            @if($isPengelola && $penyewaan->status === 'verifikasi_serah_terima' && $st->serah_terima_status === 'document_uploaded')
                <div class="st-section st-section-violet">
                    <div class="st-eyebrow">⚡ Validasi Diperlukan</div>
                    <h2>Periksa Dokumen Serah Terima</h2>
                    <p>Penyewa telah mengunggah dokumen serah terima beserta checklist kondisi koleksi.</p>

                    <div class="st-doc-preview-actions" style="margin-top:1rem;display:flex;gap:.5rem;flex-wrap:wrap;">
                        @if($st->handover_document_path)
                            <a href="{{ route('pengelola.penyewaan.handover.download', $penyewaan) }}" class="st-btn st-btn-ghost">Unduh Dokumen Awal</a>
                        @endif
                        @if($st->tenant_signed_document_path)
                            <a href="{{ Storage::url($st->tenant_signed_document_path) }}" target="_blank" class="st-btn st-btn-violet">↓ Unduh Ditandatangani</a>
                        @endif
                    </div>

                    <div class="st-validasi-grid" style="display:grid;grid-template-columns:1fr 360px;gap:1.25rem;margin-top:1.25rem;align-items:start;">
                        {{-- Kiri: preview iframe + checklist --}}
                        <div>
                            <div style="background:var(--white);border:1.5px solid var(--border);border-radius:1.25rem;padding:1.1rem;">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:.875rem;flex-wrap:wrap;">
                                    <div>
                                        <div style="font-size:.82rem;font-weight:700;color:var(--navy);">Dokumen Serah Terima</div>
                                        <div style="font-size:.72rem;color:var(--slate);">Diunggah oleh penyewa</div>
                                    </div>
                                    @if($st->tenant_signed_document_path)
                                        <a href="{{ Storage::url($st->tenant_signed_document_path) }}" target="_blank" class="st-btn st-btn-ghost" style="padding:.35rem .75rem;font-size:.72rem;">🔍 Tab Baru</a>
                                    @endif
                                </div>

                                @if($st->tenant_signed_document_path)
                                    <iframe src="{{ Storage::url($st->tenant_signed_document_path) }}"
                                        style="width:100%;height:380px;border:0;border-radius:.75rem;" title="Preview Dokumen Serah Terima"></iframe>
                                    <div class="st-meta-grid" style="margin-top:.875rem;">
                                        <div class="st-meta-cell">
                                            <div class="lbl">Diunggah Pada</div>
                                            <div class="val">{{ $st->tenant_uploaded_at?->format('d M Y, H:i') ?? '-' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:200px;color:#94a3b8;font-size:.83rem;background:#f8fafc;border-radius:.75rem;">
                                        <span style="font-size:2.5rem;margin-bottom:.75rem;">📄</span>
                                        Penyewa belum mengunggah dokumen serah terima yang ditandatangani.
                                    </div>
                                @endif
                            </div>
                        </div>{{-- tutup kolom kiri --}}

                        {{-- Kanan: form validasi --}}
                        <div>
                            <form action="{{ route('pengelola.penyewaan.handover.validate', $penyewaan) }}" method="POST"
                                  style="display:flex;flex-direction:column;gap:.875rem;">
                                @csrf
                                <div class="st-radio-grid">
                                    <label class="st-radio-label"><input type="radio" name="action" value="validate" checked><span>✅ Validasi</span></label>
                                    <label class="st-radio-label"><input type="radio" name="action" value="reject"><span>❌ Tolak</span></label>
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
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Tahap 19: Masa aktif (Pengelola) ── --}}
            @if($isPengelola && $penyewaan->status === 'aktif')
                @php $rentalEnded = $penyewaan->end_date->isPast(); @endphp
                <div class="st-section {{ $rentalEnded ? 'st-section-amber' : 'st-section-emerald' }}">
                    <div class="st-eyebrow">{{ $rentalEnded ? '⚠ Masa Sewa Berakhir' : '✅ Masa Sewa Berjalan' }}</div>
                    <h2>{{ $rentalEnded ? 'Tandai Proses Pengembalian' : 'Penyewaan Aktif' }}</h2>
                    <div class="st-meta-grid">
                        <div class="st-meta-cell"><div class="lbl">Berakhir</div><div class="val">{{ $penyewaan->end_date?->format('d M Y') ?? '-' }}</div></div>
                        <div class="st-meta-cell" style="{{ $sisaHari !== null && $sisaHari <= 3 ? 'border-color:#fecaca;background:#fef2f2;' : '' }}">
                            <div class="lbl">Sisa Hari</div>
                            <div class="val" style="{{ $sisaHari !== null && $sisaHari <= 3 ? 'color:#dc2626;' : 'color:#059669;' }}">
                                {{ $sisaHari !== null ? ($sisaHari > 0 ? $sisaHari . ' hari lagi' : 'Sudah berakhir') : '-' }}
                            </div>
                        </div>
                        <div class="st-meta-cell"><div class="lbl">Mulai Aktif</div><div class="val">{{ $penyewaan->rental_started_at?->format('d M Y') ?? '-' }}</div></div>
                    </div>
                    @if($rentalEnded)
                        <div class="st-action-row">
                            <form method="POST" action="{{ route('pengelola.penyewaan.handover.mark-returning', $penyewaan) }}">
                                @csrf
                                <button type="submit" onclick="return confirm('Tandai penyewaan ini masuk proses pengembalian?')" class="st-btn st-btn-amber">
                                    Mulai Proses Pengembalian →
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif

            {{-- ════ PENGELOLA: TAHAP PENGEMBALIAN (tidak berubah dari versi lama) ════ --}}
            @if($isPengelola && ($isCancellationReturn || in_array($penyewaan->status, [
                'pengembalian', 'menunggu_konfirmasi_refund', 'menunggu_ttd_pengembalian',
                'menunggu_pembayaran_kerusakan', 'menunggu_konfirmasi_selesai',
            ])))
                {{-- Menunggu penyewa kirim info pengiriman balik --}}
                @if(($penyewaan->status === 'pengembalian' || ($isCancellationReturn && $penyewaan->status === 'dibatalkan')) && ! $st->return_shipment_submitted_at)
                    <div class="st-section st-section-orange">
                        <div class="st-eyebrow">⏳ Menunggu Penyewa</div>
                        <h2>{{ $isCancellationReturn ? 'Menunggu Pengembalian Koleksi (Pembatalan)' : 'Menunggu Info Pengiriman Balik' }}</h2>
                        <p>{{ $isCancellationReturn
                            ? 'Sewa dibatalkan karena kerusakan saat pengiriman. Penyewa perlu mengembalikan koleksi ke museum dan mengisi informasi pengiriman balik.'
                            : 'Masa penyewaan berakhir. Penyewa perlu mengirimkan informasi pengiriman balik koleksi ke museum.' }}</p>
                    </div>

                @elseif(($penyewaan->status === 'pengembalian' || ($isCancellationReturn && $penyewaan->status === 'dibatalkan')) && $st->return_shipment_submitted_at && ! $st->collection_arrived_at)
                    {{-- Info pengiriman balik --}}
                    <div class="st-section st-section-amber">
                        <div class="st-eyebrow">⚡ Tracking Pengembalian</div>
                        <h2>Pantau Pengiriman Balik Koleksi</h2>
                        <p>Penyewa telah mengirimkan informasi pengiriman balik. Pantau status pengiriman di bawah.</p>
                        <div class="st-meta-grid">
                            <div class="st-meta-cell"><div class="lbl">Metode</div><div class="val">{{ $st->return_shipment_method ?? '-' }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">Pengirim</div><div class="val">{{ $st->return_shipment_officer ?? '-' }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">No. Resi</div><div class="val" style="font-family:monospace;">{{ $st->return_shipment_tracking ?? '-' }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">Rencana Kirim</div><div class="val">{{ $st->return_shipment_scheduled_at?->format('d M Y H:i') ?? '-' }}</div></div>
                        </div>
                        @if($st->return_shipment_notes)
                            <div class="st-catatan"><div class="lbl">Catatan penyewa</div><div class="val">{{ $st->return_shipment_notes }}</div></div>
                        @endif
                    </div>

                    {{-- Tracking Binderbyte (card mandiri) --}}
                    @if($st->return_shipment_tracking)
                        @include('penyewaan.partials.tracking-card', [
                            'trackingUrl' => route('pengelola.penyewaan.handover.tracking-data', $penyewaan) . '?for=return',
                        ])
                    @else
                        {{-- Timeline manual --}}
                        @php
                            $returnStatuses = \App\Models\SerahTerima::returnShipmentStatuses();
                            $statusKeys = array_keys($returnStatuses);
                            $currentStatus = $st->return_shipment_status;
                            $currentIdx = $currentStatus ? array_search($currentStatus, $statusKeys) : -1;
                        @endphp
                        <div class="st-note-box">
                            <strong>Pengiriman Mandiri</strong> — Penyewa mengirim balik koleksi tanpa kurir. Monitor progres berdasarkan update dari penyewa.
                        </div>
                        @if(!empty($st->return_shipment_timeline))
                            <div class="st-card" style="margin-top:1.25rem;">
                                <div class="st-card-header"><div class="st-card-header-accent"></div><h3>Timeline Pengiriman Balik (Update dari Penyewa)</h3></div>
                                <div class="st-card-body">
                                    <div class="st-timeline">
                                        @foreach(array_reverse($st->return_shipment_timeline) as $entry)
                                            <div class="st-timeline-item">
                                                <div class="st-timeline-dot" style="background:#38bdf8;"></div>
                                                <div class="st-timeline-body">
                                                    <div class="tlabel">{{ $entry['label'] }}</div>
                                                    @if(!empty($entry['catatan']))<div class="tnote">{{ $entry['catatan'] }}</div>@endif
                                                    <div class="tmeta">{{ \Carbon\Carbon::parse($entry['timestamp'])->format('d M Y, H:i') }} • oleh {{ $entry['by'] }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    {{-- Tombol konfirmasi sebagai card mandiri --}}
                    <div class="st-section st-section-teal" style="margin-top:0;">
                        <div class="st-eyebrow">⚡ Aksi Diperlukan</div>
                        <h2>Konfirmasi Koleksi Tiba di Museum</h2>
                        <p>{{ $isCancellationReturn
                            ? 'Setelah koleksi benar-benar sudah diterima kembali di museum, konfirmasi untuk melanjutkan proses refund biaya sewa + deposit.'
                            : 'Setelah koleksi benar-benar sudah diterima di museum, klik tombol konfirmasi di bawah untuk melanjutkan ke pemeriksaan kondisi.' }}</p>
                        <div class="st-action-row">
                            <form method="POST" action="{{ route('pengelola.penyewaan.handover.confirm-collection-arrived', $penyewaan) }}">
                                @csrf
                                <button type="submit" onclick="return confirm('Konfirmasi koleksi sudah tiba di museum?')" class="st-btn st-btn-teal">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    ✓ Konfirmasi Koleksi Tiba di Museum
                                </button>
                            </form>
                        </div>
                    </div>

                @elseif($penyewaan->status === 'pengembalian' && $st->collection_arrived_at && ! $st->return_document_path)
                    <div class="st-section st-section-orange">
                        <div class="st-eyebrow">⚡ Aksi Diperlukan</div>
                        <h2>Periksa Kondisi Koleksi & Proses Pengembalian</h2>
                        <p>Koleksi sudah dikonfirmasi tiba di museum. Lakukan pemeriksaan fisik, tentukan ada tidaknya kerusakan, dan generate dokumen untuk ditandatangani penyewa.</p>

                        <form action="{{ route('pengelola.penyewaan.handover.process-return', $penyewaan) }}"
                            method="POST" enctype="multipart/form-data" style="margin-top:1.25rem;">
                            @csrf

                            @if($errors->any())
                                <div class="st-error-box">
                                    <div class="ef-title">⚠ Error:</div>
                                    <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                            @endif

                            {{-- ── Hasil Pemeriksaan ── --}}
                            <div class="st-card" style="margin-bottom:1.25rem;">
                                <div class="st-card-header">
                                    <div class="st-card-header-accent"></div>
                                    <h3>Hasil Pemeriksaan Kerusakan</h3>
                                </div>
                                <div class="st-card-body" style="display:flex;flex-direction:column;gap:.75rem;">
                                    @error('has_damage')
                                        <div class="st-form-error">{{ $message }}</div>
                                    @enderror

                                    <label style="display:flex;align-items:flex-start;gap:.875rem;padding:1rem 1.1rem;border:1.5px solid #bbf7d0;background:#f0fdf4;border-radius:1rem;cursor:pointer;">
                                        <input type="radio" name="has_damage" value="0" required
                                            {{ old('has_damage') === '0' ? 'checked' : '' }}
                                            onchange="toggleDamageSection(false)"
                                            style="margin-top:.2rem;flex-shrink:0;">
                                        <div>
                                            <div style="font-size:.88rem;font-weight:700;color:#166534;">✓ Tidak Ada Kerusakan</div>
                                            <div style="font-size:.78rem;color:#4b7a5a;margin-top:.2rem;">Deposit akan dikembalikan penuh ke penyewa.</div>
                                        </div>
                                    </label>

                                    <label style="display:flex;align-items:flex-start;gap:.875rem;padding:1rem 1.1rem;border:1.5px solid #fecaca;background:#fef2f2;border-radius:1rem;cursor:pointer;">
                                        <input type="radio" name="has_damage" value="1" required
                                            {{ old('has_damage') === '1' ? 'checked' : '' }}
                                            onchange="toggleDamageSection(true)"
                                            style="margin-top:.2rem;flex-shrink:0;">
                                        <div>
                                            <div style="font-size:.88rem;font-weight:700;color:#991b1b;">✗ Ditemukan Kerusakan</div>
                                            <div style="font-size:.78rem;color:#7f2e2e;margin-top:.2rem;">Pilih jenis kerusakan dan tingkat keparahannya di bawah.</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- ── Detail Kerusakan ── --}}
                            <div id="damage-detail" style="{{ old('has_damage') === '1' ? '' : 'display:none;' }}margin-bottom:1.25rem;">
                                <div class="st-card">
                                    <div class="st-card-header">
                                        <div class="st-card-header-accent"></div>
                                        <h3>Pilih Jenis Kerusakan</h3>
                                    </div>
                                    <div class="st-card-body" style="display:flex;flex-direction:column;gap:.75rem;">

                                        @php
                                            $damageTypes = [
                                                'frame'  => ['label' => 'Frame / Bingkai Rusak',       'base' => 100000],
                                                'tears'  => ['label' => 'Sobekan Kanvas / Lukisan',     'base' => 150000],
                                                'color'  => ['label' => 'Kerusakan Warna / Cat',        'base' => 200000],
                                                'glass'  => ['label' => 'Kaca Pelindung Retak / Pecah', 'base' => 75000],
                                                'mold'   => ['label' => 'Jamur / Kerusakan Biologis',   'base' => 125000],
                                                'other'  => ['label' => 'Kerusakan Lainnya',            'base' => 50000],
                                            ];
                                            $damageMultipliers = ['ringan' => 0.5, 'sedang' => 2, 'berat' => 5];
                                            $oldDamageItems = old('damage_items', []);
                                        @endphp

                                        @foreach($damageTypes as $key => $info)
                                        @php
                                            $checked  = isset($oldDamageItems[$key]);
                                            $oldLevel = $oldDamageItems[$key]['level'] ?? 'ringan';
                                            $oldNote  = $oldDamageItems[$key]['note']  ?? '';
                                        @endphp
                                        <div class="damage-item-wrap" style="border:1.5px solid {{ $checked ? '#c7d2fe' : 'var(--border)' }};border-radius:1rem;overflow:hidden;background:var(--white);transition:border-color .2s;">

                                            {{-- Header checkbox --}}
                                            <label style="display:flex;align-items:center;gap:.75rem;padding:.875rem 1.1rem;cursor:pointer;background:{{ $checked ? '#eef2ff' : 'var(--white)' }};">
                                                <input type="checkbox"
                                                    name="damage_items[{{ $key }}][checked]"
                                                    value="1"
                                                    {{ $checked ? 'checked' : '' }}
                                                    class="damage-checkbox"
                                                    data-key="{{ $key }}"
                                                    data-base="{{ $info['base'] }}"
                                                    style="width:16px;height:16px;cursor:pointer;flex-shrink:0;"
                                                    onchange="onDamageCheck(this)">
                                                <span style="font-size:.88rem;font-weight:600;color:var(--navy);">{{ $info['label'] }}</span>
                                                <span style="margin-left:auto;font-size:.72rem;color:#94a3b8;white-space:nowrap;">
                                                    Base: Rp {{ number_format($info['base'], 0, ',', '.') }}
                                                </span>
                                            </label>

                                            {{-- Detail (muncul saat dicentang) --}}
                                            <div id="dmg-detail-{{ $key }}"
                                                style="{{ $checked ? '' : 'display:none;' }}border-top:1.5px solid #c7d2fe;padding:1rem 1.1rem;background:#f8f9ff;display:flex;flex-direction:column;gap:.75rem;">

                                                {{-- Tingkat keparahan --}}
                                                <div>
                                                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.4rem;">Tingkat Keparahan</div>
                                                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                                                        @foreach($damageMultipliers as $level => $mult)
                                                        <label style="display:flex;align-items:center;gap:.4rem;padding:.5rem .875rem;border:1.5px solid var(--border);border-radius:.75rem;cursor:pointer;font-size:.8rem;font-weight:500;background:var(--white);transition:all .15s;"
                                                            class="level-option-{{ $key }}">
                                                            <input type="radio"
                                                                name="damage_items[{{ $key }}][level]"
                                                                value="{{ $level }}"
                                                                data-key="{{ $key }}"
                                                                data-base="{{ $info['base'] }}"
                                                                data-multiplier="{{ $mult }}"
                                                                {{ $oldLevel === $level ? 'checked' : '' }}
                                                                onchange="recalcDamageCost(this)"
                                                                style="flex-shrink:0;">
                                                            <span>{{ ucfirst($level) }}</span>
                                                            <span style="color:#94a3b8;font-size:.72rem;">— Rp {{ number_format($info['base'] * $mult, 0, ',', '.') }}</span>
                                                        </label>
                                                        @endforeach
                                                    </div>
                                                    <input type="hidden" name="damage_items[{{ $key }}][cost]" id="dmg-cost-{{ $key }}"
                                                        value="{{ $checked ? (int)($info['base'] * ($damageMultipliers[$oldLevel] ?? 0.5)) : 0 }}">
                                                </div>

                                                {{-- Catatan --}}
                                                <div>
                                                    <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.4rem;">Catatan Detail Kerusakan <span style="font-weight:400;">(opsional)</span></div>
                                                    <input type="text"
                                                        name="damage_items[{{ $key }}][note]"
                                                        value="{{ $oldNote }}"
                                                        class="st-form-input"
                                                        placeholder="Deskripsikan kerusakan secara spesifik...">
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach

                                        {{-- Total biaya --}}
                                        <div style="background:linear-gradient(135deg,#0b1d35,#142744);border-radius:1rem;padding:1rem 1.25rem;display:flex;justify-content:space-between;align-items:center;margin-top:.25rem;">
                                            <span style="font-size:.78rem;color:rgba(255,255,255,.6);font-weight:600;">TOTAL ESTIMASI BIAYA KERUSAKAN</span>
                                            <span id="total-damage-cost" style="font-family:'Playfair Display',serif;font-size:1.2rem;color:#fff;font-weight:700;">
                                                Rp 0
                                            </span>
                                        </div>
                                        <input type="hidden" name="damage_cost" id="damage-cost-hidden" value="{{ old('damage_cost', 0) }}">

                                    </div>
                                </div>
                            </div>

                            {{-- ── Foto kondisi ── --}}
                            <div class="st-card" style="margin-bottom:1.25rem;">
                                <div class="st-card-header">
                                    <div class="st-card-header-accent"></div>
                                    <h3>Dokumentasi Foto Kondisi</h3>
                                </div>
                                <div class="st-card-body">
                                    <label class="st-form-label">Foto Kondisi Koleksi <span class="opt">(JPG/PNG/PDF, maks 10MB)</span></label>
                                    <input type="file" name="return_condition_photo" accept=".jpg,.jpeg,.png,.pdf"
                                        class="st-form-input" style="padding:.6rem .75rem;cursor:pointer;">
                                    @error('return_condition_photo')<div class="st-form-error">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- ── Submit ── --}}
                            <div class="st-action-row">
                                <button type="submit"
                                    onclick="return confirm('Simpan hasil pemeriksaan dan generate dokumen pengembalian?')"
                                    class="st-btn st-btn-orange">
                                    💾 Simpan & Generate Dokumen Pengembalian
                                </button>
                            </div>
                        </form>
                    </div>

                    <script>
                    function toggleDamageSection(show) {
                        const el = document.getElementById('damage-detail');
                        el.style.display = show ? '' : 'none';
                        if (!show) {
                            document.getElementById('damage-cost-hidden').value = 0;
                            document.getElementById('total-damage-cost').textContent = 'Rp 0';
                        } else {
                            updateTotal();
                        }
                    }

                    function onDamageCheck(cb) {
                        const key    = cb.dataset.key;
                        const detail = document.getElementById('dmg-detail-' + key);
                        const wrap   = cb.closest('.damage-item-wrap');
                        const header = wrap.querySelector('label');

                        if (cb.checked) {
                            detail.style.display = '';
                            wrap.style.borderColor = '#c7d2fe';
                            header.style.background = '#eef2ff';

                            // Set cost dari level yang terpilih
                            const selectedLevel = detail.querySelector('input[type=radio]:checked');
                            if (selectedLevel) {
                                document.getElementById('dmg-cost-' + key).value =
                                    Math.round(parseFloat(cb.dataset.base) * parseFloat(selectedLevel.dataset.multiplier));
                            }
                        } else {
                            detail.style.display = 'none';
                            wrap.style.borderColor = 'var(--border)';
                            header.style.background = 'var(--white)';
                            document.getElementById('dmg-cost-' + key).value = 0;
                        }
                        updateTotal();
                    }

                    function recalcDamageCost(radio) {
                        const key  = radio.dataset.key;
                        const base = parseFloat(radio.dataset.base);
                        const mult = parseFloat(radio.dataset.multiplier);
                        document.getElementById('dmg-cost-' + key).value = Math.round(base * mult);
                        updateTotal();
                    }

                    function updateTotal() {
                        let total = 0;
                        document.querySelectorAll('.damage-checkbox:checked').forEach(cb => {
                            total += parseInt(document.getElementById('dmg-cost-' + cb.dataset.key)?.value || 0, 10);
                        });
                        document.getElementById('total-damage-cost').textContent =
                            'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                        document.getElementById('damage-cost-hidden').value = total;
                    }

                    document.addEventListener('DOMContentLoaded', function () {
                        // Style radio level yang terpilih
                        document.querySelectorAll('.damage-checkbox:checked').forEach(cb => {
                            updateTotal();
                        });
                        document.querySelectorAll('input[name^="damage_items"][type="radio"]').forEach(r => {
                            styleSelectedLevel(r);
                        });
                    });

                    function styleSelectedLevel(radio) {
                        if (radio.checked) {
                            radio.closest('label').style.borderColor = '#818cf8';
                            radio.closest('label').style.background  = '#eef2ff';
                        }
                        radio.addEventListener('change', function() {
                            // Reset semua sibling
                            const key = this.dataset.key;
                            document.querySelectorAll(`input[name="damage_items[${key}][level]"]`).forEach(r => {
                                r.closest('label').style.borderColor = 'var(--border)';
                                r.closest('label').style.background  = 'var(--white)';
                            });
                            this.closest('label').style.borderColor = '#818cf8';
                            this.closest('label').style.background  = '#eef2ff';
                        });
                    }

                    document.querySelectorAll('input[name^="damage_items"][type="radio"]').forEach(styleSelectedLevel);
                    </script>

                @elseif($penyewaan->status === 'menunggu_konfirmasi_refund' && ! $penyewaan->depositRefund && ! $st->refund_processed_at)
                    @php
                        if ($isCancellationReturn) {
                            $depositAmount = $penyewaan->calculateCancellationRefundAmount();
                            $subtotalAmt   = (int) ($penyewaan->subtotal_amount ?? 0);
                            $depositOnly   = $penyewaan->calculateDeposit();
                            $damageCost    = 0;
                            $sisaRefund    = $depositAmount;
                        } else {
                            $depositAmount = $penyewaan->deposit_amount ?: $penyewaan->calculateDeposit();
                            $damageCost    = (int) ($st->final_damage_cost ?? $st->damage_cost ?? 0);
                            $sisaRefund    = max(0, $depositAmount - $damageCost);
                            $subtotalAmt   = null;
                            $depositOnly   = null;
                        }
                    @endphp
                    <div class="st-section st-section-amber">
                        <div class="st-eyebrow">⚡ Aksi Diperlukan</div>
                        <h2>{{ $isCancellationReturn ? 'Input Bukti Transfer Refund (Sewa + Deposit)' : 'Input Bukti Transfer Refund Deposit' }}</h2>
                        <p>
                            @if($isCancellationReturn)
                                Pembatalan akibat kerusakan saat pengiriman. Transfer Rp {{ number_format($sisaRefund, 0, ',', '.') }}
                                (biaya sewa + deposit) ke rekening penyewa. Ongkir tidak dikembalikan.
                            @elseif($damageCost > 0) Deposit dipotong Rp {{ number_format($damageCost, 0, ',', '.') }} untuk kerusakan. Sisa Rp {{ number_format($sisaRefund, 0, ',', '.') }} perlu ditransfer ke penyewa.
                            @else Tidak ada kerusakan. Deposit Rp {{ number_format($depositAmount, 0, ',', '.') }} dikembalikan penuh.
                            @endif
                        </p>
                        <div style="margin-top:1.25rem;"><div class="st-cost-wrap">
                            @if($isCancellationReturn)
                            <div class="st-cost-row"><span class="lbl">Biaya Sewa</span><span class="val">Rp {{ number_format($subtotalAmt, 0, ',', '.') }}</span></div>
                            <div class="st-cost-row"><span class="lbl">Deposit</span><span class="val">Rp {{ number_format($depositOnly, 0, ',', '.') }}</span></div>
                            <div class="st-cost-row"><span class="lbl">Ongkir</span><span class="val" style="color:#94a3b8;">Tidak dikembalikan</span></div>
                            @else
                            <div class="st-cost-row"><span class="lbl">Total Deposit</span><span class="val">Rp {{ number_format($depositAmount, 0, ',', '.') }}</span></div>
                            <div class="st-cost-row"><span class="lbl">Potongan Kerusakan</span><span class="val" style="{{ $damageCost > 0 ? 'color:#f87171;' : '' }}">Rp {{ number_format($damageCost, 0, ',', '.') }}</span></div>
                            @endif
                            <div class="st-cost-total"><span class="lbl">Yang Dikembalikan</span><span class="val">Rp {{ number_format($sisaRefund, 0, ',', '.') }}</span></div>
                        </div></div>
                        @if($sisaRefund > 0)
                            <div class="st-card" style="margin-top:1.25rem;">
                                <div class="st-card-header"><div class="st-card-header-accent"></div><h3>Form Bukti Transfer Refund</h3></div>
                                <div class="st-card-body">
                                    <form action="{{ route('pengelola.penyewaan.handover.store-refund-proof', $penyewaan) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @if($errors->any())<div class="st-errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                                        <div class="st-form-grid">
                                            <div class="st-form-group"><label class="st-form-label">Nominal Ditransfer (Rp) <span class="req">*</span></label><input type="number" name="refund_amount" required min="0" max="{{ $depositAmount }}" value="{{ old('refund_amount', $sisaRefund) }}" class="st-form-input"></div>
                                            <div class="st-form-group"><label class="st-form-label">Nama Bank <span class="req">*</span></label><input type="text" name="bank_name" required value="{{ old('bank_name', $penyewaan->bank_name) }}" placeholder="BCA, BRI, dll" class="st-form-input"></div>
                                            <div class="st-form-group"><label class="st-form-label">Nomor Rekening <span class="req">*</span></label><input type="text" name="account_number" required value="{{ old('account_number', $penyewaan->account_number) }}" class="st-form-input"></div>
                                            <div class="st-form-group"><label class="st-form-label">Nama Pemilik Rekening <span class="req">*</span></label><input type="text" name="account_holder" required value="{{ old('account_holder', $penyewaan->account_holder) }}" class="st-form-input"></div>
                                            <div class="st-form-group"><label class="st-form-label">Tanggal Transfer <span class="req">*</span></label><input type="date" name="refund_date" required value="{{ old('refund_date', now()->format('Y-m-d')) }}" class="st-form-input"></div>
                                            <div class="st-form-group"><label class="st-form-label">Bukti Transfer <span class="req">*</span></label><input type="file" name="transfer_proof" required accept=".jpg,.jpeg,.png,.pdf" class="st-form-input" style="padding:.45rem .75rem;"><div style="font-size:.7rem;color:#94a3b8;margin-top:.3rem;">JPG, PNG, PDF. Maks 5MB.</div></div>
                                        </div>
                                        <div class="st-form-group"><label class="st-form-label">Catatan <span class="opt">(opsional)</span></label><textarea name="notes" rows="2" class="st-form-textarea" placeholder="Keterangan tambahan...">{{ old('notes') }}</textarea></div>
                                        <div style="display:flex;justify-content:flex-end;"><button type="submit" onclick="return confirm('Simpan bukti transfer refund deposit?')" class="st-btn st-btn-amber">Simpan Bukti Transfer →</button></div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="st-action-row">
                                <button onclick="if(confirm('Lanjutkan tanpa transfer? Deposit habis digunakan biaya kerusakan.')) document.getElementById('autoRefundForm').submit()" class="st-btn st-btn-slate">Lanjutkan ke Tahap TTD Penyewa →</button>
                            </div>
                            <form action="{{ route('pengelola.penyewaan.handover.store-refund-proof', $penyewaan) }}" method="POST" enctype="multipart/form-data" class="hidden" id="autoRefundForm">@csrf<input type="hidden" name="refund_amount" value="0"><input type="hidden" name="bank_name" value="-"><input type="hidden" name="account_number" value="-"><input type="hidden" name="account_holder" value="-"><input type="hidden" name="refund_date" value="{{ now()->format('Y-m-d') }}"><input type="hidden" name="notes" value="Deposit habis digunakan untuk biaya kerusakan."></form>
                        @endif
                    </div>

                @elseif($penyewaan->status === 'menunggu_konfirmasi_refund' && $penyewaan->depositRefund)
                    <div class="st-section st-section-slate">
                        <div class="st-eyebrow">⏳ Menunggu Penyewa</div>
                        <h2>Menunggu Konfirmasi Penerimaan Refund</h2>
                        <p>Bukti transfer sudah diinput. Menunggu penyewa mengkonfirmasi bahwa dana sudah diterima di rekening.</p>
                        <div class="st-meta-grid">
                            <div class="st-meta-cell success">
                                <div class="lbl">Nominal Ditransfer</div>
                                <div class="val">Rp {{ number_format($penyewaan->depositRefund->refund_amount, 0, ',', '.') }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">Tanggal Transfer</div>
                                <div class="val">{{ $penyewaan->depositRefund->refund_date?->format('d M Y') }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">Bank Tujuan</div>
                                <div class="val">{{ $penyewaan->depositRefund->bank_name }} — {{ $penyewaan->depositRefund->account_number }}</div>
                            </div>
                        </div>
                    </div>                  

                @elseif($penyewaan->status === 'menunggu_ttd_pengembalian' && ! $st->tenant_signed_return_document_path)
                    <div class="st-section st-section-slate">
                        <div class="st-eyebrow">⏳ Menunggu Penyewa</div>
                        <h2>Menunggu Tanda Tangan Dokumen Pengembalian</h2>
                        <p>Bukti refund deposit sudah diinput. Penyewa perlu mengunduh dan menandatangani dokumen pengembalian.</p>
                        @if($penyewaan->depositRefund)
                            <div class="st-meta-grid"> 
                                <div class="st-meta-cell"><div class="lbl">Nominal Refund</div><div class="val" style="color:#059669;">Rp {{ number_format($penyewaan->depositRefund->refund_amount, 0, ',', '.') }}</div></div>
                                <div class="st-meta-cell"><div class="lbl">Tanggal Transfer</div><div class="val">{{ $penyewaan->depositRefund->refund_date?->format('d M Y') }}</div></div>
                                <div class="st-meta-cell"><div class="lbl">Bank Tujuan</div><div class="val">{{ $penyewaan->depositRefund->bank_name }}</div></div>
                            </div>
                        @endif
                        <div class="st-action-row"><a href="{{ route('pengelola.penyewaan.handover.download-return', $penyewaan) }}" class="st-btn st-btn-sky">Lihat Template Dokumen Pengembalian</a></div>
                    </div>

                @elseif($penyewaan->status === 'menunggu_pembayaran_kerusakan')
                    @php $invoice = $penyewaan->damageInvoice; @endphp
                    <div class="st-section st-section-red">
                        <div class="st-eyebrow">⏳ Menunggu Pembayaran</div>
                        <h2>Menunggu Pelunasan Invoice Kerusakan</h2>
                        <p>Biaya kerusakan melebihi deposit. Invoice tambahan sudah dikirim ke penyewa.</p>
                        @if($invoice)
                            <div class="st-meta-grid">
                                <div class="st-meta-cell"><div class="lbl">No. Invoice</div><div class="val" style="font-family:monospace;font-size:.8rem;">{{ $invoice->invoice_number }}</div></div>
                                <div class="st-meta-cell"><div class="lbl">Tagihan Tambahan</div><div class="val" style="color:#dc2626;">Rp {{ number_format($invoice->additional_charge, 0, ',', '.') }}</div></div>
                                <div class="st-meta-cell"><div class="lbl">Status</div><div class="val" style="{{ $invoice->isPaid() ? 'color:#059669;' : 'color:#dc2626;' }}">{{ $invoice->status_label }}</div></div>
                            </div>
                        @endif
                    </div>

                @elseif($penyewaan->status === 'menunggu_konfirmasi_selesai')
                    <div class="st-section st-section-teal">
                        <div class="st-eyebrow">⚡ Langkah Terakhir</div>
                        <h2>Konfirmasi Dokumen Serah Terima Pengembalian</h2>
                        <p>Penyewa telah menandatangani dokumen pengembalian. Periksa dokumen di bawah, lalu konfirmasi untuk menyelesaikan penyewaan.</p>
                    </div>

                    @if($st->tenant_signed_return_document_path)
                        <div class="st-card">
                            <div class="st-card-header">
                                <div class="st-card-header-accent" style="background:linear-gradient(180deg,#0f766e,#14b8a6);"></div>
                                <h3>Dokumen Pengembalian (TTD Penyewa)</h3>
                            </div>
                            <div class="st-card-body">
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:.875rem;flex-wrap:wrap;">
                                    <div>
                                        <div style="font-size:.82rem;font-weight:700;color:var(--navy);">Dokumen Pengembalian</div>
                                        <div style="font-size:.72rem;color:var(--slate);">Diunggah oleh penyewa pada {{ $st->tenant_signed_return_at?->format('d M Y, H:i') ?? '-' }}</div>
                                    </div>
                                    <a href="{{ Storage::url($st->tenant_signed_return_document_path) }}" target="_blank" class="st-btn st-btn-ghost" style="padding:.35rem .75rem;font-size:.72rem;">🔍 Tab Baru</a>
                                </div>

                                <iframe src="{{ Storage::url($st->tenant_signed_return_document_path) }}"
                                    style="width:100%;height:380px;border:0;border-radius:.75rem;" title="Preview Dokumen Pengembalian"></iframe>

                                <div class="st-meta-grid" style="margin-top:.875rem;">
                                    <div class="st-meta-cell">
                                        <div class="lbl">Diunggah Pada</div>
                                        <div class="val">{{ $st->tenant_signed_return_at?->format('d M Y, H:i') ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="st-section st-section-teal" style="margin-top:0;">
                        <div class="st-action-row">
                            <form method="POST" action="{{ route('pengelola.penyewaan.handover.confirm-rental-completed', $penyewaan) }}">
                                @csrf
                                <button type="submit" onclick="return confirm('Konfirmasi penyewaan selesai? Koleksi akan kembali tersedia.')" class="st-btn st-btn-teal">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    ✓ Konfirmasi Dokumen Serah Terima Pengembalian
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endif

            {{-- Pengelola: Selesai --}}
            @if($isPengelola && $penyewaan->status === 'selesai')
                <div class="st-section st-section-green">
                    <div class="st-eyebrow">🎉 Penyewaan Selesai</div>
                    <h2>Transaksi Telah Diarsipkan</h2>
                    <p>Seluruh proses penyewaan selesai. Koleksi telah dikembalikan dan kembali tersedia.</p>
                    <div class="st-action-row">
                        @if($st->handover_document_path)<a href="{{ route('pengelola.penyewaan.handover.download', $penyewaan) }}" class="st-btn st-btn-sky">Unduh Dok. Serah Terima Awal</a>@endif
                        @if($st->return_document_path)<a href="{{ route('pengelola.penyewaan.handover.download-return', $penyewaan) }}" class="st-btn st-btn-slate">Unduh Dok. Pengembalian</a>@endif
                    </div>
                </div>
            @endif

            {{-- ════════════════════════════════════════════════════════════════
                 AKSI PENYEWA
            ════════════════════════════════════════════════════════════════ --}}

            {{-- Menunggu pengiriman --}}
            @if(!$isPengelola && in_array($penyewaan->status, ['pengiriman'])
                && in_array($st->handover_status, ['waiting_handover', 'preparing_delivery'])
                && !($isManager && $st->handover_status === 'preparing_delivery'))
                <div class="st-section st-section-slate">
                    <div class="st-eyebrow">⏳ Menunggu Pengelola</div>
                    <h2>Koleksi Sedang Disiapkan</h2>
                    <p>Pengelola sedang memproses dan menyiapkan pengiriman. Anda akan mendapat informasi saat koleksi dikirimkan.</p>
                </div>
            @endif

            @if(!$isPengelola && $isManager && in_array($penyewaan->status, ['pengiriman', 'siap_diserahkan'])
                && $st->handover_status === 'preparing_delivery')
                <div class="st-section st-section-amber">
                    <div class="st-eyebrow">📦 Sedang Dipersiapkan</div>
                    <h2>Koleksi Sedang Disiapkan untuk Dikirim</h2>
                    <p>
                        Pengelola sedang menyiapkan koleksi untuk dikirimkan ke alamat Anda menggunakan
                        kendaraan/petugas pengelola. Anda akan mendapat informasi saat koleksi sudah dikirimkan.
                    </p>
                    @if($st->delivery_officer)
                    <div class="st-meta-grid">
                        <div class="st-meta-cell">
                            <div class="lbl">Petugas Pengirim</div>
                            <div class="val">{{ $st->delivery_officer }}</div>
                        </div>
                        @if($st->delivery_scheduled_at)
                        <div class="st-meta-cell">
                            <div class="lbl">Rencana Kirim</div>
                            <div class="val">{{ \Carbon\Carbon::parse($st->delivery_scheduled_at)->format('d M Y') }}</div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            @endif

            {{-- ── Penyewa: dalam_pengiriman — GABUNGAN semua info ── --}}
            @if(!$isPengelola && in_array($penyewaan->status, ['pengiriman', 'dalam_pengiriman', 'siap_diserahkan']) && $st->handover_status === 'in_delivery')
                @if($isManager)
                    {{-- Manager: tampilkan sesuai sub-status --}}
                    @php $mds = $st->manager_delivery_status; @endphp

                    @if($mds === 'tiba_di_tujuan')
                        <div class="st-card">
                            <div class="st-card-header">
                                <div class="st-card-header-accent" style="background:linear-gradient(180deg,#10b981,#059669);"></div>
                                <h3>🏁 Koleksi Telah Tiba</h3>
                            </div>
                            <div class="st-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">
                                <div style="padding:.875rem 1rem;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:.875rem;">
                                    <div style="font-size:.82rem;font-weight:600;color:#065f46;">Koleksi sudah tiba di lokasi Anda.</div>
                                    <div style="font-size:.76rem;color:#4b7a5a;margin-top:.2rem;">Periksa kondisi koleksi dan konfirmasi penerimaan.</div>
                                </div>
                                <div class="st-meta-grid">
                                    <div class="st-meta-cell"><div class="lbl">Metode Pengiriman</div><div class="val">{{ $st->delivery_method ?? '-' }}</div></div>
                                    <div class="st-meta-cell"><div class="lbl">Petugas Pengirim</div><div class="val">{{ $st->delivery_officer ?? '-' }}</div></div>
                                </div>
                                <div class="st-action-row">
                                    <form action="{{ route('penyewaan.requests.handover.confirm-received', $penyewaan) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            onclick="return confirm('Konfirmasi bahwa koleksi sudah Anda terima dalam kondisi baik?')"
                                            class="st-btn st-btn-emerald">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Konfirmasi Penerimaan Koleksi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Timeline status pengiriman --}}
                        @if(!empty($st->manager_delivery_timeline))
                            @include('penyewaan.partials.manager-timeline', ['timeline' => $st->manager_delivery_timeline])
                        @endif

                    @elseif($mds === 'dalam_perjalanan')
                        <div class="st-card">
                            <div class="st-card-header">
                                <div class="st-card-header-accent" style="background:linear-gradient(180deg,#8b5cf6,#6d28d9);"></div>
                                <h3>🚚 Koleksi Dalam Perjalanan</h3>
                            </div>
                            <div class="st-card-body" style="display:flex;flex-direction:column;gap:1rem;">
                                <p style="font-size:.84rem;color:#475569;margin:0;">Koleksi sedang dalam perjalanan menuju alamat Anda.</p>
                                <div class="st-meta-grid">
                                    <div class="st-meta-cell"><div class="lbl">Metode Pengiriman</div><div class="val">{{ $st->delivery_method ?? '-' }}</div></div>
                                    <div class="st-meta-cell"><div class="lbl">Estimasi Tiba</div><div class="val">{{ $st->delivery_scheduled_at ? \Carbon\Carbon::parse($st->delivery_scheduled_at)->format('d M Y') : '-' }}</div></div>
                                </div>
                                @if(!empty($st->manager_delivery_timeline))
                                    @include('penyewaan.partials.manager-timeline', ['timeline' => $st->manager_delivery_timeline])
                                @endif
                            </div>
                        </div>

                    @else
                        {{-- dikemas / siap_dikirim / null --}}
                        <div class="st-card">
                            <div class="st-card-header">
                                <div class="st-card-header-accent" style="background:linear-gradient(180deg,#f59e0b,#d97706);"></div>
                                <h3>📦 Sedang Dipersiapkan</h3>
                            </div>
                            <div class="st-card-body">
                                <p style="font-size:.84rem;color:#475569;margin:0;">Pengelola sedang mempersiapkan koleksi untuk dikirimkan. Pantau status pengiriman di bawah.</p>
                                @if(!empty($st->manager_delivery_timeline))
                                    @include('penyewaan.partials.manager-timeline', ['timeline' => $st->manager_delivery_timeline])
                                @endif
                            </div>
                        </div>
                    @endif

                @else
                    {{-- ── KURIR: info + tracking + konfirmasi (GABUNGAN) ── --}}
                    <div class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-header-accent" style="background:linear-gradient(180deg,#38bdf8,#0284c7);"></div>
                            <h3>🚚 Dalam Pengiriman via Kurir</h3>
                        </div>
                        <div class="st-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

                            {{-- Informasi pengiriman --}}
                            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:.75rem;">
                                <div class="st-meta-cell"><div class="lbl">Kurir</div><div class="val">{{ $st->delivery_method ?? '-' }}</div></div>
                                <div class="st-meta-cell"><div class="lbl">No. Resi</div><div class="val" style="font-family:monospace;">{{ $st->delivery_tracking_number ?? '-' }}</div></div>
                                <div class="st-meta-cell"><div class="lbl">Penerima</div><div class="val">{{ $st->recipient_name ?? '-' }}</div></div>
                                @if($penyewaan->courier_etd)
                                <div class="st-meta-cell">
                                    <div class="lbl">Estimasi Tiba</div>
                                    <div class="val">{{ $penyewaan->courier_etd }} hari kerja</div>
                                </div>
                                @endif
                            </div>

                            {{-- Tracking --}}
                            @include('penyewaan.partials.tracking-card', [
                                'trackingUrl' => route('penyewaan.requests.handover.tracking-data', $penyewaan),
                            ])

                            {{-- Konfirmasi penerimaan --}}
                            <div style="border-top:1.5px solid var(--border);padding-top:1.25rem;">
                                <div style="font-size:.7rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin-bottom:.75rem;">
                                    ⚡ Aksi Diperlukan
                                </div>
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                                    <div>
                                        <div style="font-size:.84rem;font-weight:600;color:var(--navy);">Konfirmasi Penerimaan Koleksi</div>                                    </div>
                                    <form action="{{ route('penyewaan.requests.handover.confirm-received', $penyewaan) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Konfirmasi bahwa koleksi sudah Anda terima?')" class="st-btn st-btn-blue">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            ✓ Koleksi Sudah Saya Terima
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
            @endif

            {{-- ── Penyewa: Sedang dalam pengecekan kondisi (legacy) ── --}}
            @if(!$isPengelola && $st->handover_status === 'condition_checking' && $penyewaan->status === 'pengiriman')
                <div class="st-section st-section-amber">
                    <div class="st-eyebrow">⚡ Aksi Diperlukan</div>
                    <h2>Periksa Kondisi Koleksi</h2>
                    <p>
                        Penerimaan koleksi sudah dikonfirmasi pada
                        <strong>{{ $st->confirmed_received_at?->format('d M Y, H:i') ?? '-' }}</strong>.
                        Periksa kondisi koleksi sebelum melanjutkan ke dokumen serah terima.
                    </p>
                    <div class="st-action-row">
                        <a href="{{ route('penyewaan.requests.handover.condition-check', $penyewaan) }}"
                        class="st-btn st-btn-amber">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                            Mulai Pengecekan Kondisi Koleksi →
                        </a>
                    </div>
                </div>
            @endif
            
            {{-- ── Penyewa: Laporan kerusakan sudah dikirim, menunggu pengelola (legacy) ── --}}
            @if(!$isPengelola && $st->handover_status === 'damage_reported' && in_array($penyewaan->status, ['pengiriman', 'menunggu_review_kerusakan']))
                @php
                    $checkedItems  = $st->getCheckedDamageItems();
                    $sevLabel      = match($st->arrival_damage_severity) {
                        'ringan' => '🟡 Ringan', 'parah' => '🔴 Parah', default => '-'
                    };
                    $decLabel = match($st->arrival_damage_tenant_decision) {
                        'lanjutkan' => '✅ Ingin melanjutkan sewa',
                        'batalkan'  => '❌ Ingin membatalkan sewa',
                        default     => '-'
                    };
                @endphp
                <div class="st-section st-section-amber">
                    <div class="st-eyebrow">⏳ Menunggu Pengelola</div>
                    <h2>Laporan Kerusakan Sudah Dikirim</h2>
                    <p>
                        Laporan kerusakan kamu sudah diterima pengelola pada
                        <strong>{{ $st->arrival_damage_reported_at?->format('d M Y, H:i') ?? '-' }}</strong>.
                        Tunggu keputusan pengelola — kamu akan mendapat notifikasi.
                    </p>
            
                    {{-- Ringkasan laporan --}}
                    <div style="margin-top:1rem;background:var(--white);border:1.5px solid var(--border);border-radius:1rem;padding:1.1rem;">
                        <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.75rem;">
                            Ringkasan Laporan yang Dikirim
                        </div>

                        {{-- Foto kondisi depan & belakang --}}
                        @if($st->arrival_condition_front_photo || $st->arrival_condition_back_photo)
                        <div style="margin-top:.75rem;padding-top:.75rem;border-top:1px solid var(--border);">
                            <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.6rem;">
                                Foto Kondisi Koleksi (Depan & Belakang)
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                                @if($st->arrival_condition_front_photo)
                                <div>
                                    <div style="font-size:.72rem;color:#64748b;margin-bottom:.35rem;font-weight:600;">Tampak Depan</div>
                                    <img src="{{ asset('storage/' . $st->arrival_condition_front_photo) }}"
                                        alt="Foto Depan"
                                        style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);
                                                object-fit:cover;max-height:220px;cursor:pointer;"
                                        onclick="openPhotoModalPenyewa(this.src)">
                                </div>
                                @endif
                                @if($st->arrival_condition_back_photo)
                                <div>
                                    <div style="font-size:.72rem;color:#64748b;margin-bottom:.35rem;font-weight:600;">Tampak Belakang</div>
                                    <img src="{{ asset('storage/' . $st->arrival_condition_back_photo) }}"
                                        alt="Foto Belakang"
                                        style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);
                                                object-fit:cover;max-height:220px;cursor:pointer;"
                                        onclick="openPhotoModalPenyewa(this.src)">
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Foto / video bukti kerusakan --}}
                        @if(!empty($st->arrival_damage_photos))
                        <div style="margin-top:.75rem;padding-top:.75rem;border-top:1px solid var(--border);">
                            <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.6rem;">
                                Foto / Video Bukti Kerusakan ({{ count($st->arrival_damage_photos) }} file)
                            </div>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:.6rem;">
                                @foreach($st->arrival_damage_photos as $i => $photoPath)
                                    @php $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION)); @endphp
                                    @if(in_array($ext, ['jpg','jpeg','png']))
                                        <div style="position:relative;">
                                            <img src="{{ asset('storage/' . $photoPath) }}"
                                                alt="Bukti Kerusakan {{ $i+1 }}"
                                                style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:.875rem;
                                                        border:1.5px solid var(--border);cursor:pointer;"
                                                onclick="openPhotoModalPenyewa(this.src)">
                                            <span style="position:absolute;top:.4rem;left:.4rem;background:rgba(0,0,0,.55);
                                                        color:#fff;font-size:.65rem;font-weight:700;padding:.15rem .4rem;
                                                        border-radius:.4rem;">{{ $i+1 }}</span>
                                        </div>
                                    @elseif(in_array($ext, ['mp4','mov']))
                                        <div style="background:#f8fafc;border:1.5px solid var(--border);border-radius:.875rem;
                                                    display:flex;flex-direction:column;align-items:center;justify-content:center;
                                                    aspect-ratio:1;gap:.4rem;">
                                            <span style="font-size:1.5rem;">🎥</span>
                                            <a href="{{ asset('storage/' . $photoPath) }}" target="_blank"
                                            style="font-size:.72rem;color:var(--blue);font-weight:600;text-decoration:none;">
                                                Video {{ $i+1 }}
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
            
                        {{-- Kerusakan --}}
                        @if(!empty($checkedItems))
                        <div style="margin-bottom:.75rem;">
                            <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.4rem;">Jenis Kerusakan</div>
                            <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                                @foreach($checkedItems as $item)
                                    <span style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:.6rem;
                                                padding:.3rem .75rem;font-size:.76rem;font-weight:600;color:#991b1b;">
                                        ⚠ {{ $item }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
            
                        {{-- Deskripsi --}}
                        @if($st->arrival_damage_description)
                        <div style="margin-top:.75rem;padding-top:.75rem;border-top:1px solid var(--border);">
                            <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.25rem;">Deskripsi</div>
                            <div style="font-size:.82rem;color:#334155;line-height:1.65;">{{ $st->arrival_damage_description }}</div>
                            @endif
                        </div>
                    </div>
                @endif
            
            {{-- ── Penyewa: Keputusan pengelola sudah ada (damage_reviewed) ── --}}
            @if(!$isPengelola && $st->handover_status === 'damage_reviewed' && $st->arrival_damage_manager_decision)
                @php
                    $managerDecision = $st->arrival_damage_manager_decision;
                @endphp
            
                @if($managerDecision === 'setuju_batal')
                    <div class="st-section st-section-slate">
                        <div class="st-eyebrow">❌ Sewa Dibatalkan</div>
                        <h2>Pengelola Menyetujui Pembatalan</h2>
                        <p>
                            Pengelola menyetujui pembatalan sewa karena kerusakan saat pengiriman.
                            Deposit akan dikembalikan penuh ke rekening kamu.
                        </p>
                        @if($st->arrival_damage_manager_notes)
                        <div class="st-catatan" style="margin-top:1rem;">
                            <div class="lbl">Catatan dari Pengelola</div>
                            <div class="val">{{ $st->arrival_damage_manager_notes }}</div>
                        </div>
                        @endif
                    </div>
                @endif
            @endif



            {{-- Upload dokumen serah terima (Penyewa) --}}
            @if(!$isPengelola && $penyewaan->status === 'menunggu_dokumen_serah_terima')
            @php $stDitolak = $st->serah_terima_status === 'rejected'; @endphp
            <div class="st-section {{ $stDitolak ? 'st-section-red' : 'st-section-amber' }}">
                <div class="st-eyebrow">{{ $stDitolak ? '❌ Dokumen Ditolak' : '📄 Langkah Serah Terima' }}</div>
                <h2>{{ $stDitolak ? 'Upload Ulang Dokumen Serah Terima' : 'Unduh & Upload Dokumen Serah Terima' }}</h2>
                <p>
                    @if($stDitolak)
                        Dokumen Anda sebelumnya ditolak. Unduh kembali dokumen, perbaiki sesuai catatan pengelola, tanda tangani ulang, lalu upload kembali.
                    @else
                        Unduh dokumen serah terima, tandatangani, lalu upload kembali sebagai bukti penerimaan.
                    @endif
                </p>
                {{-- tombol unduh dan form upload tetap sama seperti sebelumnya --}}
                <div class="st-action-row">
                    <a href="{{ route('penyewaan.requests.handover.download', $penyewaan) }}" class="st-btn" style="background:rgba(217,119,6,.15);border:1.5px solid rgba(217,119,6,.35);color:#d97706;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        {{ $stDitolak ? 'Unduh Ulang Dokumen Serah Terima' : 'Unduh Dokumen Serah Terima' }}
                    </a>
                </div>

                <form action="{{ route('penyewaan.requests.handover.upload', $penyewaan) }}"
                    method="POST" enctype="multipart/form-data" style="margin-top:1.25rem;">
                    @csrf
                    @if($errors->any())
                        <div class="st-errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif
                    <div class="st-form-group">
                        <label class="st-form-label">
                            {{ $stDitolak ? 'Dokumen Serah Terima yang Sudah Diperbaiki & Ditandatangani' : 'Dokumen Serah Terima yang Sudah Ditandatangani' }}
                            <span class="req">*</span>
                        </label>
                        <input type="file" name="tenant_signed_document" accept=".pdf,.doc,.docx" required
                            class="st-form-input" style="padding:.5rem .75rem;">
                        <div style="font-size:.7rem;color:#94a3b8;margin-top:.3rem;">Format: PDF, DOC, DOCX. Maksimal 10MB.</div>
                    </div>
                    <div style="display:flex;justify-content:flex-end;">
                        <button type="submit" class="st-btn st-btn-amber">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                            {{ $stDitolak ? 'Upload Ulang Dokumen' : 'Upload Dokumen' }}
                        </button>
                    </div>
                </form>
            </div>
            @endif

            @if(!$isPengelola && $penyewaan->status === 'verifikasi_serah_terima')
                <div class="st-section st-section-violet">
                    <div class="st-eyebrow">⏳ Menunggu Pengelola</div>
                    <h2>Dokumen Sedang Diverifikasi</h2>
                    <p>Dokumen serah terima Anda sedang diperiksa oleh pengelola. Anda akan mendapat informasi setelah divalidasi.</p>
                </div>
            @endif

            {{-- Masa aktif (Penyewa) --}}
            @if(!$isPengelola && $penyewaan->status === 'aktif')
                @php
                    $isUrgent  = $sisaHari !== null && $sisaHari <= 3 && $sisaHari >= 0;
                    $isExpired = $sisaHari !== null && $sisaHari < 0;
                @endphp
                <div class="st-section {{ $isExpired ? 'st-section-red' : ($isUrgent ? 'st-section-amber' : 'st-section-emerald') }}">
                    <div class="st-eyebrow">{{ $isExpired ? '⚠ Masa Sewa Berakhir' : '✅ Masa Sewa Berjalan' }}</div>
                    <h2>{{ $isExpired ? 'Segera Kembalikan Koleksi' : 'Penyewaan Aktif' }}</h2>
                    <div class="st-meta-grid">
                        <div class="st-meta-cell"><div class="lbl">Berakhir Pada</div><div class="val">{{ $penyewaan->end_date?->format('d M Y') ?? '-' }}</div></div>
                        <div class="st-meta-cell" style="{{ $isUrgent || $isExpired ? 'border-color:#fecaca;background:#fef2f2;' : '' }}">
                            <div class="lbl">Sisa Hari</div>
                            <div class="val" style="{{ $isUrgent || $isExpired ? 'color:#dc2626;' : 'color:#059669;' }}">{{ $isExpired ? 'Sudah berakhir' : ($sisaHari . ' hari lagi') }}</div>
                        </div>
                        <div class="st-meta-cell"><div class="lbl">Mulai Aktif</div><div class="val">{{ $penyewaan->rental_started_at?->format('d M Y') ?? '-' }}</div></div>
                    </div>
                    @if($isExpired)
                        <p style="margin-top:1rem;font-size:.82rem;font-weight:600;color:#dc2626;">⚠ Masa sewa telah berakhir. Pengelola akan segera menghubungi Anda.</p>
                    @endif
                </div>
            @endif

            {{-- ════ PENYEWA: TAHAP PENGEMBALIAN ════ --}}
            @if(!$isPengelola && ($isCancellationReturn || in_array($penyewaan->status, [
                'pengembalian', 'menunggu_konfirmasi_refund', 'menunggu_ttd_pengembalian',
                'menunggu_pembayaran_kerusakan', 'menunggu_konfirmasi_selesai',
            ])) && ! ($penyewaan->status === 'menunggu_konfirmasi_refund' && $st->refund_processed_at && ! $penyewaan->depositRefund))

                @if(($penyewaan->status === 'pengembalian' || ($isCancellationReturn && $penyewaan->status === 'dibatalkan')) && ! $st->return_shipment_submitted_at)
                    <div class="st-section st-section-orange">
                        <div class="st-eyebrow">⚡ Aksi Diperlukan</div>
                        <h2>{{ $isCancellationReturn ? 'Kembalikan Koleksi ke Museum' : 'Kirim Koleksi Kembali ke Museum' }}</h2>
                        <p>{{ $isCancellationReturn
                            ? 'Sewa dibatalkan karena kerusakan saat pengiriman. Kembalikan koleksi ke museum dan isi informasi pengiriman di bawah ini.'
                            : 'Masa penyewaan berakhir. Kirimkan koleksi kembali ke museum dan isi informasi pengiriman di bawah ini.' }}</p>
                        <form action="{{ route('penyewaan.requests.handover.submit-return-shipment', $penyewaan) }}" method="POST" style="margin-top:1.25rem;">
                            @csrf
                            @if($errors->any())<div class="st-errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                            <div class="st-form-grid">
                                <div class="st-form-group"><label class="st-form-label">Metode Pengiriman <span class="req">*</span></label><input name="return_shipment_method" required value="{{ old('return_shipment_method') }}" placeholder="JNE, TIKI, dll" class="st-form-input"></div>
                                <div class="st-form-group"><label class="st-form-label">Nama Pengirim / Petugas <span class="req">*</span></label><input name="return_shipment_officer" required value="{{ old('return_shipment_officer') }}" class="st-form-input"></div>
                                <div class="st-form-group"><label class="st-form-label">Nomor Resi </label><input name="return_shipment_tracking" value="{{ old('return_shipment_tracking') }}" class="st-form-input"></div>
                                <div class="st-form-group"><label class="st-form-label">Tanggal Kirim <span class="req">*</span></label><input type="datetime-local" name="return_shipment_scheduled_at" required value="{{ old('return_shipment_scheduled_at') }}" class="st-form-input"></div>
                            </div>
                            <div class="st-form-group"><label class="st-form-label">Catatan <span class="opt">(opsional)</span></label><textarea name="return_shipment_notes" rows="2" class="st-form-textarea" placeholder="Catatan khusus terkait pengiriman balik...">{{ old('return_shipment_notes') }}</textarea></div>
                            <div style="display:flex;justify-content:flex-end;"><button type="submit" onclick="return confirm('Kirim informasi pengiriman balik koleksi?')" class="st-btn st-btn-orange">Kirim Informasi Pengiriman →</button></div>
                        </form>
                    </div>

                @elseif(($penyewaan->status === 'pengembalian' || ($isCancellationReturn && $penyewaan->status === 'dibatalkan')) && $st->return_shipment_submitted_at && ! $st->collection_arrived_at)
                    {{-- Info pengiriman balik --}}
                    <div class="st-section st-section-slate">
                        <div class="st-eyebrow">📦 Info Pengiriman Balik</div>
                        <h2>Info Pengiriman Balik Sudah Dikirim</h2>
                        <p>Informasi pengiriman balik sudah terkirim. Pengelola akan mengkonfirmasi saat koleksi tiba di museum.</p>
                        <div class="st-meta-grid">
                            <div class="st-meta-cell"><div class="lbl">Metode</div><div class="val">{{ $st->return_shipment_method ?? '-' }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">No. Resi</div><div class="val" style="font-family:monospace;">{{ $st->return_shipment_tracking ?? '-' }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">Dikirim Oleh</div><div class="val">{{ $st->return_shipment_officer ?? '-' }}</div></div>
                            <div class="st-meta-cell"><div class="lbl">Rencana Kirim</div><div class="val">{{ $st->return_shipment_scheduled_at?->format('d M Y H:i') ?? '-' }}</div></div>
                        </div>
                        @if($st->return_shipment_notes)
                            <div class="st-catatan"><div class="lbl">Catatan</div><div class="val">{{ $st->return_shipment_notes }}</div></div>
                        @endif
                    </div>

                    {{-- Tracking Binderbyte (card mandiri) --}}
                    @if($st->return_shipment_tracking)
                        @include('penyewaan.partials.tracking-card', [
                            'trackingUrl' => route('penyewaan.requests.handover.tracking-data', $penyewaan) . '?for=return',
                        ])
                    @else
                        {{-- Timeline manual --}}
                        @php
                            $returnStatuses = \App\Models\SerahTerima::returnShipmentStatuses();
                            $statusKeys = array_keys($returnStatuses);
                            $currentStatus = $st->return_shipment_status;
                            $currentIdx = $currentStatus ? array_search($currentStatus, $statusKeys) : -1;
                        @endphp
                        <div class="st-note-box">
                            <strong>Pengiriman Mandiri</strong> — Update status secara bertahap agar pengelola dapat memantau progres pengembalian.
                        </div>
                        @if($st->return_shipment_status === 'tiba_di_tujuan')
                            <div class="st-catatan" style="background:#d1fae5;border-color:#6ee7b7;">
                                <div class="lbl" style="color:#065f46;">✅ Koleksi Sudah Tiba di Museum</div>
                                <div class="val">Tunggu konfirmasi pengelola bahwa koleksi sudah diterima.</div>
                            </div>
                        @else
                            <form action="{{ route('penyewaan.requests.handover.return-status', $penyewaan) }}" method="POST" style="margin-top:1.25rem;">
                                @csrf
                                @if($errors->any())<div class="st-errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                                <div class="st-form-group">
                                    <label class="st-form-label">Update Status Pengiriman Balik</label>
                                    <div class="st-radio-grid">
                                        @foreach($returnStatuses as $value => $label)
                                            @php
                                                $statusIndex = array_search($value, $statusKeys);
                                                $canUpdate = $currentIdx === -1 ? $statusIndex === 0 : $statusIndex === $currentIdx + 1;
                                            @endphp
                                            <label class="st-radio-label">
                                                <input type="radio" name="return_shipment_status" value="{{ $value }}"
                                                    {{ old('return_shipment_status', $currentStatus) === $value ? 'checked' : '' }}
                                                    {{ $canUpdate ? '' : 'disabled' }}>
                                                <span>{!! $label !!}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Catatan <span class="opt">(opsional)</span></label>
                                    <input name="catatan_status" value="{{ old('catatan_status') }}" class="st-form-input" placeholder="Detail update..."/>
                                </div>
                                <div style="display:flex;justify-content:flex-end;">
                                    <button type="submit" class="st-btn st-btn-violet">Update Status</button>
                                </div>
                            </form>
                        @endif
                        @if(!empty($st->return_shipment_timeline))
                            <div class="st-card" style="margin-top:1.25rem;">
                                <div class="st-card-header"><div class="st-card-header-accent"></div><h3>Timeline Pengiriman Balik</h3></div>
                                <div class="st-card-body">
                                    <div class="st-timeline">
                                        @foreach(array_reverse($st->return_shipment_timeline) as $entry)
                                            <div class="st-timeline-item">
                                                <div class="st-timeline-dot" style="background:#38bdf8;"></div>
                                                <div class="st-timeline-body">
                                                    <div class="tlabel">{{ $entry['label'] }}</div>
                                                    @if(!empty($entry['catatan']))<div class="tnote">{{ $entry['catatan'] }}</div>@endif
                                                    <div class="tmeta">{{ \Carbon\Carbon::parse($entry['timestamp'])->format('d M Y, H:i') }} • oleh {{ $entry['by'] }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                @elseif($penyewaan->status === 'pengembalian' && $st->collection_arrived_at && ! $st->return_document_path)
                    <div class="st-section st-section-slate">
                        <div class="st-eyebrow">⏳ Menunggu Pengelola</div>
                        <h2>Koleksi Tiba — Sedang Diperiksa</h2>
                        <p>Pengelola sedang melakukan pemeriksaan kondisi koleksi dan menyiapkan dokumen pengembalian.</p>
                    </div>

                @elseif($penyewaan->status === 'menunggu_konfirmasi_refund')
                    @php
                        if ($isCancellationReturn) {
                            $depositAmount = $penyewaan->calculateCancellationRefundAmount();
                            $subtotalAmt   = (int) ($penyewaan->subtotal_amount ?? 0);
                            $depositOnly   = $penyewaan->calculateDeposit();
                            $damageCost    = 0;
                        } else {
                            $depositAmount = $penyewaan->deposit_amount ?: $penyewaan->calculateDeposit();
                            $damageCost    = (int) ($st->final_damage_cost ?? $st->damage_cost ?? 0);
                            $subtotalAmt   = null;
                            $depositOnly   = null;
                        }
                        $sisaRefund = $isCancellationReturn
                            ? $depositAmount
                            : max(0, $depositAmount - $damageCost);

                        // ── SATU-SATUNYA acuan apakah refund sudah dikirim pengelola ──
                        $refund = $penyewaan->depositRefund()->latest()->first();
                                // Fallback: cek di serah_terima jika refund ada di sana
                        $hasRefundProof = ($refund && $refund->transfer_proof_path)
                            || $st->refund_transfer_proof_path;
                        $alreadyConfirmed = $refund?->refund_confirmed_at
                            || $st->refund_confirmed_at;                    @endphp

                    @if($hasRefundProof && ! $alreadyConfirmed)
                        {{-- Pengelola SUDAH transfer & upload bukti — penyewa perlu konfirmasi --}}
                        <div class="st-section st-section-teal">
                            <div class="st-eyebrow">⚡ Aksi Diperlukan</div>
                            <h2>{{ $isCancellationReturn ? 'Konfirmasi Penerimaan Refund' : 'Konfirmasi Penerimaan Refund Deposit' }}</h2>
                            <p>Pengelola telah mentransfer refund ke rekening Anda. Periksa rekening dan konfirmasi bahwa dana sudah diterima.</p>

                            <div class="st-cost-wrap" style="margin-top:1.25rem;">
                                @if($isCancellationReturn)
                                    <div class="st-cost-row"><span class="lbl">Biaya Sewa</span><span class="val">Rp {{ number_format($subtotalAmt, 0, ',', '.') }}</span></div>
                                    <div class="st-cost-row"><span class="lbl">Deposit</span><span class="val">Rp {{ number_format($depositOnly, 0, ',', '.') }}</span></div>
                                @else
                                    <div class="st-cost-row"><span class="lbl">Total Deposit</span><span class="val">Rp {{ number_format($depositAmount, 0, ',', '.') }}</span></div>
                                    @if($damageCost > 0)
                                        <div class="st-cost-row"><span class="lbl">Potongan Kerusakan</span><span class="val" style="color:#f87171;">Rp {{ number_format($damageCost, 0, ',', '.') }}</span></div>
                                    @endif
                                @endif
                                <div class="st-cost-total"><span class="lbl">Yang Ditransfer</span><span class="val">Rp {{ number_format($refund->refund_amount, 0, ',', '.') }}</span></div>
                            </div>

                            <div class="st-meta-grid" style="margin-top:1rem;">
                                <div class="st-meta-cell">
                                    <div class="lbl">Bank</div>
                                    <div class="val">{{ $refund->bank_name }}</div>
                                </div>
                                <div class="st-meta-cell">
                                    <div class="lbl">No. Rekening</div>
                                    <div class="val" style="font-family:monospace;">{{ $refund->account_number }}</div>
                                </div>
                                <div class="st-meta-cell">
                                    <div class="lbl">Atas Nama</div>
                                    <div class="val">{{ $refund->account_holder }}</div>
                                </div>
                                <div class="st-meta-cell">
                                    <div class="lbl">Tanggal Transfer</div>
                                    <div class="val">{{ $refund->refund_date?->format('d M Y') ?? '-' }}</div>
                                </div>
                            </div>

                            @if($refund->transfer_proof_path)
                            <div style="margin-top:1.25rem;background:var(--white);border:1.5px solid var(--border);border-radius:1rem;padding:1.1rem;">
                                <div style="font-size:.75rem;font-weight:700;color:var(--navy);margin-bottom:.75rem;">📎 Bukti Transfer dari Pengelola</div>
                                @php $ext = pathinfo($refund->transfer_proof_path, PATHINFO_EXTENSION); @endphp
                                @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                                    <img src="{{ Storage::url($refund->transfer_proof_path) }}"
                                        alt="Bukti Transfer"
                                        style="width:160px;border-radius:.75rem;border:1px solid var(--border);display:block;cursor:zoom-in;"
                                        onclick="openTransferProofLightbox(this.src, this.alt)">
                                    <div style="font-size:.7rem;color:#94a3b8;margin-top:.4rem;">Klik gambar untuk memperbesar.</div>
                                @else
                                    <a href="{{ Storage::url($refund->transfer_proof_path) }}" target="_blank" class="st-btn st-btn-ghost" style="font-size:.78rem;">
                                        📄 Lihat Bukti Transfer (PDF)
                                    </a>
                                @endif
                            </div>

                            {{-- Lightbox bukti transfer --}}
                            <div id="transfer-proof-lightbox-overlay" onclick="closeTransferProofLightbox(event)"
                                style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.85);backdrop-filter:blur(4px);align-items:center;justify-content:center;cursor:zoom-out;">
                                <span onclick="closeTransferProofLightbox({target:this})"
                                    style="position:absolute;top:1.25rem;right:1.5rem;color:#fff;font-size:2rem;font-weight:300;cursor:pointer;line-height:1;opacity:.7;">&times;</span>
                                <img id="transfer-proof-lightbox-img" src="" alt=""
                                    style="max-width:90vw;max-height:90vh;border-radius:1rem;box-shadow:0 24px 80px rgba(0,0,0,.6);object-fit:contain;"
                                    onclick="event.stopPropagation()">
                            </div>
                            <script>
                                function openTransferProofLightbox(src, alt) {
                                    const ov = document.getElementById('transfer-proof-lightbox-overlay');
                                    document.getElementById('transfer-proof-lightbox-img').src = src;
                                    document.getElementById('transfer-proof-lightbox-img').alt = alt || '';
                                    ov.style.display = 'flex';
                                    document.body.style.overflow = 'hidden';
                                }
                                function closeTransferProofLightbox(e) {
                                    const ov  = document.getElementById('transfer-proof-lightbox-overlay');
                                    const img = document.getElementById('transfer-proof-lightbox-img');
                                    if (e.target === ov || e.target.tagName === 'SPAN') {
                                        ov.style.display = 'none';
                                        document.body.style.overflow = '';
                                        img.src = '';
                                    }
                                }
                                document.addEventListener('keydown', e => {
                                    if (e.key === 'Escape') {
                                        const ov = document.getElementById('transfer-proof-lightbox-overlay');
                                        if (ov && ov.style.display !== 'none') {
                                            ov.style.display = 'none';
                                            document.getElementById('transfer-proof-lightbox-img').src = '';
                                            document.body.style.overflow = '';
                                        }
                                    }
                                });
                            </script>
                        @endif

                            <div class="st-action-row" style="margin-top:1.25rem;">
                                <form method="POST" action="{{ route('penyewaan.requests.handover.confirm-refund', $penyewaan) }}">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Konfirmasi bahwa refund deposit sebesar Rp {{ number_format($refund->refund_amount, 0, ',', '.') }} sudah Anda terima?')"
                                        class="st-btn st-btn-teal">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        ✓ Dana Sudah Saya Terima
                                    </button>
                                </form>
                            </div>
                            <p style="margin-top:.625rem;font-size:.75rem;color:#64748b;">
                                {{ $isCancellationReturn
                                    ? 'Setelah konfirmasi, proses pembatalan penyewaan selesai.'
                                    : 'Setelah konfirmasi, Anda akan diarahkan untuk menandatangani dokumen pengembalian.' }}
                            </p>
                        </div>

                    @else
                        {{-- Pengelola BELUM transfer --}}
                        <div class="st-section st-section-slate">
                            <div class="st-eyebrow">⏳ Menunggu Pengelola</div>
                            <h2>Menunggu Proses Refund Deposit</h2>
                            <p>Pemeriksaan koleksi selesai. Pengelola sedang memproses pengembalian deposit ke rekening Anda.</p>
                            <div style="margin-top:1.25rem;"><div class="st-cost-wrap">
                                <div class="st-cost-row"><span class="lbl">Total Deposit</span><span class="val">Rp {{ number_format($depositAmount, 0, ',', '.') }}</span></div>
                                @if($damageCost > 0)
                                    <div class="st-cost-row"><span class="lbl">Potongan Kerusakan</span><span class="val" style="color:#f87171;">Rp {{ number_format($damageCost, 0, ',', '.') }}</span></div>
                                @endif
                                <div class="st-cost-total"><span class="lbl">Yang Akan Dikembalikan</span><span class="val">Rp {{ number_format($sisaRefund, 0, ',', '.') }}</span></div>
                            </div></div>
                        </div>
                    @endif

                @elseif($penyewaan->status === 'menunggu_pembayaran_kerusakan')
                    @php $invoice = $penyewaan->damageInvoice; @endphp
                    <div class="st-section st-section-red">
                        <div class="st-eyebrow">⚡ Aksi Diperlukan</div>
                        <h2>Lunasi Tagihan Kerusakan</h2>
                        <p>Biaya kerusakan koleksi melebihi deposit Anda. Ada tagihan tambahan yang perlu dilunasi.</p>
                        @if($invoice)
                            <div style="margin-top:1.25rem;"><div class="st-cost-wrap">
                                <div class="st-cost-row"><span class="lbl">Total Kerusakan</span><span class="val">Rp {{ number_format($invoice->restoration_cost, 0, ',', '.') }}</span></div>
                                <div class="st-cost-row"><span class="lbl">Deposit Hangus</span><span class="val" style="color:#f87171;">Rp {{ number_format($invoice->deposit_used, 0, ',', '.') }}</span></div>
                                <div class="st-cost-total"><span class="lbl">Tagihan Anda</span><span class="val" style="color:#f87171;">Rp {{ number_format($invoice->additional_charge, 0, ',', '.') }}</span></div>
                            </div></div>
                            @if(! $invoice->isPaid())
                                <div class="st-action-row"><a href="{{ route('penyewaan.requests.deposit.damage-payment', $penyewaan) }}" class="st-btn st-btn-red">💳 Bayar Sekarang — Rp {{ number_format($invoice->additional_charge, 0, ',', '.') }}</a></div>
                            @else
                                <div style="margin-top:.875rem;padding:.875rem 1rem;background:#d1fae5;border:1.5px solid #6ee7b7;border-radius:.875rem;font-size:.83rem;font-weight:600;color:#065f46;">✓ Invoice sudah lunas. Menunggu dokumen pengembalian disiapkan.</div>
                            @endif
                        @endif
                    </div>

                @elseif($penyewaan->status === 'menunggu_ttd_pengembalian' && ! $st->tenant_signed_return_document_path)
                    <div class="st-section st-section-teal">
                        <div class="st-eyebrow">⚡ Aksi Diperlukan</div>
                        <h2>Tandatangani Dokumen Pengembalian</h2>
                        <p>Unduh dokumen pengembalian, tandatangani, lalu upload kembali untuk menyelesaikan proses.</p>
                        @if($penyewaan->depositRefund && $penyewaan->depositRefund->refund_amount > 0)
                            <div class="st-cost-wrap" style="margin-top:1.25rem;">
                                <div class="st-cost-row"><span class="lbl">Nominal Refund</span><span class="val" style="color:#34d399;">Rp {{ number_format($penyewaan->depositRefund->refund_amount, 0, ',', '.') }}</span></div>
                                <div class="st-cost-row"><span class="lbl">Bank</span><span class="val">{{ $penyewaan->depositRefund->bank_name }}</span></div>
                                <div class="st-cost-row"><span class="lbl">No. Rekening</span><span class="val" style="font-family:monospace;">{{ $penyewaan->depositRefund->account_number }}</span></div>
                            </div>
                        @endif

                        <div class="st-action-row">
                            <a href="{{ route('penyewaan.requests.handover.download-initial-return', $penyewaan) }}"
                            class="st-btn st-btn-sky">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                Unduh Dokumen Pengembalian
                            </a>
                        </div>

                        <form action="{{ route('penyewaan.requests.handover.upload-signed-return', $penyewaan) }}"
                            method="POST" enctype="multipart/form-data" style="margin-top:1.25rem;">
                            @csrf
                            @error('tenant_signed_return_document')
                                <p style="font-size:.72rem;color:#dc2626;margin-bottom:.4rem;">{{ $message }}</p>
                            @enderror
                            <div class="st-form-group">
                                <label class="st-form-label">Dokumen yang Sudah Ditandatangani <span class="req">*</span></label>
                                <input type="file" name="tenant_signed_return_document"
                                    accept=".pdf,.doc,.docx" required
                                    class="st-form-input" style="padding:.5rem .75rem;">
                                <div style="font-size:.7rem;color:#94a3b8;margin-top:.3rem;">PDF, DOC, DOCX. Maks 10MB.</div>
                            </div>
                            <div style="display:flex;justify-content:flex-end;">
                                <button type="submit"
                                    onclick="return confirm('Pastikan dokumen sudah ditandatangani.')"
                                    class="st-btn st-btn-teal">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                                    Upload Dokumen
                                </button>
                            </div>
                        </form>
                    </div>

                @elseif($penyewaan->status === 'menunggu_konfirmasi_selesai')
                    <div class="st-section st-section-slate">
                        <div class="st-eyebrow">⏳ Menunggu Pengelola</div>
                        <h2>Dokumen Berhasil Diunggah</h2>
                        <p>Dokumen pengembalian yang sudah ditandatangani telah berhasil diunggah. Pengelola akan mengkonfirmasi untuk menyelesaikan penyewaan.</p>
                        <div class="st-catatan" style="background:var(--white);"><div class="lbl">Diunggah pada</div><div class="val">{{ $st->tenant_signed_return_at?->format('d M Y H:i') ?? '-' }}</div></div>
                    </div>
                @endif
            @endif

            {{-- Penyewa: Selesai --}}
            @if(!$isPengelola && $penyewaan->status === 'selesai')
                {{-- Tambahkan summary di sini juga --}}            
                <div class="st-section st-section-green">
                    <div class="st-eyebrow">🎉 Penyewaan Selesai</div>
                    <h2>Terima Kasih!</h2>
                    <p>Penyewaan telah selesai dengan sukses. Dokumen dapat diunduh di bawah ini.</p>
                    <div class="st-action-row">
                        <a href="{{ route('penyewaan.requests.handover.download', $penyewaan) }}" class="st-btn st-btn-sky">Unduh Dok. Serah Terima Awal</a>
                        @if($st->return_document_path)<a href="{{ route('penyewaan.requests.handover.download-initial-return', $penyewaan) }}" class="st-btn st-btn-slate">Unduh Dok. Pengembalian</a>@endif
                    </div>
                </div>
            @endif

            @if($st->final_inspection_at)
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent"></div>
                        <h3>Hasil Pemeriksaan Kondisi Pengembalian</h3>
                        @if($st->final_inspection_by)
                            <span style="margin-left:auto;font-size:.72rem;color:#94a3b8;">
                                Diperiksa oleh {{ $st->final_inspection_by }}
                                {{ $st->final_inspection_at ? ' · ' . $st->final_inspection_at->format('d M Y H:i') : '' }}
                            </span>
                        @endif
                    </div>
                    <div class="st-card-body">

                        @php
                            $damageItems = $st->damage_items_detail ?? [];
                            $levelColors = [
                                'ringan' => ['bg'=>'#fffbeb','border'=>'#fde68a','text'=>'#d97706','badge_bg'=>'#fef3c7'],
                                'sedang' => ['bg'=>'#fff7ed','border'=>'#fed7aa','text'=>'#c2410c','badge_bg'=>'#ffedd5'],
                                'berat'  => ['bg'=>'#fef2f2','border'=>'#fecaca','text'=>'#dc2626','badge_bg'=>'#fee2e2'],
                            ];
                            $levelLabels = ['ringan'=>'Ringan','sedang'=>'Sedang','berat'=>'Berat'];
                        @endphp

                        @if(! $st->has_damage)
                            {{-- ── Tidak ada kerusakan ── --}}
                            <div style="display:flex;align-items:center;gap:.75rem;padding:1rem 1.25rem;
                                        background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:1rem;">
                                <span style="font-size:1.5rem;">✅</span>
                                <div>
                                    <div style="font-size:.88rem;font-weight:700;color:#166534;">Tidak Ada Kerusakan</div>
                                    <div style="font-size:.78rem;color:#4b7a5a;margin-top:.15rem;">
                                        Koleksi dikembalikan dalam kondisi baik sesuai saat diserahterimakan.
                                    </div>
                                </div>
                            </div>

                        @elseif(! empty($damageItems))
                            {{-- ── Ada kerusakan — detail per item ── --}}
                            <div style="display:flex;flex-direction:column;gap:.625rem;margin-bottom:1rem;">
                                @foreach($damageItems as $item)
                                    @php
                                        $level  = $item['level'] ?? 'ringan';
                                        $colors = $levelColors[$level] ?? $levelColors['ringan'];
                                    @endphp
                                    <div style="display:flex;align-items:flex-start;justify-content:space-between;
                                                gap:1rem;background:{{ $colors['bg'] }};
                                                border:1.5px solid {{ $colors['border'] }};
                                                border-radius:1rem;padding:.875rem 1.1rem;">
                                        <div style="flex:1;min-width:0;">
                                            <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-bottom:.25rem;">
                                                <span style="font-size:.88rem;font-weight:700;color:#1e293b;">
                                                    {{ $item['label'] ?? ucfirst($item['key'] ?? '-') }}
                                                </span>
                                                <span style="font-size:.68rem;font-weight:700;letter-spacing:.06em;
                                                            text-transform:uppercase;padding:.2rem .55rem;border-radius:99px;
                                                            background:{{ $colors['badge_bg'] }};color:{{ $colors['text'] }};">
                                                    {{ $levelLabels[$level] ?? ucfirst($level) }}
                                                </span>
                                            </div>
                                            @if(! empty($item['note']))
                                                <div style="font-size:.78rem;color:#64748b;">{{ $item['note'] }}</div>
                                            @endif
                                        </div>
                                        <div style="text-align:right;flex-shrink:0;">
                                            <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;
                                                        letter-spacing:.07em;color:#94a3b8;margin-bottom:.2rem;">Biaya</div>
                                            <div style="font-size:.95rem;font-weight:700;color:{{ $colors['text'] }};">
                                                Rp {{ number_format($item['cost'] ?? 0, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Total biaya --}}
                            <div style="background:linear-gradient(135deg,#0b1d35,#142744);border-radius:1rem;
                                        padding:1rem 1.25rem;display:flex;justify-content:space-between;align-items:center;
                                        margin-bottom:1rem;">
                                <span style="font-size:.75rem;font-weight:700;letter-spacing:.08em;
                                            text-transform:uppercase;color:rgba(255,255,255,.55);">
                                    Total Biaya Kerusakan
                                </span>
                                <span style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:#fff;">
                                    Rp {{ number_format($st->final_damage_cost ?? $st->damage_cost ?? 0, 0, ',', '.') }}
                                </span>
                            </div>

                        @else
                            {{-- ── Fallback: data lama sebelum sistem damage_items_detail ── --}}
                            <div style="padding:1rem 1.25rem;background:#fef2f2;border:1.5px solid #fecaca;border-radius:1rem;margin-bottom:1rem;">
                                <div style="font-size:.78rem;font-weight:700;color:#991b1b;margin-bottom:.35rem;">⚠ Kerusakan Ditemukan</div>
                                @if($st->final_damage_type)
                                    <div style="font-size:.83rem;color:#7f1d1d;">
                                        Jenis: {{ $st->final_damage_type }}
                                        @if($st->final_damage_level)
                                            · Tingkat: {{ ucfirst($st->final_damage_level) }}
                                        @endif
                                    </div>
                                @endif
                                @if(($st->final_damage_cost ?? $st->damage_cost) > 0)
                                    <div style="font-size:.9rem;font-weight:700;color:#dc2626;margin-top:.5rem;">
                                        Biaya: Rp {{ number_format($st->final_damage_cost ?? $st->damage_cost, 0, ',', '.') }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- ── Catatan pemeriksaan ── --}}
                        @if($st->return_condition_notes ?? $st->damage_notes)
                            <div class="st-catatan">
                                <div class="lbl">Catatan Pemeriksaan</div>
                                <div class="val">{{ $st->return_condition_notes ?? $st->damage_notes }}</div>
                            </div>
                        @endif

                        {{-- ── Preview foto kondisi ── --}}
                        @if($st->return_condition_photo_path)
                            <div style="margin-top:1rem;">
                                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;
                                            letter-spacing:.08em;color:#94a3b8;margin-bottom:.5rem;">
                                    Foto Dokumentasi Kerusakan
                                </div>
                                @php $photoExt = strtolower(pathinfo($st->return_condition_photo_path, PATHINFO_EXTENSION)); @endphp
                                @if(in_array($photoExt, ['jpg','jpeg','png']))
                                    <img src="{{ Storage::url($st->return_condition_photo_path) }}"
                                        alt="Foto Kondisi Koleksi"
                                        style="width:100%;max-width:480px;border-radius:.875rem;
                                                border:1.5px solid var(--border);display:block;cursor:zoom-in;"
                                        onclick="openReturnConditionLightbox(this.src, this.alt)">
                                    <div style="font-size:.7rem;color:#94a3b8;margin-top:.35rem;">
                                        Klik gambar untuk memperbesar.
                                    </div>
                                @else
                                    <a href="{{ Storage::url($st->return_condition_photo_path) }}"
                                    target="_blank" class="st-btn st-btn-ghost" style="font-size:.78rem;">
                                        📄 Lihat Dokumentasi Kondisi (PDF)
                                    </a>
                                @endif
                            </div>

                            {{-- Lightbox foto dokumentasi kondisi pengembalian --}}
                            <div id="return-condition-lightbox-overlay" onclick="closeReturnConditionLightbox(event)"
                                style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.85);backdrop-filter:blur(4px);align-items:center;justify-content:center;cursor:zoom-out;">
                                <span onclick="closeReturnConditionLightbox({target:this})"
                                    style="position:absolute;top:1.25rem;right:1.5rem;color:#fff;font-size:2rem;font-weight:300;cursor:pointer;line-height:1;opacity:.7;">&times;</span>
                                <img id="return-condition-lightbox-img" src="" alt=""
                                    style="max-width:90vw;max-height:90vh;border-radius:1rem;box-shadow:0 24px 80px rgba(0,0,0,.6);object-fit:contain;"
                                    onclick="event.stopPropagation()">
                            </div>
                            <script>
                                function openReturnConditionLightbox(src, alt) {
                                    const ov = document.getElementById('return-condition-lightbox-overlay');
                                    document.getElementById('return-condition-lightbox-img').src = src;
                                    document.getElementById('return-condition-lightbox-img').alt = alt || '';
                                    ov.style.display = 'flex';
                                    document.body.style.overflow = 'hidden';
                                }
                                function closeReturnConditionLightbox(e) {
                                    const ov  = document.getElementById('return-condition-lightbox-overlay');
                                    const img = document.getElementById('return-condition-lightbox-img');
                                    if (e.target === ov || e.target.tagName === 'SPAN') {
                                        ov.style.display = 'none';
                                        document.body.style.overflow = '';
                                        img.src = '';
                                    }
                                }
                                document.addEventListener('keydown', e => {
                                    if (e.key === 'Escape') {
                                        const ov = document.getElementById('return-condition-lightbox-overlay');
                                        if (ov && ov.style.display !== 'none') {
                                            ov.style.display = 'none';
                                            document.getElementById('return-condition-lightbox-img').src = '';
                                            document.body.style.overflow = '';
                                        }
                                    }
                                });
                            </script>
                        @endif

                    </div>
                </div>
            @endif

            {{-- Modal preview foto (sisi penyewa) --}}
            @if(!$isPengelola)
            <div id="photo-modal-penyewa" onclick="this.style.display='none'"
                style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);z-index:9999;
                        align-items:center;justify-content:center;cursor:zoom-out;">
                <img id="photo-modal-penyewa-img" src="" alt=""
                    style="max-width:90vw;max-height:90vh;border-radius:1rem;object-fit:contain;">
            </div>
            <script>
            function openPhotoModalPenyewa(src) {
                const modal = document.getElementById('photo-modal-penyewa');
                document.getElementById('photo-modal-penyewa-img').src = src;
                modal.style.display = 'flex';
            }
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('photo-modal-penyewa');
                    if (modal) modal.style.display = 'none';
                }
            });
            </script>
            @endif
</x-app-layout>