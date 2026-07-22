<x-app-layout>
    <x-slot name="header">{{-- kosong, hero di dalam --}}</x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:   #0b1d35;
            --navy2:  #142744;
            --blue:   #1d4ed8;
            --sky:    #38bdf8;
            --cream:  #f2f5f9;
            --slate:  #64748b;
            --border: #e2e8f0;
            --white:  #ffffff;
        }
        *, *::before, *::after { box-sizing: border-box; }

        .td-root {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            padding-bottom: 4rem;
        }

        /* ── HERO ── */
        .td-hero {
            background: linear-gradient(140deg, #0b1d35 0%, #142744 55%, #1c3a68 100%);
            padding: 2.5rem 0 0;
            position: relative;
            overflow: hidden;
        }
        .td-hero::before {
            content: '';
            position: absolute;
            top: -60px; right: -80px;
            width: 420px; height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(56,189,248,.07) 0%, transparent 70%);
            pointer-events: none;
        }
        .td-hero-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 1;
        }
        .td-hero-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .td-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            background: rgba(56,189,248,.1);
            border: 1px solid rgba(56,189,248,.22);
            color: var(--sky);
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            padding: .32rem .9rem;
            border-radius: 99px;
            margin-bottom: .9rem;
        }
        .td-eyebrow-dot { width: 5px; height: 5px; background: var(--sky); border-radius: 50%; }
        .td-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.15;
            margin: 0 0 .5rem;
        }
        .td-hero h1 em { font-style: italic; color: var(--sky); }
        .td-hero-sub {
            color: rgba(255,255,255,.42);
            font-size: .83rem;
            line-height: 1.7;
            max-width: 440px;
            margin: 0;
        }

        /* Live badge */
        .td-live {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(16,185,129,.12);
            border: 1px solid rgba(16,185,129,.25);
            color: #34d399;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: .3rem .75rem;
            border-radius: 99px;
            margin-top: .9rem;
        }
        .td-live-dot {
            width: 6px; height: 6px;
            background: #10b981;
            border-radius: 50%;
            animation: livePulse 1.8s ease-in-out infinite;
        }
        @keyframes livePulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.45;transform:scale(.75)} }

        /* Chips kanan */
        .td-chips { display: flex; gap: .6rem; flex-wrap: wrap; justify-content: flex-end; flex-shrink: 0; }
        .td-chip {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            background: rgba(255,255,255,.055);
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 1.1rem;
            padding: .85rem 1.1rem;
            min-width: 100px;
            transition: background .2s, transform .2s;
        }
        .td-chip:hover { background: rgba(255,255,255,.1); transform: translateY(-2px); }
        .td-chip-val {
            font-family: 'Playfair Display', serif;
            font-size: 1.9rem;
            color: #fff;
            line-height: 1;
        }
        .td-chip-lbl {
            font-size: .66rem;
            font-weight: 600;
            color: rgba(255,255,255,.45);
            letter-spacing: .06em;
            text-align: right;
            margin-top: .2rem;
            line-height: 1.35;
        }
        .td-chip.warn { border-color: rgba(251,191,36,.28); background: rgba(251,191,36,.07); }
        .td-chip.warn .td-chip-val { color: #fbbf24; }
        .td-chip.ok { border-color: rgba(52,211,153,.28); background: rgba(52,211,153,.07); }
        .td-chip.ok .td-chip-val { color: #34d399; }

        /* KPI stat bar */
        .td-stat-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
            border-top: 1px solid rgba(255,255,255,.07);
            position: relative; z-index: 1;
        }
        .td-stat-item {
            padding: 1rem 1.1rem;
            border-right: 1px solid rgba(255,255,255,.06);
            transition: background .18s;
        }
        .td-stat-item:last-child { border-right: none; }
        .td-stat-item:hover { background: rgba(255,255,255,.04); }
        .td-stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: #fff;
            display: block;
            line-height: 1;
            transition: color .3s;
        }
        .td-stat-txt {
            font-size: .66rem;
            font-weight: 600;
            color: rgba(255,255,255,.4);
            letter-spacing: .06em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: .3rem;
            margin-top: .3rem;
        }
        .td-stat-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }

        /* Alert */
        .td-alert {
            background: rgba(245,158,11,.1);
            border: 1px solid rgba(245,158,11,.3);
            border-radius: 1rem;
            padding: .85rem 1.2rem;
            font-size: .82rem;
            color: #fbbf24;
            display: flex;
            align-items: center;
            gap: .6rem;
            margin-bottom: 1.25rem;
        }
        .td-alert a { color: #fde68a; font-weight: 700; margin-left: auto; text-decoration: none; white-space: nowrap; }
        .td-alert a:hover { text-decoration: underline; }

        /* ── CONTENT ── */
        .td-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.75rem 2rem 0;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* ── CARD ── */
        .td-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 1.5rem;
            box-shadow: 0 4px 28px rgba(11,29,53,.05);
            overflow: hidden;
        }
        .td-card-inner { padding: 1.5rem; }
        .td-card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--navy);
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: .2rem;
        }
        .td-card-title::before {
            content: '';
            width: 3px; height: 16px;
            background: linear-gradient(180deg, #1d4ed8, #38bdf8);
            border-radius: 99px;
            flex-shrink: 0;
        }
        .td-card-sub { font-size: .77rem; color: var(--slate); margin-bottom: 1rem; padding-left: .85rem; }

        /* ── FILTER BAR ── */
        .td-fbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .5rem;
            padding: .6rem .9rem;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: .875rem;
            margin-bottom: 1rem;
        }
        .td-fbar label { font-size: .7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .07em; white-space: nowrap; }
        .td-fbar select, .td-fbar input[type=date] {
            font-size: .78rem;
            color: var(--navy);
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: .5rem;
            padding: .28rem .6rem;
            height: 30px;
            outline: none;
            font-family: 'DM Sans', sans-serif;
        }
        .td-fbar select:focus, .td-fbar input[type=date]:focus { border-color: var(--blue); box-shadow: 0 0 0 2px rgba(29,78,216,.12); }
        .td-fbar-sep { width: 1px; height: 16px; background: var(--border); flex-shrink: 0; }
        .td-fbar-apply {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .3rem .85rem; border-radius: .5rem; height: 30px;
            font-size: .75rem; font-weight: 700; border: none; cursor: pointer;
            background: var(--navy); color: #fff;
            font-family: 'DM Sans', sans-serif;
            transition: background .18s;
        }
        .td-fbar-apply:hover { background: var(--blue); }
        .td-fbar-reset {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .3rem .75rem; border-radius: .5rem; height: 30px;
            font-size: .75rem; font-weight: 600; cursor: pointer;
            background: var(--white); color: var(--slate);
            border: 1px solid var(--border);
            font-family: 'DM Sans', sans-serif;
            transition: background .18s;
        }
        .td-fbar-reset:hover { background: #f1f5f9; }
        .td-fbar-badge { font-size: .7rem; color: var(--blue); font-weight: 700; }

        /* ── FIN CARDS ── */
        .td-fin-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
        .td-fin {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 1.25rem;
            padding: 1.25rem 1.4rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(11,29,53,.04);
        }
        .td-fin::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 1.25rem 1.25rem 0 0;
        }
        .td-fin.g::before { background: #22c55e; }
        .td-fin.b::before { background: #3b82f6; }
        .td-fin.v::before { background: #7c3aed; }
        .td-fin-icon {
            position: absolute; right: 1.2rem; top: 1.2rem;
            font-size: 2.5rem; opacity: .07; line-height: 1;
        }
        .td-fin-lbl { font-size: .7rem; font-weight: 700; color: #9ca3af; letter-spacing: .07em; text-transform: uppercase; margin-bottom: .5rem; }
        .td-fin-val { font-family: 'Playfair Display', serif; font-size: 1.45rem; font-weight: 700; color: var(--navy); }
        .td-fin-sub { font-size: .72rem; color: var(--slate); margin-top: .35rem; }

        /* ── 2-COL GRID ── */
        .td-grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: stretch; }
        @media (max-width: 900px) { .td-grid-2 { grid-template-columns: 1fr; } }

        /* Tambah: */
        .td-donut-legend-scroll {
            max-height: 200px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #e2e8f0 transparent;
        }
        .td-donut-legend-scroll::-webkit-scrollbar { width: 4px; }
        .td-donut-legend-scroll::-webkit-scrollbar-track { background: transparent; }
        .td-donut-legend-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }

        /* Export toolbar */
        .td-export-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: .75rem;
            padding: .875rem 1.25rem;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 1rem;
        }
        .td-export-label {
            font-size: .72rem;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .td-export-actions { display: flex; gap: .5rem; flex-wrap: wrap; }
        .td-export-btn {
            display: inline-flex; align-items: center; gap: .35rem;
            padding: .38rem .9rem;
            border-radius: .6rem;
            font-size: .73rem; font-weight: 700;
            border: 1.5px solid;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            text-decoration: none;
        }
        .td-export-btn.pdf {
            background: #fef2f2; color: #dc2626; border-color: #fecaca;
        }
        .td-export-btn.pdf:hover { background: #dc2626; color: #fff; border-color: #dc2626; }
        .td-export-btn.excel {
            background: #f0fdf4; color: #15803d; border-color: #bbf7d0;
        }
        .td-export-btn.excel:hover { background: #15803d; color: #fff; border-color: #15803d; }
        .td-export-btn.csv {
            background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe;
        }
        .td-export-btn.csv:hover { background: #1d4ed8; color: #fff; border-color: #1d4ed8; }
        /* ── CHART CANVAS ── */
        .td-canvas-wrap { position: relative; }

        /* ── DONUT LEGEND ── */
        .td-donut-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 5px 0; font-size: .78rem;
            border-bottom: 1px solid #f5f5f3;
        }
        .td-donut-row:last-child { border-bottom: none; }
        .td-donut-left { display: flex; align-items: center; gap: 7px; color: var(--slate); }
        .td-donut-val { font-weight: 700; color: var(--navy); font-size: .82rem; }

        /* ── POPULAR — Ranked Cards ── */
        .td-rank-list { display: flex; flex-direction: column; gap: .55rem; }
        .td-rank-item {
            display: flex;
            align-items: center;
            gap: .9rem;
            padding: .75rem 1rem;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 1rem;
            transition: background .15s, border-color .15s;
        }
        .td-rank-item:hover { background: #f0f4ff; border-color: #bfdbfe; }
        .td-rank-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #cbd5e1;
            min-width: 22px;
            text-align: center;
            flex-shrink: 0;
        }
        .td-rank-num.top { color: var(--navy); }
        .td-rank-body { flex: 1; min-width: 0; }
        .td-rank-title {
            font-size: .82rem;
            font-weight: 700;
            color: var(--navy);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .td-rank-bar-wrap {
            margin-top: .35rem;
            height: 5px;
            background: #e2e8f0;
            border-radius: 99px;
            overflow: hidden;
        }
        .td-rank-bar {
            height: 100%;
            border-radius: 99px;
            background: linear-gradient(90deg, #1d4ed8, #38bdf8);
            transition: width .6s cubic-bezier(.34,1.56,.64,1);
        }
        .td-rank-count {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--blue);
            flex-shrink: 0;
            min-width: 30px;
            text-align: right;
        }
        .td-rank-lbl { font-size: .64rem; color: var(--slate); text-align: right; }

        /* ── TABLE ── */
        .td-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
        .td-table th {
            padding: .7rem 1rem;
            text-align: left;
            font-size: .67rem;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: .08em;
            text-transform: uppercase;
            background: #f8fafc;
            border-bottom: 1.5px solid var(--border);
            white-space: nowrap;
        }
        .td-table td {
            padding: .85rem 1rem;
            border-bottom: 1px solid #f0f4f8;
            color: var(--navy);
            vertical-align: middle;
        }
        .td-table tr:last-child td { border-bottom: none; }
        .td-table tr:hover td { background: #fafbff; }

        /* ── BADGES ── */
        .td-pill {
            display: inline-flex; align-items: center;
            padding: .2rem .65rem; border-radius: 99px;
            font-size: .67rem; font-weight: 700;
        }
        .td-pill-blue  { background: #dbeafe; color: #1e40af; }
        .td-pill-gray  { background: #f1f5f9; color: #475569; }
        .td-pill-green { background: #dcfce7; color: #14532d; }

        /* ── SUMMARY STRIP ── */
        .td-summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: .875rem; margin-bottom: 1rem; }
        .td-summary-box {
            border-radius: 1rem;
            padding: .9rem 1.1rem;
            border: 1px solid;
        }
        .td-summary-box.s-blue  { background: #eff6ff; border-color: #bfdbfe; }
        .td-summary-box.s-green { background: #f0fdf4; border-color: #bbf7d0; }
        .td-summary-box.s-violet{ background: #fdf4ff; border-color: #e9d5ff; }
        .td-summary-box.s-orange{ background: #fff7ed; border-color: #fed7aa; }
        .td-summary-lbl { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; margin-bottom: .3rem; }
        .td-summary-box.s-blue   .td-summary-lbl { color: #1d4ed8; }
        .td-summary-box.s-green  .td-summary-lbl { color: #15803d; }
        .td-summary-box.s-violet .td-summary-lbl { color: #7e22ce; }
        .td-summary-box.s-orange .td-summary-lbl { color: #c2410c; }
        .td-summary-val { font-family: 'Playfair Display', serif; font-size: 1.35rem; font-weight: 700; line-height: 1.1; }
        .td-summary-box.s-blue   .td-summary-val { color: #1e3a8a; }
        .td-summary-box.s-green  .td-summary-val { color: #14532d; }
        .td-summary-box.s-violet .td-summary-val { color: #581c87; }
        .td-summary-box.s-orange .td-summary-val { color: #7c2d12; }
        .td-summary-sub { font-size: .7rem; margin-top: .2rem; }
        .td-summary-box.s-blue   .td-summary-sub { color: #60a5fa; }
        .td-summary-box.s-green  .td-summary-sub { color: #4ade80; }
        .td-summary-box.s-violet .td-summary-sub { color: #c084fc; }
        .td-summary-box.s-orange .td-summary-sub { color: #fb923c; }

        /* ── ACTION BTN ── */
        .td-btn {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .35rem .8rem;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: .6rem;
            font-size: .72rem; font-weight: 700;
            color: var(--slate);
            text-decoration: none;
            transition: all .15s;
            font-family: 'DM Sans', sans-serif;
        }
        .td-btn:hover { background: var(--navy); color: #fff; border-color: var(--navy); }

        /* ── LEGEND ── */
        .td-legend { display: flex; flex-wrap: wrap; gap: .9rem; margin-bottom: .75rem; }
        .td-legend-item { display: flex; align-items: center; gap: 5px; font-size: .75rem; color: var(--slate); }
        .td-legend-dot { width: 8px; height: 8px; border-radius: 2px; flex-shrink: 0; }

        @media (max-width: 1024px) { .td-chips { display: none; } }
        @media (max-width: 640px) { .td-content { padding: 1.25rem 1rem 0; } }
    </style>

    <div class="td-root">

        {{-- ── HERO ── --}}
        <div class="td-hero">
            <div class="td-hero-inner">
                <div class="td-hero-top">
                    <div>
                        <div class="td-eyebrow">
                            <span class="td-eyebrow-dot"></span>
                            Manajemen Koleksi Museum
                        </div>
                        <h1>Dashboard <em>Transaksi</em></h1>
                        <p class="td-hero-sub">Pantau aktivitas penyewaan dan penjualan koleksi secara real-time dari satu tempat terpusat.</p>
                    </div>

                    <div class="td-chips">
                        <div class="td-chip warn" id="chip-verif">
                            <span class="td-chip-val">{{ number_format($menungguVerifikasi) }}</span>
                            <span class="td-chip-lbl">Perlu Verifikasi</span>
                        </div>
                        <div class="td-chip warn" id="chip-kirim">
                            <span class="td-chip-val">{{ number_format($sedangPengiriman) }}</span>
                            <span class="td-chip-lbl">Dalam Pengiriman</span>
                        </div>
                        <div class="td-chip ok" id="chip-aktif">
                            <span class="td-chip-val">{{ number_format($penyewaanAktif) }}</span>
                            <span class="td-chip-lbl">Sewa Aktif</span>
                        </div>
                        <div class="td-chip" id="chip-total">
                            <span class="td-chip-val">{{ number_format($totalTransactions) }}</span>
                            <span class="td-chip-lbl">Total Transaksi</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI stat bar --}}
            <div class="td-stat-bar">
                @php
                $stats = [
                    ['lbl'=>'Total Transaksi',   'val'=>$totalTransactions,  'dot'=>'#38bdf8', 'id'=>'stat-total'],
                    ['lbl'=>'Sewa Aktif',        'val'=>$penyewaanAktif,     'dot'=>'#10b981', 'id'=>'stat-aktif'],
                    ['lbl'=>'Penjualan Selesai', 'val'=>$penjualanSelesai,   'dot'=>'#22c55e', 'id'=>'stat-jual'],
                    ['lbl'=>'Perlu Verifikasi',  'val'=>$menungguVerifikasi, 'dot'=>'#f59e0b', 'id'=>'stat-verif'],
                    ['lbl'=>'Dalam Pengiriman',  'val'=>$sedangPengiriman,   'dot'=>'#818cf8', 'id'=>'stat-kirim'],
                    ['lbl'=>'Pengembalian',      'val'=>$sedangPengembalian, 'dot'=>'#f97316', 'id'=>'stat-kembali'],
                ];
                @endphp
                @foreach($stats as $s)
                <div class="td-stat-item">
                    <span class="td-stat-num" id="{{ $s['id'] }}">{{ number_format($s['val']) }}</span>
                    <span class="td-stat-txt">
                        <span class="td-stat-dot" style="background:{{ $s['dot'] }};"></span>
                        {{ $s['lbl'] }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="td-content">

        {{-- ── EXPORT BAR ── --}}
            <div class="td-export-bar">
                <span class="td-export-label">📤 Ekspor Data Dashboard</span>
                <div class="td-export-actions">
                    <button class="td-export-btn pdf" onclick="exportPDF()">
                        📄 Export PDF
                    </button>
                    <button class="td-export-btn excel" onclick="exportExcel()">
                        📊 Export Excel
                    </button>
                </div>
            </div>

            {{-- ── SECTION 1: Filter KPI + Financial ── --}}
            <div class="td-card">
                <div class="td-card-inner">
                    <p class="td-card-title">Ringkasan Keuangan</p>
                    <p class="td-card-sub">Total pendapatan, deposit, dan refund berdasarkan filter</p>

                    <div class="td-fbar">
                        <label>Tipe</label>
                        <select id="kf-type">
                            <option value="all">Semua</option>
                            <option value="penyewaan">Penyewaan</option>
                            <option value="pembelian">Pembelian</option>
                        </select>
                        <div class="td-fbar-sep"></div>
                        <label>Dari</label>
                        <input type="date" id="kf-date-from" />
                        <label>Sampai</label>
                        <input type="date" id="kf-date-to" />
                        <div class="td-fbar-sep"></div>
                        <button class="td-fbar-apply" onclick="loadKpiFin()">Terapkan</button>
                        <button class="td-fbar-reset" onclick="resetKpiFin()">Reset</button>
                        <span id="kf-badge" class="td-fbar-badge" style="display:none;">● Aktif</span>
                    </div>

                    <div class="td-fin-grid" id="fin-grid">
                        <div class="td-fin g">
                            <div class="td-fin-icon">💰</div>
                            <p class="td-fin-lbl">Total Pendapatan</p>
                            <p class="td-fin-val" id="fin-pendapatan">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                            <p class="td-fin-sub">Dari seluruh transaksi selesai</p>
                        </div>
                        <div class="td-fin b">
                            <div class="td-fin-icon">🔒</div>
                            <p class="td-fin-lbl">Deposit Ditahan</p>
                            <p class="td-fin-val" id="fin-deposit">Rp {{ number_format($totalDepositDitahan, 0, ',', '.') }}</p>
                            <p class="td-fin-sub">Penyewaan aktif saat ini</p>
                        </div>
                        <div class="td-fin v">
                            <div class="td-fin-icon">↩️</div>
                            <p class="td-fin-lbl">Refund Deposit</p>
                            <p class="td-fin-val" id="fin-refund">Rp {{ number_format($refundDeposit, 0, ',', '.') }}</p>
                            <p class="td-fin-sub">Total dikembalikan ke penyewa</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 2: Revenue + Donut ── --}}
            <div class="td-grid-2">

                {{-- Revenue Chart --}}
                <div class="td-card">
                    <div class="td-card-inner">
                        <p class="td-card-title">Tren Pendapatan Bulanan</p>
                        <p class="td-card-sub">Penyewaan vs penjualan per bulan</p>

                        <div class="td-fbar">
                            <label>Tampilkan</label>
                            <select id="rev-type">
                                <option value="all">Semua</option>
                                <option value="penyewaan">Penyewaan</option>
                                <option value="pembelian">Pembelian</option>
                            </select>
                            <div class="td-fbar-sep"></div>
                            <label>Tahun</label>
                            <select id="rev-year">
                                <option value="rolling">12 Bln Terakhir</option>
                                @for($y = now()->year; $y >= now()->year - 3; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                            <div class="td-fbar-sep"></div>
                            <button class="td-fbar-apply" onclick="loadRevenue()">Update</button>
                            <span id="rev-badge" class="td-fbar-badge" style="display:none;">● Aktif</span>
                        </div>

                        <div class="td-legend" id="rev-legend">
                            <span class="td-legend-item"><span class="td-legend-dot" style="background:#1d4ed8;"></span>Penyewaan</span>
                            <span class="td-legend-item"><span class="td-legend-dot" style="background:#22c55e;"></span>Penjualan</span>
                        </div>
                        <div class="td-canvas-wrap" style="height:260px;">
                            <canvas id="chartRevenue"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Donut --}}
                <div class="td-card">
                    <div class="td-card-inner">
                        <p class="td-card-title">Status Transaksi</p>
                        <p class="td-card-sub">Distribusi berdasarkan status</p>

                        <div class="td-fbar" style="flex-direction:column;align-items:flex-start;gap:.4rem;">
                            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.4rem;">
                                <label>Tipe</label>
                                <select id="st-type" style="flex:1;min-width:90px;">
                                    <option value="all">Semua</option>
                                    <option value="penyewaan">Penyewaan</option>
                                    <option value="pembelian">Pembelian</option>
                                </select>
                            </div>
                            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.4rem;">
                                <label>Dari</label><input type="date" id="st-date-from" style="flex:1;"/>
                                <label>s/d</label><input type="date" id="st-date-to" style="flex:1;"/>
                            </div>
                            <div style="display:flex;gap:.4rem;">
                                <button class="td-fbar-apply" onclick="loadDonut()">Terapkan</button>
                                <button class="td-fbar-reset" onclick="resetDonut()">Reset</button>
                            </div>
                        </div>

                        <div style="position:relative;height:140px;margin:.5rem 0;">
                            <canvas id="chartDonut"></canvas>
                            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;pointer-events:none;">
                                <div style="font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:var(--navy);" id="donut-total">—</div>
                                <div style="font-size:.65rem;color:var(--slate);">total</div>
                            </div>
                        </div>
                        <div class="td-donut-legend-scroll">
                            <div id="donutLegend"></div>
                        </div>                    </div>
                </div>
            </div>

            {{-- ── SECTION 3: Koleksi Terpopuler ── --}}
            <div class="td-card">
                <div class="td-card-inner">
                    <p class="td-card-title">Koleksi Terpopuler</p>
                    <p class="td-card-sub">Top 10 koleksi berdasarkan frekuensi penyewaan</p>

                    <div class="td-fbar">
                        <label>Dari</label>
                        <input type="date" id="pop-date-from" />
                        <label>Sampai</label>
                        <input type="date" id="pop-date-to" />
                        <div class="td-fbar-sep"></div>
                        <button class="td-fbar-apply" onclick="loadPopular()">Update</button>
                        <button class="td-fbar-reset" onclick="resetPopular()">Reset</button>
                        <span id="pop-badge" class="td-fbar-badge" style="display:none;">● Aktif</span>
                    </div>

                    <div id="popularList" class="td-rank-list">
                        @for($i = 0; $i < 5; $i++)
                        <div class="td-rank-item" style="opacity:.35;">
                            <div class="td-rank-num">{{ $i+1 }}</div>
                            <div class="td-rank-body">
                                <div class="td-rank-title" style="background:#e2e8f0;border-radius:4px;height:14px;width:60%;"></div>
                                <div class="td-rank-bar-wrap" style="margin-top:.5rem;">
                                    <div class="td-rank-bar" style="width:{{ 80 - $i*15 }}%;"></div>
                                </div>
                            </div>
                            <div class="td-rank-count" style="color:#cbd5e1;">—</div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ── SECTION 5: Insight Penyewaan ── --}}
            <div class="td-card">
                <div class="td-card-inner">
                    <p class="td-card-title">Insight Penyewaan Koleksi</p>
                    <p class="td-card-sub">Transaksi sewa selesai — diurutkan dari subtotal tertinggi</p>

                    <div class="td-fbar">
                        <label>Dari</label>
                        <input type="date" id="ri-date-from" />
                        <label>Sampai</label>
                        <input type="date" id="ri-date-to" />
                        <div class="td-fbar-sep"></div>
                        <button class="td-fbar-apply" onclick="loadRentalInsight()">Update</button>
                        <button class="td-fbar-reset" onclick="resetRentalInsight()">Reset</button>
                        <span id="ri-badge" class="td-fbar-badge" style="display:none;">● Aktif</span>
                    </div>

                    <div class="td-summary-grid">
                        <div class="td-summary-box s-blue">
                            <p class="td-summary-lbl">Total Selesai</p>
                            <p class="td-summary-val" id="ri-total-count">—</p>
                            <p class="td-summary-sub">transaksi sewa</p>
                        </div>
                        <div class="td-summary-box s-green">
                            <p class="td-summary-lbl">Total Pendapatan Sewa</p>
                            <p class="td-summary-val" id="ri-total-pendapatan">—</p>
                        </div>
                        <div class="td-summary-box s-orange">
                            <p class="td-summary-lbl">Sewa Termahal</p>
                            <p class="td-summary-val" style="font-size:1rem;line-height:1.3;" id="ri-termahal-judul">—</p>
                            <p class="td-summary-sub" id="ri-termahal-subtotal"></p>
                        </div>
                    </div>

                    <div style="overflow-x:auto;">
                        <table class="td-table">
                            <thead>
                                <tr>
                                    <th>Koleksi</th>
                                    <th>Penyewa</th>
                                    <th style="text-align:center;">Durasi</th>
                                    <th style="text-align:right;">Subtotal</th>
                                    <th style="text-align:right;">Deposit</th>
                                    <th style="text-align:right;">Ongkir</th>
                                    <th style="text-align:right;">Total</th>
                                    <th>Periode</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="ri-tbody">
                                <tr><td colspan="9" style="text-align:center;padding:2rem;color:var(--slate);">Memuat data…</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 4: Insight Penjualan ── --}}
            <div class="td-card">
                <div class="td-card-inner">
                    <p class="td-card-title">Insight Penjualan Koleksi</p>
                    <p class="td-card-sub">Koleksi terjual — diurutkan dari harga tertinggi</p>

                    <div class="td-fbar">
                        <label>Selesai Dari</label>
                        <input type="date" id="si-date-from" />
                        <label>Sampai</label>
                        <input type="date" id="si-date-to" />
                        <div class="td-fbar-sep"></div>
                        <button class="td-fbar-apply" onclick="loadSalesInsight()">Update</button>
                        <button class="td-fbar-reset" onclick="resetSalesInsight()">Reset</button>
                        <span id="si-badge" class="td-fbar-badge" style="display:none;">● Aktif</span>
                    </div>

                    <div class="td-summary-grid">
                        <div class="td-summary-box s-green">
                            <p class="td-summary-lbl">Total Terjual</p>
                            <p class="td-summary-val" id="si-total-count">—</p>
                            <p class="td-summary-sub">koleksi</p>
                        </div>
                        <div class="td-summary-box s-blue">
                            <p class="td-summary-lbl">Total Nilai Penjualan</p>
                            <p class="td-summary-val" id="si-total-nilai">—</p>
                            <p class="td-summary-sub">termasuk ongkir</p>
                        </div>
                        <div class="td-summary-box s-violet">
                            <p class="td-summary-lbl">Koleksi Termahal</p>
                            <p class="td-summary-val" style="font-size:1rem;line-height:1.3;" id="si-termahal-judul">—</p>
                            <p class="td-summary-sub" id="si-termahal-harga"></p>
                        </div>
                    </div>

                    <div style="overflow-x:auto;">
                        <table class="td-table">
                            <thead>
                                <tr>
                                    <th>Koleksi</th>
                                    <th>Pembeli</th>
                                    <th style="text-align:right;">Harga Beli</th>
                                    <th style="text-align:right;">Ongkir</th>
                                    <th style="text-align:right;">Total</th>
                                    <th>Tgl Selesai</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="si-tbody">
                                <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--slate);">Memuat data…</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>{{-- /td-content --}}
    </div>{{-- /td-root --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    (function () {
        const ROUTES = {
            kpiFin       : '{{ route("pengelola.transactions.dashboard") }}',
            revenue      : '{{ route("pengelola.transactions.data.revenue") }}',
            status       : '{{ route("pengelola.transactions.data.status") }}',
            popular      : '{{ route("pengelola.transactions.data.popular") }}',
            salesInsight : '{{ route("pengelola.transactions.data.sales-insight") }}',
            rentalInsight: '{{ route("pengelola.transactions.data.rental-insight") }}',
        };

        const fmt   = n => new Intl.NumberFormat('id-ID').format(n);
        const fmtRp = n => 'Rp\u00a0' + fmt(n);

        function buildUrl(base, params) {
            const u = new URL(base, window.location.origin);
            Object.entries(params).forEach(([k, v]) => { if (v) u.searchParams.set(k, v); });
            return u.toString();
        }

        const TOOLTIP = {
            backgroundColor: '#fff', titleColor: '#0b1d35', bodyColor: '#64748b',
            borderColor: '#e2e8f0', borderWidth: 1, padding: 10, cornerRadius: 8,
        };

        const STATUS_META = {
            aktif:                { label: 'Aktif',             color: '#3b82f6' },
            selesai:              { label: 'Selesai',           color: '#22c55e' },
            menunggu_verifikasi:  { label: 'Tunggu Verifikasi', color: '#f59e0b' },
            menunggu_pembayaran:  { label: 'Tunggu Pembayaran', color: '#f97316' },
            pengiriman:           { label: 'Pengiriman',        color: '#818cf8' },
            dikirim:              { label: 'Dikirim',           color: '#818cf8' },
            dalam_pengiriman:     { label: 'Dalam Pengiriman',  color: '#818cf8' },
            pengembalian:         { label: 'Pengembalian',      color: '#f97316' },
            ditolak:              { label: 'Ditolak',           color: '#ef4444' },
            dibatalkan:           { label: 'Dibatalkan',        color: '#ef4444' },
            pembayaran_berhasil:  { label: 'Pembayaran OK',     color: '#34d399' },
        };

        let chartRevenue = null, chartDonut = null;

        // ── KPI + Financial ──
        async function loadKpiFin() {
            const type = document.getElementById('kf-type').value;
            const from = document.getElementById('kf-date-from').value;
            const to   = document.getElementById('kf-date-to').value;
            const isF  = type !== 'all' || from || to;
            document.getElementById('kf-badge').style.display = isF ? 'inline' : 'none';
            document.getElementById('fin-grid').style.opacity = '.5';
            try {
                const d = await fetch(buildUrl(ROUTES.kpiFin, { type, date_from: from, date_to: to, _json: 1 }), {
                    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                }).then(r => r.json());
                document.getElementById('fin-pendapatan').textContent = fmtRp(d.totalPendapatan);
                document.getElementById('fin-deposit').textContent    = fmtRp(d.totalDepositDitahan);
                document.getElementById('fin-refund').textContent     = fmtRp(d.refundDeposit);
                document.getElementById('stat-total').textContent  = fmt(d.totalTransactions);
                document.getElementById('stat-aktif').textContent  = fmt(d.penyewaanAktif);
                document.getElementById('stat-jual').textContent   = fmt(d.penjualanSelesai);
                document.getElementById('stat-verif').textContent  = fmt(d.menungguVerifikasi);
                document.getElementById('stat-kirim').textContent  = fmt(d.sedangPengiriman);
                document.getElementById('stat-kembali').textContent= fmt(d.sedangPengembalian);
            } catch(e) { console.error(e); }
            finally { document.getElementById('fin-grid').style.opacity = '1'; }
        }
        function resetKpiFin() {
            document.getElementById('kf-type').value = 'all';
            document.getElementById('kf-date-from').value = '';
            document.getElementById('kf-date-to').value = '';
            document.getElementById('kf-badge').style.display = 'none';
            loadKpiFin();
        }

        // ── Revenue ──
        async function loadRevenue() {
            const type = document.getElementById('rev-type').value;
            const year = document.getElementById('rev-year').value;
            const isF  = type !== 'all' || year !== 'rolling';
            document.getElementById('rev-badge').style.display = isF ? 'inline' : 'none';
            const leg = document.getElementById('rev-legend');
            leg.innerHTML = '';
            if (type !== 'pembelian') leg.innerHTML += `<span class="td-legend-item"><span class="td-legend-dot" style="background:#1d4ed8;"></span>Penyewaan</span>`;
            if (type !== 'penyewaan') leg.innerHTML += `<span class="td-legend-item"><span class="td-legend-dot" style="background:#22c55e;"></span>Penjualan</span>`;
            try {
                const d = await fetch(buildUrl(ROUTES.revenue, { type, year }), { headers: { Accept: 'application/json' } }).then(r => r.json());
                const datasets = [];
                if (type !== 'pembelian') datasets.push({
                    label: 'Penyewaan', data: d.rental,
                    borderColor: '#1d4ed8', backgroundColor: 'rgba(29,78,216,0.06)',
                    fill: true, tension: 0.42, borderWidth: 2,
                    pointRadius: 3, pointBackgroundColor: '#1d4ed8', pointBorderColor: '#fff', pointBorderWidth: 2, pointHoverRadius: 5,
                });
                if (type !== 'penyewaan') datasets.push({
                    label: 'Penjualan', data: d.sales,
                    borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,0.06)',
                    fill: true, tension: 0.42, borderWidth: 2,
                    pointRadius: 3, pointBackgroundColor: '#22c55e', pointBorderColor: '#fff', pointBorderWidth: 2, pointHoverRadius: 5,
                });
                if (chartRevenue) {
                    chartRevenue.data.labels = d.labels;
                    chartRevenue.data.datasets = datasets;
                    chartRevenue.update();
                } else {
                    chartRevenue = new Chart(document.getElementById('chartRevenue'), {
                        type: 'line',
                        data: { labels: d.labels, datasets },
                        options: {
                            responsive: true, maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: { ...TOOLTIP, mode: 'index', intersect: false, callbacks: { label: ctx => ' ' + ctx.dataset.label + ': ' + fmtRp(ctx.raw) } },
                            },
                            interaction: { mode: 'index', intersect: false },
                            scales: {
                                x: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 11, family: "'DM Sans'" }, color: '#94a3b8', maxRotation: 0 } },
                                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, border: { display: false },
                                    ticks: { font: { size: 11, family: "'DM Sans'" }, color: '#94a3b8', callback: v => v >= 1e6 ? 'Rp '+(v/1e6).toFixed(0)+'jt' : v >= 1e3 ? 'Rp '+(v/1e3).toFixed(0)+'rb' : 'Rp '+v } },
                            },
                        },
                    });
                }
            } catch(e) { console.error(e); }
        }

        // ── Donut ──
        async function loadDonut() {
            const type = document.getElementById('st-type').value;
            const from = document.getElementById('st-date-from').value;
            const to   = document.getElementById('st-date-to').value;
            try {
                const st = await fetch(buildUrl(ROUTES.status, { type, date_from: from, date_to: to }), { headers: { Accept: 'application/json' } }).then(r => r.json());
                const merged = {};
                for (const [k,v] of Object.entries(st.penyewaan||{})) merged[k]=(merged[k]||0)+Number(v);
                for (const [k,v] of Object.entries(st.pembelian||{}))  merged[k]=(merged[k]||0)+Number(v);
                const entries = Object.entries(merged).sort((a,b)=>b[1]-a[1]);
                const labels  = entries.map(([k])=>STATUS_META[k]?.label??k.replace(/_/g,' '));
                const values  = entries.map(([,v])=>v);
                const colors  = entries.map(([k])=>STATUS_META[k]?.color??'#94a3b8');
                const total   = values.reduce((a,b)=>a+b,0);
                document.getElementById('donut-total').textContent = fmt(total);
                if (chartDonut) {
                    chartDonut.data.labels = labels;
                    chartDonut.data.datasets[0].data = values;
                    chartDonut.data.datasets[0].backgroundColor = colors;
                    chartDonut.update();
                } else {
                    chartDonut = new Chart(document.getElementById('chartDonut'), {
                        type: 'doughnut',
                        data: { labels, datasets: [{ data: values, backgroundColor: colors, borderWidth: 0, hoverOffset: 4 }] },
                        options: {
                            responsive: true, maintainAspectRatio: false, cutout: '72%',
                            plugins: {
                                legend: { display: false },
                                tooltip: { ...TOOLTIP, callbacks: { label: ctx => ' '+ctx.label+': '+fmt(ctx.raw)+' ('+((ctx.raw/total)*100).toFixed(1)+'%)' } },
                            },
                        },
                    });
                }
                const leg = document.getElementById('donutLegend');
                leg.innerHTML = '';
                entries.forEach(([k,v]) => {
                    const pct   = total > 0 ? ((v/total)*100).toFixed(0) : 0;
                    const label = STATUS_META[k]?.label ?? k.replace(/_/g,' ');
                    const color = STATUS_META[k]?.color ?? '#94a3b8';
                    leg.innerHTML += `<div class="td-donut-row">
                        <span class="td-donut-left">
                            <span style="width:8px;height:8px;border-radius:2px;background:${color};display:inline-block;flex-shrink:0;"></span>
                            ${label}
                        </span>
                        <span style="display:flex;align-items:center;gap:8px;">
                            <span style="font-size:.7rem;color:var(--slate);">${pct}%</span>
                            <span class="td-donut-val">${fmt(v)}</span>
                        </span>
                    </div>`;
                });
            } catch(e) { console.error(e); }
        }
        function resetDonut() {
            document.getElementById('st-type').value = 'all';
            document.getElementById('st-date-from').value = '';
            document.getElementById('st-date-to').value = '';
            loadDonut();
        }

        // ── Popular — Ranked Cards ──
        async function loadPopular() {
            const from = document.getElementById('pop-date-from').value;
            const to   = document.getElementById('pop-date-to').value;
            const isF  = from || to;
            document.getElementById('pop-badge').style.display = isF ? 'inline' : 'none';
            try {
                const pop  = await fetch(buildUrl(ROUTES.popular, { date_from: from, date_to: to }), { headers: { Accept: 'application/json' } }).then(r => r.json());

                // Hanya tampilkan koleksi yang memang punya transaksi sewa (rent_count > 0)
                const filtered = pop.filter(p => (p.rent_count || 0) > 0);
                const top  = filtered.slice(0, 10);
                const max  = Math.max(...top.map(p => p.rent_count || 0), 1);
                const list = document.getElementById('popularList');
                if (!top.length) {
                    list.innerHTML = `<p style="text-align:center;color:var(--slate);padding:2rem 0;font-size:.82rem;">Belum ada koleksi yang disewa pada rentang tanggal ini.</p>`;
                    return;
                }
                list.innerHTML = top.map((p, i) => {
                    const cnt  = p.rent_count || 0;
                    const pct  = Math.round((cnt / max) * 100);
                    const isTop = i < 3;
                    return `<div class="td-rank-item">
                        <div class="td-rank-num ${isTop ? 'top' : ''}">${i+1}</div>
                        <div class="td-rank-body">
                            <div class="td-rank-title">${p.title ?? '#'+p.id}</div>
                            <div class="td-rank-bar-wrap">
                                <div class="td-rank-bar" style="width:0%;" data-pct="${pct}"></div>
                            </div>
                        </div>
                        <div>
                            <div class="td-rank-count">${fmt(cnt)}</div>
                            <div class="td-rank-lbl">kali sewa</div>
                        </div>
                    </div>`;
                }).join('');
                requestAnimationFrame(() => {
                    list.querySelectorAll('.td-rank-bar').forEach(bar => {
                        bar.style.width = bar.dataset.pct + '%';
                    });
                });
            } catch(e) { console.error(e); }
        }
        function resetPopular() {
            document.getElementById('pop-date-from').value = '';
            document.getElementById('pop-date-to').value = '';
            document.getElementById('pop-badge').style.display = 'none';
            loadPopular();
        }

        // ── Sales Insight ──
        async function loadSalesInsight() {
            const from = document.getElementById('si-date-from').value;
            const to   = document.getElementById('si-date-to').value;
            document.getElementById('si-badge').style.display = (from||to) ? 'inline' : 'none';
            const tbody = document.getElementById('si-tbody');
            tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--slate);">Memuat data…</td></tr>`;
            try {
                const data = await fetch(buildUrl(ROUTES.salesInsight, { date_from: from, date_to: to }), { headers: { Accept: 'application/json' } }).then(r => r.json());
                const s = data.summary;
                document.getElementById('si-total-count').textContent   = fmt(s.total_terjual);
                document.getElementById('si-total-nilai').textContent   = fmtRp(s.total_nilai);
                document.getElementById('si-termahal-judul').textContent = s.termahal_judul;
                document.getElementById('si-termahal-harga').textContent = s.termahal_harga ? fmtRp(s.termahal_harga) : '';
                if (!data.rows.length) {
                    tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--slate);">Belum ada koleksi terjual.</td></tr>`;
                    return;
                }
                const bBadge = t => t === 'b2b'
                    ? `<span class="td-pill td-pill-blue">B2B</span>`
                    : `<span class="td-pill td-pill-gray">B2C</span>`;
                tbody.innerHTML = data.rows.map(r => `<tr>
                    <td style="font-weight:700;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${r.judul}</td>
                    <td><div style="display:flex;align-items:center;gap:5px;">${bBadge(r.buyer_type)}<span style="font-size:.78rem;">${r.pembeli}</span></div></td>
                    <td style="text-align:right;font-weight:700;color:#15803d;">${fmtRp(r.harga_beli)}</td>
                    <td style="text-align:right;color:var(--slate);">${fmtRp(r.shipping_cost)}</td>
                    <td style="text-align:right;font-weight:700;color:var(--navy);">${fmtRp(r.total_bayar)}</td>
                    <td style="color:var(--slate);font-size:.75rem;white-space:nowrap;">${r.completed_at??'—'}</td>
                    <td><a href="${r.url}" class="td-btn">Lihat →</a></td>
                </tr>`).join('');
            } catch(e) { console.error(e); tbody.innerHTML = `<tr><td colspan="7" style="text-align:center;padding:2rem;color:#ef4444;">Gagal memuat data.</td></tr>`; }
        }
        function resetSalesInsight() {
            document.getElementById('si-date-from').value = '';
            document.getElementById('si-date-to').value = '';
            document.getElementById('si-badge').style.display = 'none';
            loadSalesInsight();
        }

        // ── Rental Insight ──
        async function loadRentalInsight() {
            const from = document.getElementById('ri-date-from').value;
            const to   = document.getElementById('ri-date-to').value;
            document.getElementById('ri-badge').style.display = (from||to) ? 'inline' : 'none';
            const tbody = document.getElementById('ri-tbody');
            tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;padding:2rem;color:var(--slate);">Memuat data…</td></tr>`;
            try {
                const data = await fetch(buildUrl(ROUTES.rentalInsight, { date_from: from, date_to: to }), { headers: { Accept: 'application/json' } }).then(r => r.json());
                const s = data.summary;
                document.getElementById('ri-total-count').textContent      = fmt(s.total_selesai);
                document.getElementById('ri-total-pendapatan').textContent = fmtRp(s.total_pendapatan);
                document.getElementById('ri-termahal-judul').textContent   = s.termahal_judul;
                document.getElementById('ri-termahal-subtotal').textContent = s.termahal_subtotal ? fmtRp(s.termahal_subtotal) : '';
                if (!data.rows.length) {
                    tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;padding:2rem;color:var(--slate);">Belum ada transaksi sewa selesai.</td></tr>`;
                    return;
                }
                const rBadge = t => t === 'instansi'
                    ? `<span class="td-pill td-pill-blue">Instansi</span>`
                    : `<span class="td-pill td-pill-gray">Perseorangan</span>`;
                tbody.innerHTML = data.rows.map(r => {
                    let start = r.start_date, end = r.end_date, duration = r.duration_days;

                    // Pengaman: jika tanggal terbalik (start > end), tukar posisi & hitung ulang durasi
                    if (start && end) {
                        const startDate = new Date(start);
                        const endDate   = new Date(end);
                        if (startDate > endDate) {
                            [start, end] = [end, start];
                        }
                        const diffDays = Math.round((new Date(end) - new Date(start)) / (1000 * 60 * 60 * 24)) + 1;
                        if (!duration || duration <= 0) duration = diffDays > 0 ? diffDays : 1;
                    }

                    const periode = (start && end) ? `${start} – ${end}` : '—';
                    return `<tr>
                        <td style="font-weight:700;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${r.judul}</td>
                        <td><div style="display:flex;align-items:center;gap:5px;">${rBadge(r.rental_type)}<span style="font-size:.78rem;">${r.penyewa}</span></div></td>
                        <td style="text-align:center;color:var(--slate);">${duration} hari</td>
                        <td style="text-align:right;font-weight:700;color:#1d4ed8;">${fmtRp(r.subtotal)}</td>
                        <td style="text-align:right;color:var(--slate);">${fmtRp(r.deposit)}</td>
                        <td style="text-align:right;color:var(--slate);">${fmtRp(r.shipping_cost)}</td>
                        <td style="text-align:right;font-weight:700;color:var(--navy);">${fmtRp(r.total_bayar)}</td>
                        <td style="color:var(--slate);font-size:.72rem;white-space:nowrap;">${periode}</td>
                        <td><a href="${r.url}" class="td-btn">Lihat →</a></td>
                    </tr>`;
                }).join('');
            } catch(e) { console.error(e); tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;padding:2rem;color:#ef4444;">Gagal memuat data.</td></tr>`; }
        }
        function resetRentalInsight() {
            document.getElementById('ri-date-from').value = '';
            document.getElementById('ri-date-to').value = '';
            document.getElementById('ri-badge').style.display = 'none';
            loadRentalInsight();
        }

        // ── EXPORT ──
        async function exportPDF() {
            const btn = event.currentTarget;
            const orig = btn.innerHTML;
            btn.innerHTML = '⏳ Menyiapkan PDF...';
            btn.disabled = true;
            try {
                const params = new URLSearchParams({
                    date_from : document.getElementById('kf-date-from')?.value ?? '',
                    date_to   : document.getElementById('kf-date-to')?.value   ?? '',
                    type      : document.getElementById('kf-type')?.value       ?? 'all',
                });
                window.open('{{ route("pengelola.transactions.export.pdf") }}?' + params.toString(), '_blank');
            } catch(e) {
                console.error(e);
                alert('Gagal membuka PDF. Coba lagi.');
            } finally {
                setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; }, 1500);
            }
        }
        
        async function exportExcel() {
            const btn = event.currentTarget;
            const orig = btn.innerHTML;
            btn.innerHTML = '⏳ Menyiapkan Excel...';
            btn.disabled = true;
            try {
                const params = new URLSearchParams({
                    date_from : document.getElementById('kf-date-from')?.value ?? '',
                    date_to   : document.getElementById('kf-date-to')?.value   ?? '',
                    type      : document.getElementById('kf-type')?.value       ?? 'all',
                });
                window.location.href = '{{ route("pengelola.transactions.export.excel") }}?' + params.toString();
            } catch(e) {
                console.error(e);
            } finally {
                setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; }, 3000);
            }
        }
        
        async function exportCSV(section = 'all') {
            const btn = event.currentTarget;
            const orig = btn.innerHTML;
            btn.innerHTML = '⏳ Menyiapkan CSV...';
            btn.disabled = true;
            try {
                const params = new URLSearchParams({
                    section   : section,
                    date_from : document.getElementById('kf-date-from')?.value ?? '',
                    date_to   : document.getElementById('kf-date-to')?.value   ?? '',
                    type      : document.getElementById('kf-type')?.value       ?? 'all',
                });
                window.location.href = '{{ route("pengelola.transactions.export.csv") }}?' + params.toString();
            } catch(e) {
                console.error(e);
            } finally {
                setTimeout(() => { btn.innerHTML = orig; btn.disabled = false; }, 3000);
            }
        }
        
        window.exportPDF   = exportPDF;
        window.exportExcel = exportExcel;
        window.exportCSV   = exportCSV;

        window.loadKpiFin = loadKpiFin; window.resetKpiFin = resetKpiFin;
        window.loadRevenue = loadRevenue;
        window.loadDonut = loadDonut; window.resetDonut = resetDonut;
        window.loadPopular = loadPopular; window.resetPopular = resetPopular;
        window.loadSalesInsight = loadSalesInsight; window.resetSalesInsight = resetSalesInsight;
        window.loadRentalInsight = loadRentalInsight; window.resetRentalInsight = resetRentalInsight;

        loadRevenue();
        loadDonut();
        loadPopular();
        loadSalesInsight();
        loadRentalInsight();
    })();
    </script>

</x-app-layout>