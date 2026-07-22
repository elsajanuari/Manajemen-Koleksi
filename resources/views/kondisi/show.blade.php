<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pemeriksaan Kondisi</h2>
            <a href="{{ route('koleksi.show', $koleksi) }}" class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900 shrink-0">
                Kembali ke Detail Koleksi
            </a>
        </div>
    </x-slot>

    @php
        $comparison = $kondisi->getConditionComparison();
        $previousInspection = $kondisi->getPreviousInspection();
        $suhuDisplay = $kondisi->suhu !== null
            ? rtrim(rtrim(number_format($kondisi->suhu, 2, '.', ''), '0'), '.') . '°C'
            : null;
    @endphp

    <div class="py-8">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
                <div class="p-5 md:p-6">

                    <div class="mb-5 rounded-xl border border-gray-200 bg-gradient-to-br from-slate-50 via-white to-indigo-50/30 p-4 md:p-5">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold uppercase tracking-wide text-indigo-600">Pemeriksaan Kondisi Koleksi</p>
                                <h1 class="mt-1 text-xl sm:text-2xl font-bold text-gray-900 leading-tight">
                                    <a href="{{ route('koleksi.show', $koleksi) }}" class="hover:text-indigo-700 transition-colors" title="Lihat detail koleksi">
                                        {{ $koleksi->nama }}
                                    </a>
                                </h1>
                                <div class="mt-3 flex flex-wrap items-center gap-2 sm:gap-3">
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-sm font-semibold text-slate-800 ring-1 ring-inset ring-slate-200 capitalize">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        {{ $koleksi->kategori }}
                                    </span>
                                    <span class="hidden sm:inline text-gray-300" aria-hidden="true">|</span>
                                    <span class="inline-flex items-center gap-1.5 text-sm text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="font-medium">{{ $kondisi->tanggal_periksa->format('d M Y') }}</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 text-sm text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>{{ $kondisi->pemeriksa }}</span>
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 shrink-0">
                                <a href="{{ route('koleksi.kondisi.edit', [$koleksi, $kondisi]) }}"
                                   class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('koleksi.kondisi.destroy', [$koleksi, $kondisi]) }}" class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus catatan pemeriksaan ini? Foto yang terkait juga akan dihapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700">
                                        Hapus
                                    </button>
                                </form>
                                <a href="{{ route('koleksi.show', $koleksi) }}"
                                   class="inline-flex items-center gap-1.5 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Perbandingan kondisi --}}
                    @if ($comparison['status_perubahan'])
                        <div class="mb-4 rounded-lg border border-blue-100 bg-blue-50 p-4">
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-blue-900 mb-3">Perbandingan dengan Pemeriksaan Sebelumnya</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="rounded-md border border-blue-200 bg-white p-3">
                                    <p class="text-[11px] font-medium uppercase text-blue-700">Sebelumnya</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $comparison['label_previous'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $comparison['tanggal_sebelumnya'] }}</p>
                                </div>
                                <div class="rounded-md border border-blue-200 bg-white p-3 flex items-center justify-center">
                                    <div class="text-center">
                                        <p class="text-lg font-semibold text-blue-600 leading-none">→</p>
                                        <p class="mt-1 text-[11px] font-semibold
                                            @if ($comparison['status_perubahan'] === 'meningkat') text-green-700
                                            @elseif ($comparison['status_perubahan'] === 'menurun') text-red-700
                                            @else text-amber-700 @endif">
                                            {{ $comparison['label_status'] }}
                                        </p>
                                    </div>
                                </div>
                                <div class="rounded-md border border-blue-200 bg-white p-3">
                                    <p class="text-[11px] font-medium uppercase text-blue-700">Saat Ini</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $comparison['label_current'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $kondisi->tanggal_periksa->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-4 rounded-lg border border-amber-100 bg-amber-50 px-4 py-3">
                            <p class="text-sm text-amber-800">
                                <span class="font-semibold">Pemeriksaan pertama</span> — belum ada data pemeriksaan sebelumnya untuk dibandingkan.
                            </p>
                        </div>
                    @endif

                    {{-- Grid utama: ringkasan + lingkungan + detail (setara Bootstrap row/col-12) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 items-stretch">

                        {{-- Ringkasan Pemeriksaan --}}
                        <div class="flex h-full">
                            <div class="rounded-lg border border-gray-200 bg-slate-50 p-4 flex flex-col w-full">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-600 mb-3">Ringkasan Pemeriksaan</h3>
                                <div class="flex-1 space-y-4">
                                    <div class="space-y-3">
                                        <div>
                                            <p class="text-[11px] font-medium uppercase text-gray-500">Tanggal</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $kondisi->tanggal_periksa->format('d M Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-medium uppercase text-gray-500">Pemeriksa</p>
                                            <p class="text-sm text-gray-900">{{ $kondisi->pemeriksa }}</p>
                                        </div>
                                    </div>

                                    @if ($kondisi->rekomendasi_tindak_lanjut)
                                        <div class="space-y-3">
                                            <p class="text-[11px] font-medium uppercase text-gray-500 mb-2">Rekomendasi</p>
                                            <div class="flex flex-wrap items-center gap-3">
                                                <span class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-800 ring-1 ring-inset ring-slate-200">
                                                    {{ $kondisi->label_rekomendasi }}
                                                </span>

                                                @if ($kondisi->canBuatJadwal())
                                                    <a href="{{ route('jadwal-konservasi.create', [
                                                        'koleksi_id' => $koleksi->id,
                                                        'jenis_perawatan' => $kondisi->getJenisPerawatanDariRekomendasi(),
                                                        'kondisi_koleksi_id' => $kondisi->id,
                                                    ]) }}"
                                                       class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        Buat Jadwal
                                                    </a>
                                                @elseif ($kondisi->hasRekomendasiUntukJadwal())
                                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $kondisi->status_rekomendasi_badge_class }}">
                                                        {{ $kondisi->label_status_rekomendasi }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Ringkasan Lingkungan --}}
                        <div class="flex h-full">
                            <div class="rounded-lg border border-gray-200 bg-white p-4 flex flex-col w-full">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-600 mb-3">Ringkasan Lingkungan</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-[11px] font-medium uppercase text-gray-500">Suhu</p>
                                        <p class="mt-0.5 text-sm font-medium text-gray-900">{{ $suhuDisplay ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-medium uppercase text-gray-500">Kelembapan</p>
                                        <p class="mt-0.5 text-sm font-medium text-gray-900">{{ $kondisi->kelembapan !== null ? $kondisi->kelembapan . '%' : '—' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-medium uppercase text-gray-500">Pencahayaan</p>
                                        <p class="mt-0.5 text-sm font-medium text-gray-900">{{ $kondisi->pencahayaan_label ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-medium uppercase text-gray-500">Kebersihan</p>
                                        <p class="mt-0.5 text-sm font-medium text-gray-900 capitalize">{{ $kondisi->kebersihan_lingkungan ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Pemeriksaan --}}
                        <div class="flex h-full">
                            <div class="rounded-lg border border-gray-200 bg-white p-4 flex flex-col w-full">
                                <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-600 mb-3">Detail Pemeriksaan</h3>
                                <div class="flex-1 space-y-4">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[11px] font-medium uppercase text-gray-500">Jenis Kerusakan</p>
                                            <p class="mt-1 text-sm text-gray-900">{{ $kondisi->jenis_kerusakan ?: '—' }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-medium uppercase text-gray-500">Catatan Pemeriksaan</p>
                                        <p class="mt-1 text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $kondisi->catatan ?: '—' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($kondisi->jadwalRekomendasi->isNotEmpty())
                        @php
                            $totalJadwalTindakLanjut = $kondisi->jadwalRekomendasi->count();
                            $jadwalTindakLanjutTerbaru = $kondisi->jadwalRekomendasi->take(5);
                        @endphp
                        <div class="mt-5 rounded-lg border border-indigo-100 bg-indigo-50/40 p-4">
                            <div class="mb-3 flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900">Jadwal Tindak Lanjut</h3>
                                    <span class="text-xs font-medium text-gray-500">{{ $totalJadwalTindakLanjut }} jadwal</span>
                                </div>
                                <a href="{{ route('jadwal-konservasi.index') }}"
                                   class="shrink-0 inline-flex items-center rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                                    Lihat Semua
                                </a>
                            </div>

                            <div class="space-y-2">
                                @foreach ($jadwalTindakLanjutTerbaru as $jadwal)
                                    @php
                                        $jadwalRowClass = match ($jadwal->status) {
                                            'terjadwal'  => 'border-blue-200 bg-blue-50 border-l-4 border-l-blue-500',
                                            'selesai'    => 'border-green-200 bg-green-50 border-l-4 border-l-green-500',
                                            'dibatalkan' => 'border-red-200 bg-red-50 border-l-4 border-l-red-400',
                                            default      => 'border-gray-200 bg-white border-l-4 border-l-gray-300',
                                        };
                                    @endphp
                                    <div class="flex flex-col gap-3 rounded-lg border px-4 py-3 sm:flex-row sm:items-center sm:justify-between {{ $jadwalRowClass }}">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="text-sm font-semibold text-gray-900">{{ $jadwal->label_jenis }}</span>
                                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $jadwal->badge_class }}">
                                                    {{ $jadwal->label_status }}
                                                </span>
                                                @if ($jadwal->jadwal_indikator_waktu)
                                                    <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[11px] font-medium ring-1 ring-inset {{ $jadwal->jadwal_indikator_badge_class }}">
                                                        {{ $jadwal->jadwal_indikator_waktu }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">
                                                {{ $jadwal->jadwal_tanggal->translatedFormat('d M Y') }}
                                                · {{ $jadwal->penanggung_jawab }}
                                                · {{ $jadwal->label_frekuensi }}
                                            </p>
                                        </div>

                                        <div class="flex shrink-0 flex-wrap items-center gap-2">
                                            <a href="{{ route('jadwal-konservasi.show', $jadwal) }}"
                                               class="inline-flex w-28 items-center justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700 transition">
                                                Lihat Jadwal
                                            </a>
                                            @include('perawatan._conservation_action', ['perawatan' => $jadwal, 'compact' => true, 'fixedWidth' => true])
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Dokumentasi foto --}}
                    @if ($previousInspection?->foto_kondisi_saat_ini_url || $kondisi->foto_kondisi_saat_ini_url || $kondisi->foto_kerusakan_url)
                        <div class="mt-5 pt-5 border-t border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Dokumentasi Foto</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" x-data="{ modalOpen: false, modalSrc: '', modalTitle: '' }">
                                @if ($previousInspection?->foto_kondisi_saat_ini_url)
                                    <div class="rounded-lg border border-blue-200 bg-blue-50 overflow-hidden relative group">
                                        <img src="{{ $previousInspection->foto_kondisi_saat_ini_url }}" alt="Foto pemeriksaan sebelumnya" class="w-full h-40 object-cover" />
                                        <button @click="modalOpen = true; modalSrc = '{{ $previousInspection->foto_kondisi_saat_ini_url }}'; modalTitle = 'Foto Pemeriksaan Sebelumnya - {{ $previousInspection->tanggal_periksa->format('d M Y') }}'"
                                                class="absolute inset-0 opacity-0 group-hover:opacity-100 flex items-center justify-center bg-black/40 transition-opacity duration-200"
                                                title="Klik untuk memperbesar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                                            </svg>
                                        </button>
                                        <div class="px-3 py-2 bg-white border-t border-blue-200">
                                            <p class="text-xs font-semibold text-blue-900">Foto Pemeriksaan Sebelumnya</p>
                                            <p class="text-[11px] text-blue-700">{{ $previousInspection->tanggal_periksa->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if ($kondisi->foto_kondisi_saat_ini_url)
                                    <div class="rounded-lg border border-gray-200 overflow-hidden relative group">
                                        <img src="{{ $kondisi->foto_kondisi_saat_ini_url }}" alt="Foto kondisi saat ini" class="w-full h-40 object-cover" />
                                        <button @click="modalOpen = true; modalSrc = '{{ $kondisi->foto_kondisi_saat_ini_url }}'; modalTitle = 'Foto Kondisi Saat Ini'"
                                                class="absolute inset-0 opacity-0 group-hover:opacity-100 flex items-center justify-center bg-black/40 transition-opacity duration-200"
                                                title="Klik untuk memperbesar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                                            </svg>
                                        </button>
                                        <div class="px-3 py-2 bg-gray-50 border-t border-gray-200">
                                            <p class="text-xs font-semibold text-gray-900">Foto Kondisi Saat Ini</p>
                                        </div>
                                    </div>
                                @endif
                                @if ($kondisi->foto_kerusakan_url)
                                    <div class="rounded-lg border border-red-200 bg-red-50 overflow-hidden relative group">
                                        <img src="{{ $kondisi->foto_kerusakan_url }}" alt="Foto kerusakan" class="w-full h-40 object-cover" />
                                        <button @click="modalOpen = true; modalSrc = '{{ $kondisi->foto_kerusakan_url }}'; modalTitle = 'Foto Detail Kerusakan'"
                                                class="absolute inset-0 opacity-0 group-hover:opacity-100 flex items-center justify-center bg-black/40 transition-opacity duration-200"
                                                title="Klik untuk memperbesar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
                                            </svg>
                                        </button>
                                        <div class="px-3 py-2 bg-white border-t border-red-200">
                                            <p class="text-xs font-semibold text-red-900">Foto Detail Kerusakan</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Modal Fullscreen -->
                                <div x-show="modalOpen" @click.self="modalOpen = false" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4">
                                    <div class="relative max-w-4xl w-full max-h-[90vh]">
                                        <button @click="modalOpen = false"
                                                class="absolute -top-8 right-0 z-60 text-white hover:text-gray-300 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <div class="bg-white rounded-lg overflow-hidden">
                                            <img :src="modalSrc" :alt="modalTitle" class="w-full h-auto max-h-[80vh] object-contain" />
                                            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                                                <p class="text-sm font-semibold text-gray-900" x-text="modalTitle"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
