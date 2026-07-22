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
        .pb-root { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; padding-bottom: 4rem; }

        /* ── HERO ── */
        .pb-hero { background: linear-gradient(140deg,#0b1d35 0%,#142744 55%,#1c3a68 100%); padding: 2.25rem 0; position: relative; overflow: hidden; }
        .pb-hero::before { content: ''; position: absolute; top: -60px; right: -80px; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events: none; }
        .pb-hero::after  { content: ''; position: absolute; bottom: -80px; left: -40px; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle,rgba(29,78,216,.06) 0%,transparent 70%); pointer-events: none; }
        .pb-hero-inner { max-width: 1100px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 1; }

        .pb-breadcrumb { display: flex; align-items: center; gap: .45rem; margin-bottom: .85rem; }
        .pb-breadcrumb a { color: rgba(255,255,255,.45); font-size: .75rem; font-weight: 500; text-decoration: none; transition: color .15s; }
        .pb-breadcrumb a:hover { color: var(--sky); }
        .pb-breadcrumb-sep { color: rgba(255,255,255,.25); font-size: .7rem; }
        .pb-breadcrumb-cur { color: rgba(255,255,255,.7); font-size: .75rem; font-weight: 600; }

        .pb-hero-title { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; color: #fff; line-height: 1.2; margin: 0 0 .3rem; }
        .pb-hero-sub   { font-size: .88rem; color: rgba(255,255,255,.55); margin: 0; }
        /* Hero actions — sama persis seperti di ps-hero */
        .pb-hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; }
        .pb-hero-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: flex-start; padding-top: .25rem; }
        .pb-hero-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.2rem; border-radius: .875rem; font-size: .8rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .18s; border: none; cursor: pointer; white-space: nowrap; }
        .pb-hero-btn svg { width: 13px; height: 13px; }
        .pb-hero-btn-back { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15); color: rgba(255,255,255,.85); }
        .pb-hero-btn-back:hover { background: rgba(255,255,255,.17); }

        /* ── PROGRESS BAR ── */
        .pb-progress-wrap { max-width: 1100px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 1; margin-top: 1.5rem; }
        .pb-progress-track { background: rgba(255,255,255,.08); border-radius: 99px; height: 4px; overflow: hidden; }
        .pb-progress-fill  { height: 100%; width: 50%; background: linear-gradient(90deg, var(--sky), #60a5fa); border-radius: 99px; }
        .pb-steps-row { display: flex; justify-content: space-between; margin-top: .6rem; }
        .pb-step-pill { display: inline-flex; align-items: center; gap: .35rem; font-size: .7rem; font-weight: 600; }
        .pb-step-pill.active { color: var(--sky); }
        .pb-step-pill.pending { color: rgba(255,255,255,.3); }
        .pb-step-pill-num { width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .62rem; font-weight: 700; }
        .pb-step-pill.active  .pb-step-pill-num { background: var(--sky); color: var(--navy); }
        .pb-step-pill.pending .pb-step-pill-num { background: rgba(255,255,255,.1); color: rgba(255,255,255,.4); border: 1.5px solid rgba(255,255,255,.15); }

        /* ── CONTENT ── */
        .pb-content { max-width: 1100px; margin: 0 auto; padding: 1.75rem 3rem 0; }

        /* ── MAIN GRID ── */
        .pb-grid { display: grid; grid-template-columns: 1fr; gap: 1.25rem; }

        /* ── CARD ── */
        .pb-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.5rem; box-shadow: 0 4px 24px rgba(11,29,53,.05); overflow: hidden; }
        .pb-card-header { padding: 1.1rem 1.5rem; border-bottom: 1.5px solid #f0f4f8; display: flex; align-items: center; gap: .55rem; }
        .pb-card-header-accent { width: 3px; height: 16px; background: linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius: 99px; flex-shrink: 0; }
        .pb-card-header h3 { font-size: .76rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--navy); margin: 0; }
        .pb-card-body { padding: 1.5rem; }

        /* ── RADIO CARDS ── */
        .pb-radio-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        @media(max-width: 600px) { .pb-radio-grid { grid-template-columns: 1fr; } }

        .pb-radio-label { position: relative; cursor: pointer; display: block; }
        .pb-radio-label input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .pb-radio-inner {
            border: 1.5px solid var(--border);
            border-radius: 1.25rem;
            padding: 1.4rem;
            background: #f8fafc;
            transition: all .2s;
            display: flex; flex-direction: column; gap: .75rem;
            height: 100%;
        }
        .pb-radio-label:hover .pb-radio-inner { border-color: #93c5fd; background: #eff6ff; }
        .pb-radio-label input:checked ~ .pb-radio-inner {
            border-color: var(--blue);
            background: #eff6ff;
            box-shadow: 0 0 0 4px rgba(29,78,216,.08), 0 4px 16px rgba(29,78,216,.1);
        }

        .pb-radio-top { display: flex; align-items: flex-start; justify-content: space-between; gap: .5rem; }
        .pb-radio-icon {
            width: 42px; height: 42px; border-radius: .875rem;
            display: flex; align-items: center; justify-content: center;
            background: #dbeafe; color: var(--blue);
            transition: background .2s, color .2s; flex-shrink: 0;
        }
        .pb-radio-label input:checked ~ .pb-radio-inner .pb-radio-icon { background: var(--blue); color: #fff; }
        .pb-radio-icon svg { width: 20px; height: 20px; }

        .pb-radio-dot {
            width: 18px; height: 18px; border-radius: 50%;
            border: 2px solid #cbd5e1;
            display: flex; align-items: center; justify-content: center;
            transition: border-color .2s; flex-shrink: 0; margin-top: 2px;
        }
        .pb-radio-label input:checked ~ .pb-radio-inner .pb-radio-dot { border-color: var(--blue); background: var(--blue); }
        .pb-radio-label input:checked ~ .pb-radio-inner .pb-radio-dot::after { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #fff; display: block; }

        .pb-radio-title { font-size: .9rem; font-weight: 700; color: var(--navy); margin-top: .5rem; }
        .pb-radio-desc  { font-size: .79rem; color: var(--slate); line-height: 1.6; }

        /* ── ACTIONS ── */
        .pb-actions {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            padding: 1.1rem 1.5rem;
            border-top: 1.5px solid #f0f4f8;
            background: #fafbfc; flex-wrap: wrap;
        }
        .pb-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .65rem 1.35rem; border-radius: .875rem; font-size: .82rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .2s; border: none; cursor: pointer; }
        .pb-btn svg { width: 14px; height: 14px; }
        .pb-btn-ghost { background: transparent; border: 1.5px solid var(--border); color: var(--slate); }
        .pb-btn-ghost:hover { background: #f8fafc; }
        .pb-btn-blue  { background: linear-gradient(135deg,var(--blue),#2563eb); color: #fff; box-shadow: 0 4px 14px rgba(29,78,216,.25); }
        .pb-btn-blue:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(29,78,216,.35); }

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
                            <a href="{{ route('gallery') }}">Galeri</a>
                            <span class="pb-breadcrumb-sep">/</span>
                            <a href="{{ route('gallery.show', $koleksi) }}">{{ $painting->title }}</a>
                            <span class="pb-breadcrumb-sep">/</span>
                            <span class="pb-breadcrumb-cur">Pengajuan Pembelian</span>
                        </div>
                        <h1 class="pb-hero-title">Pengajuan Pembelian Koleksi</h1>
                        <p class="pb-hero-sub">{{ $painting->title }} &mdash; {{ $painting->artist }}</p>
                        @if($painting->sale_price > 0)
                        <p style="font-size:.82rem;color:rgba(255,255,255,.7);margin:.3rem 0 0;font-weight:600;">
                            💰 Harga Beli: Rp {{ number_format($painting->sale_price, 0, ',', '.') }}
                        </p>
                        @endif
                    </div>
                    <div class="pb-hero-actions">
                        <a href="{{ route('gallery.show', $koleksi) }}" class="pb-hero-btn pb-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>

            {{-- Progress steps --}}
            <div class="pb-progress-wrap">
                <div class="pb-progress-track">
                    <div class="pb-progress-fill" style="width:50%;"></div>
                </div>
                <div class="pb-steps-row">
                    <div class="pb-step-pill active">
                        <div class="pb-step-pill-num">1</div>
                        Jenis Pembeli
                    </div>
                    <div class="pb-step-pill pending">
                        <div class="pb-step-pill-num">2</div>
                        Data &amp; Dokumen
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="pb-content">
            <div class="pb-grid">

                {{-- LEFT: FORM --}}
                <div style="display:flex;flex-direction:column;">
                    <div class="pb-card">
                        <div class="pb-card-header">
                            <div class="pb-card-header-accent"></div>
                            <h3>Langkah 1 — Jenis Pembeli</h3>
                        </div>
                        <form action="{{ route('pembelian.storeStep1', $koleksi) }}" method="POST">
                            @csrf
                            <div class="pb-card-body">
                                <p style="font-size:.84rem;color:#475569;line-height:1.7;margin:0 0 1.25rem;">
                                    Pilih apakah Anda membeli sebagai <strong>perorangan</strong> atau atas nama <strong>instansi / perusahaan</strong>. Pilihan ini menentukan dokumen apa saja yang perlu Anda siapkan.
                                </p>

                                <div class="pb-radio-grid">

                                    {{-- Perorangan --}}
                                    <label class="pb-radio-label">
                                        <input type="radio" name="buyer_type" value="b2c"
                                            {{ (old('buyer_type') ?: (session('pembelian_step1.buyer_type') ?? 'b2c')) === 'b2c' ? 'checked' : '' }}
                                            required>
                                        <div class="pb-radio-inner">
                                            <div class="pb-radio-top">
                                                <div class="pb-radio-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                                    </svg>
                                                </div>
                                                <div class="pb-radio-dot"></div>
                                            </div>
                                            <div class="pb-radio-title">Perorangan</div>
                                            <div class="pb-radio-desc">Pembelian atas nama diri sendiri sebagai individu. Dokumen yang dibutuhkan: KTP dan NPWP (opsional).</div>
                                        </div>
                                    </label>

                                    {{-- Instansi --}}
                                    <label class="pb-radio-label">
                                        <input type="radio" name="buyer_type" value="b2b"
                                            {{ (old('buyer_type') ?: (session('pembelian_step1.buyer_type') ?? '')) === 'b2b' ? 'checked' : '' }}>
                                        <div class="pb-radio-inner">
                                            <div class="pb-radio-top">
                                                <div class="pb-radio-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
                                                    </svg>
                                                </div>
                                                <div class="pb-radio-dot"></div>
                                            </div>
                                            <div class="pb-radio-title">Instansi / Perusahaan</div>
                                            <div class="pb-radio-desc">Pembelian atas nama perusahaan atau lembaga. Dokumen: NPWP perusahaan, surat pembelian, dan KTP PIC.</div>
                                        </div>
                                    </label>

                                </div>
                            </div>

                            <div class="pb-actions" style="justify-content: flex-end;">
                                <button type="submit" class="pb-btn pb-btn-blue">
                                    Lanjutkan ke Form Pengajuan
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>