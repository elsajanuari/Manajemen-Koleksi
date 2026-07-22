{{--
    Komponen kalender ketersediaan (EDITABLE).
    Dipakai di tickets/create.blade.php dan tickets/edit.blade.php.

    Props:
    - scheduleConfigName  (string)  nama hidden input, default 'schedule_config'
    - startInputId        (string)  id input tanggal_mulai yang sudah ada di form
    - endInputId           (string)  id input tanggal_selesai yang sudah ada di form
    - existingDates        (array)   tanggal yang SUDAH tersimpan & berstatus tersedia (untuk edit)
    - skipEmptyMonths      (bool)    true = navigasi bulan loncat ke bulan yang ada datanya saja
                                      (default false — create & edit tetap tampilkan semua bulan
                                      dalam periode supaya pengelola bisa menambah tanggal baru)
--}}
@props([
    'scheduleConfigName' => 'schedule_config',
    'startInputId' => 'tanggal_mulai_input',
    'endInputId' => 'tanggal_selesai_input',
    'existingDates' => [],
    'skipEmptyMonths' => false,
])

@php
    $uid = 'cal_' . uniqid();
    $currentYear = date('Y');
    $nextYear = $currentYear + 1;
    $holidaysData = array_merge(
        \App\Models\TicketQuota::getIndonesianHolidays($currentYear),
        \App\Models\TicketQuota::getIndonesianHolidays($nextYear)
    );
@endphp

