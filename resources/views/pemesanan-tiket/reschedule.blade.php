<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('pemesanan-tiket.index') }}" class="hover:text-gray-700 transition">Pemesanan</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('pemesanan-tiket.show', $pemesananTiket) }}" class="hover:text-gray-700 transition">Detail</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 font-medium">Reschedule</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-sky-50 to-blue-50 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-sky-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-gray-900">Ubah Tanggal Kunjungan</h1>
                        <p class="text-sm text-gray-500 mt-0.5">Pilih tanggal baru untuk kunjungan Anda</p>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6">

                {{-- Error Summary --}}
                <div id="validation-errors" class="hidden mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="font-medium">Mohon perbaiki kesalahan berikut:</p>
                            <ul id="error-list" class="list-disc list-inside mt-1 space-y-0.5"></ul>
                        </div>
                    </div>
                </div>

                {{-- Error --}}
                @if(session('error'))
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @if($tanggalTersedia->isEmpty())
                    <div class="bg-yellow-50 rounded-xl p-6 border border-yellow-200 text-center">
                        <svg class="w-12 h-12 text-yellow-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-sm font-medium text-yellow-800">Tidak Ada Tanggal Alternatif</p>
                        <p class="text-sm text-yellow-600 mt-1">Saat ini tidak ada tanggal lain yang memenuhi kuota dan batas waktu.</p>
                        <a href="{{ route('pemesanan-tiket.show', $pemesananTiket) }}" 
                           class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-yellow-600 text-white rounded-lg text-sm font-medium hover:bg-yellow-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Detail
                        </a>
                    </div>
                @else
                    <form action="{{ route('pemesanan-tiket.reschedule.store', $pemesananTiket) }}" method="POST" id="form-reschedule" novalidate>
                        @csrf

                        {{-- Layout Grid: Desktop (2 kolom), Mobile (1 kolom) --}}
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                            {{-- LEFT: Informasi Tiket --}}
                            <div class="space-y-4">
                                {{-- Informasi Tiket --}}
                                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 sm:p-5">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Informasi Tiket</p>
                                    <div class="space-y-2.5 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Nama Tiket</span>
                                            <span class="font-medium text-gray-800">{{ $pemesananTiket->ticket->nama_tiket }}</span>
                                        </div>
                                        <div class="flex justify-between border-t border-gray-200 pt-2.5">
                                            <span class="text-gray-500">Jumlah Tiket</span>
                                            <span class="font-medium text-gray-800">{{ $pemesananTiket->jumlah_tiket }} tiket</span>
                                        </div>
                                        <div class="flex justify-between border-t border-gray-200 pt-2.5">
                                            <span class="text-gray-500">Tanggal Saat Ini</span>
                                            <span class="font-medium text-blue-600">{{ $pemesananTiket->tanggal_pemesanan->locale('id')->translatedFormat('d F Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Info Batas Waktu --}}
                                <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3.5 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-blue-700">
                                        Perubahan hanya dapat dilakukan paling lambat <strong>48 jam sebelum tanggal kunjungan</strong> (batas: {{ \Carbon\Carbon::parse($pemesananTiket->tanggal_pemesanan)->subHours(48)->locale('id')->translatedFormat('d F Y H:i') }}).
                                    </p>
                                </div>

                                {{-- Tanggal Dipilih --}}
                                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Tanggal Dipilih</p>
                                    <div id="tanggal_display" class="w-full px-4 py-3 rounded-lg bg-white border border-gray-200 text-gray-500 font-medium text-sm">
                                        Pilih tanggal di kalender...
                                    </div>
                                    <div id="tanggal_warning" style="display:none;" class="mt-2 text-xs text-red-600 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <span id="tanggal_warning_text"></span>
                                    </div>
                                    <input type="hidden" name="tanggal_pemesanan_baru" id="tanggal_pilih" required>
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                    <a href="{{ route('pemesanan-tiket.show', $pemesananTiket) }}" 
                                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                        </svg>
                                        Kembali
                                    </a>
                                    <button type="submit" id="btn-submit" disabled
                                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-gray-300 px-6 py-2.5 text-sm font-medium text-white cursor-not-allowed transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>

                            {{-- RIGHT: Kalender --}}
                            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <span class="text-lg">📅</span>
                                        Pilih Tanggal Baru
                                    </h3>
                                    <div class="flex items-center gap-1">
                                        <button type="button" id="prevMonth" 
                                                class="p-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 disabled:opacity-30 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </button>
                                        <h4 id="currentMonthYear" class="text-sm font-bold text-blue-600 min-w-[140px] text-center"></h4>
                                        <button type="button" id="nextMonth" 
                                                class="p-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 disabled:opacity-30 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Header Hari --}}
                                <div class="grid grid-cols-7 gap-1 mb-2">
                                    <div class="text-center text-[10px] font-bold text-gray-400 py-1">Sen</div>
                                    <div class="text-center text-[10px] font-bold text-gray-400 py-1">Sel</div>
                                    <div class="text-center text-[10px] font-bold text-gray-400 py-1">Rab</div>
                                    <div class="text-center text-[10px] font-bold text-gray-400 py-1">Kam</div>
                                    <div class="text-center text-[10px] font-bold text-gray-400 py-1">Jum</div>
                                    <div class="text-center text-[10px] font-bold text-gray-400 py-1">Sab</div>
                                    <div class="text-center text-[10px] font-bold text-gray-400 py-1">Min</div>
                                </div>

                                @php
                                    $ticket = $pemesananTiket->ticket;
                                    $startDate = \Carbon\Carbon::parse($ticket->tanggal_mulai);
                                    $endDate = $ticket->tanggal_selesai ? \Carbon\Carbon::parse($ticket->tanggal_selesai) : $startDate->copy()->addMonths(3);

                                    $quotas = $ticket->quotas->keyBy(fn($q) => $q->tanggal->toDateString());
                                    $availableDates = $tanggalTersedia->pluck('tanggal')->map(fn($d) => $d->toDateString())->toArray();

                                    $yearsNeeded = range($startDate->year, $endDate->year);
                                    $holidaysData = [];
                                    foreach ($yearsNeeded as $year) {
                                        $holidaysData[$year] = \App\Models\TicketQuota::getIndonesianHolidays($year);
                                    }

                                    $months = [];
                                    $currentCursor = $startDate->copy()->startOfMonth();

                                    while ($currentCursor->lte($endDate)) {
                                        $monthName = $currentCursor->locale('id')->translatedFormat('F Y');
                                        $daysInMonth = [];
                                        $monthHasActionableDay = false;

                                        $padding = $currentCursor->copy()->startOfMonth()->dayOfWeekIso - 1;
                                        for ($i = 0; $i < $padding; $i++) {
                                            $daysInMonth[] = ['status' => 'blank'];
                                        }

                                        $tempDate = $currentCursor->copy()->startOfMonth();
                                        while ($tempDate->month == $currentCursor->month) {
                                            $dateStr = $tempDate->toDateString();
                                            $quota = $quotas[$dateStr] ?? null;
                                            $isHoliday = isset($holidaysData[$tempDate->year][$dateStr]);
                                            $holidayName = $isHoliday ? $holidaysData[$tempDate->year][$dateStr] : null;

                                            if ($tempDate->lt(now()->startOfDay())) {
                                                $status = 'expired';
                                            } elseif (in_array($dateStr, $availableDates)) {
                                                $status = 'available';
                                            } elseif ($quota && $quota->kuota_sisa > 0 && $tempDate->between($startDate, $endDate)) {
                                                $status = 'available';
                                            } elseif ($quota && $quota->kuota_sisa <= 0) {
                                                $status = 'full';
                                            } else {
                                                $status = 'closed';
                                            }

                                            if ($status === 'available') {
                                                $monthHasActionableDay = true;
                                            }

                                            $daysInMonth[] = [
                                                'date' => $tempDate->copy(),
                                                'dayNum' => $tempDate->day,
                                                'status' => $status,
                                                'isHoliday' => $isHoliday,
                                                'holidayName' => $holidayName,
                                                'sisa_kuota' => $quota ? $quota->kuota_sisa : 0,
                                                'kuota' => $quota ? $quota->kuota : 0,
                                            ];
                                            $tempDate->addDay();
                                        }

                                        if ($monthHasActionableDay) {
                                            $months[] = ['name' => $monthName, 'days' => $daysInMonth];
                                        }
                                        $currentCursor->addMonth();
                                    }
                                @endphp

                                {{-- Render Months --}}
                                @foreach($months as $idx => $month)
                                    <div class="month-slide {{ $idx > 0 ? 'hidden' : '' }}" data-month-index="{{ $idx }}">
                                        <div class="grid grid-cols-7 gap-1">
                                            @foreach($month['days'] as $d)
                                                @if($d['status'] === 'blank')
                                                    <div class="h-10 sm:h-12"></div>
                                                @else
                                                    @php
                                                        if ($d['status'] === 'expired') {
                                                            $bgClass = 'bg-gray-100';
                                                            $borderClass = 'border-transparent';
                                                            $textClass = 'text-gray-400';
                                                            $cursor = 'cursor-not-allowed';
                                                        } elseif ($d['status'] === 'available') {
                                                            $bgClass = $d['isHoliday'] ? 'bg-amber-50' : 'bg-white';
                                                            $borderClass = $d['isHoliday'] ? 'border-amber-400' : 'border-gray-200';
                                                            $textClass = $d['isHoliday'] ? 'text-amber-800' : 'text-gray-700';
                                                            $cursor = 'cursor-pointer date-cell hover:border-blue-400 hover:shadow-sm';
                                                        } elseif ($d['status'] === 'full') {
                                                            $bgClass = 'bg-orange-50';
                                                            $borderClass = 'border-orange-200';
                                                            $textClass = 'text-orange-400';
                                                            $cursor = 'cursor-not-allowed';
                                                        } else {
                                                            $bgClass = $d['isHoliday'] ? 'bg-red-100' : 'bg-rose-50';
                                                            $borderClass = $d['isHoliday'] ? 'border-red-300' : 'border-rose-100';
                                                            $textClass = $d['isHoliday'] ? 'text-red-700' : 'text-rose-300';
                                                            $cursor = 'cursor-not-allowed';
                                                        }

                                                        $style = "{$bgClass} {$borderClass} {$textClass} {$cursor}";
                                                    @endphp

                                                    <div class="h-10 sm:h-12 rounded-lg border-2 flex flex-col items-center justify-center transition-all duration-200 {{ $style }} relative group"
                                                        data-date="{{ $d['date']->toDateString() }}"
                                                        data-status="{{ $d['status'] }}"
                                                        data-holiday="{{ $d['isHoliday'] ? 'true' : 'false' }}"
                                                        data-holiday-name="{{ $d['holidayName'] ?? '' }}"
                                                        data-sisa-kuota="{{ $d['sisa_kuota'] }}"
                                                        data-kuota="{{ $d['kuota'] }}">

                                                        <span class="text-sm font-bold">{{ $d['dayNum'] }}</span>

                                                        <span class="text-[6px] sm:text-[8px] font-semibold uppercase leading-tight">
                                                            @if($d['status'] === 'available' && $d['isHoliday'])
                                                                Libur*
                                                            @elseif($d['status'] === 'available')
                                                                Tersedia
                                                            @elseif($d['status'] === 'full')
                                                                Penuh
                                                            @elseif($d['status'] === 'closed' && $d['isHoliday'])
                                                                Libur
                                                            @elseif($d['status'] === 'expired')
                                                                Lewat
                                                            @endif
                                                        </span>

                                                        @if($d['sisa_kuota'] > 0 && $d['sisa_kuota'] <= 3 && $d['status'] === 'available')
                                                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[7px] font-bold rounded-full w-4 h-4 flex items-center justify-center animate-pulse">
                                                                {{ $d['sisa_kuota'] }}
                                                            </span>
                                                        @endif

                                                        @if($d['isHoliday'] && $d['holidayName'])
                                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover:block z-20 whitespace-nowrap pointer-events-none">
                                                                <div class="bg-gray-800 text-white text-[10px] rounded-lg px-2 py-1 shadow-lg">
                                                                    {{ $d['holidayName'] }}
                                                                </div>
                                                                <div class="w-1.5 h-1.5 bg-gray-800 rotate-45 mx-auto -mt-0.5"></div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                {{-- Legend --}}
                                <div class="mt-4 pt-3 border-t border-gray-200 flex flex-wrap gap-2 justify-center text-[8px] sm:text-[9px] font-semibold uppercase tracking-wider">
                                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> <span class="text-gray-600">Tersedia</span></div>
                                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span> <span class="text-gray-600">Libur</span></div>
                                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-orange-300"></span> <span class="text-gray-400">Penuh</span></div>
                                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-400"></span> <span class="text-red-600">Tutup</span></div>
                                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-gray-200"></span> <span class="text-gray-400">Lewat</span></div>
                                </div>
                            </div>

                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <style>
        .date-cell.selected {
            background: #2563eb !important;
            border-color: #1d4ed8 !important;
            color: white !important;
            box-shadow: 0 0 0 3px #bfdbfe, 0 4px 12px rgba(37,99,235,.25);
            transform: scale(1.05);
            z-index: 2;
        }
        .date-cell.selected span { color: white !important; }
        .date-cell.holiday-available:hover {
            border-color: #d97706 !important;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        .animate-pulse { animation: pulse 1s ease-in-out infinite; }
        
        /* Error styling */
        .input-error {
            border-color: #dc2626 !important;
            background-color: #fef2f2 !important;
        }
        .input-error.border {
            border-color: #dc2626 !important;
        }
        
        .error-message {
            color: #dc2626;
            font-size: 11px;
            font-weight: 500;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .error-message svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Daftar nama bulan dalam bahasa Indonesia
            const monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const monthSlides = document.querySelectorAll('.month-slide');
            const prevBtn = document.getElementById('prevMonth');
            const nextBtn = document.getElementById('nextMonth');
            const tanggalInput = document.getElementById('tanggal_pilih');
            const tanggalDisplay = document.getElementById('tanggal_display');
            const tanggalWarning = document.getElementById('tanggal_warning');
            const tanggalWarningText = document.getElementById('tanggal_warning_text');
            const errorContainer = document.getElementById('validation-errors');
            const errorList = document.getElementById('error-list');
            const form = document.getElementById('form-reschedule');
            const btnSubmit = document.getElementById('btn-submit');
            const currentMonthYear = document.getElementById('currentMonthYear');

            // Tanggal saat ini dari server
            const currentDate = '{{ $pemesananTiket->tanggal_pemesanan->toDateString() }}';

            // Waktu sekarang (dengan jam) untuk cek 48 jam ke tanggal BARU
            const now = new Date();
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            let currentMonthIdx = 0;
            let selectedDate = null;

            function updateSlider() {
                monthSlides.forEach((s, i) => {
                    s.classList.toggle('hidden', i !== currentMonthIdx);
                });
                prevBtn.disabled = currentMonthIdx === 0;
                nextBtn.disabled = currentMonthIdx === monthSlides.length - 1;
                
                // Update title bulan dengan nama bulan bahasa Indonesia
                const activeSlide = monthSlides[currentMonthIdx];
                if (activeSlide) {
                    // Ambil nama bulan dari data atau dari text di dalam slide
                    const monthName = activeSlide.dataset.monthName || '';
                    if (monthName) {
                        // Parse nama bulan dan tahun
                        const parts = monthName.split(' ');
                        if (parts.length >= 2) {
                            const monthPart = parts.slice(0, -1).join(' ');
                            const yearPart = parts[parts.length - 1];
                            // Cari indeks bulan
                            const monthIndex = monthNames.findIndex(m => m.toLowerCase() === monthPart.toLowerCase());
                            if (monthIndex !== -1) {
                                currentMonthYear.textContent = monthNames[monthIndex] + ' ' + yearPart;
                            } else {
                                currentMonthYear.textContent = monthName;
                            }
                        } else {
                            currentMonthYear.textContent = monthName;
                        }
                    } else {
                        // Fallback: coba ambil dari judul
                        const titleElement = activeSlide.querySelector('h3');
                        if (titleElement) {
                            currentMonthYear.textContent = titleElement.textContent;
                        }
                    }
                }
            }

            // Set data-month-name untuk setiap slide
            @foreach($months as $idx => $month)
                document.querySelector('.month-slide[data-month-index="{{ $idx }}"]')?.setAttribute('data-month-name', '{{ $month['name'] }}');
            @endforeach

            prevBtn.addEventListener('click', function() {
                if (currentMonthIdx > 0) {
                    currentMonthIdx--;
                    updateSlider();
                }
            });

            nextBtn.addEventListener('click', function() {
                if (currentMonthIdx < monthSlides.length - 1) {
                    currentMonthIdx++;
                    updateSlider();
                }
            });

            // Klik tanggal
            document.querySelectorAll('.date-cell').forEach(function(cell) {
                cell.addEventListener('click', function() {
                    // Reset semua selection
                    document.querySelectorAll('.date-cell').forEach(function(c) {
                        c.classList.remove('selected');
                    });

                    this.classList.add('selected');

                    const val = this.dataset.date;
                    const isHoliday = this.dataset.holiday === 'true';
                    const holidayName = this.dataset.holidayName;

                    selectedDate = val;
                    tanggalInput.value = val;

                    // Sembunyikan warning sebelumnya
                    tanggalWarning.style.display = 'none';

                    // Format tampilan tanggal
                    const dateObj = new Date(val + 'T00:00:00');
                    const monthName = monthNames[dateObj.getMonth()];
                    const dayName = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][dateObj.getDay()];
                    let displayText = `${dayName}, ${dateObj.getDate()} ${monthName} ${dateObj.getFullYear()}`;

                    // Reset class display
                    tanggalDisplay.className = 'w-full px-4 py-3 rounded-lg bg-white border text-gray-700 font-medium text-sm';

                    // Cek validasi tanggal
                    let isValid = true;
                    let warningMessage = '';

                    // Cek apakah tanggal sama dengan tanggal saat ini
                    if (val === currentDate) {
                        isValid = false;
                        warningMessage = 'Tanggal yang dipilih sama dengan tanggal saat ini. Silakan pilih tanggal yang berbeda.';
                    }

                    // Cek apakah tanggal BARU kurang dari 48 jam dari SEKARANG
                    if (isValid) {
                        const selectedDateObj = new Date(val + 'T00:00:00');
                        const deadline = new Date(selectedDateObj.getTime() - (48 * 60 * 60 * 1000));
                        
                        if (now > deadline) {
                            isValid = false;
                            warningMessage = 'Tanggal yang dipilih kurang dari 48 jam dari sekarang. Pilih tanggal minimal 2 hari ke depan.';
                        }
                    }

                    if (!isValid) {
                        tanggalWarning.style.display = 'flex';
                        tanggalWarningText.textContent = warningMessage;
                        tanggalDisplay.className = 'w-full px-4 py-3 rounded-lg bg-red-50 border border-red-300 text-red-700 font-medium text-sm';
                        btnSubmit.disabled = true;
                        btnSubmit.className = 'flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-gray-300 px-6 py-2.5 text-sm font-medium text-white cursor-not-allowed transition';
                    } else if (isHoliday && holidayName) {
                        displayText += ' — 🏛️ ' + holidayName;
                        tanggalDisplay.className = 'w-full px-4 py-3 rounded-lg bg-amber-50 border border-amber-300 text-amber-700 font-medium text-sm';
                        btnSubmit.disabled = false;
                        btnSubmit.className = 'flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-sky-600 hover:bg-sky-700 px-6 py-2.5 text-sm font-medium text-white transition shadow-sm';
                    } else {
                        tanggalDisplay.className = 'w-full px-4 py-3 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 font-medium text-sm';
                        btnSubmit.disabled = false;
                        btnSubmit.className = 'flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-sky-600 hover:bg-sky-700 px-6 py-2.5 text-sm font-medium text-white transition shadow-sm';
                    }
                    tanggalDisplay.innerText = displayText;
                });
            });

            // Hapus error
            document.querySelectorAll('input, textarea, select').forEach(function(field) {
                field.addEventListener('input', function() {
                    this.classList.remove('input-error');
                    const errorMsg = this.parentElement.querySelector('.error-message');
                    if (errorMsg) errorMsg.remove();
                });
                
                field.addEventListener('change', function() {
                    this.classList.remove('input-error');
                    const errorMsg = this.parentElement.querySelector('.error-message');
                    if (errorMsg) errorMsg.remove();
                });
            });

            // Validasi form
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Hapus error sebelumnya
                    errorContainer.classList.add('hidden');
                    errorList.innerHTML = '';
                    
                    let isValid = true;
                    let errorMessages = [];

                    // Cek apakah tanggal dipilih
                    if (!tanggalInput.value) {
                        isValid = false;
                        errorMessages.push('Silakan pilih tanggal kunjungan terlebih dahulu.');
                        tanggalDisplay.classList.add('input-error');
                    }

                    // Cek apakah tanggal sama dengan tanggal saat ini
                    if (tanggalInput.value === currentDate) {
                        isValid = false;
                        errorMessages.push('Tanggal yang dipilih sama dengan tanggal saat ini. Silakan pilih tanggal yang berbeda.');
                        tanggalDisplay.classList.add('input-error');
                        tanggalWarning.style.display = 'flex';
                        tanggalWarningText.textContent = 'Tanggal yang dipilih sama dengan tanggal saat ini. Silakan pilih tanggal yang berbeda.';
                    }

                    // Cek apakah tanggal BARU kurang dari 48 jam dari SEKARANG
                    if (tanggalInput.value && tanggalInput.value !== currentDate) {
                        const selectedDateObj = new Date(tanggalInput.value + 'T00:00:00');
                        const deadline = new Date(selectedDateObj.getTime() - (48 * 60 * 60 * 1000));

                        if (now > deadline) {
                            isValid = false;
                            errorMessages.push('Tanggal yang dipilih kurang dari 48 jam dari sekarang. Pilih tanggal minimal 2 hari ke depan.');
                            tanggalDisplay.classList.add('input-error');
                            tanggalWarning.style.display = 'flex';
                            tanggalWarningText.textContent = 'Tanggal yang dipilih kurang dari 48 jam dari sekarang. Pilih tanggal minimal 2 hari ke depan.';
                        }
                    }

                    // Tampilkan error summary jika ada
                    if (!isValid && errorContainer) {
                        errorContainer.classList.remove('hidden');
                        errorList.innerHTML = errorMessages.map(function(msg) {
                            return '<li>' + msg + '</li>';
                        }).join('');
                        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }

                    // Jika valid, submit form
                    if (isValid) {
                        form.submit();
                    }
                });
            }

            // Inisialisasi
            updateSlider();
        });
    </script>
</x-app-layout>