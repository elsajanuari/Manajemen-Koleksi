@php use App\Models\PerawatanKoleksi; @endphp
<x-app-layout>
    <div class="py-12">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ── HEADER ─────────────────────────────────────────────── --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                <div class="grid gap-4 md:grid-cols-[1fr_auto] md:items-end">
                    <div class="space-y-2">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ __('Jadwal Konservasi') }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            Menampilkan {{ $perawatans->count() }} dari {{ $perawatans->total() }} jadwal.
                            @if($search)
                                Hasil untuk "{{ $search }}".
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center justify-start gap-2 md:justify-end">
                        <a href="{{ route('jadwal-konservasi.create') }}"
                            class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ __('Tambah Jadwal') }}
                        </a>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <form method="GET" action="{{ route('jadwal-konservasi.index') }}" class="flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-48">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari koleksi / PJ</label>
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Nama koleksi atau penanggung jawab..."
                                class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status" class="text-sm rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua</option>
                                <option value="terjadwal"  {{ $statusFilter === 'terjadwal'  ? 'selected' : '' }}>Terjadwal</option>
                                <option value="selesai"    {{ $statusFilter === 'selesai'    ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ $statusFilter === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis</label>
                            <select name="jenis" class="text-sm rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua</option>
                                @foreach (PerawatanKoleksi::JENIS_OPTIONS as $value => $label)
                                    <option value="{{ $value }}" {{ $jenisFilter === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Prioritas</label>
                            <select name="prioritas" class="text-sm rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua</option>
                                @foreach (PerawatanKoleksi::PRIORITAS_OPTIONS as $value => $label)
                                    <option value="{{ $value }}" {{ ($prioritasFilter ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tindakan Konservasi</label>
                            <select name="tindakan" class="text-sm rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua</option>
                                <option value="belum" {{ ($tindakanFilter ?? '') === 'belum' ? 'selected' : '' }}>Belum ditangani</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                Filter
                            </button>
                            <a href="{{ route('jadwal-konservasi.index') }}"
                                class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ── STATISTIK RINGKAS ──────────────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['terjadwal'] }}</p>
                        <p class="text-sm text-gray-500">Terjadwal</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['terlambat'] }}</p>
                        <p class="text-sm text-gray-500">Terlambat</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-amber-100 p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-amber-600">{{ $stats['belum_ditangani'] }}</p>
                        <p class="text-sm text-gray-500">Belum Ditangani</p>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-green-100 p-5 flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['selesai'] }}</p>
                        <p class="text-sm text-gray-500">Selesai</p>
                    </div>
                </div>
            </div>

            {{-- ── FLASH ───────────────────────────────────────────────── --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── TABEL ───────────────────────────────────────────────── --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                <table class="w-full text-sm divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th scope="col" class="px-3 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide whitespace-nowrap">Koleksi</th>
                            <th scope="col" class="px-3 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide whitespace-nowrap">Jenis</th>
                            <th scope="col" class="px-3 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide whitespace-nowrap">Prioritas</th>
                            <th scope="col" class="px-3 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide whitespace-nowrap">Tanggal</th>
                            <th scope="col" class="px-3 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide whitespace-nowrap hidden lg:table-cell">Frekuensi</th>
                            <th scope="col" class="px-3 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide whitespace-nowrap hidden md:table-cell">PJ</th>
                            <th scope="col" class="px-3 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide whitespace-nowrap">Status</th>
                            <th scope="col" class="px-4 py-2.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 bg-white">
                        @forelse ($perawatans as $p)
                            <tr class="hover:bg-gray-50/80 transition {{ $p->is_terlambat ? 'bg-red-50/60' : '' }}">
                                <td class="px-3 py-2.5 max-w-[11rem]">
                                    <div class="font-medium text-gray-900 truncate" title="{{ $p->koleksi->nama }}">{{ $p->koleksi->nama }}</div>
                                    <div class="text-[11px] text-gray-400 truncate">{{ $p->koleksi->nomor_inventaris ?? '-' }}</div>
                                </td>
                                <td class="px-3 py-2.5 text-gray-700 whitespace-nowrap">
                                    <div>{{ $p->label_jenis }}</div>
                                    @if ($p->isAwaitingConservation())
                                        <span class="mt-0.5 inline-flex items-center rounded px-1.5 py-0.5 text-[11px] font-medium ring-1 ring-inset bg-amber-50 text-amber-700 ring-amber-200">
                                            Belum ditangani
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium whitespace-nowrap {{ $p->prioritas_badge_class }}">
                                        {{ $p->label_prioritas }}
                                    </span>
                                </td>
                                <td class="px-3 py-2.5 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $p->jadwal_tanggal->translatedFormat('d M Y') }}</div>
                                    @if ($p->jadwal_indikator_waktu)
                                        <span class="mt-0.5 inline-flex items-center rounded px-1.5 py-0.5 text-[11px] font-medium ring-1 ring-inset {{ $p->jadwal_indikator_badge_class }}">
                                            {{ $p->jadwal_indikator_waktu }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-2.5 text-gray-600 whitespace-nowrap hidden lg:table-cell">{{ $p->label_frekuensi }}</td>
                                <td class="px-3 py-2.5 text-gray-700 max-w-[8rem] hidden md:table-cell">
                                    <span class="block truncate text-xs" title="{{ $p->penanggung_jawab }}">{{ $p->penanggung_jawab }}</span>
                                </td>
                                <td class="px-3 py-2.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium whitespace-nowrap {{ $p->badge_class }}">
                                        {{ $p->label_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right align-middle whitespace-nowrap">
                                    <div class="inline-flex flex-nowrap items-center justify-end gap-1">
                                        <a href="{{ route('jadwal-konservasi.show', $p) }}"
                                            class="text-xs px-2 py-1 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded font-medium transition">
                                            Detail
                                        </a>
                                        @if ($p->status === 'terjadwal')
                                            @if ($p->requiresConservation())
                                                @include('perawatan._conservation_action', ['perawatan' => $p, 'compact' => true])
                                            @elseif ($p->isPemeriksaan())
                                                <a href="{{ route('koleksi.kondisi.create', ['koleksi' => $p->koleksi_id, 'perawatan_id' => $p->id]) }}"
                                                    class="text-xs px-2 py-1 bg-green-100 text-green-700 hover:bg-green-200 rounded font-medium transition">
                                                    Periksa
                                                </a>
                                            @else
                                                <button type="button"
                                                    onclick="openSelesaiModal({{ $p->id }}, '{{ addslashes($p->koleksi->nama) }}', '{{ $p->jenis_perawatan }}')"
                                                    class="text-xs px-2 py-1 bg-green-100 text-green-700 hover:bg-green-200 rounded font-medium transition">
                                                    Selesai
                                                </button>
                                            @endif
                                            <a href="{{ route('jadwal-konservasi.edit', $p) }}"
                                                class="text-xs px-2 py-1 bg-indigo-100 text-indigo-700 hover:bg-indigo-200 rounded font-medium transition">
                                                Edit
                                            </a>
                                            <button type="button"
                                                onclick="openBatalkanModal({{ $p->id }}, '{{ addslashes($p->koleksi->nama) }}')"
                                                class="text-xs px-2 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded font-medium transition">
                                                Batal
                                            </button>
                                        @else
                                            <form action="{{ route('jadwal-konservasi.destroy', $p) }}" method="POST"
                                                  onsubmit="return confirm('Hapus jadwal ini?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-xs px-2 py-1 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded font-medium transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-3 py-10 text-center text-gray-400 text-sm">
                                    Tidak ada jadwal konservasi ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>

                <div class="px-4 py-4 border-t border-gray-100 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-2 text-sm text-gray-700">
                        <label for="per_page_footer" class="font-medium">Jumlah per halaman</label>
                        <form id="per-page-form" method="GET" action="{{ route('jadwal-konservasi.index') }}" class="inline">
                            @foreach (['search', 'status', 'jenis', 'prioritas', 'tindakan', 'per_page'] as $param)
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
                        {{ $perawatans->links('vendor.pagination.simple-tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ MODAL: Catat Penyelesaian ══════════════════════════════════ --}}
    <div id="selesai-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Catat Penyelesaian</h3>
            <p id="selesai-nama" class="text-sm text-gray-500 mb-4"></p>

            <div id="selesai-pemeriksaan-info" class="mb-4 rounded-lg border border-indigo-200 bg-indigo-50 p-3 text-sm text-indigo-900 hidden">
                Pemeriksaan kondisi wajib dicatat saat menyelesaikan jadwal pemeriksaan ulang.
            </div>

            <form id="selesai-form" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_selesai" max="{{ today()->toDateString() }}"
                            value="{{ today()->toDateString() }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Pemeriksa <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="pemeriksa" id="pemeriksa-input-modal"
                            value="{{ Auth::user()->name ?? '' }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Nama petugas"
                            required>
                    </div>
                    <div id="kegiatan-pemeliharaan-wrapper" class="hidden">
                        <label class="block text-sm font-medium text-gray-700">Kegiatan Pemeliharaan yang Dilakukan</label>
                        <p class="text-xs text-gray-500 mb-2">Pilih satu atau lebih kegiatan konservasi preventif yang dikerjakan.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach (PerawatanKoleksi::KEGIATAN_PEMELIHARAAN as $value => $label)
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" name="kegiatan[]" value="{{ $value }}"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Catatan Penyelesaian</label>
                        <textarea name="catatan_penyelesaian" id="catatan-penyelesaian-input" rows="3"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Opsional..."></textarea>
                    </div>

                    <hr class="border-dashed">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide" id="kondisi-label">Catat kondisi koleksi (opsional)</p>
                    <p id="kondisi-note" class="text-xs text-gray-500"></p>

                    <div id="kondisi-field-wrapper">
                        <label class="block text-sm font-medium text-gray-700">
                            Kondisi <span id="kondisi-required" class="text-red-500 hidden">*</span>
                        </label>
                        <select name="kondisi" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" id="kondisi-select">
                            <option value="">Tidak perlu dicatat</option>
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                        </select>
                    </div>
                    <div id="jenis-kerusakan-wrapper">
                        <label class="block text-sm font-medium text-gray-700">Jenis Kerusakan</label>
                        <input type="text" name="jenis_kerusakan" id="jenis-kerusakan-input"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Contoh: retak, pudar">
                    </div>
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kebersihan Lingkungan</label>
                            <select name="kebersihan_lingkungan" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Pilih tingkat kebersihan</option>
                                <option value="baik">Baik</option>
                                <option value="cukup">Cukup</option>
                                <option value="buruk">Buruk</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Suhu (°C)</label>
                            <input type="number" name="suhu" step="0.1" min="-10" max="60"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="22.5">
                        </div>
                    </div>
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kelembapan (%)</label>
                            <input type="number" name="kelembapan" min="0" max="100"
                                class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                placeholder="55">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pencahayaan</label>
                            <select name="pencahayaan" class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Pilih tingkat pencahayaan</option>
                                <option value="rendah">Rendah</option>
                                <option value="sedang">Sedang</option>
                                <option value="tinggi">Tinggi</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3 justify-end">
                        <button type="button" onclick="closeSelesaiModal()"
                            class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Tutup</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700 transition font-medium">Catat Penyelesaian</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openSelesaiModal(id, nama, jenis) {
            document.getElementById('selesai-nama').textContent = nama;
            document.getElementById('selesai-form').action = `/jadwal-konservasi/${id}/selesai`;

            const isPemeriksaan = jenis === 'pemeriksaan_ulang';
            const isPemeliharaan = jenis === 'pemeliharaan';
            const infoBox = document.getElementById('selesai-pemeriksaan-info');
            const kondisiLabel = document.getElementById('kondisi-label');
            const kondisiRequired = document.getElementById('kondisi-required');
            const kondisiSelect = document.getElementById('kondisi-select');
            const kondisiNote = document.getElementById('kondisi-note');
            const kondisiFieldWrapper = document.getElementById('kondisi-field-wrapper');
            const jenisKerusakanWrapper = document.getElementById('jenis-kerusakan-wrapper');
            const jenisKerusakanInput = document.getElementById('jenis-kerusakan-input');
            const kegiatanWrapper = document.getElementById('kegiatan-pemeliharaan-wrapper');
            const catatanInput = document.getElementById('catatan-penyelesaian-input');

            // Reset blok kegiatan pemeliharaan setiap modal dibuka.
            kegiatanWrapper.classList.add('hidden');
            kegiatanWrapper.querySelectorAll('input[type="checkbox"]').forEach(cb => { cb.checked = false; });
            catatanInput.placeholder = 'Opsional...';

            if (isPemeriksaan) {
                infoBox.classList.remove('hidden');
                kondisiLabel.textContent = 'Hasil Pemeriksaan (wajib)';
                kondisiRequired.classList.remove('hidden');
                kondisiSelect.required = true;
                kondisiFieldWrapper.classList.remove('hidden');
                jenisKerusakanWrapper.classList.remove('hidden');
                kondisiNote.textContent = 'Hasil pemeriksaan akan dicatat dalam riwayat pemeriksaan kondisi koleksi.';
            } else if (isPemeliharaan) {
                // Konservasi preventif: pilih kegiatan + pemantauan lingkungan, tanpa field kondisi/kerusakan.
                infoBox.classList.add('hidden');
                kegiatanWrapper.classList.remove('hidden');
                catatanInput.placeholder = 'Detail pekerjaan, mis. membersihkan debu pada bingkai, mengganti silica gel, dll.';
                kondisiLabel.textContent = 'Catat Pemantauan Lingkungan (opsional)';
                kondisiRequired.classList.add('hidden');
                kondisiSelect.required = false;
                kondisiSelect.value = '';
                kondisiFieldWrapper.classList.add('hidden');
                jenisKerusakanWrapper.classList.add('hidden');
                if (jenisKerusakanInput) jenisKerusakanInput.value = '';
                kondisiNote.textContent = 'Catat suhu, kelembapan, pencahayaan, dan kebersihan jika dilakukan. Jika diisi, tersimpan sebagai log pemantauan (kondisi tetap baik).';
            } else {
                infoBox.classList.add('hidden');
                kondisiLabel.textContent = 'Catat kondisi koleksi (opsional)';
                kondisiRequired.classList.add('hidden');
                kondisiSelect.required = false;
                kondisiFieldWrapper.classList.remove('hidden');
                jenisKerusakanWrapper.classList.remove('hidden');
                kondisiNote.textContent = 'Jika diisi, akan membuat entri tambahan ke riwayat pemeriksaan kondisi koleksi.';
            }

            document.getElementById('selesai-modal').classList.remove('hidden');
        }

        function closeSelesaiModal() {
            document.getElementById('selesai-modal').classList.add('hidden');
        }

        document.getElementById('selesai-modal').addEventListener('click', function (e) {
            if (e.target === this) closeSelesaiModal();
        });
    </script>

    {{-- ══ MODAL: Batalkan Jadwal ════════════════════════════════════ --}}
    <div id="batalkan-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-1">Batalkan Jadwal</h3>
            <p id="batalkan-nama" class="text-sm text-gray-500 mb-4"></p>

            <form id="batalkan-form" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alasan Pembatalan <span class="text-red-500">*</span></label>
                        <textarea name="alasan_pembatalan" rows="4" required
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Jelaskan alasan pembatalan jadwal..."></textarea>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button type="button" onclick="closeBatalkanModal()"
                            class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Tutup</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-red-600 rounded-lg hover:bg-red-700 transition font-medium">Batalkan Jadwal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (session('conservation_follow_up_suggestion'))
        @php $followUp = session('conservation_follow_up_suggestion'); @endphp
        <div id="conservation-follow-up-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Buat Jadwal Konservasi Lanjutan?</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Hasil konservasi untuk <span class="font-semibold">{{ $followUp['koleksi_nama'] }}</span>
                    dinilai <span class="font-semibold">Perlu Tindak Lanjut</span>.
                    Buat jadwal penanganan kerusakan lanjutan untuk
                    <span class="font-semibold">{{ \Carbon\Carbon::parse($followUp['jadwal_tanggal'])->format('d M Y') }}</span>?
                </p>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('conservation-follow-up-modal').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Nanti Saja</button>
                    <a href="{{ route('jadwal-konservasi.create', array_filter([
                        'koleksi_id' => $followUp['koleksi_id'],
                        'kondisi_koleksi_id' => $followUp['kondisi_koleksi_id'],
                        'jenis_perawatan' => $followUp['jenis_perawatan'],
                        'frekuensi' => $followUp['frekuensi'],
                        'jadwal_tanggal' => $followUp['jadwal_tanggal'],
                        'penanggung_jawab' => $followUp['penanggung_jawab'] ?? null,
                        'estimasi_durasi_menit' => $followUp['estimasi_durasi_menit'] ?? null,
                        'catatan' => $followUp['catatan'] ?? null,
                    ])) }}"
                        class="px-4 py-2 text-sm text-white bg-amber-600 rounded-lg hover:bg-amber-700 transition font-medium">
                        Buat Jadwal Lanjutan
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if (session('next_schedule_suggestion'))
        @php $suggestion = session('next_schedule_suggestion'); @endphp
        <div id="next-schedule-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Buat Jadwal Berikutnya?</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Jadwal konservasi untuk <span class="font-semibold">{{ $suggestion['koleksi_nama'] }}</span> selesai.
                    Frekuensi <span class="font-semibold">{{ $suggestion['label_frekuensi'] }}</span> —
                    buat jadwal lanjutan untuk
                    <span class="font-semibold">{{ \Carbon\Carbon::parse($suggestion['jadwal_tanggal'])->format('d M Y') }}</span>?
                </p>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('next-schedule-modal').classList.add('hidden')"
                        class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Nanti Saja</button>
                    <a href="{{ route('jadwal-konservasi.create', array_filter([
                        'koleksi_id' => $suggestion['koleksi_id'],
                        'jenis_perawatan' => $suggestion['jenis_perawatan'],
                        'frekuensi' => $suggestion['frekuensi'],
                        'jadwal_tanggal' => $suggestion['jadwal_tanggal'],
                        'penanggung_jawab' => $suggestion['penanggung_jawab'] ?? null,
                        'estimasi_durasi_menit' => $suggestion['estimasi_durasi_menit'] ?? null,
                        'lanjutan_jadwal' => 1,
                    ])) }}"
                        class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition font-medium">
                        Buat Jadwal Lanjutan
                    </a>
                </div>
            </div>
        </div>
    @endif

    <script>
        function openBatalkanModal(id, nama) {
            document.getElementById('batalkan-nama').textContent = nama;
            document.getElementById('batalkan-form').action = `/jadwal-konservasi/${id}/batalkan`;
            document.getElementById('batalkan-modal').classList.remove('hidden');
        }

        function closeBatalkanModal() {
            document.getElementById('batalkan-modal').classList.add('hidden');
        }

        document.getElementById('batalkan-modal')?.addEventListener('click', function (e) {
            if (e.target === this) closeBatalkanModal();
        });
    </script>
</x-app-layout>