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
        .rt-root { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; padding-bottom: 4rem; }

        /* ── HERO ── */
        .rt-hero { background: linear-gradient(140deg, #0b1d35 0%, #142744 55%, #1c3a68 100%); padding: 2.5rem 0 0; position: relative; overflow: hidden; }
        .rt-hero::before { content: ''; position: absolute; top: -60px; right: -80px; width: 420px; height: 420px; border-radius: 50%; background: radial-gradient(circle, rgba(56,189,248,.07) 0%, transparent 70%); pointer-events: none; }
        .rt-hero::after  { content: ''; position: absolute; bottom: -40px; left: -60px; width: 300px; height: 300px; border-radius: 50%; background: radial-gradient(circle, rgba(29,78,216,.06) 0%, transparent 70%); pointer-events: none; }
        .rt-hero-inner { max-width: 1200px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 1; }
        .rt-hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap; }

        /* breadcrumb */
        .rt-breadcrumb { display: flex; align-items: center; gap: .45rem; margin-bottom: .75rem; }
        .rt-breadcrumb a { color: rgba(255,255,255,.4); font-size: .72rem; font-weight: 500; text-decoration: none; transition: color .18s; }
        .rt-breadcrumb a:hover { color: rgba(255,255,255,.75); }
        .rt-breadcrumb-sep { color: rgba(255,255,255,.2); font-size: .7rem; }
        .rt-breadcrumb-cur { color: rgba(255,255,255,.65); font-size: .72rem; font-weight: 600; }

        .rt-eyebrow { display: inline-flex; align-items: center; gap: .45rem; background: rgba(56,189,248,.1); border: 1px solid rgba(56,189,248,.22); color: var(--sky); font-size: .68rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase; padding: .32rem .9rem; border-radius: 99px; margin-bottom: .9rem; }
        .rt-eyebrow-dot { width: 5px; height: 5px; background: var(--sky); border-radius: 50%; }
        .rt-hero h1 { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 700; color: #fff; line-height: 1.15; margin: 0 0 .5rem; }
        .rt-hero h1 em { font-style: italic; color: var(--sky); }
        .rt-hero-sub { color: rgba(255,255,255,.42); font-size: .83rem; line-height: 1.7; max-width: 460px; margin: 0; }

        /* chips */
        .rt-chips { display: flex; gap: .6rem; flex-wrap: wrap; justify-content: flex-end; align-items: flex-start; flex-shrink: 0; }
        .rt-chip { display: flex; flex-direction: column; align-items: flex-end; background: rgba(255,255,255,.055); border: 1px solid rgba(255,255,255,.09); border-radius: 1.1rem; padding: .85rem 1.1rem; min-width: 96px; transition: background .2s, transform .2s; }
        .rt-chip:hover { background: rgba(255,255,255,.1); transform: translateY(-2px); }
        .rt-chip-val { font-family: 'Playfair Display', serif; font-size: 1.9rem; color: #fff; line-height: 1; }
        .rt-chip-lbl { font-size: .66rem; font-weight: 600; color: rgba(255,255,255,.45); letter-spacing: .06em; text-align: right; margin-top: .2rem; max-width: 88px; line-height: 1.35; }
        .rt-chip.blue  { border-color: rgba(56,189,248,.28); background: rgba(56,189,248,.07); }
        .rt-chip.blue .rt-chip-val { color: var(--sky); }
        .rt-chip.green { border-color: rgba(52,211,153,.28); background: rgba(52,211,153,.07); }
        .rt-chip.green .rt-chip-val { color: #34d399; }
        .rt-chip.gold  { border-color: rgba(251,191,36,.28); background: rgba(251,191,36,.07); }
        .rt-chip.gold .rt-chip-val { color: #fbbf24; }

        /* stat bar */
        .rt-stat-bar {
            display: grid; grid-template-columns: repeat(3, 1fr);
            background: linear-gradient(to right, #0b1d35, #1d4ed8, #38bdf8);
            box-shadow: 0 4px 16px rgba(11,29,53,.15);
        }
        .rt-stat-item { padding: 1rem 1.25rem; border-right: 1px solid rgba(255,255,255,.1); text-decoration: none; position: relative; transition: background .18s; }
        .rt-stat-item:last-child { border-right: none; }
        .rt-stat-item:hover { background: rgba(255,255,255,.07); }
        .rt-stat-item.active { background: rgba(255,255,255,.12); }
        .rt-stat-item.active::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2.5px; background: #fff; border-radius: 99px 99px 0 0; }
        .rt-stat-num { font-family: 'Playfair Display', serif; font-size: 1.45rem; color: #fff; display: block; line-height: 1; }
        .rt-stat-txt { font-size: .67rem; font-weight: 600; color: rgba(255,255,255,.75); letter-spacing: .06em; text-transform: uppercase; display: flex; align-items: center; gap: .3rem; margin-top: .3rem; }
        .rt-stat-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }

        /* content */
        .rt-content { max-width: 1200px; margin: 0 auto; padding: 1.75rem 2rem 0; }

        /* flash */
        .rt-flash { border-radius: .875rem; padding: .85rem 1.2rem; font-size: .83rem; font-weight: 600; display: flex; align-items: center; gap: .55rem; margin-bottom: 1.25rem; animation: flashIn .35s ease; }
        @keyframes flashIn { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:none} }
        .rt-flash svg { width: 16px; height: 16px; flex-shrink: 0; }
        .rt-flash.ok  { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .rt-flash.err { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

        /* toolbar */
        .rt-toolbar { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.25rem; padding: .875rem 1.1rem; display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; margin-bottom: 1.25rem; box-shadow: 0 2px 16px rgba(11,29,53,.045); }
        .rt-search { position: relative; flex: 1; min-width: 200px; }
        .rt-search-icon { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; color: #94a3b8; pointer-events: none; }
        .rt-search input { width: 100%; border: 1.5px solid var(--border); border-radius: .875rem; padding: .62rem 1rem .62rem 2.45rem; font-size: .82rem; font-family: 'DM Sans', sans-serif; color: var(--navy); background: #f8fafc; outline: none; transition: border-color .2s, box-shadow .2s, background .2s; }
        .rt-search input:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(29,78,216,.09); background: var(--white); }
        .rt-search input::placeholder { color: #b0bac6; }
        .rt-select { border: 1.5px solid var(--border); border-radius: .875rem; padding: .62rem 2.1rem .62rem .875rem; font-size: .81rem; font-family: 'DM Sans', sans-serif; color: var(--navy); font-weight: 500; background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2394a3b8'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E") no-repeat right .65rem center/14px; appearance: none; outline: none; cursor: pointer; transition: border-color .2s; }
        .rt-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(29,78,216,.09); }
        .rt-divider { width: 1px; height: 24px; background: var(--border); flex-shrink: 0; }
        .rt-btn { display: inline-flex; align-items: center; gap: .38rem; border: none; border-radius: .875rem; padding: .62rem 1.05rem; font-size: .79rem; font-family: 'DM Sans', sans-serif; font-weight: 600; cursor: pointer; white-space: nowrap; transition: background .18s, transform .15s, box-shadow .18s; text-decoration: none; }
        .rt-btn svg { width: 13px; height: 13px; }
        .rt-btn-primary { background: var(--navy); color: #fff; }
        .rt-btn-primary:hover { background: var(--blue); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(29,78,216,.25); }
        .rt-btn-ghost { background: transparent; color: var(--slate); }
        .rt-btn-ghost:hover { color: var(--navy); background: #f1f5f9; }

        /* card */
        .rt-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.5rem; box-shadow: 0 4px 28px rgba(11,29,53,.055); overflow: hidden; }
        .rt-card-head { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.5rem; border-bottom: 1.5px solid #f0f4f8; gap: 1rem; flex-wrap: wrap; }
        .rt-card-title { font-size: .76rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--navy); display: flex; align-items: center; gap: .55rem; }
        .rt-card-title::before { content: ''; width: 3px; height: 15px; background: linear-gradient(180deg, #1d4ed8, #38bdf8); border-radius: 99px; }
        .rt-per-page { display: flex; align-items: center; gap: .45rem; font-size: .74rem; color: var(--slate); }
        .rt-per-page select { border: 1.5px solid var(--border); border-radius: .5rem; padding: .28rem .5rem; font-size: .74rem; font-family: 'DM Sans', sans-serif; color: var(--navy); background: #f8fafc; outline: none; }

        /* table */
        .rt-table { width: 100%; border-collapse: collapse; }
        .rt-table thead tr { background: #f8fafc; border-bottom: 1.5px solid var(--border); }
        .rt-table thead th { padding: .78rem 1rem; text-align: left; font-size: .68rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #94a3b8; white-space: nowrap; }
        .rt-table thead th:first-child { padding-left: 1.5rem; }
        .rt-table thead th:last-child { padding-right: 1.5rem; text-align: right; }
        .rt-table tbody tr { border-bottom: 1px solid #f0f4f8; transition: background .12s; animation: rowIn .3s ease both; }
        .rt-table tbody tr:last-child { border-bottom: none; }
        .rt-table tbody tr:hover { background: #fafbff; }
        @keyframes rowIn { from{opacity:0;transform:translateY(5px)} to{opacity:1;transform:none} }
        .rt-table tbody td { padding: .9rem 1rem; font-size: .81rem; color: var(--navy); vertical-align: middle; }
        .rt-table tbody td:first-child { padding-left: 1.5rem; }
        .rt-table tbody td:last-child { padding-right: 1.5rem; text-align: right; }

        /* cell helpers */
        .rt-id { font-family: 'Playfair Display', serif; font-size: .92rem; color: var(--blue); font-weight: 600; }
        .rt-sub { font-size: .72rem; color: var(--slate); margin-top: .1rem; }
        .rt-name { font-weight: 600; }
        .rt-koleksi { display: flex; align-items: center; gap: .6rem; }
        .rt-thumb { width: 40px; height: 40px; border-radius: .6rem; object-fit: cover; background: #f1f5f9; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .rt-thumb svg { width: 16px; height: 16px; color: #b0bac6; }
        .rt-kname { font-weight: 600; line-height: 1.3; font-size: .81rem; }
        .rt-kartist { font-size: .72rem; color: var(--slate); }
        .rt-price { font-size: .82rem; font-weight: 700; color: var(--navy); }
        .rt-price-sub { font-size: .7rem; color: var(--slate); margin-top: .1rem; }

        /* tipe pill */
        .rt-tipe { display: inline-flex; align-items: center; gap: .28rem; padding: .22rem .65rem; border-radius: 99px; font-size: .66rem; font-weight: 700; white-space: nowrap; letter-spacing: .04em; }
        .rt-tipe.pembelian { background: #dbeafe; color: #1e40af; }
        .rt-tipe.penyewaan { background: #ede9fe; color: #5b21b6; }

        /* badge status */
        .rt-badge { display: inline-flex; align-items: center; gap: .28rem; padding: .26rem .75rem; border-radius: 99px; font-size: .68rem; font-weight: 700; white-space: nowrap; letter-spacing: .02em; }
        .rt-badge-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
        .st-selesai        { background: #d1fae5; color: #065f46; }
        .st-selesai .rt-badge-dot        { background: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,.18); }
        .st-dibatalkan     { background: #f1f5f9; color: #475569; }
        .st-dibatalkan .rt-badge-dot     { background: #94a3b8; }
        .st-ditolak        { background: #fee2e2; color: #991b1b; }
        .st-ditolak .rt-badge-dot        { background: #ef4444; }
        .st-diterima       { background: #d1fae5; color: #065f46; }
        .st-diterima .rt-badge-dot       { background: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,.18); }
        .st-lainnya        { background: #fef3c7; color: #92400e; }
        .st-lainnya .rt-badge-dot        { background: #f59e0b; }

        /* aksi */
        .rt-btn-detail { display: inline-flex; align-items: center; gap: .38rem; padding: .45rem .9rem; background: var(--navy); color: #fff; border-radius: .6rem; font-size: .74rem; font-weight: 600; text-decoration: none; white-space: nowrap; transition: background .18s, transform .15s, box-shadow .18s; }
        .rt-btn-detail:hover { background: var(--blue); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(29,78,216,.28); }
        .rt-btn-detail svg { width: 11px; height: 11px; }

        /* empty */
        .rt-empty { padding: 4.5rem 2rem; text-align: center; }
        .rt-empty-icon { width: 62px; height: 62px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.2rem; }
        .rt-empty-icon svg { width: 25px; height: 25px; color: #94a3b8; }
        .rt-empty h3 { font-family: 'Playfair Display', serif; font-size: 1.2rem; color: var(--navy); margin: 0 0 .35rem; }
        .rt-empty p { font-size: .82rem; color: var(--slate); }

        /* pagination */
        .rt-pagination { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.5rem; border-top: 1.5px solid #f0f4f8; gap: 1rem; flex-wrap: wrap; }
        .rt-page-info { font-size: .74rem; color: var(--slate); }
        .rt-page-info strong { color: var(--navy); }
        .rt-page-btns { display: flex; gap: .3rem; }
        .rt-page-btn { width: 31px; height: 31px; display: flex; align-items: center; justify-content: center; border-radius: .5rem; border: 1.5px solid var(--border); background: var(--white); color: var(--slate); font-size: .76rem; font-weight: 600; text-decoration: none; transition: all .15s; }
        .rt-page-btn:hover { border-color: var(--blue); color: var(--blue); }
        .rt-page-btn.active { background: var(--navy); border-color: var(--navy); color: #fff; }
        .rt-page-btn.disabled { opacity: .33; cursor: not-allowed; pointer-events: none; }
        .rt-page-btn svg { width: 12px; height: 12px; }

        /* periode sewa pill */
        .rt-periode { display: inline-flex; align-items: center; gap: .3rem; background: #ede9fe; color: #5b21b6; font-size: .69rem; font-weight: 600; padding: .2rem .6rem; border-radius: 99px; white-space: nowrap; }
        .rt-periode svg { width: 10px; height: 10px; }

        @media (max-width:1024px) { 
            .rt-chips { display: none; } 
            .rt-kname { max-width: 90px; }
        }
        @media (max-width:860px) {
            .rt-hero h1 { font-size: 1.75rem; }
            /* sembunyikan kolom Periode di tablet */
            .rt-table thead th:nth-child(5), 
            .rt-table tbody td:nth-child(5) { display: none; }
        }
        @media (max-width:640px) {
            .rt-content { padding: 1.25rem 1rem 0; }
            .rt-stat-bar { grid-template-columns: repeat(3,1fr); overflow-x: auto; }
            .rt-stat-item { min-width: 90px; padding: .75rem .65rem; }
            .rt-stat-num { font-size: 1.1rem; }
            .rt-stat-txt { font-size: .58rem; }
            /* sembunyikan Koleksi & Periode di mobile */
            .rt-table thead th:nth-child(3), 
            .rt-table tbody td:nth-child(3),
            .rt-table thead th:nth-child(5), 
            .rt-table tbody td:nth-child(5) { display: none; }
        }

        /* ── kompres tabel supaya muat tanpa scroll ── */
        .rt-table thead th,
        .rt-table tbody td { padding-left: .65rem; padding-right: .65rem; }
        .rt-table thead th:first-child,
        .rt-table tbody td:first-child { padding-left: 1.25rem; }
        .rt-table thead th:last-child,
        .rt-table tbody td:last-child  { padding-right: 1.25rem; }

        /* periode sewa — biarkan wrap supaya tidak memaksa lebar */
        .rt-periode { white-space: normal; line-height: 1.4; }

        /* nama lukisan — potong jika terlalu panjang */
        .rt-kname { 
            max-width: 120px; 
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis; 
        }

        /* nama user — potong jika terlalu panjang */
        .rt-name { 
            max-width: 130px; 
            white-space: nowrap; 
            overflow: hidden; 
            text-overflow: ellipsis; 
        }
        .rt-sub {
            max-width: 130px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* thumb sedikit lebih kecil */
        .rt-thumb { width: 34px; height: 34px; }

        /* harga — nowrap supaya tidak pecah */
        .rt-price { white-space: nowrap; }

        /* tombol detail — lebih kompak */
        .rt-btn-detail { padding: .4rem .7rem; font-size: .7rem; }
    </style>

    <div class="rt-root">

        {{-- ══ HERO ══ --}}
        <div class="rt-hero">
            <div class="rt-hero-inner">
                <div class="rt-hero-top">
                    <div>
                        {{-- breadcrumb --}}
                        <div class="rt-breadcrumb">
                            <a href="{{ route('pengelola.pembelian.index') }}">Dashboard Pembelian</a>
                            <span class="rt-breadcrumb-sep">/</span>
                            <span class="rt-breadcrumb-cur">Riwayat Transaksi</span>
                        </div>

                        <div class="rt-eyebrow">
                            <span class="rt-eyebrow-dot"></span>
                            Laporan Transaksi Selesai
                        </div>
                        <h1>Riwayat <em>Transaksi</em></h1>
                        <p class="rt-hero-sub">Seluruh transaksi pembelian dan penyewaan lukisan yang telah selesai atau dibatalkan. Gunakan filter untuk menelusuri data dengan cepat.</p>
                    </div>

                    {{-- chips ringkasan --}}
                    <div class="rt-chips">
                        <div class="rt-chip blue">
                            <span class="rt-chip-val">{{ $summary['total'] }}</span>
                            <span class="rt-chip-lbl">Total Transaksi</span>
                        </div>
                        <div class="rt-chip green">
                            <span class="rt-chip-val">{{ $summary['pembelian'] }}</span>
                            <span class="rt-chip-lbl">Pembelian</span>
                        </div>
                        <div class="rt-chip gold">
                            <span class="rt-chip-val">{{ $summary['penyewaan'] }}</span>
                            <span class="rt-chip-lbl">Penyewaan</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- stat bar --}}
            <div class="rt-stat-bar">
                @php
                    $statItems = [
                        ['label' => 'Semua Tipe',    'val' => $summary['total'],     'dot' => '#94a3b8', 'type' => 'all'],
                        ['label' => 'Pembelian',      'val' => $summary['pembelian'], 'dot' => '#38bdf8', 'type' => 'pembelian'],
                        ['label' => 'Penyewaan',      'val' => $summary['penyewaan'], 'dot' => '#a78bfa', 'type' => 'penyewaan'],
                    ];
                @endphp
                @foreach($statItems as $s)
                    <a href="{{ route('pengelola.pembelian.transactions.index', array_merge(request()->except('type','page'), ['type' => $s['type']])) }}"
                       class="rt-stat-item {{ ($filters['type'] ?? 'all') === $s['type'] ? 'active' : '' }}">
                        <span class="rt-stat-num">{{ $s['val'] }}</span>
                        <span class="rt-stat-txt">
                            <span class="rt-stat-dot" style="background:{{ $s['dot'] }};"></span>
                            {{ $s['label'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ══ CONTENT ══ --}}
        <div class="rt-content">

            @if(session('success'))
                <div class="rt-flash ok">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Toolbar / Filter --}}
            <form method="GET" action="{{ route('pengelola.pembelian.transactions.index') }}" id="rt-filter-form">
                <div class="rt-toolbar">
                    {{-- search --}}
                    <div class="rt-search">
                        <svg class="rt-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari nama, lukisan, atau nomor transaksi…" id="rt-search-input">
                    </div>

                    <div class="rt-divider"></div>

                    {{-- tipe --}}
                    <select name="type" class="rt-select" onchange="this.form.submit()">
                        <option value="all"       {{ ($filters['type'] ?? 'all') === 'all'       ? 'selected' : '' }}>Semua Tipe</option>
                        <option value="pembelian" {{ ($filters['type'] ?? '') === 'pembelian'    ? 'selected' : '' }}>Pembelian</option>
                        <option value="penyewaan" {{ ($filters['type'] ?? '') === 'penyewaan'    ? 'selected' : '' }}>Penyewaan</option>
                    </select>

                    <div class="rt-divider"></div>

                    {{-- tanggal dari --}}
                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                           style="border:1.5px solid var(--border);border-radius:.875rem;padding:.62rem .875rem;font-size:.81rem;font-family:'DM Sans',sans-serif;color:var(--navy);background:#f8fafc;outline:none;transition:border-color .2s;"
                           onfocus="this.style.borderColor='var(--blue)'" onblur="this.style.borderColor='var(--border)'">

                    <span style="font-size:.75rem;color:var(--slate);white-space:nowrap;">s/d</span>

                    {{-- tanggal sampai --}}
                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                           style="border:1.5px solid var(--border);border-radius:.875rem;padding:.62rem .875rem;font-size:.81rem;font-family:'DM Sans',sans-serif;color:var(--navy);background:#f8fafc;outline:none;transition:border-color .2s;"
                           onfocus="this.style.borderColor='var(--blue)'" onblur="this.style.borderColor='var(--border)'">

                    <div class="rt-divider"></div>

                    <button type="submit" class="rt-btn rt-btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/></svg>
                        Terapkan
                    </button>

                    @if(($filters['search'] ?? '') || ($filters['date_from'] ?? '') || ($filters['date_to'] ?? '') || (($filters['type'] ?? 'all') !== 'all'))
                        <a href="{{ route('pengelola.pembelian.transactions.index') }}" class="rt-btn rt-btn-ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Table card --}}
            <div class="rt-card">
                <div class="rt-card-head">
                    <div class="rt-card-title">Daftar Transaksi Selesai</div>
                    <div style="display:flex;align-items:center;gap:1rem;">
                        <div class="rt-per-page">
                            <span>Tampilkan</span>
                            <form method="GET" action="{{ route('pengelola.pembelian.transactions.index') }}" style="display:inline;">
                                <input type="hidden" name="search"    value="{{ $filters['search'] ?? '' }}">
                                <input type="hidden" name="type"      value="{{ $filters['type'] ?? 'all' }}">
                                <input type="hidden" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
                                <input type="hidden" name="date_to"   value="{{ $filters['date_to'] ?? '' }}">
                                <select name="per_page" onchange="this.form.submit()">
                                    @foreach([10,25,50] as $n)
                                        <option value="{{ $n }}" {{ request('per_page',20)==$n ? 'selected' : '' }}>{{ $n }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <span>baris &nbsp;·&nbsp; {{ $transactions->total() }} total</span>
                        </div>
                    </div>
                </div>

                @if($transactions->isEmpty())
                    <div class="rt-empty">
                        <div class="rt-empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                        </div>
                        <h3>Tidak ada riwayat transaksi</h3>
                        <p>Belum ada transaksi yang cocok dengan filter yang diterapkan. Coba ubah rentang tanggal atau reset filter.</p>
                    </div>
                @else
                    <div>
                        <table class="rt-table">
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Tipe</th>
                                    <th>Koleksi</th>
                                    <th>Pembeli / Penyewa</th>
                                    <th>Periode / Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $t)
                                @php
                                    $isPembelian = $t['type'] === 'pembelian';

                                    // nomor transaksi
                                    $noPrefix = $isPembelian ? 'BLI' : 'SP';
                                    $noStr    = $noPrefix . '-' . str_pad($t['id'], 5, '0', STR_PAD_LEFT);

                                    // badge kelas & label
                                    $status = $t['status'] ?? '';
                                    [$badgeClass, $statusLabel] = match(true) {
                                        in_array($status, ['selesai','diterima_pembeli','dikembalikan'])
                                            => ['st-selesai',    'Selesai'],
                                        $status === 'dibatalkan'
                                            => ['st-dibatalkan', 'Dibatalkan'],
                                        $status === 'ditolak'
                                            => ['st-ditolak',    'Ditolak'],
                                        default
                                            => ['st-lainnya', ucfirst(str_replace('_',' ',$status))],
                                    };

                                    // rute detail — arahkan ke show yang sudah ada
                                    $detailRoute = $isPembelian
                                        ? route('pengelola.pembelian.show', $t['id'])
                                        : route('pengelola.penyewaan.show', $t['id']);
                                @endphp
                                <tr>
                                    {{-- No. Transaksi --}}
                                    <td>
                                        <div class="rt-id">{{ $noStr }}</div>
                                        <div class="rt-sub">
                                            {{ is_object($t['date']) ? $t['date']->format('d M Y') : \Carbon\Carbon::parse($t['date'])->format('d M Y') }}
                                        </div>
                                    </td>

                                    {{-- Tipe --}}
                                    <td>
                                        <span class="rt-tipe {{ $t['type'] }}">
                                            {{ $isPembelian ? 'Pembelian' : 'Penyewaan' }}
                                        </span>
                                    </td>

                                    {{-- Koleksi --}}
                                    <td>
                                        <div class="rt-koleksi">
                                            @if(!empty($t['painting_image']))
                                                <img class="rt-thumb" src="{{ $t['painting_image'] }}" alt="{{ $t['title'] ?? '' }}">
                                            @else
                                                <div class="rt-thumb">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="rt-kname">{{ $t['title'] ?? '-' }}</div>
                                                @if(!empty($t['painting_artist']))
                                                    <div class="rt-kartist">{{ $t['painting_artist'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Pembeli / Penyewa --}}
                                    <td>
                                        <div class="rt-name">{{ $t['user_name'] ?? '-' }}</div>
                                        <div class="rt-sub">{{ $t['user_email'] ?? '' }}</div>
                                    </td>

                                    {{-- Periode / Tanggal selesai --}}
                                    <td>
                                        @if(!$isPembelian && !empty($t['rental_start']) && !empty($t['rental_end']))
                                            <span class="rt-periode">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/></svg>
                                                {{ \Carbon\Carbon::parse($t['rental_start'])->format('d M Y') }}
                                                –
                                                {{ \Carbon\Carbon::parse($t['rental_end'])->format('d M Y') }}
                                            </span>
                                        @else
                                            <div style="font-size:.79rem;color:var(--slate);">—</div>
                                        @endif
                                    </td>

                                    {{-- Total --}}
                                    <td>
                                        <div class="rt-price">Rp {{ number_format($t['amount'] ?? 0, 0, ',', '.') }}</div>
                                        @if(!empty($t['refund_amount']) && $t['refund_amount'] > 0)
                                            <div class="rt-price-sub" style="color:#d97706;">
                                                Refund: Rp {{ number_format($t['refund_amount'], 0, ',', '.') }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        <span class="rt-badge {{ $badgeClass }}">
                                            <span class="rt-badge-dot"></span>
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    {{-- Aksi --}}
                                    <td>
                                        <a href="{{ $detailRoute }}" class="rt-btn-detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($transactions->hasPages())
                    <div class="rt-pagination">
                        <div class="rt-page-info">
                            Menampilkan <strong>{{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}</strong>
                            dari <strong>{{ $transactions->total() }}</strong> transaksi
                        </div>
                        <div class="rt-page-btns">
                            @if($transactions->onFirstPage())
                                <span class="rt-page-btn disabled"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg></span>
                            @else
                                <a href="{{ $transactions->previousPageUrl() }}" class="rt-page-btn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg></a>
                            @endif
                            @foreach($transactions->getUrlRange(max(1,$transactions->currentPage()-2), min($transactions->lastPage(),$transactions->currentPage()+2)) as $page => $url)
                                <a href="{{ $url }}" class="rt-page-btn {{ $page == $transactions->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                            @endforeach
                            @if($transactions->hasMorePages())
                                <a href="{{ $transactions->nextPageUrl() }}" class="rt-page-btn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></a>
                            @else
                                <span class="rt-page-btn disabled"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></span>
                            @endif
                        </div>
                    </div>
                    @endif
                @endif
            </div>

        </div>{{-- /.rt-content --}}
    </div>{{-- /.rt-root --}}

    <script>
    (function(){
        const s = document.getElementById('rt-search-input');
        if(s){
            let t;
            s.addEventListener('input', function(){
                clearTimeout(t);
                t = setTimeout(() => document.getElementById('rt-filter-form').submit(), 600);
            });
        }
    })();
    </script>

</x-app-layout>