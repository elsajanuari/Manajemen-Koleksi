{{--
    Komponen kalender READ-ONLY untuk visualisasi kuota harian.
    Dipakai di tickets/show.blade.php.

    Props:
    - ticket           (Ticket model, relasi quotas sudah di-load)
    - skipEmptyMonths  (bool) true = navigasi loncat ke bulan yang punya data kuota saja.
                        Default true, karena halaman ini murni untuk "melihat", bukan mengisi.
--}}
@props([
    'ticket',
    'skipEmptyMonths' => true,
])

@php
    $uid = 'calview_' . uniqid();

    $startDate = is_string($ticket->tanggal_mulai) ? \Carbon\Carbon::parse($ticket->tanggal_mulai) : $ticket->tanggal_mulai;
    $endDate = $ticket->tanggal_selesai
        ? (is_string($ticket->tanggal_selesai) ? \Carbon\Carbon::parse($ticket->tanggal_selesai) : $ticket->tanggal_selesai)
        : null;

    $quotaMap = [];
    foreach ($ticket->quotas as $quota) {
        $quotaDate = is_string($quota->tanggal) ? \Carbon\Carbon::parse($quota->tanggal) : $quota->tanggal;
        $quotaMap[$quotaDate->toDateString()] = [
            'kuota_max' => (int) $quota->kuota_max,
            'terisi' => (int) ($quota->kuota_terisi ?? 0),
        ];
    }

    $years = range($startDate->year, $endDate ? $endDate->year : $startDate->year);
    $holidaysData = [];
    foreach ($years as $y) {
        $holidaysData = array_merge($holidaysData, \App\Models\TicketQuota::getIndonesianHolidays($y));
    }
@endphp

<div data-calendar-root="{{ $uid }}">
    <div class="flex items-center justify-between mb-3">
        <button type="button" data-nav="prev" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-50 transition disabled:opacity-30 disabled:cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <h3 data-label="month-year" class="text-sm font-bold text-gray-800 tracking-wide"></h3>
        <button type="button" data-nav="next" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-50 transition disabled:opacity-30 disabled:cursor-not-allowed">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>

    <div class="grid grid-cols-7 gap-1 mb-1.5 bg-gray-100 rounded-lg p-1.5">
        @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
            <div class="text-center text-[10px] font-semibold text-gray-500 py-1">{{ $day }}</div>
        @endforeach
    </div>

    <div data-grid="days" class="grid grid-cols-7 gap-1"></div>

    <div class="text-[11px] text-gray-500 mt-3 flex flex-wrap gap-3 justify-center border-t pt-3">
        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Tersedia</div>
        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-200"></span> Penuh</div>
        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-gray-200"></span> Tutup</div>
        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-400"></span> Libur Nasional</div>
        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-gray-50 border border-gray-200"></span> Di Luar Periode</div>
    </div>
</div>

