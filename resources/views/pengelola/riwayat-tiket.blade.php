<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <span class="text-gray-700 font-medium">Riwayat Tiket</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Riwayat Tiket</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Daftar tiket yang sudah lunas beserta status scan tiket per pengunjung</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-indigo-100 text-indigo-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>
                            {{ $riwayat->total() }} Total Tiket
                        </span>
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

                @if(session('warning'))
                    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3.5 text-sm text-amber-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        {{ session('warning') }}
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

                {{-- Filter --}}
                <div class="bg-gray-50/50 rounded-xl p-4 sm:p-5 border border-gray-200 mb-6">
                    <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Status</label>
                            <select name="status" onchange="this.form.submit()"
                                    class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua Tiket</option>
                                <option value="unused" {{ $statusFilter === 'unused' ? 'selected' : '' }}>Belum Discan</option>
                                <option value="used" {{ $statusFilter === 'used' ? 'selected' : '' }}>Sudah Discan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Urutkan</label>
                            <select name="sort" onchange="this.form.submit()"
                                    class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                                <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Cari</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Cari nama / tiket..."
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
                            <a href="{{ route('pengelola.riwayat-tiket.index') }}"
                               class="flex-1 inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Statistik Filter --}}
                @php
                    $totalUsed = $riwayat->filter(fn($d) => !is_null($d->tiket_terpakai_at))->count();
                    $totalUnused = $riwayat->filter(fn($d) => is_null($d->tiket_terpakai_at))->count();
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Total Tiket</p>
                            <p class="text-lg font-bold text-gray-900">{{ $riwayat->total() }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Belum Discan</p>
                            <p class="text-lg font-bold text-emerald-600">{{ $totalUnused }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Sudah Discan</p>
                            <p class="text-lg font-bold text-amber-600">{{ $totalUsed }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- List Tiket --}}
                @if($riwayat->count())
                    <div class="space-y-4">
                        @foreach($riwayat as $detail)
                            @php
                                $pemesanan = $detail->pemesananTiket;
                                $isUsed = !is_null($detail->tiket_terpakai_at);
                                $ticketExists = $pemesanan && $pemesanan->ticket;
                            @endphp

                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                                {{-- Header Card --}}
                                <div class="px-4 sm:px-5 py-3 sm:py-4 bg-gray-50 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <span class="font-mono text-sm font-semibold text-gray-700">
                                            #{{ str_pad((string) $pemesanan?->id ?? 0, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-700">
                                            Lunas
                                        </span>
                                        @if($isUsed)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-700">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Discan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-700">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Belum Discan
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        @if($ticketExists)
                                            <span class="font-medium text-gray-700">{{ $pemesanan->ticket->nama_tiket }}</span>
                                        @else
                                            <span class="font-medium text-red-500">[Tiket Dihapus]</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Body Card --}}
                                <div class="px-4 sm:px-5 py-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-wider">Pengunjung</p>
                                        <p class="mt-1 text-sm font-medium text-gray-800">{{ $detail->getDisplayName() }}</p>
                                        <p class="text-xs text-gray-400">Pemesan: {{ $pemesanan?->user?->name ?? 'Tidak Diketahui' }}</p>
                                    </div>

                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-wider">Kunjungan</p>
                                        <p class="mt-1 text-sm font-medium text-gray-800">
                                            {{ $pemesanan?->tanggal_pemesanan ? $pemesanan->tanggal_pemesanan->locale('id')->translatedFormat('d F Y') : '-' }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            Tiket #{{ $detail->urutan_pengunjung }} dari {{ $pemesanan?->jumlah_tiket ?? 0 }}
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-wider">Token Verifikasi</p>
                                        <p class="mt-1 text-xs font-mono text-gray-600 truncate max-w-full">
                                            {{ $detail->tiket_verifikasi_token ?? '-' }}
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-start lg:justify-end">
                                        @if($detail->tiket_verifikasi_token)
                                            <a href="{{ route('pengelola.scan-tiket', ['token' => $detail->tiket_verifikasi_token]) }}"
                                               class="inline-flex items-center rounded-lg border border-blue-600 px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50 transition">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Lihat Detail
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-400">Token tidak tersedia</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Footer Card - Info Scan --}}
                                @if($isUsed)
                                    <div class="px-4 sm:px-5 py-2 bg-amber-50 border-t border-amber-100 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-xs text-amber-700">
                                            Discan pada: {{ \Carbon\Carbon::parse($detail->tiket_terpakai_at)->locale('id')->translatedFormat('d F Y H:i') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($riwayat->hasPages())
                        <div class="mt-6">
                            {{ $riwayat->links() }}
                        </div>
                    @endif
                @else
                    {{-- Empty State --}}
                    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-6 py-16 text-center">
                        <svg class="mx-auto mb-4 h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">Tidak ada riwayat tiket</h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Belum ada data tiket untuk filter yang dipilih.
                        </p>
                        @if(request('search') || request('status') !== 'all')
                            <a href="{{ route('pengelola.riwayat-tiket.index') }}" 
                               class="mt-4 inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset Filter
                            </a>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>