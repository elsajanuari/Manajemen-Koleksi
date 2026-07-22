<x-app-layout>
    <x-slot name="header">{{-- kosong, hero di dalam --}}</x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:    #0b1d35;
            --navy-2:  #142744;
            --blue:    #1d4ed8;
            --sky:     #38bdf8;
            --cream:   #f2f5f9;
            --slate:   #64748b;
            --border:  #e2e8f0;
            --white:   #ffffff;
            --success: #10b981;
            --warn:    #f59e0b;
            --danger:  #ef4444;
        }

        * { box-sizing: border-box; }

        .mu-root {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            padding-bottom: 4rem;
        }

        /* ══════════════════════════ LIVE INDICATOR ══════════════════════════ */
        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(16,185,129,.12);
            border: 1px solid rgba(16,185,129,.25);
            color: #059669;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            padding: .3rem .75rem;
            border-radius: 99px;
        }
        .live-dot {
            width: 6px; height: 6px;
            background: #10b981;
            border-radius: 50%;
            animation: livePulse 1.8s ease-in-out infinite;
        }
        @keyframes livePulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .45; transform: scale(.75); }
        }

        /* ══════════════════════════ HERO ══════════════════════════ */
        .mu-hero {
            background: linear-gradient(140deg, #0b1d35 0%, #142744 55%, #1c3a68 100%);
            padding: 2.5rem 0 0;
            position: relative;
            overflow: hidden;
        }
        .mu-hero::before {
            content: '';
            position: absolute;
            top: -60px; right: -80px;
            width: 420px; height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(56,189,248,.07) 0%, transparent 70%);
            pointer-events: none;
        }
        .mu-hero::after {
            content: '';
            position: absolute;
            bottom: 40px; left: -60px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(29,78,216,.08) 0%, transparent 65%);
            pointer-events: none;
        }

        .mu-hero-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            position: relative;
            z-index: 1;
        }

        .mu-hero-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .mu-eyebrow {
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
        .mu-eyebrow-dot { width: 5px; height: 5px; background: var(--sky); border-radius: 50%; }

        .mu-hero h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.15;
            margin: 0 0 .5rem;
        }
        .mu-hero h1 em {
            font-style: italic;
            color: var(--sky);
        }

        .mu-hero-sub {
            color: rgba(255,255,255,.42);
            font-size: .83rem;
            line-height: 1.7;
            max-width: 440px;
            margin: 0;
        }

        /* Chips kanan hero */
        .mu-chips {
            display: flex;
            gap: .6rem;
            flex-wrap: wrap;
            justify-content: flex-end;
            align-items: flex-start;
            flex-shrink: 0;
        }
        .mu-chip {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            background: rgba(255,255,255,.055);
            border: 1px solid rgba(255,255,255,.09);
            border-radius: 1.1rem;
            padding: .85rem 1.1rem;
            min-width: 96px;
            cursor: default;
            transition: background .2s, transform .2s;
        }
        .mu-chip:hover { background: rgba(255,255,255,.1); transform: translateY(-2px); }
        .mu-chip-val {
            font-family: 'Playfair Display', serif;
            font-size: 1.9rem;
            color: #fff;
            line-height: 1;
            transition: color .3s;
        }
        .mu-chip-lbl {
            font-size: .66rem;
            font-weight: 600;
            color: rgba(255,255,255,.45);
            letter-spacing: .06em;
            text-align: right;
            margin-top: .2rem;
            max-width: 88px;
            line-height: 1.35;
        }
        .mu-chip.urgent { border-color: rgba(251,191,36,.28); background: rgba(251,191,36,.07); }
        .mu-chip.urgent .mu-chip-val { color: #fbbf24; }
        .mu-chip.success { border-color: rgba(52,211,153,.28); background: rgba(52,211,153,.07); }
        .mu-chip.success .mu-chip-val { color: #34d399; }

        /* Stat bar */
        .mu-stat-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(125px, 1fr));
            border-top: 1px solid rgba(255,255,255,.07);
            position: relative;
            z-index: 1;
        }
        .mu-stat-item {
            padding: 1rem 1.25rem;
            border-right: 1px solid rgba(255,255,255,.06);
            cursor: pointer;
            transition: background .18s;
            text-decoration: none;
            position: relative;
        }
        .mu-stat-item:last-child { border-right: none; }
        .mu-stat-item:hover { background: rgba(255,255,255,.04); }
        .mu-stat-item.active {
            background: rgba(29,78,216,.22);
        }
        .mu-stat-item.active::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2.5px;
            background: linear-gradient(90deg, var(--sky), var(--blue));
            border-radius: 99px 99px 0 0;
        }
        .mu-stat-num {
            font-family: 'Playfair Display', serif;
            font-size: 1.45rem;
            color: #fff;
            display: block;
            line-height: 1;
            transition: color .3s;
        }
        .mu-stat-txt {
            font-size: .67rem;
            font-weight: 600;
            color: rgba(255,255,255,.4);
            letter-spacing: .06em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: .3rem;
            margin-top: .3rem;
        }
        .mu-stat-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ══════════════════════════ CONTENT ══════════════════════════ */
        .mu-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.75rem 2rem 0;
        }

        /* ══════════════════════════ FLASH ══════════════════════════ */
        .mu-flash {
            border-radius: .875rem;
            padding: .85rem 1.2rem;
            font-size: .83rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .55rem;
            margin-bottom: 1.25rem;
            animation: flashIn .35s ease;
        }
        @keyframes flashIn { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: none; } }
        .mu-flash svg { width: 16px; height: 16px; flex-shrink: 0; }
        .mu-flash.ok  { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .mu-flash.err { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

        /* ══════════════════════════ TOOLBAR ══════════════════════════ */
        .mu-toolbar {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 1.25rem;
            padding: .875rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
            box-shadow: 0 2px 16px rgba(11,29,53,.045);
        }

        .mu-search {
            position: relative;
            flex: 1;
            min-width: 200px;
        }
        .mu-search-icon {
            position: absolute;
            left: .85rem;
            top: 50%;
            transform: translateY(-50%);
            width: 15px; height: 15px;
            color: #94a3b8;
            pointer-events: none;
        }
        .mu-search input {
            width: 100%;
            border: 1.5px solid var(--border);
            border-radius: .875rem;
            padding: .62rem 1rem .62rem 2.45rem;
            font-size: .82rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--navy);
            background: #f8fafc;
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
        }
        .mu-search input:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(29,78,216,.09);
            background: var(--white);
        }
        .mu-search input::placeholder { color: #b0bac6; }

        .mu-select {
            border: 1.5px solid var(--border);
            border-radius: .875rem;
            padding: .62rem 2.1rem .62rem .875rem;
            font-size: .81rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--navy);
            font-weight: 500;
            background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2394a3b8'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z' clip-rule='evenodd'/%3E%3C/svg%3E") no-repeat right .65rem center / 14px;
            appearance: none;
            outline: none;
            cursor: pointer;
            min-width: 145px;
            transition: border-color .2s;
        }
        .mu-select:focus { border-color: var(--blue); box-shadow: 0 0 0 3px rgba(29,78,216,.09); }

        .mu-divider { width: 1px; height: 24px; background: var(--border); flex-shrink: 0; }

        .mu-btn {
            display: inline-flex;
            align-items: center;
            gap: .38rem;
            border: none;
            border-radius: .875rem;
            padding: .62rem 1.05rem;
            font-size: .79rem;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background .18s, transform .15s, box-shadow .18s;
        }
        .mu-btn svg { width: 13px; height: 13px; }
        .mu-btn-primary { background: var(--navy); color: #fff; }
        .mu-btn-primary:hover { background: var(--blue); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(29,78,216,.25); }
        .mu-btn-ghost { background: transparent; color: var(--slate); text-decoration: none; }
        .mu-btn-ghost:hover { color: var(--navy); background: #f1f5f9; }

        /* ══════════════════════════ TABLE CARD ══════════════════════════ */
        .mu-card {
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 1.5rem;
            box-shadow: 0 4px 28px rgba(11,29,53,.055);
            overflow: hidden;
        }

        .mu-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-bottom: 1.5px solid #f0f4f8;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .mu-card-title {
            font-size: .76rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--navy);
            display: flex;
            align-items: center;
            gap: .55rem;
        }
        .mu-card-title::before {
            content: '';
            width: 3px; height: 15px;
            background: linear-gradient(180deg, #1d4ed8, #38bdf8);
            border-radius: 99px;
        }

        .mu-per-page {
            display: flex;
            align-items: center;
            gap: .45rem;
            font-size: .74rem;
            color: var(--slate);
        }
        .mu-per-page select {
            border: 1.5px solid var(--border);
            border-radius: .5rem;
            padding: .28rem .5rem;
            font-size: .74rem;
            font-family: 'DM Sans', sans-serif;
            color: var(--navy);
            background: #f8fafc;
            outline: none;
        }

        /* ══════════════════════════ TABLE ══════════════════════════ */
        .mu-table { width: 100%; border-collapse: collapse; }

        .mu-table thead tr {
            background: #f8fafc;
            border-bottom: 1.5px solid var(--border);
        }
        .mu-table thead th {
            padding: .78rem 1rem;
            text-align: left;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #94a3b8;
            white-space: nowrap;
        }
        .mu-table thead th:first-child { padding-left: 1.5rem; }
        .mu-table thead th:last-child  { padding-right: 1.5rem; text-align: right; }

        .mu-table tbody tr {
            border-bottom: 1px solid #f0f4f8;
            transition: background .12s;
        }
        .mu-table tbody tr:last-child { border-bottom: none; }
        .mu-table tbody tr:hover { background: #fafbff; }

        /* Row entry animation */
        .mu-table tbody tr {
            animation: rowIn .3s ease both;
        }
        @keyframes rowIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: none; }
        }

        .mu-table tbody td {
            padding: .9rem 1rem;
            font-size: .81rem;
            color: var(--navy);
            vertical-align: middle;
        }
        .mu-table tbody td:first-child { padding-left: 1.5rem; }
        .mu-table tbody td:last-child  { padding-right: 1.5rem; text-align: right; }

        .mu-id {
            font-family: 'Playfair Display', serif;
            font-size: .92rem;
            color: var(--blue);
            font-weight: 600;
        }
        .mu-date { font-size: .73rem; color: var(--slate); margin-top: .1rem; }

        .mu-name { font-weight: 600; }
        .mu-type-pill {
            display: inline-flex;
            align-items: center;
            gap: .22rem;
            background: #f1f5f9;
            color: var(--slate);
            font-size: .66rem;
            font-weight: 600;
            padding: .12rem .5rem;
            border-radius: 99px;
            margin-top: .18rem;
        }
        .mu-type-pill svg { width: 8px; height: 8px; }
        .mu-type-pill.inst { background: #eff6ff; color: var(--blue); }

        .mu-koleksi { display: flex; align-items: center; gap: .6rem; }
        .mu-thumb {
            width: 40px; height: 40px;
            border-radius: .6rem;
            object-fit: cover;
            background: #f1f5f9;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .mu-thumb svg { width: 16px; height: 16px; color: #b0bac6; }
        .mu-kname { font-weight: 600; line-height: 1.3; font-size: .81rem; }
        .mu-kartist { font-size: .72rem; color: var(--slate); }

        .mu-period { font-size: .79rem; font-weight: 500; }
        .mu-dur {
            display: inline-block;
            background: #f0fdf4;
            color: #15803d;
            font-size: .66rem;
            font-weight: 600;
            padding: .08rem .45rem;
            border-radius: 99px;
            margin-top: .18rem;
        }

        /* ══════════════════════════ STATUS BADGES ══════════════════════════ */
        .mu-badge {
            display: inline-flex;
            align-items: center;
            gap: .28rem;
            padding: .26rem .75rem;
            border-radius: 99px;
            font-size: .68rem;
            font-weight: 700;
            white-space: nowrap;
            letter-spacing: .02em;
            transition: all .35s ease;
        }
        .mu-badge-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* — per-status — */
        .st-draft                         { background:#f1f5f9; color:#475569; }
        .st-draft .mu-badge-dot           { background:#94a3b8; }

        .st-menunggu-verifikasi                         { background:#fef3c7; color:#92400e; }
        .st-menunggu-verifikasi .mu-badge-dot           { background:#f59e0b; box-shadow:0 0 0 2px rgba(245,158,11,.18); }

        .st-menunggu-perjanjian                         { background:#dbeafe; color:#1e40af; }
        .st-menunggu-perjanjian .mu-badge-dot           { background:#3b82f6; }

        .st-verifikasi-perjanjian                       { background:#ede9fe; color:#4c1d95; }
        .st-verifikasi-perjanjian .mu-badge-dot         { background:#8b5cf6; }

        .st-menunggu-bayar                              { background:#ffedd5; color:#9a3412; }
        .st-menunggu-bayar .mu-badge-dot                { background:#f97316; box-shadow:0 0 0 2px rgba(249,115,22,.18); }

        .st-pengiriman                                  { background:#e0f2fe; color:#075985; }
        .st-pengiriman .mu-badge-dot                    { background:#0284c7; }

        .st-menunggu-st                                 { background:#e0e7ff; color:#3730a3; }
        .st-menunggu-st .mu-badge-dot                   { background:#6366f1; }

        .st-verifikasi-st                               { background:#f3e8ff; color:#6b21a8; }
        .st-verifikasi-st .mu-badge-dot                 { background:#a855f7; }

        .st-aktif                                       { background:#d1fae5; color:#065f46; }
        .st-aktif .mu-badge-dot                         { background:#10b981; box-shadow:0 0 0 2px rgba(16,185,129,.18); }

        .st-pengembalian                                { background:#ccfbf1; color:#134e4a; }
        .st-pengembalian .mu-badge-dot                  { background:#14b8a6; }

        .st-selesai                                     { background:#dcfce7; color:#14532d; }
        .st-selesai .mu-badge-dot                       { background:#22c55e; }

        .st-ditolak                                     { background:#fee2e2; color:#991b1b; }
        .st-ditolak .mu-badge-dot                       { background:#ef4444; }

        .st-dibatalkan                                  { background:#f1f5f9; color:#475569; }
        .st-dibatalkan .mu-badge-dot                    { background:#94a3b8; }

        /* Badge update flash */
        .badge-updated {
            animation: badgeFlash .6s ease;
        }
        @keyframes badgeFlash {
            0%   { transform: scale(1); }
            30%  { transform: scale(1.12); box-shadow: 0 0 0 4px rgba(56,189,248,.25); }
            100% { transform: scale(1); box-shadow: none; }
        }

        /* ══════════════════════════ ACTION ══════════════════════════ */
        .mu-btn-detail {
            display: inline-flex;
            align-items: center;
            gap: .38rem;
            padding: .45rem .9rem;
            background: var(--navy);
            color: #fff;
            border-radius: .6rem;
            font-size: .74rem;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: background .18s, transform .15s, box-shadow .18s;
        }
        .mu-btn-detail:hover {
            background: var(--blue);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(29,78,216,.28);
        }
        .mu-btn-detail svg { width: 11px; height: 11px; }

        /* ══════════════════════════ EMPTY ══════════════════════════ */
        .mu-empty {
            padding: 4.5rem 2rem;
            text-align: center;
        }
        .mu-empty-icon {
            width: 62px; height: 62px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem;
        }
        .mu-empty-icon svg { width: 25px; height: 25px; color: #94a3b8; }
        .mu-empty h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            color: var(--navy);
            margin: 0 0 .35rem;
        }
        .mu-empty p { font-size: .82rem; color: var(--slate); }

        /* ══════════════════════════ PAGINATION ══════════════════════════ */
        .mu-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-top: 1.5px solid #f0f4f8;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .mu-page-info { font-size: .74rem; color: var(--slate); }
        .mu-page-info strong { color: var(--navy); }
        .mu-page-btns { display: flex; gap: .3rem; }
        .mu-page-btn {
            width: 31px; height: 31px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: .5rem;
            border: 1.5px solid var(--border);
            background: var(--white);
            color: var(--slate);
            font-size: .76rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .15s;
        }
        .mu-page-btn:hover { border-color: var(--blue); color: var(--blue); }
        .mu-page-btn.active { background: var(--navy); border-color: var(--navy); color: #fff; }
        .mu-page-btn.disabled { opacity: .33; cursor: not-allowed; pointer-events: none; }
        .mu-page-btn svg { width: 12px; height: 12px; }

        /* ══════════════════════════ UPDATE TOAST ══════════════════════════ */
        .mu-toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background: var(--navy);
            color: #fff;
            border-radius: .875rem;
            padding: .75rem 1.1rem;
            font-size: .78rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: .5rem;
            box-shadow: 0 8px 28px rgba(11,29,53,.3);
            z-index: 9999;
            transform: translateY(80px);
            opacity: 0;
            transition: transform .35s cubic-bezier(.34,1.56,.64,1), opacity .3s ease;
            pointer-events: none;
        }
        .mu-toast.show { transform: translateY(0); opacity: 1; }
        .mu-toast svg { width: 14px; height: 14px; color: var(--sky); }

        /* ══════════════════════════ RESPONSIVE ══════════════════════════ */
        @media (max-width: 1024px) {
            .mu-chips { display: none; }
        }
        @media (max-width: 860px) {
            .mu-hero h1 { font-size: 1.75rem; }
            .mu-table thead th:nth-child(4),
            .mu-table tbody td:nth-child(4) { display: none; }
        }
        @media (max-width: 640px) {
            .mu-content { padding: 1.25rem 1rem 0; }
            .mu-table thead th:nth-child(3),
            .mu-table tbody td:nth-child(3) { display: none; }
        }
    </style>

    <div class="mu-root">

        {{-- ══════════════════════════ HERO ══════════════════════════ --}}
        <div class="mu-hero">
            <div class="mu-hero-inner">
                <div class="mu-hero-top">
                    <div>
                        <div class="mu-eyebrow">
                            <span class="mu-eyebrow-dot"></span>
                            Manajemen Penyewaan Koleksi
                        </div>
                        <h1>Dashboard <em>Pengajuan</em><br>Penyewaan</h1>
                        <p class="mu-hero-sub">Tinjau, verifikasi, dan pantau seluruh pengajuan penyewaan koleksi lukisan museum dari satu tempat.</p>
                        <div style="margin-top:.9rem; display:flex; align-items:center; gap:.75rem; flex-wrap:wrap;">
                            <a href="{{ route('pengelola.penyewaan.riwayat') }}"
                            style="display:inline-flex;align-items:center;gap:.4rem;background:rgba(52,211,153,.12);border:1px solid rgba(52,211,153,.25);color:#34d399;font-size:.72rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:.3rem .85rem;border-radius:99px;text-decoration:none;transition:background .2s;"
                            onmouseover="this.style.background='rgba(52,211,153,.22)'"
                            onmouseout="this.style.background='rgba(52,211,153,.12)'">
                                🧾 Riwayat Transaksi
                            </a>
                        </div>
                    </div>
                    <div class="mu-chips">
                        <div class="mu-chip urgent" id="chip-verifikasi">
                            <span class="mu-chip-val" data-stat="menunggu_verifikasi">{{ $totalMenungguVerifikasi ?? 0 }}</span>
                            <span class="mu-chip-lbl">Perlu Diverifikasi</span>
                        </div>
                        <div class="mu-chip urgent">
                            <span class="mu-chip-val" data-stat="menunggu_perjanjian">{{ $totalMenungguPerjanjian ?? 0 }}</span>
                            <span class="mu-chip-lbl">Proses Perjanjian</span>
                        </div>
                        <div class="mu-chip success">
                            <span class="mu-chip-val" data-stat="aktif">{{ $totalAktif ?? 0 }}</span>
                            <span class="mu-chip-lbl">Sedang Aktif</span>
                        </div>
                        <div class="mu-chip">
                            <span class="mu-chip-val" data-stat="total">{{ $totalAll ?? $requests->total() }}</span>
                            <span class="mu-chip-lbl">Total Pengajuan</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stat bar --}}
            <div class="mu-stat-bar">
                @php
                    $statItems = [
                        ['label' => 'Menunggu Verifikasi',  'val' => $totalMenungguVerifikasi ?? 0, 'dot' => '#f59e0b', 'filter' => 'menunggu_verifikasi',          'key' => 'menunggu_verifikasi'],
                        ['label' => 'Proses Perjanjian',    'val' => $totalMenungguPerjanjian ?? 0, 'dot' => '#8b5cf6', 'filter' => 'menunggu_dokumen_perjanjian',  'key' => 'menunggu_perjanjian'],
                        ['label' => 'Menunggu Pembayaran',  'val' => $totalMenungguPembayaran ?? 0, 'dot' => '#f97316', 'filter' => 'menunggu_pembayaran',          'key' => 'menunggu_pembayaran'],
                        ['label' => 'Dalam Pengiriman',     'val' => $totalPengiriman ?? 0,         'dot' => '#0284c7', 'filter' => 'pengiriman',                   'key' => 'pengiriman'],
                        ['label' => 'Penyewaan Aktif',      'val' => $totalAktif ?? 0,             'dot' => '#10b981', 'filter' => 'aktif',                        'key' => 'aktif'],
                        ['label' => 'Segera Kembali',       'val' => $totalNearingReturn ?? 0,      'dot' => '#14b8a6', 'filter' => 'pengembalian',                 'key' => 'nearing_return'],
                        ['label' => 'Selesai',              'val' => $totalCompleted ?? 0,          'dot' => '#22c55e', 'filter' => 'selesai',                      'key' => 'selesai'],
                    ];
                @endphp

                @foreach($statItems as $s)
                    <a href="{{ route('pengelola.penyewaan.index', ['status' => $s['filter']]) }}"
                       class="mu-stat-item {{ request('status') === $s['filter'] ? 'active' : '' }}">
                        <span class="mu-stat-num" data-stat="{{ $s['key'] }}">{{ $s['val'] }}</span>
                        <span class="mu-stat-txt">
                            <span class="mu-stat-dot" style="background:{{ $s['dot'] }};"></span>
                            {{ $s['label'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════ CONTENT ══════════════════════════ --}}
        <div class="mu-content">

            {{-- Flash --}}
            @if(session('success'))
                <div class="mu-flash ok">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mu-flash err">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Toolbar --}}
            <form method="GET" action="{{ route('pengelola.penyewaan.index') }}" id="filter-form">
                <div class="mu-toolbar">
                    <div class="mu-search">
                        <svg class="mu-search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor, nama, koleksi…" id="search-input">
                    </div>

                    <div class="mu-divider"></div>

                    <select name="status" class="mu-select" onchange="this.form.submit()">
                        <option value="">Semua Status Aktif</option>
                        <option value="menunggu_verifikasi"           {{ request('status') === 'menunggu_verifikasi'           ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="menunggu_dokumen_perjanjian"   {{ request('status') === 'menunggu_dokumen_perjanjian'   ? 'selected' : '' }}>Menunggu Upload Perjanjian</option>
                        <option value="verifikasi_dokumen_perjanjian" {{ request('status') === 'verifikasi_dokumen_perjanjian' ? 'selected' : '' }}>Verifikasi Dokumen Perjanjian</option>
                        <option value="menunggu_pembayaran"           {{ request('status') === 'menunggu_pembayaran'           ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="pengiriman"                    {{ request('status') === 'pengiriman'                    ? 'selected' : '' }}>Persiapan Pengiriman</option>
                        <option value="siap_diserahkan"               {{ request('status') === 'siap_diserahkan'               ? 'selected' : '' }}>Siap Diserahkan</option>
                        <option value="dalam_pengiriman"              {{ request('status') === 'dalam_pengiriman'              ? 'selected' : '' }}>Dalam Pengiriman</option>
                        <option value="pengecekan_kondisi"            {{ request('status') === 'pengecekan_kondisi'            ? 'selected' : '' }}>Pengecekan Kondisi</option>
                        <option value="menunggu_review_kerusakan"     {{ request('status') === 'menunggu_review_kerusakan'     ? 'selected' : '' }}>Review Kerusakan</option>
                        <option value="menunggu_dokumen_serah_terima" {{ request('status') === 'menunggu_dokumen_serah_terima' ? 'selected' : '' }}>Menunggu Dok. Serah Terima</option>
                        <option value="verifikasi_serah_terima"       {{ request('status') === 'verifikasi_serah_terima'       ? 'selected' : '' }}>Verifikasi Serah Terima</option>
                        <option value="aktif"                         {{ request('status') === 'aktif'                         ? 'selected' : '' }}>Penyewaan Aktif</option>
                        <option value="pengembalian"                  {{ request('status') === 'pengembalian'                  ? 'selected' : '' }}>Proses Pengembalian</option>
                        <option value="menunggu_konfirmasi_refund"    {{ request('status') === 'menunggu_konfirmasi_refund'    ? 'selected' : '' }}>Konfirmasi Refund</option>
                        <option value="menunggu_ttd_pengembalian"     {{ request('status') === 'menunggu_ttd_pengembalian'     ? 'selected' : '' }}>Menunggu TTD Pengembalian</option>
                        <option value="menunggu_pembayaran_kerusakan" {{ request('status') === 'menunggu_pembayaran_kerusakan' ? 'selected' : '' }}>Tagihan Kerusakan</option>
                        <option value="menunggu_konfirmasi_selesai"   {{ request('status') === 'menunggu_konfirmasi_selesai'   ? 'selected' : '' }}>Konfirmasi Selesai</option>
                    </select>

                    <select name="jenis_penyewa" class="mu-select" onchange="this.form.submit()">
                        <option value="">Semua Jenis Penyewa</option>
                        <option value="perseorangan" {{ request('jenis_penyewa') === 'perseorangan' ? 'selected' : '' }}>Perseorangan</option>
                        <option value="instansi"     {{ request('jenis_penyewa') === 'instansi'     ? 'selected' : '' }}>Instansi</option>
                    </select>

                    <div class="mu-divider"></div>

                    <button type="submit" class="mu-btn mu-btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/></svg>
                        Filter
                    </button>

                    @if(request('search') || request('status') || request('jenis_penyewa'))
                        <a href="{{ route('pengelola.penyewaan.index') }}" class="mu-btn mu-btn-ghost">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Table Card --}}
            <div class="mu-card">
                <div class="mu-card-head">
                    <div class="mu-card-title">Daftar Pengajuan</div>
                    <div style="display:flex;align-items:center;gap:1rem;">
                        <div class="mu-per-page">
                            <span>Tampilkan</span>
                            <form method="GET" action="{{ route('pengelola.penyewaan.index') }}" style="display:inline;">
                                <input type="hidden" name="search"        value="{{ request('search') }}">
                                <input type="hidden" name="status"        value="{{ request('status') }}">
                                <input type="hidden" name="jenis_penyewa" value="{{ request('jenis_penyewa') }}">
                                <select name="per_page" onchange="this.form.submit()">
                                    @foreach([10,25,50] as $n)
                                        <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <span>baris &nbsp;·&nbsp; {{ $requests->total() }} total</span>
                        </div>
                    </div>
                </div>

                @if($requests->isEmpty())
                    <div class="mu-empty">
                        <div class="mu-empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </div>
                        <h3>Tidak ada pengajuan</h3>
                        <p>Tidak ada data yang sesuai dengan filter yang dipilih.</p>
                    </div>
                @else
                    <div style="overflow-x:auto;" id="table-wrap">
                        <table class="mu-table" id="penyewaan-table">
                            <thead>
                                <tr>
                                    <th>No. Pengajuan</th>
                                    <th>Penyewa</th>
                                    <th>Koleksi</th>
                                    <th>Periode Sewa</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                @foreach($requests as $item)
                                @php
                                    $badgeClass = match($item->status ?? 'draft') {
                                        'menunggu_verifikasi'           => 'st-menunggu-verifikasi',
                                        'menunggu_dokumen_perjanjian'   => 'st-menunggu-perjanjian',
                                        'verifikasi_dokumen_perjanjian' => 'st-verifikasi-perjanjian',
                                        'menunggu_pembayaran'           => 'st-menunggu-bayar',
                                        'pengiriman'                    => 'st-pengiriman',
                                        'siap_diserahkan'               => 'st-pengiriman',
                                        'dalam_pengiriman'              => 'st-pengiriman',
                                        'pengecekan_kondisi'            => 'st-pengiriman',
                                        'menunggu_review_kerusakan'     => 'st-menunggu-verifikasi',
                                        'menunggu_data_rekening'        => 'st-menunggu-bayar',
                                        'menunggu_penerimaan_koleksi'   => 'st-pengiriman',
                                        'menunggu_refund_kerusakan'     => 'st-menunggu-bayar',
                                        'menunggu_konfirmasi_refund'    => 'st-pengembalian',
                                        'menunggu_dokumen_serah_terima' => 'st-menunggu-st',
                                        'verifikasi_serah_terima'       => 'st-verifikasi-st',
                                        'aktif'                         => 'st-aktif',
                                        'pengembalian'                  => 'st-pengembalian',
                                        'menunggu_ttd_pengembalian'     => 'st-menunggu-st',
                                        'menunggu_pembayaran_kerusakan' => 'st-menunggu-bayar',
                                        'menunggu_konfirmasi_selesai'   => 'st-aktif',
                                        'selesai'                       => 'st-selesai',
                                        'ditolak'                       => 'st-ditolak',
                                        'dibatalkan'                    => 'st-dibatalkan',
                                        default                         => 'st-draft',
                                    };
                                    $statusLabel = $item->status_label ?? ucfirst(str_replace('_', ' ', $item->status ?? 'draft'));
                                @endphp
                                <tr data-id="{{ $item->id }}">
                                    <td>
                                        <div class="mu-id">{{ $item->nomor_pengajuan ?? 'SW-' . str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</div>
                                        <div class="mu-date">{{ $item->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="mu-name">
                                            {{ $item->rental_type === 'instansi'
                                                ? ($item->nama_instansi ?? $item->institution_name ?? '-')
                                                : ($item->contact_name ?? '-') }}
                                        </div>
                                        <div>
                                            <span class="mu-type-pill {{ $item->rental_type === 'instansi' ? 'inst' : '' }}">
                                                @if($item->rental_type === 'instansi')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21"/></svg>
                                                    Instansi
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                                                    Perseorangan
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mu-koleksi">
                                            @if($item->painting->image_url ?? $item->painting->image_path ?? null)
                                                <img class="mu-thumb" src="{{ $item->painting->image_url ?? asset('storage/' . $item->painting->image_path) }}" alt="{{ $item->painting->title ?? '' }}">
                                            @else
                                                <div class="mu-thumb">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="mu-kname">{{ $item->painting->title ?? '-' }}</div>
                                                <div class="mu-kartist">{{ $item->painting->artist ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mu-period">
                                            {{ $item->start_date ? $item->start_date->format('d M Y') : '-' }}
                                            <span style="color:#b0bac6;"> – </span>
                                            {{ $item->end_date ? $item->end_date->format('d M Y') : '-' }}
                                        </div>
                                        @if($item->duration_days)
                                            <span class="mu-dur">{{ $item->duration_days }} hari</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="mu-badge {{ $badgeClass }}" data-row-id="{{ $item->id }}" data-current-status="{{ $item->status ?? 'draft' }}">
                                            <span class="mu-badge-dot"></span>
                                            {{ $item->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pengelola.penyewaan.show', $item) }}" class="mu-btn-detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($requests->hasPages())
                    <div class="mu-pagination">
                        <div class="mu-page-info">
                            Menampilkan <strong>{{ $requests->firstItem() }}–{{ $requests->lastItem() }}</strong>
                            dari <strong>{{ $requests->total() }}</strong> pengajuan
                        </div>
                        <div class="mu-page-btns">
                            @if($requests->onFirstPage())
                                <span class="mu-page-btn disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                                </span>
                            @else
                                <a href="{{ $requests->previousPageUrl() }}" class="mu-page-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                                </a>
                            @endif

                            @foreach($requests->getUrlRange(max(1, $requests->currentPage() - 2), min($requests->lastPage(), $requests->currentPage() + 2)) as $page => $url)
                                <a href="{{ $url }}" class="mu-page-btn {{ $page == $requests->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                            @endforeach

                            @if($requests->hasMorePages())
                                <a href="{{ $requests->nextPageUrl() }}" class="mu-page-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                </a>
                            @else
                                <span class="mu-page-btn disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif
                @endif
            </div>

        </div>{{-- /mu-content --}}

    </div>{{-- /mu-root --}}

    {{-- Toast Notification --}}
    <div class="mu-toast" id="update-toast">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
        <span id="toast-msg">Status diperbarui</span>
    </div>

    <script>
    (function () {
        // ── Status label map ──
        const STATUS_LABELS = {
            'draft':                         'Draft Pengajuan',
            'menunggu_verifikasi':           'Menunggu Verifikasi',
            'menunggu_dokumen_perjanjian':   'Upload Dokumen Perjanjian',
            'verifikasi_dokumen_perjanjian': 'Dokumen Sedang Diverifikasi',
            'menunggu_pembayaran':           'Menunggu Pembayaran',
            'pengiriman':                    'Koleksi Sedang Dikirim',
            'menunggu_dokumen_serah_terima': 'Upload Dokumen Serah Terima',
            'verifikasi_serah_terima':       'Dokumen Sedang Diverifikasi',
            'aktif':                         'Masa Penyewaan Aktif',
            'pengembalian':                  'Proses Pengembalian',
            'selesai':                       'Penyewaan Selesai',
            'ditolak':                       'Pengajuan Ditolak',
            'dibatalkan':                    'Pengajuan Dibatalkan',
        };

        const STATUS_CLASS = {
            'draft':                         'st-draft',
            'menunggu_verifikasi':           'st-menunggu-verifikasi',
            'menunggu_dokumen_perjanjian':   'st-menunggu-perjanjian',
            'verifikasi_dokumen_perjanjian': 'st-verifikasi-perjanjian',
            'menunggu_pembayaran':           'st-menunggu-bayar',
            'pengiriman':                    'st-pengiriman',
            'menunggu_dokumen_serah_terima': 'st-menunggu-st',
            'verifikasi_serah_terima':       'st-verifikasi-st',
            'aktif':                         'st-aktif',
            'pengembalian':                  'st-pengembalian',
            'selesai':                       'st-selesai',
            'ditolak':                       'st-ditolak',
            'dibatalkan':                    'st-dibatalkan',
        };

        // Kumpulkan semua ID yang ada di tabel sekarang
        function getVisibleIds() {
            return [...document.querySelectorAll('[data-row-id]')].map(el => el.dataset.rowId);
        }

        // Toast helper
        let toastTimer = null;
        function showToast(msg) {
            const t = document.getElementById('update-toast');
            document.getElementById('toast-msg').textContent = msg;
            t.classList.add('show');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => t.classList.remove('show'), 3000);
        }

        // Polling: cek status setiap 20 detik
        const POLL_INTERVAL = 20000;

        async function pollStatuses() {
            const ids = getVisibleIds();
            if (!ids.length) return;

            try {
                // Endpoint kita buat via route JSON — fallback: iterate individual
                const url = '{{ route("pengelola.penyewaan.index") }}?_json=1&ids=' + ids.join(',');
                const resp = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });

                if (!resp.ok) return;
                const data = await resp.json();

                // data.statuses = { id: { status, status_label } }
                if (!data.statuses) return;

                let changed = 0;
                Object.entries(data.statuses).forEach(([id, info]) => {
                    const badge = document.querySelector(`[data-row-id="${id}"]`);
                    if (!badge) return;

                    const prev = badge.dataset.currentStatus;
                    const next = info.status;
                    if (prev === next) return;

                    // Update badge
                    const allClasses = Object.values(STATUS_CLASS);
                    badge.classList.remove(...allClasses);
                    badge.classList.add(STATUS_CLASS[next] || 'st-draft');
                    badge.querySelector('.mu-badge-dot'); // exists
                    // Update teks (node setelah dot)
                    const dot = badge.querySelector('.mu-badge-dot');
                    badge.innerHTML = '';
                    badge.appendChild(dot);
                    badge.append(' ' + (STATUS_LABELS[next] || next));
                    badge.dataset.currentStatus = next;

                    badge.classList.add('badge-updated');
                    setTimeout(() => badge.classList.remove('badge-updated'), 700);
                    changed++;
                });

                // Update stat counts di hero
                if (data.counts) {
                    Object.entries(data.counts).forEach(([key, val]) => {
                        document.querySelectorAll(`[data-stat="${key}"]`).forEach(el => {
                            if (el.textContent.trim() !== String(val)) {
                                el.textContent = val;
                                el.style.transition = 'color .4s';
                                el.style.color = '#38bdf8';
                                setTimeout(() => el.style.color = '', 1000);
                            }
                        });
                    });
                }

                if (changed > 0) {
                    showToast(`${changed} status diperbarui`);
                }
            } catch (e) {
                // Gagal fetch — diam saja, coba lagi nanti
            }
        }

        // Mulai polling setelah halaman load
        setTimeout(pollStatuses, POLL_INTERVAL);
        setInterval(pollStatuses, POLL_INTERVAL);

        // Search: debounce submit saat ketik
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            let debounceTimer;
            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    document.getElementById('filter-form').submit();
                }, 600);
            });
        }

    })();
    </script>

</x-app-layout>