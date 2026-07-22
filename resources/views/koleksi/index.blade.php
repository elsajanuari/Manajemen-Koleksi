<x-app-layout>
    <div class="py-12">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="space-y-6">
                    <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
                        <div class="space-y-2">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                {{ __('Pengelolaan Koleksi') }}
                            </h2>
                            <p class="text-sm text-gray-500">
                                Menampilkan {{ $koleksis->count() }} dari {{ $koleksis->total() }} koleksi.
                                @if($search)
                                    Hasil untuk "{{ $search }}".
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center justify-start gap-2 md:justify-end">
                            <a href="{{ route('koleksi.create') }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                {{ __('Tambah Koleksi') }}
                            </a>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('koleksi.index') }}">
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                            <div class="grid gap-4 lg:grid-cols-6 lg:items-end">
                                <div class="lg:col-span-2">
                                    <label for="search" class="block text-sm font-medium text-gray-700">Cari</label>
                                    <x-text-input id="search" name="search" type="text" value="{{ $search ?? '' }}" placeholder="Nama, kategori..." class="mt-1 h-11 w-full" />
                                </div>
                                <div>
                                    <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
                                    <select id="kategori" name="kategori" class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                                        <option value="">Semua</option>
                                        @foreach ($kategoriOptions as $kategori)
                                            <option value="{{ $kategori }}" {{ $kategoriFilter === $kategori ? 'selected' : '' }}>{{ ucfirst($kategori) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="sort" class="block text-sm font-medium text-gray-700">Urutkan</label>
                                    <select id="sort" name="sort" class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                                        @foreach ($sortOptions as $value => $label)
                                            <option value="{{ $value }}" {{ $sort === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Ketersediaan Sewa/Beli</label>
                                    <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                                        <option value="">Semua</option>
                                        @foreach (\App\Models\Koleksi::statusSewaOptions() as $value => $option)
                                            <option value="{{ $value }}" {{ $statusFilter === $value ? 'selected' : '' }}>{{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi Fisik</label>
                                    <select id="lokasi" name="lokasi" class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                                        <option value="">Semua</option>
                                        @foreach (\App\Models\Koleksi::lokasiOptions() as $value => $option)
                                            <option value="{{ $value }}" {{ $lokasiFilter === $value ? 'selected' : '' }}>{{ $option['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-center gap-2 lg:justify-end">
                                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        {{ __('Filter') }}
                                    </button>
                                    <a href="{{ route('koleksi.index') }}" class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                        {{ __('Reset') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if($search || $kategoriFilter || $statusFilter || $lokasiFilter || $sort !== 'newest')
                        <div class="flex flex-wrap gap-2 items-center">
                            <span class="text-sm font-medium text-gray-600">Filter aktif:</span>
                            @if($search)
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800">
                                    Cari: "{{ $search }}"
                                </span>
                            @endif
                            @if($kategoriFilter)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                    Kategori: {{ ucfirst($kategoriFilter) }}
                                </span>
                            @endif
                            @if($statusFilter)
                                <span class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800">
                                    Ketersediaan: {{ \App\Models\Koleksi::labelStatusSewa($statusFilter) }}
                                </span>
                            @endif
                            @if($lokasiFilter)
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">
                                    Lokasi: {{ \App\Models\Koleksi::labelLokasi($lokasiFilter) }}
                                </span>
                            @endif
                            @if($sort !== 'newest')
                                <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-800">
                                    Urutkan: {{ $sortOptions[$sort] ?? $sort }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full table-fixed text-sm divide-y divide-gray-200">
                        <colgroup>
                            <col class="w-[7.5rem]">
                            <col class="w-14">
                            <col class="w-[11rem]">
                            <col class="w-[8.5rem]">
                            <col class="w-20">
                            <col class="w-24">
                            <col class="w-[9.5rem]">
                            <col class="w-[7.5rem]">
                            <col class="w-14">
                            <col class="w-24">
                        </colgroup>
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">No. Inv.</th>
                                <th class="px-2 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Foto</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Seniman/Penulis</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Kondisi</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Ketersediaan</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Lokasi</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Tahun</th>
                                <th class="px-3 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                                @forelse ($koleksis as $koleksi)
                                    @php $badge = $koleksi->getStatusBadgeInfo(); @endphp
                                    <tr class="hover:bg-slate-50/80 align-middle">
                                        <td class="px-3 py-3 text-xs font-mono text-gray-600 truncate" title="{{ $koleksi->nomor_inventaris }}">{{ $koleksi->nomor_inventaris ?? '-' }}</td>
                                        <td class="px-2 py-3">
                                            @if ($koleksi->foto)
                                                <img src="{{ asset('storage/' . $koleksi->foto) }}" alt="{{ $koleksi->nama }}" class="h-11 w-11 rounded-lg object-cover border border-gray-200" />
                                            @else
                                                <div class="h-11 w-11 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center text-[10px] text-gray-400">—</div>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 font-medium text-gray-900 truncate" title="{{ $koleksi->nama }}">{{ $koleksi->nama }}</td>
                                        <td class="px-3 py-3 text-gray-600 truncate" title="{{ $koleksi->seniman }}">{{ $koleksi->seniman ?? '-' }}</td>
                                        <td class="px-3 py-3 text-gray-600 capitalize">{{ $koleksi->kategori }}</td>
                                        <td class="px-3 py-3 text-gray-600 truncate" title="{{ $koleksi->current_kondisi }}">{{ $koleksi->current_kondisi ?? '-' }}</td>
                                        <td class="px-3 py-3">
                                            <span class="inline-block max-w-full truncate rounded-full {{ $badge['bgColor'] }} px-2 py-0.5 text-[11px] font-medium {{ $badge['textColor'] }}" title="{{ $badge['label'] }}">{{ $badge['label'] }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            @if ($koleksi->lokasi === 'dipamerkan')
                                                <span class="inline-block truncate rounded-full bg-indigo-100 px-2 py-0.5 text-[11px] font-medium text-indigo-800">{{ \App\Models\Koleksi::labelLokasi($koleksi->lokasi) }}</span>
                                            @else
                                                <span class="inline-block truncate rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-700">{{ \App\Models\Koleksi::labelLokasi($koleksi->lokasi) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 text-gray-600">{{ $koleksi->tahun ?? '-' }}</td>
                                        <td class="px-3 py-3">
                                            <div class="flex flex-col gap-1 text-xs font-medium">
                                                <a href="{{ route('koleksi.show', $koleksi) }}" class="text-gray-600 hover:text-gray-900">Detail</a>
                                                <a href="{{ route('koleksi.edit', $koleksi) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                                <form action="{{ route('koleksi.destroy', $koleksi) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin ingin menghapus koleksi ini?');">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-4 py-10 text-center text-sm text-gray-500">Tidak ada koleksi yang ditemukan.</td>
                                    </tr>
                                @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-4 border-t border-gray-100 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <label for="per_page_footer" class="font-medium">Jumlah per halaman</label>
                        <form id="per-page-form" method="GET" action="{{ route('koleksi.index') }}" class="inline">
                            @foreach (['search','kategori','status','lokasi','sort','per_page'] as $param)
                                @if(request()->query($param) !== null)
                                    <input type="hidden" name="{{ $param }}" value="{{ request()->query($param) }}">
                                @endif
                            @endforeach
                            <select id="per_page_footer" name="per_page" onchange="this.form.submit()" class="h-10 rounded-md border border-gray-300 bg-white py-2 px-3 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500">
                                @foreach ($perPageOptions as $size)
                                    <option value="{{ $size }}" {{ $perPage === $size ? 'selected' : '' }}>{{ $size }} data</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="w-full sm:w-auto">
                        {{ $koleksis->links('vendor.pagination.simple-tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>