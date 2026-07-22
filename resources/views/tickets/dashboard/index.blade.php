<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">Dashboard Tiket</h1>
                        <p class="text-slate-600 mt-2">Ringkasan penjualan tiket, pengunjung, dan analitik pemesanan.</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 mt-4 text-xs text-slate-500">
                    <span class="font-medium text-slate-400">Keterangan periode:</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span> Sepanjang waktu</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-indigo-300"></span> Bulan ini</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-blue-300"></span> Hari ini / real-time</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-teal-300"></span> 7 hari terakhir</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                {{-- Total Pendapatan --}}
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border-l-4 border-emerald-500 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm text-slate-600 font-medium">Pendapatan Bulan Ini</p>
                                <span class="text-[10px] font-semibold uppercase tracking-wide bg-indigo-100 text-indigo-500 px-2 py-0.5 rounded-full">Bulan ini</span>
                            </div>
                            <p class="text-2xl font-bold text-slate-900 mt-2">Rp {{ number_format($kpi['pendapatan_bulan_ini'], 0, ',', '.') }}</p>
                            @php $pd = $kpi['pendapatan_direction'] ?? 'up'; @endphp
                            <p class="text-xs font-semibold mt-2 {{ $pd === 'up' ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $pd === 'up' ? '↑' : '↓' }} {{ abs($kpi['pertumbuhan_pendapatan']) }}% dibanding bulan lalu
                            </p>
                            <p class="text-[11px] text-slate-400 mt-1">Total sepanjang waktu: Rp {{ number_format($kpi['total_pendapatan'], 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-emerald-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Tiket Terjual --}}
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border-l-4 border-purple-500 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm text-slate-600 font-medium">Tiket Terjual</p>
                                <span class="text-[10px] font-semibold uppercase tracking-wide bg-indigo-100 text-indigo-500 px-2 py-0.5 rounded-full">Bulan ini</span>
                            </div>
                            <p class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($kpi['tiket_terjual_bulan_ini']) }}</p>
                            @php $td = $kpi['tiket_direction'] ?? 'up'; @endphp
                            <p class="text-xs font-semibold mt-2 {{ $td === 'up' ? 'text-purple-600' : 'text-red-600' }}">
                                {{ $td === 'up' ? '↑' : '↓' }} {{ abs($kpi['pertumbuhan_pengunjung']) }}% dibanding bulan lalu
                            </p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V5z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Total Pengunjung --}}
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border-l-4 border-orange-500 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm text-slate-600 font-medium">Total Pengunjung</p>
                                <span class="text-[10px] font-semibold uppercase tracking-wide bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">Sepanjang waktu</span>
                            </div>
                            <p class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($kpi['total_pengunjung']) }}</p>
                            <p class="text-xs text-orange-600 font-semibold mt-2">Hari ini: {{ number_format($kpi['pengunjung_hari_ini']) }}</p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

           <div class="mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Perlu Perhatian</h2>
                    <span class="text-[10px] font-semibold uppercase tracking-wide bg-blue-100 text-blue-500 px-2 py-0.5 rounded-full">Kondisi saat ini, bukan periode tertentu</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Detail Pending Refund --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                        <p class="text-sm text-amber-800 font-medium">Refund Diproses</p>
                        <p class="text-2xl font-bold text-amber-900 mt-1">{{ $refundStatistics['jumlah_refund_proses'] }} transaksi</p>
                        <p class="text-xs text-amber-700 mt-1">Rp {{ number_format($refundStatistics['total_refund_proses'], 0, ',', '.') }}</p>
                    </div>

                    {{-- Menunggu Pembayaran & berisiko dibatalkan otomatis --}}
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-5">
                        <p class="text-sm text-orange-800 font-medium">Belum Bayar, Berisiko Batal</p>
                        <p class="text-2xl font-bold text-orange-900 mt-1">{{ $pemesananBerisiko['total_berisiko'] }} pemesanan</p>
                        <p class="text-xs text-orange-700 mt-1">
                            {{ $pemesananBerisiko['kunjungan_besok'] }} kunjungan besok &middot; {{ $pemesananBerisiko['sudah_kedaluwarsa'] + $pemesananBerisiko['kunjungan_hari_ini'] }} sudah lewat/hari ini
                        </p>
                    </div>

                    {{-- Belum Scan --}}
                    <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                        <p class="text-sm text-red-800 font-medium">Belum Check-in Hari Ini</p>
                        @if($scanStatusHariIni['total'] > 0)
                            <p class="text-2xl font-bold text-red-900 mt-1">{{ number_format($scanStatusHariIni['belum_scan']) }}</p>
                            <p class="text-xs text-red-700 mt-1">dari {{ number_format($scanStatusHariIni['total']) }} yang jadwal kunjungannya hari ini</p>
                        @else
                            <p class="text-2xl font-bold text-red-900 mt-1">-</p>
                            <p class="text-xs text-red-700 mt-1">Tidak ada jadwal kunjungan hari ini</p>
                        @endif
                    </div>

                    {{-- Kapasitas Hari Ini --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                        <p class="text-sm text-blue-800 font-medium">Kapasitas Terisi Hari Ini</p>
                        @if($kapasitasHariIni['ada_jadwal'])
                            <p class="text-2xl font-bold text-blue-900 mt-1">{{ $kapasitasHariIni['persentase_terisi'] }}%</p>
                            <p class="text-xs text-blue-700 mt-1">{{ number_format($kapasitasHariIni['terjual']) }} terjual &middot; {{ number_format($kapasitasHariIni['sisa']) }} slot tersisa</p>
                        @else
                            <p class="text-2xl font-bold text-blue-900 mt-1">-</p>
                            <p class="text-xs text-blue-700 mt-1">Tidak ada jadwal kunjungan hari ini</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Analitik</h2>
                    <span class="text-xs text-slate-400 font-normal normal-case">— setiap grafik punya skop periode masing-masing, lihat badge di tiap kartu</span>
                </div>
            </div>

            {{-- Scan Status & Category Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="mb-6">
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-bold text-slate-900">Status Scan Tiket</h3>
                            <span class="text-[10px] font-semibold uppercase tracking-wide bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">Sepanjang waktu</span>
                        </div>
                        <p class="text-sm text-slate-600 mt-1">Perbandingan pengunjung yang sudah dan belum scan</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <div class="flex items-end justify-between mb-2">
                                <div>
                                    <p class="text-sm font-medium text-slate-900">Total Tiket</p>
                                    <p class="text-2xl font-bold text-slate-900">{{ number_format($scanStatus['total_tiket']) }}</p>
                                </div>
                                <p class="text-2xl font-bold text-emerald-600">{{ $scanStatus['persentase_scan'] }}%</p>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-full rounded-full transition-all duration-500" style="width: {{ $scanStatus['persentase_scan'] }}%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-emerald-50 rounded-lg p-4">
                                <p class="text-sm text-slate-600 font-medium">Sudah Scan</p>
                                <p class="text-3xl font-bold text-emerald-600 mt-2">{{ number_format($scanStatus['sudah_di_scan']) }}</p>
                                <p class="text-xs text-emerald-600 mt-1">Tiket terpakai</p>
                            </div>
                            <div class="bg-red-50 rounded-lg p-4">
                                <p class="text-sm text-slate-600 font-medium">Belum Scan</p>
                                <p class="text-3xl font-bold text-red-600 mt-2">{{ number_format($scanStatus['belum_di_scan']) }}</p>
                                <p class="text-xs text-red-600 mt-1">Belum terpakai</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="mb-6">
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-bold text-slate-900">Pengunjung per Kategori Tiket</h3>
                            <span class="text-[10px] font-semibold uppercase tracking-wide bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">Sepanjang waktu</span>
                        </div>
                        <p class="text-sm text-slate-600 mt-1">Distribusi pengunjung berdasarkan jenis tiket</p>
                    </div>
                    <div style="height: 250px;">
                        <canvas id="visitorCategoryChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-semibold text-gray-800">Grafik Pendapatan & Refund</h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <select id="pendapatan-periode" class="rounded-lg border-gray-300 text-xs py-1 px-4">
                                <option value="mingguan" {{ $pendapatanPeriode == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                                <option value="bulanan" {{ $pendapatanPeriode == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                <option value="tahunan" {{ $pendapatanPeriode == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                            <button id="pendapatan-prev" class="bg-gray-100 hover:bg-gray-200 rounded-lg px-2 py-1 text-sm">◀</button>
                            <span id="pendapatan-title" class="text-xs font-medium text-gray-600 min-w-[80px] text-center">{{ $combinedChart['title'] ?? '' }}</span>
                            <button id="pendapatan-next" class="bg-gray-100 hover:bg-gray-200 rounded-lg px-2 py-1 text-sm">▶</button>
                            <button id="pendapatan-reset" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-2 py-1 text-xs">Reset</button>
                        </div>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="combinedChart"></canvas>
                    </div>
                    <div class="mt-2 flex justify-center gap-4 text-xs">
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>
                            Pendapatan: <span class="font-bold text-green-600" id="combined-total-pendapatan">Rp {{ number_format($combinedChart['total_pendapatan'] ?? 0, 0, ',', '.') }}</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
                            Refund: <span class="font-bold text-red-600" id="combined-total-refund">Rp {{ number_format($combinedChart['total_refund'] ?? 0, 0, ',', '.') }}</span>
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <h3 class="text-sm font-semibold text-gray-800">Penjualan Tiket</h3>
                        <span class="text-[10px] font-semibold uppercase tracking-wide bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">Sepanjang waktu</span>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="penjualanChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-semibold text-gray-800">Tren Pengunjung (7 Hari)</h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="pengunjung-prev" class="bg-gray-100 hover:bg-gray-200 rounded-lg px-2 py-1 text-sm">◀</button>
                            <span id="pengunjung-title" class="text-xs font-medium text-gray-600 min-w-[120px] text-center">{{ $pengunjungChart['title'] ?? '' }}</span>
                            <button id="pengunjung-next" class="bg-gray-100 hover:bg-gray-200 rounded-lg px-2 py-1 text-sm">▶</button>
                            <button id="pengunjung-reset" class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-2 py-1 text-xs">Reset</button>
                        </div>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="pengunjungChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <h3 class="text-sm font-semibold text-gray-800">Statistik Cepat</h3>
                        <span class="text-[10px] font-semibold uppercase tracking-wide bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">Sepanjang waktu</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="text-xs text-gray-500">Hari Terbanyak</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $statistikCepat['hari_terbanyak'] }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="text-xs text-gray-500">Bulan Tertinggi</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $statistikCepat['bulan_tertinggi'] }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="text-xs text-gray-500">Tiket Terlaris</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $kpi['tiket_terlaris'] }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="text-xs text-gray-500">Transaksi Sukses</p>
                            <p class="text-sm font-semibold text-green-600">{{ number_format($statistikCepat['transaksi_sukses']) }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-2">
                            <p class="text-xs text-gray-500">Refund Selesai</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $refundStatistics['jumlah_refund_selesai'] }} (Rp {{ number_format($refundStatistics['total_refund_selesai'], 0, ',', '.') }})</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let pendapatanOffset = {{ $pendapatanOffset ?? 0 }};
            let pendapatanPeriode = '{{ $pendapatanPeriode ?? "bulanan" }}';
            let pengunjungOffset = {{ $pengunjungOffset ?? 0 }};

            let combinedChartInstance = null;
            let penjualanChartInstance = null;
            let pengunjungChartInstance = null;
            let visitorCategoryChartInstance = null;

            function loadCombined() {
                fetch('{{ route("tickets.dashboard.chart-data") }}?pendapatan_periode=' + pendapatanPeriode + '&pendapatan_offset=' + pendapatanOffset)
                    .then(response => response.json())
                    .then(data => {
                        const chartData = data.combined;
                        document.getElementById('pendapatan-title').textContent = chartData.title || '';

                        document.getElementById('combined-total-pendapatan').textContent =
                            'Rp ' + (chartData.total_pendapatan || 0).toLocaleString('id-ID');
                        document.getElementById('combined-total-refund').textContent =
                            'Rp ' + (chartData.total_refund || 0).toLocaleString('id-ID');

                        if (combinedChartInstance) combinedChartInstance.destroy();

                        const ctx = document.getElementById('combinedChart').getContext('2d');

                        combinedChartInstance = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: chartData.labels || [],
                                datasets: [
                                    {
                                        label: 'Pendapatan',
                                        data: chartData.pendapatan || [],
                                        borderColor: '#22c55e',
                                        backgroundColor: 'rgba(34, 197, 94, 0.12)',
                                        borderWidth: 1,
                                        tension: 0.25,
                                        fill: false,
                                        pointRadius: 2,
                                        pointBackgroundColor: '#22c55e',
                                        pointBorderColor: '#22c55e',
                                        order: 1
                                    },
                                    {
                                        label: 'Refund',
                                        data: chartData.refund || [],
                                        borderColor: '#ef4444',
                                        backgroundColor: 'rgba(239, 68, 68, 0.12)',
                                        borderWidth: 1,
                                        tension: 0.25,
                                        fill: false,
                                        pointRadius: 2,
                                        pointBackgroundColor: '#ef4444',
                                        pointBorderColor: '#ef4444',
                                        order: 2
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: { mode: 'index', intersect: false },
                                elements: { line: { tension: 0.25, borderWidth: 1 }, point: { radius: 2 } },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'top',
                                        labels: { boxWidth: 12, padding: 10, font: { size: 10 }, usePointStyle: true, pointStyle: 'circle' }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                const value = context.raw;
                                                const label = context.dataset.label;
                                                if (value < 0) return label + ': -Rp ' + Math.abs(value).toLocaleString('id-ID');
                                                return label + ': Rp ' + value.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        ticks: {
                                            font: { size: 10 },
                                            callback: function(value) {
                                                if (value < 0) return '-Rp ' + Math.abs(value).toLocaleString('id-ID');
                                                return 'Rp ' + value.toLocaleString('id-ID');
                                            }
                                        },
                                        grid: {
                                            color: function(context) {
                                                return context.tick.value === 0 ? 'rgba(0,0,0,0.3)' : 'rgba(0,0,0,0.05)';
                                            },
                                            lineWidth: function(context) {
                                                return context.tick.value === 0 ? 2 : 1;
                                            }
                                        },
                                        beginAtZero: true
                                    },
                                    x: { ticks: { font: { size: 10 }, maxRotation: 0, maxTicksLimit: 12 } }
                                }
                            }
                        });
                    });
            }

            function loadPenjualan() {
                fetch('{{ route("tickets.dashboard.chart-data") }}')
                    .then(response => response.json())
                    .then(data => {
                        const chartData = data.penjualan;
                        if (penjualanChartInstance) penjualanChartInstance.destroy();

                        const ctx = document.getElementById('penjualanChart').getContext('2d');
                        penjualanChartInstance = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: chartData.jenis_tiket?.labels || [],
                                datasets: [{
                                    label: 'Jumlah Terjual',
                                    data: chartData.jenis_tiket?.data || [],
                                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                                    borderRadius: 4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: { callbacks: { label: function(context) { return context.raw + ' tiket'; } } }
                                },
                                scales: {
                                    y: { ticks: { font: { size: 10 } }, beginAtZero: true },
                                    x: { ticks: { font: { size: 10 } } }
                                }
                            }
                        });
                    });
            }

            function loadVisitorCategory() {
                const visitorCategoryData = {
                    labels: {!! json_encode($visitorByCategory['labels'] ?? []) !!},
                    data: {!! json_encode($visitorByCategory['data'] ?? []) !!}
                };

                if (visitorCategoryChartInstance) visitorCategoryChartInstance.destroy();

                const ctx = document.getElementById('visitorCategoryChart').getContext('2d');
                visitorCategoryChartInstance = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: visitorCategoryData.labels,
                        datasets: [{
                            data: visitorCategoryData.data,
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(139, 92, 246, 0.8)',
                                'rgba(236, 72, 153, 0.8)',
                                'rgba(249, 115, 22, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(6, 182, 212, 0.8)',
                            ],
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { padding: 15, font: { size: 11, weight: 'bold' }, usePointStyle: true, pointStyle: 'circle' }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                padding: 12,
                                font: { size: 12 },
                                callbacks: { label: function(context) { return context.label + ': ' + context.raw + ' pengunjung'; } }
                            }
                        }
                    }
                });
            }

            function loadPengunjung() {
                fetch('{{ route("tickets.dashboard.chart-data") }}?pengunjung_offset=' + pengunjungOffset)
                    .then(response => response.json())
                    .then(data => {
                        const chartData = data.pengunjung;
                        document.getElementById('pengunjung-title').textContent = chartData.title || '';

                        if (pengunjungChartInstance) pengunjungChartInstance.destroy();

                        const ctx = document.getElementById('pengunjungChart').getContext('2d');
                        pengunjungChartInstance = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: chartData.labels || [],
                                datasets: [{
                                    label: 'Pengunjung',
                                    data: chartData.data || [],
                                    borderColor: '#f97316',
                                    backgroundColor: 'rgba(249, 115, 22, 0.12)',
                                    tension: 0.25,
                                    fill: false,
                                    pointRadius: 2,
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: { mode: 'index', intersect: false },
                                elements: { line: { tension: 0.25, borderWidth: 1 }, point: { radius: 2 } },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: { callbacks: { label: function(context) { return context.raw + ' pengunjung'; } } }
                                },
                                scales: {
                                    y: { ticks: { font: { size: 10 } }, beginAtZero: true },
                                    x: { ticks: { font: { size: 10 }, maxRotation: 0, maxTicksLimit: 7 } }
                                }
                            }
                        });
                    });
            }

            document.getElementById('pendapatan-periode').addEventListener('change', function() {
                pendapatanPeriode = this.value;
                pendapatanOffset = 0;
                loadCombined();
            });

            document.getElementById('pendapatan-prev').addEventListener('click', function() {
                pendapatanOffset++;
                loadCombined();
            });

            document.getElementById('pendapatan-next').addEventListener('click', function() {
                if (pendapatanOffset > 0) {
                    pendapatanOffset--;
                    loadCombined();
                }
            });

            document.getElementById('pendapatan-reset').addEventListener('click', function() {
                pendapatanOffset = 0;
                loadCombined();
            });

            document.getElementById('pengunjung-prev').addEventListener('click', function() {
                pengunjungOffset++;
                loadPengunjung();
            });

            document.getElementById('pengunjung-next').addEventListener('click', function() {
                if (pengunjungOffset > 0) {
                    pengunjungOffset--;
                    loadPengunjung();
                }
            });

            document.getElementById('pengunjung-reset').addEventListener('click', function() {
                pengunjungOffset = 0;
                loadPengunjung();
            });

            loadCombined();
            loadPenjualan();
            loadPengunjung();
            loadVisitorCategory();
        });
    </script>
    @endpush
</x-app-layout>