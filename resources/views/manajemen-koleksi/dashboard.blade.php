<x-app-layout>
    <style>
        .dash-page-title { font-size: 1.5rem; line-height: 2rem; font-weight: 600; color: #1f2937; }
        .dash-page-subtitle { font-size: 1rem; line-height: 1.5rem; color: #6b7280; }
        .dash-title { font-size: 1rem; line-height: 1.5rem; font-weight: 600; color: #111827; }
        .dash-subtitle { margin-top: 0.25rem; font-size: 0.875rem; line-height: 1.25rem; color: #6b7280; }
        .dash-info { margin-top: 0.25rem; font-size: 0.875rem; line-height: 1.375rem; color: #6b7280; }
        .dash-stat { margin-top: 0.25rem; font-size: 1.5rem; line-height: 2rem; font-weight: 700; font-variant-numeric: tabular-nums; }
        @media (min-width: 640px) { .dash-stat { font-size: 1.875rem; line-height: 2.25rem; } }
        .dash-link { font-size: 0.875rem; line-height: 1.25rem; font-weight: 500; color: #4f46e5; }
        .dash-link:hover { color: #3730a3; }
        .dash-body { font-size: 0.875rem; line-height: 1.25rem; color: #374151; }
        .dash-body-strong { font-size: 0.875rem; line-height: 1.25rem; font-weight: 500; color: #111827; }
        .dash-meta { font-size: 0.875rem; line-height: 1.25rem; color: #6b7280; }
        .dash-badge { font-size: 0.75rem; line-height: 1rem; font-weight: 500; }
        .dash-table-head { font-size: 0.875rem; line-height: 1.25rem; font-weight: 600; text-transform: uppercase; color: #6b7280; }
        .dash-empty { font-size: 0.875rem; line-height: 1.25rem; color: #6b7280; }
    </style>

    <div class="py-12">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Header --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div class="space-y-2">
                        <h2 class="dash-page-title">{{ __('Dashboard Manajemen Koleksi') }}</h2>
                        <p class="dash-page-subtitle">
                            Ringkasan koleksi, kondisi koleksi, jadwal, dan konservasi koleksi museum.
                        </p>
                    </div>
                    <a href="{{ route('koleksi.index') }}" class="rounded-xl border border-indigo-100 bg-indigo-50/50 px-5 py-3 text-center transition hover:border-indigo-200 hover:bg-indigo-50">
                        <p class="dash-stat text-indigo-600" style="margin-top:0">{{ $totalKoleksi }}</p>
                        <p class="dash-meta">Koleksi terdaftar</p>
                    </a>
                </div>
            </div>

            {{-- Perhatian Segera --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-4">
                    <h3 class="dash-title">Perhatian Segera</h3>
                    <p class="dash-subtitle">Indikator utama yang perlu ditindaklanjuti pengelola museum</p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <a href="{{ route('koleksi.index') }}" class="group rounded-xl border border-gray-100 bg-gray-50/60 px-4 py-3.5 transition hover:border-indigo-200 hover:bg-indigo-50/40">
                        <p class="dash-title text-gray-600">Belum Diperiksa</p>
                        <p class="dash-stat text-indigo-600">{{ $koleksiBelumDiperiksa }}</p>
                        <p class="dash-info">Koleksi yang belum memiliki catatan pemeriksaan kondisi</p>
                    </a>

                    <a href="{{ route('kondisi.index', ['kondisi' => 'rusak_berat']) }}" class="group rounded-xl border border-red-100 bg-red-50/40 px-4 py-3.5 transition hover:border-red-200 hover:bg-red-50/70">
                        <p class="dash-title text-gray-600">Kondisi Kritis</p>
                        <p class="dash-stat text-red-600">{{ $kondisiRusakBerat }}</p>
                        <p class="dash-info">
                            Koleksi dengan pemeriksaan terakhir berstatus <span class="font-medium text-red-700">rusak berat</span>
                        </p>
                    </a>

                    <a href="{{ route('jadwal-konservasi.index', ['status' => 'terjadwal']) }}" class="group rounded-xl border border-amber-100 bg-amber-50/40 px-4 py-3.5 transition hover:border-amber-200 hover:bg-amber-50/70">
                        <p class="dash-title text-gray-600">Jadwal Terlambat</p>
                        <p class="dash-stat text-amber-600">{{ $perawatanTerlambat }}</p>
                        <p class="dash-info">Jadwal konservasi aktif yang tanggalnya sudah lewat hari ini</p>
                    </a>

                    <a href="{{ route('kondisi.index') }}" class="group rounded-xl border border-blue-100 bg-blue-50/40 px-4 py-3.5 transition hover:border-blue-200 hover:bg-blue-50/70">
                        <p class="dash-title text-gray-600">Belum Dijadwalkan</p>
                        <p class="dash-stat text-blue-600">{{ $menungguJadwal }}</p>
                        <p class="dash-info">Sudah direkomendasikan tindak lanjut, tetapi belum dibuat jadwal konservasi</p>
                    </a>
                </div>
            </div>

            {{-- Beban Kerja Jadwal --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h3 class="dash-title">Beban Kerja Jadwal Konservasi</h3>
                        <p class="dash-subtitle">
                            Jumlah jadwal yang masih berstatus <span class="font-medium text-gray-700">terjadwal</span> (belum selesai atau dibatalkan)
                        </p>
                    </div>
                    <a href="{{ route('jadwal-konservasi.index') }}" class="dash-link shrink-0">Kelola jadwal &rarr;</a>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-indigo-100 bg-indigo-50/30 px-4 py-4">
                        <p class="dash-title text-indigo-700">Jatuh Tempo Hari Ini</p>
                        <p class="dash-stat text-indigo-700">{{ $perawatanHariIni }}</p>
                        <p class="dash-info">Jadwal yang harus dikerjakan <span class="font-medium">hari ini</span></p>
                    </div>

                    <div class="rounded-xl border border-orange-100 bg-orange-50/30 px-4 py-4">
                        <p class="dash-title text-orange-700">Besok – 7 Hari Ke Depan</p>
                        <p class="dash-stat text-orange-700">{{ $perawatanMingguDepan }}</p>
                        <p class="dash-info">Jadwal mendatang untuk persiapan kerja minggu ini</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-4">
                        <p class="dash-title text-slate-700">Total Jadwal Aktif</p>
                        <p class="dash-stat text-slate-800">{{ $perawatanTerjadwal }}</p>
                        <p class="dash-info">
                            @if ($perawatanTerlambat > 0)
                                {{ $perawatanTerjadwalMendatang }} tanggal belum lewat &middot; {{ $perawatanTerlambat }} sudah terlambat
                            @else
                                Semua jadwal aktif masih pada tanggal hari ini atau mendatang
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Komposisi Inventaris --}}
            <div class="space-y-4">
                <div>
                    <h3 class="dash-title">Komposisi Inventaris</h3>
                    <p class="dash-subtitle">Distribusi koleksi berdasarkan kategori, lokasi fisik, dan ketersediaan sewa/beli</p>
                </div>

                <div class="grid grid-cols-1 items-stretch gap-5 lg:grid-cols-12">
                    <div class="flex flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-5">
                        <div class="mb-3 shrink-0">
                            <h3 class="dash-title">Koleksi per Kategori</h3>
                            <p class="dash-subtitle">Jumlah koleksi di setiap kategori inventaris museum</p>
                        </div>
                        <div class="relative min-h-[220px] flex-1">
                            <canvas id="chartKategori"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:col-span-7 lg:grid-cols-2">
                        <div class="flex flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div class="mb-3 shrink-0">
                                <h3 class="dash-title">Lokasi Fisik</h3>
                                <p class="dash-subtitle">Koleksi yang sedang dipamerkan vs disimpan di gudang</p>
                            </div>
                            <div class="relative min-h-[200px] flex-1">
                                <canvas id="chartLokasi"></canvas>
                            </div>
                        </div>

                        <div class="flex flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div class="mb-3 shrink-0">
                                <h3 class="dash-title">Ketersediaan Sewa/Beli</h3>
                                <p class="dash-subtitle">Status komersial koleksi untuk program sewa atau pembelian</p>
                            </div>
                            <div class="relative min-h-[200px] flex-1">
                                <canvas id="chartStatusSewa"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kondisi & Rekomendasi --}}
            <div class="space-y-4">
                <div>
                    <h3 class="dash-title">Kondisi &amp; Rekomendasi</h3>
                    <p class="dash-subtitle">Hasil pemeriksaan kondisi terakhir setiap koleksi beserta rekomendasi tindak lanjutnya</p>
                </div>

                <div class="grid grid-cols-1 items-stretch gap-5 lg:grid-cols-2">
                    <div class="flex flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="mb-3 shrink-0">
                            <h3 class="dash-title">Kondisi Terkini</h3>
                            <p class="dash-subtitle">Ringkasan hasil pemeriksaan terakhir: baik, rusak ringan, rusak berat, atau belum diperiksa</p>
                        </div>
                        <div class="relative min-h-[240px] flex-1">
                            <canvas id="chartKondisi"></canvas>
                        </div>
                    </div>

                    <div class="flex flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="mb-3 shrink-0">
                            <h3 class="dash-title">Rekomendasi Tindak Lanjut</h3>
                            <p class="dash-subtitle">Tindakan yang disarankan setelah pemeriksaan, misalnya pemeliharaan atau penanganan kerusakan</p>
                        </div>
                        <div class="relative min-h-[240px] flex-1" id="chartRekomendasiWrap">
                            <canvas id="chartRekomendasi"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jadwal & Tindakan Konservasi --}}
            <div class="space-y-4">
                <div>
                    <h3 class="dash-title">Jadwal &amp; Tindakan Konservasi</h3>
                    <p class="dash-subtitle">Statistik penjadwalan dan pelaksanaan konservasi</p>
                </div>

                <div class="grid grid-cols-1 items-stretch gap-5 xl:grid-cols-12">
                    <div class="flex flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-sm xl:col-span-8">
                        <div class="mb-3 flex flex-wrap items-start justify-between gap-3 shrink-0">
                            <div>
                                <h3 class="dash-title">Distribusi Jadwal Konservasi</h3>
                                <p class="dash-subtitle">Menampilkan sebaran jadwal konservasi pada periode tertentu.</p>
                            </div>
                            <div class="inline-flex rounded-lg border border-gray-200 bg-gray-50 p-1 dash-body font-medium" role="group" aria-label="Rentang waktu">
                                <button type="button" data-range="harian" class="timeline-toggle rounded-md px-4 py-2 transition">30 Hari</button>
                                <button type="button" data-range="mingguan" class="timeline-toggle rounded-md px-4 py-2 transition">12 Minggu</button>
                                <button type="button" data-range="bulanan" class="timeline-toggle rounded-md px-4 py-2 transition">12 Bulan</button>
                            </div>
                        </div>
                        <div class="relative min-h-[260px] flex-1">
                            <canvas id="chartTimeline"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:col-span-4 xl:grid-cols-1">
                        <div class="flex flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-sm xl:min-h-[160px]">
                            <div class="mb-2 shrink-0">
                                <h3 class="dash-title">Status Jadwal</h3>
                                <p class="dash-subtitle">Semua catatan jadwal: terjadwal, selesai, atau dibatalkan</p>
                            </div>
                            <div class="relative min-h-[140px] flex-1 xl:max-h-[160px]">
                                <canvas id="chartPerawatan"></canvas>
                            </div>
                        </div>

                        <div class="flex flex-col rounded-2xl border border-gray-200 bg-white p-5 shadow-sm xl:min-h-[160px]">
                            <div class="mb-2 shrink-0">
                                <h3 class="dash-title">Status Tindakan Konservasi</h3>
                                <p class="dash-subtitle">Tahapan pelaksanaan: direncanakan, sedang berjalan, atau selesai</p>
                            </div>
                            <div class="relative min-h-[140px] flex-1 xl:max-h-[160px]">
                                <canvas id="chartKonservasi"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Daftar Perlu Tindakan --}}
            <div class="space-y-4">
                <div>
                    <h3 class="dash-title">Daftar Perlu Tindakan</h3>
                    <p class="dash-subtitle">Jadwal terdekat, koleksi kritis, dan konservasi yang sedang berjalan</p>
                </div>

                <div class="grid grid-cols-1 items-start gap-5 xl:grid-cols-12">
                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm xl:col-span-7">
                        <div class="flex items-center justify-between gap-3 border-b border-gray-200 px-5 py-4">
                            <div class="min-w-0">
                                <h3 class="dash-title">Jadwal 30 Hari Mendatang</h3>
                                <p class="dash-subtitle">Jadwal konservasi terjadwal dari hari ini hingga 30 hari ke depan</p>
                            </div>
                            <a href="{{ route('jadwal-konservasi.index') }}" class="dash-link shrink-0">Lihat semua</a>
                        </div>
                        @if ($perawatanMendatang->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full table-fixed">
                                    <colgroup>
                                        <col class="w-[42%]">
                                        <col class="w-[30%]">
                                        <col class="w-[28%]">
                                    </colgroup>
                                    <thead class="border-b border-gray-100 bg-gray-50">
                                        <tr>
                                            <th class="px-5 py-2.5 text-left dash-table-head">Koleksi</th>
                                            <th class="px-5 py-2.5 text-left dash-table-head">Jenis</th>
                                            <th class="px-5 py-2.5 text-left dash-table-head">Jadwal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($perawatanMendatang as $perawatan)
                                            <tr class="align-middle hover:bg-slate-50/80">
                                                <td class="px-5 py-3">
                                                    <a href="{{ route('jadwal-konservasi.show', $perawatan) }}" class="block truncate dash-body-strong text-indigo-600 hover:underline" title="{{ $perawatan->koleksi->nama }}">
                                                        {{ $perawatan->koleksi->nama }}
                                                    </a>
                                                </td>
                                                <td class="truncate px-5 py-3 dash-body" title="{{ $perawatan->label_jenis }}">{{ $perawatan->label_jenis }}</td>
                                                <td class="px-5 py-3">
                                                    <div class="dash-body">{{ $perawatan->jadwal_tanggal->translatedFormat('d M Y') }}</div>
                                                    <span class="mt-0.5 inline-flex rounded-full px-2 py-0.5 dash-badge ring-1 ring-inset {{ $perawatan->jadwal_indikator_badge_class }}">
                                                        {{ $perawatan->jadwal_indikator_waktu }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="px-5 py-12 text-center dash-empty">
                                Tidak ada jadwal konservasi terjadwal dalam 30 hari mendatang.
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:col-span-5 xl:grid-cols-1">
                        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                            <div class="flex items-center justify-between gap-3 border-b border-gray-200 px-5 py-4">
                                <div class="min-w-0">
                                    <h3 class="dash-title">Koleksi Kondisi Kritis</h3>
                                    <p class="dash-subtitle">Koleksi rusak berat berdasarkan pemeriksaan terakhir</p>
                                </div>
                                <a href="{{ route('kondisi.index') }}" class="dash-link shrink-0">Lihat semua</a>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @forelse ($koleksiKritis as $kondisi)
                                    <div class="px-5 py-3.5 hover:bg-slate-50/80">
                                        <a href="{{ route('koleksi.kondisi.show', [$kondisi->koleksi, $kondisi]) }}" class="block truncate dash-body-strong hover:text-indigo-600" title="{{ $kondisi->koleksi->nama }}">
                                            {{ $kondisi->koleksi->nama }}
                                        </a>
                                        <p class="mt-0.5 truncate dash-meta">{{ $kondisi->koleksi->nomor_inventaris }}</p>
                                        <div class="mt-2 flex items-center justify-between gap-2">
                                            <span class="truncate dash-meta">{{ $kondisi->tanggal_periksa->translatedFormat('d M Y') }}</span>
                                            <span class="shrink-0 rounded-full bg-red-100 px-2 py-0.5 dash-badge text-red-800">
                                                {{ $kondisi->label_rekomendasi }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-5 py-10 text-center dash-empty">
                                        Tidak ada koleksi dengan kondisi kritis.
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                            <div class="flex items-center justify-between gap-3 border-b border-gray-200 px-5 py-4">
                                <div class="min-w-0">
                                    <h3 class="dash-title">Tindakan Konservasi Berjalan</h3>
                                    <p class="dash-subtitle">Pelaksanaan konservasi yang belum selesai didokumentasikan</p>
                                </div>
                                <a href="{{ route('konservasi.tindakan.index') }}" class="dash-link shrink-0">Lihat semua</a>
                            </div>
                            <div class="divide-y divide-gray-100">
                                @forelse ($conservationActive as $action)
                                    <div class="px-5 py-3.5 hover:bg-slate-50/80">
                                        <a href="{{ route('konservasi.tindakan.show', $action) }}" class="block truncate dash-body-strong hover:text-indigo-600" title="{{ $action->koleksi->nama }}">
                                            {{ $action->koleksi->nama }}
                                        </a>
                                        <div class="mt-2 flex items-center justify-between gap-2">
                                            <span class="truncate dash-meta">{{ $action->jenis_konservasi_label }}</span>
                                            <span class="shrink-0 rounded-full px-2 py-0.5 dash-badge {{ $action->status_badge_class }}">
                                                {{ $action->status_label }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-5 py-10 text-center dash-empty">
                                        Tidak ada tindakan konservasi yang sedang berjalan.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartData = @json($chartData);

            const palette = {
                indigo: '#6366f1',
                blue: '#3b82f6',
                green: '#22c55e',
                amber: '#f59e0b',
                red: '#ef4444',
                slate: '#94a3b8',
                purple: '#a855f7',
                teal: '#14b8a6',
                orange: '#f97316',
            };

            Chart.defaults.font = {
                family: 'Figtree, ui-sans-serif, system-ui, sans-serif',
                size: 13,
            };
            Chart.defaults.color = '#64748b';

            const doughnutOptions = (compact = false) => ({
                responsive: true,
                maintainAspectRatio: false,
                cutout: compact ? '58%' : '62%',
                layout: {
                    padding: compact ? { bottom: 4 } : { bottom: 8 },
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            padding: compact ? 8 : 12,
                            font: { size: 13 },
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                const total = ctx.dataset.data.reduce((sum, value) => sum + value, 0);
                                const value = ctx.parsed ?? 0;
                                const percent = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${ctx.label}: ${value} koleksi (${percent}%)`;
                            },
                        },
                    },
                },
            });

            const barOptions = (horizontal = false, unit = 'koleksi') => ({
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: horizontal ? 'y' : 'x',
                layout: { padding: { top: 4, right: 8, bottom: 4, left: 4 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => `${ctx.parsed[horizontal ? 'x' : 'y']} ${unit}`,
                        },
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: { size: 13 } },
                        grid: horizontal ? { display: false } : { color: '#f1f5f9' },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { size: 13 },
                            autoSkip: false,
                        },
                        grid: horizontal ? { color: '#f1f5f9' } : { display: false },
                    },
                },
            });

            new Chart(document.getElementById('chartKategori'), {
                type: 'bar',
                data: {
                    labels: chartData.kategori.labels,
                    datasets: [{
                        data: chartData.kategori.data,
                        backgroundColor: [palette.indigo, palette.teal, palette.purple, palette.orange],
                        borderRadius: 6,
                        maxBarThickness: 56,
                    }],
                },
                options: barOptions(false),
            });

            new Chart(document.getElementById('chartLokasi'), {
                type: 'doughnut',
                data: {
                    labels: chartData.lokasi.labels,
                    datasets: [{
                        data: chartData.lokasi.data,
                        backgroundColor: [palette.indigo, palette.slate],
                        borderWidth: 0,
                    }],
                },
                options: doughnutOptions(false),
            });

            new Chart(document.getElementById('chartStatusSewa'), {
                type: 'doughnut',
                data: {
                    labels: chartData.statusSewa.labels,
                    datasets: [{
                        data: chartData.statusSewa.data,
                        backgroundColor: [palette.slate, palette.blue, palette.purple, palette.indigo],
                        borderWidth: 0,
                    }],
                },
                options: doughnutOptions(false),
            });

            new Chart(document.getElementById('chartKondisi'), {
                type: 'doughnut',
                data: {
                    labels: chartData.kondisi.labels,
                    datasets: [{
                        data: chartData.kondisi.data,
                        backgroundColor: [palette.green, palette.amber, palette.red, palette.slate],
                        borderWidth: 0,
                    }],
                },
                options: doughnutOptions(false),
            });

            if (chartData.rekomendasi.labels.length > 0) {
                new Chart(document.getElementById('chartRekomendasi'), {
                    type: 'bar',
                    data: {
                        labels: chartData.rekomendasi.labels,
                        datasets: [{
                            data: chartData.rekomendasi.data,
                            backgroundColor: [palette.green, palette.amber, palette.red, palette.blue],
                            borderRadius: 6,
                            maxBarThickness: 32,
                        }],
                    },
                    options: barOptions(true, 'koleksi'),
                });
            } else {
                document.getElementById('chartRekomendasiWrap').innerHTML =
                    '<div class="flex h-full min-h-[240px] items-center justify-center dash-empty">Belum ada data rekomendasi.</div>';
            }

            let timelineRange = 'harian';
            const timelineChart = new Chart(document.getElementById('chartTimeline'), {
                type: 'bar',
                data: {
                    labels: chartData.timeline[timelineRange].labels,
                    datasets: [{
                        label: 'Jadwal konservasi',
                        data: chartData.timeline[timelineRange].data,
                        backgroundColor: palette.indigo,
                        hoverBackgroundColor: '#4f46e5',
                        borderRadius: 3,
                        maxBarThickness: 22,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: { padding: { top: 8, right: 12, bottom: 4, left: 4 } },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                title: (items) => items[0]?.label ?? '',
                                label: (ctx) => `${ctx.parsed.y} jadwal`,
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 12,
                                font: { size: 13 },
                            },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, stepSize: 1, font: { size: 13 } },
                            grid: { color: '#f1f5f9' },
                        },
                    },
                },
            });

            const timelineButtons = document.querySelectorAll('.timeline-toggle');

            function setActiveTimeline(range) {
                timelineButtons.forEach((btn) => {
                    const isActive = btn.dataset.range === range;
                    btn.classList.toggle('bg-white', isActive);
                    btn.classList.toggle('text-indigo-600', isActive);
                    btn.classList.toggle('shadow-sm', isActive);
                    btn.classList.toggle('text-gray-500', !isActive);
                });
            }

            timelineButtons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    timelineRange = btn.dataset.range;
                    const series = chartData.timeline[timelineRange];
                    timelineChart.data.labels = series.labels;
                    timelineChart.data.datasets[0].data = series.data;
                    timelineChart.update();
                    setActiveTimeline(timelineRange);
                });
            });

            setActiveTimeline(timelineRange);

            new Chart(document.getElementById('chartPerawatan'), {
                type: 'doughnut',
                data: {
                    labels: chartData.perawatan.labels,
                    datasets: [{
                        data: chartData.perawatan.data,
                        backgroundColor: [palette.blue, palette.green, palette.red],
                        borderWidth: 0,
                    }],
                },
                options: {
                    ...doughnutOptions(true),
                    plugins: {
                        ...doughnutOptions(true).plugins,
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const total = ctx.dataset.data.reduce((sum, value) => sum + value, 0);
                                    const value = ctx.parsed ?? 0;
                                    const percent = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${ctx.label}: ${value} jadwal (${percent}%)`;
                                },
                            },
                        },
                    },
                },
            });

            new Chart(document.getElementById('chartKonservasi'), {
                type: 'doughnut',
                data: {
                    labels: chartData.konservasi.labels,
                    datasets: [{
                        data: chartData.konservasi.data,
                        backgroundColor: [palette.blue, palette.amber, palette.green],
                        borderWidth: 0,
                    }],
                },
                options: {
                    ...doughnutOptions(true),
                    plugins: {
                        ...doughnutOptions(true).plugins,
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const total = ctx.dataset.data.reduce((sum, value) => sum + value, 0);
                                    const value = ctx.parsed ?? 0;
                                    const percent = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${ctx.label}: ${value} tindakan (${percent}%)`;
                                },
                            },
                        },
                    },
                },
            });
        });
    </script>
</x-app-layout>
