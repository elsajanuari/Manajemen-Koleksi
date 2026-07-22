<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            @if($isAdmin)
                <a href="{{ route('dashboard') }}" class="hover:text-gray-700 transition">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-700 font-medium">Riwayat Pemesanan</span>
            @else
                <a href="{{ route('tiket.index') }}" class="hover:text-gray-700 transition">Tiket</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-700 font-medium">Daftar Pemesanan Saya</span>
            @endif
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">
                                {{ $isAdmin ? 'Riwayat Pemesanan' : 'Daftar Pemesanan Saya' }}
                            </h1>
                            <p class="text-sm text-gray-500 mt-0.5">
                                {{ $isAdmin 
                                    ? 'Kelola semua pemesanan dan proses pengembalian dana manual.' 
                                    : 'Kelola semua pemesanan tiket Anda.' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-indigo-100 text-indigo-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>
                            {{ $pemesanans->total() }} Total Pemesanan
                        </span>
                        @unless($isAdmin)
                            <a href="{{ route('tiket.index') }}"
                               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali ke Tiket
                            </a>
                        @endunless
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6">

                {{-- Alert --}}
                @if(session('success'))
                    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3.5 text-sm text-green-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Statistik --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Total</p>
                            <p class="text-xl font-bold text-gray-900">{{ $pemesanans->total() }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Lunas</p>
                            <p class="text-xl font-bold text-emerald-600">{{ $pemesanans->where('status', 'lunas')->count() }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Menunggu</p>
                            <p class="text-xl font-bold text-amber-600">{{ $pemesanans->where('status', 'menunggu_pembayaran')->count() }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Refund</p>
                            <p class="text-xl font-bold text-red-600">{{ $pemesanans->where('status', 'pengembalian_berhasil')->count() + $pemesanans->where('status', 'proses_pembatalan')->count() }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="bg-gray-50/50 rounded-xl p-4 sm:p-5 border border-gray-200 mb-6">
                    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Filter Status</label>
                            <select name="status" onchange="this.form.submit()"
                                    class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                                <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>Semua Status</option>
                                <option value="menunggu_pembayaran" {{ request('status') === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Bayar</option>
                                <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="proses_pembatalan" {{ request('status') === 'proses_pembatalan' ? 'selected' : '' }}>Proses Refund</option>
                                <option value="pengembalian_berhasil" {{ request('status') === 'pengembalian_berhasil' ? 'selected' : '' }}>Refund Berhasil</option>
                                <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Cari Pemesanan</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Cari nama pemesan atau tiket"
                                   class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                    class="flex-1 inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Cari
                            </button>
                            <a href="{{ route($isAdmin ? 'pengelola.riwayat-pemesanan.index' : 'pemesanan-tiket.index') }}"
                               class="flex-1 inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Status Colors --}}
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-700',
                        'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-700',
                        'lunas' => 'bg-green-100 text-green-700',
                        'proses_pembatalan' => 'bg-blue-100 text-blue-700',
                        'pengembalian_berhasil' => 'bg-emerald-100 text-emerald-700',
                        'dibatalkan' => 'bg-red-100 text-red-700',
                    ];

                    $statusLabels = [
                        'pending' => 'Pending',
                        'menunggu_pembayaran' => 'Menunggu Bayar',
                        'lunas' => 'Lunas',
                        'proses_pembatalan' => 'Proses Refund',
                        'pengembalian_berhasil' => 'Refund Berhasil',
                        'dibatalkan' => 'Dibatalkan',
                    ];
                @endphp

                {{-- Mobile Card View --}}
                @if($pemesanans->count())
                    <div class="md:hidden space-y-3 mb-4">
                        @foreach($pemesanans as $pemesanan)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 hover:shadow-md transition">
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <div class="min-w-0">
                                        <span class="font-mono text-xs text-gray-400">#{{ str_pad($pemesanan->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <p class="font-semibold text-gray-800 truncate">{{ $pemesanan->getRepresentativeName() }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $pemesanan->user->name }}</p>
                                    </div>
                                    <span class="flex-shrink-0 inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $statusColors[$pemesanan->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $statusLabels[$pemesanan->status] ?? $pemesanan->status }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-y-2 gap-x-3 text-sm border-t border-gray-100 pt-3">
                                    <div class="col-span-2">
                                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Tiket</p>
                                        <p class="text-gray-800 truncate">{{ $pemesanan->ticket->nama_tiket ?? 'Tiket tidak tersedia' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Kunjungan</p>
                                        <p class="text-gray-800">{{ $pemesanan->tanggal_pemesanan->locale('id')->translatedFormat('d M Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Jumlah</p>
                                        <p class="text-gray-800">{{ $pemesanan->jumlah_tiket }} tiket</p>
                                    </div>
                                    <div class="col-span-2">
                                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total</p>
                                        <p class="font-bold text-gray-900">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                                    </div>
                                    @if($isAdmin && $pemesanan->no_rekening_refund)
                                        <div class="col-span-2">
                                            <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Rekening Refund</p>
                                            <p class="text-gray-800">{{ $pemesanan->no_rekening_refund }}</p>
                                            @if($pemesanan->refund_requested_at)
                                                <p class="text-xs text-gray-400">{{ $pemesanan->refund_requested_at->locale('id')->translatedFormat('d M Y H:i') }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="border-t border-gray-100 mt-3 pt-3">
                                    @if($isAdmin)
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if($pemesanan->status === 'proses_pembatalan')
                                                <a href="{{ route('pengelola.detail-refund', $pemesanan) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Detail Refund
                                                </a>
                                            @elseif($pemesanan->status === 'pengembalian_berhasil')
                                                <span class="text-xs font-semibold text-emerald-600 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Refund Selesai
                                                </span>
                                                @if($pemesanan->bukti_pengembalian)
                                                    <button onclick="openRefundModal('{{ asset('storage/' . $pemesanan->bukti_pengembalian) }}')"
                                                            class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:text-blue-700 hover:underline transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        Lihat Bukti
                                                    </button>
                                                @endif
                                            @else
                                                <span class="text-xs text-gray-400">-</span>
                                            @endif
                                        </div>

                                        @if($pemesanan->status === 'proses_pembatalan')
                                            <form action="{{ route('pengelola.riwayat-pemesanan.kirim', $pemesanan) }}"
                                                  method="POST" enctype="multipart/form-data" class="w-full mt-2">
                                                @csrf
                                                <label class="relative block w-full cursor-pointer rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-3 py-2 text-center text-xs text-gray-500 hover:border-blue-400 hover:bg-blue-50 transition group">
                                                    <input type="file" name="bukti_pengembalian"
                                                           accept="image/*,application/pdf"
                                                           required
                                                           class="absolute inset-0 cursor-pointer opacity-0"
                                                           onchange="updateFileName(this, 'file-label-m-{{ $pemesanan->id }}')">
                                                    <span id="file-label-m-{{ $pemesanan->id }}" class="flex items-center justify-center gap-1.5">
                                                        <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                        </svg>
                                                        <span>Pilih Bukti</span>
                                                    </span>
                                                </label>
                                                <button type="submit"
                                                        class="mt-2 w-full rounded-lg bg-blue-600 hover:bg-blue-700 px-3 py-2 text-xs font-semibold text-white transition">
                                                    <svg class="w-3.5 h-3.5 inline mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Kirim Refund
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    @unless($isAdmin)
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('pemesanan-tiket.show', $pemesanan) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </a>
                                            @if($pemesanan->dapatCancel())
                                                <a href="{{ route('pemesanan-tiket.batalkan.form', $pemesanan) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Batalkan
                                                </a>
                                            @endif
                                            @if($pemesanan->dapatReschedule())
                                                <a href="{{ route('pemesanan-tiket.reschedule', $pemesanan) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-purple-50 text-purple-600 hover:bg-purple-100 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Reschedule
                                                </a>
                                            @endif
                                        </div>
                                    @endunless
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Desktop Table View --}}
                    <div class="hidden md:block bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <th class="px-4 py-3 text-center">ID</th>
                                        <th class="px-4 py-3 text-left">Pemesan</th>
                                        <th class="px-4 py-3 text-left">Tiket</th>
                                        <th class="px-4 py-3 text-center">Kunjungan</th>
                                        <th class="px-4 py-3 text-center">Jumlah</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                        <th class="px-4 py-3 text-center">Status</th>
                                        @if($isAdmin)
                                            <th class="px-4 py-3 text-center">Refund</th>
                                        @endif
                                        <th class="px-4 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($pemesanans as $pemesanan)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 text-center">
                                                <span class="font-mono text-xs text-gray-400">#{{ str_pad($pemesanan->id, 5, '0', STR_PAD_LEFT) }}</span>
                                            </td>

                                            <td class="px-4 py-3">
                                                <span class="font-medium text-gray-800">{{ $pemesanan->getRepresentativeName() }}</span>
                                                <div class="text-xs text-gray-500">{{ $pemesanan->user->name }}</div>
                                            </td>

                                            <td class="px-4 py-3">
                                                <span class="text-gray-800">{{ $pemesanan->ticket->nama_tiket ?? 'Tiket tidak tersedia' }}</span>
                                            </td>

                                            <td class="px-4 py-3 text-center text-gray-800">
                                                {{ $pemesanan->tanggal_pemesanan->locale('id')->translatedFormat('d M Y') }}
                                            </td>

                                            <td class="px-4 py-3 text-center text-gray-600">
                                                {{ $pemesanan->jumlah_tiket }}
                                            </td>

                                            <td class="px-4 py-3 text-right font-bold text-gray-900">
                                                Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                                            </td>

                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $statusColors[$pemesanan->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                    {{ $statusLabels[$pemesanan->status] ?? $pemesanan->status }}
                                                </span>
                                            </td>

                                            @if($isAdmin)
                                                <td class="px-4 py-3 text-center">
                                                    @if($pemesanan->no_rekening_refund)
                                                        <div class="text-xs text-gray-600">
                                                            <span class="font-medium">{{ $pemesanan->no_rekening_refund }}</span>
                                                            @if($pemesanan->refund_requested_at)
                                                                <div class="text-[10px] text-gray-400">
                                                                    {{ $pemesanan->refund_requested_at->locale('id')->translatedFormat('d M Y H:i') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                            @endif

                                            <td class="px-4 py-3 text-center">
                                                <div class="flex flex-col items-center gap-1">
                                                    @if($isAdmin)
                                                        @if($pemesanan->status === 'proses_pembatalan')
                                                            <a href="{{ route('pengelola.detail-refund', $pemesanan) }}"
                                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                </svg>
                                                                Detail Refund
                                                            </a>
                                                        @endif

                                                        @if($pemesanan->status === 'proses_pembatalan')
                                                            <form action="{{ route('pengelola.riwayat-pemesanan.kirim', $pemesanan) }}"
                                                                  method="POST" enctype="multipart/form-data" class="w-full">
                                                                @csrf
                                                                <div class="w-full">
                                                                    <label class="relative block w-full cursor-pointer rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 px-3 py-1.5 text-center text-xs text-gray-500 hover:border-blue-400 hover:bg-blue-50 transition group">
                                                                        <input type="file" name="bukti_pengembalian"
                                                                               accept="image/*,application/pdf"
                                                                               required
                                                                               class="absolute inset-0 cursor-pointer opacity-0"
                                                                               onchange="updateFileName(this, 'file-label-{{ $pemesanan->id }}')">
                                                                        <span id="file-label-{{ $pemesanan->id }}" class="flex items-center justify-center gap-1">
                                                                            <svg class="w-3.5 h-3.5 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                                            </svg>
                                                                            <span>Pilih Bukti</span>
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                                <button type="submit"
                                                                        class="mt-1 w-full rounded-lg bg-blue-600 hover:bg-blue-700 px-3 py-1.5 text-xs font-semibold text-white transition">
                                                                    <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                                    </svg>
                                                                    Kirim Refund
                                                                </button>
                                                            </form>
                                                        @elseif($pemesanan->status === 'pengembalian_berhasil')
                                                            <span class="text-xs font-semibold text-emerald-600 flex items-center gap-1">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                Refund Selesai
                                                            </span>
                                                            @if($pemesanan->bukti_pengembalian)
                                                                <button onclick="openRefundModal('{{ asset('storage/' . $pemesanan->bukti_pengembalian) }}')"
                                                                        class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 hover:text-blue-700 hover:underline transition">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                    </svg>
                                                                    Lihat Bukti
                                                                </button>
                                                            @endif
                                                        @else
                                                            <span class="text-xs text-gray-400">-</span>
                                                        @endif
                                                    @else
                                                        <div class="flex flex-col items-center gap-0.5">
                                                            <a href="{{ route('pemesanan-tiket.show', $pemesanan) }}"
                                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                </svg>
                                                                Detail
                                                            </a>
                                                            @if($pemesanan->dapatCancel())
                                                                <a href="{{ route('pemesanan-tiket.batalkan.form', $pemesanan) }}"
                                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                    Batalkan
                                                                </a>
                                                            @endif
                                                            @if($pemesanan->dapatReschedule())
                                                                <a href="{{ route('pemesanan-tiket.reschedule', $pemesanan) }}"
                                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-purple-50 text-purple-600 hover:bg-purple-100 transition">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                    Reschedule
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div class="bg-white rounded-xl border border-gray-200 px-4 py-3 mt-3 md:mt-0 md:border-t-0 md:rounded-t-none flex flex-col sm:flex-row justify-between items-center gap-2">
                        <span class="text-xs text-gray-500 text-center sm:text-left">
                            Menampilkan {{ $pemesanans->firstItem() ?? 0 }} - {{ $pemesanans->lastItem() ?? 0 }} 
                            dari {{ $pemesanans->total() }} data
                        </span>
                        {{ $pemesanans->withQueryString()->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 px-6 py-16 text-center">
                        <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <p class="mb-2 font-bold text-gray-800">Tidak ada pemesanan untuk ditampilkan.</p>
                        @unless($isAdmin)
                            <p class="mb-4 text-sm text-gray-500">Mulai pesan tiket sekarang untuk mengunjungi museum!</p>
                            <a href="{{ route('tiket.index') }}" 
                               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 transition shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                Lihat Tiket Tersedia
                            </a>
                        @endunless
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Modal Refund --}}
    <div id="refundModal" 
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4 transition-opacity duration-300"
         onclick="closeRefundModal(event)">
        <div class="relative max-w-4xl w-full mx-auto" onclick="event.stopPropagation()">
            <button onclick="closeRefundModal()"
                    class="absolute -top-12 right-0 text-white hover:text-gray-300 transition text-4xl font-light">
                &times;
            </button>
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <svg class="w-5 h-5 inline mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Bukti Transfer Refund
                    </h3>
                    <button onclick="closeRefundModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-4 bg-gray-50 flex items-center justify-center" style="min-height: 400px;">
                    <img id="refundImage" src="" alt="Bukti Transfer Refund"
                         class="max-w-full max-h-[70vh] rounded-lg shadow-md object-contain" style="display: none;">
                    <div id="refundLoading" class="flex flex-col items-center justify-center py-12">
                        <svg class="w-12 h-12 text-gray-300 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <p class="mt-3 text-sm text-gray-500">Memuat gambar...</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <span class="text-xs text-gray-500">Klik di luar modal untuk menutup</span>
                    <div class="flex gap-2">
                        <button onclick="closeRefundModal()"
                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition text-sm font-medium">
                            Tutup
                        </button>
                        <a id="refundDownloadLink" href="#" target="_blank"
                           class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Buka di Tab Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFileName(input, labelId) {
            const label = document.getElementById(labelId);
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                label.innerHTML = `
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-emerald-600 font-medium">${fileName}</span>
                `;
                label.closest('label').classList.add('border-emerald-400', 'bg-emerald-50');
            }
        }

        function openRefundModal(imageUrl) {
            const modal = document.getElementById('refundModal');
            const image = document.getElementById('refundImage');
            const loading = document.getElementById('refundLoading');
            const downloadLink = document.getElementById('refundDownloadLink');

            image.style.display = 'none';
            loading.style.display = 'flex';
            downloadLink.href = imageUrl;

            const img = new Image();
            img.onload = function() {
                image.src = imageUrl;
                image.style.display = 'block';
                loading.style.display = 'none';
            };
            img.onerror = function() {
                loading.innerHTML = `
                    <svg class="w-12 h-12 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-3 text-sm text-red-500">Gagal memuat gambar.</p>
                    <a href="${imageUrl}" target="_blank" class="mt-2 text-blue-500 hover:underline text-sm">Buka di tab baru</a>
                `;
            };
            img.src = imageUrl;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeRefundModal(event) {
            if (event && event.target !== document.getElementById('refundModal')) return;
            const modal = document.getElementById('refundModal');
            const image = document.getElementById('refundImage');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            image.src = '';
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeRefundModal();
        });

        const style = document.createElement('style');
        style.textContent = `
            #refundModal { backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }
            #refundModal .max-w-4xl { animation: modalFadeIn 0.3s ease-out; }
            @keyframes modalFadeIn {
                from { opacity: 0; transform: scale(0.95) translateY(10px); }
                to { opacity: 1; transform: scale(1) translateY(0); }
            }
            .animate-spin { animation: spin 1s linear infinite; }
            @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>