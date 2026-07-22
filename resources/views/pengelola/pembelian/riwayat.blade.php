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
        .pr-root { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; padding-bottom: 4rem; }

        /* ── HERO ── */
        .pr-hero { background: linear-gradient(140deg, #0b1d35 0%, #142744 55%, #1c3a68 100%); padding: 2.25rem 0; position: relative; overflow: hidden; }
        .pr-hero::before { content:''; position:absolute; top:-60px; right:-80px; width:420px; height:420px; border-radius:50%; background:radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events:none; }
        .pr-hero::after  { content:''; position:absolute; bottom:-40px; left:-60px; width:300px; height:300px; border-radius:50%; background:radial-gradient(circle,rgba(29,78,216,.06) 0%,transparent 70%); pointer-events:none; }
        .pr-hero-inner { max-width:1200px; margin:0 auto; padding:0 2rem; position:relative; z-index:1; }
        .pr-hero-top { display:flex; align-items:flex-start; justify-content:space-between; gap:1.5rem; flex-wrap:wrap; }

        .pr-breadcrumb { display:flex; align-items:center; gap:.45rem; margin-bottom:.75rem; }
        .pr-breadcrumb a { color:rgba(255,255,255,.45); font-size:.75rem; font-weight:500; text-decoration:none; transition:color .15s; }
        .pr-breadcrumb a:hover { color:var(--sky); }
        .pr-breadcrumb-sep { color:rgba(255,255,255,.25); font-size:.7rem; }
        .pr-breadcrumb-cur { color:rgba(255,255,255,.7); font-size:.75rem; font-weight:600; }

        .pr-eyebrow { display:inline-flex; align-items:center; gap:.45rem; background:rgba(56,189,248,.1); border:1px solid rgba(56,189,248,.22); color:var(--sky); font-size:.68rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; padding:.32rem .9rem; border-radius:99px; margin-bottom:.75rem; }
        .pr-eyebrow-dot { width:5px; height:5px; background:var(--sky); border-radius:50%; }
        .pr-hero h1 { font-family:'Playfair Display',serif; font-size:1.9rem; font-weight:700; color:#fff; line-height:1.2; margin:0 0 .4rem; }
        .pr-hero-sub { color:rgba(255,255,255,.45); font-size:.84rem; line-height:1.7; max-width:460px; margin:0; }

        .pr-hero-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.6rem 1.2rem; border-radius:.875rem; font-size:.8rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .18s; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15); color:rgba(255,255,255,.85); white-space:nowrap; margin-top:.25rem; }
        .pr-hero-btn:hover { background:rgba(255,255,255,.17); }
        .pr-hero-btn svg { width:13px; height:13px; }

        /* STAT CHIPS */
        .pr-chips { display:flex; gap:.6rem; flex-wrap:wrap; justify-content:flex-end; align-items:flex-start; flex-shrink:0; }
        .pr-chip { display:flex; flex-direction:column; align-items:flex-end; background:rgba(255,255,255,.055); border:1px solid rgba(255,255,255,.09); border-radius:1.1rem; padding:.75rem 1rem; min-width:88px; }
        .pr-chip-val { font-family:'Playfair Display',serif; font-size:1.7rem; color:#fff; line-height:1; }
        .pr-chip-lbl { font-size:.64rem; font-weight:600; color:rgba(255,255,255,.45); letter-spacing:.06em; text-align:right; margin-top:.2rem; line-height:1.35; }
        .pr-chip.green { border-color:rgba(52,211,153,.28); background:rgba(52,211,153,.07); }
        .pr-chip.green .pr-chip-val { color:#34d399; }
        .pr-chip.red { border-color:rgba(248,113,113,.28); background:rgba(248,113,113,.07); }
        .pr-chip.red .pr-chip-val { color:#f87171; }
        .pr-chip.slate { border-color:rgba(148,163,184,.2); background:rgba(148,163,184,.05); }
        .pr-chip.slate .pr-chip-val { color:#94a3b8; }

        /* CONTENT */
        .pr-content { max-width:1200px; margin:0 auto; padding:1.75rem 2rem 0; }

        /* FLASH */
        .pr-flash { border-radius:.875rem; padding:.85rem 1.2rem; font-size:.83rem; font-weight:600; display:flex; align-items:center; gap:.55rem; margin-bottom:1.25rem; }
        .pr-flash svg { width:16px; height:16px; flex-shrink:0; }
        .pr-flash.ok  { background:#d1fae5; border:1px solid #6ee7b7; color:#065f46; }
        .pr-flash.err { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; }

        /* FILTER CARD */
        .pr-filter-card { background:var(--white); border:1.5px solid var(--border); border-radius:1.25rem; padding:1.25rem 1.5rem; margin-bottom:1.25rem; box-shadow:0 2px 12px rgba(11,29,53,.04); }
        .pr-filter-label { font-size:.67rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#94a3b8; margin-bottom:.4rem; display:block; }
        .pr-filter-row { display:flex; gap:.75rem; flex-wrap:wrap; align-items:flex-end; }
        .pr-filter-group { display:flex; flex-direction:column; flex:1; min-width:160px; }
        .pr-filter-input { padding:.55rem .9rem; border:1.5px solid var(--border); border-radius:.75rem; font-size:.82rem; font-family:'DM Sans',sans-serif; color:var(--navy); background:var(--white); transition:border-color .15s; outline:none; }
        .pr-filter-input:focus { border-color:#93c5fd; }
        .pr-filter-select { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right .75rem center; background-size:14px; padding-right:2.2rem; cursor:pointer; }
        .pr-filter-actions { display:flex; gap:.5rem; flex-shrink:0; }
        .pr-filter-btn { display:inline-flex; align-items:center; gap:.35rem; padding:.55rem 1.1rem; border-radius:.75rem; font-size:.8rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .18s; border:none; cursor:pointer; white-space:nowrap; }
        .pr-filter-btn svg { width:13px; height:13px; }
        .pr-filter-btn-primary { background:var(--navy); color:#fff; }
        .pr-filter-btn-primary:hover { background:var(--blue); transform:translateY(-1px); }
        .pr-filter-btn-reset { background:transparent; border:1.5px solid var(--border); color:var(--slate); }
        .pr-filter-btn-reset:hover { background:#f8fafc; border-color:#cbd5e1; }

        /* ACTIVE FILTERS */
        .pr-active-filters { display:flex; align-items:center; gap:.5rem; flex-wrap:wrap; margin-top:.875rem; padding-top:.875rem; border-top:1px solid #f0f4f8; }
        .pr-active-label { font-size:.72rem; font-weight:600; color:#94a3b8; }
        .pr-filter-tag { display:inline-flex; align-items:center; gap:.3rem; background:#eff6ff; border:1px solid #bfdbfe; color:#1d4ed8; font-size:.72rem; font-weight:600; padding:.22rem .65rem; border-radius:99px; }

        /* TABLE CARD */
        .pr-card { background:var(--white); border:1.5px solid var(--border); border-radius:1.5rem; box-shadow:0 4px 28px rgba(11,29,53,.055); overflow:hidden; }
        .pr-card-head { display:flex; align-items:center; justify-content:space-between; padding:1.1rem 1.5rem; border-bottom:1.5px solid #f0f4f8; gap:1rem; flex-wrap:wrap; }
        .pr-card-title { font-size:.76rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--navy); display:flex; align-items:center; gap:.55rem; }
        .pr-card-title::before { content:''; width:3px; height:15px; background:linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius:99px; }
        .pr-card-count { font-size:.74rem; color:var(--slate); background:#f1f5f9; padding:.2rem .65rem; border-radius:99px; font-weight:600; }

        /* TABLE */
        .pr-table { width:100%; border-collapse:collapse; }
        .pr-table thead tr { background:#f8fafc; border-bottom:1.5px solid var(--border); }
        .pr-table thead th { padding:.78rem 1rem; text-align:left; font-size:.68rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:#94a3b8; white-space:nowrap; }
        .pr-table thead th:first-child { padding-left:1.5rem; }
        .pr-table thead th:last-child { padding-right:1.5rem; text-align:right; }
        .pr-table tbody tr { border-bottom:1px solid #f0f4f8; transition:background .12s; animation:rowIn .3s ease both; }
        .pr-table tbody tr:last-child { border-bottom:none; }
        .pr-table tbody tr:hover { background:#fafbff; }
        @keyframes rowIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }
        .pr-table tbody td { padding:.9rem 1rem; font-size:.81rem; color:var(--navy); vertical-align:middle; }
        .pr-table tbody td:first-child { padding-left:1.5rem; }
        .pr-table tbody td:last-child { padding-right:1.5rem; text-align:right; }

        .pr-id { font-family:'Playfair Display',serif; font-size:.92rem; color:var(--blue); font-weight:600; }
        .pr-date { font-size:.73rem; color:var(--slate); margin-top:.1rem; }
        .pr-name { font-weight:600; }
        .pr-koleksi { display:flex; align-items:center; gap:.6rem; }
        .pr-thumb { width:40px; height:40px; border-radius:.6rem; object-fit:cover; background:#f1f5f9; flex-shrink:0; display:flex; align-items:center; justify-content:center; overflow:hidden; }
        .pr-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
        .pr-thumb svg { width:16px; height:16px; color:#b0bac6; }
        .pr-kname { font-weight:600; line-height:1.3; font-size:.81rem; }
        .pr-kartist { font-size:.72rem; color:var(--slate); }

        /* BADGES */
        .pr-badge { display:inline-flex; align-items:center; gap:.28rem; padding:.26rem .75rem; border-radius:99px; font-size:.68rem; font-weight:700; white-space:nowrap; }
        .pr-badge-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
        .st-selesai    { background:#dcfce7; color:#166534; }
        .st-selesai    .pr-badge-dot { background:#22c55e; }
        .st-ditolak    { background:#fee2e2; color:#991b1b; }
        .st-ditolak    .pr-badge-dot { background:#ef4444; }
        .st-dibatalkan { background:#f1f5f9; color:#475569; }
        .st-dibatalkan .pr-badge-dot { background:#94a3b8; }

        /* BUTTONS */
        .pr-btn-detail { display:inline-flex; align-items:center; gap:.38rem; padding:.45rem .9rem; background:var(--navy); color:#fff; border-radius:.6rem; font-size:.74rem; font-weight:600; text-decoration:none; white-space:nowrap; transition:background .18s,transform .15s,box-shadow .18s; }
        .pr-btn-detail:hover { background:var(--blue); transform:translateY(-1px); box-shadow:0 4px 12px rgba(29,78,216,.28); }
        .pr-btn-detail svg { width:11px; height:11px; }

        /* EMPTY */
        .pr-empty { padding:4.5rem 2rem; text-align:center; }
        .pr-empty-icon { width:64px; height:64px; background:linear-gradient(135deg,#dbeafe,#e0e7ff); border-radius:1rem; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; font-size:1.75rem; }
        .pr-empty h3 { font-family:'Playfair Display',serif; font-size:1.25rem; color:var(--navy); margin:0 0 .4rem; }
        .pr-empty p { font-size:.83rem; color:var(--slate); max-width:320px; margin:0 auto; line-height:1.6; }
        .pr-empty-cta { display:inline-flex; align-items:center; gap:.4rem; margin-top:1rem; padding:.65rem 1.35rem; background:var(--navy); color:#fff; border-radius:.875rem; font-size:.82rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .2s; }
        .pr-empty-cta:hover { background:var(--blue); transform:translateY(-1px); }

        /* PAGINATION */
        .pr-pagination { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.5rem; border-top:1.5px solid #f0f4f8; gap:1rem; flex-wrap:wrap; }
        .pr-page-info { font-size:.74rem; color:var(--slate); }
        .pr-page-info strong { color:var(--navy); }
        .pr-page-btns { display:flex; gap:.3rem; }
        .pr-page-btn { width:31px; height:31px; display:flex; align-items:center; justify-content:center; border-radius:.5rem; border:1.5px solid var(--border); background:var(--white); color:var(--slate); font-size:.76rem; font-weight:600; text-decoration:none; transition:all .15s; }
        .pr-page-btn:hover { border-color:var(--blue); color:var(--blue); }
        .pr-page-btn.active { background:var(--navy); border-color:var(--navy); color:#fff; }
        .pr-page-btn.disabled { opacity:.33; cursor:not-allowed; pointer-events:none; }
        .pr-page-btn svg { width:12px; height:12px; }

        @media (max-width:1024px) { .pr-chips { display:none; } }
        @media (max-width:860px) {
            .pr-table thead th:nth-child(4), .pr-table tbody td:nth-child(4) { display:none; }
        }
        @media (max-width:640px) {
            .pr-content { padding:1.25rem 1rem 0; }
            .pr-table thead th:nth-child(3), .pr-table tbody td:nth-child(3) { display:none; }
            .pr-filter-group { min-width:100%; }
        }
    </style>

    @php
        $filterStatus = request('status');
        $filterDari   = request('dari');
        $filterSampai = request('sampai');
        $filterSearch = request('search');

        $statusLabels = [
            'selesai'    => 'Selesai',
            'ditolak'    => 'Ditolak',
            'dibatalkan' => 'Dibatalkan',
        ];
    @endphp

    <div class="pr-root">

        {{-- ── HERO ── --}}
        <div class="pr-hero">
            <div class="pr-hero-inner">
                <div class="pr-hero-top">
                    <div>
                        <div class="pr-breadcrumb">
                            <a href="{{ route('pengelola.pembelian.index') }}">Dashboard Pembelian</a>
                            <span class="pr-breadcrumb-sep">/</span>
                            <span class="pr-breadcrumb-cur">Riwayat Transaksi</span>
                        </div>
                        <div class="pr-eyebrow">
                            <span class="pr-eyebrow-dot"></span>
                            Manajemen Pembelian
                        </div>
                        <h1>Riwayat Transaksi</h1>
                        <p class="pr-hero-sub">Seluruh transaksi yang telah selesai, ditolak, maupun dibatalkan oleh semua pembeli.</p>
                        <a href="{{ route('pengelola.pembelian.index') }}" class="pr-hero-btn" style="display:inline-flex;margin-top:1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Dashboard
                        </a>
                    </div>

                    <div class="pr-chips">
                        <div class="pr-chip green">
                            <span class="pr-chip-val">{{ $counts['selesai'] }}</span>
                            <span class="pr-chip-lbl">Selesai</span>
                        </div>
                        <div class="pr-chip red">
                            <span class="pr-chip-val">{{ $counts['ditolak'] }}</span>
                            <span class="pr-chip-lbl">Ditolak</span>
                        </div>
                        <div class="pr-chip slate">
                            <span class="pr-chip-val">{{ $counts['dibatalkan'] }}</span>
                            <span class="pr-chip-lbl">Dibatalkan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="pr-content">

            @if(session('success'))
                <div class="pr-flash ok">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- ── FILTER ── --}}
            <div class="pr-filter-card">
                <form method="GET" action="{{ route('pengelola.pembelian.riwayat') }}">
                    <div class="pr-filter-row">

                        <div class="pr-filter-group" style="flex:2;min-width:200px;">
                            <label class="pr-filter-label">Cari Pembeli / Koleksi</label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);width:14px;height:14px;color:#94a3b8;pointer-events:none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                <input type="text" name="search" value="{{ $filterSearch }}"
                                       placeholder="Nama pembeli atau nama koleksi…"
                                       class="pr-filter-input" style="padding-left:2.4rem;">
                            </div>
                        </div>

                        <div class="pr-filter-group">
                            <label class="pr-filter-label">Status</label>
                            <select name="status" class="pr-filter-input pr-filter-select">
                                <option value="">Semua Status</option>
                                <option value="selesai"    {{ $filterStatus === 'selesai'    ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak"    {{ $filterStatus === 'ditolak'    ? 'selected' : '' }}>Ditolak</option>
                                <option value="dibatalkan" {{ $filterStatus === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <div class="pr-filter-group">
                            <label class="pr-filter-label">Dari Tanggal</label>
                            <input type="date" name="dari" class="pr-filter-input"
                                   value="{{ $filterDari }}" max="{{ date('Y-m-d') }}">
                        </div>

                        <div class="pr-filter-group">
                            <label class="pr-filter-label">Sampai Tanggal</label>
                            <input type="date" name="sampai" class="pr-filter-input"
                                   value="{{ $filterSampai }}" max="{{ date('Y-m-d') }}">
                        </div>

                        <div class="pr-filter-actions">
                            <button type="submit" class="pr-filter-btn pr-filter-btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                Terapkan
                            </button>
                            @if($filterStatus || $filterDari || $filterSampai || $filterSearch)
                                <a href="{{ route('pengelola.pembelian.riwayat') }}" class="pr-filter-btn pr-filter-btn-reset">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Reset
                                </a>
                            @endif
                        </div>

                    </div>

                    {{-- ACTIVE FILTER TAGS --}}
                    @if($filterStatus || $filterDari || $filterSampai || $filterSearch)
                    <div class="pr-active-filters">
                        <span class="pr-active-label">Filter aktif:</span>
                        @if($filterSearch)
                            <span class="pr-filter-tag">Cari: "{{ $filterSearch }}"</span>
                        @endif
                        @if($filterStatus)
                            <span class="pr-filter-tag">Status: {{ $statusLabels[$filterStatus] ?? $filterStatus }}</span>
                        @endif
                        @if($filterDari)
                            <span class="pr-filter-tag">Dari: {{ \Carbon\Carbon::parse($filterDari)->format('d M Y') }}</span>
                        @endif
                        @if($filterSampai)
                            <span class="pr-filter-tag">Sampai: {{ \Carbon\Carbon::parse($filterSampai)->format('d M Y') }}</span>
                        @endif
                    </div>
                    @endif
                </form>
            </div>

            {{-- ── TABLE ── --}}
            <div class="pr-card">
                <div class="pr-card-head">
                    <div class="pr-card-title">Daftar Riwayat Transaksi</div>
                    <span class="pr-card-count">{{ $riwayat->total() }} transaksi</span>
                </div>

                @if($riwayat->isEmpty())
                    <div class="pr-empty">
                        <div class="pr-empty-icon">🧾</div>
                        <h3>Tidak ada data</h3>
                        <p>
                            @if($filterStatus || $filterDari || $filterSampai || $filterSearch)
                                Tidak ada transaksi yang sesuai dengan filter yang dipilih.
                            @else
                                Belum ada transaksi selesai, ditolak, atau dibatalkan.
                            @endif
                        </p>
                        @if($filterStatus || $filterDari || $filterSampai || $filterSearch)
                            <a href="{{ route('pengelola.pembelian.riwayat') }}" class="pr-empty-cta">Reset Filter</a>
                        @endif
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table class="pr-table">
                            <thead>
                                <tr>
                                    <th>No. Pengajuan</th>
                                    <th>Pembeli</th>
                                    <th>Koleksi</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($riwayat as $item)
                                @php
                                    $badgeClass = match($item->status) {
                                        'selesai'    => 'st-selesai',
                                        'ditolak'    => 'st-ditolak',
                                        'dibatalkan' => 'st-dibatalkan',
                                        default      => 'st-dibatalkan',
                                    };
                                    $statusLabel = match($item->status) {
                                        'selesai'    => 'Selesai',
                                        'ditolak'    => 'Ditolak',
                                        'dibatalkan' => 'Dibatalkan',
                                        default      => ucfirst($item->status),
                                    };
                                    $tanggalAkhir = match($item->status) {
                                        'selesai'    => $item->completed_at,
                                        default      => $item->updated_at,
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <div class="pr-id">BLI-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        <div class="pr-date">{{ ($item->submitted_at ?? $item->created_at)->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="pr-name">{{ $item->nama_lengkap }}</div>
                                        <div class="pr-date">{{ $item->email }}</div>
                                    </td>
                                    <td>
                                        <div class="pr-koleksi">
                                            <div class="pr-thumb">
                                                @if($item->painting->image_url ?? null)
                                                    <img src="{{ $item->painting->image_url }}" alt="{{ $item->painting->title }}">
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="pr-kname">{{ $item->painting->title ?? '-' }}</div>
                                                <div class="pr-kartist">{{ $item->painting->artist ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size:.82rem;font-weight:600;color:{{ $item->status === 'selesai' ? '#059669' : 'var(--slate)' }};">
                                            Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="pr-badge {{ $badgeClass }}">
                                            <span class="pr-badge-dot"></span>
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="font-size:.81rem;color:var(--navy);font-weight:500;">
                                            {{ $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') : '-' }}
                                        </div>
                                        <div class="pr-date">
                                            {{ $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->format('H:i') : '' }}
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('pengelola.pembelian.show', $item) }}" class="pr-btn-detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($riwayat->hasPages())
                    <div class="pr-pagination">
                        <div class="pr-page-info">
                            Menampilkan <strong>{{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }}</strong>
                            dari <strong>{{ $riwayat->total() }}</strong> transaksi
                        </div>
                        <div class="pr-page-btns">
                            @if($riwayat->onFirstPage())
                                <span class="pr-page-btn disabled"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg></span>
                            @else
                                <a href="{{ $riwayat->previousPageUrl() }}" class="pr-page-btn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg></a>
                            @endif
                            @foreach($riwayat->getUrlRange(max(1,$riwayat->currentPage()-2), min($riwayat->lastPage(),$riwayat->currentPage()+2)) as $page => $url)
                                <a href="{{ $url }}" class="pr-page-btn {{ $page == $riwayat->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                            @endforeach
                            @if($riwayat->hasMorePages())
                                <a href="{{ $riwayat->nextPageUrl() }}" class="pr-page-btn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></a>
                            @else
                                <span class="pr-page-btn disabled"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></span>
                            @endif
                        </div>
                    </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>