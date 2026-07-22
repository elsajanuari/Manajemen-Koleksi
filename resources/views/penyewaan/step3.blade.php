<x-app-layout>
    <x-slot name="header">{{-- empty --}}</x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:   #0b1d35; --navy-2: #142744; --blue: #1d4ed8;
            --sky:    #38bdf8; --cream:  #f2f5f9; --slate: #64748b;
            --border: #e2e8f0; --white:  #ffffff;
        }
        * { box-sizing: border-box; }
        .pb-root { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; padding-bottom: 4rem; }

        /* ── HERO ── */
        .pb-hero { background: linear-gradient(140deg,#0b1d35 0%,#142744 55%,#1c3a68 100%); padding: 2.25rem 0; position: relative; overflow: hidden; }
        .pb-hero::before { content: ''; position: absolute; top: -60px; right: -80px; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events: none; }
        .pb-hero::after  { content: ''; position: absolute; bottom: -80px; left: -40px; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle,rgba(29,78,216,.06) 0%,transparent 70%); pointer-events: none; }
        .pb-hero-inner { max-width: 1100px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 1; }
        .pb-hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; }
        .pb-hero-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: flex-start; padding-top: .25rem; }
        .pb-hero-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.2rem; border-radius: .875rem; font-size: .8rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .18s; border: none; cursor: pointer; white-space: nowrap; }
        .pb-hero-btn svg { width: 13px; height: 13px; }
        .pb-hero-btn-back { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15); color: rgba(255,255,255,.85); }
        .pb-hero-btn-back:hover { background: rgba(255,255,255,.17); }

        .pb-breadcrumb { display: flex; align-items: center; gap: .45rem; margin-bottom: .85rem; }
        .pb-breadcrumb a { color: rgba(255,255,255,.45); font-size: .75rem; font-weight: 500; text-decoration: none; transition: color .15s; }
        .pb-breadcrumb a:hover { color: var(--sky); }
        .pb-breadcrumb-sep { color: rgba(255,255,255,.25); font-size: .7rem; }
        .pb-breadcrumb-cur { color: rgba(255,255,255,.7); font-size: .75rem; font-weight: 600; }
        .pb-hero-title { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; color: #fff; line-height: 1.2; margin: 0 0 .3rem; }
        .pb-hero-sub   { font-size: .88rem; color: rgba(255,255,255,.55); margin: 0; }

        /* ── PROGRESS BAR ── */
        .pb-progress-wrap { max-width: 1100px; margin: 1.5rem auto 0; padding: 0 2rem; position: relative; z-index: 1; }
        .pb-progress-track { background: rgba(255,255,255,.08); border-radius: 99px; height: 4px; overflow: hidden; }
        .pb-progress-fill  { height: 100%; background: linear-gradient(90deg, var(--sky), #60a5fa); border-radius: 99px; }
        .pb-steps-row { display: flex; justify-content: space-between; margin-top: .6rem; }
        .pb-step-pill { display: inline-flex; align-items: center; gap: .35rem; font-size: .7rem; font-weight: 600; }
        .pb-step-pill.done    { color: var(--sky); }
        .pb-step-pill.active  { color: var(--sky); }
        .pb-step-pill.pending { color: rgba(255,255,255,.3); }
        .pb-step-pill-num { width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .62rem; font-weight: 700; }
        .pb-step-pill.done   .pb-step-pill-num { background: var(--sky); color: var(--navy); }
        .pb-step-pill.active .pb-step-pill-num { background: var(--sky); color: var(--navy); }
        .pb-step-pill.pending .pb-step-pill-num { background: rgba(255,255,255,.1); color: rgba(255,255,255,.4); border: 1.5px solid rgba(255,255,255,.15); }

        /* ── CONTENT ── */
        .pb-content { max-width: 1100px; margin: 0 auto; padding: 1.75rem 2rem 0; }

        /* ── CARD ── */
        .pb-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.5rem; box-shadow: 0 4px 24px rgba(11,29,53,.05); overflow: hidden; margin-bottom: 1.25rem; }
        .pb-card-header { padding: 1.1rem 1.5rem; border-bottom: 1.5px solid #f0f4f8; display: flex; align-items: center; gap: .55rem; }
        .pb-card-header-accent { width: 3px; height: 16px; background: linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius: 99px; flex-shrink: 0; }
        .pb-card-header h3 { font-size: .76rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--navy); margin: 0; }

        /* ── PAINTING BAR ── */
        .pb-painting-bar { display: flex; align-items: center; gap: 1.1rem; padding: 1rem 1.5rem; background: #f8fafc; border-bottom: 1.5px solid #f0f4f8; }
        .pb-painting-thumb { width: 56px; height: 56px; border-radius: .875rem; overflow: hidden; flex-shrink: 0; background: var(--border); }
        .pb-painting-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .pb-painting-info h3 { font-family: 'Playfair Display', serif; font-size: .9rem; color: var(--navy); margin: 0 0 .15rem; font-weight: 600; }
        .pb-painting-info p  { font-size: .76rem; color: var(--slate); margin: 0; }

        /* ── FORM BODY ── */
        .pb-form-body { padding: 1.5rem; }

        /* ── SECTION LABEL ── */
        .pb-section-label { display: flex; align-items: center; gap: .65rem; margin-bottom: 1.25rem; margin-top: 1.75rem; }
        .pb-section-label:first-child { margin-top: 0; }
        .pb-section-label::before { content: ''; width: 3px; height: 16px; background: linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius: 99px; flex-shrink: 0; }
        .pb-section-label h2 { font-size: .76rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--navy); margin: 0; }
        .pb-section-label span { margin-left: auto; background: #eff6ff; color: var(--blue); font-size: .7rem; font-weight: 600; padding: .2rem .75rem; border-radius: 99px; }
        .pb-section-label span.opt { background: #f1f5f9; color: var(--slate); }
        .pb-section-label span.green { background: #f0fdf4; color: #16a34a; }

        /* ── FORM GRID ── */
        .pb-grid   { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
        .pb-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.1rem; margin-bottom: 1.25rem; }
        .pb-span-2 { grid-column: span 2; }
        @media(max-width: 640px) {
            .pb-grid { grid-template-columns: 1fr; }
            .pb-grid-3 { grid-template-columns: 1fr 1fr; }
            .pb-span-2 { grid-column: span 1; }
            .pb-form-body { padding: 1.25rem; }
        }
        @media(max-width: 420px) { .pb-grid-3 { grid-template-columns: 1fr; } }

        /* ── FIELD ── */
        .pb-field { display: flex; flex-direction: column; gap: .4rem; }
        .pb-field label { font-size: .8rem; font-weight: 600; color: var(--navy); display: flex; align-items: center; gap: .25rem; }
        .pb-field label .req { color: #ef4444; font-size: .9em; }
        .pb-input-wrap { position: relative; }
        .pb-icon { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: #94a3b8; display: flex; align-items: center; pointer-events: none; }
        .pb-icon svg { width: 15px; height: 15px; }
        .pb-icon.top { top: .9rem; transform: none; }
        .pb-field input, .pb-field select, .pb-field textarea {
            width: 100%; background: #f8fafc; border: 1.5px solid var(--border); border-radius: .875rem;
            padding: .8rem .9rem .8rem 2.5rem;
            font-size: .855rem; font-family: 'DM Sans', sans-serif;
            color: var(--navy); outline: none; appearance: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .pb-field textarea { padding-top: .8rem; min-height: 76px; resize: vertical; }
        .pb-field input::placeholder, .pb-field textarea::placeholder { color: #94a3b8; }
        .pb-field input:focus, .pb-field select:focus, .pb-field textarea:focus { border-color: var(--blue); background: #fff; box-shadow: 0 0 0 4px rgba(29,78,216,.07); }
        .pb-field select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2394a3b8'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right .9rem center; background-size: 16px; padding-right: 2.25rem; }
        .pb-field .pb-hint { font-size: .73rem; color: #94a3b8; line-height: 1.5; }

        /* ── DIVIDER ── */
        .pb-divider { border: none; border-top: 1.5px solid #f0f4f8; margin: 1.5rem 0; }

        /* ── ERROR BOX ── */
        .pb-error-box { background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 1.25rem; padding: 1.1rem 1.5rem; color: #b91c1c; margin-bottom: 1.25rem; }
        .pb-error-box h3 { font-weight: 700; font-size: .85rem; margin: 0 0 .45rem; }
        .pb-error-box ul { padding-left: 1.25rem; font-size: .8rem; line-height: 1.8; margin: 0; }

        /* ── INFO BOX ── */
        .pb-info-box { display: flex; align-items: flex-start; gap: .65rem; background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: .875rem; padding: .875rem 1.1rem; font-size: .8rem; color: #1e40af; line-height: 1.6; margin-bottom: 1.25rem; }
        .pb-info-box svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 1px; color: var(--blue); }

        /* ── YES/NO TOGGLE ── */
        .pb-yn-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem; }
        @media(max-width: 500px) { .pb-yn-grid { grid-template-columns: 1fr; } }
        .pb-yn-card { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; padding: 1rem 1.1rem; display: flex; flex-direction: column; gap: .65rem; }
        .pb-yn-card .q { font-size: .82rem; font-weight: 600; color: var(--navy); display: flex; align-items: center; gap: .45rem; }
        .pb-yn-card .q svg { width: 14px; height: 14px; color: var(--slate); flex-shrink: 0; }
        .pb-yn-opts { display: flex; gap: .5rem; }
        .pb-yn-opt { position: relative; flex: 1; }
        .pb-yn-opt input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .pb-yn-opt-inner { display: flex; align-items: center; justify-content: center; gap: .35rem; padding: .6rem .75rem; border-radius: .75rem; border: 1.5px solid var(--border); background: #fff; font-size: .78rem; font-weight: 600; color: var(--slate); cursor: pointer; transition: all .18s; user-select: none; }
        .pb-yn-opt-inner svg { width: 13px; height: 13px; }
        .pb-yn-opt:hover .pb-yn-opt-inner { border-color: #93c5fd; background: #eff6ff; }
        .pb-yn-opt input:checked ~ .pb-yn-opt-inner { border-color: var(--blue); background: #eff6ff; color: var(--blue); box-shadow: 0 0 0 3px rgba(29,78,216,.08); }
        .pb-yn-opt.danger input:checked ~ .pb-yn-opt-inner { border-color: #ef4444; background: #fef2f2; color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,.08); }

        /* ── COLLECTION SUMMARY ── */
        .pb-collection-row-wrap { display: flex; align-items: center; gap: 1.1rem; background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; overflow: hidden; margin-bottom: 1.25rem; }
        .pb-coll-thumb { width: 80px; height: 80px; flex-shrink: 0; background: var(--navy-2); overflow: hidden; }
        .pb-coll-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .pb-coll-thumb-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,.2); }
        .pb-coll-thumb-placeholder svg { width: 24px; height: 24px; }
        .pb-coll-details { flex: 1; padding: .875rem 1rem .875rem 0; display: flex; flex-direction: column; gap: .3rem; }
        .pb-coll-name { font-family: 'Playfair Display', serif; font-size: .9rem; color: var(--navy); font-weight: 600; }
        .pb-coll-meta { display: flex; gap: 1.5rem; flex-wrap: wrap; }
        .pb-coll-meta-item { font-size: .76rem; color: var(--slate); }
        .pb-coll-meta-item strong { color: var(--navy); font-weight: 700; }
        .pb-coll-meta-item.rate strong { color: var(--blue); }

        /* ── COST TABLE ── */
        .pb-cost-table { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; overflow: hidden; margin-bottom: 1.25rem; }
        .pb-cost-row { display: flex; align-items: center; justify-content: space-between; padding: .8rem 1.1rem; border-bottom: 1px solid #f0f4f8; font-size: .82rem; }
        .pb-cost-row:last-child { border-bottom: none; }
        .pb-cost-row .c-label { color: var(--slate); display: flex; align-items: center; gap: .4rem; }
        .pb-cost-row .c-label svg { width: 13px; height: 13px; color: #94a3b8; }
        .pb-cost-row .c-value { font-weight: 700; color: var(--navy); }
        .pb-cost-row.total { background: linear-gradient(135deg, var(--navy), var(--navy-2)); padding: .9rem 1.1rem; }
        .pb-cost-row.total .c-label { color: rgba(255,255,255,.65); font-size: .76rem; }
        .pb-cost-row.total .c-value { font-family: 'Playfair Display', serif; font-size: 1.15rem; color: #fff; }

        /* ── UPLOAD CARDS ── */
        .pb-upload-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 0; }
        @media(max-width: 640px) { .pb-upload-grid { grid-template-columns: 1fr; } }
        .pb-upload-card { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; padding: 1.1rem 1.25rem; transition: border-color .2s; position: relative; }
        .pb-upload-card:hover { border-color: var(--sky); }
        .pb-upload-card.has-file { border-color: #6ee7b7; background: #f8fffe; }
        .pb-upload-card input[type="file"] { position: absolute; width: 0; height: 0; opacity: 0; }
        .pb-upload-top { display: flex; align-items: flex-start; gap: .75rem; margin-bottom: .75rem; }
        .pb-upload-icon { width: 36px; height: 36px; flex-shrink: 0; border-radius: .625rem; background: var(--border); display: flex; align-items: center; justify-content: center; }
        .pb-upload-icon svg { width: 16px; height: 16px; color: var(--slate); }
        .pb-upload-card.has-file .pb-upload-icon { background: #d1fae5; }
        .pb-upload-card.has-file .pb-upload-icon svg { color: #059669; }
        .pb-upload-meta h4 { font-size: .82rem; font-weight: 700; color: var(--navy); margin: 0 0 .25rem; }
        .pb-upload-badge { display: inline-block; font-size: .66rem; font-weight: 700; padding: .12rem .5rem; border-radius: 99px; }
        .pb-upload-badge.wajib    { background: #fef2f2; color: #b91c1c; }
        .pb-upload-badge.opsional { background: #f1f5f9; color: var(--slate); }
        .pb-upload-specs { display: flex; gap: .75rem; font-size: .72rem; color: #94a3b8; margin-bottom: .75rem; }
        .pb-upload-action { display: flex; align-items: center; gap: .6rem; }
        .pb-upload-trigger { display: inline-flex; align-items: center; gap: .35rem; padding: .45rem .9rem; border-radius: .625rem; font-size: .76rem; font-weight: 600; cursor: pointer; border: 1.5px solid var(--border); color: var(--navy); background: #fff; transition: all .18s; font-family: 'DM Sans', sans-serif; white-space: nowrap; }
        .pb-upload-trigger:hover { border-color: var(--blue); color: var(--blue); background: #eff6ff; }
        .pb-upload-trigger svg { width: 12px; height: 12px; }
        .pb-upload-filename { font-size: .75rem; color: #94a3b8; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 140px; }
        .pb-upload-filename.selected { color: #059669; font-weight: 600; }

        /* ── REKENING ── */
        .pb-rekening-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media(max-width: 640px) { .pb-rekening-grid { grid-template-columns: 1fr; } }
        .pb-field-plain { display: flex; flex-direction: column; gap: .35rem; }
        .pb-field-plain label { font-size: .8rem; font-weight: 600; color: var(--navy); }
        .pb-field-plain .req { color: #ef4444; }
        .pb-field-plain input { width: 100%; padding: .75rem 1rem; border: 1.5px solid var(--border); border-radius: .875rem; font-size: .855rem; font-family: 'DM Sans', sans-serif; color: var(--navy); background: #f8fafc; outline: none; transition: border-color .18s, box-shadow .18s; }
        .pb-field-plain input:focus { border-color: var(--blue); background: #fff; box-shadow: 0 0 0 4px rgba(29,78,216,.07); }
        .pb-field-plain input::placeholder { color: #94a3b8; }
        .pb-field-plain .pb-hint { font-size: .73rem; color: #94a3b8; }

        /* ── AGREE CARDS ── */
        .pb-agree-list { display: flex; flex-direction: column; gap: .75rem; }
        .pb-agree-card { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; padding: 1rem 1.25rem; display: flex; align-items: flex-start; gap: .875rem; cursor: pointer; transition: border-color .2s, background .2s, box-shadow .2s; user-select: none; }
        .pb-agree-card:hover { border-color: var(--sky); }
        .pb-agree-card.checked { border-color: var(--blue); background: #f8fbff; box-shadow: 0 0 0 3px rgba(29,78,216,.07); }
        .pb-agree-card input[type="checkbox"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .pb-checkbox-ui { width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px; border: 2px solid #cbd5e1; border-radius: 5px; display: flex; align-items: center; justify-content: center; background: #fff; transition: all .18s; }
        .pb-agree-card.checked .pb-checkbox-ui { background: var(--blue); border-color: var(--blue); }
        .pb-checkbox-ui svg { width: 12px; height: 12px; color: #fff; opacity: 0; transition: opacity .15s; }
        .pb-agree-card.checked .pb-checkbox-ui svg { opacity: 1; }
        .pb-agree-icon { width: 36px; height: 36px; flex-shrink: 0; border-radius: .625rem; background: var(--border); display: flex; align-items: center; justify-content: center; }
        .pb-agree-icon svg { width: 16px; height: 16px; color: var(--slate); }
        .pb-agree-card.checked .pb-agree-icon { background: #dbeafe; }
        .pb-agree-card.checked .pb-agree-icon svg { color: var(--blue); }
        .pb-agree-body h4 { font-size: .82rem; font-weight: 700; color: var(--navy); margin: 0 0 .25rem; }
        .pb-agree-body p  { font-size: .77rem; color: var(--slate); line-height: 1.6; margin: 0; }

        /* ── ACTIONS ── */
        .pb-actions { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.1rem 1.5rem; border-top: 1.5px solid #f0f4f8; background: #fafbfc; flex-wrap: wrap; }
        .pb-actions-left  { display: flex; gap: .75rem; flex-wrap: wrap; }
        .pb-actions-right { display: flex; gap: .75rem; flex-wrap: wrap; }
        .pb-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .65rem 1.35rem; border-radius: .875rem; font-size: .82rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .2s; border: none; cursor: pointer; }
        .pb-btn svg { width: 14px; height: 14px; }
        .pb-btn-ghost  { background: transparent; border: 1.5px solid var(--border); color: var(--slate); }
        .pb-btn-ghost:hover  { background: #f8fafc; }
        .pb-btn-draft  { background: #f8fafc; border: 1.5px solid var(--border); color: var(--navy); }
        .pb-btn-draft:hover  { background: #f1f5f9; }
        .pb-btn-green  { background: linear-gradient(135deg,#16a34a,#15803d); color: #fff; box-shadow: 0 4px 14px rgba(22,163,74,.25); padding: .65rem 1.6rem; }
        .pb-btn-green:hover  { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.35); }

        @media(max-width: 768px) {
            .pb-content    { padding: 1.25rem 1rem 0; }
            .pb-hero-inner { padding: 0 1rem; }
            .pb-progress-wrap { padding: 0 1rem; }
        }
    </style>

    <div class="pb-root">

        {{-- ── HERO ── --}}
        <div class="pb-hero">
            <div class="pb-hero-inner">
                <div class="pb-hero-top">
                    <div>
                        <div class="pb-breadcrumb">
                            <a href="{{ route('penyewaan.index') }}">Katalog Sewa</a>
                            <span class="pb-breadcrumb-sep">/</span>
                            <a href="{{ route('penyewaan.step1', ['koleksi' => $painting->id]) }}">{{ $painting->title }}</a>
                            <span class="pb-breadcrumb-sep">/</span>
                            <span class="pb-breadcrumb-cur">Pengajuan Penyewaan</span>
                        </div>
                        <h1 class="pb-hero-title">Pengajuan Penyewaan Koleksi</h1>
                        <p class="pb-hero-sub">{{ $painting->title }} &mdash; {{ $painting->artist }}</p>
                    </div>
                    <div class="pb-hero-actions">
                        <button type="button" onclick="document.querySelector('[name=back]').click()" class="pb-hero-btn pb-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Langkah 2
                        </button>
                    </div>
                </div>
            </div>

            {{-- Progress 3 Langkah --}}
            <div class="pb-progress-wrap">
                <div class="pb-progress-track">
                    <div class="pb-progress-fill" style="width: 100%;"></div>
                </div>
                <div class="pb-steps-row">
                    <div class="pb-step-pill done">
                        <div class="pb-step-pill-num">✓</div>
                        Jenis Penyewa
                    </div>
                    <div class="pb-step-pill done">
                        <div class="pb-step-pill-num">✓</div>
                        Identitas &amp; Kontak
                    </div>
                    <div class="pb-step-pill active">
                        <div class="pb-step-pill-num">3</div>
                        Detail &amp; Pengajuan
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="pb-content">

            @if($errors->any())
            <div class="pb-error-box">
                <h3>⚠ Periksa kembali data Anda</h3>
                <ul>
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="pb-card">

                <div class="pb-card-header">
                    <div class="pb-card-header-accent"></div>
                    <h3>Langkah 3 — Detail Penyewaan &amp; Pengajuan</h3>
                </div>

                <form action="{{ route('penyewaan.storeStep3', ['koleksi' => $painting->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="rental_type" value="{{ $rentalType }}">

                    <div class="pb-form-body">

                        {{-- ══ SEKSI 1: LOKASI & PENGGUNAAN ══ --}}
                        <div class="pb-section-label" style="margin-top:0;">
                            <h2>Lokasi &amp; Penggunaan</h2>
                            <span>Wajib diisi</span>
                        </div>

                        <div class="pb-grid">
                            <div class="pb-field">
                                <label for="jenis_tempat">Jenis Tempat <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg></span>
                                    <select id="jenis_tempat" name="jenis_tempat" required>
                                        <option value="">Pilih jenis tempat</option>
                                        @foreach($jenisTempatOptions as $opt)
                                            <option value="{{ $opt }}" {{ old('jenis_tempat', session('penyewaan_step4.jenis_tempat', '')) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="indoor_outdoor">Indoor / Outdoor <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg></span>
                                    <select id="indoor_outdoor" name="indoor_outdoor" required>
                                        <option value="">Pilih opsi</option>
                                        <option value="Indoor"  {{ old('indoor_outdoor', session('penyewaan_step4.indoor_outdoor','')) === 'Indoor'  ? 'selected':'' }}>Indoor</option>
                                        <option value="Outdoor" {{ old('indoor_outdoor', session('penyewaan_step4.indoor_outdoor','')) === 'Outdoor' ? 'selected':'' }}>Outdoor</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="jenis_tempat_lain_field" class="pb-field" style="margin-bottom:1.25rem; display:none;">
                            <label for="jenis_tempat_lain">Jelaskan Jenis Tempat Lainnya <span class="req">*</span></label>
                            <div class="pb-input-wrap">
                                <span class="pb-icon top"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg></span>
                                <textarea id="jenis_tempat_lain" name="jenis_tempat_lain" rows="2" placeholder="Tuliskan jenis tempat secara singkat">{{ old('jenis_tempat_lain', session('penyewaan_step4.jenis_tempat_lain','')) }}</textarea>
                            </div>
                        </div>

                        <div class="pb-field" style="margin-bottom:1.25rem;">
                            <label for="alamat_lengkap">Alamat Lengkap Lokasi <span class="req">*</span></label>
                            <div class="pb-input-wrap">
                                <span class="pb-icon top"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg></span>
                                <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" placeholder="Jl. Contoh No. 10..." required>{{ old('alamat_lengkap', session('penyewaan_step4.alamat_lengkap','')) }}</textarea>
                            </div>
                        </div>

                        {{-- Baris 1: Provinsi → Kota/Kabupaten → Kode Pos (Binderbyte, untuk ongkir) --}}
                        <div class="pb-grid-3">
                            <div class="pb-field">
                                <label for="provinsi_select">Provinsi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3"/></svg></span>
                                    <select id="provinsi_select" required onchange="sw_loadKota(this.value, this.options[this.selectedIndex].text)">
                                        <option value="">Pilih provinsi</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov['id'] }}" {{ old('province_id', session('penyewaan_step3.province_id', '')) == $prov['id'] ? 'selected' : '' }}>{{ ucwords(strtolower($prov['name'])) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="kota_select">Kota / Kabupaten <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h12"/></svg></span>
                                    <select id="kota_select" name="kota_kabupaten" required disabled onchange="sw_onKotaChange(this)">
                                        <option value="">Pilih provinsi dulu</option>
                                    </select>
                                </div>
                                <span class="pb-hint" id="kota-loading" style="display:none;">Memuat kota...</span>
                            </div>
                            <div class="pb-field">
                                <label for="kode_pos">Kode Pos <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg></span>
                                    <input id="kode_pos" name="kode_pos" type="text" inputmode="numeric" value="{{ old('kode_pos', session('penyewaan_step3.kode_pos', '')) }}" placeholder="40xxx" maxlength="5" pattern="[0-9]{5}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Baris 2: Kecamatan → Kelurahan/Desa → RT → RW (Emsifa, by-name dari Binderbyte) --}}
                        <div class="pb-grid">
                            <div class="pb-field">
                                <label for="lokasi_kecamatan">Kecamatan <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <select id="lokasi_kecamatan" name="kecamatan" required disabled onchange="lokasi_onKecamatanChange(this)">
                                        <option value="">Pilih kota dulu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="lokasi_kelurahan">Kelurahan / Desa <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <select id="lokasi_kelurahan" name="kelurahan_desa" required disabled>
                                        <option value="">Pilih kecamatan dulu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="rt">RT <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="rt" name="rt" type="text" inputmode="numeric" value="{{ old('rt', session('penyewaan_step4.rt','')) }}" placeholder="001" maxlength="5" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="rw">RW <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="rw" name="rw" type="text" inputmode="numeric" value="{{ old('rw', session('penyewaan_step4.rw','')) }}" placeholder="005" maxlength="5" required>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="city_name"           id="hidden_city_name"    value="{{ old('city_name', session('penyewaan_step3.city_name', '')) }}">
                        <input type="hidden" name="province_id"         id="hidden_province_id"  value="{{ old('province_id', session('penyewaan_step3.province_id', '')) }}">
                        <input type="hidden" name="provinsi"            id="hidden_provinsi"     value="{{ old('provinsi', session('penyewaan_step3.provinsi', '')) }}">
                        <input type="hidden" name="destination_city_id" id="destination_city_id" value="{{ old('destination_city_id', session('penyewaan_step3.destination_city_id', session('penyewaan_step4.destination_city_id', ''))) }}">
                        <input type="hidden" name="kota_kabupaten_hidden" id="hidden_kota_kabupaten" value="{{ old('kota_kabupaten', session('penyewaan_step3.kota_kabupaten', '')) }}">

                        <hr class="pb-divider">

                        <div class="pb-field" style="margin-bottom:1.25rem;">
                            <label for="tujuan_penyewaan">Tujuan Penyewaan <span class="req">*</span></label>
                            <div class="pb-input-wrap">
                                <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg></span>
                                <select id="tujuan_penyewaan" name="tujuan_penyewaan" required>
                                    <option value="">Pilih tujuan</option>
                                    @foreach($tujuanOptions as $opt)
                                        <option value="{{ $opt }}" {{ old('tujuan_penyewaan', session('penyewaan_step4.tujuan_penyewaan','')) === $opt ? 'selected':'' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="tujuan_lain_field" class="pb-field" style="margin-bottom:1.25rem; display:none;">
                            <label for="tujuan_penyewaan_lain">Jelaskan Tujuan Lainnya <span class="req">*</span></label>
                            <div class="pb-input-wrap">
                                <span class="pb-icon top"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg></span>
                                <textarea id="tujuan_penyewaan_lain" name="tujuan_penyewaan_lain" rows="2" placeholder="Tuliskan tujuan sewa secara singkat">{{ old('tujuan_penyewaan_lain', session('penyewaan_step4.tujuan_penyewaan_lain','')) }}</textarea>
                            </div>
                        </div>

                        {{-- ══ SEKSI 2: KEAMANAN & KONDISI ══ --}}
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Keamanan &amp; Kondisi Tempat</h2>
                            <span>Wajib diisi</span>
                        </div>
                        <div class="pb-yn-grid">
                            <div class="pb-yn-card">
                                <div class="q"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/></svg> Lokasi memiliki CCTV?</div>
                                <div class="pb-yn-opts">
                                    <label class="pb-yn-opt"><input type="radio" name="cctv" value="ya" {{ old('cctv', session('penyewaan_step4.cctv','')) === 'ya' ? 'checked':'' }} required><div class="pb-yn-opt-inner"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>Ya</div></label>
                                    <label class="pb-yn-opt danger"><input type="radio" name="cctv" value="tidak" {{ old('cctv', session('penyewaan_step4.cctv','')) === 'tidak' ? 'checked':'' }}><div class="pb-yn-opt-inner"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Tidak</div></label>
                                </div>
                            </div>
                            <div class="pb-yn-card">
                                <div class="q"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg> Ada petugas keamanan?</div>
                                <div class="pb-yn-opts">
                                    <label class="pb-yn-opt"><input type="radio" name="keamanan" value="ya" {{ old('keamanan', session('penyewaan_step4.keamanan','')) === 'ya' ? 'checked':'' }} required><div class="pb-yn-opt-inner"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>Ya</div></label>
                                    <label class="pb-yn-opt danger"><input type="radio" name="keamanan" value="tidak" {{ old('keamanan', session('penyewaan_step4.keamanan','')) === 'tidak' ? 'checked':'' }}><div class="pb-yn-opt-inner"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Tidak</div></label>
                                </div>
                            </div>
                            <div class="pb-yn-card">
                                <div class="q"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21m8.485-8.485l-1.59 1.59M5.106 18.894l-1.59-1.59M21 12h-2.25M5.25 12H3m15.364-6.364l-1.59 1.59M6.22 17.78l-1.59-1.59M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg> Lokasi ber-AC?</div>
                                <div class="pb-yn-opts">
                                    <label class="pb-yn-opt"><input type="radio" name="ber_ac" value="ya" {{ old('ber_ac', session('penyewaan_step4.ber_ac','')) === 'ya' ? 'checked':'' }}><div class="pb-yn-opt-inner"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>Ya</div></label>
                                    <label class="pb-yn-opt danger"><input type="radio" name="ber_ac" value="tidak" {{ old('ber_ac', session('penyewaan_step4.ber_ac','')) === 'tidak' ? 'checked':'' }}><div class="pb-yn-opt-inner"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Tidak</div></label>
                                </div>
                            </div>
                            <div class="pb-yn-card">
                                <div class="q"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15a4.5 4.5 0 004.5 4.5H18a3.75 3.75 0 001.332-7.257 3 3 0 00-3.758-3.848 5.25 5.25 0 00-10.233 2.33A4.502 4.502 0 002.25 15z"/></svg> Risiko terkena hujan/panas?</div>
                                <div class="pb-yn-opts">
                                    <label class="pb-yn-opt danger"><input type="radio" name="risiko_cuaca" value="ya" {{ old('risiko_cuaca', session('penyewaan_step4.risiko_cuaca','')) === 'ya' ? 'checked':'' }} required><div class="pb-yn-opt-inner"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>Ya</div></label>
                                    <label class="pb-yn-opt"><input type="radio" name="risiko_cuaca" value="tidak" {{ old('risiko_cuaca', session('penyewaan_step4.risiko_cuaca','')) === 'tidak' ? 'checked':'' }}><div class="pb-yn-opt-inner"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>Tidak</div></label>
                                </div>
                            </div>
                        </div>

                        {{-- ══ SEKSI 3: DETAIL KOLEKSI & PERIODE ══ --}}
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Koleksi &amp; Periode Sewa</h2>
                            <span>Wajib diisi</span>
                        </div>

                        <div class="pb-collection-row-wrap">
                            <div class="pb-coll-thumb">
                                @if($painting->image_path)
                                    <img src="{{ asset('storage/' . $painting->image_path) }}" alt="{{ $painting->title }}">
                                @elseif($painting->image_url)
                                    <img src="{{ $painting->image_url }}" alt="{{ $painting->title }}">
                                @else
                                    <div class="pb-coll-thumb-placeholder"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg></div>
                                @endif
                            </div>
                            <div class="pb-coll-details">
                                <div class="pb-coll-name">{{ $painting->title }}</div>
                                <div class="pb-coll-meta">
                                    @if($painting->artist)<div class="pb-coll-meta-item">Seniman: <strong>{{ $painting->artist }}</strong></div>@endif
                                    <div class="pb-coll-meta-item">Jumlah: <strong>1 karya</strong></div>
                                    <div class="pb-coll-meta-item rate">Tarif: <strong>Rp {{ number_format($painting->daily_rate, 0, ',', '.') }} / hari</strong></div>
                                    @if($painting->dapatDibeli() && $painting->sale_price > 0)
                                    <div class="pb-coll-meta-item" style="color:var(--slate);">Harga Beli: <strong style="color:#7e22ce;">Rp {{ number_format($painting->sale_price, 0, ',', '.') }}</strong></div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="pb-grid">
                            <div class="pb-field">
                                <label for="start_date">Tanggal Mulai Sewa <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg></span>
                                    <input id="start_date" name="start_date" type="date" value="{{ old('start_date', session('penyewaan_step4.start_date','')) }}" min="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                                </div>
                                <span class="pb-hint">Minimal 7 hari dari hari ini.</span>
                            </div>
                            <div class="pb-field">
                                <label for="end_date">Tanggal Selesai Sewa <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg></span>
                                    <input id="end_date" name="end_date" type="date" value="{{ old('end_date', session('penyewaan_step4.end_date','')) }}" required>
                                </div>
                                <span class="pb-hint">Harus setelah tanggal mulai sewa.</span>
                            </div>
                        </div>

                        {{-- Cost Summary --}}
                        <div class="pb-cost-table">
                            <div class="pb-cost-row">
                                <span class="c-label"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25"/></svg> Durasi Sewa</span>
                                <span class="c-value"><span id="cost-duration">0</span> hari</span>
                            </div>
                            <div class="pb-cost-row">
                                <span class="c-label"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 21z"/></svg> Subtotal Sewa</span>
                                <span class="c-value" id="cost-subtotal">Rp 0</span>
                            </div>
                            <div class="pb-cost-row">
                                <span class="c-label"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757"/></svg> Deposit (50%)</span>
                                <span class="c-value" id="cost-deposit">Rp 0</span>
                            </div>
                            <div class="pb-cost-row">
                                <span class="c-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                    Ongkos Kirim
                                </span>
                                <span style="font-size:.78rem; color:#94a3b8; font-style:italic;">Ditentukan pengelola saat verifikasi</span>
                            </div>
                            <div class="pb-cost-row total">
                                <span class="c-label">Total Tagihan Awal</span>
                                <span class="c-value" id="cost-total">Rp 0</span>
                            </div>
                        </div>

                        {{-- ══ SEKSI 4: DOKUMEN VERIFIKASI ══ --}}
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Dokumen Verifikasi</h2>
                            <span>Wajib diisi</span>
                        </div>

                        @php
                            $draft = $penyewaan ?? null;
                            $savedFileName = fn ($path) => $path ? pathinfo($path, PATHINFO_BASENAME) : 'Belum ada file';
                            $hasFile = fn ($path) => ! empty($path);
                            $savedProvinceId = old('province_id', session('penyewaan_step3.province_id', ''));
                            $savedKota = old('kota_kabupaten', session('penyewaan_step3.kota_kabupaten', session('penyewaan_step4.kota_kabupaten', '')));
                            $savedKecamatan = old('kecamatan', session('penyewaan_step3.kecamatan', session('penyewaan_step4.kecamatan', '')));
                            $savedKelurahan = old('kelurahan_desa', session('penyewaan_step3.kelurahan_desa', session('penyewaan_step4.kelurahan_desa', '')));
                        @endphp

                        @if($rentalType === 'perseorangan')
                        <div class="pb-upload-grid">
                            <div class="pb-upload-card{{ $hasFile($draft->upload_ktp ?? null) ? ' has-file' : '' }}" id="card-ktp">
                                <input type="file" id="upload_ktp" name="upload_ktp" accept=".pdf" @if(!$hasFile($draft->upload_ktp ?? null)) required @endif onchange="handleFile(this,'lbl-ktp','card-ktp')">
                                <div class="pb-upload-top">
                                    <div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg></div>
                                    <div class="pb-upload-meta"><h4>KTP / Kartu Identitas</h4><span class="pb-upload-badge wajib">Wajib</span></div>
                                </div>
                                <div class="pb-upload-specs"><span>PDF</span><span>Maks. 2 MB</span></div>
                                <div class="pb-upload-action">
                                    <label for="upload_ktp" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label>
                                    <span class="pb-upload-filename{{ $hasFile($draft->upload_ktp ?? null) ? ' selected':'' }}" id="lbl-ktp">{{ $savedFileName($draft->upload_ktp ?? null) }}</span>
                                </div>
                            </div>
                            <div class="pb-upload-card{{ $hasFile($draft->upload_npwp ?? null) ? ' has-file' : '' }}" id="card-npwp">
                                <input type="file" id="upload_npwp" name="upload_npwp" accept=".pdf" onchange="handleFile(this,'lbl-npwp','card-npwp')">
                                <div class="pb-upload-top">
                                    <div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg></div>
                                    <div class="pb-upload-meta"><h4>NPWP</h4><span class="pb-upload-badge opsional">Opsional</span></div>
                                </div>
                                <div class="pb-upload-specs"><span>PDF</span><span>Maks. 2 MB</span></div>
                                <div class="pb-upload-action">
                                    <label for="upload_npwp" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label>
                                    <span class="pb-upload-filename{{ $hasFile($draft->upload_npwp ?? null) ? ' selected':'' }}" id="lbl-npwp">{{ $savedFileName($draft->upload_npwp ?? null) }}</span>
                                </div>
                            </div>
                            <div class="pb-upload-card{{ $hasFile($draft->upload_foto_lokasi ?? null) ? ' has-file' : '' }}" id="card-foto">
                                <input type="file" id="upload_foto_lokasi" name="upload_foto_lokasi" accept=".pdf" onchange="handleFile(this,'lbl-foto','card-foto')">
                                <div class="pb-upload-top">
                                    <div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/></svg></div>
                                    <div class="pb-upload-meta"><h4>Foto Lokasi Penempatan</h4><span class="pb-upload-badge opsional">Opsional</span></div>
                                </div>
                                <div class="pb-upload-specs"><span>PDF</span><span>Maks. 5 MB</span></div>
                                <div class="pb-upload-action">
                                    <label for="upload_foto_lokasi" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label>
                                    <span class="pb-upload-filename{{ $hasFile($draft->upload_foto_lokasi ?? null) ? ' selected':'' }}" id="lbl-foto">{{ $savedFileName($draft->upload_foto_lokasi ?? null) }}</span>
                                </div>
                            </div>
                            <div class="pb-upload-card{{ $hasFile($draft->upload_denah ?? null) ? ' has-file' : '' }}" id="card-denah">
                                <input type="file" id="upload_denah" name="upload_denah" accept=".pdf" onchange="handleFile(this,'lbl-denah','card-denah')">
                                <div class="pb-upload-top">
                                    <div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689"/></svg></div>
                                    <div class="pb-upload-meta"><h4>Denah / Sketsa Lokasi</h4><span class="pb-upload-badge opsional">Opsional</span></div>
                                </div>
                                <div class="pb-upload-specs"><span>PDF</span><span>Maks. 5 MB</span></div>
                                <div class="pb-upload-action">
                                    <label for="upload_denah" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label>
                                    <span class="pb-upload-filename{{ $hasFile($draft->upload_denah ?? null) ? ' selected':'' }}" id="lbl-denah">{{ $savedFileName($draft->upload_denah ?? null) }}</span>
                                </div>
                            </div>
                        </div>

                        @else
                        {{-- INSTANSI --}}
                        <div class="pb-upload-grid">
                            <div class="pb-upload-card{{ $hasFile($draft->upload_surat_pengajuan ?? null) ? ' has-file':'' }}" id="card-surat">
                                <input type="file" id="upload_surat_pengajuan" name="upload_surat_pengajuan" accept=".pdf" @if(!$hasFile($draft->upload_surat_pengajuan ?? null)) required @endif onchange="handleFile(this,'lbl-surat','card-surat')">
                                <div class="pb-upload-top"><div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg></div><div class="pb-upload-meta"><h4>Surat Pengajuan Instansi</h4><span class="pb-upload-badge wajib">Wajib</span></div></div>
                                <div class="pb-upload-specs"><span>PDF</span><span>Maks. 5 MB</span></div>
                                <div class="pb-upload-action"><label for="upload_surat_pengajuan" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label><span class="pb-upload-filename{{ $hasFile($draft->upload_surat_pengajuan ?? null) ? ' selected':'' }}" id="lbl-surat">{{ $savedFileName($draft->upload_surat_pengajuan ?? null) }}</span></div>
                            </div>
                            <div class="pb-upload-card{{ $hasFile($draft->upload_ktp_pic ?? null) ? ' has-file':'' }}" id="card-ktp-pic">
                                <input type="file" id="upload_ktp_pic" name="upload_ktp_pic" accept=".pdf" @if(!$hasFile($draft->upload_ktp_pic ?? null)) required @endif onchange="handleFile(this,'lbl-ktp-pic','card-ktp-pic')">
                                <div class="pb-upload-top"><div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0z"/></svg></div><div class="pb-upload-meta"><h4>KTP Penanggung Jawab (PIC)</h4><span class="pb-upload-badge wajib">Wajib</span></div></div>
                                <div class="pb-upload-specs"><span>PDF</span><span>Maks. 2 MB</span></div>
                                <div class="pb-upload-action"><label for="upload_ktp_pic" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label><span class="pb-upload-filename{{ $hasFile($draft->upload_ktp_pic ?? null) ? ' selected':'' }}" id="lbl-ktp-pic">{{ $savedFileName($draft->upload_ktp_pic ?? null) }}</span></div>
                            </div>
                            <div class="pb-upload-card{{ $hasFile($draft->upload_npwp_instansi ?? null) ? ' has-file':'' }}" id="card-npwp-inst">
                                <input type="file" id="upload_npwp_instansi" name="upload_npwp_instansi" accept=".pdf" @if(!$hasFile($draft->upload_npwp_instansi ?? null)) required @endif onchange="handleFile(this,'lbl-npwp-inst','card-npwp-inst')">
                                <div class="pb-upload-top"><div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757"/></svg></div><div class="pb-upload-meta"><h4>NPWP Instansi</h4><span class="pb-upload-badge wajib">Wajib</span></div></div>
                                <div class="pb-upload-specs"><span>PDF</span><span>Maks. 2 MB</span></div>
                                <div class="pb-upload-action"><label for="upload_npwp_instansi" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label><span class="pb-upload-filename{{ $hasFile($draft->upload_npwp_instansi ?? null) ? ' selected':'' }}" id="lbl-npwp-inst">{{ $savedFileName($draft->upload_npwp_instansi ?? null) }}</span></div>
                            </div>
                            <div class="pb-upload-card{{ $hasFile($draft->upload_foto_lokasi ?? null) ? ' has-file':'' }}" id="card-foto-inst">
                                <input type="file" id="upload_foto_lokasi" name="upload_foto_lokasi" accept=".pdf" onchange="handleFile(this,'lbl-foto-inst','card-foto-inst')">
                                <div class="pb-upload-top"><div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5z"/></svg></div><div class="pb-upload-meta"><h4>Foto Lokasi Penempatan</h4><span class="pb-upload-badge opsional">Opsional</span></div></div>
                                <div class="pb-upload-specs"><span>PDF</span><span>Maks. 5 MB</span></div>
                                <div class="pb-upload-action"><label for="upload_foto_lokasi" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label><span class="pb-upload-filename{{ $hasFile($draft->upload_foto_lokasi ?? null) ? ' selected':'' }}" id="lbl-foto-inst">{{ $savedFileName($draft->upload_foto_lokasi ?? null) }}</span></div>
                            </div>
                            <div class="pb-upload-card{{ $hasFile($draft->upload_denah ?? null) ? ' has-file':'' }}" id="card-denah-inst">
                                <input type="file" id="upload_denah" name="upload_denah" accept=".pdf" onchange="handleFile(this,'lbl-denah-inst','card-denah-inst')">
                                <div class="pb-upload-top"><div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82"/></svg></div><div class="pb-upload-meta"><h4>Denah / Sketsa Lokasi</h4><span class="pb-upload-badge opsional">Opsional</span></div></div>
                                <div class="pb-upload-specs"><span>PDF</span><span>Maks. 5 MB</span></div>
                                <div class="pb-upload-action"><label for="upload_denah" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label><span class="pb-upload-filename{{ $hasFile($draft->upload_denah ?? null) ? ' selected':'' }}" id="lbl-denah-inst">{{ $savedFileName($draft->upload_denah ?? null) }}</span></div>
                            </div>
                        </div>
                        @endif

                        {{-- ══ SEKSI 5: REKENING ══ --}}
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Rekening Pengembalian Deposit</h2>
                            <span>Wajib diisi</span>
                        </div>
                        <div class="pb-info-box">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                            Deposit 50% akan dikembalikan ke rekening ini setelah koleksi kembali dalam kondisi baik.
                        </div>
                        <div class="pb-rekening-grid">
                            <div class="pb-field-plain">
                                <label for="bank_name">Nama Bank <span class="req">*</span></label>
                                <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name', $draft->bank_name ?? '') }}" placeholder="Contoh: BCA, BNI, Mandiri, BRI" required>
                                <span class="pb-hint">Nama bank tujuan transfer refund deposit</span>
                            </div>
                            <div class="pb-field-plain">
                                <label for="account_number">Nomor Rekening <span class="req">*</span></label>
                                <input type="text" id="account_number" name="account_number" value="{{ old('account_number', $draft->account_number ?? '') }}" placeholder="Contoh: 1234567890" required style="font-family: monospace; letter-spacing: .05em;">
                            </div>
                            <div class="pb-field-plain" style="grid-column: span 2;">
                                <label for="account_holder">Nama Pemilik Rekening <span class="req">*</span></label>
                                <input type="text" id="account_holder" name="account_holder" value="{{ old('account_holder', $draft->account_holder ?? '') }}" placeholder="Sesuai nama di buku tabungan / kartu ATM" required>
                                <span class="pb-hint">Pastikan nama sesuai persis dengan nama di rekening bank</span>
                            </div>
                        </div>

                        {{-- ══ SEKSI 6: PERSETUJUAN ══ --}}
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Persetujuan</h2>
                            <span>Wajib diisi</span>
                        </div>
                        <div class="pb-agree-list">
                            <label class="pb-agree-card" id="ac-1">
                                <input type="checkbox" name="agree_terms" id="chk-1" required onchange="syncAgree('chk-1','ac-1')"
                                    {{ old('agree_terms', session('penyewaan_step4.agree_terms', false)) ? 'checked':'' }}>                                <div class="pb-checkbox-ui"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></div>
                                <div class="pb-agree-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg></div>
                                <div class="pb-agree-body"><h4>Syarat &amp; Ketentuan Penyewaan</h4><p>Saya telah membaca dan menyetujui seluruh syarat dan ketentuan penyewaan koleksi, termasuk aturan penanganan, transportasi, dan pengembalian koleksi.</p></div>
                            </label>
                            <label class="pb-agree-card" id="ac-2">
                                <input type="checkbox" name="agree_responsibility" id="chk-2" required onchange="syncAgree('chk-2','ac-2')"
                                    {{ old('agree_responsibility', session('penyewaan_step4.agree_responsibility', false)) ? 'checked':'' }}>                                <div class="pb-checkbox-ui"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></div>
                                <div class="pb-agree-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg></div>
                                <div class="pb-agree-body"><h4>Tanggung Jawab atas Kerusakan &amp; Kehilangan</h4><p>Saya bertanggung jawab penuh atas segala bentuk kerusakan, kehilangan, atau penurunan kondisi koleksi selama masa penyewaan berlangsung.</p></div>
                            </label>
                            <label class="pb-agree-card" id="ac-3">
                                <input type="checkbox" name="agree_privacy" id="chk-3" required onchange="syncAgree('chk-3','ac-3')"
                                    {{ old('agree_privacy', session('penyewaan_step4.agree_privacy', false)) ? 'checked':'' }}>                                <div class="pb-checkbox-ui"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></div>
                                <div class="pb-agree-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg></div>
                                <div class="pb-agree-body"><h4>Kebijakan Privasi &amp; Penggunaan Data</h4><p>Saya menyetujui pengumpulan dan penggunaan data pribadi sesuai kebijakan privasi museum untuk keperluan verifikasi dan administrasi penyewaan.</p></div>
                            </label>
                        </div>

                    </div>{{-- /pb-form-body --}}

                    {{-- ACTIONS --}}
                    <div class="pb-actions">
                        <div class="pb-actions-left">
                            {{-- Ganti dari button submit menjadi link biasa --}}
                            <button type="submit" name="back" value="1" formnovalidate class="pb-btn pb-btn-ghost">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                                </svg>
                                Kembali ke Langkah 2
                            </button>
                            {{-- Hapus hidden input #input-back yang tidak lagi dipakai --}}
                            <a href="{{ route('penyewaan.index') }}" class="pb-btn pb-btn-ghost">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/>
                                </svg>
                                Kembali ke Katalog
                            </a>
                        </div>
                        <div class="pb-actions-right">
                            <button type="submit" name="save_draft" value="1" formnovalidate class="pb-btn pb-btn-draft">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/>
                                </svg>
                                Simpan Draft
                            </button>
                            <button type="button" onclick="openModal()" class="pb-btn pb-btn-green">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Ajukan Penyewaan
                            </button>
                            <button id="btn-submit-real" type="submit" style="display:none;"></button>
                        </div>
                    </div>
                </form>
            </div>{{-- /pb-card --}}
        </div>{{-- /pb-content --}}
    </div>{{-- /pb-root --}}

    {{-- MODAL KONFIRMASI --}}
    <div id="confirm-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(11,29,53,.55);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:1.5rem;padding:2.25rem;max-width:420px;width:calc(100% - 2rem);box-shadow:0 32px 80px rgba(11,29,53,.2);text-align:center;">
            <div style="width:56px;height:56px;border-radius:50%;background:#eff6ff;display:flex;align-items:center;justify-content:center;margin:0 auto 1.1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#1d4ed8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
            </div>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.2rem;color:#0b1d35;margin:0 0 .65rem;">Konfirmasi Pengajuan</h3>
            <p style="font-size:.82rem;color:#64748b;line-height:1.7;margin:0 0 .4rem;">Setelah diajukan, permohonan akan dikirim ke pengelola untuk diverifikasi dan <strong>tidak dapat diubah</strong> kecuali oleh pengelola.</p>
            <p style="font-size:.82rem;color:#64748b;line-height:1.7;margin:0 0 1.5rem;">Pastikan seluruh dokumen dan data sudah benar sebelum melanjutkan.</p>
            <div style="display:flex;gap:.75rem;justify-content:center;">
                <button onclick="closeModal()" style="padding:.65rem 1.35rem;border-radius:.875rem;border:1.5px solid #e2e8f0;background:#f8fafc;color:#334155;font-size:.82rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;">Batal, Periksa Lagi</button>
                <button onclick="submitForm()" style="padding:.65rem 1.5rem;border-radius:.875rem;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:none;font-size:.82rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;box-shadow:0 4px 14px rgba(22,163,74,.3);">Ya, Ajukan Sekarang</button>
            </div>
        </div>
    </div>

    <script>
        const dailyRate  = {{ (float)$painting->daily_rate }};
        const startInput = document.getElementById('start_date');
        const endInput   = document.getElementById('end_date');

        function formatRp(v) { return 'Rp ' + parseInt(v).toLocaleString('id-ID'); }

        function setEndMax(startDate) {
            const maxEnd = new Date(startDate.getTime() + 30*24*60*60*1000);
            endInput.max = maxEnd.toISOString().split('T')[0];
        }

        function updateCostSummary() {
            if (!startInput.value || !endInput.value) {
                document.getElementById('cost-duration').textContent = '0';
                document.getElementById('cost-subtotal').textContent = formatRp(0);
                document.getElementById('cost-deposit').textContent  = formatRp(0);
                document.getElementById('cost-total').textContent    = formatRp(0);
                return;
            }
            const start = new Date(startInput.value);
            const end   = new Date(endInput.value);
            if (end < start) { endInput.setCustomValidity('Tanggal selesai harus sama atau setelah tanggal mulai.'); return; }
            endInput.setCustomValidity('');
            const days     = Math.round((end - start) / (1000*60*60*24)) + 1;
            const subtotal = Math.round(dailyRate * days);
            const deposit  = subtotal > 0 ? Math.round(subtotal * 0.5) : 0;
            const total    = subtotal + deposit;
            document.getElementById('cost-duration').textContent = days;
            document.getElementById('cost-subtotal').textContent = formatRp(subtotal);
            document.getElementById('cost-deposit').textContent  = formatRp(deposit);
            document.getElementById('cost-total').textContent    = formatRp(total);
        }

        startInput.addEventListener('change', function () {
            const d = new Date(this.value);
            if (!isNaN(d.getTime())) { endInput.min = this.value; setEndMax(d); }
            if (endInput.value && endInput.value < this.value) endInput.value = '';
            updateCostSummary();
        });
        endInput.addEventListener('change', updateCostSummary);
        if (startInput.value) { endInput.min = startInput.value; setEndMax(new Date(startInput.value)); }
        updateCostSummary();

        // jenis tempat & tujuan toggle
        (function(){
            const jtSel   = document.getElementById('jenis_tempat');
            const jtField = document.getElementById('jenis_tempat_lain_field');
            const jtInput = document.getElementById('jenis_tempat_lain');
            function toggleJt() { const s = jtSel && jtSel.value === 'Lainnya'; if(jtField) jtField.style.display = s ? 'block':'none'; if(jtInput) jtInput.required = s; }
            if (jtSel) { jtSel.addEventListener('change', toggleJt); toggleJt(); }
            const tjSel   = document.getElementById('tujuan_penyewaan');
            const tjField = document.getElementById('tujuan_lain_field');
            const tjInput = document.getElementById('tujuan_penyewaan_lain');
            function toggleTj() { const s = tjSel && tjSel.value === 'Lainnya'; if(tjField) tjField.style.display = s ? 'block':'none'; if(tjInput) tjInput.required = s; }
            if (tjSel) { tjSel.addEventListener('change', toggleTj); toggleTj(); }
        })();

        // upload
        function handleFile(input, labelId, cardId) {
            const label = document.getElementById(labelId);
            const card  = document.getElementById(cardId);
            if (input.files && input.files[0]) {
                const name = input.files[0].name;
                label.textContent = name.length > 22 ? name.slice(0,19)+'...' : name;
                label.classList.add('selected'); card.classList.add('has-file');
            } else {
                label.textContent = 'Belum ada file';
                label.classList.remove('selected'); card.classList.remove('has-file');
            }
        }

        // agree
        function syncAgree(checkboxId, cardId) {
            const cb   = document.getElementById(checkboxId);
            const card = document.getElementById(cardId);
            if (!cb || !card) return;
            if (cb.checked) {
                card.classList.add('checked');
            } else {
                card.classList.remove('checked');
            }
        }
        ['chk-1', 'chk-2', 'chk-3'].forEach(function(id, i) {
            const cb = document.getElementById(id);
            if (cb && cb.checked) {
                const card = document.getElementById('ac-' + (i + 1));
                if (card) card.classList.add('checked');
            }
        });

        // modal
        function openModal() {
            const modal = document.getElementById('confirm-modal');
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
        }

        function closeModal() {
            document.getElementById('confirm-modal').style.display = 'none';
        }
        // Tambahkan di fungsi submitForm() dan di form submit event:
        function submitForm() { 
            // Enable semua select yang disabled agar ikut tersubmit
            ['kota_select','lokasi_kecamatan','lokasi_kelurahan'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.disabled = false;
            });
            document.getElementById('btn-submit-real').click(); 
        }

        document.querySelector('form').addEventListener('submit', function() {
            ['kota_select','lokasi_kecamatan','lokasi_kelurahan'].forEach(function(id) {
                const el = document.getElementById(id);
                if (el) el.disabled = false;
            });
        });

        // numeric inputs
        ['rt','rw','kode_pos'].forEach(function(id){
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', function(){ this.value = this.value.replace(/[^0-9]/g,''); });
        });
    </script>

    <script>
    const SW_provinces     = @json($provinces);
    const SW_citiesGrouped = @json($citiesGrouped);
    const SW_savedProvinceId = @json($savedProvinceId);
    const SW_savedKota = @json($savedKota);    

    function sw_loadKota(provinceId, provinceName) {
        const kotaSel = document.getElementById('kota_select');
        document.getElementById('hidden_province_id').value = provinceId;
        document.getElementById('hidden_provinsi').value    = provinceName;
        document.getElementById('hidden_city_name').value   = '';
        if (!provinceId) { kotaSel.innerHTML = '<option value="">Pilih provinsi dulu</option>'; kotaSel.disabled = true; return; }
        const cities = SW_citiesGrouped[String(provinceId)] || [];
        kotaSel.innerHTML = '<option value="">Pilih kota/kabupaten</option>';
        cities.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.name; opt.dataset.cityId = c.id;
            opt.textContent = sw_toTitleCase(c.name);
            if (SW_savedKota && c.name === SW_savedKota) { opt.selected = true; document.getElementById('hidden_city_name').value = sw_normalizeCityName(c.name); }
            kotaSel.appendChild(opt);
        });
        kotaSel.disabled = false;
    }

    async function sw_onKotaChange(sel) {
        const cityName = sel.value;
        if (!cityName) return;
        const normalized   = sw_normalizeCityName(cityName);
        const provinceName = document.getElementById('hidden_provinsi').value;
        document.getElementById('hidden_city_name').value   = normalized;
        document.getElementById('destination_city_id').value = '';
        if (cityName && provinceName) {
            try {
                const res  = await fetch(`/api/rajaongkir/find-city?city_name=${encodeURIComponent(normalized)}&province_name=${encodeURIComponent(provinceName)}`);
                const data = await res.json();
                document.getElementById('destination_city_id').value = data.city_id ?? '';
            } catch (e) { console.warn('find-city gagal:', e); }
        }

        lokasi_loadKecamatan(provinceName, cityName);
    }

    function sw_normalizeCityName(name) { return name.toLowerCase().replace(/^(kab\.|kab |kota |kabupaten |kota)\s*/i,'').trim(); }
    function sw_toTitleCase(str) { return str.toLowerCase().replace(/\b\w/g, c => c.toUpperCase()); }

    document.addEventListener('DOMContentLoaded', function () {
        if (SW_savedProvinceId) {
            const provSel = document.getElementById('provinsi_select');
            provSel.value = SW_savedProvinceId;
            const selectedOpt = provSel.options[provSel.selectedIndex];
            sw_loadKota(SW_savedProvinceId, selectedOpt ? selectedOpt.text : '');

            if (SW_savedKota) {
                document.getElementById('hidden_city_name').value = sw_normalizeCityName(SW_savedKota);
            }
        }
    });
    </script>

    <script>
        const EMSIFA_BASE = '/api/wilayah';
        let emsifaProvincesCache = null;

        const SAVED_KECAMATAN = @json($savedKecamatan);
        const SAVED_KELURAHAN = @json($savedKelurahan);

        function emsifa_normalize(str) {
            return (str || '').toLowerCase()
                .replace(/^(kab\.|kab |kota adm\.|kota administrasi |kota |kabupaten )\s*/i, '')
                .trim();
        }

        function emsifa_findByName(list, name) {
            const target = emsifa_normalize(name);
            if (!target) return null;
            let found = list.find(item => emsifa_normalize(item.name) === target);
            if (!found) {
                found = list.find(item =>
                    emsifa_normalize(item.name).includes(target) ||
                    target.includes(emsifa_normalize(item.name))
                );
            }
            return found || null;
        }

        async function emsifa_getProvinces() {
            if (!emsifaProvincesCache) {
                const res = await fetch(`${EMSIFA_BASE}/provinces`);
                emsifaProvincesCache = await res.json();
            }
            return emsifaProvincesCache;
        }

        function lokasi_setOptions(sel, items, getValue, getLabel, selectedValue, placeholder) {
            sel.innerHTML = `<option value="">${placeholder}</option>`;
            items.forEach(item => {
                const opt = document.createElement('option');
                opt.value = getValue(item);
                opt.textContent = sw_toTitleCase(getLabel(item));
                opt.dataset.id = item.id;
                if (selectedValue && emsifa_normalize(getValue(item)) === emsifa_normalize(selectedValue)) {
                    opt.selected = true;
                }
                sel.appendChild(opt);
            });
        }

        // Dipanggil setelah Kota/Kabupaten (Binderbyte) dipilih
        async function lokasi_loadKecamatan(provinceName, cityName) {
            const kecSel = document.getElementById('lokasi_kecamatan');
            const kelSel = document.getElementById('lokasi_kelurahan');

            kelSel.innerHTML = '<option value="">Pilih kecamatan dulu</option>';
            kelSel.disabled = true;

            if (!provinceName || !cityName) {
                kecSel.innerHTML = '<option value="">Pilih kota dulu</option>';
                kecSel.disabled = true;
                return;
            }

            kecSel.innerHTML = '<option value="">Memuat kecamatan...</option>';
            kecSel.disabled = true;

            try {
                const provinces = await emsifa_getProvinces();
                const emProv = emsifa_findByName(provinces, provinceName);
                if (!emProv) { kecSel.innerHTML = '<option value="">Provinsi tidak ditemukan</option>'; return; }

                const resReg = await fetch(`${EMSIFA_BASE}/regencies/${emProv.id}`);
                const regencies = await resReg.json();
                const emCity = emsifa_findByName(regencies, cityName);
                if (!emCity) { kecSel.innerHTML = '<option value="">Kota tidak ditemukan</option>'; return; }

                const resDist = await fetch(`${EMSIFA_BASE}/districts/${emCity.id}`);
                const districts = await resDist.json();

                lokasi_setOptions(kecSel, districts, d => sw_toTitleCase(d.name), d => d.name, SAVED_KECAMATAN, 'Pilih kecamatan');
                kecSel.disabled = false;

                // Auto-load kelurahan jika ada yang terpilih (saved/restore)
                const selectedOpt = kecSel.options[kecSel.selectedIndex];
                if (selectedOpt && selectedOpt.value) {
                    await lokasi_loadKelurahan(selectedOpt.dataset.id);
                }
            } catch (e) {
                console.error('Emsifa kecamatan error:', e);
                kecSel.innerHTML = '<option value="">Gagal memuat kecamatan</option>';
                kecSel.disabled = false;
            }
        }

        async function lokasi_loadKelurahan(districtId) {
            const kelSel = document.getElementById('lokasi_kelurahan');
            if (!districtId) {
                kelSel.innerHTML = '<option value="">Pilih kecamatan dulu</option>';
                kelSel.disabled = true;
                return;
            }

            kelSel.innerHTML = '<option value="">Memuat kelurahan...</option>';
            kelSel.disabled = true;

            try {
                const res = await fetch(`${EMSIFA_BASE}/villages/${districtId}`);
                const villages = await res.json();
                lokasi_setOptions(kelSel, villages, v => sw_toTitleCase(v.name), v => v.name, SAVED_KELURAHAN, 'Pilih kelurahan/desa');
                kelSel.disabled = false;
            } catch (e) {
                console.error('Emsifa kelurahan error:', e);
                kelSel.innerHTML = '<option value="">Gagal memuat kelurahan</option>';
                kelSel.disabled = false;
            }
        }

        function lokasi_onKecamatanChange(sel) {
            const opt = sel.options[sel.selectedIndex];
            lokasi_loadKelurahan(opt ? opt.dataset.id : null);
        }
    </script>

</x-app-layout>