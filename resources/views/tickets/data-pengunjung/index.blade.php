<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <span class="text-gray-700 font-medium">Data Pengunjung</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Data Pengunjung</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Kelola dan analisis data pengunjung museum</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-indigo-100 text-indigo-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>
                            {{ $pengunjung->total() }} Total Pengunjung
                        </span>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6">

                {{-- Statistik Ringkas --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Total Pengunjung</p>
                            <p class="text-xl font-bold text-gray-900">{{ number_format($pengunjung->total()) }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Pengunjung Baru</p>
                            <p class="text-xl font-bold text-emerald-600">{{ number_format($statistikBaruVsLama['baru']) }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Pengunjung Lama</p>
                            <p class="text-xl font-bold text-orange-600">{{ number_format($statistikBaruVsLama['lama']) }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Tiket Terjual</p>
                            <p class="text-xl font-bold text-purple-600">{{ number_format($pengunjung->sum('pemesananTiket.jumlah_tiket') ?? 0) }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Filter --}}
                <div class="bg-gray-50/50 rounded-xl p-4 sm:p-5 border border-gray-200 mb-6">
                    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Nama</label>
                            <input type="text" name="nama" value="{{ request('nama') }}" 
                                   class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" 
                                   placeholder="Cari nama...">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Jenis Tiket</label>
                            <select name="jenis_tiket" 
                                    class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                                <option value="">Semua</option>
                                <option value="reguler" {{ request('jenis_tiket') == 'reguler' ? 'selected' : '' }}>Reguler</option>
                                <option value="event" {{ request('jenis_tiket') == 'event' ? 'selected' : '' }}>Event</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Status</label>
                            <select name="status" 
                                    class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                                <option value="">Semua</option>
                                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                <option value="proses_pembatalan" {{ request('status') == 'proses_pembatalan' ? 'selected' : '' }}>Proses Refund</option>
                                <option value="pengembalian_berhasil" {{ request('status') == 'pengembalian_berhasil' ? 'selected' : '' }}>Refund Berhasil</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" 
                                   class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" 
                                   class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" 
                                    class="flex-1 inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Filter
                            </button>
                            <a href="{{ route('tickets.data-pengunjung.index') }}" 
                               class="flex-1 inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Tabel --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Nama</th>
                                    <th class="px-4 py-3 hidden lg:table-cell">Email</th>
                                    <th class="px-4 py-3 hidden lg:table-cell">No. Telepon</th>
                                    <th class="px-4 py-3">Kunjungan</th>
                                    <th class="px-4 py-3">Tiket</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($pengunjung as $item)
                                    @php $pemesanan = $item->pemesananTiket; @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-gray-800">{{ $item->getDisplayName() }}</span>
                                                @if($item->tipe_pengunjung === 'kelompok')
                                                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-medium text-indigo-700">
                                                        Kelompok
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 hidden lg:table-cell">{{ $item->email ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-600 hidden lg:table-cell">{{ $item->nomor_ponsel ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-600">
                                            {{ $pemesanan->tanggal_pemesanan->locale('id')->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($pemesanan->ticket)
                                                <span class="text-gray-800">{{ $pemesanan->ticket->nama_tiket }}</span>
                                                <span class="text-xs text-gray-400 ml-1">({{ $pemesanan->jumlah_tiket }} tiket)</span>
                                            @else
                                                <span class="text-red-500">Tiket dihapus</span>
                                                <span class="text-xs text-gray-400 ml-1">({{ $pemesanan->jumlah_tiket }} tiket)</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @php
                                                $statusColors = [
                                                    'lunas' => 'bg-green-100 text-green-700',
                                                    'dibatalkan' => 'bg-red-100 text-red-700',
                                                    'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-700',
                                                    'proses_pembatalan' => 'bg-blue-100 text-blue-700',
                                                    'pengembalian_berhasil' => 'bg-emerald-100 text-emerald-700',
                                                ];
                                                $statusLabels = [
                                                    'lunas' => 'Lunas',
                                                    'dibatalkan' => 'Dibatalkan',
                                                    'menunggu_pembayaran' => 'Menunggu Bayar',
                                                    'proses_pembatalan' => 'Proses Refund',
                                                    'pengembalian_berhasil' => 'Refund Berhasil',
                                                ];
                                            @endphp
                                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$pemesanan->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                {{ $statusLabels[$pemesanan->status] ?? ucfirst(str_replace('_', ' ', $pemesanan->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('tickets.data-pengunjung.show', $item->id) }}" 
                                               class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-100 transition">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-12 text-center">
                                            <svg class="mx-auto mb-3 h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <p class="font-medium text-gray-600">Tidak ada data pengunjung</p>
                                            <p class="text-sm text-gray-400 mt-1">Belum ada pengunjung yang terdaftar</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden divide-y divide-gray-100">
                        @forelse($pengunjung as $item)
                            @php $pemesanan = $item->pemesananTiket; @endphp
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-800 text-sm">{{ $item->getDisplayName() }}</span>
                                            @if($item->tipe_pengunjung === 'kelompok')
                                                <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-medium text-indigo-700 flex-shrink-0">
                                                    Kelompok
                                                </span>
                                            @endif
                                        </div>
                                        @if($item->email)
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $item->email }}</p>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0 ml-2">
                                        @php
                                            $statusColors = [
                                                'lunas' => 'bg-green-100 text-green-700',
                                                'dibatalkan' => 'bg-red-100 text-red-700',
                                                'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-700',
                                                'proses_pembatalan' => 'bg-blue-100 text-blue-700',
                                                'pengembalian_berhasil' => 'bg-emerald-100 text-emerald-700',
                                            ];
                                            $statusLabels = [
                                                'lunas' => 'Lunas',
                                                'dibatalkan' => 'Dibatalkan',
                                                'menunggu_pembayaran' => 'Menunggu Bayar',
                                                'proses_pembatalan' => 'Proses Refund',
                                                'pengembalian_berhasil' => 'Refund Berhasil',
                                            ];
                                        @endphp
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusColors[$pemesanan->status] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $statusLabels[$pemesanan->status] ?? ucfirst(str_replace('_', ' ', $pemesanan->status)) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-1 text-xs">
                                    <div>
                                        <span class="text-gray-500">Telepon:</span>
                                        <span class="text-gray-700">{{ $item->nomor_ponsel ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Kunjungan:</span>
                                        <span class="text-gray-700">{{ $pemesanan->tanggal_pemesanan->locale('id')->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="text-gray-500">Tiket:</span>
                                        <span class="text-gray-700">{{ $pemesanan->ticket->nama_tiket }}</span>
                                        <span class="text-gray-400">({{ $pemesanan->jumlah_tiket }} tiket)</span>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <a href="{{ route('tickets.data-pengunjung.show', $item->id) }}" 
                                       class="inline-flex items-center justify-center w-full rounded-lg bg-blue-50 px-3 py-2 text-xs font-medium text-blue-600 hover:bg-blue-100 transition">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="px-4 py-12 text-center">
                                <svg class="mx-auto mb-3 h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="font-medium text-gray-600">Tidak ada data pengunjung</p>
                                <p class="text-sm text-gray-400 mt-1">Belum ada pengunjung yang terdaftar</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="px-4 py-3 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-2">
                        <span class="text-xs text-gray-500">
                            Menampilkan {{ $pengunjung->firstItem() ?? 0 }} - {{ $pengunjung->lastItem() ?? 0 }} 
                            dari {{ $pengunjung->total() }} data
                        </span>
                        {{ $pengunjung->withQueryString()->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>