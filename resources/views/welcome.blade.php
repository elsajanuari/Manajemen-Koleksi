<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Museum MK Lesmana</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #ffffff;
            color: #1a1a2e;
        }

        /* ===== HERO SECTION ===== */
        .hero {
            display: flex;
            padding: 60px 60px 80px;
            background: #f8faff;
            align-items: center;
            gap: 60px;
            flex-wrap: wrap;
            min-height: 500px;
        }

        .hero-text {
            flex: 1;
            min-width: 280px;
        }

        .hero-text .badge-top {
            display: inline-block;
            background: #eff6ff;
            color: #2563eb;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 16px;
            border-radius: 20px;
            margin-bottom: 16px;
            letter-spacing: 0.5px;
        }

        .hero-text h1 {
            font-size: 56px;
            font-weight: 800;
            margin: 0;
            line-height: 1.15;
            color: #1a1a2e;
            letter-spacing: -1.5px;
        }

        .hero-text h1 span {
            color: #2563eb;
        }

        .hero-text p {
            margin-top: 20px;
            color: #4a5568;
            line-height: 1.8;
            font-size: 1.05rem;
            max-width: 540px;
        }

        .hero-text .cta-group {
            margin-top: 32px;
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .hero-text .btn-hero {
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-primary {
            background: #2563eb;
            color: white;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(37, 99, 235, 0.4);
            background: #1d4ed8;
        }

        .btn-hero-secondary {
            background: white;
            color: #1a1a2e;
            border: 2px solid #e2e8f0;
        }

        .btn-hero-secondary:hover {
            border-color: #2563eb;
            color: #2563eb;
            transform: translateY(-3px);
        }

        .hero-image {
            flex: 1;
            min-width: 280px;
            height: 420px;
            border-radius: 20px;
            box-shadow: 0 20px 50px -12px rgba(0,0,0,0.12);
            overflow: hidden;
            position: relative;
            background: #e8edf5;
            transition: transform 0.3s ease;
        }

        .hero-image:hover {
            transform: scale(1.01);
        }

        .hero-image .slide {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
            animation: fadeIn 0.8s ease;
        }

        .hero-image .slide.active {
            display: block;
        }

        .hero-image .slide-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px 30px;
            background: linear-gradient(transparent, rgba(0,0,0,0.5));
            color: white;
        }

        .hero-image .slide-overlay h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .hero-image .slide-overlay p {
            font-size: 13px;
            opacity: 0.9;
        }

        .hero-image .slide-indicators {
            position: absolute;
            bottom: 16px;
            right: 20px;
            display: flex;
            gap: 8px;
            z-index: 5;
        }

        .hero-image .slide-indicators .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255,255,255,0.4);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .hero-image .slide-indicators .dot.active {
            background: white;
            width: 28px;
            border-radius: 10px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(1.02); }
            to { opacity: 1; transform: scale(1); }
        }

        /* ===== STATS SECTION ===== */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 30px;
            padding: 50px 60px;
            background: #ffffff;
            border-top: 1px solid #e8edf5;
            border-bottom: 1px solid #e8edf5;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item .number {
            font-size: 32px;
            font-weight: 800;
            color: #2563eb;
        }

        .stat-item .label {
            color: #718096;
            font-size: 14px;
            font-weight: 500;
            margin-top: 4px;
        }

        /* ===== KNOW MORE ===== */
        .know-more {
            text-align: center;
            padding: 30px 40px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 1px;
            background: white;
            margin: 30px auto 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 14px;
            width: 90%;
            max-width: 320px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.04);
            border: 1px solid #e8edf5;
            color: #4a5568;
        }

        .know-more:hover {
            background: #2563eb;
            color: white;
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.25);
            border-color: #2563eb;
        }

        .know-more i {
            margin-left: 10px;
            transition: transform 0.3s ease;
        }

        .know-more:hover i {
            transform: translateX(5px);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .hero { padding: 40px 30px 60px; gap: 40px; }
            .hero-text h1 { font-size: 44px; }
            .stats { padding: 40px 30px; }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 30px 20px 50px;
                flex-direction: column;
                text-align: center;
            }
            .hero-text h1 { font-size: 36px; }
            .hero-text p { max-width: 100%; }
            .hero-text .cta-group { justify-content: center; }
            .hero-image { height: 280px; }
            .stats { grid-template-columns: repeat(2, 1fr); padding: 30px 20px; gap: 20px; }
            .stat-item .number { font-size: 24px; }
        }

        @media (max-width: 480px) {
            .hero-text h1 { font-size: 28px; }
            .hero-image { height: 220px; }
            .stats { grid-template-columns: 1fr 1fr; gap: 14px; padding: 20px 16px; }
            .stat-item .number { font-size: 20px; }
            .know-more { padding: 18px 20px; font-size: 14px; max-width: 100%; }
            .btn-hero-primary, .btn-hero-secondary { padding: 10px 20px; font-size: 13px; }
        }
    </style>