<script>
(function () {
    const root = document.querySelector('[data-calendar-root="{{ $uid }}"]');
    if (!root || root.dataset.bound) return;
    root.dataset.bound = '1';

    const SKIP_EMPTY = @json($skipEmptyMonths);
    const holidays = @json($holidaysData);
    const quotaMap = new Map(Object.entries(@json($quotaMap)));
    const startPeriod = new Date('{{ $startDate->toDateString() }}');
    const endPeriod = @json($endDate?->toDateString()) ? new Date(@json($endDate?->toDateString())) : null;

    const grid = root.querySelector('[data-grid="days"]');
    const label = root.querySelector('[data-label="month-year"]');
    const prevBtn = root.querySelector('[data-nav="prev"]');
    const nextBtn = root.querySelector('[data-nav="next"]');

    let currentDate = new Date(startPeriod);

    function isDateInPeriod(date) {
        const d = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        const s = new Date(startPeriod.getFullYear(), startPeriod.getMonth(), startPeriod.getDate());
        if (!endPeriod) return d >= s;
        const e = new Date(endPeriod.getFullYear(), endPeriod.getMonth(), endPeriod.getDate());
        return d >= s && d <= e;
    }

    function monthHasQuota(year, month) {
        for (let dateStr of quotaMap.keys()) {
            const d = new Date(dateStr);
            if (d.getFullYear() === year && d.getMonth() === month) return true;
        }
        return false;
    }

    function generateCalendar(year, month) {
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        let startWeekday = firstDay.getDay() === 0 ? 7 : firstDay.getDay();
        const daysInMonth = lastDay.getDate();

        let html = '';
        for (let i = 1; i < startWeekday; i++) {
            html += '<div class="h-14 bg-gray-50 rounded-lg border border-gray-100"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateStr = `${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;

            const isHoliday = holidays[dateStr] !== undefined;
            const holidayName = isHoliday ? holidays[dateStr] : null;
            const inPeriod = isDateInPeriod(date);
            const quotaInfo = quotaMap.get(dateStr);

            let bg = 'bg-white', border = 'border-gray-200', text = 'text-gray-700', badge = '';

            if (!inPeriod) {
                bg = 'bg-gray-50'; border = 'border-gray-100'; text = 'text-gray-300';
            } else if (quotaInfo) {
                const sisa = quotaInfo.kuota_max - quotaInfo.terisi;
                if (quotaInfo.kuota_max > 0 && sisa > 0) {
                    bg = 'bg-green-50'; border = 'border-green-200'; text = 'text-green-800';
                    badge = `<div class="text-[9px] mt-1 font-medium text-green-600 bg-white border border-green-100 rounded px-1 text-center">Sisa ${sisa}</div>`;
                } else {
                    bg = 'bg-red-50'; border = 'border-red-200'; text = 'text-red-800';
                    badge = `<div class="text-[9px] mt-1 font-medium text-red-600 bg-white border border-red-100 rounded px-1 text-center">Penuh</div>`;
                }
            } else {
                bg = 'bg-gray-100'; border = 'border-gray-200'; text = 'text-gray-400';
                badge = `<div class="text-[9px] mt-1 font-medium text-gray-500 bg-gray-50 border rounded px-1 text-center">Tutup</div>`;
            }

            if (isHoliday && (!quotaInfo || quotaInfo.kuota_max === 0)) {
                bg = 'bg-red-100'; border = 'border-red-300'; text = 'text-red-700';
            }

            html += `
                <div class="h-14 p-1 ${bg} rounded-lg border ${border} transition relative group">
                    <div class="flex flex-col h-full justify-between">
                        <div class="flex justify-between items-start">
                            <span class="text-[11px] font-bold ${text}">${day}</span>
                            ${isHoliday ? `<span class="text-[9px] cursor-help" title="${holidayName}">🏛️</span>` : ''}
                        </div>
                        ${badge}
                    </div>
                </div>`;
        }

        grid.innerHTML = html;
        const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        label.textContent = `${monthNames[month]} ${year}`;
        updateNavigation();
    }

    function updateNavigation() {
        const cur = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const s = new Date(startPeriod.getFullYear(), startPeriod.getMonth(), 1);
        prevBtn.disabled = cur <= s;
        if (endPeriod) {
            const e = new Date(endPeriod.getFullYear(), endPeriod.getMonth(), 1);
            nextBtn.disabled = cur >= e;
        }
    }

    function stepMonth(direction) {
        if (!SKIP_EMPTY) {
            currentDate.setMonth(currentDate.getMonth() + direction);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
            return;
        }

        const startMonthStart = new Date(startPeriod.getFullYear(), startPeriod.getMonth(), 1);
        const endMonthStart = endPeriod ? new Date(endPeriod.getFullYear(), endPeriod.getMonth(), 1) : null;
        let probe = new Date(currentDate);
        while (true) {
            probe.setMonth(probe.getMonth() + direction);
            const probeStart = new Date(probe.getFullYear(), probe.getMonth(), 1);
            if (probeStart < startMonthStart) return;
            if (endMonthStart && probeStart > endMonthStart) return;
            if (monthHasQuota(probe.getFullYear(), probe.getMonth())) {
                currentDate = probe;
                generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
                return;
            }
            // jaga-jaga supaya tidak infinite loop kalau memang tidak ada data sama sekali
            if (!endMonthStart && Math.abs(probe.getFullYear() - startPeriod.getFullYear()) > 5) return;
        }
    }

    prevBtn.addEventListener('click', () => stepMonth(-1));
    nextBtn.addEventListener('click', () => stepMonth(1));

    // Mulai dari bulan pertama yang ada datanya (jika skip-empty aktif), jika tidak ya bulan mulai periode
    if (SKIP_EMPTY && !monthHasQuota(currentDate.getFullYear(), currentDate.getMonth())) {
        stepMonth(1);
    } else {
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
    }
})();
</script>