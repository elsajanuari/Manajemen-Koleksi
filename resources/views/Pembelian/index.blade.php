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
        .pb-hero { background: linear-gradient(140deg, #0b1d35 0%, #142744 55%, #1c3a68 100%); padding: 2.5rem 0 0; position: relative; overflow: hidden; }
        .pb-hero::before { content:''; position:absolute; top:-60px; right:-80px; width:420px; height:420px; border-radius:50%; background:radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events:none; }
        .pb-hero::after { content:''; position:absolute; bottom:-40px; left:-60px; width:300px; height:300px; border-radius:50%; background:radial-gradient(circle,rgba(29,78,216,.06) 0%,transparent 70%); pointer-events:none; }
        .pb-hero-inner { max-width: 1200px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 1; }
        .pb-hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap; }

        .pb-eyebrow { display:inline-flex; align-items:center; gap:.45rem; background:rgba(56,189,248,.1); border:1px solid rgba(56,189,248,.22); color:var(--sky); font-size:.68rem; font-weight:700; letter-spacing:.18em; text-transform:uppercase; padding:.32rem .9rem; border-radius:99px; margin-bottom:.9rem; }
        .pb-eyebrow-dot { width:5px; height:5px; background:var(--sky); border-radius:50%; }
        .pb-hero h1 { font-family:'Playfair Display',serif; font-size:2.2rem; font-weight:700; color:#fff; line-height:1.15; margin:0 0 .5rem; }
        .pb-hero h1 em { font-style:italic; color:var(--sky); }
        .pb-hero-sub { color:rgba(255,255,255,.45); font-size:.85rem; line-height:1.75; max-width:460px; margin:0; }

        /* HERO CTA */
        .pb-hero-cta { margin-top:1.25rem; display:flex; gap:.75rem; flex-wrap:wrap; }
        .pb-cta-btn { display:inline-flex; align-items:center; gap:.45rem; padding:.65rem 1.35rem; border-radius:.875rem; font-size:.82rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .2s; }
        .pb-cta-btn svg { width:14px; height:14px; }
        .pb-cta-primary { background:var(--sky); color:var(--navy); }
        .pb-cta-primary:hover { background:#7dd3fc; transform:translateY(-1px); }
        .pb-cta-ghost { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.15); color:rgba(255,255,255,.85); }
        .pb-cta-ghost:hover { background:rgba(255,255,255,.13); }

        /* CHIPS */
        .pb-chips { display:flex; gap:.6rem; flex-wrap:wrap; justify-content:flex-end; align-items:flex-start; flex-shrink:0; }
        .pb-chip { display:flex; flex-direction:column; align-items:flex-end; background:rgba(255,255,255,.055); border:1px solid rgba(255,255,255,.09); border-radius:1.1rem; padding:.85rem 1.1rem; min-width:96px; transition:background .2s,transform .2s; }
        .pb-chip:hover { background:rgba(255,255,255,.1); transform:translateY(-2px); }
        .pb-chip-val { font-family:'Playfair Display',serif; font-size:1.9rem; color:#fff; line-height:1; }
        .pb-chip-lbl { font-size:.66rem; font-weight:600; color:rgba(255,255,255,.45); letter-spacing:.06em; text-align:right; margin-top:.2rem; max-width:88px; line-height:1.35; }
        .pb-chip.urgent { border-color:rgba(251,191,36,.28); background:rgba(251,191,36,.07); }
        .pb-chip.urgent .pb-chip-val { color:#fbbf24; }
        .pb-chip.success { border-color:rgba(52,211,153,.28); background:rgba(52,211,153,.07); }
        .pb-chip.success .pb-chip-val { color:#34d399; }
        .pb-chip.sky { border-color:rgba(56,189,248,.28); background:rgba(56,189,248,.07); }
        .pb-chip.sky .pb-chip-val { color:var(--sky); }

        /* STAT BAR */
        .pb-stat-bar {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            border-top: none;
            position: relative;
            z-index: 1;
            background: linear-gradient(to right, #0b1d35, #1d4ed8, #38bdf8);
            box-shadow: 0 4px 16px rgba(11, 29, 53, 0.15);
        }
        .pb-stat-item {
            padding: .85rem 1rem;
            border-right: 1px solid rgba(255,255,255,.1);
            transition: background .18s;
            text-decoration: none;
            position: relative;
        }
        .pb-stat-item:last-child { border-right: none; }
        .pb-stat-item:hover { background: rgba(255,255,255,.07); }
        .pb-stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.45rem;
            color: #ffffff;
            display: block;
            line-height: 1;
        }
        .pb-stat-txt {
            font-size: .67rem;
            font-weight: 600;
            color: rgba(255,255,255,.75);
            letter-spacing: .06em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: .3rem;
            margin-top: .3rem;
        }
        .pb-stat-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }

        @media (max-width: 480px) {
            .pb-stat-bar {
                grid-template-columns: repeat(5, 1fr);
                overflow-x: auto;
            }
            .pb-stat-item {
                min-width: 90px;
                padding: .75rem .65rem;
            }
            .pb-stat-num { font-size: 1.1rem; }
            .pb-stat-txt { font-size: .58rem; }
        }

        /* CONTENT */
        .pb-content { max-width:1200px; margin:0 auto; padding:1.75rem 2rem 0; }

        /* FLASH */
        .pb-flash { border-radius:.875rem; padding:.85rem 1.2rem; font-size:.83rem; font-weight:600; display:flex; align-items:center; gap:.55rem; margin-bottom:1.25rem; }
        .pb-flash svg { width:16px; height:16px; flex-shrink:0; }
        .pb-flash.ok  { background:#d1fae5; border:1px solid #6ee7b7; color:#065f46; }
        .pb-flash.err { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; }

        /* QUICK ACTIONS */
        .pb-quick-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:1rem; margin-bottom:1.25rem; }
        .pb-quick-card { background:var(--white); border:1.5px solid var(--border); border-radius:1.25rem; padding:1.5rem; display:flex; align-items:center; gap:1rem; text-decoration:none; transition:all .2s; box-shadow:0 2px 12px rgba(11,29,53,.04); }
        .pb-quick-card:hover { border-color:#93c5fd; transform:translateY(-2px); box-shadow:0 8px 28px rgba(29,78,216,.1); }
        .pb-quick-icon { width:46px; height:46px; border-radius:.875rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.35rem; }
        .pb-quick-icon.blue { background:linear-gradient(135deg,#dbeafe,#bfdbfe); }
        .pb-quick-icon.indigo { background:linear-gradient(135deg,#e0e7ff,#c7d2fe); }
        .pb-quick-text h3 { font-size:.88rem; font-weight:700; color:var(--navy); margin:0 0 .2rem; }
        .pb-quick-text p { font-size:.76rem; color:var(--slate); margin:0; line-height:1.5; }
        .pb-quick-arrow { margin-left:auto; color:#93c5fd; font-size:1rem; flex-shrink:0; }

        /* LIST CARD */
        .pb-card { background:var(--white); border:1.5px solid var(--border); border-radius:1.5rem; box-shadow:0 4px 28px rgba(11,29,53,.055); overflow:hidden; }
        .pb-card-head { display:flex; align-items:center; justify-content:space-between; padding:1.1rem 1.5rem; border-bottom:1.5px solid #f0f4f8; gap:1rem; flex-wrap:wrap; }
        .pb-card-title { font-size:.76rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--navy); display:flex; align-items:center; gap:.55rem; }
        .pb-card-title::before { content:''; width:3px; height:15px; background:linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius:99px; }
        .pb-card-count { font-size:.74rem; color:var(--slate); background:#f1f5f9; padding:.2rem .65rem; border-radius:99px; font-weight:600; }

        /* ITEM */
        .pb-item { border-bottom:1px solid #f0f4f8; padding:1.25rem 1.5rem; display:grid; grid-template-columns:1fr auto; gap:1.25rem; align-items:center; transition:background .12s; animation:itemIn .3s ease both; }
        .pb-item:last-child { border-bottom:none; }
        .pb-item:hover { background:#fafbff; }
        @keyframes itemIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:none} }

        .pb-item-left { display:flex; align-items:center; gap:1rem; min-width:0; }
        .pb-item-thumb { width:52px; height:52px; border-radius:.75rem; object-fit:cover; background:#f1f5f9; flex-shrink:0; display:flex; align-items:center; justify-content:center; overflow:hidden; }
        .pb-item-thumb img { width:100%; height:100%; object-fit:cover; }
        .pb-item-thumb svg { width:20px; height:20px; color:#b0bac6; }

        .pb-item-id { font-family:'Playfair Display',serif; font-size:.92rem; color:var(--blue); font-weight:600; }
        .pb-item-title { font-size:.88rem; font-weight:700; color:var(--navy); margin:.1rem 0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .pb-item-artist { font-size:.74rem; color:var(--slate); }

        .pb-item-meta { display:flex; gap:1.5rem; margin-top:.65rem; flex-wrap:wrap; }
        .pb-meta-cell { }
        .pb-meta-lbl { font-size:.66rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#94a3b8; }
        .pb-meta-val { font-size:.82rem; font-weight:600; color:var(--navy); margin-top:.1rem; }

        .pb-item-right { display:flex; flex-direction:column; align-items:flex-end; gap:.65rem; flex-shrink:0; }
        .pb-item-price { font-family:'Playfair Display',serif; font-size:1.05rem; color:var(--navy); font-weight:600; white-space:nowrap; }
        .pb-item-actions { display:flex; flex-direction:column; gap:.4rem; }

        /* BADGES */
        .pb-badge { display:inline-flex; align-items:center; gap:.28rem; padding:.28rem .8rem; border-radius:99px; font-size:.68rem; font-weight:700; white-space:nowrap; letter-spacing:.02em; }
        .pb-badge-dot { width:5px; height:5px; border-radius:50%; flex-shrink:0; }
        .st-menunggu-verifikasi { background:#fef3c7; color:#92400e; }
        .st-menunggu-verifikasi .pb-badge-dot { background:#f59e0b; }
        .st-menunggu-pembayaran { background:#ffedd5; color:#9a3412; }
        .st-menunggu-pembayaran .pb-badge-dot { background:#f97316; }
        .st-pembayaran-berhasil { background:#d1fae5; color:#065f46; }
        .st-pembayaran-berhasil .pb-badge-dot { background:#10b981; }
        .st-siap-diserahkan, .st-dalam-pengiriman { background:#dbeafe; color:#1e40af; }
        .st-siap-diserahkan .pb-badge-dot, .st-dalam-pengiriman .pb-badge-dot { background:#3b82f6; }
        .st-selesai { background:#dcfce7; color:#166534; }
        .st-selesai .pb-badge-dot { background:#22c55e; }
        .st-ditolak { background:#fee2e2; color:#991b1b; }
        .st-ditolak .pb-badge-dot { background:#ef4444; }
        .st-dibatalkan { background:#f1f5f9; color:#475569; }
        .st-dibatalkan .pb-badge-dot { background:#94a3b8; }
        .st-pengecekan-kondisi { background:#ede9fe; color:#5b21b6; }
        .st-pengecekan-kondisi .pb-badge-dot { background:#7c3aed; }

        /* BUTTONS */
        .pb-btn { display:inline-flex; align-items:center; justify-content:center; gap:.38rem; padding:.5rem 1.1rem; border-radius:.65rem; font-size:.78rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; white-space:nowrap; transition:all .18s; border:none; cursor:pointer; }
        .pb-btn svg { width:12px; height:12px; }
        .pb-btn-navy { background:var(--navy); color:#fff; }
        .pb-btn-navy:hover { background:var(--blue); transform:translateY(-1px); box-shadow:0 4px 12px rgba(29,78,216,.3); }
        .pb-btn-pay { background:linear-gradient(135deg,#1d4ed8,#2563eb); color:#fff; }
        .pb-btn-pay:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(29,78,216,.35); }
        .pb-btn-danger { background:transparent; border:1.5px solid #fca5a5; color:#dc2626; }
        .pb-btn-danger:hover { background:#fef2f2; }

        /* EMPTY */
        .pb-empty { padding:4.5rem 2rem; text-align:center; }
        .pb-empty-icon { width:64px; height:64px; background:linear-gradient(135deg,#dbeafe,#e0e7ff); border-radius:1rem; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; font-size:1.75rem; }
        .pb-empty h3 { font-family:'Playfair Display',serif; font-size:1.25rem; color:var(--navy); margin:0 0 .4rem; }
        .pb-empty p { font-size:.83rem; color:var(--slate); max-width:320px; margin:0 auto .75rem; line-height:1.6; }
        .pb-empty-cta { display:inline-flex; align-items:center; gap:.4rem; margin-top:.5rem; padding:.65rem 1.35rem; background:var(--navy); color:#fff; border-radius:.875rem; font-size:.82rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .2s; }
        .pb-empty-cta:hover { background:var(--blue); transform:translateY(-1px); }

        @media (max-width:1024px) { .pb-chips { display:none; } }
        @media (max-width:768px) {
            .pb-hero h1 { font-size:1.75rem; }
            .pb-item { grid-template-columns:1fr; }
            .pb-item-right { align-items:flex-start; flex-direction:row; flex-wrap:wrap; }
            .pb-content { padding:1.25rem 1rem 0; }
        }
        @media (max-width:480px) {
            .pb-item-meta { gap:.75rem; }
            .pb-item-actions { flex-direction:row; }
        }
    </style>

    <div class="pb-root">

        {{-- ── HERO ── --}}
        <div class="pb-hero">
            <div class="pb-hero-inner">
                <div class="pb-hero-top">
                    <div>
                        <div class="pb-eyebrow">
                            <span class="pb-eyebrow-dot"></span>
                            Pembelian Koleksi Museum
                        </div>
                        <h1>Halo, <em>{{ Auth::user()->name }}</em> 👋</h1>
                        <p class="pb-hero-sub">Kelola pengajuan pembelian koleksi museum Anda — pantau status verifikasi, selesaikan pembayaran, dan lacak pengiriman dari satu tempat.</p>
                        <div class="pb-hero-cta">
                            <a href="{{ route('gallery') }}" class="pb-cta-btn pb-cta-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                Jelajahi Katalog
                            </a>
                            <a href="#daftar-pembelian" class="pb-cta-btn pb-cta-ghost">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                Lihat Pengajuan Saya
                            </a>
                        </div>
                    </div>

                    @php
                        $total              = $pembelians->count();
                        $menungguVerifikasi = $pembelians->where('status','menunggu_verifikasi')->count();
                        $menungguPembayaran = $pembelians->where('status','menunggu_pembayaran')->count();
                        $berhasil           = $pembelians->whereIn('status',['pembayaran_berhasil','selesai'])->count();
                    @endphp
                    <div class="pb-chips">
                        @if($menungguVerifikasi > 0)
                        <div class="pb-chip urgent">
                            <span class="pb-chip-val">{{ $menungguVerifikasi }}</span>
                            <span class="pb-chip-lbl">Perlu Verifikasi</span>
                        </div>
                        @endif
                        @if($menungguPembayaran > 0)
                        <div class="pb-chip sky">
                            <span class="pb-chip-val">{{ $menungguPembayaran }}</span>
                            <span class="pb-chip-lbl">Menunggu Bayar</span>
                        </div>
                        @endif
                        <div class="pb-chip success">
                            <span class="pb-chip-val">{{ $berhasil }}</span>
                            <span class="pb-chip-lbl">Berhasil</span>
                        </div>
                        <div class="pb-chip">
                            <span class="pb-chip-val">{{ $total }}</span>
                            <span class="pb-chip-lbl">Total Pengajuan</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STAT BAR --}}
            <div class="pb-stat-bar">
                @php
                    $statItems = [
                        ['label'=>'Menunggu Verifikasi', 'val'=>$pembelians->where('status','menunggu_verifikasi')->count(), 'dot'=>'#f59e0b'],
                        ['label'=>'Menunggu Pembayaran', 'val'=>$pembelians->where('status','menunggu_pembayaran')->count(), 'dot'=>'#f97316'],
                        ['label'=>'Dalam Proses',        'val'=>$pembelians->whereIn('status',['pembayaran_berhasil','siap_diserahkan','dalam_pengiriman'])->count(), 'dot'=>'#38bdf8'],
                        ['label'=>'Selesai',             'val'=>$pembelians->where('status','selesai')->count(), 'dot'=>'#10b981'],
                        ['label'=>'Semua',               'val'=>$total, 'dot'=>'#94a3b8'],
                    ];
                @endphp
                @foreach($statItems as $s)
                    <div class="pb-stat-item">
                        <span class="pb-stat-num">{{ $s['val'] }}</span>
                        <span class="pb-stat-txt">
                            <span class="pb-stat-dot" style="background:{{ $s['dot'] }};"></span>
                            {{ $s['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="pb-content">

            @if(session('success'))
                <div class="pb-flash ok">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="pb-flash err">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- QUICK ACTIONS --}}
            @if($pembelians->isNotEmpty())
            <div class="pb-quick-grid">
                <a href="{{ route('gallery') }}" class="pb-quick-card">
                    <div class="pb-quick-icon blue">🖼️</div>
                    <div class="pb-quick-text">
                        <h3>Jelajahi Katalog</h3>
                        <p>Temukan koleksi lukisan yang tersedia</p>
                    </div>
                    <span class="pb-quick-arrow">→</span>
                </a>
                <a href="{{ route('pembelian.riwayat') }}" class="pb-quick-card" style="border-color:#34d399;">                    <div class="pb-quick-icon" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);">🧾</div>
                    <div class="pb-quick-text">
                        <h3 style="color:#065f46;">Riwayat Transaksi</h3>
                        <p>{{ $pembelians->where('status','selesai')->count() }} transaksi selesai</p>
                    </div>
                    <span class="pb-quick-arrow" style="color:#34d399;">→</span>
                </a>
            </div>
            @endif

            {{-- DAFTAR PENGAJUAN (masih berjalan) --}}
            @php
                $aktif = $pembelians->whereNotIn('status', ['selesai','selesai_dengan_kompensasi','ditolak','dibatalkan']);
            @endphp
            <div class="pb-card" id="daftar-pembelian">
                <div class="pb-card-head">
                    <div class="pb-card-title">Daftar Pengajuan Pembelian</div>
                    <span class="pb-card-count">{{ $aktif->count() }} pengajuan aktif</span>
                </div>

                @if($pembelians->isEmpty())
                    {{-- Belum pernah ada pengajuan sama sekali --}}
                    <div class="pb-empty">
                        <div class="pb-empty-icon">🖼️</div>
                        <h3>Belum ada pengajuan</h3>
                        <p>Jelajahi katalog museum dan ajukan pembelian koleksi yang Anda minati.</p>
                        <a href="{{ route('gallery') }}" class="pb-empty-cta">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                            Jelajahi Katalog Sekarang
                        </a>
                    </div>
                @elseif($aktif->isEmpty())
                    {{-- Ada pengajuan tapi semuanya sudah selesai/ditolak/dibatalkan --}}
                    <div class="pb-empty">
                        <div class="pb-empty-icon">📋</div>
                        <h3>Tidak ada pengajuan aktif</h3>
                        <p>Semua pengajuan Anda sudah selesai. Lihat riwayat transaksi di bawah.</p>
                    </div>
                @else
                    {{-- Tampilkan yang aktif saja --}}
                    @foreach($aktif as $item)
                        @php
                            $badgeClass = match($item->status) {
                                'menunggu_verifikasi'         => 'st-menunggu-verifikasi',
                                'menunggu_pembayaran'         => 'st-menunggu-pembayaran',
                                'pembayaran_berhasil'         => 'st-pembayaran-berhasil',
                                'siap_diserahkan'             => 'st-siap-diserahkan',
                                'dalam_pengiriman'            => 'st-dalam-pengiriman',
                                'pengecekan_kondisi'          => 'st-dalam-pengiriman',
                                'menunggu_review_kerusakan'   => 'st-menunggu-pembayaran',
                                'menunggu_data_rekening'      => 'st-menunggu-pembayaran',
                                'menunggu_penerimaan_koleksi' => 'st-dalam-pengiriman',
                                'menunggu_refund_kerusakan'   => 'st-menunggu-pembayaran',
                                'menunggu_konfirmasi_refund'  => 'st-pembayaran-berhasil',
                                'menunggu_dokumen_serah_terima'  => 'st-siap-diserahkan',
                                'menunggu_validasi_serah_terima' => 'st-siap-diserahkan',
                                'diterima_pembeli'            => 'st-siap-diserahkan',
                                default                       => 'st-dibatalkan',
                            };
                            $statusLabel = match($item->status) {
                                'menunggu_verifikasi'            => 'Menunggu Verifikasi',
                                'menunggu_pembayaran'            => 'Menunggu Pembayaran',
                                'pembayaran_berhasil'            => 'Pembayaran Berhasil',
                                'siap_diserahkan'                => 'Siap Diserahkan',
                                'dalam_pengiriman'               => 'Dalam Pengiriman',
                                'pengecekan_kondisi'             => 'Pengecekan Kondisi',
                                'menunggu_review_kerusakan'      => 'Menunggu Review Kerusakan',
                                'menunggu_data_rekening'         => 'Menunggu Data Rekening',
                                'menunggu_penerimaan_koleksi'    => 'Menunggu Penerimaan Koleksi',
                                'menunggu_refund_kerusakan'      => 'Menunggu Refund',
                                'menunggu_konfirmasi_refund'     => 'Menunggu Konfirmasi Refund',
                                'menunggu_dokumen_serah_terima'  => 'Menunggu Dok. Serah Terima',
                                'menunggu_validasi_serah_terima' => 'Menunggu Validasi',
                                'diterima_pembeli'               => 'Diterima Pembeli',
                                default                          => ucfirst(str_replace('_', ' ', $item->status)),
                            };
                        @endphp
                        <div class="pb-item">
                            <div class="pb-item-left">
                                <div class="pb-item-thumb">
                                    @if($item->painting->image_url ?? null)
                                        <img src="{{ $item->painting->image_url }}" alt="{{ $item->painting->title }}">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                    @endif
                                </div>
                                <div style="min-width:0;">
                                    <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;margin-bottom:.2rem;">
                                        <span class="pb-item-id">BLI-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="pb-badge {{ $badgeClass }}">
                                            <span class="pb-badge-dot"></span>
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                    <div class="pb-item-title">{{ $item->painting->title }}</div>
                                    <div class="pb-item-artist">{{ $item->painting->artist }} &bull; {{ $item->painting->category }}</div>
                                    <div class="pb-item-meta">
                                        <div class="pb-meta-cell">
                                            <div class="pb-meta-lbl">Diajukan</div>
                                            <div class="pb-meta-val">{{ ($item->submitted_at ?? $item->created_at)->format('d M Y') }}</div>
                                        </div>
                                        <div class="pb-meta-cell">
                                            <div class="pb-meta-lbl">Harga Koleksi</div>
                                            <div class="pb-meta-val">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="pb-meta-cell">
                                            <div class="pb-meta-lbl">Total Bayar</div>
                                            <div class="pb-meta-val" style="color:var(--blue);">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pb-item-right">
                                <div class="pb-item-actions">
                                    <a href="{{ route('pembelian.show', $item) }}" class="pb-btn pb-btn-navy">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Lihat Detail
                                    </a>
                                    @if($item->status === 'menunggu_pembayaran')
                                        <a href="{{ route('pembelian.payment', $item) }}" class="pb-btn pb-btn-pay">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                                            Bayar Sekarang
                                        </a>
                                    @endif
                                    @if($item->status === 'menunggu_verifikasi')
                                        <form action="{{ route('pembelian.cancel', $item) }}" method="POST"
                                            onsubmit="return confirm('Batalkan pengajuan ini?')">
                                            @csrf
                                            <button type="submit" class="pb-btn pb-btn-danger" style="width:100%;">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Batalkan
                                            </button>
                                        </form>
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