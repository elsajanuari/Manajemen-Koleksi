<x-app-layout>
    <div class="py-12">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <x-conservation-workflow-nav :action="$action" step="pelaksanaan" variant="actions" />
            <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Catatan Pelaksanaan</h3>

                @forelse ($action->implementations as $item)
                    <div class="mb-4 rounded-2xl border border-gray-200 p-4 bg-slate-50">
                        <div class="flex items-center justify-between gap-4 mb-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $item->tanggal_pelaksanaan->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">Petugas: {{ $item->petugas ?? '-' }}</p>
                                <p class="text-xs text-gray-500">Durasi: {{ $item->durasi ?? '-' }}</p>
                            </div>
                            @if ($item->foto_proses)
                                <span class="text-xs text-gray-500">Foto tersedia</span>
                            @endif
                        </div>
                        <div class="space-y-3 text-sm text-gray-700">
                            <div>
                                <p class="font-semibold">Catatan Pelaksanaan</p>
                                <p class="whitespace-pre-line">{{ $item->catatan_pelaksanaan }}</p>
                            </div>
                            @if ($item->foto_proses)
                                <div>
                                    <p class="font-semibold">Foto Sebelum/Saat Proses Konservasi</p>
                                    <img src="{{ $item->foto_proses_url }}" alt="Foto proses" class="mt-2 w-full max-w-sm rounded-lg border border-gray-200" />
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold">Catatan Perubahan dari Rencana</p>
                                <p>{{ $item->catatan_perubahan ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500">
                        Belum ada catatan pelaksanaan konservasi.
                    </div>
                @endforelse
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                @php $implementation = $action->implementations->first(); @endphp
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    {{ $implementation ? 'Edit Catatan Pelaksanaan' : 'Tambah Catatan Pelaksanaan' }}
                </h3>
                @if (! $implementation)
                    <form method="POST" action="{{ route('konservasi.tindakan.pelaksanaan.store', $action) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <x-conservation-field-label required>Tanggal Pelaksanaan</x-conservation-field-label>
                            <input type="date" name="tanggal_pelaksanaan" value="{{ old('tanggal_pelaksanaan') }}" class="mt-2 w-full rounded-lg border-gray-300 text-sm" required />
                            <x-input-error :messages="$errors->get('tanggal_pelaksanaan')" class="mt-2" />
                        </div>
                        <div>
                            <x-conservation-field-label required>Nama Petugas Pelaksana</x-conservation-field-label>
                            <input
                                type="text"
                                name="petugas"
                                value="{{ old('petugas', Auth::user()->name ?? '') }}"
                                class="mt-2 w-full rounded-lg border-gray-300 text-sm"
                                placeholder="Nama petugas yang melakukan konservasi"
                                required
                            />
                            <x-input-error :messages="$errors->get('petugas')" class="mt-2" />
                        </div>
                        <div>
                            <x-conservation-field-label optional>Durasi</x-conservation-field-label>
                            <input name="durasi" value="{{ old('durasi') }}" class="mt-2 w-full rounded-lg border-gray-300 text-sm" placeholder="Contoh: 2 jam" />
                        </div>
                        <div>
                            <x-conservation-field-label required>Catatan Pelaksanaan</x-conservation-field-label>
                            <textarea name="catatan_pelaksanaan" rows="6" class="mt-2 w-full rounded-lg border-gray-300 text-sm" placeholder="Jelaskan langkah-langkah yang dilakukan, bahan yang digunakan, dan hal penting lainnya." required>{{ old('catatan_pelaksanaan') }}</textarea>
                            <x-input-error :messages="$errors->get('catatan_pelaksanaan')" class="mt-2" />
                        </div>
                        <div>
                            <x-conservation-field-label required>Foto Sebelum/Saat Proses Konservasi</x-conservation-field-label>
                            <input type="file" name="foto_proses" class="mt-2 w-full text-sm" accept="image/*" required />
                            <x-input-error :messages="$errors->get('foto_proses')" class="mt-2" />
                        </div>
                        <div>
                            <x-conservation-field-label optional>Catatan Perubahan dari Rencana</x-conservation-field-label>
                            <textarea name="catatan_perubahan" rows="3" class="mt-2 w-full rounded-lg border-gray-300 text-sm">{{ old('catatan_perubahan') }}</textarea>
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <x-back-link :href="route('konservasi.tindakan.show', $action)" label="Batal" class="text-gray-600" />
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-5 py-2 text-sm font-semibold text-white hover:bg-amber-700 transition">
                                Simpan Pelaksanaan
                            </button>
                        </div>
                    </form>
                @else
                    <form method="POST" action="{{ route('konservasi.tindakan.pelaksanaan.update', $action) }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-conservation-field-label required>Tanggal Pelaksanaan</x-conservation-field-label>
                            <input type="date" name="tanggal_pelaksanaan" value="{{ old('tanggal_pelaksanaan', $implementation->tanggal_pelaksanaan?->toDateString()) }}" class="mt-2 w-full rounded-lg border-gray-300 text-sm" required />
                            <x-input-error :messages="$errors->get('tanggal_pelaksanaan')" class="mt-2" />
                        </div>
                        <div>
                            <x-conservation-field-label required>Nama Petugas Pelaksana</x-conservation-field-label>
                            <input
                                type="text"
                                name="petugas"
                                value="{{ old('petugas', $implementation->petugas) }}"
                                class="mt-2 w-full rounded-lg border-gray-300 text-sm"
                                placeholder="Nama petugas yang melakukan konservasi"
                                required
                            />
                            <x-input-error :messages="$errors->get('petugas')" class="mt-2" />
                        </div>
                        <div>
                            <x-conservation-field-label optional>Durasi</x-conservation-field-label>
                            <input name="durasi" value="{{ old('durasi', $implementation->durasi) }}" class="mt-2 w-full rounded-lg border-gray-300 text-sm" placeholder="Contoh: 2 jam" />
                        </div>
                        <div>
                            <x-conservation-field-label required>Catatan Pelaksanaan</x-conservation-field-label>
                            <textarea name="catatan_pelaksanaan" rows="6" class="mt-2 w-full rounded-lg border-gray-300 text-sm" placeholder="Jelaskan langkah-langkah yang dilakukan, bahan yang digunakan, dan hal penting lainnya." required>{{ old('catatan_pelaksanaan', $implementation->catatan_pelaksanaan) }}</textarea>
                            <x-input-error :messages="$errors->get('catatan_pelaksanaan')" class="mt-2" />
                        </div>
                        <div>
                            <x-conservation-field-label :required="! $implementation->foto_proses" :optional="$implementation->foto_proses">Foto Sebelum/Saat Proses Konservasi</x-conservation-field-label>
                            <input type="file" name="foto_proses" class="mt-2 w-full text-sm" accept="image/*" @if(! $implementation->foto_proses) required @endif />
                            {{-- Foto saat ini ditampilkan di kolom Catatan Pelaksanaan, tidak perlu tautan di formulir edit --}}
                            <x-input-error :messages="$errors->get('foto_proses')" class="mt-2" />
                        </div>
                        <div>
                            <x-conservation-field-label optional>Catatan Perubahan dari Rencana</x-conservation-field-label>
                            <textarea name="catatan_perubahan" rows="3" class="mt-2 w-full rounded-lg border-gray-300 text-sm">{{ old('catatan_perubahan', $implementation->catatan_perubahan) }}</textarea>
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <x-back-link :href="route('konservasi.tindakan.show', $action)" label="Batal" class="text-gray-600" />
                            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-amber-600 px-5 py-2 text-sm font-semibold text-white hover:bg-amber-700 transition">
                                Perbarui Pelaksanaan
                            </button>
                        </div>
                    </form>
                @endif
            </div>
            </div>
        </div>
    </div>
</x-app-layout>
