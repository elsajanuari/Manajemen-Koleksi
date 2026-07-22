<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Upload Dokumen Serah Terima</h2>
                <p class="mt-2 text-sm text-slate-500">Periksa kondisi koleksi, isi checklist, dan upload dokumen yang sudah ditandatangani.</p>
            </div>
            <a href="{{ route('penyewaan.requests.handover.show', $penyewaan) }}"
               class="inline-flex items-center rounded-full border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('penyewaan.requests.handover.upload', $penyewaan) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                @if($errors->any())
                    <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Langkah 1: Download dokumen --}}
                <section class="rounded-3xl border border-blue-200 bg-blue-50 p-6">
                    <h3 class="text-sm font-semibold text-blue-900">Langkah 1 — Unduh Dokumen Serah Terima</h3>
                    <p class="mt-2 text-sm text-blue-700">Unduh dokumen di bawah ini, cetak atau buka secara digital, lalu tandatangani.</p>
                    <a href="{{ route('penyewaan.requests.handover.download', $penyewaan) }}"
                       class="mt-4 inline-flex items-center rounded-full border border-blue-300 bg-white px-5 py-2 text-sm font-semibold text-blue-800 hover:bg-blue-50">
                        Download Dokumen Serah Terima
                    </a>
                </section>

                {{-- Checklist kondisi koleksi --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-4">Langkah 2 — Checklist Kondisi Koleksi</h3>
                    <p class="text-sm text-slate-600 mb-4">Centang kondisi yang sesuai dengan keadaan koleksi saat Anda terima.</p>
                    <div class="space-y-3">
                        @foreach([
                            ['name' => 'checklist_frame_safe', 'label' => 'Frame/bingkai dalam kondisi aman dan tidak rusak'],
                            ['name' => 'checklist_no_tears', 'label' => 'Tidak ada sobekan atau kerusakan pada kanvas/lukisan'],
                            ['name' => 'checklist_color_normal', 'label' => 'Warna lukisan normal dan tidak pudar/bernoda'],
                            ['name' => 'checklist_glass_safe', 'label' => 'Kaca pelindung (jika ada) dalam kondisi aman'],
                            ['name' => 'checklist_no_mold', 'label' => 'Tidak ada jamur atau bau tidak sedap pada koleksi'],
                            ['name' => 'checklist_matches_documentation', 'label' => 'Kondisi koleksi sesuai dengan dokumentasi yang diberikan'],
                        ] as $item)
                            <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 cursor-pointer hover:bg-slate-100">
                                <input type="checkbox"
                                       name="{{ $item['name'] }}"
                                       value="1"
                                       {{ old($item['name']) ? 'checked' : '' }}
                                       class="mt-0.5 h-4 w-4 rounded border-slate-300 text-emerald-600">
                                <span class="text-sm text-slate-700">{{ $item['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </section>

                {{-- Catatan kondisi --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-4">Langkah 3 — Catatan Kondisi</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Catatan Kondisi Awal Koleksi</label>
                            <textarea name="initial_condition_note" rows="3"
                                class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm"
                                placeholder="Deskripsikan kondisi koleksi saat Anda terima...">{{ old('initial_condition_note') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Catatan Tambahan dari Penyewa</label>
                            <textarea name="tenant_notes" rows="3"
                                class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm"
                                placeholder="Catatan atau pertanyaan dari Anda...">{{ old('tenant_notes') }}</textarea>
                        </div>
                    </div>
                </section>

                {{-- Upload foto & dokumen --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-4">Langkah 4 — Upload Dokumentasi</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">
                                Dokumen Serah Terima (Sudah Ditandatangani) <span class="text-red-500">*</span>
                            </label>
                            <p class="mt-1 text-xs text-slate-500">Format: PDF, DOC, DOCX. Maks 10MB.</p>
                            <input type="file" name="tenant_signed_document" required accept=".pdf,.doc,.docx"
                                class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm file:mr-4 file:rounded-full file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                            @error('tenant_signed_document')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">
                                Foto Kondisi Koleksi Saat Diterima <span class="text-slate-400">(opsional)</span>
                            </label>
                            <p class="mt-1 text-xs text-slate-500">Format: JPG, PNG, PDF. Maks 5MB.</p>
                            <input type="file" name="received_condition_photo" accept=".jpg,.jpeg,.png,.pdf"
                                class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm file:mr-4 file:rounded-full file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                        </div>
                    </div>
                </section>

                <div class="flex gap-3">
                    <button type="submit"
                        class="inline-flex items-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-700">
                        Upload & Kirim ke Pengelola
                    </button>
                    <a href="{{ route('penyewaan.requests.handover.show', $penyewaan) }}"
                       class="inline-flex items-center rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>