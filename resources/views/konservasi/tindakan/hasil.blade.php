@php use App\Models\KondisiKoleksi; @endphp
<x-app-layout>
    <div class="py-12">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <x-conservation-workflow-nav :action="$action" step="hasil" variant="actions" />
            <div class="grid gap-6 lg:grid-cols-[1fr_1.4fr]">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Hasil</h3>
                    @if ($action->result)
                        <dl class="grid gap-4">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kondisi Setelah</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $action->result->kondisi_setelah }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Evaluasi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ \App\Models\ConservationResult::EVALUATION_OPTIONS[$action->result->evaluasi] ?? $action->result->evaluasi }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Rekomendasi Penyimpanan</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $action->result->rekomendasi_penyimpanan ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Rekomendasi Penanganan Khusus</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $action->result->rekomendasi_penanganan_khusus ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Catatan Akhir</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $action->result->catatan_akhir ?: '-' }}</dd>
                            </div>
                            @if ($action->result->foto_setelah_url)
                                <div>
                                    <p class="font-semibold">Foto Setelah Konservasi</p>
                                    <img src="{{ $action->result->foto_setelah_url }}" alt="Foto setelah konservasi" class="mt-2 w-full max-w-sm rounded-lg border border-gray-200" />
                                </div>
                            @endif
                        </dl>
                    @else
                        <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-6 text-sm text-gray-600">
                            Belum ada hasil konservasi yang dicatat. Form di samping dapat digunakan untuk menyimpan hasil.
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $action->result ? 'Edit Hasil Konservasi' : 'Tambah Hasil Konservasi' }}</h3>
                    <form method="POST" action="{{ route('konservasi.tindakan.hasil.store', $action) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <x-conservation-field-label required>Kondisi Setelah Konservasi</x-conservation-field-label>
                            <select id="kondisiSetelah" name="kondisi_setelah" class="mt-2 w-full rounded-lg border-gray-300 text-sm" required>
                                <option value="">Pilih kondisi</option>
                                @foreach (KondisiKoleksi::KONDISI_OPTIONS as $value => $label)
                                    <option value="{{ $value }}" {{ old('kondisi_setelah', $action->result->kondisi_setelah ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('kondisi_setelah')" class="mt-2" />

                            <div class="mt-4">
                                <x-conservation-field-label>Evaluasi Hasil</x-conservation-field-label>
                                <input id="evaluasiLabel" type="text" class="mt-2 w-full rounded-lg border-gray-200 bg-gray-100 text-sm text-gray-700" disabled value="{{
                                    match(old('kondisi_setelah', $action->result->kondisi_setelah ?? '') ) {
                                        'baik' => 'Berhasil',
                                        'rusak_ringan' => 'Sebagian Berhasil',
                                        'rusak_berat' => 'Perlu Tindak Lanjut',
                                        default => ''
                                    }
                                }}" />
                                <input id="evaluasiHidden" type="hidden" name="evaluasi" value="{{
                                    match(old('kondisi_setelah', $action->result->kondisi_setelah ?? '') ) {
                                        'baik' => 'berhasil',
                                        'rusak_ringan' => 'sebagian_berhasil',
                                        'rusak_berat' => 'perlu_tindak_lanjut',
                                        default => ''
                                    }
                                }}" />
                            </div>
                        </div>
                    <div>
                        <x-conservation-field-label :required="! $action->result?->foto_setelah" :optional="(bool) $action->result?->foto_setelah">Upload Foto Setelah Konservasi</x-conservation-field-label>
                        <input type="file" name="foto_setelah" class="mt-2 w-full text-sm" accept="image/*" @if (! $action->result?->foto_setelah) required @endif />
                        <x-input-error :messages="$errors->get('foto_setelah')" class="mt-2" />
                    </div>
                    <div>
                        <x-conservation-field-label optional>Rekomendasi Penyimpanan</x-conservation-field-label>
                        <textarea name="rekomendasi_penyimpanan" rows="3" class="mt-2 w-full rounded-lg border-gray-300 text-sm">{{ old('rekomendasi_penyimpanan', $action->result->rekomendasi_penyimpanan ?? '') }}</textarea>
                    </div>
                    <div>
                        <x-conservation-field-label optional>Rekomendasi Penanganan Khusus</x-conservation-field-label>
                        <textarea name="rekomendasi_penanganan_khusus" rows="3" class="mt-2 w-full rounded-lg border-gray-300 text-sm">{{ old('rekomendasi_penanganan_khusus', $action->result->rekomendasi_penanganan_khusus ?? '') }}</textarea>
                    </div>
                    <div>
                        <x-conservation-field-label optional>Catatan Akhir</x-conservation-field-label>
                        <textarea name="catatan_akhir" rows="4" class="mt-2 w-full rounded-lg border-gray-300 text-sm">{{ old('catatan_akhir', $action->result->catatan_akhir ?? '') }}</textarea>
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <x-back-link :href="route('konservasi.tindakan.show', $action)" label="Batal" class="text-gray-600" />
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-green-600 px-5 py-2 text-sm font-semibold text-white hover:bg-green-700 transition">
                            {{ $action->result ? 'Perbarui Hasil' : 'Simpan Hasil' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
window.addEventListener('load', function () {
    const map = {
        'baik': ['Berhasil', 'berhasil'],
        'rusak_ringan': ['Sebagian Berhasil', 'sebagian_berhasil'],
        'rusak_berat': ['Perlu Tindak Lanjut', 'perlu_tindak_lanjut'],
    };

    const select = document.getElementById('kondisiSetelah');
    const label = document.getElementById('evaluasiLabel');
    const hidden = document.getElementById('evaluasiHidden');

    if (!select || !label || !hidden) {
        return;
    }

    function updateEvaluasi() {
        const v = select.value;
        if (map[v]) {
            label.value = map[v][0];
            hidden.value = map[v][1];
        } else {
            label.value = '';
            hidden.value = '';
        }
    }

    select.addEventListener('change', updateEvaluasi);
    select.addEventListener('input', updateEvaluasi);
    updateEvaluasi();
});
</script>
