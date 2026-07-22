<x-app-layout>
    <div class="bg-white">
        {{-- ================= HEADER BACK ================= --}}
        <section class="bg-[#f8faff] px-4 sm:px-6 lg:px-8 py-4 sm:py-6 border-b border-[#e8edf5]">
            <div class="max-w-7xl mx-auto">
                <a href="{{ route('tiket.index') }}" 
                   class="inline-flex items-center gap-2 text-sm font-semibold text-[#2563eb] hover:text-[#1d4ed8] transition-colors duration-200 group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar Tiket
                </a>
            </div>
        </section>

        {{-- ================= MAIN CONTENT ================= --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-10">

            {{-- ALERT --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-[#ecfdf5] border border-[#a7f3d0] rounded-2xl text-[#059669] text-sm flex items-start gap-3">
                    <i class="fas fa-check-circle text-[#22c55e] text-base mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-[#fef2f2] border border-[#fca5a5] rounded-2xl text-[#dc2626] text-sm flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-[#dc2626] text-base mt-0.5"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- ================= TICKET DETAIL CARD ================= --}}
            <div class="bg-white border border-[#e8edf5] rounded-2xl shadow-sm overflow-hidden">

                {{-- TOP SECTION --}}
                <div class="p-4 sm:p-6 lg:p-8 grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 border-b border-[#f1f5f9]">
                    
                    {{-- LEFT --}}
                    <div>
                        <div class="inline-block bg-[#eff6ff] text-[#2563eb] text-[10px] font-semibold px-3 py-1 rounded-full mb-3 tracking-wide">
                            <i class="fas fa-ticket-alt mr-1.5"></i> {{ $ticket->jenis_tiket ?? 'Tiket Museum' }}
                        </div>

                        <h1 class="text-2xl sm:text-4xl font-extrabold text-[#1a1a2e] leading-tight mb-2">
                            {{ $ticket->nama_tiket }}
                        </h1>

                        <div class="flex flex-wrap gap-1.5 mb-3">
                            @if($ticket->sub_jenis)
                                <span class="text-[8px] sm:text-[10px] font-semibold uppercase tracking-wide text-[#475569] bg-[#f1f5f9] border border-[#e2e8f0] px-2.5 py-1 rounded-full">{{ $ticket->sub_jenis }}</span>
                            @endif
                            @if($ticket->kategori_pengunjung)
                                <span class="text-[8px] sm:text-[10px] font-semibold uppercase tracking-wide text-[#059669] bg-[#ecfdf5] border border-[#a7f3d0] px-2.5 py-1 rounded-full">{{ $ticket->kategori_pengunjung }}</span>
                            @endif
                            @if($ticket->boleh_reschedule)
                                <span class="text-[8px] sm:text-[10px] font-semibold uppercase tracking-wide text-[#d97706] bg-[#fffbeb] border border-[#fde68a] px-2.5 py-1 rounded-full">Bisa Reschedule</span>
                            @endif
                            @if($ticket->boleh_cancel)
                                <span class="text-[8px] sm:text-[10px] font-semibold uppercase tracking-wide text-[#dc2626] bg-[#fef2f2] border border-[#fca5a5] px-2.5 py-1 rounded-full">Bisa Batal</span>
                            @endif
                        </div>

                        <p class="text-sm sm:text-base text-[#4a5568] leading-relaxed mb-4 sm:mb-6">
                            {{ $ticket->deskripsi ?: 'Nikmati pengalaman tak terlupakan di museum kami dengan layanan terbaik.' }}
                        </p>

                        <div class="grid grid-cols-2 gap-3 sm:gap-4">
                            <div class="p-3 sm:p-4 bg-[#f8faff] rounded-2xl border border-[#e8edf5]">
                                <p class="text-[8px] sm:text-[10px] font-bold uppercase tracking-wider text-[#94a3b8] mb-0.5">Harga Tiket</p>
                                <p class="text-lg sm:text-2xl font-extrabold text-[#2563eb]">Rp {{ number_format($ticket->harga, 0, ',', '.') }}</p>
                            </div>
                            @if($ticket->minimal_anggota)
                            <div class="p-3 sm:p-4 bg-[#f8faff] rounded-2xl border border-[#e8edf5]">
                                <p class="text-[8px] sm:text-[10px] font-bold uppercase tracking-wider text-[#94a3b8] mb-0.5">Min. Anggota</p>
                                <p class="text-lg sm:text-2xl font-extrabold text-[#1a1a2e]">{{ $ticket->minimal_anggota }} <span class="text-sm font-normal text-[#718096]">Orang</span></p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- RIGHT - IMAGE --}}
                    <div class="relative group order-first lg:order-last">
                        @if($ticket->gambar)
                            <img src="{{ asset('storage/gambar/'.$ticket->gambar) }}" 
                                 alt="{{ $ticket->nama_tiket }}"
                                 class="w-full h-48 sm:h-56 lg:h-64 object-cover rounded-2xl shadow-lg transition-transform duration-500 group-hover:scale-[1.02]">
                        @else
                            <div class="w-full h-48 sm:h-56 lg:h-64 bg-gradient-to-br from-[#eff6ff] to-[#dbeafe] rounded-2xl flex items-center justify-center">
                                <i class="fas fa-image text-5xl text-[#93c5fd]"></i>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- ================= CALENDAR SECTION ================= --}}
                <div class="p-4 sm:p-6 lg:p-8 bg-[#f8faff]">
                    
                    {{-- HEADER --}}
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
                        <div>
                            <h2 class="text-sm sm:text-lg font-bold text-[#1a1a2e]">Pilih Tanggal Kunjungan</h2>
                            <p class="text-xs sm:text-sm text-[#718096]">Pilih tanggal yang tersedia pada kalender di bawah.</p>
                        </div>
                        <div class="flex items-center gap-1 bg-white border border-[#e2e8f0] rounded-xl p-1 shadow-sm">
                            <button type="button" id="prevMonth"
                                    class="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white text-[#4a5568] font-semibold text-xs hover:bg-[#eff6ff] hover:text-[#2563eb] transition disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-[#4a5568]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                </svg>
                                <span class="hidden sm:inline">Sebelumnya</span>
                            </button>
                            <div class="w-px h-5 bg-[#e2e8f0]"></div>
                            <button type="button" id="nextMonth"
                                    class="flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white text-[#4a5568] font-semibold text-xs hover:bg-[#eff6ff] hover:text-[#2563eb] transition disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-[#4a5568]">
                                <span class="hidden sm:inline">Berikutnya</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @php
                        $startDate = \Carbon\Carbon::parse($ticket->tanggal_mulai);
                        $endDate   = \Carbon\Carbon::parse($ticket->tanggal_selesai ?? $startDate->copy()->addMonths(3));

                        $quotas = $ticket->quotas->keyBy(fn($q) => $q->tanggal->toDateString());

                        $yearsNeeded = range($startDate->year, $endDate->year);
                        $holidaysData = [];
                        foreach ($yearsNeeded as $year) {
                            $holidaysData[$year] = \App\Models\TicketQuota::getIndonesianHolidays($year);
                        }

                        $isSundayPainting = strtolower((string) $ticket->sub_jenis) === 'sunday painting';
                        $isIndividu = $isSundayPainting && $ticket->kategori_pengunjung === 'Individu';
                        $isKelompok = $isSundayPainting && $ticket->kategori_pengunjung === 'Kelompok';
                        $maxIndividu = 4;
                        $minKelompok = $ticket->minimal_anggota ?: 5;

                        $kelompokTicket = null;
                        if ($isIndividu) {
                            $kelompokTicket = \App\Models\Ticket::where('status', true)
                                ->where('jenis_tiket', 'event')
                                ->where('sub_jenis', $ticket->sub_jenis)
                                ->where('kategori_pengunjung', 'Kelompok')
                                ->where('tanggal_mulai', $ticket->tanggal_mulai)
                                ->first();
                        }

                        $individuTicket = null;
                        if ($isKelompok) {
                            $individuTicket = \App\Models\Ticket::where('status', true)
                                ->where('jenis_tiket', 'event')
                                ->where('sub_jenis', $ticket->sub_jenis)
                                ->where('kategori_pengunjung', 'Individu')
                                ->where('tanggal_mulai', $ticket->tanggal_mulai)
                                ->first();
                        }

                        $months = [];
                        $currentCursor = $startDate->copy()->startOfMonth();

                        while ($currentCursor->lte($endDate)) {
                            $monthName = $currentCursor->translatedFormat('F Y');
                            $daysInMonth = [];
                            $monthHasAvailableDay = false;

                            $padding = $currentCursor->copy()->startOfMonth()->dayOfWeekIso - 1;
                            for ($i = 0; $i < $padding; $i++) {
                                $daysInMonth[] = ['status' => 'blank'];
                            }

                            $tempDate = $currentCursor->copy()->startOfMonth();
                            while ($tempDate->month == $currentCursor->month) {
                                $dateStr    = $tempDate->toDateString();
                                $quota      = $quotas[$dateStr] ?? null;
                                $isHoliday  = isset($holidaysData[$tempDate->year][$dateStr]);
                                $holidayName = $isHoliday ? $holidaysData[$tempDate->year][$dateStr] : null;

                                if ($tempDate->lt(now()->startOfDay())) {
                                    $status = 'expired';
                                } elseif ($quota && $quota->kuota_sisa > 0 && $tempDate->between($startDate, $endDate)) {
                                    $status = 'available';
                                } elseif ($quota && $quota->kuota_sisa <= 0) {
                                    $status = 'full';
                                } else {
                                    $status = 'closed';
                                }

                                if ($status === 'available') {
                                    $monthHasAvailableDay = true;
                                }

                                $daysInMonth[] = [
                                    'date'        => $tempDate->copy(),
                                    'dayNum'      => $tempDate->day,
                                    'status'      => $status,
                                    'isHoliday'   => $isHoliday,
                                    'holidayName' => $holidayName,
                                    'sisa_kuota'  => $quota ? $quota->kuota_sisa : 0,
                                    'kuota'       => $quota ? $quota->kuota : 0,
                                ];
                                $tempDate->addDay();
                            }

                            if ($monthHasAvailableDay) {
                                $months[] = ['name' => $monthName, 'days' => $daysInMonth];
                            }
                            $currentCursor->addMonth();
                        }
                    @endphp

                    @if(empty($months))
                        <div class="bg-[#fffbeb] border border-[#fde68a] rounded-2xl p-6 text-center">
                            <i class="fas fa-calendar-times text-2xl text-[#d97706] mb-2 block"></i>
                            <p class="text-sm font-bold text-[#92400e]">Belum ada tanggal yang tersedia untuk tiket ini.</p>
                            <p class="text-xs text-[#78350f] mt-1">Silakan cek kembali di lain waktu atau hubungi pengelola museum.</p>
                        </div>
                    @endif

                    {{-- CALENDAR --}}
                    @foreach($months as $idx => $month)
                        <div class="month-slide {{ $idx > 0 ? 'hidden' : '' }}" data-month-index="{{ $idx }}">
                            <h3 class="text-center font-extrabold text-[#2563eb] mb-3 text-sm uppercase tracking-widest">
                                {{ \Carbon\Carbon::parse($month['name'])->locale('id')->translatedFormat('F Y') }}
                            </h3>
                            <div class="grid grid-cols-7 gap-1 sm:gap-2">
                                {{-- HARI DALAM BAHASA INDONESIA --}}
                                @foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $h)
                                    <div class="text-center text-[8px] sm:text-[10px] font-bold text-[#94a3b8] py-1">{{ $h }}</div>
                                @endforeach

                                @foreach($month['days'] as $d)
                                    @if($d['status'] === 'blank')
                                        <div class="h-10 sm:h-14"></div>
                                    @else
                                        @php
                                            // STATUS DALAM BAHASA INDONESIA
                                            $statusLabel = '';
                                            if ($d['status'] === 'expired') {
                                                $statusLabel = 'Lewat';
                                                $bgClass     = 'bg-[#f1f5f9]';
                                                $borderClass = 'border-transparent';
                                                $textClass   = 'text-[#94a3b8]';
                                                $cursor      = 'cursor-not-allowed';
                                            } elseif ($d['status'] === 'available') {
                                                $statusLabel = $d['isHoliday'] ? 'Libur*' : 'Tersedia';
                                                $bgClass     = $d['isHoliday'] ? 'bg-[#fffbeb]'       : 'bg-white';
                                                $borderClass = $d['isHoliday'] ? 'border-[#fcd34d]'   : 'border-[#e2e8f0]';
                                                $textClass   = $d['isHoliday'] ? 'text-[#92400e]'     : 'text-[#1a1a2e]';
                                                $cursor      = 'cursor-pointer date-cell';
                                            } elseif ($d['status'] === 'full') {
                                                $statusLabel = 'Penuh';
                                                $bgClass     = 'bg-[#fffbeb]';
                                                $borderClass = 'border-[#fcd34d]';
                                                $textClass   = 'text-[#b45309]';
                                                $cursor      = 'cursor-not-allowed';
                                            } else {
                                                $statusLabel = $d['isHoliday'] ? 'Libur' : 'Tutup';
                                                $bgClass     = $d['isHoliday'] ? 'bg-[#fef2f2]'     : 'bg-[#fef2f2]';
                                                $borderClass = $d['isHoliday'] ? 'border-[#fca5a5]'  : 'border-[#fca5a5]';
                                                $textClass   = $d['isHoliday'] ? 'text-[#dc2626]'    : 'text-[#f87171]';
                                                $cursor      = 'cursor-not-allowed';
                                            }

                                            $style = "{$bgClass} {$borderClass} {$textClass} {$cursor}";
                                        @endphp

                                        <div class="h-10 sm:h-14 rounded-xl border-2 flex flex-col items-center justify-center transition-all duration-200 {{ $style }} relative group"
                                             data-date="{{ $d['date']->toDateString() }}"
                                             data-status="{{ $d['status'] }}"
                                             data-holiday="{{ $d['isHoliday'] ? 'true' : 'false' }}"
                                             data-holiday-name="{{ $d['holidayName'] ?? '' }}"
                                             data-sisa-kuota="{{ $d['sisa_kuota'] }}"
                                             data-kuota="{{ $d['kuota'] }}">

                                            <span class="text-sm sm:text-base font-bold">{{ $d['dayNum'] }}</span>

                                            <span class="text-[5px] sm:text-[8px] font-semibold uppercase leading-tight mt-0.5">
                                                {{ $statusLabel }}
                                            </span>

                                            @if($d['sisa_kuota'] > 0 && $d['sisa_kuota'] <= 3 && $d['status'] === 'available')
                                                <span class="absolute -top-1 -right-1 bg-[#dc2626] text-white text-[7px] sm:text-[9px] font-bold rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center animate-pulse">
                                                    {{ $d['sisa_kuota'] }}
                                                </span>
                                            @endif

                                            @if($d['isHoliday'] && $d['holidayName'])
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover:block z-20 whitespace-nowrap pointer-events-none">
                                                    <div class="bg-[#1a1a2e] text-white text-[9px] sm:text-xs rounded-lg px-2 py-1 shadow-lg">
                                                        {{ $d['holidayName'] }}
                                                    </div>
                                                    <div class="w-2 h-2 bg-[#1a1a2e] rotate-45 mx-auto -mt-1"></div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- LEGEND DALAM BAHASA INDONESIA --}}
                    <div class="mt-4 flex flex-wrap gap-x-3 gap-y-1 justify-center text-[8px] sm:text-[10px] font-semibold uppercase tracking-wide">
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#2563eb]"></span> <span class="text-[#475569]">Tersedia</span></div>
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#f59e0b]"></span> <span class="text-[#475569]">Tersedia (Libur)</span></div>
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#f97316]"></span> <span class="text-[#94a3b8]">Penuh</span></div>
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#ef4444]"></span> <span class="text-[#dc2626]">Libur (Tutup)</span></div>
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#fca5a5]"></span> <span class="text-[#94a3b8]">Tutup</span></div>
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#e2e8f0]"></span> <span class="text-[#94a3b8]">Lewat</span></div>
                    </div>
                </div>

                {{-- ================= CHECKOUT SECTION ================= --}}
                <div class="p-4 sm:p-6 lg:p-8 border-t border-[#e8edf5] bg-white">
                    <form action="{{ route('tiket.checkout', $ticket->id) }}" method="POST" id="checkoutForm"
                          data-kategori="{{ $ticket->kategori_pengunjung }}"
                          data-is-individu="{{ $isIndividu ? '1' : '0' }}"
                          data-is-kelompok="{{ $isKelompok ? '1' : '0' }}"
                          data-max-individu="{{ $maxIndividu }}"
                          data-min-kelompok="{{ $minKelompok }}"
                          data-kelompok-url="{{ $kelompokTicket ? route('tiket.show', $kelompokTicket->id) : '' }}"
                          data-individu-url="{{ $individuTicket ? route('tiket.show', $individuTicket->id) : '' }}">
                        @csrf
                        <input type="hidden" name="tanggal_pemesanan" id="tanggal_pilih" required>

                        <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4 items-stretch">

                            {{-- LEFT --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                
                                {{-- TANGGAL --}}
                                <div class="bg-[#f8faff] border border-[#e2e8f0] rounded-2xl p-3 sm:p-4">
                                    <label class="flex items-center gap-1.5 text-[8px] sm:text-[10px] font-bold text-[#94a3b8] uppercase tracking-wider mb-1">
                                        <i class="fas fa-calendar-day"></i>
                                        Tanggal Dipilih
                                    </label>
                                    <div id="tanggal_display" 
                                         class="w-full px-3 py-2 rounded-xl bg-white border border-[#e2e8f0] text-[#94a3b8] font-medium text-xs sm:text-sm leading-snug min-h-[38px] sm:min-h-[44px] flex items-center">
                                        Pilih di kalender...
                                    </div>
                                    <div id="tanggal_info" class="text-[8px] sm:text-[10px] mt-1 hidden"></div>
                                </div>

                                {{-- JUMLAH --}}
                                <div class="bg-[#f8faff] border-2 border-[#2563eb]/20 rounded-2xl p-3 sm:p-4 ring-2 ring-[#2563eb]/5">
                                    <label class="flex items-center gap-1.5 text-[8px] sm:text-[10px] font-bold text-[#2563eb] uppercase tracking-wider mb-1">
                                        <i class="fas fa-users"></i>
                                        Jumlah Tiket
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <button type="button" id="jumlah_minus"
                                                class="w-7 h-7 sm:w-9 sm:h-9 flex-shrink-0 flex items-center justify-center rounded-xl bg-white border border-[#e2e8f0] text-[#4a5568] font-bold hover:bg-[#eff6ff] hover:border-[#2563eb] transition disabled:opacity-50 disabled:cursor-not-allowed text-lg sm:text-xl">
                                            −
                                        </button>
                                        <input type="number" name="jumlah_tiket" id="jumlah_tiket" min="1" value="1"
                                               class="w-full text-center px-2 py-1.5 sm:py-2 rounded-xl border border-[#e2e8f0] focus:ring-2 focus:ring-[#2563eb] focus:border-[#2563eb] font-bold text-sm sm:text-base bg-white">
                                        <button type="button" id="jumlah_plus"
                                                class="w-7 h-7 sm:w-9 sm:h-9 flex-shrink-0 flex items-center justify-center rounded-xl bg-white border border-[#e2e8f0] text-[#4a5568] font-bold hover:bg-[#eff6ff] hover:border-[#2563eb] transition disabled:opacity-50 disabled:cursor-not-allowed text-lg sm:text-xl">
                                            +
                                        </button>
                                    </div>
                                    <div id="kuota_info" class="text-[8px] sm:text-[10px] text-[#94a3b8] mt-1 leading-tight"></div>
                                    <div id="kuota_warning" class="text-[9px] sm:text-[11px] font-semibold text-[#dc2626] mt-1 hidden"></div>
                                    <div id="alih_kelompok_box" class="hidden mt-2 rounded-xl bg-[#fffbeb] border border-[#fde68a] px-3 py-1.5 text-[8px] sm:text-[10px] text-[#92400e] font-medium"></div>
                                </div>
                            </div>

                            {{-- BUTTON --}}
                            <button type="submit" id="checkout_btn" disabled
                                    class="w-full lg:w-auto px-6 sm:px-10 py-3 sm:py-4 bg-[#e2e8f0] text-[#94a3b8] rounded-2xl font-extrabold text-sm uppercase tracking-widest transition-all cursor-not-allowed self-end mt-1 lg:mt-0">
                                Selanjutnya →
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <style>
        .date-cell.selected {
            background: #2563eb !important;
            border-color: #1d4ed8 !important;
            color: white !important;
            box-shadow: 0 0 0 4px #bfdbfe, 0 8px 24px rgba(37,99,235,0.25);
            transform: scale(1.05);
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const monthSlides    = document.querySelectorAll('.month-slide');
            const prevBtn        = document.getElementById('prevMonth');
            const nextBtn        = document.getElementById('nextMonth');
            const checkoutBtn    = document.getElementById('checkout_btn');
            const tanggalInput   = document.getElementById('tanggal_pilih');
            const tanggalDisplay = document.getElementById('tanggal_display');
            const tanggalInfo    = document.getElementById('tanggal_info');
            const jumlahTiket    = document.getElementById('jumlah_tiket');
            const jumlahMinus    = document.getElementById('jumlah_minus');
            const jumlahPlus     = document.getElementById('jumlah_plus');
            const kuotaInfo      = document.getElementById('kuota_info');
            const kuotaWarning   = document.getElementById('kuota_warning');
            const alihBox        = document.getElementById('alih_kelompok_box');
            const form           = document.getElementById('checkoutForm');

            const isIndividu  = form.dataset.isIndividu === '1';
            const isKelompok  = form.dataset.isKelompok === '1';
            const maxIndividu = parseInt(form.dataset.maxIndividu) || 4;
            const minKelompok = parseInt(form.dataset.minKelompok) || 5;
            const kelompokUrl = form.dataset.kelompokUrl || '';
            const individuUrl = form.dataset.individuUrl || '';

            let currentMonthIdx   = 0;
            let selectedSisaKuota = 0;
            let selectedDate      = null;
            let redirectTimer     = null;

            if (isIndividu) {
                kuotaInfo.textContent = `Maks. ${maxIndividu} tiket`;
            } else if (isKelompok) {
                kuotaInfo.textContent = `Min. ${minKelompok} tiket`;
                jumlahTiket.value = minKelompok;
            }

            function updateSlider() {
                monthSlides.forEach((s, i) => s.classList.toggle('hidden', i !== currentMonthIdx));
                prevBtn.disabled = currentMonthIdx === 0;
                nextBtn.disabled = currentMonthIdx === monthSlides.length - 1;
            }
            prevBtn.addEventListener('click', () => { if (currentMonthIdx > 0) { currentMonthIdx--; updateSlider(); } });
            nextBtn.addEventListener('click', () => { if (currentMonthIdx < monthSlides.length - 1) { currentMonthIdx++; updateSlider(); } });

            jumlahMinus.addEventListener('click', () => {
                const current = parseInt(jumlahTiket.value) || 1;
                jumlahTiket.value = Math.max(1, current - 1);
                validateJumlahTiket();
            });
            jumlahPlus.addEventListener('click', () => {
                const current = parseInt(jumlahTiket.value) || 1;
                jumlahTiket.value = current + 1;
                validateJumlahTiket();
            });

            function cancelRedirect() {
                if (redirectTimer) {
                    clearTimeout(redirectTimer);
                    redirectTimer = null;
                }
                alihBox.classList.add('hidden');
            }

            function scheduleRedirect(url, label) {
                if (redirectTimer) return;
                alihBox.classList.remove('hidden');
                alihBox.className = 'mt-2 rounded-xl bg-[#fffbeb] border border-[#fde68a] px-3 py-1.5 text-[8px] sm:text-[10px] text-[#92400e] font-medium';
                alihBox.textContent = label;
                redirectTimer = setTimeout(() => {
                    const params = new URLSearchParams();
                    params.set('jumlah_tiket', jumlahTiket.value);
                    if (selectedDate) params.set('tanggal', selectedDate);
                    window.location.href = url + '?' + params.toString();
                }, 1200);
            }

            function showStaticWarningBox(message) {
                alihBox.classList.remove('hidden');
                alihBox.className = 'mt-2 rounded-xl bg-[#fef2f2] border border-[#fca5a5] px-3 py-1.5 text-[8px] sm:text-[10px] text-[#dc2626] font-medium';
                alihBox.innerHTML = message;
            }

            function validateJumlahTiket() {
                const jumlah = parseInt(jumlahTiket.value) || 0;
                const sisa   = selectedSisaKuota;

                kuotaWarning.classList.add('hidden');
                let valid = true;
                let pendingRedirect = false;

                if (isIndividu && jumlah > maxIndividu) {
                    valid = false;
                    kuotaWarning.classList.remove('hidden');
                    kuotaWarning.innerHTML = `⚠️ Maksimal ${maxIndividu} tiket per pemesanan.`;
                    if (kelompokUrl) {
                        pendingRedirect = true;
                        scheduleRedirect(kelompokUrl, 'Mengalihkan ke tiket Kelompok…');
                    } else {
                        showStaticWarningBox(`⚠️ Maksimal ${maxIndividu} tiket. Tiket Kelompok belum tersedia — hubungi pengelola museum.`);
                    }
                } else if (isKelompok && jumlah < minKelompok) {
                    valid = false;
                    kuotaWarning.classList.remove('hidden');
                    kuotaWarning.innerHTML = `⚠️ Minimal ${minKelompok} orang untuk kategori Kelompok.`;
                    if (jumlah > 0 && jumlah <= maxIndividu) {
                        if (individuUrl) {
                            pendingRedirect = true;
                            scheduleRedirect(individuUrl, 'Mengalihkan ke tiket Individu…');
                        } else {
                            showStaticWarningBox(`⚠️ Tiket Individu belum tersedia — hubungi pengelola museum.`);
                        }
                    }
                }

                if (!pendingRedirect) {
                    cancelRedirect();
                }

                if (valid && selectedDate && sisa > 0 && jumlah > sisa) {
                    kuotaWarning.classList.remove('hidden');
                    kuotaWarning.innerHTML = `⚠️ Permintaan (${jumlah}) melebihi kuota tersisa (${sisa}).`;
                    valid = false;
                }

                setCheckoutDisabled(!valid || !selectedDate);
                return valid;
            }

            function setCheckoutDisabled(disabled) {
                checkoutBtn.disabled = disabled;
                if (disabled) {
                    checkoutBtn.className = 'w-full lg:w-auto px-6 sm:px-10 py-3 sm:py-4 bg-[#e2e8f0] text-[#94a3b8] rounded-2xl font-extrabold text-sm uppercase tracking-widest transition-all cursor-not-allowed self-end mt-1 lg:mt-0';
                } else {
                    checkoutBtn.className = 'w-full lg:w-auto px-6 sm:px-10 py-3 sm:py-4 bg-[#2563eb] hover:bg-[#1d4ed8] text-white rounded-2xl font-extrabold text-sm uppercase tracking-widest transition-all shadow-lg shadow-[#2563eb]/30 hover:shadow-xl self-end mt-1 lg:mt-0';
                }
            }

            jumlahTiket.addEventListener('input',  validateJumlahTiket);
            jumlahTiket.addEventListener('change', validateJumlahTiket);

            document.querySelectorAll('.date-cell').forEach(cell => {
                cell.addEventListener('click', function () {
                    document.querySelectorAll('.date-cell').forEach(c => c.classList.remove('selected'));

                    this.classList.add('selected');

                    const val         = this.dataset.date;
                    const sisaKuota   = parseInt(this.dataset.sisaKuota) || 0;
                    const isHoliday   = this.dataset.holiday === 'true';
                    const holidayName = this.dataset.holidayName;

                    selectedSisaKuota = sisaKuota;
                    selectedDate      = val;
                    tanggalInput.value = val;

                    const dateObj    = new Date(val + 'T00:00:00');
                    let displayText  = dateObj.toLocaleDateString('id-ID', {
                        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                    });

                    if (isHoliday && holidayName) {
                        displayText += ` — 🏛️ ${holidayName}`;
                        tanggalDisplay.className = 'w-full px-3 py-2 rounded-xl bg-[#fffbeb] border border-[#fcd34d] text-[#92400e] font-medium text-xs sm:text-sm leading-snug min-h-[38px] sm:min-h-[44px] flex items-center';
                    } else {
                        tanggalDisplay.className = 'w-full px-3 py-2 rounded-xl bg-[#eff6ff] border border-[#2563eb]/30 text-[#1e40af] font-medium text-xs sm:text-sm leading-snug min-h-[38px] sm:min-h-[44px] flex items-center';
                    }
                    tanggalDisplay.innerText = displayText;

                    if (sisaKuota > 0) {
                        tanggalInfo.classList.remove('hidden');
                        if (sisaKuota === 1) {
                            tanggalInfo.innerHTML = `<span class="text-[#dc2626] font-bold">⚠️ Hanya tersisa 1 tiket!</span>`;
                        } else if (sisaKuota <= 3) {
                            tanggalInfo.innerHTML = `<span class="text-[#d97706] font-semibold">⚠️ Kuota tersisa: ${sisaKuota} tiket</span>`;
                        } else {
                            tanggalInfo.innerHTML = `<span class="text-[#059669] font-semibold">✓ Kuota tersedia</span>`;
                        }
                    } else {
                        tanggalInfo.classList.add('hidden');
                    }

                    const jumlahVal = parseInt(jumlahTiket.value) || 1;
                    if (jumlahVal > sisaKuota && sisaKuota > 0) {
                        jumlahTiket.value = isKelompok ? Math.max(sisaKuota, minKelompok) : sisaKuota;
                    }

                    validateJumlahTiket();
                });
            });

            document.getElementById('checkoutForm').addEventListener('submit', function (e) {
                if (!tanggalInput.value) {
                    e.preventDefault();
                    alert('Silakan pilih tanggal kunjungan terlebih dahulu.');
                    return;
                }
                if (!validateJumlahTiket()) {
                    e.preventDefault();
                    return;
                }
                const jumlah = parseInt(jumlahTiket.value) || 0;
                if (jumlah > selectedSisaKuota) {
                    e.preventDefault();
                    alert(`Maaf, kuota tidak mencukupi. Sisa kuota: ${selectedSisaKuota} tiket.`);
                    return;
                }
                const selectedCell = document.querySelector('.date-cell.selected');
                if (selectedCell && selectedCell.dataset.holiday === 'true') {
                    const hn = selectedCell.dataset.holidayName;
                    const tgl = new Date(tanggalInput.value + 'T00:00:00').toLocaleDateString('id-ID');
                    if (!confirm(`Perhatian: ${tgl} adalah hari libur nasional (${hn}). Museum mungkin tutup pada hari tersebut. Tetap lanjutkan pemesanan?`)) {
                        e.preventDefault();
                    }
                }
            });

            const urlParams = new URLSearchParams(window.location.search);
            const prefillJumlah = parseInt(urlParams.get('jumlah_tiket'));
            if (prefillJumlah && !isNaN(prefillJumlah)) {
                jumlahTiket.value = prefillJumlah;
                validateJumlahTiket();
            }
            const prefillTanggal = urlParams.get('tanggal');
            if (prefillTanggal) {
                const targetCell = document.querySelector(`.date-cell[data-date="${prefillTanggal}"]`);
                if (targetCell) {
                    const slide = targetCell.closest('.month-slide');
                    if (slide) currentMonthIdx = parseInt(slide.dataset.monthIndex) || 0;
                    targetCell.click();
                }
            }

            updateSlider();
        });
    </script>
</x-app-layout>