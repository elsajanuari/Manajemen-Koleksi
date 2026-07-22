<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('tickets.index') }}" class="hover:text-gray-700 transition">Tiket</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 font-medium">Detail Tiket</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Detail Tiket</h1>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $ticket->nama_tiket }}</p>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium {{ $ticket->getStatusBadgeClass() }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $ticket->getStatusDotClass() }} mr-2"></span>
                            {{ $ticket->getDisplayStatus() }}
                        </span>
                        
                        @if($ticket->isExpired())
                            <span class="text-xs text-gray-400">
                                Berakhir pada {{ \Carbon\Carbon::parse($ticket->tanggal_selesai)->locale('id')->translatedFormat('d F Y') }}
                            </span>
                        @endif
                        
                        @if($ticket->isUpcoming())
                            <span class="text-xs text-blue-600">
                                Mulai {{ \Carbon\Carbon::parse($ticket->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6">

                {{-- Tombol Aksi --}}
                <div class="flex flex-wrap gap-2 mb-6">
                    <a href="{{ route('tickets.edit', $ticket->id) }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-amber-100 text-amber-700 text-sm font-medium hover:bg-amber-200 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Edit Tiket
                    </a>
                    <a href="{{ route('tickets.quotas', $ticket->id) }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg bg-green-100 text-green-700 text-sm font-medium hover:bg-green-200 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Kelola Kuota Tiket
                    </a>
                    <a href="{{ route('tickets.index') }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>

                {{-- GRID UTAMA: 2 Kolom (Desktop) --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Kolom Kiri: Informasi Tiket --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Informasi Dasar --}}
                        <div class="bg-gray-50/50 rounded-xl p-4 sm:p-5 border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">1</span>
                                Informasi Tiket
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-500">Jenis Tiket</span>
                                    <p class="font-medium text-gray-800 mt-0.5">{{ ucfirst($ticket->jenis_tiket) }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Sub Jenis</span>
                                    <p class="font-medium text-gray-800 mt-0.5">{{ $ticket->sub_jenis ?: '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Sub Kategori</span>
                                    <p class="font-medium text-gray-800 mt-0.5">{{ $ticket->sub_kategori ?: '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Kategori Pengunjung</span>
                                    <p class="font-medium text-gray-800 mt-0.5">{{ $ticket->kategori_pengunjung }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Harga</span>
                                    <p class="font-medium text-gray-800 mt-0.5">Rp {{ number_format($ticket->harga, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Kuota per Hari</span>
                                    <p class="font-medium text-gray-800 mt-0.5">{{ $ticket->kuota }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Minimal Anggota</span>
                                    <p class="font-medium text-gray-800 mt-0.5">{{ $ticket->minimal_anggota ?: '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Periode</span>
                                    <p class="font-medium text-gray-800 mt-0.5">
                                        {{ \Carbon\Carbon::parse($ticket->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}
                                        @if($ticket->tanggal_selesai)
                                            - {{ \Carbon\Carbon::parse($ticket->tanggal_selesai)->locale('id')->translatedFormat('d F Y') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="text-gray-500">Deskripsi</span>
                                    <p class="font-medium text-gray-800 mt-0.5">{{ $ticket->deskripsi ?: '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Kebijakan --}}
                        <div class="bg-gray-50/50 rounded-xl p-4 sm:p-5 border border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">2</span>
                                Kebijakan
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                <div>
                                    <span class="text-gray-500">Reschedule</span>
                                    <p class="font-medium text-gray-800 mt-0.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs {{ $ticket->boleh_reschedule ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $ticket->boleh_reschedule ? '✓ Diizinkan' : '✗ Tidak diizinkan' }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <span class="text-gray-500">Pembatalan (Refund)</span>
                                    <p class="font-medium text-gray-800 mt-0.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs {{ $ticket->boleh_cancel ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $ticket->boleh_cancel ? '✓ Diizinkan' : '✗ Tidak diizinkan' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Gambar --}}
                    <div>
                        <div class="bg-gray-50/50 rounded-xl p-4 sm:p-5 border border-gray-200 sticky top-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Gambar Tiket
                            </h3>
                            @if($ticket->gambar)
                                <img src="{{ asset('storage/gambar/'.$ticket->gambar) }}"
                                    alt="{{ $ticket->nama_tiket }}"
                                    class="w-full h-48 object-cover rounded-xl border border-gray-200">
                                <p class="text-xs text-gray-400 mt-2 text-center">{{ $ticket->gambar }}</p>
                            @else
                                <div class="w-full h-48 bg-gray-100 rounded-xl border border-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-400 mt-2 text-center">Tidak ada gambar</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- SECTION: Kalender Visualisasi --}}
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">3</span>
                        Visualisasi Kalender Kuota
                    </h3>
                    <p class="text-xs text-gray-500 mb-4">Melihat ketersediaan kuota secara real-time berdasarkan tanggal.</p>

                    <div class="bg-gray-50/50 rounded-xl p-4 sm:p-5 border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <button type="button" id="prevMonthBtn" 
                                class="p-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <h4 id="currentMonthYear" class="text-base font-bold text-gray-800"></h4>
                            <button type="button" id="nextMonthBtn" 
                                class="p-2 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-7 gap-1 mb-2">
                            @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                                <div class="text-center text-xs font-medium text-gray-500 py-1.5">{{ $day }}</div>
                            @endforeach
                        </div>

                        <div id="calendar-days" class="grid grid-cols-7 gap-1"></div>

                        <div class="text-xs text-gray-500 mt-4 flex flex-wrap gap-4 justify-center border-t pt-4">
                            <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-green-500"></span> Tersedia</div>
                            <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-200"></span> Penuh</div>
                            <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-gray-200"></span> Tutup</div>
                            <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-400"></span> Libur</div>
                            <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-gray-50 border border-gray-200"></span> Di Luar Periode</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media (min-width: 1024px) {
            .sticky {
                position: sticky;
                top: 1rem;
            }
        }
    </style>

    <script>
   
    let currentDate = new Date();
    let startPeriod = new Date('{{ $ticket->tanggal_mulai }}');
    let endPeriod = '{{ $ticket->tanggal_selesai }}' ? new Date('{{ $ticket->tanggal_selesai }}') : null;

    let quotaMap = new Map();
    @foreach($ticket->quotas as $quota)
        @php
            $quotaDate = is_string($quota->tanggal) ? \Carbon\Carbon::parse($quota->tanggal) : $quota->tanggal;
        @endphp
        quotaMap.set('{{ $quotaDate->toDateString() }}', {
            kuota_max: {{ (int)$quota->kuota_max }},
            terisi: {{ (int)($quota->kuota_terisi ?? 0) }}
        });
    @endforeach

    let holidays = {};
    @php
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $holidaysData = \App\Models\TicketQuota::getIndonesianHolidays($currentYear);
        $holidaysDataNext = \App\Models\TicketQuota::getIndonesianHolidays($nextYear);
        $holidaysData = array_merge($holidaysData, $holidaysDataNext);
    @endphp
    holidays = @json($holidaysData);

    function isDateInPeriod(date) {
        const d = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        const s = new Date(startPeriod.getFullYear(), startPeriod.getMonth(), startPeriod.getDate());
        if (!endPeriod) return d >= s;
        const e = new Date(endPeriod.getFullYear(), endPeriod.getMonth(), endPeriod.getDate());
        return d >= s && d <= e;
    }

    function generateCalendar(year, month) {
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        let startWeekday = firstDay.getDay() === 0 ? 7 : firstDay.getDay();
        const daysInMonth = lastDay.getDate();
        
        let html = '';
        
        for (let i = 1; i < startWeekday; i++) {
            html += '<div class="h-20 p-1 bg-gray-50 rounded-lg border border-gray-100"></div>';
        }
        
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateStr = `${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;
            
            const isHoliday = holidays[dateStr] !== undefined;
            const holidayName = isHoliday ? holidays[dateStr] : null;
            const inPeriod = isDateInPeriod(date);
            const quotaInfo = quotaMap.get(dateStr);
            
            let bg = 'bg-white';
            let border = 'border-gray-200';
            let text = 'text-gray-700';
            let badgeHtml = '';
            
            if (!inPeriod) {
                bg = 'bg-gray-50';
                border = 'border-gray-100';
                text = 'text-gray-300';
            } else if (quotaInfo) {
                const sisa = quotaInfo.kuota_max - quotaInfo.terisi;
                if (quotaInfo.kuota_max > 0 && sisa > 0) {
                    bg = 'bg-green-50';
                    border = 'border-green-200';
                    text = 'text-green-800';
                    badgeHtml = `<div class="text-[10px] mt-2 font-medium text-green-600 bg-white border border-green-100 rounded px-1 py-0.5 text-center">Sisa: ${sisa}</div>`;
                } else {
                    bg = 'bg-red-50';
                    border = 'border-red-200';
                    text = 'text-red-800';
                    badgeHtml = `<div class="text-[10px] mt-2 font-medium text-red-600 bg-white border border-red-100 rounded px-1 py-0.5 text-center">Penuh</div>`;
                }
            } else {
                bg = 'bg-gray-100';
                border = 'border-gray-200';
                text = 'text-gray-400';
                badgeHtml = `<div class="text-[10px] mt-2 font-medium text-gray-500 bg-gray-50 border rounded px-1 py-0.5 text-center">Tutup</div>`;
            }

            if (isHoliday && (!quotaInfo || quotaInfo.kuota_max === 0)) {
                bg = 'bg-red-100';
                border = 'border-red-300';
                text = 'text-red-700';
            }
            
            html += `
                <div class="h-20 p-1.5 ${bg} rounded-lg border ${border} transition relative group">
                    <div class="flex flex-col h-full justify-between">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-bold ${text}">${day}</span>
                            ${isHoliday ? `<span class="text-[10px] cursor-help" title="${holidayName}">🏛️</span>` : ''}
                        </div>
                        ${badgeHtml}
                    </div>
                    ${isHoliday ? 
                        `<div class="absolute bottom-full mb-1 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-[9px] rounded px-2 py-1 whitespace-nowrap z-20 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity">
                            ${holidayName}
                        </div>` : ''}
                </div>
            `;
        }
        
        document.getElementById('calendar-days').innerHTML = html;
        const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        document.getElementById('currentMonthYear').textContent = `${monthNames[month]} ${year}`;
        
        updateNavigation();
    }

    function updateNavigation() {
        const prev = document.getElementById('prevMonthBtn');
        const next = document.getElementById('nextMonthBtn');
        
        const cur = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const s = new Date(startPeriod.getFullYear(), startPeriod.getMonth(), 1);
        prev.disabled = cur <= s;

        if (endPeriod) {
            const e = new Date(endPeriod.getFullYear(), endPeriod.getMonth(), 1);
            next.disabled = cur >= e;
        }
    }

    document.getElementById('prevMonthBtn').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    });

    document.getElementById('nextMonthBtn').addEventListener('click', () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    });

    // Inisialisasi
    document.addEventListener('DOMContentLoaded', () => {
        if (startPeriod) {
            currentDate = new Date(startPeriod);
        }
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    });
    </script>
</x-app-layout>