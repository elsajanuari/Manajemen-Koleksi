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

        /* LIST CARD */
        .pr-card { background:var(--white); border:1.5px solid var(--border); border-radius:1.5rem; box-shadow:0 4px 28px rgba(11,29,53,.055); overflow:hidden; }
        .pr-card-head { display:flex; align-items:center; justify-content:space-between; padding:1.1rem 1.5rem; border-bottom:1.5px solid #f0f4f8; gap:1rem; flex-wrap:wrap; }
        .pr-card-title { font-size:.76rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--navy); display:flex; align-items:center; gap:.55rem; }
        .pr-card-title::before { content:''; width:3px; height:15px; background:linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius:99px; }
        .pr-card-count { font-size:.74rem; color:var(--slate); background:#f1f5f9; padding:.2rem .65rem; border-radius:99px; font-weight:600; }

        /* ITEM */
        .pr-item { border-bottom:1px solid #f0f4f8; padding:1.25rem 1.5rem; display:grid; grid-template-columns:1fr auto; gap:1.25rem; align-items:center; transition:background .12s; animation:itemIn .3s ease both; }
        .pr-item:last-child { border-bottom:none; }
        .pr-item:hover { background:#fafbff; }
        @keyframes itemIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }

        .pr-item-left { display:flex; align-items:center; gap:1rem; min-width:0; }
        .pr-item-thumb { width:52px; height:52px; border-radius:.75rem; background:#f1f5f9; flex-shrink:0; display:flex; align-items:center; justify-content:center; overflow:hidden; }
        .pr-item-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
        .pr-item-thumb svg { width:20px; height:20px; color:#b0bac6; }

        .pr-item-id { font-family:'Playfair Display',serif; font-size:.92rem; color:var(--blue); font-weight:600; }
        .pr-item-title { font-size:.88rem; font-weight:700; color:var(--navy); margin:.1rem 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .pr-item-artist { font-size:.74rem; color:var(--slate); }

        .pr-item-meta { display:flex; gap:1.5rem; margin-top:.65rem; flex-wrap:wrap; }
        .pr-meta-cell .pr-meta-lbl { font-size:.66rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#94a3b8; }
        .pr-meta-cell .pr-meta-val { font-size:.82rem; font-weight:600; color:var(--navy); margin-top:.1rem; }

        .pr-item-right { display:flex; flex-direction:column; align-items:flex-end; gap:.65rem; flex-shrink:0; }
        .pr-item-actions { display:flex; flex-direction:column; gap:.4rem; }

        /* BADGES */
        .pr-badge { display:inline-flex; align-items:center; gap:.28rem; padding:.28rem .8rem; border-radius:99px; font-size:.68rem; font-weight:700; white-space:nowrap; }
        .pr-badge-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
        .st-selesai    { background:#dcfce7; color:#166534; }
        .st-selesai    .pr-badge-dot { background:#22c55e; }
        .st-ditolak    { background:#fee2e2; color:#991b1b; }
        .st-ditolak    .pr-badge-dot { background:#ef4444; }
        .st-dibatalkan { background:#f1f5f9; color:#475569; }
        .st-dibatalkan .pr-badge-dot { background:#94a3b8; }

        /* BUTTONS */
        .pr-btn { display:inline-flex; align-items:center; justify-content:center; gap:.38rem; padding:.5rem 1.1rem; border-radius:.65rem; font-size:.78rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; white-space:nowrap; transition:all .18s; border:none; cursor:pointer; }
        .pr-btn svg { width:12px; height:12px; }
        .pr-btn-navy { background:var(--navy); color:#fff; }
        .pr-btn-navy:hover { background:var(--blue); transform:translateY(-1px); box-shadow:0 4px 12px rgba(29,78,216,.3); }
        .pr-btn-green { background:linear-gradient(135deg,#059669,#10b981); color:#fff; }
        .pr-btn-green:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(16,185,129,.3); }

        /* EMPTY */
        .pr-empty { padding:4.5rem 2rem; text-align:center; }
        .pr-empty-icon { width:64px; height:64px; background:linear-gradient(135deg,#dbeafe,#e0e7ff); border-radius:1rem; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; font-size:1.75rem; }
        .pr-empty h3 { font-family:'Playfair Display',serif; font-size:1.25rem; color:var(--navy); margin:0 0 .4rem; }
        .pr-empty p { font-size:.83rem; color:var(--slate); max-width:320px; margin:0 auto; line-height:1.6; }
        .pr-empty-cta { display:inline-flex; align-items:center; gap:.4rem; margin-top:1rem; padding:.65rem 1.35rem; background:var(--navy); color:#fff; border-radius:.875rem; font-size:.82rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .2s; }
        .pr-empty-cta:hover { background:var(--blue); transform:translateY(-1px); }

        @media (max-width:1024px) { .pr-chips { display:none; } }
        @media (max-width:768px) {
            .pr-hero h1 { font-size:1.6rem; }
            .pr-item { grid-template-columns:1fr; }
            .pr-item-right { align-items:flex-start; flex-direction:row; flex-wrap:wrap; }
            .pr-content { padding:1.25rem 1rem 0; }
            .pr-filter-group { min-width:100%; }
        }
        @media (max-width:480px) {
            .pr-item-meta { gap:.75rem; }
            .pr-item-actions { flex-direction:row; }
        }
    </style>

    @php
        $filterStatus = request('status');
        $filterDari   = request('dari');
        $filterSampai = request('sampai');

        $jumlahSelesai    = $riwayat->where('status','selesai')->count();
        $jumlahDitolak    = $riwayat->where('status','ditolak')->count();
        $jumlahDibatalkan = $riwayat->where('status','dibatalkan')->count();

        $statusLabels = [
            'selesai'    => 'Selesai',
            'selesai_dengan_kompensasi'=> 'Selesai dengan Kompensasi',
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
                            <a href="{{ route('pembelian.index') }}">Pembelian Saya</a>
                            <span class="pr-breadcrumb-sep">/</span>
                            <span class="pr-breadcrumb-cur">Riwayat Transaksi</span>
                        </div>
                        <div class="pr-eyebrow">
                            <span class="pr-eyebrow-dot"></span>
                            Riwayat Transaksi
                        </div>
                        <h1>Riwayat Pembelian Anda</h1>
                        <p class="pr-hero-sub">Seluruh transaksi yang telah selesai, ditolak, maupun dibatalkan tersimpan di sini.</p>
                        <a href="{{ route('pembelian.index') }}" class="pr-hero-btn" style="margin-top:1rem;display:inline-flex;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Dashboard
                        </a>
                    </div>

                    <div class="pr-chips">
                        <div class="pr-chip green">
                            <span class="pr-chip-val">{{ $jumlahSelesai }}</span>
                            <span class="pr-chip-lbl">Selesai</span>
                        </div>
                        <div class="pr-chip red">
                            <span class="pr-chip-val">{{ $jumlahDitolak }}</span>
                            <span class="pr-chip-lbl">Ditolak</span>
                        </div>
                        <div class="pr-chip slate">
                            <span class="pr-chip-val">{{ $jumlahDibatalkan }}</span>
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
                <form method="GET" action="{{ route('pembelian.riwayat') }}">
                    <div class="pr-filter-row">

                        <div class="pr-filter-group">
                            <label class="pr-filter-label">Status</label>
                            <select name="status" class="pr-filter-input pr-filter-select">
                                <option value="">Semua Status</option>
                                <option value="selesai"    {{ $filterStatus === 'selesai'    ? 'selected' : '' }}>Selesai</option>
                                <option value="selesai_dengan_kompensasi" {{ $filterStatus === 'selesai_dengan_kompensasi' ? 'selected' : '' }}>Selesai dengan Kompensasi</option>
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
                            @if($filterStatus || $filterDari || $filterSampai)
                                <a href="{{ route('pembelian.riwayat') }}" class="pr-filter-btn pr-filter-btn-reset">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Reset
                                </a>
                            @endif
                        </div>

                    </div>

                    {{-- ACTIVE FILTER TAGS --}}
                    @if($filterStatus || $filterDari || $filterSampai)
                    <div class="pr-active-filters">
                        <span class="pr-active-label">Filter aktif:</span>
                        @if($filterStatus)
                            <span class="pr-filter-tag">
                                Status: {{ $statusLabels[$filterStatus] ?? $filterStatus }}
                            </span>
                        @endif
                        @if($filterDari)
                            <span class="pr-filter-tag">
                                Dari: {{ \Carbon\Carbon::parse($filterDari)->format('d M Y') }}
                            </span>
                        @endif
                        @if($filterSampai)
                            <span class="pr-filter-tag">
                                Sampai: {{ \Carbon\Carbon::parse($filterSampai)->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                    @endif
                </form>
            </div>

            {{-- ── LIST ── --}}
            <div class="pr-card">
                <div class="pr-card-head">
                    <div class="pr-card-title">Daftar Riwayat</div>
                    <span class="pr-card-count">{{ $riwayat->count() }} transaksi</span>
                </div>

                @if($riwayat->isEmpty())
                    <div class="pr-empty">
                        <div class="pr-empty-icon">🧾</div>
                        <h3>Tidak ada data</h3>
                        <p>
                            @if($filterStatus || $filterDari || $filterSampai)
                                Tidak ada transaksi yang sesuai dengan filter yang dipilih.
                            @else
                                Belum ada transaksi selesai, ditolak, atau dibatalkan.
                            @endif
                        </p>
                        @if($filterStatus || $filterDari || $filterSampai)
                            <a href="{{ route('pembelian.riwayat') }}" class="pr-empty-cta">Reset Filter</a>
                        @endif
                    </div>
                @else
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
                            'ditolak'    => $item->updated_at,
                            'dibatalkan' => $item->updated_at,
                            default      => $item->updated_at,
                        };
                        $tanggalAkhirLabel = match($item->status) {
                            'selesai'    => 'Selesai Pada',
                            'ditolak'    => 'Ditolak Pada',
                            'dibatalkan' => 'Dibatalkan Pada',
                            default      => 'Diperbarui',
                        };
                    @endphp
                    <div class="pr-item">
                        <div class="pr-item-left">
                            <div class="pr-item-thumb">
                                @if($item->painting->image_url ?? null)
                                    <img src="{{ $item->painting->image_url }}" alt="{{ $item->painting->title }}">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                @endif
                            </div>
                            <div style="min-width:0;">
                                <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-bottom:.2rem;">
                                    <span class="pr-item-id">BLI-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    <span class="pr-badge {{ $badgeClass }}">
                                        <span class="pr-badge-dot"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <div class="pr-item-title">{{ $item->painting->title }}</div>
                                <div class="pr-item-artist">{{ $item->painting->artist }} &bull; {{ $item->painting->category }}</div>
                                <div class="pr-item-meta">
                                    <div class="pr-meta-cell">
                                        <div class="pr-meta-lbl">Diajukan</div>
                                        <div class="pr-meta-val">{{ ($item->submitted_at ?? $item->created_at)->format('d M Y') }}</div>
                                    </div>
                                    <div class="pr-meta-cell">
                                        <div class="pr-meta-lbl">{{ $tanggalAkhirLabel }}</div>
                                        <div class="pr-meta-val">{{ $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') : '-' }}</div>
                                    </div>
                                    <div class="pr-meta-cell">
                                        <div class="pr-meta-lbl">Total Bayar</div>
                                        <div class="pr-meta-val" style="color:{{ $item->status === 'selesai' ? '#059669' : 'var(--slate)' }};">
                                            Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pr-item-right">
                            <div class="pr-item-actions">
                                <a href="{{ route('pembelian.show', $item) }}" class="pr-btn pr-btn-navy">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Lihat Detail
                                </a>
                                @if($item->status === 'selesai')
                                    <a href="{{ route('pembelian.serah-terima.certificate.download', $item) }}" class="pr-btn pr-btn-green">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                        Sertifikat
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</x-app-layout>