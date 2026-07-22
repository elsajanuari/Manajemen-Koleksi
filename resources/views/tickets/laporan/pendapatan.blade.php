<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <span class="text-gray-700 font-medium">Laporan Pendapatan</span>
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
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Laporan Pendapatan</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Laporan pendapatan museum berdasarkan periode</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-indigo-100 text-indigo-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>
                            {{ $group_by == 'daily' ? 'Harian' : ($group_by == 'monthly' ? 'Bulanan' : ($group_by == 'quarterly' ? 'Kuartalan' : 'Tahunan')) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6">

                {{-- Filter --}}
                <div class="bg-gray-50/50 rounded-xl p-4 sm:p-5 border border-gray-200 mb-6">
                    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
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
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Group By</label>
                            <select name="group_by" 
                                    class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                                <option value="daily" {{ $group_by == 'daily' ? 'selected' : '' }}>Harian</option>
                                <option value="monthly" {{ $group_by == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                <option value="quarterly" {{ $group_by == 'quarterly' ? 'selected' : '' }}>Kuartalan</option>
                                <option value="yearly" {{ $group_by == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" 
                                    class="flex-1 inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Tampilkan
                            </button>
                            <a href="{{ route('tickets.laporan.pendapatan') }}" 
                               class="flex-1 inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset
                            </a>
                        </div>
                        <div class="flex items-end gap-2">
                            <a href="{{ route('tickets.laporan.pendapatan.export', ['start_date' => $start_date, 'end_date' => $end_date, 'group_by' => $group_by, 'format' => 'excel']) }}" 
                               class="flex-1 inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                                </svg>
                                Excel
                            </a>
                            <a href="{{ route('tickets.laporan.pendapatan.export', ['start_date' => $start_date, 'end_date' => $end_date, 'group_by' => $group_by, 'format' => 'pdf']) }}" 
                               class="flex-1 inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                PDF
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Ringkasan --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
                        <p class="text-xs opacity-90">Total Pendapatan</p>
                        <p class="text-xl font-bold">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
                        <p class="text-xs opacity-90">Jumlah Transaksi</p>
                        <p class="text-xl font-bold">{{ number_format($jumlah_transaksi) }}</p>
                    </div>
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white">
                        <p class="text-xs opacity-90">Rata-rata Transaksi</p>
                        <p class="text-xl font-bold">Rp {{ number_format($rata_transaksi, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Grafik --}}
                <div class="bg-gray-50/50 rounded-xl p-4 sm:p-5 border border-gray-200 mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Grafik Pendapatan
                    </h3>
                    <div style="height: 220px;">
                        <canvas id="pendapatanChart"></canvas>
                    </div>
                </div>

                {{-- Tabel --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($chart_labels as $index => $label)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 text-sm text-gray-700">{{ $label }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-gray-800">Rp {{ number_format($chart_data[$index], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 font-bold border-t border-gray-200">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-800">Total</td>
                                    <td class="px-4 py-3 text-right text-gray-800">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('pendapatanChart'), {
            type: 'line',
            data: {
                labels: @json($chart_labels),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json($chart_data),
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: '#22c55e'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        ticks: { 
                            font: { size: 10 },
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: { ticks: { font: { size: 10 } } }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>