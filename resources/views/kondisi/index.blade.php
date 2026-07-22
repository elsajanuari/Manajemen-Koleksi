@php use App\Models\KondisiKoleksi; @endphp
<x-app-layout>
    <div class="py-12">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="space-y-6">
                    <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
                        <div class="space-y-2">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('Daftar Kondisi Koleksi') }}
                            </h2>
                            <p class="text-sm text-gray-500">
                                Menampilkan {{ $kondisiKoleksis->count() }} dari {{ $kondisiKoleksis->total() }} catatan kondisi.
                                @if ($search)
                                    Hasil untuk "{{ $search }}".
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center justify-start gap-2 md:justify-end">
                            <a href="{{ route('koleksi.index') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                {{ __('Kembali ke Koleksi') }}
                            </a>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('kondisi.index') }}">
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                            <div class="grid gap-4 lg:grid-cols-5 lg:items-end">
                                <div class="lg:col-span-2">
                                    <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                                    <x-text-input id="search" name="search" type="text" value="{{ $search ?? '' }}" placeholder="Nama koleksi, inventaris, atau pemeriksa..." class="mt-1 h-11 w-full" />
                                </div>
                                <div>
                                    <label for="kondisi" class="block text-sm font-medium text-gray-700">Kondisi</label>
                                    <select id="kondisi" name="kondisi" class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                                        <option value="">Semua</option>
                                        @foreach (KondisiKoleksi::KONDISI_OPTIONS as $value => $label)
                                            <option value="{{ $value }}" {{ ($kondisiFilter ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="rekomendasi" class="block text-sm font-medium text-gray-700">Rekomendasi</label>
                                    <select id="rekomendasi" name="rekomendasi" class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                                        <option value="">Semua</option>
                                        @foreach (KondisiKoleksi::REKOMENDASI_OPTIONS as $value => $label)
                                            <option value="{{ $value }}" {{ ($rekomendasiFilter ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-center gap-2 lg:justify-end">
                                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        {{ __('Filter') }}
                                    </button>
                                    <a href="{{ route('kondisi.index') }}" class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                        {{ __('Reset') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if ($search || $kondisiFilter || $rekomendasiFilter)
                        <div class="flex flex-wrap gap-2 items-center">
                            <span class="text-sm font-medium text-gray-600">Filter aktif:</span>
                            @if ($search)
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">
                                    Cari: "{{ $search }}"
                                </span>
                            @endif
                            @if ($kondisiFilter)
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">
                                    Kondisi: {{ KondisiKoleksi::KONDISI_OPTIONS[$kondisiFilter] ?? $kondisiFilter }}
                                </span>
                            @endif
                            @if ($rekomendasiFilter)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                    Rekomendasi: {{ KondisiKoleksi::REKOMENDASI_OPTIONS[$rekomendasiFilter] ?? $rekomendasiFilter }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed text-sm divide-y divide-gray-200">
                        <colgroup>
                            <col class="w-16">
                            <col class="w-48">
                            <col class="w-32">
                            <col class="w-28">
                            <col class="w-28">
                            <col class="w-32">
                            <col class="w-24">
                        </colgroup>
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">No.</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Koleksi</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Kondisi</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Pemeriksa</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Rekomendasi</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($kondisiKoleksis as $kondisi)
                                <tr class="hover:bg-slate-50/80 align-middle">
                                    <td class="px-3 py-3 text-xs text-gray-600">{{ ($kondisiKoleksis->currentPage() - 1) * $kondisiKoleksis->perPage() + $loop->iteration }}</td>
                                    <td class="px-3 py-3 text-gray-700 truncate" title="{{ $kondisi->koleksi->nama }}">{{ $kondisi->koleksi->nama }}</td>
                                    <td class="px-3 py-3 text-gray-600">{{ $kondisi->tanggal_periksa->format('d M Y') }}</td>
                                    <td class="px-3 py-3">
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-[11px] font-medium {{ $kondisi->badge_class }}">{{ $kondisi->label_kondisi }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-gray-600">{{ $kondisi->pemeriksa }}</td>
                                    <td class="px-3 py-3 text-gray-600">{{ $kondisi->label_rekomendasi }}</td>
                                    <td class="px-3 py-3">
                                        <div class="flex flex-col gap-1 text-xs font-medium">
                                            <a href="{{ route('koleksi.kondisi.show', [$kondisi->koleksi, $kondisi]) }}" class="text-gray-600 hover:text-gray-900">Detail</a>
                                            <a href="{{ route('koleksi.kondisi.edit', [$kondisi->koleksi, $kondisi]) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-sm text-gray-500">Tidak ada catatan kondisi koleksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-4 border-t border-gray-100 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <label for="per_page_footer" class="font-medium">Jumlah per halaman</label>
                        <form id="per-page-form" method="GET" action="{{ route('kondisi.index') }}" class="inline">
                            @if ($search)
                                <input type="hidden" name="search" value="{{ $search }}">
                            @endif
                            @if ($kondisiFilter)
                                <input type="hidden" name="kondisi" value="{{ $kondisiFilter }}">
                            @endif
                            @if ($rekomendasiFilter)
                                <input type="hidden" name="rekomendasi" value="{{ $rekomendasiFilter }}">
                            @endif
                            <select id="per_page_footer" name="per_page" onchange="this.form.submit()" class="h-10 rounded-md border border-gray-300 bg-white py-2 px-3 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                                @foreach ($perPageOptions as $size)
                                    <option value="{{ $size }}" {{ $perPage === $size ? 'selected' : '' }}>{{ $size }} data</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="w-full sm:w-auto">
                        {{ $kondisiKoleksis->links('vendor.pagination.simple-tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
