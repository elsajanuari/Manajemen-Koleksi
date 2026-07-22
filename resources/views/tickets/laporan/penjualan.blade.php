<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <span class="text-gray-700 font-medium">Laporan Penjualan Tiket</span>
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
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Laporan Penjualan Tiket</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Laporan penjualan tiket berdasarkan jenis tiket</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-indigo-100 text-indigo-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>
                            {{ count($data) }} Jenis Tiket
                        </span>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6">

                {{-- Filter --}}
                <div class="bg-gray-50/50 rounded-xl p-4 sm:p-5 border border-gray-200 mb-6">
                    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $start_date }}" 
                                   class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal Selesai</label>
                            <input type="date" name="end_date" value="{{ $end_date }}" 
                                   class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" 
                                    class="flex-1 inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Tampilkan
                            </button>
                            <a href="{{ route('tickets.laporan.penjualan') }}" 
                               class="flex-1 inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset
                            </a>
                        </div>
                        <div class="flex items-end gap-2">
                            <a href="{{ route('tickets.laporan.penjualan.export', ['start_date' => $start_date, 'end_date' => $end_date]) }}" 
                               class="flex-1 inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                </svg>
                                Excel
                            </a>
                            <a href="{{ route('tickets.laporan.penjualan.export-pdf', ['start_date' => $start_date, 'end_date' => $end_date]) }}" 
                               class="flex-1 inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                PDF
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Tabel --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Tiket</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Terjual</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($data as $index => $item)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ $item['jenis_tiket'] }}</td>
                                        <td class="px-4 py-3 text-right text-gray-700">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right text-gray-700">{{ number_format($item['jumlah_terjual']) }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-gray-800">Rp {{ number_format($item['total_pendapatan'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-12 text-center">
                                            <svg class="mx-auto mb-3 h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="font-medium text-gray-600">Tidak ada data penjualan</p>
                                            <p class="text-sm text-gray-400 mt-1">Belum ada transaksi penjualan pada periode ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if(!empty($data))
                            <tfoot class="bg-gray-50 font-bold border-t border-gray-200">
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-right text-sm text-gray-800">Total Keseluruhan</td>
                                    <td class="px-4 py-3 text-right text-gray-800">Rp {{ number_format(array_sum(array_column($data, 'total_pendapatan')), 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>