</head>

<body>

@guest
    @include('layouts.navigation_public')
@else
    @include('layouts.navigation')
@endguest

{{-- HERO SECTION --}}
<section class="hero">
    <div class="hero-text">
        <div class="badge-top">
            <i class="fas fa-star" style="margin-right: 6px;"></i> Koleksi Terbaik Nusantara
        </div>
        <h1>
            MUSEUM <br>
            <span>MK LESMANA</span>
        </h1>
        <p>
            Museum MK Lesmana menghadirkan pengalaman edukatif dan historis melalui koleksi berharga 
            yang dapat dinikmati secara langsung oleh masyarakat. Temukan warisan budaya dan 
            kisah menarik di setiap sudut museum kami.
        </p>
        <div class="cta-group">
            <a href="{{ route('gallery') }}" class="btn-hero btn-hero-primary">
                <i class="fas fa-arrow-right"></i> Jelajahi Koleksi
            </a>
        </div>
    </div>

    <div class="hero-image" id="heroSlider">
        @php
            $koleksis = \App\Models\Koleksi::orderBy('created_at', 'desc')->limit(20)->get();
        @endphp

        @if($koleksis->isNotEmpty())
            @foreach($koleksis as $index => $koleksi)
                <img src="{{ asset('storage/' . $koleksi->foto) }}" 
                     alt="{{ $koleksi->nama }}" 
                     class="slide {{ $index === 0 ? 'active' : '' }}"
                     data-title="{{ $koleksi->nama }}"
                     data-artist="{{ $koleksi->seniman ?? 'Koleksi Museum' }}">
            @endforeach

            <div class="slide-overlay" id="slideOverlay">
                <h3 id="slideTitle">{{ $koleksis->first()->nama }}</h3>
                <p id="slideArtist">{{ $koleksis->first()->seniman ?? 'Koleksi Museum' }}</p>
            </div>

            <div class="slide-indicators" id="slideIndicators">
                @foreach($koleksis as $index => $koleksi)
                    <span class="dot {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}"></span>
                @endforeach
            </div>
        @else
            <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#94a3b8;font-size:1rem;flex-direction:column;gap:12px;">
                <i class="fas fa-images" style="font-size:3rem;opacity:0.5;"></i>
                <span>Koleksi akan segera hadir</span>
            </div>
        @endif
    </div>
</section>

{{-- STATS --}}
<div class="stats">
    <div class="stat-item">
        <div class="number">{{ \App\Models\Koleksi::count() }}</div>
        <div class="label">Total Koleksi</div>
    </div>
    <div class="stat-item">
        <div class="number">{{ \App\Models\Koleksi::where('kategori', 'lukisan')->count() }}</div>
        <div class="label">Koleksi Lukisan</div>
    </div>
    <div class="stat-item">
        <div class="number">{{ \App\Models\Koleksi::where('kategori', 'buku')->count() }}</div>
        <div class="label">Koleksi Buku</div>
    </div>
    <div class="stat-item">
        <div class="number">{{ \App\Models\Koleksi::where('status_sewa', '!=', 'tidak')->count() }}</div>
        <div class="label">Tersedia Sewa</div>
    </div>
    <div class="stat-item">
        <div class="number">{{ \App\Models\Koleksi::whereIn('status_sewa', ['beli', 'sewa_beli'])->count() }}</div>
        <div class="label">Tersedia Beli</div>
    </div>
</div>

