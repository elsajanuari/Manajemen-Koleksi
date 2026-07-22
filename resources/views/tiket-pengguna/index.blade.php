<x-app-layout>
<div class="bg-white">
    <section class="bg-[#20314D] px-4 sm:px-6 lg:px-8 py-8 sm:py-12 border-b border-[#e8edf5]">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <div class="inline-block bg-[#eff6ff] text-[#2563eb] text-[10px] sm:text-xs font-semibold px-3 sm:px-4 py-1 rounded-full mb-2 sm:mb-3 tracking-wide">
                        <i class="fas fa-ticket-alt mr-1.5"></i> Museum MK Lesmana
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-extrabold text-[#ffff] tracking-tight leading-tight">
                        Tiket <span class="text-[#ffff]">Museum</span>
                    </h1>
                    <p class="text-[13px] sm:text-base text-[#ffff] mt-1.5 max-w-lg">
                        Workshop, pameran, dan kegiatan museum tersedia untuk Anda.
                    </p>
                </div>
                
                <a href="{{ route('pemesanan-tiket.index') }}" 
                   class="inline-flex items-center gap-2 px-5 sm:px-6 py-2.5 sm:py-3 bg-white border-2 border-[#e2e8f0] hover:border-[#2563eb] hover:text-[#2563eb] text-[#1a1a2e] text-sm font-semibold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md whitespace-nowrap">
                    <i class="fas fa-history text-[#2563eb]"></i>
                    <span>Lihat Riwayat Pemesanan Saya</span>
                </a>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-4">
        <div class="bg-white border border-[#e8edf5] rounded-2xl shadow-sm p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-5">
                
                <div>
                    <label class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-[#718096] mb-1.5 block">
                        <i class="fas fa-search mr-1.5"></i> Cari Tiket
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[#94a3b8] text-sm"></i>
                        <input type="text" id="search-input" placeholder="Nama tiket..."
                               class="w-full pl-9 pr-3 py-2.5 bg-[#f8faff] border border-[#e2e8f0] rounded-xl text-sm text-[#1a1a2e] placeholder-[#94a3b8] focus:outline-none focus:ring-2 focus:ring-[#2563eb] focus:border-transparent transition-all duration-200">
                    </div>
                </div>

                <div>
                    <label class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-[#718096] mb-1.5 block">
                        <i class="fas fa-tag mr-1.5"></i> Kategori
                    </label>
                    <select id="category-filter"
                            class="w-full px-3 py-2.5 bg-[#f8faff] border border-[#e2e8f0] rounded-xl text-sm text-[#1a1a2e] focus:outline-none focus:ring-2 focus:ring-[#2563eb] focus:border-transparent transition-all duration-200 appearance-none">
                        <option value="">Semua Kategori</option>
                        @foreach($tickets as $group => $groupTickets)
                            @if($groupTickets->count() > 0)
                                <option value="{{ strtolower($group) }}">{{ ucwords($group) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-[#718096] mb-1.5 block">
                        <i class="fas fa-calendar-day mr-1.5"></i> Tanggal Kunjungan
                    </label>
                    <div class="relative">
                        <i class="fas fa-calendar-day absolute left-3 top-1/2 -translate-y-1/2 text-[#94a3b8] text-sm z-10"></i>
                        <input type="text" 
                            id="date-filter" 
                            name="tanggal_kunjungan"
                            placeholder="Pilih Tanggal"
                            class="w-full pl-9 pr-10 py-2.5 bg-[#f8faff] border border-[#e2e8f0] rounded-xl text-sm text-[#1a1a2e] placeholder-[#94a3b8] focus:outline-none focus:ring-2 focus:ring-[#2563eb] focus:border-transparent transition-all duration-200 cursor-pointer"
                            readonly>
                    </div>
                    <div id="date-display" class="text-[10px] text-[#718096] mt-1 hidden">
                        <i class="fas fa-check-circle text-[#22c55e] mr-1"></i>
                        <span id="selected-date-text"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 sm:mt-8">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <h2 class="text-sm sm:text-lg font-bold text-[#1a1a2e]">Jelajahi Kategori</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-4">
            @foreach($tickets as $group => $groupTickets)
                @php $validTicketsCount = $groupTickets->count(); @endphp
                @if($validTicketsCount > 0)
                    @php
                        $titleMap = [
                            'reguler' => 'Reguler',
                            'sunday painting' => 'Sunday Painting',
                            'pameran' => 'Pameran Seni Rupa',
                            'workshop' => 'Workshop',
                        ];
                        $groupTitle = $titleMap[$group] ?? ucwords($group);
                        $ticket = $groupTickets->first();
                    @endphp

                    <button type="button" 
                            class="category-button group relative overflow-hidden rounded-2xl border border-[#e8edf5] hover:border-[#2563eb] text-left transition-all duration-300 shadow-sm hover:shadow-md h-16 sm:h-20"
                            data-category="{{ strtolower($group) }}" 
                            data-title="{{ $groupTitle }}">
                        @if($ticket?->gambar)
                            <img src="{{ asset('storage/gambar/' . $ticket->gambar) }}" alt=""
                                 class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:opacity-30 transition duration-300">
                        @else
                            <div class="absolute inset-0 bg-[#f8faff]"></div>
                        @endif
                        <div class="absolute inset-0 bg-white/70 group-hover:bg-white/60 transition duration-300"></div>
                        <div class="relative flex items-center gap-2 px-3 sm:px-5 h-full">
                            <div class="min-w-0">
                                <p class="text-[11px] sm:text-sm font-bold text-[#1a1a2e] truncate leading-tight">{{ $groupTitle }}</p>
                                <p class="text-[9px] sm:text-xs text-[#718096] mt-0.5">{{ $validTicketsCount }} tiket</p>
                            </div>
                            <i class="fas fa-arrow-right text-[#2563eb] ml-auto flex-shrink-0 opacity-0 group-hover:opacity-100 transition-all duration-200 transform group-hover:translate-x-1"></i>
                        </div>
                    </button>
                @endif
            @endforeach
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 sm:mt-8 pb-12 sm:pb-16">
        <div class="flex items-center justify-between mb-3 sm:mb-5">
            <h2 class="text-sm sm:text-lg font-bold text-[#1a1a2e]">Semua Tiket</h2>
        </div>

        <div id="ticket-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

            @php $hasAnyTicket = false; @endphp

            @foreach($tickets as $group => $groupTickets)
                @foreach($groupTickets as $ticket)
                    @php
                        $hasAnyTicket = true;
                        $availableDates = $ticket->quotas
                            ->filter(fn($quota) =>
                                $quota->status === 'available' &&
                                $quota->tanggal >= now()->toDateString()
                            )
                            ->pluck('tanggal')
                            ->map(fn($date) => \Carbon\Carbon::parse($date)->format('Y-m-d'))
                            ->toArray();
                        $isValid = !$ticket->isExpired();
                        $isUpcoming = $isValid && $ticket->tanggal_mulai > now()->toDateString();
                    @endphp

                    @if($isValid)
                    <article class="ticket-card bg-white border border-[#e8edf5] rounded-2xl overflow-hidden flex flex-col hover:shadow-xl transition-all duration-300 group"
                             data-category="{{ strtolower($group) }}"
                             data-name="{{ strtolower($ticket->nama_tiket) }}"
                             data-sub="{{ strtolower($ticket->sub_jenis ?? '') }}"
                             data-dates='@json($availableDates)'>
                        
                        <div class="relative h-44 sm:h-52 overflow-hidden bg-[#f8faff] flex-shrink-0">
                            @if($ticket->gambar)
                                <img src="{{ asset('storage/gambar/' . $ticket->gambar) }}" 
                                     alt="{{ $ticket->nama_tiket }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-4xl text-[#cbd5e0]"></i>
                                </div>
                            @endif

                            <div class="absolute top-3 left-3">
                                @if($isUpcoming)
                                    <span class="inline-flex items-center gap-1.5 bg-white text-[#7c3aed] border border-[#e9d5ff] text-[8px] sm:text-[10px] font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#7c3aed]"></span>
                                        Akan Datang
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-white text-[#059669] border border-[#a7f3d0] text-[8px] sm:text-[10px] font-semibold uppercase tracking-wide px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#059669]"></span>
                                        Tersedia
                                    </span>
                                @endif
                            </div>

                            <div class="absolute bottom-3 left-3">
                                <span class="bg-[#2563eb]/90 text-white text-[8px] sm:text-[10px] font-semibold px-2.5 py-1 rounded-full">
                                    {{ $ticket->jenis_tiket ?? ucwords($group) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4 sm:p-5 flex flex-col flex-1">
                            <div class="flex flex-wrap gap-1 mb-2">
                                @if($ticket->sub_jenis)
                                    <span class="text-[7px] sm:text-[9px] font-semibold uppercase tracking-wide text-[#475569] bg-[#f1f5f9] border border-[#e2e8f0] px-2 py-0.5 rounded-full">{{ $ticket->sub_jenis }}</span>
                                @endif
                                @if($ticket->kategori_pengunjung)
                                    <span class="text-[7px] sm:text-[9px] font-semibold uppercase tracking-wide text-[#059669] bg-[#ecfdf5] border border-[#a7f3d0] px-2 py-0.5 rounded-full">{{ $ticket->kategori_pengunjung }}</span>
                                @endif
                                @if($ticket->boleh_reschedule)
                                    <span class="text-[7px] sm:text-[9px] font-semibold uppercase tracking-wide text-[#d97706] bg-[#fffbeb] border border-[#fde68a] px-2 py-0.5 rounded-full">Reschedule</span>
                                @endif
                                @if($ticket->boleh_cancel)
                                    <span class="text-[7px] sm:text-[9px] font-semibold uppercase tracking-wide text-[#dc2626] bg-[#fef2f2] border border-[#fca5a5] px-2 py-0.5 rounded-full">Cancel</span>
                                @endif
                            </div>

                            <h3 class="text-sm sm:text-base font-bold text-[#1a1a2e] leading-snug line-clamp-2">
                                {{ $ticket->nama_tiket ?: 'Tiket Museum' }}
                            </h3>
                            <p class="text-[11px] sm:text-sm text-[#4a5568] mt-1.5 leading-relaxed line-clamp-2 flex-1">
                                {{ $ticket->deskripsi ?: 'Nikmati pengalaman museum yang edukatif dan interaktif.' }}
                            </p>

                            <div class="border-t border-[#f1f5f9] my-3"></div>

                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-[8px] sm:text-[10px] uppercase tracking-wider text-[#94a3b8] font-semibold">Periode</p>
                                    <p class="text-[10px] sm:text-xs text-[#1a1a2e] font-medium mt-0.5">
                                        {{ \Carbon\Carbon::parse($ticket->tanggal_mulai)->locale('id')->translatedFormat('d M Y') }}
                                        @if($ticket->tanggal_selesai)
                                            – {{ \Carbon\Carbon::parse($ticket->tanggal_selesai)->locale('id')->translatedFormat('d M Y') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[8px] sm:text-[10px] uppercase tracking-wider text-[#94a3b8] font-semibold">Harga</p>
                                    <p class="text-base sm:text-lg font-extrabold text-[#1a1a2e] mt-0.5">Rp {{ number_format($ticket->harga ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            @if($isUpcoming)
                                <a href="{{ route('tiket.show', $ticket->id) }}"
                                   class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#f1f5f9] text-[#475569] border border-[#e2e8f0] px-3 py-2.5 text-[11px] sm:text-sm font-semibold hover:bg-[#e2e8f0] transition-all duration-200">
                                    <i class="fas fa-clock"></i>
                                    Mulai {{ \Carbon\Carbon::parse($ticket->tanggal_mulai)->locale('id')->translatedFormat('d M') }}
                                </a>
                            @else
                                <a href="{{ route('tiket.show', $ticket->id) }}"
                                   class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#2563eb] hover:bg-[#1d4ed8] text-white px-3 py-2.5 text-[11px] sm:text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-ticket-alt"></i>
                                    Pilih Tiket
                                </a>
                            @endif
                        </div>
                    </article>
                    @endif
                @endforeach
            @endforeach

            @if(!$hasAnyTicket)
                <div class="col-span-full">
                    <div class="border-2 border-dashed border-[#e2e8f0] rounded-2xl p-8 sm:p-14 text-center bg-[#f8faff]">
                        <i class="fas fa-ticket-alt text-4xl text-[#cbd5e0] mb-3 block"></i>
                        <h3 class="text-base sm:text-lg font-bold text-[#1a1a2e]">Belum Ada Tiket Tersedia</h3>
                        <p class="text-sm text-[#718096] mt-1">Silakan cek kembali nanti.</p>
                    </div>
                </div>
            @endif

        </div>

        <div id="empty-state" class="hidden border-2 border-dashed border-[#e2e8f0] rounded-2xl p-8 sm:p-12 text-center bg-[#f8faff] mt-4">
            <i class="fas fa-search text-3xl text-[#cbd5e0] mb-3 block"></i>
            <p class="text-sm text-[#718096]">Tidak ada tiket yang sesuai dengan filter.</p>
        </div>

    </section>
</div>

<div id="category-overlay" 
     class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center p-3">
    <div class="bg-white rounded-2xl w-full max-w-3xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-[#e8edf5]">
            <div>
                <h2 id="overlay-title" class="text-base sm:text-xl font-bold text-[#1a1a2e]">Kategori Tiket</h2>
                <p class="text-xs sm:text-sm text-[#718096] mt-0.5">Daftar tiket tersedia.</p>
            </div>
            <button type="button" id="close-overlay" 
                    class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-[#f1f5f9] hover:bg-[#e2e8f0] transition flex items-center justify-center text-[#475569] text-xl leading-none">
                ✕
            </button>
        </div>
        <div id="overlay-ticket-list" class="p-3 sm:p-6 grid grid-cols-1 gap-3 sm:gap-4 max-h-[70vh] overflow-y-auto"></div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const categoryFilter = document.getElementById('category-filter');
    const dateInput = document.getElementById('date-filter');
    const dateDisplay = document.getElementById('date-display');
    const dateText = document.getElementById('selected-date-text');
    const cards = document.querySelectorAll('.ticket-card');
    const emptyState = document.getElementById('empty-state');
    const overlay = document.getElementById('category-overlay');
    const overlayTitle = document.getElementById('overlay-title');
    const overlayList = document.getElementById('overlay-ticket-list');

    function parseDateFromDisplay(dateStr) {
        try {
            const parts = dateStr.split('/');
            if (parts.length === 3) {
                const day = parts[0].padStart(2, '0');
                const month = parts[1].padStart(2, '0');
                const year = parts[2];
                return year + '-' + month + '-' + day;
            }
            return null;
        } catch {
            return null;
        }
    }

    function filterTickets() {
        const keyword = searchInput.value.toLowerCase();
        const category = categoryFilter.value.toLowerCase();
        const date = dateInput.value;
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name || '';
            const sub = card.dataset.sub || '';
            const cardCategory = card.dataset.category || '';
            let dates = [];
            try {
                dates = JSON.parse(card.dataset.dates || '[]');
            } catch(e) {}

            const matchKeyword = keyword === '' || name.includes(keyword) || sub.includes(keyword) || cardCategory.includes(keyword);
            const matchCategory = category === '' || cardCategory === category;
            
            let matchDate = true;
            if (date !== '') {
                const parsedDate = parseDateFromDisplay(date);
                matchDate = dates.some(d => {
                    const dateObj = new Date(d);
                    const dateStr = dateObj.toISOString().split('T')[0];
                    return dateStr === parsedDate;
                });
            }

            if (matchKeyword && matchCategory && matchDate) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        emptyState.classList.toggle('hidden', visibleCount > 0);
    }

    searchInput.addEventListener('input', filterTickets);
    categoryFilter.addEventListener('change', filterTickets);

    const fp = flatpickr(dateInput, {
        locale: "id",
        dateFormat: "d/m/Y",
        minDate: "today",
        disableMobile: true,
        placeholder: "Pilih Tanggal",
        allowInput: false,
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                const date = selectedDates[0];
                const formatted = date.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                dateText.textContent = 'Tanggal dipilih: ' + formatted;
                dateDisplay.classList.remove('hidden');
                filterTickets();
            } else {
                dateDisplay.classList.add('hidden');
                filterTickets();
            }
        },
        onClose: function(selectedDates, dateStr, instance) {
            if (!selectedDates || selectedDates.length === 0) {
                instance.clear();
                dateDisplay.classList.add('hidden');
                filterTickets();
            }
        }
    });

    const parentWrapper = dateInput.parentElement;
    const clearBtn = document.createElement('button');
    clearBtn.type = 'button';
    clearBtn.className = 'absolute right-3 top-1/2 -translate-y-1/2 text-[#94a3b8] hover:text-[#475569] z-10';
    clearBtn.innerHTML = '<i class="fas fa-times-circle"></i>';
    clearBtn.style.display = 'none';
    clearBtn.title = 'Hapus tanggal';
    parentWrapper.appendChild(clearBtn);

    clearBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        fp.clear();
        dateDisplay.classList.add('hidden');
        filterTickets();
        clearBtn.style.display = 'none';
    });

    fp.config.onChange.push(function(selectedDates) {
        clearBtn.style.display = selectedDates.length > 0 ? 'block' : 'none';
    });

    document.querySelectorAll('.category-button').forEach(button => {
        button.addEventListener('click', () => {
            const category = button.dataset.category;
            const title = button.dataset.title;

            overlayTitle.innerText = title;
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            document.body.classList.add('overflow-hidden');

            overlayList.innerHTML = '';
            cards.forEach(card => {
                if (card.dataset.category === category && !card.classList.contains('hidden')) {
                    overlayList.innerHTML += '<div class="rounded-xl border border-[#e8edf5] overflow-hidden bg-white shadow-sm">' + card.innerHTML + '</div>';
                }
            });
        });
    });

    document.getElementById('close-overlay').addEventListener('click', () => {
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    });

    filterTickets();
});
</script>
</x-app-layout>