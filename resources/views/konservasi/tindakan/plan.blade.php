<x-app-layout>
    <div class="py-12">
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <x-conservation-workflow-nav :action="$action" step="rencana" variant="actions" />
            <div class="grid gap-6 lg:grid-cols-[1fr_1.4fr]">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Rencana</h3>
                    @if ($action->plan)
                        <dl class="grid gap-4">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Jenis Tindakan</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $action->plan->jenis_tindakan }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Deskripsi Tindakan</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $action->plan->deskripsi_tindakan }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Bahan / Material</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $action->plan->bahan_material ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Target Penyelesaian</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ optional($action->plan->target_penyelesaian)->format('d M Y') ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 uppercase tracking-wide">Catatan</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $action->plan->catatan ?: '-' }}</dd>
                            </div>
                        </dl>
                    @else
                        <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-6 text-sm text-gray-600">
                            Belum ada rencana konservasi yang dibuat. Form di samping dapat digunakan untuk menambahkan rencana.
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $action->plan ? 'Edit Rencana Konservasi' : 'Tambah Rencana Konservasi' }}</h3>
                    <form method="POST" action="{{ route('konservasi.tindakan.plan.store', $action) }}">
                        @csrf
                        <div class="grid gap-6">
                            <div>
                                <x-conservation-field-label>Jenis Konservasi</x-conservation-field-label>
                                <input type="text" readonly value="{{ $action->jenis_konservasi_label }}" class="mt-2 w-full rounded-lg border-gray-300 bg-slate-100 text-sm text-gray-700" />
                            </div>
                            <div>
                                <x-conservation-field-label required>Jenis Tindakan</x-conservation-field-label>
                                <input name="jenis_tindakan" value="{{ old('jenis_tindakan', optional($action->plan)->jenis_tindakan ?? '') }}" class="mt-2 w-full rounded-lg border-gray-300 text-sm" required />
                                <x-input-error :messages="$errors->get('jenis_tindakan')" class="mt-2" />
                            </div>
                            <div>
                                <x-conservation-field-label required>Deskripsi Tindakan</x-conservation-field-label>
                                <textarea name="deskripsi_tindakan" rows="4" class="mt-2 w-full rounded-lg border-gray-300 text-sm" required>{{ old('deskripsi_tindakan', optional($action->plan)->deskripsi_tindakan ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('deskripsi_tindakan')" class="mt-2" />
                            </div>
                            <div>
                                <x-conservation-field-label optional>Bahan / Material yang Direncanakan</x-conservation-field-label>
                                <textarea name="bahan_material" rows="3" class="mt-2 w-full rounded-lg border-gray-300 text-sm">{{ old('bahan_material', optional($action->plan)->bahan_material ?? '') }}</textarea>
                                <x-input-error :messages="$errors->get('bahan_material')" class="mt-2" />
                            </div>
                            <div class="grid gap-6 lg:grid-cols-2">
                                <div>
                                    <x-conservation-field-label required>Jadwal Target Penyelesaian</x-conservation-field-label>
                                    <input type="date" name="target_penyelesaian" value="{{ old('target_penyelesaian', optional($action->plan)->target_penyelesaian?->toDateString()) }}" class="mt-2 w-full rounded-lg border-gray-300 text-sm" required />
                                    <x-input-error :messages="$errors->get('target_penyelesaian')" class="mt-2" />
                                </div>
                                <div>
                                    <x-conservation-field-label optional>Catatan</x-conservation-field-label>
                                    <textarea name="catatan" rows="3" class="mt-2 w-full rounded-lg border-gray-300 text-sm">{{ old('catatan', optional($action->plan)->catatan ?? '') }}</textarea>
                                    <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-3">
                                <x-back-link :href="route('konservasi.tindakan.show', $action)" label="Batal" class="text-gray-600" />
                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                                    {{ $action->plan ? 'Perbarui Rencana' : 'Simpan Rencana' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
