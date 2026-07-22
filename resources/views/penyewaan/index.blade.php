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
        .sw-root { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; padding-bottom: 4rem; }

        /* ── HERO ── */
        .sw-hero { background: linear-gradient(140deg, #0b1d35 0%, #142744 55%, #1c3a68 100%); padding: 2.5rem 0 0; position: relative; overflow: hidden; }
        .sw-hero::before { content:''; position:absolute; top:-60px; right:-80px; width:420px; height:420px; border-radius:50%; background:radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events:none; }
        .sw-hero::after  { content:''; position:absolute; bottom:-40px; left:-60px; width:300px; height:300px; border-radius:50%; background:radial-gradient(circle,rgba(29,78,216,.06) 0%,transparent 70%); pointer-events:none; }
        .sw-hero-inner { max-width:1200px; margin:0 auto; padding:0 2rem; position:relative; z-index:1; }
        .sw-hero-top { display:flex; align-items:flex-start; justify-content:space-between; gap:2rem; margin-bottom:2rem; flex-wrap:wrap; }

        .sw-eyebrow { display:inline-flex; align-items:center; gap:.45rem; background:rgba(56,189,248,.1); border:1px solid rgba(56,189,248,.22); color:var(--sky); font-size:.68rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; padding:.32rem .9rem; border-radius:99px; margin-bottom:.9rem; }
        .sw-eyebrow-dot { width:5px; height:5px; background:var(--sky); border-radius:50%; }
        .sw-hero h1 { font-family:'Playfair Display',serif; font-size:2.2rem; font-weight:700; color:#fff; line-height:1.15; margin:0 0 .5rem; }
        .sw-hero h1 em { font-style:italic; color:var(--sky); }
        .sw-hero-sub { color:rgba(255,255,255,.45); font-size:.85rem; line-height:1.75; max-width:460px; margin:0; }

        /* HERO CTA */
        .sw-hero-cta { margin-top:1.25rem; display:flex; gap:.75rem; flex-wrap:wrap; }
        .sw-cta-btn { display:inline-flex; align-items:center; gap:.45rem; padding:.65rem 1.35rem; border-radius:.875rem; font-size:.82rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .2s; }
        .sw-cta-btn svg { width:14px; height:14px; }
        .sw-cta-primary { background:var(--sky); color:var(--navy); }
        .sw-cta-primary:hover { background:#7dd3fc; transform:translateY(-1px); }
        .sw-cta-ghost { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.15); color:rgba(255,255,255,.85); }
        .sw-cta-ghost:hover { background:rgba(255,255,255,.13); }

        /* CHIPS */
        .sw-chips { display:flex; gap:.6rem; flex-wrap:wrap; justify-content:flex-end; align-items:flex-start; flex-shrink:0; }
        .sw-chip { display:flex; flex-direction:column; align-items:flex-end; background:rgba(255,255,255,.055); border:1px solid rgba(255,255,255,.09); border-radius:1.1rem; padding:.85rem 1.1rem; min-width:96px; transition:background .2s,transform .2s; }
        .sw-chip:hover { background:rgba(255,255,255,.1); transform:translateY(-2px); }
        .sw-chip-val { font-family:'Playfair Display',serif; font-size:1.9rem; color:#fff; line-height:1; }
        .sw-chip-lbl { font-size:.66rem; font-weight:600; color:rgba(255,255,255,.45); letter-spacing:.06em; text-align:right; margin-top:.2rem; max-width:88px; line-height:1.35; }
        .sw-chip.urgent { border-color:rgba(251,191,36,.28); background:rgba(251,191,36,.07); }
        .sw-chip.urgent .sw-chip-val { color:#fbbf24; }
        .sw-chip.success { border-color:rgba(52,211,153,.28); background:rgba(52,211,153,.07); }
        .sw-chip.success .sw-chip-val { color:#34d399; }
        .sw-chip.sky { border-color:rgba(56,189,248,.28); background:rgba(56,189,248,.07); }
        .sw-chip.sky .sw-chip-val { color:var(--sky); }

        /* STAT BAR */
        .sw-stat-bar { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); border-top:1px solid rgba(255,255,255,.07); position:relative; z-index:1; }
        .sw-stat-item { padding:1rem 1.25rem; border-right:1px solid rgba(255,255,255,.06); transition:background .18s; text-decoration:none; position:relative; }
        .sw-stat-item:last-child { border-right:none; }
        .sw-stat-item:hover { background:rgba(255,255,255,.04); }
        .sw-stat-num { font-family:'Playfair Display',serif; font-size:1.45rem; color:#fff; display:block; line-height:1; }
        .sw-stat-txt { font-size:.67rem; font-weight:600; color:rgba(255,255,255,.4); letter-spacing:.06em; text-transform:uppercase; display:flex; align-items:center; gap:.3rem; margin-top:.3rem; }
        .sw-stat-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }

        /* CONTENT */
        .sw-content { max-width:1200px; margin:0 auto; padding:1.75rem 2rem 0; }

        /* FLASH */
        .sw-flash { border-radius:.875rem; padding:.85rem 1.2rem; font-size:.83rem; font-weight:600; display:flex; align-items:center; gap:.55rem; margin-bottom:1.25rem; }
        .sw-flash svg { width:16px; height:16px; flex-shrink:0; }
        .sw-flash.ok  { background:#d1fae5; border:1px solid #6ee7b7; color:#065f46; }
        .sw-flash.err { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; }

        /* QUICK ACTIONS */
        .sw-quick-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1rem; margin-bottom:1.25rem; }
        .sw-quick-card { background:var(--white); border:1.5px solid var(--border); border-radius:1.25rem; padding:1.5rem; display:flex; align-items:center; gap:1rem; text-decoration:none; transition:all .2s; box-shadow:0 2px 12px rgba(11,29,53,.04); }
        .sw-quick-card:hover { border-color:#93c5fd; transform:translateY(-2px); box-shadow:0 8px 28px rgba(29,78,216,.1); }
        .sw-quick-icon { width:46px; height:46px; border-radius:.875rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.35rem; }
        .sw-quick-icon.blue   { background:linear-gradient(135deg,#dbeafe,#bfdbfe); }
        .sw-quick-icon.indigo { background:linear-gradient(135deg,#e0e7ff,#c7d2fe); }
        .sw-quick-text h3 { font-size:.88rem; font-weight:700; color:var(--navy); margin:0 0 .2rem; }
        .sw-quick-text p  { font-size:.76rem; color:var(--slate); margin:0; line-height:1.5; }
        .sw-quick-arrow { margin-left:auto; color:#93c5fd; font-size:1rem; flex-shrink:0; }

        /* LIST CARD */
        .sw-card { background:var(--white); border:1.5px solid var(--border); border-radius:1.5rem; box-shadow:0 4px 28px rgba(11,29,53,.055); overflow:hidden; }
        .sw-card-head { display:flex; align-items:center; justify-content:space-between; padding:1.1rem 1.5rem; border-bottom:1.5px solid #f0f4f8; gap:1rem; flex-wrap:wrap; }
        .sw-card-title { font-size:.76rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--navy); display:flex; align-items:center; gap:.55rem; }
        .sw-card-title::before { content:''; width:3px; height:15px; background:linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius:99px; }
        .sw-card-count { font-size:.74rem; color:var(--slate); background:#f1f5f9; padding:.2rem .65rem; border-radius:99px; font-weight:600; }

        /* FILTER TOOLBAR inside card head */
        .sw-filter-select { border:1.5px solid var(--border); border-radius:.875rem; padding:.42rem 2rem .42rem .875rem; font-size:.78rem; font-family:'DM Sans',sans-serif; color:var(--navy); font-weight:500; background:#f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2394a3b8'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E") no-repeat right .55rem center / 14px; appearance:none; outline:none; cursor:pointer; }
        .sw-filter-select:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(29,78,216,.09); }

        /* ITEM */
        .sw-item { border-bottom:1px solid #f0f4f8; padding:1.25rem 1.5rem; display:grid; grid-template-columns:1fr auto; gap:1.25rem; align-items:center; transition:background .12s; animation:swItemIn .3s ease both; }
        .sw-item:last-child { border-bottom:none; }
        .sw-item:hover { background:#fafbff; }
        @keyframes swItemIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }

        .sw-item-left { display:flex; align-items:center; gap:1rem; min-width:0; }
        .sw-item-thumb { width:52px; height:52px; border-radius:.75rem; object-fit:cover; background:#f1f5f9; flex-shrink:0; display:flex; align-items:center; justify-content:center; overflow:hidden; }
        .sw-item-thumb img { width:100%; height:100%; object-fit:cover; }
        .sw-item-thumb svg { width:20px; height:20px; color:#b0bac6; }

        .sw-item-id    { font-family:'Playfair Display',serif; font-size:.92rem; color:var(--blue); font-weight:600; }
        .sw-item-title { font-size:.88rem; font-weight:700; color:var(--navy); margin:.1rem 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .sw-item-sub   { font-size:.74rem; color:var(--slate); }

        .sw-item-meta { display:flex; gap:1.5rem; margin-top:.65rem; flex-wrap:wrap; }
        .sw-meta-lbl  { font-size:.66rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#94a3b8; }
        .sw-meta-val  { font-size:.82rem; font-weight:600; color:var(--navy); margin-top:.1rem; }

        .sw-item-right   { display:flex; flex-direction:column; align-items:flex-end; gap:.65rem; flex-shrink:0; }
        .sw-item-actions { display:flex; flex-direction:column; gap:.4rem; }

        /* STEP PILL */
        .sw-step-pill { display:inline-flex; align-items:center; gap:.3rem; background:#f1f5f9; border:1px solid var(--border); border-radius:99px; padding:.2rem .7rem; font-size:.68rem; font-weight:700; color:var(--slate); }
        .sw-step-pill svg { width:10px; height:10px; }

        /* BADGES */
        .sw-badge { display:inline-flex; align-items:center; gap:.28rem; padding:.28rem .8rem; border-radius:99px; font-size:.68rem; font-weight:700; white-space:nowrap; letter-spacing:.02em; }
        .sw-badge-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }

        .st-draft                { background:#f1f5f9; color:#475569; }
        .st-draft .sw-badge-dot { background:#94a3b8; }
        .st-menunggu-verifikasi                { background:#fef3c7; color:#92400e; }
        .st-menunggu-verifikasi .sw-badge-dot  { background:#f59e0b; }
        .st-menunggu-perjanjian                { background:#dbeafe; color:#1e40af; }
        .st-menunggu-perjanjian .sw-badge-dot  { background:#3b82f6; }
        .st-menunggu-pembayaran                { background:#ffedd5; color:#9a3412; }
        .st-menunggu-pembayaran .sw-badge-dot  { background:#f97316; }
        .st-aktif                { background:#d1fae5; color:#065f46; }
        .st-aktif .sw-badge-dot { background:#10b981; }
        .st-selesai                { background:#dcfce7; color:#166534; }
        .st-selesai .sw-badge-dot { background:#22c55e; }
        .st-ditolak                { background:#fee2e2; color:#991b1b; }
        .st-ditolak .sw-badge-dot { background:#ef4444; }
        .st-dibatalkan                { background:#f1f5f9; color:#475569; }
        .st-dibatalkan .sw-badge-dot { background:#94a3b8; }

        /* BUTTONS */
        .sw-btn { display:inline-flex; align-items:center; justify-content:center; gap:.38rem; padding:.5rem 1.1rem; border-radius:.65rem; font-size:.78rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; white-space:nowrap; transition:all .18s; border:none; cursor:pointer; }
        .sw-btn svg { width:12px; height:12px; }
        .sw-btn-navy   { background:var(--navy); color:#fff; }
        .sw-btn-navy:hover { background:var(--blue); transform:translateY(-1px); box-shadow:0 4px 12px rgba(29,78,216,.3); }
        .sw-btn-primary { background:var(--blue); color:#fff; }
        .sw-btn-primary:hover { background:#1e40af; transform:translateY(-1px); box-shadow:0 4px 12px rgba(29,78,216,.3); }
        .sw-btn-pay { background:linear-gradient(135deg,#059669,#10b981); color:#fff; }
        .sw-btn-pay:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(16,185,129,.35); }
        .sw-btn-danger { background:transparent; border:1.5px solid #fca5a5; color:#dc2626; }
        .sw-btn-danger:hover { background:#fef2f2; }

        /* EMPTY */
        .sw-empty { padding:4.5rem 2rem; text-align:center; }
        .sw-empty-icon { width:64px; height:64px; background:linear-gradient(135deg,#dbeafe,#e0e7ff); border-radius:1rem; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; font-size:1.75rem; }
        .sw-empty h3 { font-family:'Playfair Display',serif; font-size:1.25rem; color:var(--navy); margin:0 0 .4rem; }
        .sw-empty p  { font-size:.83rem; color:var(--slate); max-width:320px; margin:0 auto .75rem; line-height:1.6; }
        .sw-empty-cta { display:inline-flex; align-items:center; gap:.4rem; margin-top:.5rem; padding:.65rem 1.35rem; background:var(--navy); color:#fff; border-radius:.875rem; font-size:.82rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .2s; }
        .sw-empty-cta:hover { background:var(--blue); transform:translateY(-1px); }

        @media (max-width:1024px) { .sw-chips { display:none; } }
        @media (max-width:768px) {
            .sw-hero h1 { font-size:1.75rem; }
            .sw-item { grid-template-columns:1fr; }
            .sw-item-right { align-items:flex-start; flex-direction:row; flex-wrap:wrap; }
            .sw-content { padding:1.25rem 1rem 0; }
        }
        @media (max-width:480px) {
            .sw-item-meta { gap:.75rem; }
            .sw-item-actions { flex-direction:row; }
        }
    </style>

    <div class="sw-root">

        {{-- ── HERO ── --}}
        <div class="sw-hero">
            <div class="sw-hero-inner">
                <div class="sw-hero-top">
                    <div>
                        <div class="sw-eyebrow">
                            <span class="sw-eyebrow-dot"></span>
                            Dashboard Penyewaan Koleksi
                        </div>
                        <h1>Halo, <em>{{ Auth::user()->name }}</em> 👋</h1>
                        <p class="sw-hero-sub">Kelola pengajuan sewa koleksi museum Anda — pantau status verifikasi, selesaikan perjanjian dan pembayaran, serta lacak masa aktif dari satu tempat.</p>
                        <div class="sw-hero-cta">
                            <a href="{{ route('gallery') }}" class="sw-cta-btn sw-cta-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                Jelajahi Katalog
                            </a>
                            <a href="#daftar-penyewaan" class="sw-cta-btn sw-cta-ghost">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                Lihat Pengajuan Saya
                            </a>
                        </div>
                    </div>

                    @php
                        $total              = $filteredRequests->count();
                        $countDraft         = $counts['draft'] ?? 0;
                        $countVerifikasi    = $counts['menunggu_verifikasi'] ?? 0;
                        $countPembayaran    = $counts['menunggu_pembayaran'] ?? 0;
                        $countAktif         = $counts['aktif'] ?? 0;
                    @endphp
                    <div class="sw-chips">
                        @if($countDraft > 0)
                        <div class="sw-chip urgent">
                            <span class="sw-chip-val">{{ $countDraft }}</span>
                            <span class="sw-chip-lbl">Draft Belum Kirim</span>
                        </div>
                        @endif
                        @if($countVerifikasi > 0)
                        <div class="sw-chip sky">
                            <span class="sw-chip-val">{{ $countVerifikasi }}</span>
                            <span class="sw-chip-lbl">Perlu Verifikasi</span>
                        </div>
                        @endif
                        @if($countPembayaran > 0)
                        <div class="sw-chip urgent">
                            <span class="sw-chip-val">{{ $countPembayaran }}</span>
                            <span class="sw-chip-lbl">Menunggu Bayar</span>
                        </div>
                        @endif
                        <div class="sw-chip success">
                            <span class="sw-chip-val">{{ $countAktif }}</span>
                            <span class="sw-chip-lbl">Sewa Aktif</span>
                        </div>
                        <div class="sw-chip">
                            <span class="sw-chip-val">{{ $counts['all'] ?? 0 }}</span>
                            <span class="sw-chip-lbl">Total Pengajuan</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STAT BAR --}}
            <div class="sw-stat-bar">
                @php
                    $statItems = [
                        ['label' => 'Draft',               'val' => $counts['draft'] ?? 0,                   'dot' => '#94a3b8', 'filter' => 'draft'],
                        ['label' => 'Menunggu Verifikasi', 'val' => $counts['menunggu_verifikasi'] ?? 0,      'dot' => '#f59e0b', 'filter' => 'menunggu_verifikasi'],
                        ['label' => 'Menunggu Pembayaran', 'val' => $counts['menunggu_pembayaran'] ?? 0,      'dot' => '#f97316', 'filter' => 'menunggu_pembayaran'],
                        ['label' => 'Sewa Aktif',          'val' => $counts['aktif'] ?? 0,                   'dot' => '#10b981', 'filter' => 'aktif'],
                        ['label' => 'Ditolak',             'val' => $counts['ditolak'] ?? 0,                 'dot' => '#ef4444', 'filter' => 'ditolak'],
                        ['label' => 'Semua',               'val' => $counts['all'] ?? 0,                     'dot' => '#64748b', 'filter' => 'all'],
                    ];
                @endphp
                @foreach($statItems as $s)
                    <a href="{{ route('penyewaan.index', ['status' => $s['filter']]) }}" class="sw-stat-item">
                        <span class="sw-stat-num">{{ $s['val'] }}</span>
                        <span class="sw-stat-txt">
                            <span class="sw-stat-dot" style="background:{{ $s['dot'] }};"></span>
                            {{ $s['label'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="sw-content">

            @if(session('success'))
                <div class="sw-flash ok">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="sw-flash err">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- QUICK ACTIONS --}}
            <div class="sw-quick-grid">
                <a href="{{ route('gallery') }}" class="sw-quick-card">
                    <div class="sw-quick-icon blue">🖼️</div>
                    <div class="sw-quick-text">
                        <h3>Jelajahi Katalog</h3>
                        <p>Temukan koleksi lukisan yang tersedia untuk disewa</p>
                    </div>
                    <span class="sw-quick-arrow">→</span>
                </a>
                <a href="{{ route('penyewaan.riwayat') }}" class="sw-quick-card" style="border-color:#34d399;">
                    <div class="sw-quick-icon" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);">🧾</div>
                    <div class="sw-quick-text">
                        <h3 style="color:#065f46;">Riwayat Penyewaan</h3>
                        <p>{{ ($counts['selesai'] ?? 0) + ($counts['ditolak'] ?? 0) + ($counts['dibatalkan'] ?? 0) }} transaksi selesai/ditolak/dibatalkan</p>
                    </div>
                    <span class="sw-quick-arrow" style="color:#34d399;">→</span>
                </a>
            </div>

            {{-- DAFTAR PENGAJUAN --}}
            @php
                $aktif = $filteredRequests->whereNotIn('status', ['selesai', 'ditolak', 'dibatalkan']);
            @endphp

            <div class="sw-card" id="daftar-penyewaan">
                <div class="sw-card-head">
                    <div class="sw-card-title">Daftar Pengajuan Penyewaan</div>
                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                        <span class="sw-card-count">{{ $aktif->count() }} pengajuan aktif</span>
                        <form method="GET" action="{{ route('penyewaan.index') }}">
                            <select name="status" class="sw-filter-select" onchange="this.form.submit()">
                                <option value="all"                  {{ $statusFilter === 'all'                  ? 'selected' : '' }}>Semua Status</option>
                                <option value="draft"                {{ $statusFilter === 'draft'                ? 'selected' : '' }}>Draft</option>
                                <option value="menunggu_verifikasi"  {{ $statusFilter === 'menunggu_verifikasi'  ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="menunggu_pembayaran"  {{ $statusFilter === 'menunggu_pembayaran'  ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="aktif"                {{ in_array($statusFilter, ['aktif','active'], true) ? 'selected' : '' }}>Sewa Aktif</option>
                                <option value="ditolak"              {{ $statusFilter === 'ditolak'              ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </form>
                    </div>
                </div>

                @if($filteredRequests->isEmpty())
                    <div class="sw-empty">
                        <div class="sw-empty-icon">📭</div>
                        <h3>Belum ada pengajuan</h3>
                        <p>Belum ada pengajuan untuk status ini. Jelajahi katalog dan mulai sewa koleksi pilihan Anda.</p>
                        <a href="{{ route('gallery') }}" class="sw-cta-btn sw-cta-primary">
                            Jelajahi Katalog
                        </a>
                    </div>
                @elseif($aktif->isEmpty())
                    <div class="sw-empty">
                        <div class="sw-empty-icon">📋</div>
                        <h3>Tidak ada pengajuan aktif</h3>
                        <p>Semua pengajuan Anda sudah selesai. Lihat riwayat penyewaan untuk detail transaksi.</p>
                        <a href="{{ route('penyewaan.riwayat') }}" class="sw-empty-cta">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            Lihat Riwayat Penyewaan
                        </a>
                    </div>
                @else
                    @foreach($aktif as $requestItem)
                        @php
                            $status = $requestItem->status ?? 'draft';
                            $badgeClass = match($status) {
                                'draft'                         => 'st-draft',
                                'menunggu_verifikasi'           => 'st-menunggu-verifikasi',
                                'menunggu_dokumen_perjanjian',
                                'verifikasi_dokumen_perjanjian' => 'st-menunggu-perjanjian',
                                'menunggu_pembayaran'           => 'st-menunggu-pembayaran',
                                'aktif'                         => 'st-aktif',
                                'selesai'                       => 'st-selesai',
                                'ditolak'                       => 'st-ditolak',
                                default                         => 'st-dibatalkan',
                            };
                            $statusLabel = $requestItem->status_label;
                        @endphp
                        <div class="sw-item">
                            <div class="sw-item-left">
                                <div class="sw-item-thumb">
                                    @if($requestItem->painting->image_url ?? $requestItem->painting->image_path ?? null)
                                        <img src="{{ $requestItem->painting->image_url ?? asset('storage/' . $requestItem->painting->image_path) }}"
                                             alt="{{ $requestItem->painting->title ?? '' }}">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                    @endif
                                </div>
                                <div style="min-width:0;">
                                    <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-bottom:.25rem;">
                                        <span class="sw-item-id">SW-{{ str_pad($requestItem->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="sw-badge {{ $badgeClass }}">
                                            <span class="sw-badge-dot"></span>
                                            {{ $statusLabel }}
                                        </span>
                                        @if($requestItem->submission_status === 'draft')
                                            <span class="sw-step-pill">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                                Langkah {{ $requestItem->current_step ?? 1 }}/3
                                            </span>
                                        @endif
                                    </div>
                                    <div class="sw-item-title">{{ $requestItem->painting->title ?? '-' }}</div>
                                    <div class="sw-item-sub">
                                        {{ $requestItem->painting->artist ?? '' }}
                                        &bull;
                                        {{ $requestItem->rental_type === 'instansi' ? 'Instansi' : 'Perseorangan' }}
                                    </div>
                                    <div class="sw-item-meta">
                                        <div>
                                            <div class="sw-meta-lbl">Dibuat</div>
                                            <div class="sw-meta-val">{{ $requestItem->created_at->format('d M Y') }}</div>
                                        </div>
                                        @if($requestItem->start_date && $requestItem->end_date)
                                        <div>
                                            <div class="sw-meta-lbl">Periode Sewa</div>
                                            <div class="sw-meta-val">
                                                {{ $requestItem->start_date->format('d M Y') }}
                                                <span style="color:#b0bac6;font-weight:400;"> – </span>
                                                {{ $requestItem->end_date->format('d M Y') }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="sw-meta-lbl">Durasi</div>
                                            <div class="sw-meta-val" style="color:#059669;">{{ $requestItem->duration_days }} hari</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="sw-item-right">
                                <div class="sw-item-actions">
                                    @if($requestItem->submission_status === 'draft')
                                        <a href="{{ route('penyewaan.step' . min($requestItem->current_step ?? 1, 3), ['koleksi' => $requestItem->painting->id]) }}"
                                           class="sw-btn sw-btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                            Lanjutkan Pengisian
                                        </a>
                                        <form action="{{ route('penyewaan.requests.destroy', $requestItem) }}" method="POST"
                                              onsubmit="return confirm('Hapus draft ini? Tindakan ini tidak dapat dibatalkan.')">
                                            @csrf
                                            <button type="submit" class="sw-btn sw-btn-danger" style="width:100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Hapus Draft
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('penyewaan.requests.show', $requestItem) }}"
                                           class="sw-btn sw-btn-navy">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            Lihat Detail
                                        </a>
                                        @if($requestItem->status === 'menunggu_pembayaran' && $requestItem->signed_agreement_status === 'accepted')
                                            <a href="{{ route('penyewaan.requests.payment', ['penyewaan' => $requestItem->id]) }}"
                                               class="sw-btn sw-btn-pay">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                                                Bayar Sekarang
                                            </a>
                                        @endif
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