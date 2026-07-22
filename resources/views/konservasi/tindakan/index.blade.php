@php use App\Models\ConservationAction; @endphp
<x-app-layout>
    <div class="py-12">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- ── HEADER ─────────────────────────────────────────────── --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                <div class="space-y-2">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Tindakan Konservasi') }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        Menampilkan {{ $actions->count() }} dari {{ $actions->total() }} tindakan.
                        @if($search)
                            Hasil untuk "{{ $search }}".
                        @endif
                    </p>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <form method="GET" action="{{ route('konservasi.tindakan.index') }}" class="grid gap-4 lg:grid-cols-[minmax(180px,1fr)_minmax(180px,1fr)_auto]">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Koleksi / Inventaris</label>
                            <input type="text" name="search" value="{{ $search }}" placeholder="Nama koleksi atau nomor inventaris"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua</option>
                                @foreach (ConservationAction::STATUS_OPTIONS as $value => $label)
                                    <option value="{{ $value }}" {{ $statusFilter === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                                Filter
                            </button>
                            <a href="{{ route('konservasi.tindakan.index') }}" class="inline-flex items-center justify-center rounded-lg bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-4 text-sm text-green-900 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-max w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                            <th class="px-5 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Nomor</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Nama Koleksi</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Nomor Inventaris</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Jenis Konservasi</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Tanggal Jadwal</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Penanggung Jawab</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3 text-right font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($actions as $action)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4 text-gray-700">{{ $action->id }}</td>
                                <td class="px-5 py-4 text-gray-900">{{ $action->koleksi->nama }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $action->koleksi->nomor_inventaris ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $action->jenis_konservasi_label }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ optional($action->perawatanKoleksi->jadwal_tanggal)->format('d M Y') ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $action->perawatanKoleksi->penanggung_jawab ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $action->getStatusBadgeClassAttribute() }}">
                                        {{ $action->status_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('konservasi.tindakan.show', $action) }}" class="inline-flex items-center rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-sm text-gray-400">Tidak ada tindakan konservasi ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>

                <div class="px-4 py-4 border-t border-gray-100 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <label for="per_page_footer" class="font-medium">Jumlah per halaman</label>
                        <form id="per-page-form" method="GET" action="{{ route('konservasi.tindakan.index') }}" class="inline">
                            @foreach (['search', 'status', 'per_page'] as $param)
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
                        {{ $actions->links('vendor.pagination.simple-tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