{{-- ===== TIKET TERSEDIA HARI INI ===== --}}
<section style="padding: 60px 60px; background: #ffffff;">
    <div style="text-align:center; margin-bottom: 36px;">
        <div style="display:inline-block;background:#eff6ff;color:#2563eb;font-size:12px;font-weight:600;padding:4px 16px;border-radius:20px;margin-bottom:12px;letter-spacing:0.5px;">
            <i class="fas fa-calendar-day" style="margin-right:6px;"></i>
            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
        </div>
        <h2 style="font-size:32px;font-weight:700;color:#1a1a2e;margin-bottom:8px;">
            Tiket <span style="color:#2563eb;">Tersedia Hari Ini</span>
        </h2>
        <p style="color:#4a5568;font-size:15px;">Pesan sekarang untuk kunjungan hari ini</p>
    </div>

    @php
        $today = now()->timezone('Asia/Jakarta')->toDateString();
        $todayTickets = collect();

        foreach ($tickets as $group => $groupTickets) {
            foreach ($groupTickets as $ticket) {
                $hasQuotaToday = $ticket->quotas->filter(function($quota) use ($today) {
                    $tanggalQuota = \Carbon\Carbon::parse($quota->tanggal)
                        ->timezone('Asia/Jakarta')
                        ->toDateString();
                    $masihAda = $quota->kuota_max > $quota->kuota_terjual;
                    return $tanggalQuota === $today && $masihAda;
                })->isNotEmpty();

                if ($hasQuotaToday && !$ticket->isExpired()) {
                    $todayTickets->push($ticket);
                }
            }
        }

        $todayTickets = $todayTickets->unique('id');
    @endphp

    @if($todayTickets->isEmpty())
        <div style="text-align:center;padding:48px 24px;border:2px dashed #e2e8f0;border-radius:16px;background:#f8faff;">
            <i class="fas fa-ticket-alt" style="font-size:40px;color:#cbd5e0;margin-bottom:16px;display:block;"></i>
            <p style="font-weight:600;color:#4a5568;font-size:16px;margin-bottom:4px;">Tidak Ada Tiket Hari Ini</p>
            <p style="color:#94a3b8;font-size:14px;">Silakan kunjungi halaman tiket untuk melihat jadwal lainnya.</p>
            <a href="{{ route('tiket.index') }}" style="display:inline-block;margin-top:20px;padding:10px 24px;background:#2563eb;color:white;border-radius:10px;text-decoration:none;font-weight:600;font-size:14px;">
                Lihat Semua Tiket
            </a>
        </div>
    @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;">
            @foreach($todayTickets as $ticket)
                @php
                    $quotaToday = $ticket->quotas->filter(function($quota) use ($today) {
                        return \Carbon\Carbon::parse($quota->tanggal)
                            ->timezone('Asia/Jakarta')
                            ->toDateString() === $today;
                    })->first();
                    $sisaKuota = $quotaToday
                        ? ($quotaToday->kuota_max - $quotaToday->kuota_terjual)
                        : 0;
                @endphp
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.06);transition:transform 0.2s,box-shadow 0.2s;"
                     onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,0.1)'"
                     onmouseout="this.style.transform='none';this.style.boxShadow='0 4px 20px rgba(0,0,0,0.06)'">

                    {{-- Gambar --}}
                    <div style="height:160px;background:#e8edf5;overflow:hidden;position:relative;">
                        @if($ticket->gambar)
                            <img src="{{ asset('storage/gambar/' . $ticket->gambar) }}"
                                 alt="{{ $ticket->nama }}"
                                 style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-ticket-alt" style="font-size:40px;color:#cbd5e0;"></i>
                            </div>
                        @endif

                        {{-- Badge kategori --}}
                        <div style="position:absolute;top:10px;left:10px;background:rgba(37,99,235,0.9);color:white;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;">
                            {{ ucwords($ticket->kategori) }}
                        </div>
                    </div>

                    {{-- Konten --}}
                    <div style="padding:16px;">
                        <h3 style="font-size:15px;font-weight:700;color:#1a1a2e;margin-bottom:4px;line-height:1.3;">
                            {{ $ticket->nama }}
                        </h3>
                        @if($ticket->sub_nama)
                            <p style="font-size:12px;color:#64748b;margin-bottom:12px;">{{ $ticket->sub_nama }}</p>
                        @endif

                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                            <div>
                                <p style="font-size:10px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Harga</p>
                                <p style="font-size:18px;font-weight:800;color:#1a1a2e;">Rp {{ number_format($ticket->harga ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div style="text-align:right;">
                                <p style="font-size:10px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Tanggal</p>
                                <p style="font-size:13px;font-weight:600;color:#2563eb;">Hari Ini</p>
                            </div>
                        </div>

                        {{-- Tombol: cek login --}}
                        @auth
                            <a href="{{ route('tiket.show', $ticket->id) }}"
                               style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:10px;background:#2563eb;color:white;border-radius:10px;text-decoration:none;font-weight:600;font-size:14px;transition:background 0.2s;"
                               onmouseover="this.style.background='#1d4ed8'"
                               onmouseout="this.style.background='#2563eb'">
                                <i class="fas fa-ticket-alt"></i> Pilih Tiket
                            </a>
                        @else
                            <a href="{{ route('login') }}?redirect={{ urlencode(route('tiket.show', $ticket->id)) }}"
                               style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:10px;background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;border-radius:10px;text-decoration:none;font-weight:600;font-size:14px;transition:all 0.2s;"
                               onmouseover="this.style.background='#e2e8f0'"
                               onmouseout="this.style.background='#f1f5f9'">
                                <i class="fas fa-lock"></i> Login untuk Pesan
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Tombol lihat semua --}}
        <div style="text-align:center;margin-top:36px;">
            <a href="{{ route('tiket.index') }}"
               style="display:inline-flex;align-items:center;gap:10px;padding:14px 32px;border:2px solid #2563eb;color:#2563eb;border-radius:12px;text-decoration:none;font-weight:600;font-size:15px;transition:all 0.3s;"
               onmouseover="this.style.background='#2563eb';this.style.color='white'"
               onmouseout="this.style.background='transparent';this.style.color='#2563eb'">
                Lihat Semua Tiket <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    @endif
</section>


{{-- SECTION PETA LOKASI --}}
<section style="padding: 60px 60px; background: #f8faff;">
    <div style="text-align:center; margin-bottom: 32px;">
        <h2 style="font-size: 32px; font-weight: 700; color: #1a1a2e;">
            Lokasi <span style="color: #2563eb;">Museum MK Lesmana</span>
        </h2>
        <p style="color: #4a5568; margin-top: 8px;">
            Kunjungi kami langsung untuk pengalaman terbaik
        </p>
    </div>

    <div style="display: flex; gap: 40px; flex-wrap: wrap; align-items: flex-start;">
        
        {{-- Info Kontak --}}
        <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 20px;">
            <div style="display:flex; gap: 14px; align-items: flex-start;">
                <div style="width:40px;height:40px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-map-marker-alt" style="color:#2563eb;"></i>
                </div>
                <div>
                    <div style="font-weight:600;font-size:14px;margin-bottom:4px;">Alamat</div>
                    <div style="color:#4a5568;font-size:13px;line-height:1.6;">
                        Kp. Legok Barong, RT/RW 10/05, Pusaka Mulya, Kec. Kiarapedes<br>
                        Kabupaten Purwakarta, Provinsi Jawa Barat, Indonesia
                    </div>
                </div>
            </div>

            <div style="display:flex; gap: 14px; align-items: flex-start;">
                <div style="width:40px;height:40px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-clock" style="color:#2563eb;"></i>
                </div>
                <div>
                    <div style="font-weight:600;font-size:14px;margin-bottom:4px;">Jam Operasional</div>
                    <div style="color:#4a5568;font-size:13px;line-height:1.6;">
                        08.00 – 17.00 WIB<br>
                    </div>
                </div>
            </div>

            <div style="display:flex; gap: 14px; align-items: flex-start;">
                <div style="width:40px;height:40px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-phone" style="color:#2563eb;"></i>
                </div>
                <div>
                    <div style="font-weight:600;font-size:14px;margin-bottom:4px;">Kontak</div>
                    <div class="flex flex-col gap-2">
                        {{-- WhatsApp --}}
                        <a href="https://wa.me/6281389689900?text=Halo%20Museum%20MK%20Lesmana%2C%20saya%20ingin%20bertanya%20tentang..." 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="flex items-center gap-2 text-sm text-gray-600 hover:text-green-600 transition-colors duration-200 group">
                            <svg class="w-4 h-4 text-green-500 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            <span>0813-8968-9900</span>
                            <span class="text-xs text-gray-400 group-hover:text-green-500 transition-colors">(WhatsApp)</span>
                        </a>

                        {{-- Telepon --}}
                        <a href="tel:081389689900" 
                        class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 transition-colors duration-200 group">
                            <svg class="w-4 h-4 text-blue-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>0813-8968-9900</span>
                            <span class="text-xs text-gray-400 group-hover:text-blue-500 transition-colors">(Telepon)</span>
                        </a>

                        {{-- Email --}}
                        <a href="mailto:museummklesmana61@gmail.com?subject=Halo%20Museum%20MK%20Lesmana&body=Saya%20ingin%20bertanya%20tentang..." 
                        class="flex items-center gap-2 text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200 group">
                            <svg class="w-4 h-4 text-purple-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>museummklesmana61@gmail.com</span>
                            <span class="text-xs text-gray-400 group-hover:text-purple-500 transition-colors">(Email)</span>
                        </a>
                    </div>
                </div>
            </div>

            <div style="display:flex; gap: 14px; align-items: flex-start;">
                <div style="width:40px;height:40px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-share-alt" style="color:#2563eb;"></i>
                </div>
                <div>
                    <div style="font-weight:600;font-size:14px;margin-bottom:8px;">Sosial Media</div>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        
                        <a href="https://www.facebook.com/share/1ECj9pFP3T/" target="_blank"
                        style="display:inline-flex;align-items:center;gap:8px;color:#1877F2;text-decoration:none;font-size:13px;font-weight:500;">
                            <i class="fab fa-facebook" style="font-size:16px;width:18px;text-align:center;"></i>
                            Facebook
                        </a>

                        <a href="https://www.instagram.com/galery_mklesmana?igsh=MTFwenprdXR0bGw3Mw==" target="_blank"
                        style="display:inline-flex;align-items:center;gap:8px;color:#E1306C;text-decoration:none;font-size:13px;font-weight:500;">
                            <i class="fab fa-instagram" style="font-size:16px;width:18px;text-align:center;"></i>
                            Instagram
                        </a>

                        <a href="https://www.youtube.com/@rumahbudayapurwakarta" target="_blank"
                        style="display:inline-flex;align-items:center;gap:8px;color:#FF0000;text-decoration:none;font-size:13px;font-weight:500;">
                            <i class="fab fa-youtube" style="font-size:16px;width:18px;text-align:center;"></i>
                            YouTube
                        </a>

                    </div>
                </div>
            </div>

            <a href="https://maps.app.goo.gl/vMHSPNJ26JRQKsRGA" 
               target="_blank"
               style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#2563eb;color:white;border-radius:10px;text-decoration:none;font-weight:600;font-size:14px;width:fit-content;transition:all .3s;">
                <i class="fas fa-directions"></i> Petunjuk Arah
            </a>
        </div>

        {{-- Google Maps Embed --}}
        <div style="flex: 2; min-width: 280px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1); height: 380px;">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.7224754925987!2d107.57646827499433!3d-6.681262093313968!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6919c643705187%3A0x387898a45b1206cb!2sMuseum%20Mk.lesmana!5e0!3m2!1sid!2sid!4v1783042760752!5m2!1sid!2sid"
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const overlayTitle = document.getElementById('slideTitle');
    const overlayArtist = document.getElementById('slideArtist');
    let currentIndex = 0;
    let interval;

    if (slides.length === 0) return;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });

        const activeSlide = slides[index];
        if (activeSlide) {
            overlayTitle.textContent = activeSlide.dataset.title || 'Koleksi Museum';
            overlayArtist.textContent = activeSlide.dataset.artist || 'Koleksi Museum';
        }
        currentIndex = index;
    }

    function nextSlide() {
        const next = (currentIndex + 1) % slides.length;
        showSlide(next);
    }

    dots.forEach(dot => {
        dot.addEventListener('click', function() {
            const index = parseInt(this.dataset.index);
            if (index !== currentIndex) {
                showSlide(index);
                resetInterval();
            }
        });
    });

    function resetInterval() {
        clearInterval(interval);
        interval = setInterval(nextSlide, 5000);
    }

    if (slides.length > 1) {
        interval = setInterval(nextSlide, 5000);

        const slider = document.getElementById('heroSlider');
        if (slider) {
            slider.addEventListener('mouseenter', () => clearInterval(interval));
            slider.addEventListener('mouseleave', () => {
                interval = setInterval(nextSlide, 5000);
            });
        }
    }
});
</script>
</body>
</html>