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
        .sp-root { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; padding-bottom: 4rem; }

        /* ── HERO ── */
        .sp-hero { background: linear-gradient(140deg,#0b1d35 0%,#142744 55%,#1c3a68 100%); padding: 2.25rem 0; position: relative; overflow: hidden; }
        .sp-hero::before { content: ''; position: absolute; top: -60px; right: -80px; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events: none; }
        .sp-hero::after  { content: ''; position: absolute; bottom: -80px; left: -40px; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle,rgba(29,78,216,.06) 0%,transparent 70%); pointer-events: none; }
        .sp-hero-inner { max-width: 1100px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 1; }

        .sp-breadcrumb { display: flex; align-items: center; gap: .45rem; margin-bottom: .85rem; }
        .sp-breadcrumb a { color: rgba(255,255,255,.45); font-size: .75rem; font-weight: 500; text-decoration: none; transition: color .15s; }
        .sp-breadcrumb a:hover { color: var(--sky); }
        .sp-breadcrumb-sep { color: rgba(255,255,255,.25); font-size: .7rem; }
        .sp-breadcrumb-cur { color: rgba(255,255,255,.7); font-size: .75rem; font-weight: 600; }

        .sp-hero-title { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; color: #fff; line-height: 1.2; margin: 0 0 .3rem; }
        .sp-hero-sub   { font-size: .88rem; color: rgba(255,255,255,.55); margin: 0; }
        .sp-hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; }
        .sp-hero-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: flex-start; padding-top: .25rem; }
        .sp-hero-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.2rem; border-radius: .875rem; font-size: .8rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .18s; border: none; cursor: pointer; white-space: nowrap; }
        .sp-hero-btn svg { width: 13px; height: 13px; }
        .sp-hero-btn-back { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15); color: rgba(255,255,255,.85); }
        .sp-hero-btn-back:hover { background: rgba(255,255,255,.17); }

        /* ── PROGRESS BAR ── */
        .sp-progress-wrap { max-width: 1100px; margin: 1.5rem auto 0; padding: 0 2rem; position: relative; z-index: 1; }
        .sp-progress-track { background: rgba(255,255,255,.08); border-radius: 99px; height: 4px; overflow: hidden; }
        .sp-progress-fill  { height: 100%; background: linear-gradient(90deg, var(--sky), #60a5fa); border-radius: 99px; }
        .sp-steps-row { display: flex; justify-content: space-between; margin-top: .6rem; }
        .sp-step-pill { display: inline-flex; align-items: center; gap: .35rem; font-size: .7rem; font-weight: 600; }
        .sp-step-pill.active  { color: var(--sky); }
        .sp-step-pill.pending { color: rgba(255,255,255,.3); }
        .sp-step-pill-num { width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .62rem; font-weight: 700; }
        .sp-step-pill.active  .sp-step-pill-num { background: var(--sky); color: var(--navy); }
        .sp-step-pill.pending .sp-step-pill-num { background: rgba(255,255,255,.1); color: rgba(255,255,255,.4); border: 1.5px solid rgba(255,255,255,.15); }

        /* ── CONTENT ── */
        .sp-content { max-width: 1100px; margin: 0 auto; padding: 1.75rem 2rem 0; }

        /* ── CARD ── */
        .sp-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.5rem; box-shadow: 0 4px 24px rgba(11,29,53,.05); overflow: hidden; }
        .sp-card-header { padding: 1.1rem 1.5rem; border-bottom: 1.5px solid #f0f4f8; display: flex; align-items: center; gap: .55rem; }
        .sp-card-header-accent { width: 3px; height: 16px; background: linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius: 99px; flex-shrink: 0; }
        .sp-card-header h3 { font-size: .76rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--navy); margin: 0; }
        .sp-card-body { padding: 1.5rem; }

        /* ── ERROR BOX ── */
        .sp-error-box { background: #fff1f2; border: 1px solid #fecdd3; border-radius: 1.25rem; padding: 1.25rem 1.5rem; color: #be123c; margin-bottom: 1.25rem; }
        .sp-error-box h3 { font-weight: 700; font-size: .9rem; margin-bottom: .5rem; }
        .sp-error-box ul { padding-left: 1.25rem; font-size: .82rem; line-height: 1.8; margin: 0; }

        /* ── RADIO CARDS ── */
        .sp-radio-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        @media(max-width: 600px) { .sp-radio-grid { grid-template-columns: 1fr; } }

        .sp-radio-label { position: relative; cursor: pointer; display: block; }
        .sp-radio-label input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .sp-radio-inner {
            border: 1.5px solid var(--border);
            border-radius: 1.25rem;
            padding: 1.4rem;
            background: #f8fafc;
            transition: all .2s;
            display: flex; flex-direction: column; gap: .75rem;
            height: 100%;
        }
        .sp-radio-label:hover .sp-radio-inner { border-color: #93c5fd; background: #eff6ff; }
        .sp-radio-label input:checked ~ .sp-radio-inner {
            border-color: var(--blue);
            background: #eff6ff;
            box-shadow: 0 0 0 4px rgba(29,78,216,.08), 0 4px 16px rgba(29,78,216,.1);
        }

        .sp-radio-top { display: flex; align-items: flex-start; justify-content: space-between; gap: .5rem; }
        .sp-radio-icon {
            width: 42px; height: 42px; border-radius: .875rem;
            display: flex; align-items: center; justify-content: center;
            background: #dbeafe; color: var(--blue);
            transition: background .2s, color .2s; flex-shrink: 0;
        }
        .sp-radio-label input:checked ~ .sp-radio-inner .sp-radio-icon { background: var(--blue); color: #fff; }
        .sp-radio-icon svg { width: 20px; height: 20px; }

        .sp-radio-dot {
            width: 18px; height: 18px; border-radius: 50%;
            border: 2px solid #cbd5e1;
            display: flex; align-items: center; justify-content: center;
            transition: border-color .2s; flex-shrink: 0; margin-top: 2px;
        }
        .sp-radio-label input:checked ~ .sp-radio-inner .sp-radio-dot { border-color: var(--blue); background: var(--blue); }
        .sp-radio-label input:checked ~ .sp-radio-inner .sp-radio-dot::after { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #fff; display: block; }

        .sp-radio-title { font-size: .9rem; font-weight: 700; color: var(--navy); margin-top: .5rem; }
        .sp-radio-desc  { font-size: .79rem; color: var(--slate); line-height: 1.6; }

        /* ── ACTIONS ── */
        .sp-actions {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            padding: 1.1rem 1.5rem;
            border-top: 1.5px solid #f0f4f8;
            background: #fafbfc; flex-wrap: wrap;
        }
        .sp-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .65rem 1.35rem; border-radius: .875rem; font-size: .82rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .2s; border: none; cursor: pointer; }
        .sp-btn svg { width: 14px; height: 14px; }
        .sp-btn-ghost { background: transparent; border: 1.5px solid var(--border); color: var(--slate); }
        .sp-btn-ghost:hover { background: #f8fafc; }
        .sp-btn-blue  { background: linear-gradient(135deg,var(--blue),#2563eb); color: #fff; box-shadow: 0 4px 14px rgba(29,78,216,.25); }
        .sp-btn-blue:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(29,78,216,.35); }

        @media(max-width: 768px) {
            .sp-content    { padding: 1.25rem 1rem 0; }
            .sp-hero-inner { padding: 0 1rem; }
            .sp-progress-wrap { padding: 0 1rem; }
        }
    </style>

    <div class="sp-root">

        {{-- ── HERO ── --}}
        <div class="sp-hero">
            <div class="sp-hero-inner">
                <div class="sp-hero-top">
                    <div>
                        <div class="sp-breadcrumb">
                            <a href="{{ route('penyewaan.index') }}">Katalog</a>
                            <span class="sp-breadcrumb-sep">/</span>
                            <a href="{{ route('penyewaan.index') }}">{{ $painting->title }}</a>
                            <span class="sp-breadcrumb-sep">/</span>
                            <span class="sp-breadcrumb-cur">Pengajuan Penyewaan</span>
                        </div>
                        <h1 class="sp-hero-title">Pengajuan Penyewaan Koleksi</h1>
                        <p class="sp-hero-sub">{{ $painting->title }} &mdash; {{ $painting->artist }}</p>
                        @if($painting->daily_rate > 0)
                        <p style="font-size:.82rem;color:rgba(255,255,255,.7);margin:.3rem 0 0;font-weight:600;">
                            💰 Tarif Sewa: Rp {{ number_format($painting->daily_rate, 0, ',', '.') }} / hari
                        </p>
                        @endif
                    </div>
                    <div class="sp-hero-actions">
                        <a href="{{ route('penyewaan.index') }}" class="sp-hero-btn sp-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>

            {{-- Progress steps --}}
            <div class="sp-progress-wrap">
                <div class="sp-progress-track">
                    <div class="sp-progress-fill" style="width:33%;"></div>
                </div>
                <div class="sp-steps-row">
                    <div class="sp-step-pill active">
                        <div class="sp-step-pill-num">1</div>
                        Jenis Penyewa
                    </div>
                    <div class="sp-step-pill pending">
                        <div class="sp-step-pill-num">2</div>
                        Identitas &amp; Kontak
                    </div>
                    <div class="sp-step-pill pending">
                        <div class="sp-step-pill-num">3</div>
                        Detail &amp; Pengajuan
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="sp-content">

            @if($errors->any())
            <div class="sp-error-box">
                <h3>⚠ Periksa kembali data Anda</h3>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="sp-card">
                <div class="sp-card-header">
                    <div class="sp-card-header-accent"></div>
                    <h3>Langkah 1 — Jenis Penyewa</h3>
                </div>

                <form action="{{ route('penyewaan.storeStep1', ['koleksi' => $painting->id]) }}" method="POST">
                    @csrf
                    <div class="sp-card-body">
                        <p style="font-size:.84rem;color:#475569;line-height:1.7;margin:0 0 1.25rem;">
                            Pilih apakah Anda menyewa sebagai <strong>perorangan</strong> atau atas nama <strong>instansi / organisasi</strong>. Pilihan ini menentukan dokumen apa saja yang perlu Anda siapkan.
                        </p>

                        <div class="sp-radio-grid">

                            {{-- Perorangan --}}
                            <label class="sp-radio-label">
                                <input type="radio" name="rental_type" value="perseorangan"
                                    {{ (old('rental_type') ?: (session('penyewaan_step1')['rental_type'] ?? 'perseorangan')) === 'perseorangan' ? 'checked' : '' }}
                                    required>
                                <div class="sp-radio-inner">
                                    <div class="sp-radio-top">
                                        <div class="sp-radio-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                            </svg>
                                        </div>
                                        <div class="sp-radio-dot"></div>
                                    </div>
                                    <div class="sp-radio-title">Perorangan</div>
                                    <div class="sp-radio-desc">Saya mengajukan atas nama diri sendiri sebagai individu.</div>
                                </div>
                            </label>

                            {{-- Instansi --}}
                            <label class="sp-radio-label">
                                <input type="radio" name="rental_type" value="instansi"
                                    {{ (old('rental_type') ?: (session('penyewaan_step1')['rental_type'] ?? '')) === 'instansi' ? 'checked' : '' }}>
                                <div class="sp-radio-inner">
                                    <div class="sp-radio-top">
                                        <div class="sp-radio-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                                            </svg>
                                        </div>
                                        <div class="sp-radio-dot"></div>
                                    </div>
                                    <div class="sp-radio-title">Instansi / Organisasi</div>
                                    <div class="sp-radio-desc">Saya mengajukan atas nama perusahaan, instansi, atau lembaga.</div>
                                </div>
                            </label>

                        </div>
                    </div>

                    <div class="sp-actions">
                        <a href="{{ route('penyewaan.index') }}" class="sp-btn sp-btn-ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Katalog
                        </a>
                        <button type="submit" class="sp-btn sp-btn-blue">
                            Lanjutkan ke Form Pengajuan
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>