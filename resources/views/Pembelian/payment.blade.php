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
        .ps-root { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; padding-bottom: 4rem; }

        /* ── HERO ── */
        .ps-hero { background: linear-gradient(140deg,#0b1d35 0%,#142744 55%,#1c3a68 100%); padding: 2.25rem 0; position: relative; overflow: hidden; }
        .ps-hero::before { content: ''; position: absolute; top: -60px; right: -80px; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events: none; }
        .ps-hero-inner { max-width: 860px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 1; }
        .ps-hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; }

        .ps-breadcrumb { display: flex; align-items: center; gap: .45rem; margin-bottom: .85rem; }
        .ps-breadcrumb a { color: rgba(255,255,255,.45); font-size: .75rem; font-weight: 500; text-decoration: none; transition: color .15s; }
        .ps-breadcrumb a:hover { color: var(--sky); }
        .ps-breadcrumb-sep { color: rgba(255,255,255,.25); font-size: .7rem; }
        .ps-breadcrumb-cur { color: rgba(255,255,255,.7); font-size: .75rem; font-weight: 600; }

        .ps-hero-id { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; color: #fff; line-height: 1.2; margin: 0 0 .3rem; }
        .ps-hero-title { font-size: .88rem; color: rgba(255,255,255,.55); margin: 0; }

        .ps-hero-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: flex-start; padding-top: .25rem; }
        .ps-hero-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.2rem; border-radius: .875rem; font-size: .8rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .18s; border: none; cursor: pointer; white-space: nowrap; }
        .ps-hero-btn svg { width: 13px; height: 13px; }
        .ps-hero-btn-back { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15); color: rgba(255,255,255,.85); }
        .ps-hero-btn-back:hover { background: rgba(255,255,255,.17); }

        /* BADGE */
        .ps-status-badge { display: inline-flex; align-items: center; gap: .35rem; padding: .35rem 1rem; border-radius: 99px; font-size: .72rem; font-weight: 700; letter-spacing: .04em; margin-top: .75rem; background: rgba(249,115,22,.15); border: 1px solid rgba(249,115,22,.3); color: #fb923c; }
        .ps-status-dot { width: 6px; height: 6px; border-radius: 50%; background: #fb923c; }

        /* CONTENT */
        .ps-content { max-width: 860px; margin: 0 auto; padding: 1.75rem 2rem 0; display: grid; gap: 1.25rem; }

        /* FLASH */
        .ps-flash { border-radius: .875rem; padding: .85rem 1.2rem; font-size: .83rem; font-weight: 600; display: flex; align-items: center; gap: .55rem; }
        .ps-flash svg { width: 16px; height: 16px; flex-shrink: 0; }
        .ps-flash.ok  { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .ps-flash.err { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }

        /* CARD */
        .ps-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.5rem; box-shadow: 0 4px 24px rgba(11,29,53,.05); overflow: hidden; }
        .ps-card-header { padding: 1.1rem 1.5rem; border-bottom: 1.5px solid #f0f4f8; display: flex; align-items: center; gap: .55rem; }
        .ps-card-header-accent { width: 3px; height: 16px; background: linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius: 99px; flex-shrink: 0; }
        .ps-card-header h3 { font-size: .76rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--navy); margin: 0; }
        .ps-card-body { padding: 1.5rem; }

        /* PAINTING ROW */
        .ps-painting-row { display: flex; align-items: center; gap: 1rem; padding-bottom: 1.25rem; margin-bottom: 1.25rem; border-bottom: 1.5px solid #f0f4f8; }
        .ps-painting-thumb { width: 64px; height: 64px; border-radius: .875rem; overflow: hidden; flex-shrink: 0; background: #f1f5f9; }
        .ps-painting-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .ps-painting-thumb-empty { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #b0bac6; font-size: .7rem; }
        .ps-painting-info h4 { font-family: 'Playfair Display', serif; font-size: 1rem; color: var(--navy); margin: 0 0 .2rem; }
        .ps-painting-info p { font-size: .78rem; color: var(--slate); margin: 0; }

        /* COST */
        .ps-cost-wrap { background: linear-gradient(135deg,#0b1d35,#142744); border-radius: 1.25rem; padding: 1.5rem; }
        .ps-cost-row { display: flex; justify-content: space-between; align-items: center; padding: .5rem 0; border-bottom: 1px solid rgba(255,255,255,.07); font-size: .84rem; }
        .ps-cost-row:last-child { border-bottom: none; }
        .ps-cost-row .lbl { color: rgba(255,255,255,.55); }
        .ps-cost-row .val { font-weight: 600; color: #fff; }
        .ps-cost-total { margin-top: .75rem; padding-top: .75rem; border-top: 1.5px solid rgba(255,255,255,.12); display: flex; justify-content: space-between; align-items: center; }
        .ps-cost-total .lbl { font-size: .8rem; color: rgba(255,255,255,.5); font-weight: 600; }
        .ps-cost-total .val { font-family: 'Playfair Display', serif; font-size: 1.4rem; color: #fff; }
        .ps-info-box { background: rgba(56,189,248,.07); border: 1px solid rgba(56,189,248,.2); border-radius: .875rem; padding: .875rem 1.1rem; margin-top: .75rem; }
        .ps-info-box p { font-size: .78rem; color: rgba(255,255,255,.65); line-height: 1.65; margin: 0; }
        .ps-info-box strong { color: var(--sky); }

        /* METODE GRID */
        .ps-methods-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: .75rem; }
        @media(max-width:500px){ .ps-methods-grid { grid-template-columns: repeat(2,1fr); } }
        .ps-method-badge { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; padding: .875rem 1rem; text-align: center; font-size: .78rem; font-weight: 600; color: var(--slate); }
        .ps-method-badge span { display: block; font-size: 1.2rem; margin-bottom: .3rem; }

        /* ALERT */
        .ps-alert { border-radius: 1rem; padding: .875rem 1.1rem; font-size: .82rem; display: flex; align-items: flex-start; gap: .65rem; }
        .ps-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: .1rem; }
        .ps-alert.info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }

        /* CTA */
        .ps-cta { text-align: center; padding: 1.5rem; }
        .ps-btn-pay { display: inline-flex; align-items: center; gap: .6rem; padding: .9rem 2.25rem; border-radius: .875rem; background: linear-gradient(135deg,var(--blue),#2563eb); color: #fff; font-size: .95rem; font-family: 'DM Sans', sans-serif; font-weight: 700; cursor: pointer; border: none; box-shadow: 0 4px 14px rgba(29,78,216,.35); transition: all .2s; }
        .ps-btn-pay:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(29,78,216,.4); }
        .ps-btn-pay:disabled { opacity: .6; cursor: not-allowed; transform: none; box-shadow: none; }
        .ps-btn-pay svg { width: 16px; height: 16px; }
        .ps-pay-note { font-size: .76rem; color: #94a3b8; margin-top: .65rem; }

        /* Loading spinner */
        .spinner { display: inline-block; width: 15px; height: 15px; border: 2.5px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        @media(max-width:768px){
            .ps-content    { padding: 1.25rem 1rem 0; }
            .ps-hero-inner { padding: 0 1rem; }
        }
    </style>

    <div class="ps-root">

        {{-- ── HERO ── --}}
        <div class="ps-hero">
            <div class="ps-hero-inner">
                <div class="ps-hero-top">
                    <div>
                        <div class="ps-breadcrumb">
                            <a href="{{ route('pembelian.index') }}">Pembelian Saya</a>
                            <span class="ps-breadcrumb-sep">/</span>
                            <a href="{{ route('pembelian.show', $pembelian) }}">BLI-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</a>
                            <span class="ps-breadcrumb-sep">/</span>
                            <span class="ps-breadcrumb-cur">Pembayaran</span>
                        </div>
                        <h1 class="ps-hero-id">Selesaikan Pembayaran</h1>
                        <p class="ps-hero-title">{{ $pembelian->painting->title }} &mdash; {{ $pembelian->painting->artist }}</p>
                        <div class="ps-status-badge">
                            <span class="ps-status-dot"></span>
                            Menunggu Pembayaran
                        </div>
                    </div>
                    <div class="ps-hero-actions">
                        <a href="{{ route('pembelian.show', $pembelian) }}" class="ps-hero-btn ps-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="ps-content">

            {{-- FLASH --}}
            @if(session('success'))
                <div class="ps-flash ok">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="ps-flash err">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── RINGKASAN PESANAN ── --}}
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Ringkasan Pesanan</h3>
                </div>
                <div class="ps-card-body">
                    <div class="ps-painting-row">
                        <div class="ps-painting-thumb">
                            @if($pembelian->painting->image_url ?? null)
                                <img src="{{ $pembelian->painting->image_url }}" alt="{{ $pembelian->painting->title }}">
                            @else
                                <div class="ps-painting-thumb-empty">📷</div>
                            @endif
                        </div>
                        <div class="ps-painting-info">
                            <h4>{{ $pembelian->painting->title }}</h4>
                            <p>{{ $pembelian->painting->artist }}</p>
                            <p style="margin-top:.2rem;font-family:monospace;font-size:.73rem;color:#94a3b8;">BLI-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                    <div class="ps-cost-wrap">
                        <div class="ps-cost-row">
                            <span class="lbl">Harga Beli Koleksi</span>
                            <span class="val">Rp {{ number_format($pembelian->harga_beli, 0, ',', '.') }}</span>
                        </div>
                        @if((int)$pembelian->shipping_cost > 0)
                        <div class="ps-cost-row">
                            <span class="lbl">
                                Ongkos Kirim
                                @if($pembelian->shipping_method_type === 'courier' && $pembelian->courier_name)
                                    ({{ $pembelian->courier_name }})
                                @elseif($pembelian->shipping_method_type === 'manager')
                                    (Pengelola)
                                @endif
                            </span>
                            <span class="val">Rp {{ number_format($pembelian->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        @else
                        <div class="ps-cost-row">
                            <span class="lbl">Ongkos Kirim</span>
                            <span class="val" style="color:#34d399;">Gratis</span>
                        </div>
                        @endif
                        <div class="ps-cost-total">
                            <span class="lbl">Total Pembayaran</span>
                            <span class="val">Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}</span>
                        </div>
                        <div class="ps-info-box">
                            <p><strong>ℹ️ Informasi:</strong> Harga di atas sudah final. Tidak ada PPN atau PPh — Museum MK Lesmana belum berstatus PKP.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── TOMBOL BAYAR ── --}}
            <div class="ps-card">
                <div class="ps-cta">
                    <button id="pay-btn" class="ps-btn-pay" onclick="startPayment()">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                        Bayar Sekarang
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- Midtrans Snap --}}
    @if(config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    @endif

    <script>
    const PAY_BTN_ORIGINAL = `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>Bayar Sekarang — Rp {{ number_format($pembelian->total_bayar, 0, ',', '.') }}`;

    const CANCEL_PENDING_URL = '{{ route('pembelian.cancelPendingPayment', $pembelian) }}';
    const PROCESS_URL        = '{{ route('pembelian.payment.process', $pembelian) }}';
    const SUCCESS_URL        = '{{ route('pembelian.payment.success', $pembelian) }}';
    const SHOW_URL           = '{{ route('pembelian.show', $pembelian) }}';
    const FAILED_URL         = '{{ route('pembelian.payment.failed', $pembelian) }}';
    const CSRF               = document.querySelector('meta[name="csrf-token"]').content;

    async function startPayment() {
        const btn = document.getElementById('pay-btn');
        btn.disabled  = true;
        btn.innerHTML = '<span class="spinner"></span> Memproses...';

        try {
            const res  = await fetch(PROCESS_URL, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            });
            const data = await res.json();

            // ── Ada pending aktif → tanya user mau cancel & retry ──
            if (data.error === 'Transaksi sedang diproses.') {
                btn.disabled  = false;
                btn.innerHTML = PAY_BTN_ORIGINAL;

                const confirm = window.confirm(
                    'Ada transaksi sebelumnya yang belum selesai.\n\nBatalkan transaksi lama dan coba lagi?'
                );
                if (!confirm) return;

                await cancelAndRetry();
                return;
            }

            if (!res.ok || data.error) throw new Error(data.error || 'Gagal membuat transaksi.');

            openSnap(data.snap_token, btn);

        } catch (e) {
            alert('Error: ' + e.message);
            btn.disabled  = false;
            btn.innerHTML = PAY_BTN_ORIGINAL;
        }
    }

    async function cancelAndRetry() {
        const btn = document.getElementById('pay-btn');
        btn.disabled  = true;
        btn.innerHTML = '<span class="spinner"></span> Membatalkan...';

        try {
            const res  = await fetch(CANCEL_PENDING_URL, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            });
            const data = await res.json();

            if (!res.ok || !data.success) throw new Error('Gagal membatalkan transaksi lama.');

            // Langsung proses ulang
            btn.innerHTML = '<span class="spinner"></span> Memproses...';

            const res2  = await fetch(PROCESS_URL, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            });
            const data2 = await res2.json();

            if (!res2.ok || data2.error) throw new Error(data2.error || 'Gagal membuat transaksi.');

            openSnap(data2.snap_token, btn);

        } catch (e) {
            alert('Error: ' + e.message);
            btn.disabled  = false;
            btn.innerHTML = PAY_BTN_ORIGINAL;
        }
    }

    function openSnap(token, btn) {
        snap.pay(token, {
            onSuccess: () => { window.location.href = SUCCESS_URL; },
            onPending: () => { window.location.href = SHOW_URL; },
            onError:   () => { window.location.href = FAILED_URL; },
            onClose:   () => { btn.disabled = false; btn.innerHTML = PAY_BTN_ORIGINAL; },
        });
    }
    </script>

</x-app-layout>