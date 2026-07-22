<x-app-layout>
    <x-slot name="header">{{-- kosong, hero di dalam --}}</x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    @php $isPengelola = auth()->user()->role === 'pengelola'; @endphp

    <style>
        :root {
            --navy:   #0b1d35; --navy-2: #142744; --blue: #1d4ed8;
            --sky:    #38bdf8; --cream:  #f2f5f9; --slate: #64748b;
            --border: #e2e8f0; --white:  #ffffff;
        }
        * { box-sizing: border-box; }
        .tr-root { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; padding-bottom: 4rem; }

        /* ── HERO ── */
        .tr-hero { background: linear-gradient(140deg,#0b1d35 0%,#142744 55%,#1c3a68 100%); padding: 2.25rem 0; position: relative; overflow: hidden; }
        .tr-hero::before { content: ''; position: absolute; top: -60px; right: -80px; width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events: none; }
        .tr-hero-inner { max-width: 1100px; margin: 0 auto; padding: 0 2rem; position: relative; z-index: 1; }
        .tr-hero-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 1.5rem; flex-wrap: wrap; }

        .tr-breadcrumb { display: flex; align-items: center; gap: .45rem; margin-bottom: .85rem; }
        .tr-breadcrumb a { color: rgba(255,255,255,.45); font-size: .75rem; font-weight: 500; text-decoration: none; transition: color .15s; }
        .tr-breadcrumb a:hover { color: var(--sky); }
        .tr-breadcrumb-sep { color: rgba(255,255,255,.25); font-size: .7rem; }
        .tr-breadcrumb-cur { color: rgba(255,255,255,.7); font-size: .75rem; font-weight: 600; }

        .tr-hero-id { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 700; color: #fff; line-height: 1.2; margin: 0 0 .3rem; }
        .tr-hero-title { font-size: .88rem; color: rgba(255,255,255,.55); margin: 0; }

        .tr-hero-actions { display: flex; gap: .6rem; flex-wrap: wrap; align-items: flex-start; padding-top: .25rem; }
        .tr-hero-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .6rem 1.2rem; border-radius: .875rem; font-size: .8rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .18s; border: none; cursor: pointer; white-space: nowrap; }
        .tr-hero-btn svg { width: 13px; height: 13px; }
        .tr-hero-btn-back { background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15); color: rgba(255,255,255,.85); }
        .tr-hero-btn-back:hover { background: rgba(255,255,255,.17); }
        .tr-hero-btn-refresh { background: rgba(56,189,248,.15); border: 1px solid rgba(56,189,248,.3); color: var(--sky); }
        .tr-hero-btn-refresh:hover { background: rgba(56,189,248,.25); }

        /* CONTENT */
        .tr-content { max-width: 1100px; margin: 0 auto; padding: 1.75rem 2rem 0; display: grid; gap: 1.25rem; }

        /* CARD */
        .tr-card { background: var(--white); border: 1.5px solid var(--border); border-radius: 1.5rem; box-shadow: 0 4px 24px rgba(11,29,53,.05); overflow: hidden; }
        .tr-card-header { padding: 1.1rem 1.5rem; border-bottom: 1.5px solid #f0f4f8; display: flex; align-items: center; gap: .55rem; }
        .tr-card-header-accent { width: 3px; height: 16px; background: linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius: 99px; flex-shrink: 0; }
        .tr-card-header h3 { font-size: .76rem; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--navy); margin: 0; flex: 1; }
        .tr-card-body { padding: 1.5rem; }

        /* META GRID */
        .tr-meta-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(160px,1fr)); gap: .875rem; }
        .tr-meta-cell { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; padding: .9rem 1rem; }
        .tr-meta-cell .lbl { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .3rem; }
        .tr-meta-cell .val { font-size: .9rem; font-weight: 700; color: var(--navy); }

        /* REFRESH ROW */
        .tr-refresh-row { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; margin-top: 1.1rem; padding-top: 1.1rem; border-top: 1.5px solid #f0f4f8; }
        .tr-btn { display: inline-flex; align-items: center; gap: .4rem; padding: .62rem 1.25rem; border-radius: .875rem; font-size: .81rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: all .2s; border: none; cursor: pointer; }
        .tr-btn svg { width: 14px; height: 14px; }
        .tr-btn-navy    { background: var(--navy); color: #fff; }
        .tr-btn-navy:hover    { background: var(--blue); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(29,78,216,.3); }
        .tr-btn-ghost   { background: transparent; border: 1.5px solid var(--border); color: var(--slate); }
        .tr-btn-ghost:hover   { background: #f8fafc; border-color: #94a3b8; }
        .tr-hint { font-size: .73rem; color: #94a3b8; }

        /* DETAIL PAKET */
        .tr-detail-row { display: grid; grid-template-columns: repeat(auto-fit,minmax(130px,1fr)); gap: .875rem; }
        .tr-field .lbl { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #94a3b8; margin-bottom: .25rem; }
        .tr-field .val { font-size: .85rem; font-weight: 600; color: var(--navy); }

        /* TIMELINE */
        .tr-timeline-wrap { position: relative; }
        .tr-timeline-line { position: absolute; left: 13px; top: 0; bottom: 0; width: 2px; background: #f0f4f8; border-radius: 99px; }
        .tr-timeline-list { display: flex; flex-direction: column; gap: 1.25rem; position: relative; }
        .tr-tl-item { display: flex; gap: 1.25rem; padding-left: 2.75rem; position: relative; }

        .tr-tl-dot { position: absolute; left: 0; top: .15rem; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .tr-tl-dot.active  { background: linear-gradient(135deg,#1d4ed8,#38bdf8); box-shadow: 0 0 0 4px rgba(29,78,216,.12); }
        .tr-tl-dot.done    { background: linear-gradient(135deg,#059669,#10b981); box-shadow: 0 0 0 4px rgba(16,185,129,.1); }
        .tr-tl-dot.pending { background: var(--white); border: 2px solid var(--border); }
        .tr-tl-dot-inner-active  { width: 9px; height: 9px; border-radius: 50%; background: #fff; }
        .tr-tl-dot-inner-done   { /* checkmark via SVG */ }
        .tr-tl-dot-inner-pending { width: 7px; height: 7px; border-radius: 50%; background: #e2e8f0; }

        .tr-tl-body { flex: 1; padding-bottom: .25rem; }
        .tr-tl-label { font-size: .875rem; font-weight: 700; color: var(--navy); line-height: 1.3; }
        .tr-tl-label.dim { color: #94a3b8; font-weight: 500; }
        .tr-tl-desc { font-size: .78rem; color: var(--slate); margin-top: .15rem; }
        .tr-tl-desc.dim { color: #c4cdd6; }
        .tr-tl-note { font-size: .75rem; color: #64748b; margin-top: .25rem; font-style: italic; }
        .tr-tl-meta { font-size: .72rem; color: #94a3b8; margin-top: .2rem; }

        /* EMPTY / ERROR STATE */
        .tr-state-box { border-radius: 1.25rem; padding: 3rem 1.5rem; text-align: center; }
        .tr-state-icon { font-size: 2.75rem; margin-bottom: .75rem; }
        .tr-state-title { font-size: .9rem; font-weight: 700; color: var(--navy); margin-bottom: .25rem; }
        .tr-state-desc { font-size: .8rem; color: var(--slate); }

        /* NOTE BOX */
        .tr-note-box { background: #f8fafc; border: 1.5px solid var(--border); border-radius: 1rem; padding: .875rem 1rem; font-size: .79rem; color: #475569; line-height: 1.65; }
        .tr-note-box strong { color: var(--navy); }

        @media(max-width:768px){
            .tr-content { padding: 1.25rem 1rem 0; }
            .tr-hero-inner { padding: 0 1rem; }
        }
    </style>

    <div class="tr-root">

        {{-- ── HERO ── --}}
        <div class="tr-hero">
            <div class="tr-hero-inner">
                <div class="tr-hero-top">
                    <div>
                        <div class="tr-breadcrumb">
                            @if($isPengelola)
                                <a href="{{ route('pengelola.pembelian.index') }}">Daftar Pengajuan</a>
                                <span class="tr-breadcrumb-sep">/</span>
                                <a href="{{ route('pengelola.pembelian.show', $pembelian) }}">BLI-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</a>
                                <span class="tr-breadcrumb-sep">/</span>
                                <a href="{{ route('pengelola.pembelian.serah-terima', $pembelian) }}">Serah Terima</a>
                            @else
                                <a href="{{ route('pembelian.show', $pembelian) }}">BLI-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</a>
                                <span class="tr-breadcrumb-sep">/</span>
                                <a href="{{ route('pembelian.serah-terima', $pembelian) }}">Serah Terima</a>
                            @endif
                            <span class="tr-breadcrumb-sep">/</span>
                            <span class="tr-breadcrumb-cur">Tracking</span>
                        </div>
                        <h1 class="tr-hero-id">Tracking Pengiriman</h1>
                        <p class="tr-hero-title">
                            BLI-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}
                            &mdash; {{ $pembelian->painting->title }}
                        </p>
                    </div>
                    <div class="tr-hero-actions">
                        <a href="{{ $isPengelola
                                ? route('pengelola.pembelian.serah-terima', $pembelian)
                                : route('pembelian.serah-terima', $pembelian) }}"
                           class="tr-hero-btn tr-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Serah Terima
                        </a>
                        @if($pembelian->delivery_tracking_number)
                            <a href="{{ (($isPengelola
                                    ? route('pengelola.pembelian.tracking', $pembelian)
                                    : route('pembelian.tracking', $pembelian))) . '?refresh=1' }}"
                               class="tr-hero-btn tr-hero-btn-refresh">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                Refresh Data
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CONTENT ── --}}
        <div class="tr-content">

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- PENGIRIMAN PENGELOLA: timeline manual                      --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            @if($pembelian->shipping_method_type === 'manager')

                {{-- INFO PENGIRIMAN --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <div class="tr-card-header-accent"></div>
                        <h3>Informasi Pengiriman</h3>
                    </div>
                    <div class="tr-card-body">
                        <div class="tr-meta-grid">
                            <div class="tr-meta-cell">
                                <div class="lbl">Metode</div>
                                <div class="val">Dikirim oleh Pengelola</div>
                            </div>
                            <div class="tr-meta-cell">
                                <div class="lbl">Tujuan</div>
                                <div class="val">{{ $pembelian->kota_kabupaten }}</div>
                            </div>
                            @if($pembelian->delivery_officer)
                            <div class="tr-meta-cell">
                                <div class="lbl">Petugas Pengirim</div>
                                <div class="val">{{ $pembelian->delivery_officer }}</div>
                            </div>
                            @endif
                            @if($pembelian->delivery_scheduled_at)
                            <div class="tr-meta-cell">
                                <div class="lbl">Rencana Kirim</div>
                                <div class="val">{{ \Carbon\Carbon::parse($pembelian->delivery_scheduled_at)->format('d M Y, H:i') }}</div>
                            </div>
                            @endif
                        </div>
                        @if($pembelian->delivery_notes)
                            <div class="tr-note-box" style="margin-top:1rem;">
                                <strong>Catatan Pengiriman:</strong> {{ $pembelian->delivery_notes }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- TIMELINE MANUAL --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <div class="tr-card-header-accent"></div>
                        <h3>Progress Pengiriman oleh Pengelola</h3>
                    </div>
                    <div class="tr-card-body">
                        @php
                            $allSteps = [
                                'dikemas'          => ['label' => 'Sedang Dikemas',   'icon' => '📦', 'desc' => 'Koleksi sedang dikemas dengan aman'],
                                'siap_dikirim'     => ['label' => 'Siap Dikirim',     'icon' => '✅', 'desc' => 'Koleksi siap untuk dikirimkan'],
                                'dalam_perjalanan' => ['label' => 'Dalam Perjalanan', 'icon' => '🚗', 'desc' => 'Koleksi sedang dalam perjalanan ke alamat Anda'],
                                'tiba_di_tujuan'   => ['label' => 'Tiba di Tujuan',   'icon' => '🏠', 'desc' => 'Koleksi telah tiba di alamat tujuan'],
                            ];
                            $currentStatus = $pembelian->manager_delivery_status;
                            $stepKeys      = array_keys($allSteps);
                            $currentIndex  = $currentStatus ? array_search($currentStatus, $stepKeys) : -1;
                            $timeline      = collect($pembelian->manager_delivery_timeline ?? [])->keyBy('status');
                        @endphp

                        <div class="tr-timeline-wrap">
                            <div class="tr-timeline-line"></div>
                            <div class="tr-timeline-list">

                                {{-- Step: Disetujui (selalu selesai) --}}
                                <div class="tr-tl-item">
                                    <div class="tr-tl-dot done">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </div>
                                    <div class="tr-tl-body">
                                        <div class="tr-tl-label">✅ Pengajuan Disetujui</div>
                                        <div class="tr-tl-meta">{{ $pembelian->updated_at?->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>

                                {{-- Step: Pembayaran Berhasil --}}
                                <div class="tr-tl-item">
                                    <div class="tr-tl-dot done">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </div>
                                    <div class="tr-tl-body">
                                        <div class="tr-tl-label">💳 Pembayaran Berhasil</div>
                                        <div class="tr-tl-meta">{{ $pembelian->paid_at?->format('d M Y, H:i') ?? '-' }}</div>
                                    </div>
                                </div>

                                {{-- Steps dari pengelola --}}
                                @foreach($allSteps as $key => $step)
                                    @php
                                        $stepIndex = array_search($key, $stepKeys);
                                        $isDone    = $currentIndex >= $stepIndex;
                                        $isCurrent = $currentStatus === $key;
                                        $entry     = $timeline->get($key);
                                        $dotClass  = $isCurrent ? 'active' : ($isDone ? 'done' : 'pending');
                                    @endphp
                                    <div class="tr-tl-item">
                                        <div class="tr-tl-dot {{ $dotClass }}">
                                            @if($isDone && !$isCurrent)
                                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            @elseif($isCurrent)
                                                <div class="tr-tl-dot-inner-active"></div>
                                            @else
                                                <div class="tr-tl-dot-inner-pending"></div>
                                            @endif
                                        </div>
                                        <div class="tr-tl-body">
                                            <div class="tr-tl-label {{ $isDone ? '' : 'dim' }}">
                                                {{ $step['icon'] }} {{ $step['label'] }}
                                            </div>
                                            <div class="tr-tl-desc {{ $isDone ? '' : 'dim' }}">{{ $step['desc'] }}</div>
                                            @if($entry)
                                                @if($entry['catatan'])
                                                    <div class="tr-tl-note">"{{ $entry['catatan'] }}"</div>
                                                @endif
                                                <div class="tr-tl-meta">
                                                    {{ \Carbon\Carbon::parse($entry['timestamp'])->format('d M Y, H:i') }}
                                                    &bull; {{ $entry['by'] }}
                                                </div>
                                            @elseif(!$isDone)
                                                <div class="tr-tl-meta" style="color:#d1d5db;">Menunggu update dari pengelola</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Step: Konfirmasi Pembeli --}}
                                @php $konfirmasiDone = !!$pembelian->received_at; @endphp
                                <div class="tr-tl-item">
                                    <div class="tr-tl-dot {{ $konfirmasiDone ? 'done' : 'pending' }}">
                                        @if($konfirmasiDone)
                                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        @else
                                            <div class="tr-tl-dot-inner-pending"></div>
                                        @endif
                                    </div>
                                    <div class="tr-tl-body">
                                        <div class="tr-tl-label {{ $konfirmasiDone ? '' : 'dim' }}">🤝 Dikonfirmasi Pembeli</div>
                                        <div class="tr-tl-meta {{ $konfirmasiDone ? '' : '' }}" style="{{ !$konfirmasiDone ? 'color:#d1d5db;' : '' }}">
                                            {{ $pembelian->received_at?->format('d M Y, H:i') ?? 'Menunggu konfirmasi dari pembeli' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            {{-- ═══════════════════════════════════════════════════════════ --}}
            {{-- PENGIRIMAN KURIR: tracking RajaOngkir                      --}}
            {{-- ═══════════════════════════════════════════════════════════ --}}
            @else

                {{-- INFO RESI --}}
                <div class="tr-card">
                    <div class="tr-card-header">
                        <div class="tr-card-header-accent"></div>
                        <h3>Informasi Pengiriman</h3>
                    </div>
                    <div class="tr-card-body">
                        <div class="tr-meta-grid">
                            <div class="tr-meta-cell">
                                <div class="lbl">Kurir</div>
                                <div class="val">{{ strtoupper($pembelian->delivery_method) }}</div>
                            </div>
                            <div class="tr-meta-cell">
                                <div class="lbl">Nomor Resi</div>
                                <div class="val" style="font-family:monospace;letter-spacing:.03em;">{{ $pembelian->delivery_tracking_number ?? '-' }}</div>
                            </div>
                            <div class="tr-meta-cell">
                                <div class="lbl">Tujuan</div>
                                <div class="val">{{ $pembelian->kota_kabupaten }}</div>
                            </div>
                        </div>

                        @if($pembelian->delivery_tracking_number)
                            <div class="tr-refresh-row">
                                <a href="{{ $isPengelola
                                        ? route('pengelola.pembelian.tracking', $pembelian)
                                        : route('pembelian.tracking', $pembelian) }}"
                                   class="tr-btn tr-btn-navy">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                    Cek Status
                                </a>
                                <a href="{{ (($isPengelola
                                        ? route('pengelola.pembelian.tracking', $pembelian)
                                        : route('pembelian.tracking', $pembelian))) . '?refresh=1' }}"
                                   class="tr-btn tr-btn-ghost">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                    Refresh Data
                                </a>
                                <span class="tr-hint">Data diperbarui setiap 15 menit</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- TIDAK ADA RESI --}}
                @if(!$pembelian->delivery_tracking_number)
                    <div class="tr-card">
                        <div class="tr-card-body">
                            <div class="tr-state-box">
                                <div class="tr-state-icon">📋</div>
                                <div class="tr-state-title">Nomor resi belum tersedia</div>
                                <div class="tr-state-desc">Pengelola belum memasukkan nomor resi pengiriman.</div>
                            </div>
                        </div>
                    </div>

                {{-- ERROR --}}
                @elseif($error)
                    <div class="tr-card">
                        <div class="tr-card-body">
                            <div class="tr-state-box">
                                <div class="tr-state-icon">⚠️</div>
                                <div class="tr-state-title">Gagal mengambil data tracking</div>
                                <div class="tr-state-desc">{{ $error }}</div>
                            </div>
                        </div>
                    </div>

                {{-- TRACKING DATA --}}
                @elseif($trackingData)

                    {{-- DETAIL PENGIRIM / PENERIMA --}}
                    @if($trackingData['shipper'] || $trackingData['receiver'] || $trackingData['weight'])
                        <div class="tr-card">
                            <div class="tr-card-header">
                                <div class="tr-card-header-accent"></div>
                                <h3>Detail Paket</h3>
                            </div>
                            <div class="tr-card-body">
                                <div class="tr-detail-row">
                                    @if($trackingData['shipper'])
                                        <div class="tr-field">
                                            <div class="lbl">Pengirim</div>
                                            <div class="val">{{ $trackingData['shipper'] }}</div>
                                        </div>
                                    @endif
                                    @if($trackingData['receiver'])
                                        <div class="tr-field">
                                            <div class="lbl">Penerima</div>
                                            <div class="val">{{ $trackingData['receiver'] }}</div>
                                        </div>
                                    @endif
                                    @if($trackingData['weight'])
                                        <div class="tr-field">
                                            <div class="lbl">Berat</div>
                                            <div class="val">{{ $trackingData['weight'] }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- TIMELINE KURIR --}}
                    <div class="tr-card">
                        <div class="tr-card-header">
                            <div class="tr-card-header-accent"></div>
                            <h3>Riwayat Pengiriman</h3>
                        </div>
                        <div class="tr-card-body">
                            @if(count($trackingData['history']) > 0)
                                <div class="tr-timeline-wrap">
                                    <div class="tr-timeline-line"></div>
                                    <div class="tr-timeline-list">
                                        @foreach($trackingData['history'] as $i => $item)
                                            @php $isFirst = $i === 0; @endphp
                                            <div class="tr-tl-item">
                                                <div class="tr-tl-dot {{ $isFirst ? 'active' : 'done' }}">
                                                    @if($isFirst)
                                                        <div class="tr-tl-dot-inner-active"></div>
                                                    @else
                                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                    @endif
                                                </div>
                                                <div class="tr-tl-body">
                                                    <div class="tr-tl-label">{{ $item['description'] ?: '-' }}</div>
                                                    @if($item['city'])
                                                        <div class="tr-tl-desc">📍 {{ $item['city'] }}</div>
                                                    @endif
                                                    <div class="tr-tl-meta">
                                                        {{ $item['datetime'] }}{{ $item['time'] ? ' ' . $item['time'] : '' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="tr-state-box">
                                    <div class="tr-state-icon">📋</div>
                                    <div class="tr-state-title">Belum ada riwayat pengiriman</div>
                                    <div class="tr-state-desc">Riwayat pengiriman akan muncul setelah kurir memproses paket.</div>
                                </div>
                            @endif
                        </div>
                    </div>

                @endif

                {{-- CATATAN KURIR TIDAK DIKENAL --}}
                @if($pembelian->delivery_method && !collect(['jne','j&t','jnt','sicepat','tiki','pos','anteraja','lion','ninja','sap','ide','wahana'])->contains(fn($k) => str_contains(strtolower($pembelian->delivery_method), $k)))
                    <div class="tr-note-box">
                        ℹ️ Pengiriman menggunakan <strong>{{ $pembelian->delivery_method }}</strong>.
                        Tracking otomatis hanya tersedia untuk kurir umum (JNE, J&T, SiCepat, dll).
                        Hubungi museum untuk info pengiriman lebih lanjut.
                    </div>
                @endif

            @endif
            {{-- akhir @if manager / @else kurir --}}

        </div>
    </div>
</x-app-layout>