<div data-calendar-root="{{ $uid }}" class="space-y-4 sm:space-y-5">

    {{-- Tombol Cepat --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-3 sm:p-3.5">
        <div class="grid grid-cols-2 gap-1.5 sm:gap-2">
            <button type="button" data-action="all-true" class="px-2 sm:px-3 py-1.5 sm:py-2 bg-blue-600 text-white rounded-lg text-[10px] sm:text-xs font-medium hover:bg-blue-700 transition shadow-sm whitespace-nowrap">
                Ya (Semua Hari)
            </button>
            <button type="button" data-action="all-false" class="px-2 sm:px-3 py-1.5 sm:py-2 bg-gray-200 text-gray-700 rounded-lg text-[10px] sm:text-xs font-medium hover:bg-gray-300 transition whitespace-nowrap">
                Tidak (Semua Hari)
            </button>
            <button type="button" data-action="month-true" class="px-2 sm:px-3 py-1.5 sm:py-2 bg-green-600 text-white rounded-lg text-[10px] sm:text-xs font-medium hover:bg-green-700 transition shadow-sm whitespace-nowrap">
                Ya (Bulan Ini)
            </button>
            <button type="button" data-action="reset" class="px-2 sm:px-3 py-1.5 sm:py-2 bg-amber-600 text-white rounded-lg text-[10px] sm:text-xs font-medium hover:bg-amber-700 transition shadow-sm whitespace-nowrap">
                Reset Semua
            </button>
        </div>
    </div>

    {{-- Tombol Per Hari --}}
    <div class="bg-gray-50 rounded-xl p-3 sm:p-3.5">
        <p class="text-[10px] sm:text-xs font-semibold text-gray-700 mb-2 sm:mb-2.5">Pilih Semua per Hari</p>
        <div class="grid grid-cols-7 gap-1 sm:gap-1.5">
            @php
                $hariIndo = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                $hariNum = [1, 2, 3, 4, 5, 6, 0];
            @endphp
            @foreach($hariIndo as $idx => $nama)
                <button type="button" class="day-btn px-0.5 sm:px-1 py-1 sm:py-1.5 rounded-lg text-[9px] sm:text-[11px] font-medium transition-all border text-center"
                    data-day="{{ $hariNum[$idx] }}"
                    data-state="none"
                    style="background: white; border-color: #e2e8f0; color: #4a5568; min-height: 28px; min-width: 0;">
                    {{ $nama }}
                </button>
            @endforeach
        </div>
        <p class="text-[9px] sm:text-[10px] text-gray-400 mt-1.5 sm:mt-2 text-center sm:text-left">
            <span class="text-green-600">Hijau</span> = semua tersedia ·
            <span class="text-red-500">Merah</span> = semua tutup ·
            <span class="text-gray-400">Abu-abu</span> = belum diatur
        </p>
    </div>

    {{-- Kalender --}}
    <div>
        <div class="flex items-center justify-between mb-2 sm:mb-3">
            <button type="button" data-nav="prev" class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-50 transition disabled:opacity-30 disabled:cursor-not-allowed flex-shrink-0">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <h3 data-label="month-year" class="text-xs sm:text-sm font-bold text-gray-800 tracking-wide text-center flex-1"></h3>
            <button type="button" data-nav="next" class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-50 transition disabled:opacity-30 disabled:cursor-not-allowed flex-shrink-0">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-7 gap-0.5 sm:gap-1 mb-1 sm:mb-1.5 bg-gray-100 rounded-lg p-1 sm:p-1.5">
            @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                <div class="text-center text-[8px] sm:text-[10px] font-semibold text-gray-500 py-0.5 sm:py-1">{{ $day }}</div>
            @endforeach
        </div>

        <div data-grid="days" class="grid grid-cols-7 gap-0.5 sm:gap-1"></div>
    </div>

    <div data-label="count" class="text-center text-[10px] sm:text-xs font-medium p-1.5 rounded-lg"></div>

    <div class="text-[9px] sm:text-[11px] text-gray-500 flex flex-wrap gap-1.5 sm:gap-3 justify-center">
        <div class="flex items-center gap-1 sm:gap-1.5"><span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-green-500"></span> Tersedia</div>
        <div class="flex items-center gap-1 sm:gap-1.5"><span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-gray-300"></span> Tidak Tersedia</div>
        <div class="flex items-center gap-1 sm:gap-1.5"><span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-red-400"></span> Libur Nasional</div>
        <div class="flex items-center gap-1 sm:gap-1.5"><span class="w-2 h-2 sm:w-2.5 sm:h-2.5 rounded-full bg-gray-100 border border-gray-200"></span> Di Luar Periode</div>
    </div>

    <input type="hidden" name="{{ $scheduleConfigName }}" data-output="schedule_config">
</div>

<script>
(function () {
    const root = document.querySelector('[data-calendar-root="{{ $uid }}"]');
    if (!root || root.dataset.bound) return;
    root.dataset.bound = '1';

    const SKIP_EMPTY = @json($skipEmptyMonths);
    const holidays = @json($holidaysData);
    const selectedDates = new Map();
    @foreach($existingDates as $d)
        selectedDates.set('{{ $d }}', true);
    @endforeach

    const startInput = document.getElementById('{{ $startInputId }}');
    const endInput = document.getElementById('{{ $endInputId }}');
    const outputInput = root.querySelector('[data-output="schedule_config"]');
    const grid = root.querySelector('[data-grid="days"]');
    const label = root.querySelector('[data-label="month-year"]');
    const countLabel = root.querySelector('[data-label="count"]');
    const prevBtn = root.querySelector('[data-nav="prev"]');
    const nextBtn = root.querySelector('[data-nav="next"]');

    let currentDate = new Date();
    let startPeriod = null;
    let endPeriod = null;

    function isDateInPeriod(date) {
        if (!startPeriod || !endPeriod) return false;
        const d = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        const s = new Date(startPeriod.getFullYear(), startPeriod.getMonth(), startPeriod.getDate());
        const e = new Date(endPeriod.getFullYear(), endPeriod.getMonth(), endPeriod.getDate());
        return d >= s && d <= e;
    }

    function monthHasData(year, month) {
        for (let [dateStr] of selectedDates) {
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
            html += '<div class="h-12 sm:h-14 bg-gray-50 rounded-lg border border-gray-100"></div>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateStr = `${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;

            const isHoliday = holidays[dateStr] !== undefined;
            const holidayName = isHoliday ? holidays[dateStr] : null;
            const inPeriod = isDateInPeriod(date);
            const isSelected = selectedDates.get(dateStr) === true;
            const isUnselected = selectedDates.get(dateStr) === false;
            const dayOfWeek = date.getDay();
            const dayIndex = dayOfWeek === 0 ? 7 : dayOfWeek;

            let bg = 'bg-white', border = 'border-gray-200', text = 'text-gray-700', disabled = '', selectDisabled = '';

            if (!inPeriod) {
                bg = 'bg-gray-100'; border = 'border-gray-200'; text = 'text-gray-400';
                disabled = 'opacity-50 cursor-not-allowed'; selectDisabled = 'disabled';
            } else if (isHoliday && !isSelected && !isUnselected) {
                bg = 'bg-red-100'; border = 'border-red-300'; text = 'text-red-700';
            } else if (isSelected) {
                bg = 'bg-green-500'; border = 'border-green-600'; text = 'text-white';
            } else if (isUnselected) {
                bg = 'bg-gray-200'; border = 'border-gray-300'; text = 'text-gray-500';
            }

            html += `
                <div class="h-12 sm:h-14 p-0.5 sm:p-1 ${bg} rounded-lg border ${border} ${disabled} transition relative date-cell"
                     data-date="${dateStr}" data-day="${dayIndex}">
                    <div class="flex flex-col h-full justify-between">
                        <div class="flex justify-between items-start px-0.5 sm:px-1 pt-0.5 sm:pt-1">
                            <span class="text-[10px] sm:text-[11px] font-semibold ${text}">${day}</span>
                            ${isHoliday && !isSelected && !isUnselected ? `<span class="text-[7px] sm:text-[8px]" title="${holidayName}">🏛️</span>` : ''}
                        </div>
                        <div class="px-0.5 sm:px-1 pb-0.5 sm:pb-1">
                            <select class="text-[7px] sm:text-[8px] w-full rounded leading-tight ${bg === 'bg-green-500' ? 'bg-green-600 text-white border-green-700' : 'bg-gray-50 border-gray-200'} date-select focus:outline-none focus:ring-1 focus:ring-blue-500"
                                    data-date="${dateStr}" ${selectDisabled}>
                                <option value="" ${!isSelected && !isUnselected && !selectDisabled ? 'selected' : ''}>Pilih</option>
                                <option value="1" ${isSelected ? 'selected' : ''}>✓ Ya</option>
                                <option value="0" ${isUnselected ? 'selected' : ''}>✗ Tidak</option>
                            </select>
                        </div>
                    </div>
                </div>`;
        }

        grid.innerHTML = html;
        const monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        label.textContent = `${monthNames[month]} ${year}`;

        grid.querySelectorAll('.date-select').forEach(sel => {
            sel.addEventListener('change', function () { updateDate(this.dataset.date, this.value); });
        });

        updateNavigation();
        updateCount();
        updateDayButtons();
    }

    function updateNavigation() {
        if (!startPeriod || !endPeriod) return;
        const cur = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        const s = new Date(startPeriod.getFullYear(), startPeriod.getMonth(), 1);
        const e = new Date(endPeriod.getFullYear(), endPeriod.getMonth(), 1);
        prevBtn.disabled = cur <= s;
        nextBtn.disabled = cur >= e;
    }

    function updateCount() {
        let count = 0;
        for (let [_, v] of selectedDates) if (v === true) count++;
        countLabel.innerHTML = count > 0
            ? `<span class="text-green-600 bg-green-50 px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs">${count} tanggal tersedia</span>`
            : `<span class="text-red-500 bg-red-50 px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs">Belum pilih tanggal</span>`;
    }

    function updateDayButtons() {
        if (!startPeriod || !endPeriod) return;
        root.querySelectorAll('.day-btn').forEach(btn => {
            const day = parseInt(btn.dataset.day);
            let allTrue = true, allFalse = true, hasDate = false;
            for (let d = new Date(startPeriod); d <= endPeriod; d.setDate(d.getDate() + 1)) {
                if (d.getDay() === day) {
                    hasDate = true;
                    const dateStr = d.toISOString().split('T')[0];
                    const val = selectedDates.get(dateStr);
                    if (val !== true) allTrue = false;
                    if (val !== false) allFalse = false;
                }
            }
            const base = btn.textContent.replace(/[✓✗~]/g, '').trim();
            if (!hasDate) {
                btn.style.background = '#f3f4f6'; btn.style.color = '#9ca3af'; btn.style.borderColor = '#e5e7eb';
                btn.dataset.state = 'none'; btn.textContent = base;
            } else if (allTrue && !allFalse) {
                btn.style.background = '#22c55e'; btn.style.color = 'white'; btn.style.borderColor = '#22c55e';
                btn.dataset.state = 'true'; btn.textContent = '✓ ' + base;
            } else if (!allTrue && allFalse) {
                btn.style.background = '#ef4444'; btn.style.color = 'white'; btn.style.borderColor = '#ef4444';
                btn.dataset.state = 'false'; btn.textContent = '✗ ' + base;
            } else {
                btn.style.background = '#f59e0b'; btn.style.color = 'white'; btn.style.borderColor = '#f59e0b';
                btn.dataset.state = 'mixed'; btn.textContent = '~ ' + base;
            }
        });
    }

    function updateDate(dateStr, value) {
        if (value === '1') selectedDates.set(dateStr, true);
        else if (value === '0') selectedDates.set(dateStr, false);
        else selectedDates.delete(dateStr);
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        updateScheduleConfig();
    }

    function selectByDay(dayNum, state) {
        if (!startPeriod || !endPeriod) { alert('Isi periode tanggal mulai dan selesai dulu!'); return; }
        let newState;
        if (state === 'true') newState = false;
        else if (state === 'false') newState = null;
        else newState = true;

        for (let d = new Date(startPeriod); d <= endPeriod; d.setDate(d.getDate() + 1)) {
            if (d.getDay() === dayNum) {
                const dateStr = d.toISOString().split('T')[0];
                if (newState === null) selectedDates.delete(dateStr);
                else selectedDates.set(dateStr, newState);
            }
        }
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        updateScheduleConfig();
    }

    function setAllDays(available) {
        if (!startPeriod || !endPeriod) { alert('Isi periode tanggal mulai dan selesai dulu!'); return; }
        for (let d = new Date(startPeriod); d <= endPeriod; d.setDate(d.getDate() + 1)) {
            selectedDates.set(d.toISOString().split('T')[0], available);
        }
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        updateScheduleConfig();
    }

    function setAllDaysInMonth() {
        if (!startPeriod || !endPeriod) { alert('Isi periode tanggal mulai dan selesai dulu!'); return; }
        const year = currentDate.getFullYear(), month = currentDate.getMonth();
        let count = 0;
        for (let d = new Date(startPeriod); d <= endPeriod; d.setDate(d.getDate() + 1)) {
            if (d.getFullYear() === year && d.getMonth() === month) {
                selectedDates.set(d.toISOString().split('T')[0], true);
                count++;
            }
        }
        generateCalendar(year, month);
        updateScheduleConfig();
        alert(`${count} tanggal pada bulan ini dipilih sebagai tersedia`);
    }

    function resetAllDays() {
        if (!startPeriod || !endPeriod) { alert('Isi periode tanggal mulai dan selesai dulu!'); return; }
        for (let d = new Date(startPeriod); d <= endPeriod; d.setDate(d.getDate() + 1)) {
            selectedDates.delete(d.toISOString().split('T')[0]);
        }
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        updateScheduleConfig();
    }

    function updateScheduleConfig() {
        const available = [];
        for (let [date, val] of selectedDates) if (val === true) available.push(date);
        outputInput.value = JSON.stringify({
            availableDates: available,
            startDate: startPeriod ? startPeriod.toISOString().split('T')[0] : '',
            endDate: endPeriod ? endPeriod.toISOString().split('T')[0] : ''
        });
    }

    function updatePeriod() {
        const s = startInput.value, e = endInput.value;
        if (s && e) {
            startPeriod = new Date(s);
            endPeriod = new Date(e);
            if (endPeriod < startPeriod) {
                alert('Tanggal selesai tidak boleh kurang dari tanggal mulai!');
                endInput.value = s;
                endPeriod = new Date(s);
            }
            for (let [dateStr] of selectedDates) {
                const d = new Date(dateStr);
                if (d < startPeriod || d > endPeriod) selectedDates.delete(dateStr);
            }
            if (startPeriod.getTime() === endPeriod.getTime()) {
                selectedDates.set(startPeriod.toISOString().split('T')[0], true);
            }
            currentDate = new Date(startPeriod);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        } else if (s) {
            startPeriod = new Date(s);
            currentDate = new Date(startPeriod);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        }
        updateScheduleConfig();
    }

    function stepMonth(direction) {
        if (!startPeriod || !endPeriod) {
            currentDate.setMonth(currentDate.getMonth() + direction);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
            return;
        }

        if (SKIP_EMPTY) {
            const startMonthStart = new Date(startPeriod.getFullYear(), startPeriod.getMonth(), 1);
            const endMonthStart = new Date(endPeriod.getFullYear(), endPeriod.getMonth(), 1);
            let probe = new Date(currentDate);
            while (true) {
                probe.setMonth(probe.getMonth() + direction);
                const probeStart = new Date(probe.getFullYear(), probe.getMonth(), 1);
                if (probeStart < startMonthStart || probeStart > endMonthStart) return;
                if (monthHasData(probe.getFullYear(), probe.getMonth())) {
                    currentDate = probe;
                    generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
                    return;
                }
            }
        }

        const newDate = new Date(currentDate);
        newDate.setMonth(currentDate.getMonth() + direction);
        const newMonthStart = new Date(newDate.getFullYear(), newDate.getMonth(), 1);
        const startMonthStart = new Date(startPeriod.getFullYear(), startPeriod.getMonth(), 1);
        const endMonthStart = new Date(endPeriod.getFullYear(), endPeriod.getMonth(), 1);
        if (newMonthStart >= startMonthStart && newMonthStart <= endMonthStart) {
            currentDate = newDate;
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        }
    }

    startInput?.addEventListener('change', updatePeriod);
    endInput?.addEventListener('change', updatePeriod);
    prevBtn.addEventListener('click', () => stepMonth(-1));
    nextBtn.addEventListener('click', () => stepMonth(1));

    root.querySelectorAll('.day-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            selectByDay(parseInt(this.dataset.day), this.dataset.state);
        });
    });

    root.querySelector('[data-action="all-true"]').addEventListener('click', () => setAllDays(true));
    root.querySelector('[data-action="all-false"]').addEventListener('click', () => setAllDays(false));
    root.querySelector('[data-action="month-true"]').addEventListener('click', setAllDaysInMonth);
    root.querySelector('[data-action="reset"]').addEventListener('click', resetAllDays);

    // Expose a hook so the parent form's submit handler can force-sync before posting
    root.syncScheduleConfig = updateScheduleConfig;
    root.hasAnyAvailableDate = function () {
        for (let [_, v] of selectedDates) if (v === true) return true;
        return false;
    };

    // Init
    const today = new Date();
    currentDate = new Date(today.getFullYear(), today.getMonth(), 1);
    if (startInput?.value) { startPeriod = new Date(startInput.value); currentDate = new Date(startPeriod); }
    if (endInput?.value) { endPeriod = new Date(endInput.value); }
    generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
})();
</script>