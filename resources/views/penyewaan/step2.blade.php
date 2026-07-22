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

        /* ── FORM BODY ── */
        .pb-form-body { padding: 1.5rem; }

        /* ── SECTION LABEL ── */
        .pb-section-label { display: flex; align-items: center; gap: .65rem; margin-bottom: 1.25rem; margin-top: 1.75rem; }
        .pb-section-label:first-child { margin-top: 0; }
        .pb-section-label::before { content: ''; width: 3px; height: 16px; background: linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius: 99px; flex-shrink: 0; }
        .pb-section-label h2 { font-size: .76rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--navy); margin: 0; }
        .pb-section-label span { margin-left: auto; background: #eff6ff; color: var(--blue); font-size: .7rem; font-weight: 600; padding: .2rem .75rem; border-radius: 99px; }
        .pb-section-label span.opt { background: #f1f5f9; color: var(--slate); }

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

        /* ── SELECT LOADING STATE ── */
        .pb-field select:disabled { opacity: .5; cursor: not-allowed; }

        /* ── DIVIDER ── */
        .pb-divider { border: none; border-top: 1.5px solid #f0f4f8; margin: 1.5rem 0; }

        /* ── ERROR BOX ── */
        .pb-error-box { background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 1.25rem; padding: 1.1rem 1.5rem; color: #b91c1c; margin-bottom: 1.25rem; }
        .pb-error-box h3 { font-weight: 700; font-size: .85rem; margin: 0 0 .45rem; }
        .pb-error-box ul { padding-left: 1.25rem; font-size: .8rem; line-height: 1.8; margin: 0; }

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
        .pb-btn-blue   { background: linear-gradient(135deg,var(--blue),#2563eb); color: #fff; box-shadow: 0 4px 14px rgba(29,78,216,.25); }
        .pb-btn-blue:hover   { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(29,78,216,.35); }

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
                        @if($painting->daily_rate > 0)
                        <p style="font-size:.82rem;color:rgba(255,255,255,.7);margin:.3rem 0 0;font-weight:600;">
                            💰 Tarif Sewa: Rp {{ number_format($painting->daily_rate, 0, ',', '.') }} / hari
                        </p>
                        @endif
                    </div>
                    <div class="pb-hero-actions">
                        <a href="{{ route('penyewaan.step1', ['koleksi' => $painting->id]) }}" class="pb-hero-btn pb-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Langkah 1
                        </a>
                    </div>
                </div>
            </div>

            {{-- Progress --}}
            <div class="pb-progress-wrap">
                <div class="pb-progress-track">
                    <div class="pb-progress-fill" style="width: 66%;"></div>
                </div>
                <div class="pb-steps-row">
                    <div class="pb-step-pill done">
                        <div class="pb-step-pill-num">✓</div>
                        Jenis Penyewa
                    </div>
                    <div class="pb-step-pill active">
                        <div class="pb-step-pill-num">2</div>
                        @if($rentalType === 'instansi') Identitas &amp; PIC @else Info Pribadi &amp; Kontak @endif
                    </div>
                    <div class="pb-step-pill pending">
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
                    <h3>Langkah 2 —
                        @if($rentalType === 'instansi') Identitas Instansi &amp; Data PIC
                        @else Informasi Pribadi &amp; Kontak
                        @endif
                    </h3>
                </div>

                <form action="{{ route('penyewaan.storeStep2', ['koleksi' => $painting->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="rental_type" value="{{ $rentalType }}">

                    <div class="pb-form-body">

                        {{-- ════ PERSEORANGAN ════ --}}
                        @if($rentalType === 'perseorangan')

                        <div class="pb-section-label" style="margin-top:0;">
                            <h2>Data Identitas Diri</h2>
                            <span>Sesuai KTP</span>
                        </div>
                        <div class="pb-grid">

                            <div class="pb-field pb-span-2">
                                <label for="contact_name">Nama Lengkap <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></span>
                                    <input id="contact_name" name="contact_name" type="text"
                                        value="{{ old('contact_name', session('penyewaan_step2.contact_name', '')) }}"
                                        placeholder="Nama lengkap sesuai KTP" required>
                                </div>
                            </div>

                            <div class="pb-field pb-span-2">
                                <label for="nik">NIK <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg></span>
                                    <input id="nik" name="nik" type="text" inputmode="numeric"
                                        value="{{ old('nik', session('penyewaan_step2.nik', '')) }}"
                                        placeholder="16 digit NIK sesuai KTP"
                                        pattern="[0-9]{16}" maxlength="16" required>
                                </div>
                            </div>

                            <div class="pb-field">
                                <label for="tempat_lahir">Tempat Lahir <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <input id="tempat_lahir" name="tempat_lahir" type="text"
                                        value="{{ old('tempat_lahir', session('penyewaan_step2.tempat_lahir', '')) }}"
                                        placeholder="Contoh: Jakarta" required>
                                </div>
                            </div>

                            <div class="pb-field">
                                <label for="tanggal_lahir">Tanggal Lahir <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg></span>
                                    <input id="tanggal_lahir" name="tanggal_lahir" type="date"
                                        value="{{ old('tanggal_lahir', session('penyewaan_step2.tanggal_lahir', '')) }}" required>
                                </div>
                            </div>

                            <div class="pb-field">
                                <label for="jenis_kelamin">Jenis Kelamin <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></span>
                                    <select id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', session('penyewaan_step2.jenis_kelamin', '')) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', session('penyewaan_step2.jenis_kelamin', '')) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="pb-field">
                                <label for="pekerjaan">Pekerjaan <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></span>
                                    <input id="pekerjaan" name="pekerjaan" type="text"
                                        value="{{ old('pekerjaan', session('penyewaan_step2.pekerjaan', '')) }}"
                                        placeholder="Contoh: Guru, Desainer, PNS" required>
                                </div>
                            </div>

                            <div class="pb-field pb-span-2">
                                <label for="npwp">NPWP</label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg></span>
                                    <input id="npwp" name="npwp" type="text"
                                        value="{{ old('npwp', session('penyewaan_step2.npwp', '')) }}"
                                        placeholder="Nomor NPWP (opsional)">
                                </div>
                                <span class="pb-hint">Opsional. Isi jika memiliki NPWP aktif.</span>
                            </div>

                        </div>

                        {{-- Kontak Perseorangan --}}
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Informasi Kontak</h2>
                            <span>Wajib diisi</span>
                        </div>
                        <div class="pb-grid">
                            <div class="pb-field">
                                <label for="contact_phone">Nomor HP <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 9h3"/></svg></span>
                                    <input id="contact_phone" name="contact_phone" type="tel" inputmode="numeric"
                                        value="{{ old('contact_phone', session('penyewaan_step2.contact_phone', '')) }}"
                                        placeholder="08xxxxxxxxxx" maxlength="15" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="contact_email">Email <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg></span>
                                    <input id="contact_email" name="contact_email" type="email"
                                        value="{{ old('contact_email', session('penyewaan_step2.contact_email', auth()->user()->email)) }}"
                                        placeholder="email@domain.com" required>
                                </div>
                            </div>
                        </div>

                        {{-- ════ INSTANSI ════ --}}
                        @else

                        <div class="pb-section-label" style="margin-top:0;">
                            <h2>Informasi Instansi</h2>
                            <span>Wajib diisi</span>
                        </div>
                        <div class="pb-grid">

                            <div class="pb-field pb-span-2">
                                <label for="nama_instansi">Nama Instansi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6 12.75h.008v.008H6V12.75zm0 3h.008v.008H6v-.008zm0 3h.008v.008H6v-.008zm3-6h.008v.008H9V9.75zm0 3h.008v.008H9v-.008zm0 3h.008v.008H9v-.008zm3-6h.008v.008H12V9.75zm0 3h.008v.008H12v-.008zm0 3h.008v.008H12v-.008z"/></svg></span>
                                    <input id="nama_instansi" name="nama_instansi" type="text"
                                        value="{{ old('nama_instansi', session('penyewaan_step2.nama_instansi', '')) }}"
                                        placeholder="PT / CV / Yayasan / Dinas..." required>
                                </div>
                                <span class="pb-hint">Nama resmi instansi sesuai akta pendirian atau dokumen legal.</span>
                            </div>

                            <div class="pb-field">
                                <label for="jenis_instansi">Jenis Instansi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg></span>
                                    <select id="jenis_instansi" name="jenis_instansi" required>
                                        <option value="">Pilih jenis instansi</option>
                                        @foreach(['Perusahaan','Hotel','Kampus','Event Organizer','Dinas Pemerintahan','Bank','Restoran Premium','Lainnya'] as $opt)
                                            <option value="{{ $opt }}" {{ old('jenis_instansi', session('penyewaan_step2.jenis_instansi','')) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="pb-field">
                                <label for="bidang_usaha">Bidang Usaha <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"/></svg></span>
                                    <input id="bidang_usaha" name="bidang_usaha" type="text"
                                        value="{{ old('bidang_usaha', session('penyewaan_step2.bidang_usaha','')) }}"
                                        placeholder="Contoh: Teknologi, Seni Budaya, Perbankan" required>
                                </div>
                            </div>

                            <div id="jenis_instansi_lain_field" class="pb-field pb-span-2" style="display:none;">
                                <label for="jenis_instansi_lain">Jelaskan Jenis Instansi Lainnya <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg></span>
                                    <input id="jenis_instansi_lain" name="jenis_instansi_lain" type="text"
                                        value="{{ old('jenis_instansi_lain', session('penyewaan_step2.jenis_instansi_lain','')) }}"
                                        placeholder="Tuliskan jenis instansi">
                                </div>
                            </div>

                            <div class="pb-field">
                                <label for="email_instansi">Email Resmi Instansi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg></span>
                                    <input id="email_instansi" name="email_instansi" type="email"
                                        value="{{ old('email_instansi', session('penyewaan_step2.email_instansi','')) }}"
                                        placeholder="info@instansi.com" required>
                                </div>
                            </div>

                            <div class="pb-field">
                                <label for="telepon_kantor">Nomor Telepon Kantor <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg></span>
                                    <input id="telepon_kantor" name="telepon_kantor" type="text" inputmode="numeric"
                                        value="{{ old('telepon_kantor', session('penyewaan_step2.telepon_kantor','')) }}"
                                        placeholder="(022) 1234-5678" required>
                                </div>
                            </div>

                            <div class="pb-field">
                                <label for="website_instansi">Website Instansi</label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253"/></svg></span>
                                    <input id="website_instansi" name="website_instansi" type="url"
                                        value="{{ old('website_instansi', session('penyewaan_step2.website_instansi','')) }}"
                                        placeholder="https://www.instansi.com">
                                </div>
                                <span class="pb-hint">Opsional.</span>
                            </div>

                        </div>

                        {{-- ── Alamat Instansi ── --}}
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Alamat Instansi</h2>
                            <span>Wajib diisi</span>
                        </div>

                        <div class="pb-field" style="margin-bottom:1.25rem;">
                            <label for="alamat_instansi">Alamat Instansi <span class="req">*</span></label>
                            <div class="pb-input-wrap">
                                <span class="pb-icon top"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                <textarea id="alamat_instansi" name="alamat_instansi" rows="3"
                                    placeholder="Jl. Contoh No. 10..." required>{{ old('alamat_instansi', session('penyewaan_step2.alamat_instansi','')) }}</textarea>
                            </div>
                        </div>

                        {{-- Provinsi → Kota → Kode Pos (baris 1) --}}
                        <div class="pb-grid-3">
                            <div class="pb-field">
                                <label for="inst_provinsi">Provinsi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3"/></svg></span>
                                    <select id="inst_provinsi" name="provinsi_instansi" required
                                        onchange="emsifa_onchangeProvinsi('inst', this)">
                                        <option value="">Pilih provinsi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="inst_kota">Kota / Kabupaten <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21"/></svg></span>
                                    <select id="inst_kota" name="kota_instansi" required disabled
                                        onchange="emsifa_onchangeKota('inst', this)">
                                        <option value="">Pilih provinsi dulu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="kode_pos_instansi">Kode Pos <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg></span>
                                    <input id="kode_pos_instansi" name="kode_pos_instansi" type="text" inputmode="numeric"
                                        value="{{ old('kode_pos_instansi', session('penyewaan_step2.kode_pos_instansi','')) }}"
                                        placeholder="40xxx" maxlength="5" required>
                                </div>
                            </div>
                        </div>

                        {{-- Kecamatan → Kelurahan/Desa → RT → RW (baris 2 instansi) --}}
                        <div class="pb-grid" style="margin-bottom:1.25rem;">
                            <div class="pb-field">
                                <label for="inst_kecamatan">Kecamatan <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <select id="inst_kecamatan" name="kecamatan_instansi" required disabled
                                        onchange="emsifa_onchangeKecamatan('inst', this)">
                                        <option value="">Pilih kota dulu</option>
                                    </select>
                                </div>
                                <input type="hidden" name="inst_district_id" id="inst_hidden_district_id" value="{{ old('inst_district_id', session('penyewaan_step2.inst_district_id','')) }}">
                            </div>
                            <div class="pb-field">
                                <label for="inst_kelurahan">Kelurahan / Desa <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                                    <select id="inst_kelurahan" name="kelurahan_desa_instansi" required disabled>
                                        <option value="">Pilih kecamatan dulu</option>
                                    </select>
                                </div>
                                <input type="hidden" name="inst_province_id" id="inst_hidden_province_id"
                                    value="{{ old('inst_province_id', session('penyewaan_step2.inst_province_id','')) }}">
                                <input type="hidden" name="inst_city_id" id="inst_hidden_city_id"
                                    value="{{ old('inst_city_id', session('penyewaan_step2.inst_city_id','')) }}">
                            </div>
                        </div>
                        <div class="pb-grid" style="margin-bottom:1.25rem;">
                            <div class="pb-field">
                                <label for="rt_instansi">RT <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="rt_instansi" name="rt_instansi" type="text" inputmode="numeric"
                                        value="{{ old('rt_instansi', session('penyewaan_step2.rt_instansi','')) }}"
                                        placeholder="001" maxlength="5" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="rw_instansi">RW <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="rw_instansi" name="rw_instansi" type="text" inputmode="numeric"
                                        value="{{ old('rw_instansi', session('penyewaan_step2.rw_instansi','')) }}"
                                        placeholder="005" maxlength="5" required>
                                </div>
                            </div>
                        </div>

                        {{-- Legalitas --}}
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Legalitas Instansi</h2>
                            <span>NPWP Wajib</span>
                        </div>
                        <div class="pb-grid">
                            <div class="pb-field">
                                <label for="npwp_instansi">NPWP Instansi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185z"/></svg></span>
                                    <input id="npwp_instansi" name="npwp_instansi" type="text"
                                        value="{{ old('npwp_instansi', session('penyewaan_step2.npwp_instansi','')) }}"
                                        placeholder="xx.xxx.xxx.x-xxx.xxx" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="nomor_nib">Nomor NIB</label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5"/></svg></span>
                                    <input id="nomor_nib" name="nomor_nib" type="text"
                                        value="{{ old('nomor_nib', session('penyewaan_step2.nomor_nib','')) }}"
                                        placeholder="Nomor Induk Berusaha">
                                </div>
                                <span class="pb-hint">Opsional.</span>
                            </div>
                        </div>

                        {{-- PIC --}}
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Penanggung Jawab (PIC)</h2>
                            <span>Wajib diisi</span>
                        </div>
                        <div class="pb-grid">

                            <div class="pb-field pb-span-2">
                                <label for="pic_name">Nama PIC <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg></span>
                                    <input id="pic_name" name="pic_name" type="text"
                                        value="{{ old('pic_name', session('penyewaan_step2.pic_name', '')) }}"
                                        placeholder="Nama lengkap penanggung jawab" required>
                                </div>
                            </div>

                            <div class="pb-field">
                                <label for="pic_jabatan">Jabatan PIC <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0"/></svg></span>
                                    <input id="pic_jabatan" name="pic_jabatan" type="text"
                                        value="{{ old('pic_jabatan', session('penyewaan_step2.pic_jabatan', '')) }}"
                                        placeholder="Contoh: Manajer, Direktur, Staff" required>
                                </div>
                            </div>

                            <div class="pb-field">
                                <label for="pic_nik">NIK PIC <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg></span>
                                    <input id="pic_nik" name="pic_nik" type="text" inputmode="numeric"
                                        value="{{ old('pic_nik', session('penyewaan_step2.pic_nik', '')) }}"
                                        placeholder="16 digit NIK sesuai KTP"
                                        pattern="[0-9]{16}" maxlength="16" required>
                                </div>
                            </div>

                            <div class="pb-field">
                                <label for="pic_phone">Nomor HP PIC <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 9h3"/></svg></span>
                                    <input id="pic_phone" name="pic_phone" type="tel" inputmode="numeric"
                                        value="{{ old('pic_phone', session('penyewaan_step2.pic_phone', '')) }}"
                                        placeholder="08xxxxxxxxxx" maxlength="15" required>
                                </div>
                            </div>

                            <div class="pb-field">
                                <label for="pic_email">Email PIC <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg></span>
                                    <input id="pic_email" name="pic_email" type="email"
                                        value="{{ old('pic_email', session('penyewaan_step2.pic_email', '')) }}"
                                        placeholder="pic@instansi.com" required>
                                </div>
                            </div>

                        </div>

                        @endif
                        {{-- /endif rental_type --}}

                        {{-- ════ INFORMASI ALAMAT DOMISILI (semua tipe) ════ --}}
                        <hr class="pb-divider">
                        <div class="pb-section-label">
                            <h2>Informasi Alamat</h2>
                            <span>Wajib diisi</span>
                        </div>

                        <div class="pb-field" style="margin-bottom:1.25rem;">
                            <label for="alamat_domisili">
                                @if($rentalType === 'instansi') Alamat Domisili PIC @else Alamat Domisili @endif
                                <span class="req">*</span>
                            </label>
                            <div class="pb-input-wrap">
                                <span class="pb-icon top"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg></span>
                                <textarea id="alamat_domisili" name="alamat_domisili" rows="3"
                                    placeholder="Jl. Domisili No. 5..." required>{{ old('alamat_domisili', session('penyewaan_step2.alamat_domisili', '')) }}</textarea>
                            </div>
                        </div>

                        {{-- Baris 1: Provinsi → Kota → Kode Pos --}}
                        <div class="pb-grid-3">
                            <div class="pb-field">
                                <label for="dom_provinsi">Provinsi <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253"/></svg></span>
                                    <select id="dom_provinsi" name="provinsi" required
                                        onchange="emsifa_loadKota('dom', this.value)">
                                        <option value="">Pilih provinsi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="dom_kota">Kota / Kabupaten <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75"/></svg></span>
                                    <select id="dom_kota" name="kota_kabupaten" required disabled
                                        onchange="emsifa_loadKecamatan('dom', this.value)">
                                        <option value="">Pilih provinsi dulu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="kode_pos">Kode Pos <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg></span>
                                    <input id="kode_pos" name="kode_pos" type="text" inputmode="numeric"
                                        value="{{ old('kode_pos', session('penyewaan_step2.kode_pos', '')) }}"
                                        placeholder="40xxx" maxlength="5" pattern="[0-9]{5}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Baris 2: Kelurahan/Desa → RT → RW --}}
                        <div class="pb-grid" style="margin-bottom:1.25rem;">
                            <div class="pb-field">
                                <label for="dom_kecamatan">Kecamatan <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon">{{-- icon --}}</span>
                                    <select id="dom_kecamatan" name="kecamatan" required disabled
                                        onchange="emsifa_loadVillages('dom', this.value)">
                                        <option value="">Pilih kota dulu</option>
                                    </select>
                                </div>
                                <input type="hidden" name="dom_district_id" id="dom_hidden_district_id" value="">
                            </div>
                            <div class="pb-field">
                                <label for="dom_kelurahan">Kelurahan / Desa <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon">{{-- icon --}}</span>
                                    <select id="dom_kelurahan" name="kelurahan_desa" required disabled>
                                        <option value="">Pilih kecamatan dulu</option>
                                    </select>
                                </div>
                                <input type="hidden" name="dom_province_id" id="dom_hidden_province_id"
                                    value="{{ old('dom_province_id', session('penyewaan_step2.dom_province_id','')) }}">
                                <input type="hidden" name="dom_city_id" id="dom_hidden_city_id"
                                    value="{{ old('dom_city_id', session('penyewaan_step2.dom_city_id','')) }}">
                            </div>
                            <div class="pb-field">
                                <label for="rt">RT <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="rt" name="rt" type="text" inputmode="numeric"
                                        value="{{ old('rt', session('penyewaan_step2.rt', '')) }}"
                                        placeholder="001" maxlength="5" required>
                                </div>
                            </div>
                            <div class="pb-field">
                                <label for="rw">RW <span class="req">*</span></label>
                                <div class="pb-input-wrap">
                                    <span class="pb-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg></span>
                                    <input id="rw" name="rw" type="text" inputmode="numeric"
                                        value="{{ old('rw', session('penyewaan_step2.rw', '')) }}"
                                        placeholder="005" maxlength="5" required>
                                </div>
                            </div>
                        </div>

                    </div>{{-- /pb-form-body --}}

                    {{-- ACTIONS --}}
                    <div class="pb-actions">
                        <div class="pb-actions-left">
                            <a href="{{ route('penyewaan.step1', ['koleksi' => $painting->id]) }}" class="pb-btn pb-btn-ghost">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                                Kembali ke Langkah 1
                            </a>
                            <a href="{{ route('penyewaan.index') }}" class="pb-btn pb-btn-ghost">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/></svg>
                                Kembali ke Katalog
                            </a>
                        </div>
                        <div class="pb-actions-right">
                            <button type="submit" name="save_draft" value="1" class="pb-btn pb-btn-draft">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/></svg>
                                Simpan Draft
                            </button>
                            <button type="submit" class="pb-btn pb-btn-blue">
                                Simpan &amp; Lanjutkan
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            </button>
                        </div>
                    </div>

                </form>
            </div>{{-- /pb-card --}}
        </div>{{-- /pb-content --}}
    </div>{{-- /pb-root --}}

<script>
// ═══════════════════════════════════════════════════════
//  EMSIFA API — Provinsi, Kota/Kabupaten, Kecamatan/Desa
//  API: https://emsifa.github.io/api-wilayah-indonesia/api/
//  Dipakai di Step 2 SAJA. Step 3 tetap pakai Binderbyte.
// ═══════════════════════════════════════════════════════

const EMSIFA_BASE = '/api/wilayah';

// Saved values dari session/old input untuk restore saat kembali
const SAVED = {
    dom: {
        provinceId:   '{{ old("dom_province_id",  session("penyewaan_step2.dom_province_id",  "")) }}',
        provinceName: '{{ old("provinsi",          session("penyewaan_step2.provinsi",         "")) }}',
        cityId:       '{{ old("dom_city_id",       session("penyewaan_step2.dom_city_id",      "")) }}',
        cityName:     '{{ old("kota_kabupaten",    session("penyewaan_step2.kota_kabupaten",   "")) }}',
        districtId:   '{{ old("dom_district_id",   session("penyewaan_step2.dom_district_id",  "")) }}',
        kecamatan:    '{{ old("kecamatan",         session("penyewaan_step2.kecamatan",        "")) }}',
        kelurahan:    '{{ old("kelurahan_desa",    session("penyewaan_step2.kelurahan_desa",   "")) }}',
    },
    inst: {
        provinceId:   '{{ old("inst_province_id",       session("penyewaan_step2.inst_province_id",       "")) }}',
        provinceName: '{{ old("provinsi_instansi",      session("penyewaan_step2.provinsi_instansi",      "")) }}',
        cityId:       '{{ old("inst_city_id",           session("penyewaan_step2.inst_city_id",           "")) }}',
        cityName:     '{{ old("kota_instansi",          session("penyewaan_step2.kota_instansi",          "")) }}',
        districtId:   '{{ old("inst_district_id",       session("penyewaan_step2.inst_district_id",       "")) }}',
        kecamatan:    '{{ old("kecamatan_instansi",     session("penyewaan_step2.kecamatan_instansi",     "")) }}',
        kelurahan:    '{{ old("kelurahan_desa_instansi",session("penyewaan_step2.kelurahan_desa_instansi","")) }}',
    },
};

function getEls(prefix) {
    return {
        provinsi:    document.getElementById(prefix + '_provinsi'),
        kota:        document.getElementById(prefix + '_kota'),
        kecamatan:   document.getElementById(prefix + '_kecamatan'),
        kelurahan:   document.getElementById(prefix + '_kelurahan'),
        hidProv:     document.getElementById(prefix + '_hidden_province_id'),
        hidCity:     document.getElementById(prefix + '_hidden_city_id'),
        hidDistrict: document.getElementById(prefix + '_hidden_district_id'),
    };
}

// Helper: Title Case
function toTitleCase(str) {
    return str.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
}

// Helper: set select options
// SESUDAH — tambahkan parameter useNameAsValue:
function setOptions(sel, items, valueKey, labelKey, selectedValue, placeholder, useNameAsValue = false) {
    sel.innerHTML = `<option value="">${placeholder}</option>`;
    items.forEach(item => {
        const opt = document.createElement('option');
        opt.value = useNameAsValue ? toTitleCase(item['name']) : item[valueKey];
        opt.dataset.id = item[valueKey]; // simpan ID di data-id untuk load berikutnya
        opt.textContent = toTitleCase(item[labelKey]);
        const compareVal = useNameAsValue ? toTitleCase(item['name']) : String(item[valueKey]);
        if (String(selectedValue) && compareVal === toTitleCase(String(selectedValue))) opt.selected = true;
        sel.appendChild(opt);
    });
}

// ── Load semua provinsi ──
async function emsifa_loadProvinsi(prefix, savedProvinceId) {
    const els = getEls(prefix);
    if (!els.provinsi) return;

    try {
        const res  = await fetch(`${EMSIFA_BASE}/provinces`);
        const data = await res.json();

        const useNameAsValue = (prefix === 'inst');
        const savedVal = useNameAsValue
            ? SAVED[prefix]?.provinceName   // restore by nama
            : savedProvinceId;   
        setOptions(els.provinsi, data, 'id', 'name', savedVal, 'Pilih provinsi', useNameAsValue);

        let idForChild = savedProvinceId;
        if (useNameAsValue && !idForChild && SAVED[prefix]?.provinceName) {
            const found = data.find(p =>
                toTitleCase(p.name) === toTitleCase(SAVED[prefix].provinceName)
            );
            if (found) idForChild = found.id;
        }

        if (idForChild) {
            await emsifa_loadKota(prefix, idForChild);
        }
    } catch (e) {
        console.error('Emsifa provinces error:', e);
    }
}

// ── Load kota berdasarkan province ID ──
async function emsifa_loadKota(prefix, provinceId) {
    const els = getEls(prefix);
    if (!els.kota) return;

    if (els.hidProv) els.hidProv.value = provinceId;

    if (!provinceId) {
        els.kota.innerHTML = '<option value="">Pilih provinsi dulu</option>';
        els.kota.disabled = true;
        return;
    }

    els.kota.innerHTML = '<option value="">Memuat kota...</option>';
    els.kota.disabled = true;

    try {
        const res  = await fetch(`${EMSIFA_BASE}/regencies/${provinceId}`);
        const data = await res.json();

        const useNameAsValue = (prefix === 'inst');
        const savedCityId   = SAVED[prefix]?.cityId   || '';
        const savedCityName = SAVED[prefix]?.cityName || '';

        // Cari ID untuk load kecamatan nanti
        let idForChild = savedCityId;
        if (!idForChild && savedCityName) {
            const found = data.find(c =>
                toTitleCase(c.name) === toTitleCase(savedCityName) ||
                c.name.toLowerCase().includes(savedCityName.toLowerCase())
            );
            if (found) idForChild = found.id;
        }

        // Value select: nama (inst) atau ID (dom)
        const selectedVal = useNameAsValue ? savedCityName : (idForChild || savedCityId);

        setOptions(els.kota, data, 'id', 'name', selectedVal, 'Pilih kota/kabupaten', useNameAsValue);
        els.kota.disabled = false;

        if (idForChild) {
            await emsifa_loadKecamatan(prefix, idForChild);
        }
    } catch (e) {
        console.error('Emsifa regencies error:', e);
        els.kota.innerHTML = '<option value="">Gagal memuat kota</option>';
        els.kota.disabled = false;
    }
}

// ── Load kecamatan/desa berdasarkan city ID ──
// Ganti nama fungsi lama dan ubah isinya
async function emsifa_loadKecamatan(prefix, cityId) {
    const els = getEls(prefix);
    if (!els.kecamatan) return;

    if (els.hidCity) els.hidCity.value = cityId;

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
        const res  = await fetch(`${EMSIFA_BASE}/districts/${cityId}`);
        const data = await res.json();

        const useNameAsValue = (prefix === 'inst');
        const savedKec = SAVED[prefix]?.kecamatan || '';

        setOptions(els.kecamatan, data, 'id', 'name', savedKec, 'Pilih kecamatan', useNameAsValue);
        els.kecamatan.disabled = false;

        // Untuk load villages, tetap butuh ID
        if (savedKec) {
            const found = data.find(d =>
                String(d.id) === String(savedKec) ||
                toTitleCase(d.name) === toTitleCase(savedKec)
            );
            if (found) await emsifa_loadVillages(prefix, found.id);
        }
    } catch (e) {
        console.error('Emsifa districts error:', e);
        els.kecamatan.disabled = false;
    }
}

// Fungsi baru untuk load kelurahan/desa
async function emsifa_loadVillages(prefix, districtId) {
    const els = getEls(prefix);
    if (!els.kelurahan) return;

    if (els.hidDistrict) els.hidDistrict.value = districtId;

    if (!districtId) {
        els.kelurahan.innerHTML = '<option value="">Pilih kecamatan dulu</option>';
        els.kelurahan.disabled = true;
        return;
    }

    els.kelurahan.innerHTML = '<option value="">Memuat kelurahan...</option>';
    els.kelurahan.disabled = true;

    try {
        const res  = await fetch(`${EMSIFA_BASE}/villages/${districtId}`);
        const data = await res.json();

        const savedKel = SAVED[prefix]?.kelurahan || '';
        setOptions(els.kelurahan, data, 'name', 'name', savedKel, 'Pilih kelurahan/desa');
        els.kelurahan.disabled = false;
    } catch (e) {
        console.error('Emsifa villages error:', e);
        els.kelurahan.disabled = false;
    }
}

// ── Init saat halaman load ──
document.addEventListener('DOMContentLoaded', function () {
    // Domisili — selalu tampil untuk semua tipe
    emsifa_loadProvinsi('dom', SAVED.dom.provinceId);

    // Instansi — hanya jika elemen ada (tipe instansi)
    if (document.getElementById('inst_provinsi')) {
        emsifa_loadProvinsi('inst', SAVED.inst.provinceId);
    }
});

// ═══════════════════════════════════════════════════════
//  Jenis Instansi "Lainnya"
// ═══════════════════════════════════════════════════════
(function(){
    const sel   = document.getElementById('jenis_instansi');
    const field = document.getElementById('jenis_instansi_lain_field');
    const input = document.getElementById('jenis_instansi_lain');
    function toggle(){
        if (!sel || !field) return;
        const show = sel.value === 'Lainnya';
        field.style.display = show ? 'block' : 'none';
        if (input) input.required = show;
    }
    if (sel) { sel.addEventListener('change', toggle); toggle(); }
})();

// ═══════════════════════════════════════════════════════
//  Format input numerik
// ═══════════════════════════════════════════════════════
['rt','rw','kode_pos','kode_pos_instansi','rt_instansi','rw_instansi','pic_nik','nik'].forEach(function(id){
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', function(){ this.value = this.value.replace(/[^0-9]/g,''); });
});

const phoneInput = document.getElementById('contact_phone');
if (phoneInput) phoneInput.addEventListener('input', function(){ this.value = this.value.replace(/[^0-9+]/g,''); });

function emsifa_onchangeProvinsi(prefix, sel) {
    const selectedOpt = sel.options[sel.selectedIndex];
    const id = selectedOpt?.dataset?.id || sel.value;
    emsifa_loadKota(prefix, id);
}

function emsifa_onchangeKota(prefix, sel) {
    const selectedOpt = sel.options[sel.selectedIndex];
    const id = selectedOpt?.dataset?.id || sel.value;
    emsifa_loadKecamatan(prefix, id);
}

function emsifa_onchangeKecamatan(prefix, sel) {
    const selectedOpt = sel.options[sel.selectedIndex];
    const id = selectedOpt?.dataset?.id || sel.value;
    emsifa_loadVillages(prefix, id);
}

</script>
</x-app-layout>