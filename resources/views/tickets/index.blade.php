<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <span class="text-gray-700 font-medium">Tiket</span>
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
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Kelola Tiket</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Daftar semua tiket yang tersedia di museum</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-indigo-100 text-indigo-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>
                            {{ $tickets->total() }} Total Tiket
                        </span>
                        <a href="{{ route('tickets.create') }}"
                           class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Tiket
                        </a>
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

                {{-- Statistik Ringkas --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Total Tiket</p>
                            <p class="text-xl font-bold text-gray-900">{{ $tickets->total() }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Tiket Reguler</p>
                            <p class="text-xl font-bold text-emerald-600">{{ $tickets->where('jenis_tiket', 'reguler')->count() }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Tiket Event</p>
                            <p class="text-xl font-bold text-orange-600">{{ $tickets->where('jenis_tiket', 'event')->count() }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">Total Kuota</p>
                            <p class="text-xl font-bold text-purple-600">{{ $tickets->sum('kuota') }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
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
                                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="akan_datang" {{ request('status') === 'akan_datang' ? 'selected' : '' }}>Akan Datang</option>
                                <option value="berakhir" {{ request('status') === 'berakhir' ? 'selected' : '' }}>Berakhir</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Cari Tiket</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Cari nama tiket..."
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
                            <a href="{{ route('tickets.index') }}"
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
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3">Tiket</th>
                                    <th class="px-4 py-3">Jenis</th>
                                    <th class="px-4 py-3 hidden md:table-cell">Kategori</th>
                                    <th class="px-4 py-3 text-right">Harga</th>
                                    <th class="px-4 py-3 text-center hidden sm:table-cell">Kuota</th>
                                    <th class="px-4 py-3 text-center hidden lg:table-cell">Periode</th>
                                    <th class="px-4 py-3 text-center">Status</th>
                                    <th class="px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($tickets as $t)
                                <tr class="hover:bg-gray-50 transition">
                                    {{-- Nama + gambar --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            @if($t->gambar)
                                                <img src="{{ asset('storage/gambar/'.$t->gambar) }}"
                                                     class="w-10 h-10 object-cover rounded-lg flex-shrink-0 border border-gray-100">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-medium text-gray-800">{{ $t->nama_tiket }}</div>
                                                @if($t->sub_kategori)
                                                    <div class="text-xs text-gray-400">{{ $t->sub_kategori }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Jenis --}}
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                                            {{ $t->jenis_tiket === 'reguler' ? 'bg-blue-50 text-blue-700' : 'bg-orange-50 text-orange-700' }}">
                                            {{ ucfirst($t->jenis_tiket) }}
                                        </span>
                                        @if($t->sub_jenis)
                                            <div class="text-[10px] text-gray-400 mt-0.5">{{ ucfirst($t->sub_jenis) }}</div>
                                        @endif
                                    </td>

                                    {{-- Kategori --}}
                                    <td class="px-4 py-3 hidden md:table-cell">
                                        <span class="text-gray-600">{{ $t->kategori_pengunjung }}</span>
                                    </td>

                                    {{-- Harga --}}
                                    <td class="px-4 py-3 text-right">
                                        <span class="font-semibold text-gray-800">
                                            Rp {{ number_format($t->harga, 0, ',', '.') }}
                                        </span>
                                    </td>

                                    {{-- Kuota --}}
                                    <td class="px-4 py-3 text-center hidden sm:table-cell">
                                        <span class="font-medium text-gray-700">{{ $t->kuota }}</span>
                                        <span class="text-xs text-gray-400 ml-0.5">slot</span>
                                    </td>

                                    {{-- Periode --}}
                                    <td class="px-4 py-3 text-center hidden lg:table-cell">
                                        @if($t->tanggal_mulai)
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($t->tanggal_mulai)->format('d/m/Y') }}
                                                @if($t->tanggal_selesai)
                                                    <span class="text-gray-300 mx-0.5">→</span>
                                                    {{ \Carbon\Carbon::parse($t->tanggal_selesai)->format('d/m/Y') }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            {{-- Status Utama --}}
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $t->getStatusBadgeClass() }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $t->getStatusDotClass() }} mr-1.5"></span>
                                                {{ $t->getDisplayStatus() }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap items-center justify-center gap-1">
                                            <a href="{{ route('tickets.show', $t->id) }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition"
                                            title="Detail">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span class="hidden sm:inline">Detail</span>
                                            </a>

                                            <a href="{{ route('tickets.edit', $t->id) }}"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-amber-50 text-amber-700 hover:bg-amber-100 transition"
                                            title="Edit">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                <span class="hidden sm:inline">Edit</span>
                                            </a>

                                            {{-- Tombol Hapus - Hanya tampil jika bisa dihapus --}}
                                            @if($t->canBeDeleted())
                                                <form action="{{ route('tickets.destroy', $t->id) }}" method="POST" class="inline-block"
                                                    onsubmit="return confirm('Yakin ingin menghapus tiket {{ $t->nama_tiket }} secara permanen? Tindakan ini tidak dapat dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 transition"
                                                            title="Hapus">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        <span class="hidden sm:inline">Hapus</span>
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Tampilkan tombol disabled dengan tooltip --}}
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-400 cursor-not-allowed"
                                                    title="{{ $t->getCannotDeleteReason() }}">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                    <span class="hidden sm:inline">Tidak Bisa Hapus</span>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-12 text-center">
                                        <svg class="mx-auto mb-3 h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                        <p class="font-medium text-gray-600">Belum ada tiket</p>
                                        <p class="text-sm text-gray-400 mt-1">Mulai dengan menambahkan tiket baru</p>
                                        <a href="{{ route('tickets.create') }}"
                                           class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                                            + Tambah Tiket
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4 py-3 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-2">
                        <span class="text-xs text-gray-500">
                            Menampilkan {{ $tickets->firstItem() ?? 0 }} - {{ $tickets->lastItem() ?? 0 }} 
                            dari {{ $tickets->total() }} data
                        </span>
                        {{ $tickets->withQueryString()->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>