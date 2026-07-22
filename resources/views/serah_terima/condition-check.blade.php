<x-app-layout>
    <x-slot name="header">{{-- kosong, hero di dalam --}}</x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:   #0b1d35; --navy-2: #142744; --blue: #1d4ed8;
            --sky:    #38bdf8; --cream:  #f2f5f9; --slate: #64748b;
            --border: #e2e8f0; --white:  #ffffff;
        }
        * { box-sizing: border-box; }
        .cc-root { font-family:'DM Sans',sans-serif; background:var(--cream); min-height:100vh; padding-bottom:5rem; }

        /* ── HERO (sama dengan show.blade.php) ── */
        .cc-hero { background:linear-gradient(140deg,#0b1d35 0%,#142744 55%,#1c3a68 100%); padding:2.25rem 0; position:relative; overflow:hidden; }
        .cc-hero::before { content:''; position:absolute; top:-60px; right:-80px; width:400px; height:400px; border-radius:50%; background:radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events:none; }
        .cc-hero-inner { max-width:1100px; margin:0 auto; padding:0 2rem; position:relative; z-index:1; }
        .cc-hero-top { display:flex; align-items:flex-start; justify-content:space-between; gap:1.5rem; flex-wrap:wrap; }

        .cc-breadcrumb { display:flex; align-items:center; gap:.45rem; margin-bottom:.85rem; }
        .cc-breadcrumb a { color:rgba(255,255,255,.45); font-size:.75rem; font-weight:500; text-decoration:none; transition:color .15s; }
        .cc-breadcrumb a:hover { color:var(--sky); }
        .cc-breadcrumb-sep { color:rgba(255,255,255,.25); font-size:.7rem; }
        .cc-breadcrumb-cur { color:rgba(255,255,255,.7); font-size:.75rem; font-weight:600; }

        .cc-hero-id    { font-family:'Playfair Display',serif; font-size:1.75rem; font-weight:700; color:#fff; line-height:1.2; margin:0 0 .3rem; }
        .cc-hero-title { font-size:.88rem; color:rgba(255,255,255,.55); margin:0; }

        /* Status badge */
        .cc-status-badge { display:inline-flex; align-items:center; gap:.35rem; padding:.35rem 1rem; border-radius:99px; font-size:.72rem; font-weight:700; letter-spacing:.04em; margin-top:.75rem; background:rgba(251,191,36,.15); border:1px solid rgba(251,191,36,.3); color:#fbbf24; }
        .cc-status-dot { width:6px; height:6px; border-radius:50%; background:#fbbf24; }

        /* Hero action buttons */
        .cc-hero-actions { display:flex; gap:.6rem; flex-wrap:wrap; align-items:flex-start; padding-top:.25rem; }
        .cc-hero-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.6rem 1.2rem; border-radius:.875rem; font-size:.8rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .18s; border:none; cursor:pointer; white-space:nowrap; }
        .cc-hero-btn svg { width:13px; height:13px; }
        .cc-hero-btn-back  { background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15); color:rgba(255,255,255,.85); }
        .cc-hero-btn-back:hover  { background:rgba(255,255,255,.17); }
        .cc-hero-btn-track { background:rgba(56,189,248,.15); border:1px solid rgba(56,189,248,.3); color:var(--sky); }
        .cc-hero-btn-track:hover { background:rgba(56,189,248,.25); }

        /* ── CONTENT ── */
        .cc-content { max-width:1100px; margin:0 auto; padding:1.75rem 2rem 0; display:grid; gap:1.25rem; }

        /* FLASH */
        .cc-flash { border-radius:.875rem; padding:.85rem 1.2rem; font-size:.83rem; font-weight:600; display:flex; align-items:center; gap:.55rem; animation:flashIn .35s ease; }
        @keyframes flashIn { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:none} }
        .cc-flash svg { width:16px; height:16px; flex-shrink:0; }
        .cc-flash.ok  { background:#d1fae5; border:1px solid #6ee7b7; color:#065f46; }
        .cc-flash.err { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; }

        /* CARD (sama dengan show.blade.php .st-card) */
        .cc-card { background:var(--white); border:1.5px solid var(--border); border-radius:1.5rem; box-shadow:0 4px 24px rgba(11,29,53,.05); overflow:hidden; }
        .cc-card-header { padding:1.1rem 1.5rem; border-bottom:1.5px solid #f0f4f8; display:flex; align-items:center; gap:.55rem; }
        .cc-card-header-accent { width:3px; height:16px; background:linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius:99px; flex-shrink:0; }
        .cc-card-header h3 { font-size:.76rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--navy); margin:0; }
        .cc-card-body { padding:1.5rem; }

        /* SECTION (sama dengan .st-section di show.blade.php) */
        .cc-section { border-radius:1.25rem; padding:1.5rem; }
        .cc-section .cc-eyebrow { font-size:.67rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; margin-bottom:.5rem; }
        .cc-section h2 { font-family:'Playfair Display',serif; font-size:1.3rem; color:var(--navy); margin:0 0 .5rem; }
        .cc-section p  { font-size:.84rem; line-height:1.7; color:#475569; margin:0; }
        .cc-section-amber { background:#fffbeb; border:1.5px solid #fde68a; }
        .cc-section-amber .cc-eyebrow { color:#d97706; }
        .cc-section-red   { background:#fef2f2; border:1.5px solid #fecaca; }
        .cc-section-red   .cc-eyebrow { color:#dc2626; }

        /* INFO BOX */
        .cc-info-box { background:#f0f9ff; border:1.5px solid #bae6fd; border-radius:1rem; padding:.9rem 1.1rem; font-size:.8rem; color:#0369a1; line-height:1.65; }
        .cc-info-box strong { color:#0284c7; }

        /* PILIHAN UTAMA */
        .cc-choice-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        @media(max-width:600px){ .cc-choice-grid { grid-template-columns:1fr; } }

        .cc-choice-panel { border-radius:1.25rem; padding:1.5rem; cursor:pointer; transition:all .2s; border:2px solid transparent; position:relative; }
        .cc-choice-panel.good   { background:linear-gradient(135deg,#f0fdf4,#dcfce7); border-color:#bbf7d0; }
        .cc-choice-panel.good:hover  { border-color:#4ade80; box-shadow:0 4px 20px rgba(74,222,128,.15); }
        .cc-choice-panel.damage { background:linear-gradient(135deg,#fef2f2,#fee2e2); border-color:#fecaca; }
        .cc-choice-panel.damage:hover { border-color:#f87171; box-shadow:0 4px 20px rgba(248,113,113,.15); }

        .cc-choice-icon { width:48px; height:48px; border-radius:1rem; display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin-bottom:.85rem; }
        .cc-choice-panel.good   .cc-choice-icon { background:#d1fae5; }
        .cc-choice-panel.damage .cc-choice-icon { background:#fee2e2; }

        .cc-choice-title { font-family:'Playfair Display',serif; font-size:1.1rem; color:var(--navy); margin:0 0 .35rem; font-weight:700; }
        .cc-choice-desc  { font-size:.8rem; color:#475569; line-height:1.6; margin:0 0 1rem; }

        .cc-choice-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.6rem 1.25rem; border-radius:.875rem; font-size:.82rem; font-weight:600; font-family:'DM Sans',sans-serif; border:none; cursor:pointer; transition:all .18s; }
        .cc-choice-panel.good   .cc-choice-btn { background:linear-gradient(135deg,#059669,#10b981); color:#fff; }
        .cc-choice-panel.good   .cc-choice-btn:hover { box-shadow:0 4px 14px rgba(16,185,129,.35); transform:translateY(-1px); }
        .cc-choice-panel.damage .cc-choice-btn { background:linear-gradient(135deg,#dc2626,#ef4444); color:#fff; }
        .cc-choice-panel.damage .cc-choice-btn:hover { box-shadow:0 4px 14px rgba(239,68,68,.35); transform:translateY(-1px); }

        /* FORM SECTIONS */
        .cc-form-section { display:none; }
        .cc-form-section.active { display:block; animation:fadeIn .25s ease; }
        @keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

        /* FORM ELEMENTS */
        .cc-label { display:block; font-size:.78rem; font-weight:700; color:var(--navy); margin-bottom:.4rem; }
        .cc-label span.req  { color:#ef4444; margin-left:.2rem; }
        .cc-label span.hint { font-weight:400; color:var(--slate); font-size:.72rem; }

        .cc-input { width:100%; border:1.5px solid var(--border); border-radius:.75rem; padding:.7rem .9rem; font-size:.85rem; font-family:'DM Sans',sans-serif; color:var(--navy); background:var(--white); transition:border-color .15s; }
        .cc-input:focus { outline:none; border-color:var(--blue); box-shadow:0 0 0 3px rgba(29,78,216,.08); }
        .cc-textarea { resize:vertical; min-height:90px; }

        .cc-error { font-size:.72rem; color:#dc2626; margin:.3rem 0 0; display:flex; align-items:center; gap:.3rem; }

        /* UPLOAD BOX */
        .cc-upload-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        @media(max-width:560px){ .cc-upload-grid { grid-template-columns:1fr; } }

        .cc-upload-box { border:2px dashed var(--border); border-radius:1rem; padding:1.25rem 1rem; text-align:center; transition:border-color .15s; background:#fafbfc; }
        .cc-upload-box:hover { border-color:var(--blue); background:#f8faff; }
        .cc-upload-box .cc-upload-icon  { font-size:1.6rem; margin-bottom:.5rem; }
        .cc-upload-box .cc-upload-label { font-size:.8rem; font-weight:700; color:var(--navy); margin-bottom:.2rem; }
        .cc-upload-box .cc-upload-hint  { font-size:.7rem; color:var(--slate); margin-bottom:.65rem; }
        .cc-upload-box input[type=file]  { width:100%; font-size:.75rem; font-family:'DM Sans',sans-serif; }

        /* MULTI UPLOAD */
        .cc-multi-upload { border:2px dashed var(--border); border-radius:1rem; padding:1.25rem; background:#fafbfc; transition:border-color .15s; }
        .cc-multi-upload:hover { border-color:var(--blue); }
        .cc-multi-upload .cc-upload-icon  { font-size:1.6rem; margin-bottom:.5rem; text-align:center; }
        .cc-multi-upload .cc-upload-label { font-size:.82rem; font-weight:700; color:var(--navy); margin-bottom:.25rem; text-align:center; }
        .cc-multi-upload .cc-upload-hint  { font-size:.72rem; color:var(--slate); text-align:center; margin-bottom:.75rem; }
        .cc-multi-upload input[type=file]  { width:100%; font-size:.78rem; font-family:'DM Sans',sans-serif; }

        /* CHECKLIST */
        .cc-checklist-grid { display:grid; grid-template-columns:1fr 1fr; gap:.6rem; }
        @media(max-width:560px){ .cc-checklist-grid { grid-template-columns:1fr; } }

        .cc-check-item { display:flex; align-items:flex-start; gap:.65rem; background:#f8fafc; border:1.5px solid var(--border); border-radius:.875rem; padding:.85rem 1rem; cursor:pointer; transition:all .15s; }
        .cc-check-item:hover { border-color:#93c5fd; background:#eff6ff; }
        .cc-check-item.checked { border-color:#3b82f6; background:#eff6ff; }
        .cc-check-item input[type=checkbox] { width:16px; height:16px; margin-top:.1rem; flex-shrink:0; accent-color:var(--blue); cursor:pointer; }
        .cc-check-item-label { font-size:.82rem; color:var(--navy); font-weight:500; line-height:1.4; }

        /* SEVERITY */
        .cc-severity-grid { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }

        .cc-severity-card { border:2px solid var(--border); border-radius:1rem; padding:1rem; cursor:pointer; transition:all .15s; position:relative; }
        .cc-severity-card input[type=radio] { position:absolute; opacity:0; pointer-events:none; }
        .cc-severity-card.ringan:hover,
        .cc-severity-card.ringan.selected { border-color:#f59e0b; background:#fffbeb; }
        .cc-severity-card.parah:hover,
        .cc-severity-card.parah.selected  { border-color:#ef4444; background:#fef2f2; }
        .cc-severity-icon  { font-size:1.4rem; margin-bottom:.4rem; }
        .cc-severity-title { font-size:.88rem; font-weight:700; color:var(--navy); margin-bottom:.2rem; }
        .cc-severity-desc  { font-size:.74rem; color:var(--slate); line-height:1.5; }
        .cc-severity-card.ringan.selected .cc-severity-title { color:#d97706; }
        .cc-severity-card.parah.selected  .cc-severity-title { color:#dc2626; }

        /* DECISION */
        .cc-decision-grid { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }

        .cc-decision-card { border:2px solid var(--border); border-radius:1rem; padding:1rem 1.1rem; cursor:pointer; transition:all .15s; position:relative; }
        .cc-decision-card input[type=radio] { position:absolute; opacity:0; pointer-events:none; }
        .cc-decision-card.lanjut:hover,
        .cc-decision-card.lanjut.selected { border-color:#10b981; background:#f0fdf4; }
        .cc-decision-card.batal:hover,
        .cc-decision-card.batal.selected  { border-color:#ef4444; background:#fef2f2; }
        .cc-decision-icon  { font-size:1.25rem; margin-bottom:.4rem; }
        .cc-decision-title { font-size:.88rem; font-weight:700; color:var(--navy); margin-bottom:.15rem; }
        .cc-decision-desc  { font-size:.74rem; color:var(--slate); line-height:1.5; }
        .cc-decision-card.lanjut.selected .cc-decision-title { color:#059669; }
        .cc-decision-card.batal.selected  .cc-decision-title { color:#dc2626; }

        /* SUBMIT BTN */
        .cc-submit-row { display:flex; gap:.75rem; flex-wrap:wrap; margin-top:.5rem; align-items:center; }
        .cc-btn { display:inline-flex; align-items:center; gap:.45rem; padding:.7rem 1.5rem; border-radius:.875rem; font-size:.85rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .2s; border:none; cursor:pointer; }
        .cc-btn-emerald { background:linear-gradient(135deg,#059669,#10b981); color:#fff; }
        .cc-btn-emerald:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(16,185,129,.35); }
        .cc-btn-red { background:linear-gradient(135deg,#dc2626,#ef4444); color:#fff; }
        .cc-btn-red:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(239,68,68,.35); }
        .cc-btn-ghost { background:transparent; border:1.5px solid var(--border); color:var(--slate); }
        .cc-btn-ghost:hover { background:#f8fafc; }

        /* DIVIDER */
        .cc-divider { height:1.5px; background:var(--border); border-radius:99px; margin:1.25rem 0; }

        /* SECTION TITLE */
        .cc-section-title { font-size:.72rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--slate); margin:0 0 .85rem; display:flex; align-items:center; gap:.5rem; }
        .cc-section-title::after { content:''; flex:1; height:1px; background:var(--border); }

        /* ALREADY REPORTED */
        .cc-reported-section { background:#fffbeb; border:1.5px solid #fde68a; border-radius:1.25rem; padding:1.5rem; }
        .cc-reported-section .eyebrow { font-size:.67rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:#d97706; margin-bottom:.5rem; }
        .cc-reported-section h2 { font-family:'Playfair Display',serif; font-size:1.2rem; color:var(--navy); margin:0 0 .5rem; }
        .cc-reported-section p { font-size:.84rem; color:#475569; margin:0; line-height:1.7; }

        .cc-damage-summary { margin-top:1rem; display:grid; gap:.6rem; }
        .cc-damage-tag { display:inline-flex; align-items:center; gap:.35rem; background:var(--white); border:1.5px solid #fde68a; border-radius:.6rem; padding:.3rem .75rem; font-size:.76rem; font-weight:600; color:#92400e; }

        @media(max-width:768px){
            .cc-content { padding:1.25rem 1rem 0; }
            .cc-hero-inner { padding:0 1rem; }
        }
    </style>

    <div class="cc-root">

        {{-- ── HERO (seragam dengan show.blade.php) ── --}}
        <div class="cc-hero">
            <div class="cc-hero-inner">
                <div class="cc-hero-top">
                    <div>
                        <div class="cc-breadcrumb">
                            <a href="{{ route('penyewaan.requests') }}">Pengajuan Saya</a>
                            <span class="cc-breadcrumb-sep">/</span>
                            <a href="{{ route('penyewaan.requests.show', $penyewaan) }}">SWA-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</a>
                            <span class="cc-breadcrumb-sep">/</span>
                            <a href="{{ route('penyewaan.requests.handover.show', $penyewaan) }}">Serah Terima</a>
                            <span class="cc-breadcrumb-sep">/</span>
                            <span class="cc-breadcrumb-cur">Cek Kondisi</span>
                        </div>

                        <h1 class="cc-hero-id">Pengecekan Kondisi Koleksi</h1>
                        <p class="cc-hero-title">{{ $penyewaan->painting->title }} &mdash; {{ $penyewaan->painting->artist ?? '' }}</p>

                        <div class="cc-status-badge">
                            <span class="cc-status-dot"></span>
                            Pengecekan Kondisi
                            &nbsp;·&nbsp;
                            <span style="opacity:.75;">
                                @if($isKurir) via Kurir @else via Pengelola @endif
                            </span>
                        </div>
                    </div>

                    <div class="cc-hero-actions">
                        <a href="{{ route('penyewaan.requests.handover.show', $penyewaan) }}"
                           class="cc-hero-btn cc-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Serah Terima
                        </a>
                        <a href="{{ route('penyewaan.requests.handover.track', $penyewaan) }}"
                           class="cc-hero-btn cc-hero-btn-track">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            Lihat Timeline
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="cc-content">

            {{-- FLASH --}}
            @if(session('success'))
                <div class="cc-flash ok">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="cc-flash err">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="cc-flash err">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
            @endif

            {{-- ── Sudah lapor kerusakan — tampilkan ringkasan ── --}}
            @if($serahTerima->handover_status === 'damage_reported')
            <div class="cc-reported-section">
                <div class="eyebrow">⏳ Menunggu Pengelola</div>
                <h2>Laporan Kerusakan Sudah Terkirim</h2>
                <p>
                    Laporan kerusakan kamu sudah diterima pengelola pada
                    {{ $serahTerima->arrival_damage_reported_at?->format('d M Y, H:i') ?? '-' }}.
                    Tunggu keputusan pengelola sebelum proses dilanjutkan.
                </p>

                @php
                    $checkedItems = $serahTerima->getCheckedDamageItems();
                    $severityLabel = $serahTerima->arrival_damage_severity === 'parah' ? '🔴 Parah' : '🟡 Ringan';
                    $decisionLabel = $serahTerima->arrival_damage_tenant_decision === 'lanjutkan'
                        ? '✅ Ingin melanjutkan sewa'
                        : '❌ Ingin membatalkan sewa';
                @endphp

                <div class="cc-damage-summary">
                    <div style="font-size:.72rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.08em;">Kerusakan yang Dilaporkan</div>
                    <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                        @foreach($checkedItems as $item)
                            <span class="cc-damage-tag">⚠ {{ $item }}</span>
                        @endforeach
                    </div>
                    <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-top:.25rem;">
                        <span style="font-size:.78rem;background:var(--white);border:1.5px solid var(--border);border-radius:.6rem;padding:.3rem .75rem;color:var(--navy);font-weight:600;">
                            Tingkat: {{ $severityLabel }}
                        </span>
                        <span style="font-size:.78rem;background:var(--white);border:1.5px solid var(--border);border-radius:.6rem;padding:.3rem .75rem;color:var(--navy);font-weight:600;">
                            {{ $decisionLabel }}
                        </span>
                    </div>
                </div>

                <div style="margin-top:1rem;">
                    <a href="{{ route('penyewaan.requests.show', $penyewaan) }}" class="cc-btn cc-btn-ghost" style="font-size:.8rem;">
                        ← Kembali ke Detail Pengajuan
                    </a>
                </div>
            </div>

            {{-- ── Belum cek kondisi — tampilkan pilihan ── --}}
            @else

            {{-- Panduan singkat --}}
            <div class="cc-info-box">
                <strong>📋 Langkah pengecekan:</strong>
                Periksa kondisi koleksi secara menyeluruh — bagian depan, belakang, bingkai, dan kaca pelindung.
                Jika kondisi baik, upload foto dan lanjutkan ke dokumen serah terima.
                Jika ada kerusakan, isi form laporan dan kirim ke pengelola.
            </div>

            {{-- ── PILIHAN UTAMA ── --}}
            <div class="cc-card">
                <div class="cc-card-header">
                    <div class="cc-card-header-accent"></div>
                    <h3>Kondisi Koleksi Saat Diterima</h3>
                </div>
                <div class="cc-card-body">
                    <div class="cc-choice-grid">

                        {{-- Kondisi Baik --}}
                        <div class="cc-choice-panel good" onclick="showForm('good')">
                            <div class="cc-choice-icon">✅</div>
                            <div class="cc-choice-title">Kondisi Baik</div>
                            <div class="cc-choice-desc">
                                Koleksi tiba dalam kondisi sempurna. Tidak ada kerusakan, goresan, atau cacat yang terlihat.
                            </div>
                            <button type="button" class="cc-choice-btn" onclick="showForm('good')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                Konfirmasi Kondisi Baik
                            </button>
                        </div>

                        {{-- Ada Kerusakan --}}
                        <div class="cc-choice-panel damage" onclick="showForm('damage')">
                            <div class="cc-choice-icon">⚠️</div>
                            <div class="cc-choice-title">Ada Kerusakan</div>
                            <div class="cc-choice-desc">
                                Ditemukan kerusakan, goresan, retak, atau cacat pada koleksi. Isi form laporan untuk pengelola.
                            </div>
                            <button type="button" class="cc-choice-btn" onclick="showForm('damage')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
                                Laporkan Kerusakan
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ════ FORM KONDISI BAIK ════ --}}
            <div id="form-good" class="cc-form-section">
                <div class="cc-card">
                    <div class="cc-card-header">
                        <div class="cc-card-header-accent" style="background:linear-gradient(180deg,#059669,#10b981);"></div>
                        <h3>Upload Foto Kondisi Koleksi</h3>
                    </div>
                    <div class="cc-card-body">
                        <form action="{{ route('penyewaan.requests.handover.condition-good', $penyewaan) }}"
                              method="POST" enctype="multipart/form-data">
                            @csrf

                            <p style="font-size:.84rem;color:#475569;margin:0 0 1.25rem;line-height:1.7;">
                                Upload foto koleksi dari dua sisi sebagai dokumentasi kondisi saat diterima.
                                Foto ini akan menjadi catatan resmi kondisi awal koleksi selama masa sewa.
                            </p>

                            <div class="cc-upload-grid">
                                <div>
                                    <label class="cc-label">
                                        Foto Depan Koleksi <span class="req">*</span>
                                        <span class="hint">— tampak muka, seluruh koleksi terlihat</span>
                                    </label>
                                    <div class="cc-upload-box">
                                        <div class="cc-upload-icon">🖼️</div>
                                        <div class="cc-upload-label">Foto Tampak Depan</div>
                                        <div class="cc-upload-hint">JPG, PNG · Maks 5MB</div>
                                        <input type="file" name="condition_front_photo"
                                               accept="image/jpeg,image/jpg,image/png" required
                                               onchange="previewImage(this, 'prev-front')">
                                    </div>
                                    <img id="prev-front" src="" alt="" style="display:none;width:100%;max-height:180px;object-fit:cover;border-radius:.75rem;margin-top:.5rem;border:1.5px solid var(--border);">
                                    @error('condition_front_photo')
                                        <p class="cc-error">⚠ {{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="cc-label">
                                        Foto Belakang Koleksi <span class="req">*</span>
                                        <span class="hint">— tampak belakang, seluruh koleksi terlihat</span>
                                    </label>
                                    <div class="cc-upload-box">
                                        <div class="cc-upload-icon">🖼️</div>
                                        <div class="cc-upload-label">Foto Tampak Belakang</div>
                                        <div class="cc-upload-hint">JPG, PNG · Maks 5MB</div>
                                        <input type="file" name="condition_back_photo"
                                               accept="image/jpeg,image/jpg,image/png" required
                                               onchange="previewImage(this, 'prev-back')">
                                    </div>
                                    <img id="prev-back" src="" alt="" style="display:none;width:100%;max-height:180px;object-fit:cover;border-radius:.75rem;margin-top:.5rem;border:1.5px solid var(--border);">
                                    @error('condition_back_photo')
                                        <p class="cc-error">⚠ {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="cc-divider"></div>

                            <div class="cc-submit-row">
                                <button type="submit"
                                        onclick="return confirm('Konfirmasi bahwa koleksi diterima dalam kondisi baik dan foto sudah diupload?')"
                                        class="cc-btn cc-btn-emerald">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    Konfirmasi Kondisi Baik & Lanjut
                                </button>
                                <button type="button" class="cc-btn cc-btn-ghost" onclick="hideForm()">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ════ FORM LAPORAN KERUSAKAN ════ --}}
            <div id="form-damage" class="cc-form-section">
                <div class="cc-card">
                    <div class="cc-card-header">
                        <div class="cc-card-header-accent" style="background:linear-gradient(180deg,#dc2626,#ef4444);"></div>
                        <h3>Form Laporan Kerusakan</h3>
                    </div>
                    <div class="cc-card-body">
                        <form action="{{ route('penyewaan.requests.handover.condition-damage', $penyewaan) }}"
                              method="POST" enctype="multipart/form-data" id="damage-form">
                            @csrf

                            {{-- 1. Foto kondisi depan & belakang --}}
                            <div class="cc-section-title">1. Foto Kondisi Koleksi Saat Terima</div>

                            <div class="cc-upload-grid" style="margin-bottom:1.25rem;">
                                <div>
                                    <label class="cc-label">Foto Depan <span class="req">*</span></label>
                                    <div class="cc-upload-box">
                                        <div class="cc-upload-icon">📷</div>
                                        <div class="cc-upload-label">Tampak Depan</div>
                                        <div class="cc-upload-hint">JPG, PNG · Maks 5MB</div>
                                        <input type="file" name="arrival_condition_front_photo"
                                               accept="image/jpeg,image/jpg,image/png" required
                                               onchange="previewImage(this, 'dmg-prev-front')">
                                    </div>
                                    <img id="dmg-prev-front" src="" alt="" style="display:none;width:100%;max-height:160px;object-fit:cover;border-radius:.75rem;margin-top:.5rem;border:1.5px solid var(--border);">
                                    @error('arrival_condition_front_photo')
                                        <p class="cc-error">⚠ {{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="cc-label">Foto Belakang <span class="req">*</span></label>
                                    <div class="cc-upload-box">
                                        <div class="cc-upload-icon">📷</div>
                                        <div class="cc-upload-label">Tampak Belakang</div>
                                        <div class="cc-upload-hint">JPG, PNG · Maks 5MB</div>
                                        <input type="file" name="arrival_condition_back_photo"
                                               accept="image/jpeg,image/jpg,image/png" required
                                               onchange="previewImage(this, 'dmg-prev-back')">
                                    </div>
                                    <img id="dmg-prev-back" src="" alt="" style="display:none;width:100%;max-height:160px;object-fit:cover;border-radius:.75rem;margin-top:.5rem;border:1.5px solid var(--border);">
                                    @error('arrival_condition_back_photo')
                                        <p class="cc-error">⚠ {{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- 2. Checklist jenis kerusakan --}}
                            <div class="cc-section-title">2. Jenis Kerusakan yang Ditemukan</div>
                            <p style="font-size:.8rem;color:var(--slate);margin:0 0 .85rem;">Pilih semua jenis kerusakan yang ada.</p>

                            @error('arrival_damage_checklist')
                                <p class="cc-error" style="margin-bottom:.5rem;">⚠ {{ $message }}</p>
                            @enderror

                            <div class="cc-checklist-grid" style="margin-bottom:1.25rem;">
                                @foreach($damageChecklistItems as $key => $label)
                                <label class="cc-check-item" id="check-{{ $key }}">
                                    <input type="checkbox"
                                           name="arrival_damage_checklist[{{ $key }}]"
                                           value="{{ $key }}"
                                           onchange="toggleChecked('check-{{ $key }}', this)">
                                    <span class="cc-check-item-label">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>

                            {{-- 3. Foto / video kerusakan --}}
                            <div class="cc-section-title">3. Foto / Video Bukti Kerusakan</div>

                            <div class="cc-multi-upload" style="margin-bottom:1.25rem;">
                                <div class="cc-upload-icon" style="text-align:center;">📸</div>
                                <div class="cc-upload-label">Upload Foto atau Video Kerusakan</div>
                                <div class="cc-upload-hint">
                                    Minimal 1, maksimal 5 file · JPG, PNG, MP4, MOV · Maks 20MB per file<br>
                                    Ambil dari berbagai sudut agar kerusakan terlihat jelas
                                </div>
                                <input type="file" name="arrival_damage_photos[]"
                                       accept="image/jpeg,image/jpg,image/png,video/mp4,video/quicktime"
                                       multiple required
                                       onchange="showFileCount(this, 'dmg-file-count')">
                                <div id="dmg-file-count" style="font-size:.75rem;color:var(--slate);margin-top:.4rem;text-align:center;"></div>
                            </div>
                            @error('arrival_damage_photos')
                                <p class="cc-error" style="margin-bottom:.5rem;">⚠ {{ $message }}</p>
                            @enderror
                            @error('arrival_damage_photos.*')
                                <p class="cc-error" style="margin-bottom:.5rem;">⚠ {{ $message }}</p>
                            @enderror

                            {{-- 4. Deskripsi --}}
                            <div class="cc-section-title">4. Deskripsi Kerusakan</div>
                            <div style="margin-bottom:1.25rem;">
                                <label class="cc-label">
                                    Ceritakan kerusakan yang kamu temukan
                                    <span class="hint">— opsional, tapi membantu pengelola memahami situasi</span>
                                </label>
                                <textarea name="arrival_damage_description"
                                          class="cc-input cc-textarea"
                                          placeholder="Contoh: Bingkai bagian kanan bawah retak sekitar 5cm, terlihat seperti akibat benturan saat pengiriman...">{{ old('arrival_damage_description') }}</textarea>
                            </div>

                            {{-- 5. Tingkat keparahan --}}
                            <div class="cc-section-title">5. Tingkat Keparahan</div>
                            @error('arrival_damage_severity')
                                <p class="cc-error" style="margin-bottom:.5rem;">⚠ {{ $message }}</p>
                            @enderror
                            <div class="cc-severity-grid" style="margin-bottom:1.25rem;">
                                <label class="cc-severity-card ringan" id="sev-ringan" onclick="selectSeverity('ringan')">
                                    <input type="radio" name="arrival_damage_severity" value="ringan"
                                           {{ old('arrival_damage_severity') === 'ringan' ? 'checked' : '' }}>
                                    <div class="cc-severity-icon">🟡</div>
                                    <div class="cc-severity-title">Ringan</div>
                                    <div class="cc-severity-desc">Kerusakan kecil, koleksi masih dapat digunakan. Misalnya: goresan halus, noda kecil.</div>
                                </label>
                                <label class="cc-severity-card parah" id="sev-parah" onclick="selectSeverity('parah')">
                                    <input type="radio" name="arrival_damage_severity" value="parah"
                                           {{ old('arrival_damage_severity') === 'parah' ? 'checked' : '' }}>
                                    <div class="cc-severity-icon">🔴</div>
                                    <div class="cc-severity-title">Parah</div>
                                    <div class="cc-severity-desc">Kerusakan signifikan yang mempengaruhi nilai atau tampilan koleksi. Misalnya: retak, sobek, pecah.</div>
                                </label>
                            </div>

                            {{-- 6. Keputusan penyewa --}}
                            <div class="cc-section-title">6. Keputusan Kamu</div>
                            @error('arrival_damage_tenant_decision')
                                <p class="cc-error" style="margin-bottom:.5rem;">⚠ {{ $message }}</p>
                            @enderror

                            <div id="decision-parah-note" style="display:none;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.875rem;padding:.85rem 1rem;font-size:.8rem;color:#991b1b;margin-bottom:.75rem;">
                                ⚠ Kerusakan parah wajib dikembalikan — sewa tidak dapat dilanjutkan. Koleksi harus segera dikembalikan ke museum.
                            </div>

                            <div class="cc-decision-grid" style="margin-bottom:1.5rem;">
                                <label class="cc-decision-card lanjut" id="dec-lanjut" onclick="selectDecision('lanjutkan')">
                                    <input type="radio" name="arrival_damage_tenant_decision" value="lanjutkan"
                                           id="decision-lanjutkan"
                                           {{ old('arrival_damage_tenant_decision') === 'lanjutkan' ? 'checked' : '' }}>
                                    <div class="cc-decision-icon">✅</div>
                                    <div class="cc-decision-title">Lanjutkan Sewa</div>
                                    <div class="cc-decision-desc">Kerusakan ringan, saya bersedia melanjutkan penyewaan. Kerusakan akan dicatat sebagai kondisi awal.</div>
                                </label>
                                <label class="cc-decision-card batal" id="dec-batal" onclick="selectDecision('batalkan')">
                                    <input type="radio" name="arrival_damage_tenant_decision" value="batalkan"
                                           {{ old('arrival_damage_tenant_decision') === 'batalkan' ? 'checked' : '' }}>
                                    <div class="cc-decision-icon">❌</div>
                                    <div class="cc-decision-title">Batalkan Sewa</div>
                                    <div class="cc-decision-desc">Saya tidak bersedia melanjutkan sewa karena kondisi kerusakan. Pengelola akan memproses pembatalan.</div>
                                </label>
                            </div>

                            <div class="cc-divider"></div>

                            <div class="cc-submit-row">
                                <button type="submit" id="damage-submit-btn"
                                        onclick="return confirmDamageSubmit()"
                                        class="cc-btn cc-btn-red">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
                                    Kirim Laporan Kerusakan
                                </button>
                                <button type="button" class="cc-btn cc-btn-ghost" onclick="hideForm()">Batal</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            @endif {{-- end @if damage_reported --}}

        </div>{{-- /cc-content --}}
    </div>

    <script>
        function showForm(type) {
            document.querySelectorAll('.cc-form-section').forEach(el => el.classList.remove('active'));
            const target = document.getElementById('form-' + type);
            if (target) {
                target.classList.add('active');
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function hideForm() {
            document.querySelectorAll('.cc-form-section').forEach(el => el.classList.remove('active'));
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function showFileCount(input, countId) {
            const countEl = document.getElementById(countId);
            if (!countEl) return;
            const count = input.files.length;
            if (count === 0) {
                countEl.textContent = '';
            } else if (count > 5) {
                countEl.textContent = `⚠ Maksimal 5 file. Kamu memilih ${count} file.`;
                countEl.style.color = '#dc2626';
            } else {
                countEl.textContent = `✓ ${count} file dipilih`;
                countEl.style.color = '#059669';
            }
        }

        function toggleChecked(containerId, checkbox) {
            const container = document.getElementById(containerId);
            if (container) container.classList.toggle('checked', checkbox.checked);
        }

        function selectSeverity(value) {
            document.querySelectorAll('.cc-severity-card').forEach(el => el.classList.remove('selected'));
            const target = document.getElementById('sev-' + value);
            if (target) target.classList.add('selected');
            const note = document.getElementById('decision-parah-note');
            const lanjutCard = document.getElementById('dec-lanjut');
            const lanjutInput = document.getElementById('decision-lanjutkan');
            const batalInput = document.querySelector('#dec-batal input[type=radio]');
            if (note) note.style.display = value === 'parah' ? 'block' : 'none';
            if (value === 'parah') {
                if (lanjutCard) { lanjutCard.style.opacity = '0.45'; lanjutCard.style.pointerEvents = 'none'; }
                if (lanjutInput) { lanjutInput.checked = false; lanjutInput.disabled = true; }
                if (batalInput) { batalInput.checked = true; selectDecision('batalkan'); }
            } else {
                if (lanjutCard) { lanjutCard.style.opacity = '1'; lanjutCard.style.pointerEvents = 'auto'; }
                if (lanjutInput) lanjutInput.disabled = false;
            }
        }

        function selectDecision(value) {
            document.querySelectorAll('.cc-decision-card').forEach(el => el.classList.remove('selected'));
            const map = { 'lanjutkan': 'lanjut', 'batalkan': 'batal' };
            const target = document.getElementById('dec-' + map[value]);
            if (target) target.classList.add('selected');
        }

        function confirmDamageSubmit() {
            const checked = document.querySelectorAll('#damage-form input[type=checkbox]:checked');
            if (checked.length === 0) {
                alert('Pilih minimal satu jenis kerusakan yang ditemukan.');
                return false;
            }
            const severity = document.querySelector('#damage-form input[name="arrival_damage_severity"]:checked');
            if (!severity) {
                alert('Pilih tingkat keparahan kerusakan.');
                return false;
            }
            const decision = document.querySelector('#damage-form input[name="arrival_damage_tenant_decision"]:checked');
            if (!decision) {
                alert('Pilih keputusan kamu: lanjutkan atau batalkan sewa.');
                return false;
            }
            if (severity.value === 'parah' && decision.value === 'lanjutkan') {
                alert('Kerusakan parah wajib dikembalikan — tidak dapat melanjutkan sewa.');
                return false;
            }
            const decisionText = decision.value === 'lanjutkan'
                ? 'melanjutkan penyewaan meskipun ada kerusakan'
                : 'membatalkan sewa karena kerusakan';
            return confirm(
                `Kamu akan mengirim laporan kerusakan dengan keputusan: ${decisionText}.\n\n` +
                `Laporan ini tidak dapat diubah setelah dikirim. Lanjutkan?`
            );
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if($errors->any())
                @if($errors->has('arrival_damage_checklist') ||
                    $errors->has('arrival_damage_photos') ||
                    $errors->has('arrival_damage_severity') ||
                    $errors->has('arrival_damage_tenant_decision') ||
                    $errors->has('arrival_condition_front_photo') ||
                    $errors->has('arrival_condition_back_photo'))
                    showForm('damage');
                @elseif($errors->has('condition_front_photo') || $errors->has('condition_back_photo'))
                    showForm('good');
                @endif
            @endif

            const oldSeverity = '{{ old('arrival_damage_severity') }}';
            if (oldSeverity) selectSeverity(oldSeverity);

            const oldDecision = '{{ old('arrival_damage_tenant_decision') }}';
            if (oldDecision) selectDecision(oldDecision);

            document.querySelectorAll('#damage-form input[type=checkbox]').forEach(cb => {
                if (cb.checked) {
                    const containerId = cb.closest('.cc-check-item')?.id;
                    if (containerId) document.getElementById(containerId)?.classList.add('checked');
                }
            });
        });
    </script>

</x-app-layout>