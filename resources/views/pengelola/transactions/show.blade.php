<x-app-layout>
    <x-slot name="header">{{-- kosong, hero di dalam --}}</x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    @php
        $status = $model->status ?? null;

        $statusBadgeClass = match($status) {
            'menunggu_verifikasi'            => 'st-amber',
            'menunggu_pembayaran'            => 'st-orange',
            'pembayaran_berhasil'            => 'st-emerald',
            'siap_diserahkan'                => 'st-blue',
            'dalam_pengiriman'               => 'st-sky',
            'menunggu_dokumen_serah_terima'  => 'st-slate',
            'menunggu_validasi_serah_terima' => 'st-amber',
            'diterima_pembeli'               => 'st-indigo',
            'selesai'                        => 'st-green',
            'ditolak'                        => 'st-red',
            'dibatalkan'                     => 'st-slate',
            default                          => 'st-slate',
        };

        $statusLabel = $status ? ucfirst(str_replace('_',' ', $status)) : '';

        $steps = [
            1 => ['label' => 'Verifikasi', 'icon' => '🔍'],
            2 => ['label' => 'Pembayaran', 'icon' => '💳'],
            3 => ['label' => 'Disiapkan',  'icon' => '📦'],
            4 => ['label' => 'Dikirim',    'icon' => '🚚'],
            5 => ['label' => 'Diterima',   'icon' => '✅'],
            6 => ['label' => 'Selesai',    'icon' => '🎉'],
        ];

        $progressStep = 0;
        if($status) {
            $mapping = [
                'menunggu_verifikasi' => 1,
                'menunggu_pembayaran' => 2,
                'pembayaran_berhasil' => 3,
                'siap_diserahkan'     => 3,
                'dalam_pengiriman'    => 4,
                'menunggu_dokumen_serah_terima' => 5,
                'menunggu_validasi_serah_terima' => 5,
                'diterima_pembeli' => 5,
                'selesai' => 6,
            ];
            $progressStep = $mapping[$status] ?? 0;
        }
    @endphp

    <style>
        :root { --navy: #0b1d35; --blue:#1d4ed8; --sky:#38bdf8; --cream:#f2f5f9; --border:#e2e8f0; --white:#fff; --slate:#64748b; }
        *{box-sizing:border-box}
        .ps-root{font-family:'DM Sans',sans-serif;background:var(--cream);min-height:100vh;padding-bottom:4rem}
        .ps-hero{background:linear-gradient(140deg,#0b1d35 0%,#142744 55%,#1c3a68 100%);padding:2.25rem 0;position:relative;overflow:hidden}
        .ps-hero::before{content:'';position:absolute;top:-60px;right:-80px;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(56,189,248,.07) 0%,transparent 70%);pointer-events:none}
        .ps-hero-inner{max-width:1100px;margin:0 auto;padding:0 2rem;position:relative;z-index:1}
        .ps-hero-top{display:flex;align-items:flex-start;justify-content:space-between;gap:1.5rem;flex-wrap:wrap}
        .ps-breadcrumb{display:flex;align-items:center;gap:.45rem;margin-bottom:.85rem}
        .ps-breadcrumb a{color:rgba(255,255,255,.45);font-size:.75rem;font-weight:500;text-decoration:none}
        .ps-breadcrumb-sep{color:rgba(255,255,255,.25);font-size:.7rem}
        .ps-breadcrumb-cur{color:rgba(255,255,255,.7);font-size:.75rem;font-weight:600;}
        .ps-hero-id{font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:700;color:#fff;line-height:1.1;margin:0 0 .3rem}
        .ps-hero-title{font-size:.88rem;color:rgba(255,255,255,.55);margin:0}
        .ps-hero-actions{display:flex;gap:.6rem;flex-wrap:wrap;align-items:flex-start;padding-top:.25rem;}
        .ps-hero-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.6rem 1.2rem;border-radius:.875rem;font-size:.8rem;font-weight:600;font-family:'DM Sans',sans-serif;text-decoration:none;transition:all .18s;border:none;cursor:pointer;white-space:nowrap;}
        .ps-hero-btn svg{width:13px;height:13px;}
        .ps-hero-btn-back{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.85);}
        .ps-hero-btn-back:hover{background:rgba(255,255,255,.18);}
        .ps-hero-btn-st{background:rgba(56,189,248,.15);border:1px solid rgba(56,189,248,.3);color:var(--sky);}
        .ps-hero-btn-st:hover{background:rgba(56,189,248,.25);}
        .ps-status-badge{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem 1rem;border-radius:99px;font-size:.72rem;font-weight:700;margin-top:.75rem}
        .ps-status-dot{width:6px;height:6px;border-radius:50%}
        .st-amber{background:rgba(251,191,36,.15);border:1px solid rgba(251,191,36,.3);color:#fbbf24}.st-emerald{background:rgba(52,211,153,.15);border:1px solid rgba(52,211,153,.3);color:#34d399}.st-blue{background:rgba(96,165,250,.15);border:1px solid rgba(96,165,250,.3);color:#60a5fa}.st-sky{background:rgba(56,189,248,.15);border:1px solid rgba(56,189,248,.3);color:var(--sky)}
        .ps-content{max-width:1100px;margin:0 auto;padding:1.75rem 2rem 0;display:grid;gap:1.25rem}
        .ps-card{background:var(--white);border:1.5px solid var(--border);border-radius:1.5rem;box-shadow:0 4px 24px rgba(11,29,53,.05);overflow:hidden}
        .ps-card-header{padding:1.1rem 1.5rem;border-bottom:1.5px solid #f0f4f8;display:flex;align-items:center;gap:.55rem}
        .ps-card-body{padding:1.5rem}
        .ps-card-stack{display:grid;gap:1.25rem}
    </style>

    <div class="ps-root">
        <div class="ps-hero">
            <div class="ps-hero-inner">
                <div class="ps-hero-top">
                    <div>
                        <div class="ps-breadcrumb">
                            <a href="{{ route('pengelola.pembelian.index') }}">Daftar Pengajuan</a>
                            <span class="ps-breadcrumb-sep">/</span>
                            <span class="ps-breadcrumb-cur">Detail Transaksi</span>
                        </div>
                        <h1 class="ps-hero-id">{{ $type === 'pembelian' ? 'BLI-'.str_pad($model->id,5,'0',STR_PAD_LEFT) : 'SP-'.str_pad($model->id,5,'0',STR_PAD_LEFT) }}</h1>
                        <p class="ps-hero-title">{{ $model->painting->title ?? '-' }} &mdash; {{ $model->painting->artist ?? '' }}</p>
                        @if($status)
                            <div class="ps-status-badge {{ $statusBadgeClass }}">
                                <span class="ps-status-dot"></span>
                                {{ $statusLabel }}
                            </div>
                        @endif
                    </div>
                    <div class="ps-hero-actions">
                        <a href="{{ route('pengelola.pembelian.transactions.index') }}" class="ps-hero-btn ps-hero-btn-back">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Kembali
                        </a>
                        @if($type === 'pembelian' && in_array($status, ['pembayaran_berhasil','siap_diserahkan','dalam_pengiriman','diterima_pembeli','menunggu_validasi_serah_terima','selesai']))
                            <a href="{{ route('pengelola.pembelian.serah-terima', $model) }}" class="ps-hero-btn ps-hero-btn-st">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                                Serah Terima
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="ps-content">
            @if(!in_array($status, ['ditolak','dibatalkan']))
            <div class="ps-card">
                <div class="ps-card-header">
                    <div class="ps-card-header-accent"></div>
                    <h3>Progress Transaksi</h3>
                </div>
                <div class="ps-card-body">
                    <div style="display:flex;align-items:flex-start;flex-wrap:wrap;">
                        @foreach($steps as $n => $step)
                            @php
                                $isDone    = $progressStep > $n;
                                $isCurrent = $progressStep === $n;
                                $state     = $isDone ? 'done' : ($isCurrent ? 'active' : 'pending');
                            @endphp
                            <div style="{{ $loop->last ? '' : 'flex:1;' }} display:flex; flex-direction:column; align-items:center;">
                                <div style="display:flex;align-items:center;width:100%;">
                                    <div style="display:flex;flex-direction:column;align-items:center;gap:.4rem;width:100%;">
                                        <div class="ps-step-circle {{ $state }}">
                                            @if($isDone)
                                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            @else
                                                {{ $n }}
                                            @endif
                                        </div>
                                        <span class="ps-step-label {{ $state }}">{{ $step['label'] }}</span>
                                    </div>
                                    @if(!$loop->last)
                                        <div class="ps-step-line {{ $isDone ? 'done' : 'pending' }}"></div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            <div class="ps-card-stack">
                <div class="ps-card">
                    <div class="ps-card-header"><div style="width:3px;height:16px;background:linear-gradient(180deg,#1d4ed8,#38bdf8);border-radius:99px;margin-right:.5rem;"></div><h3 style="font-size:.76rem;font-weight:700;text-transform:uppercase;color:var(--navy);margin:0;">Ringkasan Transaksi</h3></div>
                <div class="ps-card-body">
                    @if($type === 'pembelian')
                        <div style="display:grid;grid-template-columns:1fr 340px;gap:1rem;">
                            <div>
                                <h4 style="margin:0 0 .5rem;font-size:1.05rem;font-weight:700;color:var(--navy);">Pembeli & Kontak</h4>
                                <p style="margin:0 .0 1rem;color:#475569;">{{ $model->nama_lengkap }} · {{ $model->email }} · {{ $model->nomor_hp ?? '-' }}</p>
                                <h4 style="margin:0 0 .5rem;font-size:1.05rem;font-weight:700;color:var(--navy);">Alamat</h4>
                                <p style="margin:0 0 1rem;color:#475569;">{{ $model->alamat_pengiriman }}, {{ $model->kota_kabupaten }}, {{ $model->provinsi }}</p>
                                <h4 style="margin:0 0 .5rem;font-size:1.05rem;font-weight:700;color:var(--navy);">Catatan</h4>
                                <p style="margin:0;color:#475569;">{{ $model->catatan_pengelola ?? 'Tidak ada catatan.' }}</p>
                            </div>
                            <div>
                                <div style="background:linear-gradient(135deg,#0b1d35,#142744);border-radius:1rem;padding:1rem;color:#fff;">
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.5rem;">
                                        <div style="font-size:.85rem;opacity:.85;">Harga Beli</div>
                                        <div style="font-weight:700;">Rp {{ number_format($model->harga_beli ?? 0,0,',','.') }}</div>
                                    </div>
                                    <div style="display:flex;justify-content:space-between;align-items:center;">
                                        <div style="font-size:.85rem;opacity:.85;">Total Bayar</div>
                                        <div style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;">Rp {{ number_format($model->total_bayar ?? 0,0,',','.') }}</div>
                                    </div>
                                </div>
                                <div style="margin-top:.8rem;background:#f8fafc;border:1px solid var(--border);border-radius:.75rem;padding:.8rem;">
                                    <div style="font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.4rem;">Informasi Pengiriman</div>
                                    <div style="font-size:.85rem;color:#475569;">Metode: {{ $model->delivery_method ?? '-' }}<br>No. Resi: <span style="font-family:monospace;">{{ $model->delivery_tracking_number ?? '-' }}</span></div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div style="display:grid;grid-template-columns:1fr 340px;gap:1rem;">
                            <div>
                                <h4 style="margin:0 0 .5rem;font-size:1.05rem;font-weight:700;color:var(--navy);">Penyewa & Kontak</h4>
                                <p style="margin:0 .0 1rem;color:#475569;">{{ $model->contact_name ?? $model->nama_instansi }} · {{ $model->contact_email ?? '-' }} · {{ $model->contact_phone ?? '-' }}</p>
                                <h4 style="margin:0 0 .5rem;font-size:1.05rem;font-weight:700;color:var(--navy);">Periode</h4>
                                <p style="margin:0 0 1rem;color:#475569;">{{ $model->start_date?->format('d M Y') ?? '-' }} – {{ $model->end_date?->format('d M Y') ?? '-' }}</p>
                                <h4 style="margin:0 0 .5rem;font-size:1.05rem;font-weight:700;color:var(--navy);">Catatan</h4>
                                <p style="margin:0;color:#475569;">{{ $model->verification_notes ?? 'Tidak ada catatan.' }}</p>
                            </div>
                            <div>
                                <div style="background:linear-gradient(135deg,#0b1d35,#142744);border-radius:1rem;padding:1rem;color:#fff;">
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.5rem;">
                                        <div style="font-size:.85rem;opacity:.85;">Deposit</div>
                                        <div style="font-weight:700;">Rp {{ number_format($model->deposit_amount ?? 0,0,',','.') }}</div>
                                    </div>
                                    <div style="display:flex;justify-content:space-between;align-items:center;">
                                        <div style="font-size:.85rem;opacity:.85;">Status</div>
                                        <div style="font-weight:700;">{{ ucfirst(str_replace('_',' ',$model->status ?? '')) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="ps-card">
                <div class="ps-card-header"><div style="width:3px;height:16px;background:linear-gradient(180deg,#1d4ed8,#38bdf8);border-radius:99px;margin-right:.5rem;"></div><h3 style="font-size:.76rem;font-weight:700;text-transform:uppercase;color:var(--navy);margin:0;">Detail Lengkap</h3></div>
                <div class="ps-card-body">
                    @if($type === 'pembelian')
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">
                            <div style="background:#f8fafc;border:1px solid var(--border);border-radius:.75rem;padding:.8rem;">
                                <div style="font-size:.72rem;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:.35rem;">Nomor Transaksi</div>
                                <div style="font-weight:700;color:var(--navy);">BLI-{{ str_pad($model->id,5,'0',STR_PAD_LEFT) }}</div>
                            </div>
                            <div style="background:#f8fafc;border:1px solid var(--border);border-radius:.75rem;padding:.8rem;">
                                <div style="font-size:.72rem;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:.35rem;">Tanggal Selesai</div>
                                <div style="font-weight:700;color:var(--navy);">{{ $model->completed_at?->format('d M Y H:i') ?? '-' }}</div>
                            </div>
                        </div>
                    @else
                        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;">
                            <div style="background:#f8fafc;border:1px solid var(--border);border-radius:.75rem;padding:.8rem;">
                                <div style="font-size:.72rem;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:.35rem;">Nomor Transaksi</div>
                                <div style="font-weight:700;color:var(--navy);">SP-{{ str_pad($model->id,5,'0',STR_PAD_LEFT) }}</div>
                            </div>
                            <div style="background:#f8fafc;border:1px solid var(--border);border-radius:.75rem;padding:.8rem;">
                                <div style="font-size:.72rem;font-weight:700;color:#94a3b8;text-transform:uppercase;margin-bottom:.35rem;">Tanggal Mulai</div>
                                <div style="font-weight:700;color:var(--navy);">{{ $model->start_date?->format('d M Y') ?? '-' }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>