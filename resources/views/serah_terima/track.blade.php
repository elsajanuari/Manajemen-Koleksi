<x-app-layout>
    <x-slot name="header">{{-- kosong, hero di dalam --}}</x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    @php $isPengelola = auth()->user()->role === 'pengelola'; @endphp

    @php
        $statusConfig = [
            'waiting_handover'   => ['label' => 'Menunggu Pengiriman',    'class' => 'st-amber'],
            'preparing_delivery' => ['label' => 'Persiapan Pengiriman',   'class' => 'st-blue'],
            'in_delivery'        => ['label' => 'Sedang Dikirim',         'class' => 'st-sky'],
            'delivered'          => ['label' => 'Sudah Diterima Penyewa', 'class' => 'st-indigo'],
            'handover_completed' => ['label' => 'Serah Terima Selesai',   'class' => 'st-emerald'],
            'returned'           => ['label' => 'Dikembalikan',           'class' => 'st-teal'],
            'completed'          => ['label' => 'Selesai',                'class' => 'st-green'],
        ];
        $sc = $statusConfig[$serahTerima->handover_status]
            ?? ['label' => ucfirst(str_replace('_', ' ', $serahTerima->handover_status)), 'class' => 'st-slate'];

        $stepOrder  = ['waiting_handover','preparing_delivery','in_delivery','delivered','handover_completed'];
        $currentIdx = array_search($serahTerima->handover_status, $stepOrder);
        if ($currentIdx === false) $currentIdx = count($stepOrder);

        $penyewaanStatus = $penyewaan->status;

        $stages = [
            [
                'step'      => 13,
                'title'     => 'Pengelola Memproses Pengiriman',
                'desc'      => 'Pengelola menyiapkan koleksi, mengemas, dan mengatur pengiriman.',
                'handover'  => 'preparing_delivery',
                'icon'      => '📦',
                'timestamp' => null,
            ],
            [
                'step'      => 14,
                'title'     => 'Koleksi Dikirim ke Penyewa',
                'desc'      => 'Koleksi sudah dikirimkan. Status pengiriman dapat dipantau melalui nomor resi.',
                'handover'  => 'in_delivery',
                'icon'      => '🚚',
                'timestamp' => $serahTerima->shipped_at,
            ],
            [
                'step'      => 15,
                'title'     => 'Penyewa Konfirmasi Terima Koleksi',
                'desc'      => 'Penyewa menekan tombol "Koleksi Sudah Diterima" setelah koleksi tiba.',
                'handover'  => 'delivered',
                'icon'      => '✅',
                'timestamp' => $serahTerima->confirmed_received_at,
            ],
            [
                'step'      => '16–17',
                'title'     => 'Dokumen Serah Terima & Checklist Kondisi',
                'desc'      => 'Penyewa memeriksa kondisi koleksi, mengisi checklist, dan upload dokumen yang sudah ditandatangani.',
                'handover'  => null,
                'penyewaan' => ['menunggu_dokumen_serah_terima', 'verifikasi_serah_terima'],
                'icon'      => '📝',
                'timestamp' => $serahTerima->tenant_uploaded_at,
            ],
            [
                'step'      => 18,
                'title'     => 'Pengelola Memvalidasi Serah Terima',
                'desc'      => 'Pengelola memeriksa dokumen dan checklist kondisi. Jika valid, penyewaan menjadi aktif.',
                'handover'  => 'handover_completed',
                'icon'      => '🔍',
                'timestamp' => $serahTerima->validated_at,
            ],
            [
                'step'      => 19,
                'title'     => 'Masa Penyewaan Aktif',
                'desc'      => 'Koleksi resmi disewa. Sistem menampilkan countdown dan mengirim notifikasi H-3 sebelum pengembalian.',
                'handover'  => null,
                'penyewaan' => ['aktif'],
                'icon'      => '🎨',
                'timestamp' => $penyewaan->rental_started_at ?? null,
            ],
        ];

        foreach ($stages as $i => &$stage) {
            if (isset($stage['handover']) && $stage['handover'] !== null) {
                $stageIdx = array_search($stage['handover'], $stepOrder);
                $stage['status'] = $currentIdx > $stageIdx ? 'done'
                    : ($currentIdx === $stageIdx ? 'current' : 'pending');
            } elseif (isset($stage['penyewaan'])) {
                if ($penyewaanStatus === 'selesai' || $penyewaanStatus === 'pengembalian') {
                    $stage['status'] = 'done';
                } elseif (in_array($penyewaanStatus, $stage['penyewaan'])) {
                    $stage['status'] = 'current';
                } else {
                    $allStatuses = ['menunggu_verifikasi','menunggu_pembayaran','pengiriman','menunggu_dokumen_serah_terima','verifikasi_serah_terima','aktif','pengembalian','selesai'];
                    $pIdx  = array_search($penyewaanStatus, $allStatuses);
                    $minP  = min(array_map(fn($s) => array_search($s, $allStatuses) ?: 99, $stage['penyewaan']));
                    $stage['status'] = ($pIdx !== false && $pIdx > $minP) ? 'done' : 'pending';
                }
            } else {
                $stage['status'] = 'pending';
            }
        }
        unset($stage);
    @endphp

    <style>
        :root {
            --navy: #0b1d35; --navy-2: #142744; --blue: #1d4ed8;
            --sky: #38bdf8; --cream: #f2f5f9; --slate: #64748b;
            --border: #e2e8f0; --white: #ffffff;
        }
        * { box-sizing: border-box; }
        .tr-root { font-family: 'DM Sans', sans-serif; background: var(--cream); min-height: 100vh; padding-bottom: 4rem; }

        /* ── HERO ── */
        .st-hero { background: linear-gradient(140deg,#0b1d35 0%,#142744 55%,#1c3a68 100%); padding: 2.25rem 0; position: relative; overflow: hidden; }
        .st-hero::before { content:''; position:absolute; top:-60px; right:-80px; width:400px; height:400px; border-radius:50%; background:radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%); pointer-events:none; }
        .st-hero-inner { max-width:1100px; margin:0 auto; padding:0 2rem; position:relative; z-index:1; }
        .st-hero-top { display:flex; align-items:flex-start; justify-content:space-between; gap:1.5rem; flex-wrap:wrap; }

        .st-breadcrumb { display:flex; align-items:center; gap:.45rem; margin-bottom:.85rem; }
        .st-breadcrumb a { color:rgba(255,255,255,.45); font-size:.75rem; font-weight:500; text-decoration:none; transition:color .15s; }
        .st-breadcrumb a:hover { color:var(--sky); }
        .st-breadcrumb-sep { color:rgba(255,255,255,.25); font-size:.7rem; }
        .st-breadcrumb-cur { color:rgba(255,255,255,.7); font-size:.75rem; font-weight:600; }

        .st-hero-id { font-family:'Playfair Display',serif; font-size:1.75rem; font-weight:700; color:#fff; line-height:1.2; margin:0 0 .3rem; }
        .st-hero-title { font-size:.88rem; color:rgba(255,255,255,.55); margin:0; }

        .st-hero-actions { display:flex; gap:.6rem; flex-wrap:wrap; align-items:flex-start; padding-top:.25rem; }
        .st-hero-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.6rem 1.2rem; border-radius:.875rem; font-size:.8rem; font-weight:600; font-family:'DM Sans',sans-serif; text-decoration:none; transition:all .18s; border:none; cursor:pointer; white-space:nowrap; }
        .st-hero-btn svg { width:13px; height:13px; }
        .st-hero-btn-back { background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15); color:rgba(255,255,255,.85); }
        .st-hero-btn-back:hover { background:rgba(255,255,255,.17); }

        /* ── STATUS BADGES ── */
        .st-status-badge { display:inline-flex; align-items:center; gap:.35rem; padding:.35rem 1rem; border-radius:99px; font-size:.72rem; font-weight:700; letter-spacing:.04em; margin-top:.75rem; }
        .st-status-dot { width:6px; height:6px; border-radius:50%; }
        .st-amber   { background:rgba(251,191,36,.15);  border:1px solid rgba(251,191,36,.3);  color:#fbbf24; }
        .st-amber   .st-status-dot { background:#fbbf24; }
        .st-emerald { background:rgba(52,211,153,.15);  border:1px solid rgba(52,211,153,.3);  color:#34d399; }
        .st-emerald .st-status-dot { background:#34d399; }
        .st-blue    { background:rgba(96,165,250,.15);  border:1px solid rgba(96,165,250,.3);  color:#60a5fa; }
        .st-blue    .st-status-dot { background:#60a5fa; }
        .st-sky     { background:rgba(56,189,248,.15);  border:1px solid rgba(56,189,248,.3);  color:var(--sky); }
        .st-sky     .st-status-dot { background:var(--sky); }
        .st-indigo  { background:rgba(129,140,248,.15); border:1px solid rgba(129,140,248,.3); color:#818cf8; }
        .st-indigo  .st-status-dot { background:#818cf8; }
        .st-teal    { background:rgba(45,212,191,.15);  border:1px solid rgba(45,212,191,.3);  color:#2dd4bf; }
        .st-teal    .st-status-dot { background:#2dd4bf; }
        .st-green   { background:rgba(74,222,128,.15);  border:1px solid rgba(74,222,128,.3);  color:#4ade80; }
        .st-green   .st-status-dot { background:#4ade80; }
        .st-slate   { background:rgba(148,163,184,.1);  border:1px solid rgba(148,163,184,.2); color:#94a3b8; }
        .st-slate   .st-status-dot { background:#94a3b8; }

        /* ── CONTENT ── */
        .tr-content { max-width:1100px; margin:0 auto; padding:1.75rem 2rem 0; display:grid; gap:1.25rem; }

        /* ── CARD ── */
        .st-card { background:var(--white); border:1.5px solid var(--border); border-radius:1.5rem; box-shadow:0 4px 24px rgba(11,29,53,.05); overflow:hidden; }
        .st-card-header { padding:1.1rem 1.5rem; border-bottom:1.5px solid #f0f4f8; display:flex; align-items:center; gap:.55rem; }
        .st-card-header-accent { width:3px; height:16px; background:linear-gradient(180deg,#1d4ed8,#38bdf8); border-radius:99px; flex-shrink:0; }
        .st-card-header h3 { font-size:.76rem; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:var(--navy); margin:0; }
        .st-card-body { padding:1.5rem; }

        /* ── DOC INFO CARD ── */
        .tr-doc-grid { display:grid; grid-template-columns:1fr auto; align-items:center; gap:1.5rem; }
        @media(max-width:640px){ .tr-doc-grid { grid-template-columns:1fr; } }
        .tr-doc-number { font-family:'Playfair Display',serif; font-size:1.25rem; font-weight:700; color:var(--navy); margin:.2rem 0 .15rem; }
        .tr-doc-sub { font-size:.82rem; color:var(--slate); }
        .tr-doc-meta { font-size:.71rem; color:#94a3b8; margin-top:.4rem; }

        /* ── TIMELINE STAGES ── */
        .tr-stages { position:relative; }
        .tr-stage-line { position:absolute; left:19px; top:0; bottom:0; width:2px; background:var(--border); border-radius:99px; }
        .tr-stage-list { display:flex; flex-direction:column; gap:0; }
        .tr-stage-item { position:relative; display:flex; gap:1.25rem; padding-bottom:1.5rem; }
        .tr-stage-item:last-child { padding-bottom:0; }

        /* Dot */
        .tr-dot { position:relative; z-index:10; flex-shrink:0; width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:700; transition:all .3s; }
        .tr-dot.done    { background:linear-gradient(135deg,#059669,#10b981); box-shadow:0 0 0 4px rgba(16,185,129,.12); }
        .tr-dot.current { background:linear-gradient(135deg,#1d4ed8,#38bdf8); box-shadow:0 0 0 4px rgba(29,78,216,.18); }
        .tr-dot.pending { background:#f1f5f9; border:2px solid #e2e8f0; }

        /* Stage card */
        .tr-stage-card { flex:1; border-radius:1.25rem; padding:1.1rem 1.25rem; border:1.5px solid; }
        .tr-stage-card.done    { background:#f0fdf4; border-color:#bbf7d0; }
        .tr-stage-card.current { background:#eff6ff; border-color:#bfdbfe; }
        .tr-stage-card.pending { background:#f8fafc; border-color:var(--border); }

        .tr-stage-eyebrow { font-size:.67rem; font-weight:700; letter-spacing:.16em; text-transform:uppercase; margin-bottom:.25rem; }
        .tr-stage-eyebrow.done    { color:#059669; }
        .tr-stage-eyebrow.current { color:#1d4ed8; }
        .tr-stage-eyebrow.pending { color:#94a3b8; }

        .tr-stage-title { font-size:.9rem; font-weight:700; margin:0 0 .25rem; }
        .tr-stage-title.done    { color:var(--navy); }
        .tr-stage-title.current { color:var(--navy); }
        .tr-stage-title.pending { color:#94a3b8; }

        .tr-stage-desc { font-size:.78rem; color:#64748b; line-height:1.6; margin:0; }
        .tr-stage-desc.pending { color:#cbd5e1; }

        .tr-stage-header { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; }
        .tr-stage-ts { flex-shrink:0; text-align:right; }
        .tr-stage-ts .date { font-size:.72rem; color:#94a3b8; }
        .tr-stage-ts .time { font-size:.78rem; font-weight:700; color:var(--navy); }

        .tr-current-pill { display:inline-flex; align-items:center; gap:.3rem; background:linear-gradient(135deg,#1d4ed8,#38bdf8); color:#fff; font-size:.65rem; font-weight:700; letter-spacing:.06em; padding:.25rem .65rem; border-radius:99px; margin-left:.5rem; vertical-align:middle; }

        /* Detail chips */
        .tr-chips { display:flex; flex-wrap:wrap; gap:.5rem; margin-top:.875rem; }
        .tr-chip { display:inline-flex; align-items:center; gap:.35rem; background:var(--white); border:1.5px solid var(--border); border-radius:.875rem; padding:.4rem .75rem; font-size:.75rem; color:#334155; font-weight:500; }

        /* ── LOG ── */
        .tr-log-item { display:flex; gap:1rem; background:#f8fafc; border:1.5px solid var(--border); border-radius:1rem; padding:.875rem 1rem; }
        .tr-log-dot { width:8px; height:8px; border-radius:50%; background:#94a3b8; margin-top:.35rem; flex-shrink:0; }
        .tr-log-title { font-size:.83rem; font-weight:700; color:var(--navy); }
        .tr-log-msg { font-size:.78rem; color:#475569; margin-top:.15rem; line-height:1.55; }
        .tr-log-meta { font-size:.71rem; color:#94a3b8; margin-top:.2rem; }
        .tr-log-by { font-size:.72rem; font-weight:600; color:var(--slate); text-align:right; flex-shrink:0; }

        @media(max-width:768px){
            .tr-content { padding:1.25rem 1rem 0; }
            .st-hero-inner { padding:0 1rem; }
        }
    </style>

    <div class="tr-root">

        {{-- ── HERO ── --}}
        <div class="st-hero">
            <div class="st-hero-inner">
                <div class="st-hero-top">
                    <div>
                        <div class="st-breadcrumb">
                            @if($isPengelola)
                                <a href="{{ route('pengelola.penyewaan.index') }}">Daftar Penyewaan</a>
                                <span class="st-breadcrumb-sep">/</span>
                                <a href="{{ route('pengelola.penyewaan.show', $penyewaan) }}">SWA-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</a>
                                <span class="st-breadcrumb-sep">/</span>
                                <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}">Serah Terima</a>
                            @else
                                <a href="{{ route('penyewaan.requests.show', $penyewaan) }}">SWA-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</a>
                                <span class="st-breadcrumb-sep">/</span>
                                <a href="{{ route('penyewaan.requests.handover.show', $penyewaan) }}">Serah Terima</a>
                            @endif
                            <span class="st-breadcrumb-sep">/</span>
                            <span class="st-breadcrumb-cur">Timeline</span>
                        </div>
                        <h1 class="st-hero-id">Timeline Serah Terima</h1>
                        <p class="st-hero-title">{{ $penyewaan->painting->title }} &mdash; {{ $penyewaan->painting->artist ?? '' }}</p>
                        <div class="st-status-badge {{ $sc['class'] }}">
                            <span class="st-status-dot"></span>
                            {{ $sc['label'] }}
                        </div>
                    </div>
                    <div class="st-hero-actions">
                        <a href="{{ $isPengelola
                                ? route('pengelola.penyewaan.handover.show', $penyewaan)
                                : route('penyewaan.requests.handover.show', $penyewaan) }}"
                           class="st-hero-btn st-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali ke Serah Terima
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="tr-content">

            {{-- ── DOC INFO CARD ── --}}
            <div class="st-card">
                <div class="st-card-body">
                    <div class="tr-doc-grid">
                        <div>
                            <div style="font-size:.67rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#94a3b8;margin-bottom:.2rem;">Nomor Dokumen</div>
                            <div class="tr-doc-number">{{ $serahTerima->document_number }}</div>
                            <div class="tr-doc-sub">{{ $penyewaan->painting->title }} · {{ $penyewaan->contact_name ?? $penyewaan->nama_instansi }}</div>
                            <div class="tr-doc-meta">Diperbarui {{ $serahTerima->updated_at->format('d M Y, H:i') }}</div>
                        </div>
                        <div>
                            <span class="st-status-badge {{ $sc['class'] }}" style="margin-top:0;">
                                <span class="st-status-dot"></span>
                                {{ $sc['label'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── TAHAPAN ── --}}
            <div class="st-card">
                <div class="st-card-header">
                    <div class="st-card-header-accent"></div>
                    <h3>Tahapan Proses</h3>
                </div>
                <div class="st-card-body">
                    <div class="tr-stages">
                        <div class="tr-stage-line"></div>
                        <div class="tr-stage-list">
                            @foreach($stages as $stage)
                                @php
                                    $isDone    = $stage['status'] === 'done';
                                    $isCurrent = $stage['status'] === 'current';
                                    $isPending = $stage['status'] === 'pending';
                                    $stateClass = $stage['status']; // done / current / pending
                                @endphp
                                <div class="tr-stage-item">
                                    {{-- Dot --}}
                                    <div class="tr-dot {{ $stateClass }}">
                                        @if($isDone)
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="#fff"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        @elseif($isCurrent)
                                            <span style="color:#fff;font-size:.75rem;">{{ $stage['step'] }}</span>
                                        @else
                                            <span style="font-size:1rem;">{{ $stage['icon'] }}</span>
                                        @endif
                                    </div>

                                    {{-- Card --}}
                                    <div class="tr-stage-card {{ $stateClass }}">
                                        <div class="tr-stage-header">
                                            <div style="flex:1;min-width:0;">
                                                <div class="tr-stage-eyebrow {{ $stateClass }}">
                                                    Tahap {{ $stage['step'] }}
                                                    @if($isCurrent)
                                                        <span class="tr-current-pill">Saat ini</span>
                                                    @endif
                                                </div>
                                                <p class="tr-stage-title {{ $stateClass }}">{{ $stage['title'] }}</p>
                                                <p class="tr-stage-desc {{ $stateClass }}">{{ $stage['desc'] }}</p>
                                            </div>
                                            @if($stage['timestamp'])
                                                <div class="tr-stage-ts">
                                                    <div class="date">{{ $stage['timestamp']->format('d M Y') }}</div>
                                                    <div class="time">{{ $stage['timestamp']->format('H:i') }}</div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Detail chips --}}
                                        @if($isDone || $isCurrent)
                                            @if($stage['step'] == 13 && $serahTerima->delivery_officer)
                                                <div class="tr-chips">
                                                    <span class="tr-chip">📋 <strong>Metode:</strong>&nbsp;{{ $serahTerima->delivery_method ?? '-' }}</span>
                                                    <span class="tr-chip">👤 <strong>Petugas:</strong>&nbsp;{{ $serahTerima->delivery_officer }}</span>
                                                    @if($serahTerima->delivery_tracking_number)
                                                        <span class="tr-chip" style="font-family:monospace;">📬&nbsp;{{ $serahTerima->delivery_tracking_number }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($stage['step'] == 14 && $serahTerima->shipped_at)
                                                <div class="tr-chips">
                                                    <span class="tr-chip">🗓 Dikirim <strong>{{ $serahTerima->shipped_at->format('d M Y H:i') }}</strong></span>
                                                    @if($serahTerima->delivery_tracking_number)
                                                        <span class="tr-chip" style="font-family:monospace;">Resi: {{ $serahTerima->delivery_tracking_number }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($stage['step'] == 15 && $serahTerima->confirmed_received_at)
                                                <div class="tr-chips">
                                                    <span class="tr-chip">🗓 Dikonfirmasi <strong>{{ $serahTerima->confirmed_received_at->format('d M Y H:i') }}</strong></span>
                                                    @if($serahTerima->recipient_name)
                                                        <span class="tr-chip">👤 {{ $serahTerima->recipient_name }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($stage['step'] == '16–17' && $serahTerima->tenant_uploaded_at)
                                                @php $checklistCount = collect(['checklist_frame_safe','checklist_no_tears','checklist_color_normal','checklist_glass_safe','checklist_no_mold','checklist_matches_documentation'])->filter(fn($f) => $serahTerima->$f)->count(); @endphp
                                                <div class="tr-chips">
                                                    <span class="tr-chip">🗓 Upload <strong>{{ $serahTerima->tenant_uploaded_at->format('d M Y H:i') }}</strong></span>
                                                    <span class="tr-chip">✓ {{ $checklistCount }}/6 checklist</span>
                                                </div>
                                            @endif
                                            @if($stage['step'] == 18 && $serahTerima->validated_at)
                                                <div class="tr-chips">
                                                    <span class="tr-chip">👤 <strong>{{ $serahTerima->validated_by ?? 'Pengelola' }}</strong></span>
                                                    <span class="tr-chip">🗓 {{ $serahTerima->validated_at->format('d M Y H:i') }}</span>
                                                    @if($serahTerima->validation_notes)
                                                        <span class="tr-chip">📝 {{ $serahTerima->validation_notes }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($stage['step'] == 19 && $penyewaan->status === 'aktif')
                                                @php $sisaHari = now()->diffInDays($penyewaan->end_date, false); @endphp
                                                <div class="tr-chips">
                                                    <span class="tr-chip" style="{{ $sisaHari <= 3 ? 'background:#fef2f2;border-color:#fecaca;color:#dc2626;' : '' }}">
                                                        @if($sisaHari <= 0) ⏰ Masa sewa berakhir
                                                        @else ⏱ Sisa <strong>{{ $sisaHari }} hari</strong> ({{ $penyewaan->end_date->format('d M Y') }})
                                                        @endif
                                                    </span>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── LOG AKTIVITAS ── --}}
            <div class="st-card">
                <div class="st-card-header">
                    <div class="st-card-header-accent"></div>
                    <h3>Log Aktivitas</h3>
                </div>
                <div class="st-card-body">
                    @if($serahTerima->logs->isEmpty())
                        <div style="text-align:center;padding:2rem 0;color:#94a3b8;font-size:.83rem;">
                            <div style="font-size:2rem;margin-bottom:.75rem;">📋</div>
                            Belum ada aktivitas tercatat.
                        </div>
                    @else
                        <div style="display:flex;flex-direction:column;gap:.625rem;">
                            @foreach($serahTerima->logs as $log)
                                <div class="tr-log-item">
                                    <div class="tr-log-dot"></div>
                                    <div style="flex:1;min-width:0;">
                                        <div class="tr-log-title">{{ ucfirst(str_replace('_', ' ', $log->status)) }}</div>
                                        <div class="tr-log-msg">{{ $log->message }}</div>
                                        <div class="tr-log-meta">{{ $log->created_at->format('d M Y, H:i') }}</div>
                                    </div>
                                    <div class="tr-log-by">{{ $log->performed_by }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>