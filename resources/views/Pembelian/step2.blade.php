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
        .pb-content { max-width: 1100px; margin: 0 auto; padding: 1.75rem 3rem 0; }

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
        .pb-field input:disabled, .pb-field select:disabled { opacity: .5; cursor: not-allowed; }

        /* ── DIVIDER ── */
        .pb-divider { border: none; border-top: 1.5px solid #f0f4f8; margin: 1.5rem 0; }

        /* ── UPLOAD CARDS ── */
        .pb-upload-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem; }
        @media(max-width: 500px) { .pb-upload-grid { grid-template-columns: 1fr; } }
        .pb-upload-card { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1.25rem; padding: 1.1rem; position: relative; transition: border-color .2s; }
        .pb-upload-card:hover { border-color: #93c5fd; }
        .pb-upload-card.has-file { border-color: #6ee7b7; background: #f8fffe; }
        .pb-upload-card input[type="file"] { position: absolute; width: 0; height: 0; opacity: 0; }
        .pb-upload-top { display: flex; align-items: flex-start; gap: .65rem; margin-bottom: .75rem; }
        .pb-upload-icon { width: 36px; height: 36px; flex-shrink: 0; border-radius: .75rem; background: #dbeafe; display: flex; align-items: center; justify-content: center; }
        .pb-upload-icon svg { width: 16px; height: 16px; color: var(--blue); }
        .pb-upload-card.has-file .pb-upload-icon { background: #d1fae5; }
        .pb-upload-card.has-file .pb-upload-icon svg { color: #059669; }
        .pb-upload-meta h4 { font-size: .83rem; font-weight: 700; color: var(--navy); margin: 0 0 .2rem; }
        .pb-upload-badge { display: inline-block; font-size: .67rem; font-weight: 700; padding: .12rem .5rem; border-radius: 99px; }
        .pb-upload-badge.wajib    { background: #fef2f2; color: #b91c1c; }
        .pb-upload-badge.opsional { background: #f1f5f9; color: var(--slate); }
        .pb-upload-specs { font-size: .72rem; color: #94a3b8; margin-bottom: .75rem; }
        .pb-upload-action { display: flex; align-items: center; gap: .65rem; }
        .pb-upload-trigger { display: inline-flex; align-items: center; gap: .3rem; padding: .4rem .85rem; border-radius: .65rem; font-size: .76rem; font-weight: 600; cursor: pointer; border: 1.5px solid var(--border); color: var(--navy); background: #fff; transition: all .18s; font-family: 'DM Sans', sans-serif; white-space: nowrap; }
        .pb-upload-trigger:hover { border-color: var(--blue); color: var(--blue); background: #eff6ff; }
        .pb-upload-trigger svg { width: 12px; height: 12px; }
        .pb-upload-filename { font-size: .74rem; color: #94a3b8; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 150px; }
        .pb-upload-filename.selected { color: #059669; font-weight: 600; }

        /* ── COST CARD ── */
        .pb-cost-wrap { background: linear-gradient(135deg,#0b1d35,#142744); border-radius: 1.25rem; padding: 1.35rem; margin-bottom: .75rem; }
        .pb-cost-row { display: flex; justify-content: space-between; align-items: center; padding: .45rem 0; border-bottom: 1px solid rgba(255,255,255,.07); font-size: .84rem; }
        .pb-cost-row:last-child { border-bottom: none; }
        .pb-cost-row .lbl { color: rgba(255,255,255,.55); }
        .pb-cost-row .val { font-weight: 600; color: #fff; }
        .pb-cost-total { margin-top: .75rem; padding-top: .75rem; border-top: 1.5px solid rgba(255,255,255,.12); display: flex; justify-content: space-between; align-items: center; }
        .pb-cost-total .lbl { font-size: .78rem; color: rgba(255,255,255,.5); font-weight: 600; }
        .pb-cost-total .val { font-family: 'Playfair Display', serif; font-size: 1.35rem; color: #fff; }
        .pb-info-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 1rem; padding: .8rem 1.1rem; }
        .pb-info-box p { font-size: .8rem; color: #1e40af; margin: 0; line-height: 1.6; }

        /* ── ACTIONS ── */
        .pb-actions { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1.1rem 1.5rem; border-top: 1.5px solid #f0f4f8; background: #fafbfc; flex-wrap: wrap; }
        .pb-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .65rem 1.35rem; border-radius: .875rem; font-size: .82rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .2s; border: none; cursor: pointer; }
        .pb-btn svg { width: 14px; height: 14px; }
        .pb-btn-ghost  { background: transparent; border: 1.5px solid var(--border); color: var(--slate); }
        .pb-btn-ghost:hover  { background: #f8fafc; }
        .pb-btn-blue   { background: linear-gradient(135deg,var(--blue),#2563eb); color: #fff; box-shadow: 0 4px 14px rgba(29,78,216,.25); }
        .pb-btn-blue:hover   { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(29,78,216,.35); }

        /* ── SPINNER ── */
        .pb-spinner { display: inline-block; width: 13px; height: 13px; border: 2px solid #bfdbfe; border-top-color: var(--blue); border-radius: 50%; animation: spin .6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .pb-hint { font-size: .73px; color: #94a3b8; }

        @media(max-width: 768px) {
            .pb-content   { padding: 1.25rem 1rem 0; }
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
                            <a href="{{ route('gallery') }}">Galeri</a>
                            <span class="pb-breadcrumb-sep">/</span>
                            <a href="{{ route('gallery.show', $koleksi) }}">{{ $painting->title }}</a>
                            <span class="pb-breadcrumb-sep">/</span>
                            <span class="pb-breadcrumb-cur">Pengajuan Pembelian</span>
                        </div>
                        <h1 class="pb-hero-title">Pengajuan Pembelian Koleksi</h1>
                        <p class="pb-hero-sub">{{ $painting->title }} &mdash; {{ $painting->artist }}</p>
                    </div>
                    <div class="pb-hero-actions">
                        <a href="{{ route('pembelian.step1', $koleksi) }}" class="pb-hero-btn pb-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Langkah 1
                        </a>
                    </div>
                </div>
            </div>

            {{-- Progress --}}
            <div class="pb-progress-wrap">
                <div class="pb-progress-track">
                    <div class="pb-progress-fill" style="width:100%;"></div>
                </div>
                <div class="pb-steps-row">
                    <div class="pb-step-pill done">
                        <div class="pb-step-pill-num">✓</div>
                        Jenis Pembeli
                    </div>
                    <div class="pb-step-pill active">
                        <div class="pb-step-pill-num">2</div>
                        Data &amp; Dokumen
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="pb-content">

            @if($errors->any())
            <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:1.25rem;padding:1.1rem 1.5rem;color:#b91c1c;margin-bottom:1.25rem;">
                <h3 style="font-weight:700;font-size:.85rem;margin:0 0 .45rem;">⚠ Periksa kembali data Anda</h3>
                <ul style="padding-left:1.25rem;font-size:.8rem;line-height:1.8;margin:0;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <div class="pb-card">

                {{-- Card header --}}
                <div class="pb-card-header">
                    <div class="pb-card-header-accent"></div>
                    <h3>Langkah 2 — @if($buyerType === 'b2b') Data Instansi &amp; PIC @else Data Pribadi Pembeli @endif</h3>
                </div>

                {{-- Painting bar --}}
                <div class="pb-painting-bar">
                    <div class="pb-painting-thumb">
                        @if($painting->image_url)
                            <img src="{{ $painting->image_url }}" alt="{{ $painting->title }}">
                        @endif
                    </div>
                    <div class="pb-painting-info">
                        <h3>{{ $painting->title }}</h3>
                        <p>{{ $painting->artist }} &mdash; {{ $painting->category }}</p>
                    </div>
                </div>

                <form action="{{ route('pembelian.store', $koleksi) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="buyer_type" value="{{ $buyerType }}">
                    <input type="hidden" name="destination_city_id" id="destination_city_id" value="">

                    <div class="pb-form-body">

                        {{-- ══ B2C ══ --}}
                        @if($buyerType === 'b2c')
                        <div class="pb-section-label">
                            <h2>Data Pribadi</h2>
                            <span>Sesuai KTP</span>
                        </div>
                        <div class="pb-grid">
                            <div class="pb-field pb-span-2">
                                <label for="nama_lengkap">Nama Lengkap <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></span>
                                    <input id="nama_lengkap" name="nama_lengkap" type="text" value="{{ old('nama_lengkap', session('pembelian_step2.nama_lengkap')) }}" placeholder="Nama lengkap sesuai KTP" required>
                                </div>
                            </div>
                            <div class="pb-field pb-span-2">
                                <label for="nik">NIK <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg></span>
                                    <input id="nik" name="nik" type="text" inputmode="numeric" value="{{ old('nik', session('pembelian_step2.nik')) }}" placeholder="16 digit NIK sesuai KTP" pattern="[0-9]{16}" maxlength="16" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="tempat_lahir">Tempat Lahir <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <input id="tempat_lahir" name="tempat_lahir" type="text" value="{{ old('tempat_lahir', session('pembelian_step2.tempat_lahir')) }}" placeholder="Contoh: Bandung" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="tanggal_lahir">Tanggal Lahir <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg></span>
                                    <input id="tanggal_lahir" name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir', session('pembelian_step2.tanggal_lahir')) }}" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="jenis_kelamin">Jenis Kelamin <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></span>
                                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', session('pembelian_step2.jenis_kelamin')) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', session('pembelian_step2.jenis_kelamin')) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="pekerjaan">Pekerjaan <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></span>
                                    <input id="pekerjaan" name="pekerjaan" type="text" value="{{ old('pekerjaan', session('pembelian_step2.pekerjaan')) }}" placeholder="Wiraswasta, PNS, Seniman" required>
                                </div>
                            </div>
                            <div class="pb-field pb-span-2">
                                <label for="npwp">NPWP</label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg></span>
                                    <input id="npwp" name="npwp" type="text" value="{{ old('npwp', session('pembelian_step2.npwp')) }}" placeholder="Nomor NPWP (opsional)">
                                </div>
                                <span class="pb-hint">Opsional. Dicatat untuk keperluan administratif museum.</span>
                            </div>
                        </div>
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Informasi Kontak</h2><span>Wajib diisi</span>
                        </div>
                        <div class="pb-grid">
                            <div class="pb-field">
                                <label for="nomor_hp">Nomor HP <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 9h3"/></svg></span>
                                    <input id="nomor_hp" name="nomor_hp" type="tel" inputmode="numeric" value="{{ old('nomor_hp', session('pembelian_step2.nomor_hp')) }}" placeholder="08xxxxxxxxxx" maxlength="15" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="email">Email <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg></span>
                                    <input id="email" name="email" type="email" value="{{ old('email', session('pembelian_step2.email', auth()->user()->email)) }}" placeholder="email@domain.com" required>
                                </div>
                            </div>
                        </div>

                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Alamat Domisili</h2><span>Wajib diisi</span>
                        </div>
                        <div class="pb-field" style="margin-bottom:1.1rem;">
                            <label for="alamat_domisili">Alamat Lengkap <span class="req">*</span></label>
                            <div class="pb-input-wrap">
                                <span class="pb-icon top"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg></span>
                                <textarea id="alamat_domisili" name="alamat_domisili" rows="3" placeholder="Jl. Domisili No. 5..." required>{{ old('alamat_domisili', session('pembelian_step2.alamat_domisili')) }}</textarea>
                            </div>
                        </div>
                        <div class="pb-grid-3" style="margin-bottom:1.1rem;">
                            <div class="pb-field">
                                <label for="dom_provinsi">Provinsi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3"/></svg></span>
                                    <select id="dom_provinsi" name="dom_provinsi" required onchange="emsifa_onchangeProvinsi('dom', this)"><option value="">Pilih provinsi</option></select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="dom_kota">Kota / Kabupaten <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h12"/></svg></span>
                                    <select id="dom_kota" name="dom_kota_kabupaten" required disabled onchange="emsifa_onchangeKota('dom', this)"><option value="">Pilih provinsi dulu</option></select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="dom_kode_pos">Kode Pos <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg></span>
                                    <input id="dom_kode_pos" name="dom_kode_pos" type="text" inputmode="numeric" value="{{ old('dom_kode_pos', session('pembelian_step2.dom_kode_pos')) }}" placeholder="41xxx" maxlength="5" pattern="[0-9]{5}" required>
                                </div>
                            </div>
                        </div>
                        <div class="pb-grid" style="margin-bottom:1.1rem;">
                            <div class="pb-field">
                                <label for="dom_kecamatan">Kecamatan <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <select id="dom_kecamatan" name="dom_kecamatan" required disabled onchange="emsifa_onchangeKecamatan('dom', this)"><option value="">Pilih kota dulu</option></select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="dom_kelurahan">Kelurahan / Desa <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <select id="dom_kelurahan" name="dom_kelurahan_desa" required disabled><option value="">Pilih kecamatan dulu</option></select>
                                </div>
                            </div>
                        </div>
                        <div class="pb-grid" style="margin-bottom:1.1rem;">
                            <div class="pb-field">
                                <label for="dom_rt">RT <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="dom_rt" name="dom_rt" type="text" inputmode="numeric" value="{{ old('dom_rt', session('pembelian_step2.dom_rt')) }}" placeholder="001" maxlength="5" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="dom_rw">RW <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="dom_rw" name="dom_rw" type="text" inputmode="numeric" value="{{ old('dom_rw', session('pembelian_step2.dom_rw')) }}" placeholder="005" maxlength="5" required>
                                </div>
                            </div>
                        </div>

                        @else
                        {{-- ══ B2B ══ --}}
                        <div class="pb-section-label">
                            <h2>Data Instansi / Perusahaan</h2><span>B2B</span>
                        </div>
                        <div class="pb-grid">
                            <div class="pb-field pb-span-2">
                                <label for="company_name">Nama Instansi / Perusahaan <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21"/></svg></span>
                                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name', session('pembelian_step2.company_name')) }}" placeholder="Nama instansi/perusahaan" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="company_type">Jenis Instansi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg></span>
                                    <input id="company_type" name="company_type" type="text" value="{{ old('company_type', session('pembelian_step2.company_type')) }}" placeholder="Perusahaan / Yayasan / Lembaga" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="business_field">Bidang Usaha <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375"/></svg></span>
                                    <input id="business_field" name="business_field" type="text" value="{{ old('business_field', session('pembelian_step2.business_field')) }}" placeholder="Galeri seni, Kurator, Restorasi" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="company_npwp">NPWP Perusahaan <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg></span>
                                    <input id="company_npwp" name="company_npwp" type="text" value="{{ old('company_npwp', session('pembelian_step2.company_npwp')) }}" placeholder="Nomor NPWP perusahaan" required>
                                </div>
                            </div>
                            <div class="pb-field pb-span-2">
                                <label for="company_address">Alamat Perusahaan <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon top"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg></span>
                                    <textarea id="company_address" name="company_address" rows="3" placeholder="Jl. Contoh No. 10..." required>{{ old('company_address', session('pembelian_step2.company_address')) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="pb-grid-3" style="margin-bottom:1.1rem;">
                            <div class="pb-field">
                                <label for="comp_provinsi">Provinsi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3"/></svg></span>
                                    <select id="comp_provinsi" name="company_province" required onchange="emsifa_onchangeProvinsi('comp', this)">
                                        <option value="">Pilih provinsi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="comp_kota">Kota / Kabupaten <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h12"/></svg></span>
                                    <select id="comp_kota" name="company_city" required disabled onchange="emsifa_onchangeKota('comp', this)">
                                        <option value="">Pilih provinsi dulu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="company_postal_code">Kode Pos <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg></span>
                                    <input id="company_postal_code" name="company_postal_code" type="text" inputmode="numeric" value="{{ old('company_postal_code', session('pembelian_step2.company_postal_code')) }}" placeholder="41xxx" maxlength="5" pattern="[0-9]{5}" required>
                                </div>
                            </div>
                        </div>
                        <div class="pb-grid" style="margin-bottom:1.1rem;">
                            <div class="pb-field">
                                <label for="comp_kecamatan">Kecamatan <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <select id="comp_kecamatan" name="company_kecamatan" required disabled onchange="emsifa_onchangeKecamatan('comp', this)">
                                        <option value="">Pilih kota dulu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="comp_kelurahan">Kelurahan / Desa <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <select id="comp_kelurahan" name="company_kelurahan_desa" required disabled>
                                        <option value="">Pilih kecamatan dulu</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="pb-grid" style="margin-bottom:1.1rem;">
                            <div class="pb-field">
                                <label for="company_rt">RT <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="company_rt" name="company_rt" type="text" inputmode="numeric" value="{{ old('company_rt', session('pembelian_step2.company_rt')) }}" placeholder="001" maxlength="5" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="company_rw">RW <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="company_rw" name="company_rw" type="text" inputmode="numeric" value="{{ old('company_rw', session('pembelian_step2.company_rw')) }}" placeholder="005" maxlength="5" required>
                                </div>
                            </div>
                        </div>
                        <hr class="pb-divider">
                        <div class="pb-section-label"><h2>Data PIC</h2><span>Penanggung Jawab</span></div>
                        <div class="pb-grid">
                            <div class="pb-field"><label for="pic_name">Nama PIC <span class="req">*</span></label><div class="pb-input-wrap"><span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></span><input id="pic_name" name="pic_name" type="text" value="{{ old('pic_name', session('pembelian_step2.pic_name')) }}" placeholder="Nama penanggung jawab" required></div></div>
                            <div class="pb-field"><label for="pic_position">Jabatan PIC <span class="req">*</span></label><div class="pb-input-wrap"><span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></span><input id="pic_position" name="pic_position" type="text" value="{{ old('pic_position', session('pembelian_step2.pic_position')) }}" placeholder="Jabatan PIC" required></div></div>
                            <div class="pb-field"><label for="pic_nik">NIK PIC <span class="req">*</span></label><div class="pb-input-wrap"><span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg></span><input id="pic_nik" name="pic_nik" type="text" inputmode="numeric" value="{{ old('pic_nik', session('pembelian_step2.pic_nik')) }}" placeholder="16 digit NIK PIC" maxlength="16" pattern="[0-9]{16}" required></div></div>
                            <div class="pb-field"><label for="pic_phone">Nomor HP PIC <span class="req">*</span></label><div class="pb-input-wrap"><span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 9h3"/></svg></span><input id="pic_phone" name="pic_phone" type="text" value="{{ old('pic_phone', session('pembelian_step2.pic_phone')) }}" placeholder="Nomor HP PIC" required></div></div>
                            <div class="pb-field pb-span-2"><label for="pic_email">Email PIC <span class="req">*</span></label><div class="pb-input-wrap"><span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg></span><input id="pic_email" name="pic_email" type="email" value="{{ old('pic_email', session('pembelian_step2.pic_email')) }}" placeholder="Email PIC" required></div></div>
                        </div>
                        <div class="pb-section-label" style="margin-top:1.25rem;">
                            <h2>Alamat Domisili PIC</h2><span>Wajib diisi</span>
                        </div>
                        <div class="pb-field" style="margin-bottom:1.1rem;">
                            <label for="pic_alamat_domisili">Alamat Lengkap <span class="req">*</span></label>
                            <div class="pb-input-wrap">
                                <span class="pb-icon top"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg></span>
                                <textarea id="pic_alamat_domisili" name="pic_alamat_domisili" rows="3" placeholder="Jl. Domisili No. 5..." required>{{ old('pic_alamat_domisili', session('pembelian_step2.pic_alamat_domisili')) }}</textarea>
                            </div>
                        </div>
                        <div class="pb-grid-3" style="margin-bottom:1.1rem;">
                            <div class="pb-field">
                                <label for="pic_provinsi">Provinsi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3"/></svg></span>
                                    <select id="pic_provinsi" name="pic_provinsi" required onchange="emsifa_onchangeProvinsi('pic', this)"><option value="">Pilih provinsi</option></select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="pic_kota">Kota / Kabupaten <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h12"/></svg></span>
                                    <select id="pic_kota" name="pic_kota_kabupaten" required disabled onchange="emsifa_onchangeKota('pic', this)"><option value="">Pilih provinsi dulu</option></select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="pic_kode_pos">Kode Pos <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg></span>
                                    <input id="pic_kode_pos" name="pic_kode_pos" type="text" inputmode="numeric" value="{{ old('pic_kode_pos', session('pembelian_step2.pic_kode_pos')) }}" placeholder="41xxx" maxlength="5" pattern="[0-9]{5}" required>
                                </div>
                            </div>
                        </div>
                        <div class="pb-grid" style="margin-bottom:1.1rem;">
                            <div class="pb-field">
                                <label for="pic_kecamatan">Kecamatan <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <select id="pic_kecamatan" name="pic_kecamatan" required disabled onchange="emsifa_onchangeKecamatan('pic', this)"><option value="">Pilih kota dulu</option></select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="pic_kelurahan">Kelurahan / Desa <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <select id="pic_kelurahan" name="pic_kelurahan_desa" required disabled><option value="">Pilih kecamatan dulu</option></select>
                                </div>
                            </div>
                        </div>
                        <div class="pb-grid" style="margin-bottom:1.1rem;">
                            <div class="pb-field">
                                <label for="pic_rt">RT <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="pic_rt" name="pic_rt" type="text" inputmode="numeric" value="{{ old('pic_rt', session('pembelian_step2.pic_rt')) }}" placeholder="001" maxlength="5" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="pic_rw">RW <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="pic_rw" name="pic_rw" type="text" inputmode="numeric" value="{{ old('pic_rw', session('pembelian_step2.pic_rw')) }}" placeholder="005" maxlength="5" required>
                                </div>
                            </div>
                        </div>
                        <hr class="pb-divider">
                        <div class="pb-section-label"><h2>Kontak Pembelian</h2><span>Wajib diisi</span></div>
                        <div class="pb-grid">
                            <div class="pb-field"><label for="nomor_hp">Nomor HP <span class="req">*</span></label><div class="pb-input-wrap"><span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 9h3"/></svg></span><input id="nomor_hp" name="nomor_hp" type="tel" inputmode="numeric" value="{{ old('nomor_hp', session('pembelian_step2.nomor_hp')) }}" placeholder="08xxxxxxxxxx" maxlength="15" required></div></div>
                            <div class="pb-field"><label for="email">Email <span class="req">*</span></label><div class="pb-input-wrap"><span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg></span><input id="email" name="email" type="email" value="{{ old('email', session('pembelian_step2.email', auth()->user()->email)) }}" placeholder="email@domain.com" required></div></div>
                        </div>
                        @endif

                        {{-- ══ ALAMAT PENGIRIMAN ══ --}}
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Alamat Pengiriman</h2><span>Wajib diisi</span>
                        </div>
                        <div class="pb-field" style="margin-bottom:1.1rem;">
                            <label for="alamat_pengiriman">Alamat Lengkap <span class="req">*</span></label>
                            <div class="pb-input-wrap">
                                <span class="pb-icon top"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg></span>
                                <textarea id="alamat_pengiriman" name="alamat_pengiriman" rows="3" placeholder="Jl. Contoh No. 10..." required>{{ old('alamat_pengiriman', session('pembelian_step2.alamat_pengiriman')) }}</textarea>
                            </div>
                        </div>
                        <div class="pb-grid-3" style="margin-bottom:1.1rem;">
                            <div class="pb-field">
                                <label for="provinsi_select">Provinsi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3"/></svg></span>
                                    <select id="provinsi_select" required onchange="bb_loadKota(this.value, this.options[this.selectedIndex].text)">
                                        <option value="">Pilih provinsi</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov['id'] }}" {{ old('province_id', session('pembelian_step2.province_id')) == $prov['id'] ? 'selected' : '' }}>
                                                {{ ucwords(strtolower($prov['name'])) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="kota_select">Kota / Kabupaten <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h12"/></svg></span>
                                    <select id="kota_select" name="kota_kabupaten" required disabled onchange="bb_onKotaChange(this)">
                                        <option value="">Pilih provinsi dulu</option>
                                    </select>
                                </div>
                                <span class="pb-hint" id="kota-loading" style="display:none;"><span class="pb-spinner"></span> Memuat kota...</span>
                            </div>
                            <div class="pb-field">
                                <label for="kode_pos">Kode Pos <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3zM6 6h.008v.008H6V6z"/></svg></span>
                                    <input id="kode_pos" name="kode_pos" type="text" inputmode="numeric" value="{{ old('kode_pos', session('pembelian_step2.kode_pos')) }}" placeholder="41xxx" maxlength="5" pattern="[0-9]{5}" required>
                                </div>
                            </div>
                        </div>
                        <div class="pb-grid" style="margin-bottom:1.1rem;">
                            <div class="pb-field">
                                <label for="ship_kecamatan">Kecamatan <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <select id="ship_kecamatan" name="kecamatan" required disabled onchange="ship_onKecamatanChange(this)">
                                        <option value="">Pilih kota/kabupaten dulu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="ship_kelurahan">Kelurahan / Desa <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <select id="ship_kelurahan" name="kelurahan_desa" required disabled>
                                        <option value="">Pilih kecamatan dulu</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="pb-grid" style="margin-bottom:1.1rem;">
                            <div class="pb-field">
                                <label for="rt">RT <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="rt" name="rt" type="text" inputmode="numeric" value="{{ old('rt', session('pembelian_step2.rt')) }}" placeholder="001" maxlength="5" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="rw">RW <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="rw" name="rw" type="text" inputmode="numeric" value="{{ old('rw', session('pembelian_step2.rw')) }}" placeholder="005" maxlength="5" required>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="city_name"   id="hidden_city_name"   value="{{ old('city_name', session('pembelian_step2.city_name', '')) }}">
                        <input type="hidden" name="province_id" id="hidden_province_id" value="{{ old('province_id', session('pembelian_step2.province_id', '')) }}">
                        <input type="hidden" name="provinsi"    id="hidden_provinsi"    value="{{ old('provinsi', session('pembelian_step2.provinsi', '')) }}">

                        {{-- ══ DOKUMEN VERIFIKASI ══ --}}
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Dokumen Verifikasi</h2>
                            <span>{{ $buyerType === 'b2b' ? 'NPWP & Surat Wajib' : 'KTP Wajib' }}</span>
                        </div>

                        @if($buyerType === 'b2c')
                        <div class="pb-upload-grid">
                            <div class="pb-upload-card" id="card-ktp">
                                <input type="file" id="upload_ktp" name="upload_ktp" accept=".pdf" required onchange="handleFile(this,'lbl-ktp','card-ktp')">
                                <div class="pb-upload-top"><div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg></div><div class="pb-upload-meta"><h4>Foto KTP</h4><span class="pb-upload-badge wajib">Wajib</span></div></div>
                                <div class="pb-upload-specs">PDF &bull; Maks. 2 MB</div>
                                <div class="pb-upload-action"><label for="upload_ktp" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label><span class="pb-upload-filename" id="lbl-ktp">Belum ada file</span></div>
                            </div>
                            <div class="pb-upload-card" id="card-npwp">
                                <input type="file" id="upload_npwp" name="upload_npwp" accept=".pdf" onchange="handleFile(this,'lbl-npwp','card-npwp')">
                                <div class="pb-upload-top"><div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg></div><div class="pb-upload-meta"><h4>NPWP</h4><span class="pb-upload-badge opsional">Opsional</span></div></div>
                                <div class="pb-upload-specs">PDF &bull; Maks. 2 MB</div>
                                <div class="pb-upload-action"><label for="upload_npwp" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label><span class="pb-upload-filename" id="lbl-npwp">Belum ada file</span></div>
                            </div>
                        </div>
                        @else
                        <div class="pb-upload-grid" style="grid-template-columns:1fr 1fr 1fr;">
                            <div class="pb-upload-card" id="card-npwp-company">
                                <input type="file" id="upload_npwp_company" name="upload_npwp_company" accept=".pdf" required onchange="handleFile(this,'lbl-npwp-company','card-npwp-company')">
                                <div class="pb-upload-top"><div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg></div><div class="pb-upload-meta"><h4>NPWP Perusahaan</h4><span class="pb-upload-badge wajib">Wajib</span></div></div>
                                <div class="pb-upload-specs">PDF &bull; Maks. 2 MB</div>
                                <div class="pb-upload-action"><label for="upload_npwp_company" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label><span class="pb-upload-filename" id="lbl-npwp-company">Belum ada file</span></div>
                            </div>
                            <div class="pb-upload-card" id="card-surat">
                                <input type="file" id="upload_purchase_request_letter" name="upload_purchase_request_letter" accept=".pdf" required onchange="handleFile(this,'lbl-surat','card-surat')">
                                <div class="pb-upload-top"><div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg></div><div class="pb-upload-meta"><h4>Surat Pembelian</h4><span class="pb-upload-badge wajib">Wajib</span></div></div>
                                <div class="pb-upload-specs">PDF &bull; Maks. 2 MB</div>
                                <div class="pb-upload-action"><label for="upload_purchase_request_letter" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label><span class="pb-upload-filename" id="lbl-surat">Belum ada file</span></div>
                            </div>
                            <div class="pb-upload-card" id="card-pic-ktp">
                                <input type="file" id="upload_pic_ktp" name="upload_pic_ktp" accept=".pdf,.jpg,.jpeg,.png" required onchange="handleFile(this,'lbl-pic-ktp','card-pic-ktp')">
                                <div class="pb-upload-top"><div class="pb-upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg></div><div class="pb-upload-meta"><h4>KTP PIC</h4><span class="pb-upload-badge wajib">Wajib</span></div></div>
                                <div class="pb-upload-specs">PDF/JPG/PNG &bull; Maks. 2 MB</div>
                                <div class="pb-upload-action"><label for="upload_pic_ktp" class="pb-upload-trigger"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>Pilih File</label><span class="pb-upload-filename" id="lbl-pic-ktp">Belum ada file</span></div>
                            </div>
                        </div>
                        @endif

                    </div>{{-- /pb-form-body --}}

                    {{-- ══ RINGKASAN BIAYA ══ --}}
                    <div style="padding: 0 1.5rem 1.5rem;">
                        <div class="pb-cost-wrap">
                            <div class="pb-cost-row">
                                <span class="lbl">Harga Koleksi</span>
                                <span class="val">Rp {{ number_format($harga_beli, 0, ',', '.') }}</span>
                            </div>
                            <div class="pb-cost-row">
                                <span class="lbl">Ongkos Kirim</span>
                                <span style="font-size:.78rem;color:rgba(255,255,255,.4);font-style:italic;">Ditentukan pengelola saat verifikasi</span>
                            </div>
                            <div class="pb-cost-total">
                                <span class="lbl">Harga Koleksi</span>
                                <span class="val">Rp {{ number_format($harga_beli, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="pb-info-box">
                            <p><strong>ℹ️ Catatan:</strong> Tidak ada PPN atau PPh. Ongkos kirim ditentukan pengelola setelah verifikasi dan tercantum di invoice.</p>
                        </div>
                    </div>

                    {{-- ══ ACTIONS ══ --}}
                    <div class="pb-actions">
                        <a href="{{ route('pembelian.step1', $koleksi) }}" class="pb-btn pb-btn-ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Langkah 1
                        </a>
                        <button type="button" onclick="openModal()" class="pb-btn pb-btn-blue">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Ajukan Pembelian
                        </button>
                        <button id="btn-submit-real" type="submit" style="display:none;"></button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi --}}
    <div id="confirm-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(10,22,40,.5);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:1.75rem;padding:2.5rem;max-width:420px;width:calc(100% - 2rem);box-shadow:0 32px 80px rgba(10,22,40,.2);text-align:center;">
            <div style="width:60px;height:60px;border-radius:50%;background:#eff6ff;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="#1d4ed8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
            </div>
            <h3 style="font-family:'DM Serif Display',serif;font-size:1.25rem;color:#0a1628;margin:0 0 .75rem;">Konfirmasi Pengajuan</h3>
            <p style="font-size:.875rem;color:#64748b;line-height:1.7;margin:0 0 1.75rem;">Setelah diajukan, data tidak dapat diubah. Pastikan semua informasi sudah benar sebelum melanjutkan.</p>
            <div style="display:flex;gap:.75rem;justify-content:center;">
                <button onclick="closeModal()" style="padding:.7rem 1.5rem;border-radius:.875rem;border:1.5px solid #e2e8f0;background:#f8fafc;color:#334155;font-size:.875rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;">Periksa Lagi</button>
                <button onclick="submitForm()" style="padding:.7rem 1.75rem;border-radius:.875rem;background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#fff;border:none;font-size:.875rem;font-weight:600;cursor:pointer;font-family:'DM Sans',sans-serif;box-shadow:0 4px 14px rgba(29,78,216,.35);">Ya, Ajukan</button>
            </div>
        </div>
    </div>

    <script>
    // ── Data dari server (Binderbyte, di-embed langsung) ────────────────
    const BB_provinces     = @json($provinces);       // [{'id':'11','name':'ACEH'}, ...]
    const BB_citiesGrouped = @json($citiesGrouped);   // {'11': [{'id':'1101','name':'KAB. SIMEULUE'}, ...], ...}
    
    const BB_savedProvinceId = @json(old('province_id', session('pembelian_step2.province_id', '')));
    const BB_savedKota       = @json(old('kota_kabupaten', session('pembelian_step2.kota_kabupaten', '')));
    const BB_savedCityName   = @json(old('city_name', session('pembelian_step2.city_name', '')));
    
    // ── Load kota saat provinsi dipilih ─────────────────────────────────
    function bb_loadKota(provinceId, provinceName) {
        const kotaSel = document.getElementById('kota_select');
    
        // Update hidden province fields
        document.getElementById('hidden_province_id').value = provinceId;
        document.getElementById('hidden_provinsi').value    = provinceName;
    
        // Reset city & shipping kecamatan
        document.getElementById('hidden_city_name').value = '';
        document.getElementById('destination_city_id').value = '';
        const shipKec = document.getElementById('ship_kecamatan');
        const shipKel = document.getElementById('ship_kelurahan');
        if (shipKec) { shipKec.innerHTML = '<option value="">Pilih kota/kabupaten dulu</option>'; shipKec.disabled = true; }
        if (shipKel) { shipKel.innerHTML = '<option value="">Pilih kecamatan dulu</option>'; shipKel.disabled = true; }
    
        if (!provinceId) {
            kotaSel.innerHTML = '<option value="">Pilih provinsi dulu</option>';
            kotaSel.disabled  = true;
            return;
        }
    
        const cities = BB_citiesGrouped[String(provinceId)] || [];
    
        kotaSel.innerHTML = '<option value="">Pilih kota/kabupaten</option>';
    
        cities.forEach(c => {
            const opt      = document.createElement('option');
            // Nama kota dari Binderbyte: "KAB. BOGOR" → kita simpan versi lowercase untuk ongkir
            const cityName = c.name; // "KAB. BOGOR" atau "KOTA BANDUNG"
            opt.value          = cityName;
            opt.dataset.cityId = c.id;
            opt.textContent    = toTitleCase(cityName);
    
            // Restore jika ada saved value
            if (BB_savedKota && cityName === BB_savedKota) {
                opt.selected = true;
                document.getElementById('hidden_city_name').value = normalizeCityForShipping(cityName);
            }
    
            kotaSel.appendChild(opt);
        });

        if (!BB_savedKota && cities.length > 0) {
            // tidak perlu trigger, biarkan user pilih
        } else if (BB_savedKota) {
            // sudah di-handle di loop forEach di atas
        }
    
        kotaSel.disabled = false;

        if (BB_savedKota) {
            const kotaSelEl = document.getElementById('kota_select');
            if (kotaSelEl.value) bb_onKotaChange(kotaSelEl);
        }
    }
    
    async function bb_onKotaChange(sel) {
        const cityName   = sel.value;
        if (!cityName) return;

        const normalized = normalizeCityForShipping(cityName);
        document.getElementById('hidden_city_name').value = normalized;

        const provinceName = document.getElementById('hidden_provinsi').value;

        document.getElementById('destination_city_id').value = '';
        if (cityName && provinceName) {
            try {
                const res  = await fetch(`/api/rajaongkir/find-city?city_name=${encodeURIComponent(normalized)}&province_name=${encodeURIComponent(provinceName)}`);
                const data = await res.json();
                document.getElementById('destination_city_id').value = data.city_id ?? '';
            } catch (e) {
                console.warn('find-city gagal:', e);
            }
            await ship_loadKecamatanFromBB(provinceName, cityName);
        }
    }

    function normalizeWilayahName(name) {
        return (name || '').toLowerCase()
            .replace(/^(kab\.|kab |kota |kabupaten |kota)\s*/i, '')
            .replace(/\s+/g, ' ')
            .trim();
    }

    async function ship_loadKecamatanFromBB(provinceName, cityName) {
        const kecSel = document.getElementById('ship_kecamatan');
        const kelSel = document.getElementById('ship_kelurahan');
        if (!kecSel || !kelSel) return;

        kecSel.innerHTML = '<option value="">Memuat kecamatan...</option>';
        kecSel.disabled = true;
        kelSel.innerHTML = '<option value="">Pilih kecamatan dulu</option>';
        kelSel.disabled = true;

        try {
            const provRes = await fetch(`${EMSIFA_BASE}/provinces`);
            const provinces = await provRes.json();
            const provNorm = normalizeWilayahName(provinceName);
            const province = provinces.find(p =>
                normalizeWilayahName(p.name) === provNorm ||
                p.name.toLowerCase().includes(provNorm)
            );
            if (!province) {
                kecSel.innerHTML = '<option value="">Kecamatan tidak ditemukan</option>';
                return;
            }

            const regRes = await fetch(`${EMSIFA_BASE}/regencies/${province.id}`);
            const regencies = await regRes.json();
            const cityNorm = normalizeWilayahName(cityName);
            const regency = regencies.find(r =>
                normalizeWilayahName(r.name) === cityNorm ||
                normalizeWilayahName(r.name).includes(cityNorm) ||
                cityNorm.includes(normalizeWilayahName(r.name))
            );
            if (!regency) {
                kecSel.innerHTML = '<option value="">Kecamatan tidak ditemukan</option>';
                return;
            }

            const distRes = await fetch(`${EMSIFA_BASE}/districts/${regency.id}`);
            const districts = await distRes.json();
            setOptions(kecSel, districts, 'id', 'name', SHIP_SAVED.kecamatan, 'Pilih kecamatan', true);
            kecSel.disabled = false;

            if (SHIP_SAVED.kecamatan) {
                const found = districts.find(d => toTitleCase(d.name) === toTitleCase(SHIP_SAVED.kecamatan));
                if (found) await ship_loadKelurahan(found.id);
            }
        } catch (e) {
            console.error('Ship kecamatan error:', e);
            kecSel.innerHTML = '<option value="">Gagal memuat kecamatan</option>';
        }
    }

    async function ship_loadKelurahan(districtId) {
        const kelSel = document.getElementById('ship_kelurahan');
        if (!kelSel) return;
        kelSel.innerHTML = '<option value="">Memuat kelurahan...</option>';
        kelSel.disabled = true;
        try {
            const res = await fetch(`${EMSIFA_BASE}/villages/${districtId}`);
            const data = await res.json();
            setOptions(kelSel, data, 'name', 'name', SHIP_SAVED.kelurahan, 'Pilih kelurahan/desa');
            kelSel.disabled = false;
        } catch (e) {
            console.error('Ship kelurahan error:', e);
            kelSel.disabled = false;
        }
    }

    function ship_onKecamatanChange(sel) {
        const opt = sel.options[sel.selectedIndex];
        const id = opt?.dataset?.id || '';
        if (id) ship_loadKelurahan(id);
    }

    // ═══ EMSIFA (domisili, perusahaan, PIC) ═══
    const EMSIFA_BASE = '/api/wilayah';
    const EMSIFA_SAVED = {
        dom: {
            provinceName: @json(old('dom_provinsi', session('pembelian_step2.dom_provinsi', ''))),
            cityName:     @json(old('dom_kota_kabupaten', session('pembelian_step2.dom_kota_kabupaten', ''))),
            kecamatan:    @json(old('dom_kecamatan', session('pembelian_step2.dom_kecamatan', ''))),
            kelurahan:    @json(old('dom_kelurahan_desa', session('pembelian_step2.dom_kelurahan_desa', ''))),
        },
        comp: {
            provinceName: @json(old('company_province', session('pembelian_step2.company_province', ''))),
            cityName:     @json(old('company_city', session('pembelian_step2.company_city', ''))),
            kecamatan:    @json(old('company_kecamatan', session('pembelian_step2.company_kecamatan', ''))),
            kelurahan:    @json(old('company_kelurahan_desa', session('pembelian_step2.company_kelurahan_desa', ''))),
        },
        pic: {
            provinceName: @json(old('pic_provinsi', session('pembelian_step2.pic_provinsi', ''))),
            cityName:     @json(old('pic_kota_kabupaten', session('pembelian_step2.pic_kota_kabupaten', ''))),
            kecamatan:    @json(old('pic_kecamatan', session('pembelian_step2.pic_kecamatan', ''))),
            kelurahan:    @json(old('pic_kelurahan_desa', session('pembelian_step2.pic_kelurahan_desa', ''))),
        },
    };
    const SHIP_SAVED = {
        kecamatan: @json(old('kecamatan', session('pembelian_step2.kecamatan', ''))),
        kelurahan: @json(old('kelurahan_desa', session('pembelian_step2.kelurahan_desa', ''))),
    };

    function getEls(prefix) {
        return {
            provinsi:  document.getElementById(prefix + '_provinsi'),
            kota:      document.getElementById(prefix + '_kota'),
            kecamatan: document.getElementById(prefix + '_kecamatan'),
            kelurahan: document.getElementById(prefix + '_kelurahan'),
        };
    }

    function setOptions(sel, items, valueKey, labelKey, selectedValue, placeholder, useNameAsValue = false) {
        sel.innerHTML = `<option value="">${placeholder}</option>`;
        items.forEach(item => {
            const opt = document.createElement('option');
            opt.value = useNameAsValue ? toTitleCase(item['name']) : item[valueKey];
            opt.dataset.id = item[valueKey];
            opt.textContent = toTitleCase(item[labelKey]);
            const compareVal = useNameAsValue ? toTitleCase(item['name']) : String(item[valueKey]);
            if (String(selectedValue) && compareVal === toTitleCase(String(selectedValue))) opt.selected = true;
            sel.appendChild(opt);
        });
    }

    async function emsifa_loadProvinsi(prefix) {
        const els = getEls(prefix);
        if (!els.provinsi) return;
        try {
            const res = await fetch(`${EMSIFA_BASE}/provinces`);
            const data = await res.json();
            const savedVal = EMSIFA_SAVED[prefix]?.provinceName || '';
            setOptions(els.provinsi, data, 'id', 'name', savedVal, 'Pilih provinsi', true);
            let idForChild = '';
            if (savedVal) {
                const found = data.find(p => toTitleCase(p.name) === toTitleCase(savedVal));
                if (found) idForChild = found.id;
            }
            if (idForChild) await emsifa_loadKota(prefix, idForChild);
        } catch (e) {
            console.error('Emsifa provinces error:', e);
        }
    }

    async function emsifa_loadKota(prefix, provinceId) {
        const els = getEls(prefix);
        if (!els.kota) return;
        if (!provinceId) {
            els.kota.innerHTML = '<option value="">Pilih provinsi dulu</option>';
            els.kota.disabled = true;
            return;
        }
        els.kota.innerHTML = '<option value="">Memuat kota...</option>';
        els.kota.disabled = true;
        try {
            const res = await fetch(`${EMSIFA_BASE}/regencies/${provinceId}`);
            const data = await res.json();
            const savedCityName = EMSIFA_SAVED[prefix]?.cityName || '';
            let idForChild = '';
            if (savedCityName) {
                const found = data.find(c => toTitleCase(c.name) === toTitleCase(savedCityName));
                if (found) idForChild = found.id;
            }
            setOptions(els.kota, data, 'id', 'name', savedCityName, 'Pilih kota/kabupaten', true);
            els.kota.disabled = false;
            if (idForChild) await emsifa_loadKecamatan(prefix, idForChild);
        } catch (e) {
            console.error('Emsifa regencies error:', e);
            els.kota.disabled = false;
        }
    }

    async function emsifa_loadKecamatan(prefix, cityId) {
        const els = getEls(prefix);
        if (!els.kecamatan) return;
        if (!cityId) {
            els.kecamatan.innerHTML = '<option value="">Pilih kota dulu</option>';
            els.kecamatan.disabled = true;
            if (els.kelurahan) {
                els.kelurahan.innerHTML = '<option value="">Pilih kecamatan dulu</option>';
                els.kelurahan.disabled = true;
            }
            return;
        }
        els.kecamatan.innerHTML = '<option value="">Memuat kecamatan...</option>';
        els.kecamatan.disabled = true;
        try {
            const res = await fetch(`${EMSIFA_BASE}/districts/${cityId}`);
            const data = await res.json();
            const savedKec = EMSIFA_SAVED[prefix]?.kecamatan || '';
            setOptions(els.kecamatan, data, 'id', 'name', savedKec, 'Pilih kecamatan', true);
            els.kecamatan.disabled = false;
            if (savedKec) {
                const found = data.find(d => toTitleCase(d.name) === toTitleCase(savedKec));
                if (found) await emsifa_loadVillages(prefix, found.id);
            }
        } catch (e) {
            console.error('Emsifa districts error:', e);
            els.kecamatan.disabled = false;
        }
    }

    async function emsifa_loadVillages(prefix, districtId) {
        const els = getEls(prefix);
        if (!els.kelurahan) return;
        if (!districtId) {
            els.kelurahan.innerHTML = '<option value="">Pilih kecamatan dulu</option>';
            els.kelurahan.disabled = true;
            return;
        }
        els.kelurahan.innerHTML = '<option value="">Memuat kelurahan...</option>';
        els.kelurahan.disabled = true;
        try {
            const res = await fetch(`${EMSIFA_BASE}/villages/${districtId}`);
            const data = await res.json();
            const savedKel = EMSIFA_SAVED[prefix]?.kelurahan || '';
            setOptions(els.kelurahan, data, 'name', 'name', savedKel, 'Pilih kelurahan/desa');
            els.kelurahan.disabled = false;
        } catch (e) {
            console.error('Emsifa villages error:', e);
            els.kelurahan.disabled = false;
        }
    }

    function emsifa_onchangeProvinsi(prefix, sel) {
        const id = sel.options[sel.selectedIndex]?.dataset?.id || sel.value;
        emsifa_loadKota(prefix, id);
    }

    function emsifa_onchangeKota(prefix, sel) {
        const id = sel.options[sel.selectedIndex]?.dataset?.id || sel.value;
        emsifa_loadKecamatan(prefix, id);
    }

    function emsifa_onchangeKecamatan(prefix, sel) {
        const id = sel.options[sel.selectedIndex]?.dataset?.id || sel.value;
        emsifa_loadVillages(prefix, id);
    }
    
    // Normalisasi nama kota untuk Binderbyte cost API
    // "KAB. BOGOR" → "bogor" | "KOTA BANDUNG" → "bandung"
    function normalizeCityForShipping(name) {
        return name
            .toLowerCase()
            .replace(/^(kab\.|kab |kota |kabupaten |kota)\s*/i, '')
            .trim();
    }
    
    function toTitleCase(str) {
        return str.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
    }
    
    // ── Restore state saat page load ────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        const provSel = document.getElementById('provinsi_select');
    
        if (BB_savedProvinceId) {
            provSel.value = BB_savedProvinceId;
            const selectedOpt = provSel.options[provSel.selectedIndex];
            const provName    = selectedOpt ? selectedOpt.text : '';
            bb_loadKota(BB_savedProvinceId, provName);
            if (BB_savedKota) {
                ship_loadKecamatanFromBB(provName, BB_savedKota);
            }
        }

        if (document.getElementById('dom_provinsi')) emsifa_loadProvinsi('dom');
        if (document.getElementById('comp_provinsi')) emsifa_loadProvinsi('comp');
        if (document.getElementById('pic_provinsi')) emsifa_loadProvinsi('pic');

        // Numeric-only fields
        ['rt','rw','kode_pos','dom_rt','dom_rw','dom_kode_pos','company_rt','company_rw','company_postal_code','pic_rt','pic_rw','pic_kode_pos','nomor_hp','pic_nik','nik'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', () => { el.value = el.value.replace(/[^0-9]/g, ''); });
        });
    
        // Upload file handler
        window.handleFile = function(input, labelId, cardId) {
            const label = document.getElementById(labelId);
            const card  = document.getElementById(cardId);
            if (input.files && input.files[0]) {
                const name = input.files[0].name;
                label.textContent = name.length > 22 ? name.slice(0, 19) + '...' : name;
                label.classList.add('selected');
                card.classList.add('has-file');
            } else {
                label.textContent = 'Belum ada file';
                label.classList.remove('selected');
                card.classList.remove('has-file');
            }
        };
    
        // Modal
        window.openModal = () => {
            const cityId = document.getElementById('destination_city_id').value;
            if (!cityId) {
                alert('Pilih kota/kabupaten terlebih dahulu atau tunggu sebentar.');
                return;
            }
            document.getElementById('confirm-modal').style.display = 'flex';
        };        
        window.closeModal = () => { document.getElementById('confirm-modal').style.display = 'none'; };
        window.submitForm = () => { document.getElementById('btn-submit-real').click(); };
    
        document.getElementById('confirm-modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    });
    </script>

</x-app-layout>
