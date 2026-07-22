<x-app-layout>
    @php
        $koleksiIndexUrl = route('koleksi.index');
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Koleksi') }}
            </h2>
            <x-back-link :href="$koleksiIndexUrl" label="Kembali" />
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <x-back-link :href="$koleksiIndexUrl" label="Kembali ke daftar koleksi" />

            {{-- ===== KARTU INFO KOLEKSI ===== --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl">
                <div class="p-6 md:p-10">

                    @if (session('success'))
                        <div class="mb-6 rounded-lg bg-green-100 p-4 text-green-800 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid gap-8 lg:grid-cols-[minmax(420px,520px)_minmax(700px,1fr)]">

                        {{-- Foto --}}
                        <div class="w-full">
                            @if ($koleksi->foto)
                                <img
                                    src="{{ asset('storage/' . $koleksi->foto) }}"
                                    alt="{{ $koleksi->nama }}"
                                    class="w-full h-80 lg:h-[28rem] object-cover rounded-3xl border border-gray-200 shadow-sm"
                                    onerror="this.onerror=null; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden');"
                                />
                                <div class="hidden w-full h-72 md:h-80 rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-gray-400">Foto tidak tersedia</p>
                                </div>
                            @else
                                <div class="w-full h-72 md:h-80 rounded-xl border-2 border-dashed border-gray-300 flex flex-col items-center justify-center bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-gray-400">Tidak ada foto</p>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 space-y-5">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $koleksi->nama }}</h1>
                                <p class="mt-1 text-sm text-gray-500 capitalize">{{ $koleksi->kategori }}</p>
                            </div>

                            <div class="space-y-6">
                                <div class="rounded-3xl border border-gray-200 bg-slate-50 p-5">
                                    <h3 class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Ketersediaan Sewa & Beli
                                    </h3>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @if ($koleksi->status_sewa === 'sewa_beli')
                                            <x-koleksi-badge :badge="$koleksi->getStatusBadgeFor('sewa')" />
                                            <x-koleksi-badge :badge="$koleksi->getStatusBadgeFor('beli')" />
                                        @else
                                            <x-koleksi-badge :badge="$koleksi->getStatusBadgeInfo()" />
                                        @endif
                                    </div>
                                    <dl class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tarif Sewa / Hari</dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900">
                                                @if ($koleksi->daily_rate > 0)
                                                    Rp {{ number_format($koleksi->daily_rate, 0, ',', '.') }}
                                                @else
                                                    —
                                                @endif
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual</dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900">
                                                @if ($koleksi->sale_price)
                                                    Rp {{ number_format($koleksi->sale_price, 0, ',', '.') }}
                                                @else
                                                    —
                                                @endif
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Berat</dt>
                                            <dd class="mt-1 text-sm font-medium text-gray-900">
                                                @if ($koleksi->weight_gram)
                                                    {{ number_format($koleksi->weight_gram, 0, ',', '.') }} gram
                                                @else
                                                    —
                                                @endif
                                            </dd>
                                        </div>
                                    </dl>
                                </div>

                                <div class="rounded-3xl border border-gray-200 bg-white p-5">
                                    <h3 class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Informasi Koleksi
                                    </h3>
                                    @php
                                        $yearLabel = match(strtolower($koleksi->kategori)) {
                                            'lukisan' => 'Tahun Pembuatan',
                                            'buku' => 'Tahun Terbit',
                                            default => 'Tahun Pembuatan',
                                        };
                                    @endphp
                                    <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Inventaris</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $koleksi->nomor_inventaris ?? 'Belum ditetapkan' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Seniman / Penulis</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $koleksi->seniman ?? 'Tidak tersedia' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi Terakhir</dt>
                                            <dd class="mt-1">
                                                @if ($koleksi->current_kondisi)
                                                    <span class="text-sm text-gray-900">{{ $koleksi->current_kondisi }}</span>
                                                @else
                                                    <x-koleksi-badge :badge="['label' => 'Belum diperiksa', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'bgColor' => 'bg-amber-100', 'textColor' => 'text-amber-800']" class="text-xs" />
                                                @endif
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Ringkasan Lingkungan</dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                {{ optional($koleksi->kondisiTerakhir)->lingkungan_summary ?? '-' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kerusakan</dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                {{ optional($koleksi->kondisiTerakhir)->jenis_kerusakan ?: '-' }}
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Koleksi</dt>
                                            <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $koleksi->kategori }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $yearLabel }}</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $koleksi->tahun ?? '-' }}</dd>
                                        </div>
                                        @if(strtolower($koleksi->kategori) === 'lukisan')
                                            <div>
                                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Teknik / Media</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $koleksi->teknik_media ?? '-' }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran Lukisan</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $koleksi->ukuran_lukisan ?? '-' }}</dd>
                                            </div>
                                        @endif
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Masuk</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $koleksi->created_at->format('d M Y') }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Fisik</dt>
                                            <dd class="mt-1">
                                                <x-koleksi-badge :badge="\App\Models\Koleksi::lokasiBadgeInfo($koleksi->lokasi)" class="text-xs" />
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    @if ($koleksi->deskripsi)
                        <div class="mt-8 border-t border-gray-100 pt-6">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Deskripsi</h3>
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $koleksi->deskripsi }}</p>
                        </div>
                    @endif

                    {{-- Actions Koleksi --}}
                    <div class="mt-8 border-t border-gray-100 pt-6 flex items-center gap-3">
                        <a href="{{ route('koleksi.edit', $koleksi) }}"
                           class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Koleksi
                        </a>
                        <form action="{{ route('koleksi.destroy', $koleksi) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Yakin ingin menghapus koleksi ini?');"
                                    class="inline-flex items-center gap-2 rounded-md border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-600 shadow-sm hover:bg-red-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">

                    @php
                        $totalRiwayatKondisi = $koleksi->kondisis->count();
                        $riwayatKondisiTerbaru = $koleksi->kondisis->take(5);
                    @endphp
                    <div class="flex items-center justify-between mb-6 gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Riwayat Pemeriksaan Kondisi</h2>
                            <p class="mt-0.5 text-sm text-gray-500">
                                {{ $totalRiwayatKondisi }} catatan pemeriksaan
                            </p>
                        </div>
                        <div class="flex flex-wrap items-center justify-end gap-2">
                            <a href="{{ route('kondisi.index') }}"
                               class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                                Lihat Semua
                            </a>
                            <a href="{{ route('koleksi.kondisi.create', $koleksi) }}"
                               class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Catatan Baru
                            </a>
                        </div>
                    </div>

                    @if ($riwayatKondisiTerbaru->isEmpty())
                        <div class="rounded-xl border-2 border-dashed border-gray-200 py-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-sm font-medium text-gray-500">Belum ada catatan pemeriksaan</p>
                            <p class="mt-1 text-xs text-gray-400">Klik "Catatan Baru" untuk menambahkan catatan pemeriksaan.</p>
                        </div>
                    @else
                        <div class="rounded-xl border border-gray-200 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-max w-full table-fixed text-sm divide-y divide-gray-200">
                                    <colgroup>
                                    <col class="w-[6.5rem]">
                                    <col class="w-[6.75rem]">
                                    <col class="w-[6.25rem]">
                                    <col class="w-[11%]">
                                    <col class="w-[8%]">
                                    <col class="w-[10%]">
                                    <col class="w-[3.75rem]">
                                    <col class="w-[9%]">
                                    <col>
                                    <col class="w-[6.5rem]">
                                </colgroup>
                                <thead>
                                    <tr class="bg-slate-50 border-b border-gray-200">
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Tanggal</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Kondisi</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Perubahan</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Lingkungan</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Kerusakan</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Rekomendasi</th>
                                        <th scope="col" class="px-2 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wide">Foto</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Pemeriksa</th>
                                        <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wide">Catatan</th>
                                        <th scope="col" class="px-3 py-3 text-center text-xs font-semibold text-slate-600 uppercase tracking-wide">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($riwayatKondisiTerbaru as $index => $kondisi)
                                        @php
                                            $comparison = $kondisi->getConditionComparison();
                                            $hasLingkungan = $kondisi->kebersihan_lingkungan
                                                || $kondisi->suhu !== null
                                                || $kondisi->kelembapan !== null
                                                || $kondisi->pencahayaan;
                                            $suhuFormatted = $kondisi->suhu !== null
                                                ? rtrim(rtrim(number_format($kondisi->suhu, 1, '.', ''), '0'), '.') . '°C'
                                                : null;
                                            $rowConditionColor = match ($kondisi->kondisi) {
                                                'baik' => 'bg-emerald-50',
                                                'rusak_ringan' => 'bg-amber-50',
                                                'rusak_berat' => 'bg-rose-50',
                                                default => ($index % 2 === 1 ? 'bg-slate-50/80' : 'bg-white'),
                                            };
                                            $rowHighlight = $index === 0 ? 'ring-1 ring-inset ring-indigo-100' : '';
                                        @endphp
                                        <tr class="align-top transition-colors {{ $rowConditionColor }} {{ $rowHighlight }} hover:bg-slate-100">
                                            {{-- Tanggal --}}
                                            <td class="px-3 py-3.5">
                                                <time class="block text-sm font-medium text-gray-900 whitespace-nowrap" datetime="{{ $kondisi->tanggal_periksa->format('Y-m-d') }}">
                                                    {{ $kondisi->tanggal_periksa->format('d M Y') }}
                                                </time>
                                                @if ($index === 0)
                                                    <span class="mt-1 inline-flex items-center rounded-md bg-indigo-600 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-white">
                                                        Terbaru
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Kondisi --}}
                                            <td class="px-3 py-3.5">
                                                <span class="inline-flex items-center justify-center min-w-[4.5rem] rounded-lg px-2.5 py-1 text-xs font-bold shadow-sm ring-1 ring-inset ring-black/5 {{ $kondisi->badge_class }}">
                                                    {{ $kondisi->label_kondisi }}
                                                </span>
                                            </td>

                                            {{-- Perubahan --}}
                                            <td class="px-3 py-3.5">
                                                @if ($comparison['status_perubahan'])
                                                    <span class="inline-flex items-center gap-1 rounded-lg px-2 py-1 text-xs font-medium
                                                        @if ($comparison['status_perubahan'] === 'meningkat')
                                                            bg-green-50 text-green-800 ring-1 ring-green-200
                                                        @elseif ($comparison['status_perubahan'] === 'menurun')
                                                            bg-red-50 text-red-800 ring-1 ring-red-200
                                                        @else
                                                            bg-amber-50 text-amber-800 ring-1 ring-amber-200
                                                        @endif">
                                                        @if ($comparison['status_perubahan'] === 'meningkat')
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                                                        @elseif ($comparison['status_perubahan'] === 'menurun')
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" /></svg>
                                                        @endif
                                                        <span class="leading-tight">{{ $comparison['label_status'] }}</span>
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-1 text-xs text-slate-600 ring-1 ring-slate-200" title="Belum ada pemeriksaan sebelumnya">
                                                        {{ $comparison['label_status'] }}
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Lingkungan --}}
                                            <td class="px-3 py-3.5">
                                                @if ($hasLingkungan)
                                                    <ul class="space-y-1 text-xs text-gray-700">
                                                        @if ($kondisi->kebersihan_lingkungan)
                                                            <li class="flex gap-1.5">
                                                                <span class="shrink-0 text-gray-400 w-16">Kebersihan</span>
                                                                <span class="font-medium text-gray-800">{{ ucfirst($kondisi->kebersihan_lingkungan) }}</span>
                                                            </li>
                                                        @endif
                                                        @if ($suhuFormatted)
                                                            <li class="flex gap-1.5">
                                                                <span class="shrink-0 text-gray-400 w-16">Suhu</span>
                                                                <span class="font-medium text-gray-800">{{ $suhuFormatted }}</span>
                                                            </li>
                                                        @endif
                                                        @if ($kondisi->kelembapan !== null)
                                                            <li class="flex gap-1.5">
                                                                <span class="shrink-0 text-gray-400 w-16">Kelembapan</span>
                                                                <span class="font-medium text-gray-800">{{ $kondisi->kelembapan }}%</span>
                                                            </li>
                                                        @endif
                                                        @if ($kondisi->pencahayaan)
                                                            <li class="flex gap-1.5">
                                                                <span class="shrink-0 text-gray-400 w-16">Cahaya</span>
                                                                <span class="font-medium text-gray-800">{{ $kondisi->pencahayaan_label }}</span>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                @else
                                                    <span class="text-sm text-gray-400">—</span>
                                                @endif
                                            </td>

                                            {{-- Kerusakan --}}
                                            <td class="px-3 py-3.5 text-sm text-gray-800">
                                                @if ($kondisi->jenis_kerusakan)
                                                    <p class="line-clamp-3 leading-relaxed" title="{{ $kondisi->jenis_kerusakan }}">{{ $kondisi->jenis_kerusakan }}</p>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>

                                            {{-- Rekomendasi --}}
                                            <td class="px-3 py-3.5">
                                                @if ($kondisi->rekomendasi_tindak_lanjut)
                                                    <span class="inline-flex max-w-full items-center rounded-lg bg-blue-50 px-2 py-1 text-xs font-medium text-blue-800 ring-1 ring-blue-200" title="{{ $kondisi->label_rekomendasi }}">
                                                        <span class="line-clamp-2 leading-snug">{{ $kondisi->label_rekomendasi }}</span>
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-400">—</span>
                                                @endif
                                            </td>

                                            {{-- Foto --}}
                                            <td class="px-2 py-3.5 text-center">
                                                @if ($kondisi->foto_kondisi_saat_ini_url)
                                                    <a href="{{ $kondisi->foto_kondisi_saat_ini_url }}" target="_blank" rel="noopener noreferrer"
                                                       class="inline-block rounded-lg overflow-hidden border border-gray-200 shadow-sm hover:ring-2 hover:ring-indigo-300 transition-shadow"
                                                       title="Buka foto kondisi">
                                                        <img src="{{ $kondisi->foto_kondisi_saat_ini_url }}" alt="Foto kondisi {{ $kondisi->tanggal_periksa->format('d M Y') }}" class="h-11 w-11 object-cover" />
                                                    </a>
                                                @else
                                                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-lg border border-dashed border-gray-200 bg-gray-50 text-gray-300" title="Tidak ada foto">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Pemeriksa --}}
                                            <td class="px-3 py-3.5">
                                                <p class="text-sm font-medium text-gray-900 line-clamp-2 leading-relaxed" title="{{ $kondisi->pemeriksa }}">
                                                    {{ $kondisi->pemeriksa }}
                                                </p>
                                            </td>

                                            {{-- Catatan --}}
                                            <td class="px-3 py-3.5">
                                                @if ($kondisi->catatan)
                                                    <p class="text-sm text-gray-600 line-clamp-3 leading-relaxed" title="{{ $kondisi->catatan }}">
                                                        {{ $kondisi->catatan }}
                                                    </p>
                                                @else
                                                    <span class="text-sm text-gray-400">—</span>
                                                @endif
                                            </td>

                                            {{-- Aksi --}}
                                            <td class="px-3 py-3.5">
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <a href="{{ route('koleksi.kondisi.show', [$koleksi, $kondisi]) }}"
                                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-indigo-600 shadow-sm hover:border-indigo-200 hover:bg-indigo-50 transition-colors"
                                                       title="Lihat detail">
                                                        <span class="sr-only">Detail</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                    </a>
                                                    <a href="{{ route('koleksi.kondisi.edit', [$koleksi, $kondisi]) }}"
                                                       class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-indigo-600 shadow-sm hover:border-indigo-200 hover:bg-indigo-50 transition-colors"
                                                       title="Edit catatan">
                                                        <span class="sr-only">Edit</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    </a>
                                                    <form action="{{ route('koleksi.kondisi.destroy', [$koleksi, $kondisi]) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                onclick="return confirm('Hapus catatan pemeriksaan ini? Foto yang terkait juga akan dihapus.');"
                                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 bg-white text-red-600 shadow-sm hover:border-red-200 hover:bg-red-50 transition-colors"
                                                                title="Hapus catatan">
                                                            <span class="sr-only">Hapus</span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-3xl">
                        <div class="p-6 md:p-8">
                            @php
                                $totalRiwayatKonservasi = $koleksi->conservationActions->count();
                                $riwayatKonservasiTerbaru = $koleksi->conservationActions->take(5);
                            @endphp
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">Riwayat Konservasi</h2>
                                    <p class="mt-1 text-sm text-gray-500">{{ $totalRiwayatKonservasi }} tindakan konservasi tercatat.</p>
                                </div>
                                <a href="{{ route('konservasi.tindakan.index') }}" class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                                    Lihat Semua
                                </a>
                            </div>

                            @if ($riwayatKonservasiTerbaru->isEmpty())
                                <div class="rounded-3xl border border-dashed border-gray-200 py-12 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Belum ada riwayat konservasi untuk koleksi ini.</p>
                                    <p class="mt-1 text-xs text-gray-400">Riwayat konservasi akan muncul setelah tindakan dicatat melalui menu Konservasi.</p>
                                </div>
                            @else
                                <div class="overflow-hidden rounded-3xl border border-gray-200">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-max w-full text-sm divide-y divide-gray-200">
                                            <thead class="bg-slate-50">
                                                <tr>
                                                <th class="px-4 py-3 text-left font-semibold text-slate-600 uppercase tracking-wide">Dibuat</th>
                                                <th class="px-4 py-3 text-left font-semibold text-slate-600 uppercase tracking-wide">Jenis</th>
                                                <th class="px-4 py-3 text-left font-semibold text-slate-600 uppercase tracking-wide">Jadwal</th>
                                                <th class="px-4 py-3 text-left font-semibold text-slate-600 uppercase tracking-wide">Pelaksanaan</th>
                                                <th class="px-4 py-3 text-left font-semibold text-slate-600 uppercase tracking-wide">Pelaksana</th>
                                                <th class="px-4 py-3 text-left font-semibold text-slate-600 uppercase tracking-wide">Status</th>
                                                <th class="px-4 py-3 text-left font-semibold text-slate-600 uppercase tracking-wide">Kondisi Setelah</th>
                                                <th class="px-4 py-3 text-left font-semibold text-slate-600 uppercase tracking-wide">Evaluasi</th>
                                                <th class="px-4 py-3 text-right font-semibold text-slate-600 uppercase tracking-wide">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 bg-white">
                                            @foreach ($riwayatKonservasiTerbaru as $action)
                                                <tr class="hover:bg-slate-50 transition-colors">
                                                    <td class="px-4 py-3 text-slate-800">{{ $action->created_at->format('d M Y') }}</td>
                                                    <td class="px-4 py-3 text-slate-800">{{ $action->jenis_konservasi_label }}</td>
                                                    <td class="px-4 py-3 text-slate-800">{{ optional($action->perawatanKoleksi->jadwal_tanggal)->format('d M Y') ?? '-' }}</td>
                                                    <td class="px-4 py-3 text-slate-800">
                                                        @php
                                                            $implementation = $action->implementations->first();
                                                            $pelaksanaan = $implementation?->tanggal_pelaksanaan;
                                                            $jadwal = $action->perawatanKoleksi?->jadwal_tanggal;
                                                            $latenessLabel = null;
                                                            $latenessClass = 'bg-slate-100 text-slate-700 ring-slate-200';

                                                            if ($pelaksanaan && $jadwal) {
                                                                if ($pelaksanaan->greaterThan($jadwal)) {
                                                                    $latenessLabel = 'Terlambat';
                                                                    $latenessClass = 'bg-rose-100 text-rose-800 ring-rose-200';
                                                                } elseif ($pelaksanaan->equalTo($jadwal)) {
                                                                    $latenessLabel = 'Tepat waktu';
                                                                    $latenessClass = 'bg-emerald-100 text-emerald-800 ring-emerald-200';
                                                                } else {
                                                                    $latenessLabel = 'Lebih awal';
                                                                    $latenessClass = 'bg-indigo-100 text-indigo-800 ring-indigo-200';
                                                                }
                                                            }
                                                        @endphp

                                                        <div class="flex flex-wrap items-center gap-2">
                                                            <span>{{ optional($pelaksanaan)->format('d M Y') ?? '-' }}</span>
                                                            @if ($latenessLabel)
                                                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $latenessClass }}">
                                                                    {{ $latenessLabel }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3 text-slate-800">{{ $implementation?->petugas ?? '-' }}</td>
                                                    <td class="px-4 py-3">
                                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $action->getStatusBadgeClassAttribute() }}">
                                                            {{ $action->status_label }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-slate-800">{{ $action->result->kondisi_setelah ?? '-' }}</td>
                                                    <td class="px-4 py-3">
                                                        @if ($action->result)
                                                            @php
                                                                $evaluation = \App\Models\ConservationResult::EVALUATION_OPTIONS[$action->result->evaluasi] ?? $action->result->evaluasi;
                                                                $badgeClass = match ($action->result->evaluasi) {
                                                                    'berhasil' => 'bg-emerald-100 text-emerald-800',
                                                                    'sebagian_berhasil' => 'bg-amber-100 text-amber-800',
                                                                    'perlu_tindak_lanjut' => 'bg-rose-100 text-rose-800',
                                                                    default => 'bg-gray-100 text-gray-700',
                                                                };
                                                            @endphp
                                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                                                {{ $evaluation }}
                                                            </span>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-right">
                                                        <a href="{{ route('konservasi.tindakan.show', $action) }}" class="inline-flex items-center rounded-lg bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                                                            Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>