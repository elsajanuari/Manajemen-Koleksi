<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Pemeriksaan Kondisi & Proses Pengembalian</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Isi checklist kondisi koleksi saat dikembalikan, lalu generate dokumen untuk ditandatangani penyewa.
                </p>
            </div>
            <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}"
               class="inline-flex items-center rounded-full border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Info penyewaan --}}
            <section class="mb-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                <div class="grid gap-3 md:grid-cols-3 text-sm">
                    <div>
                        <p class="text-slate-500">Koleksi</p>
                        <p class="font-semibold text-slate-900">{{ $penyewaan->painting->title }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Penyewa</p>
                        <p class="font-semibold text-slate-900">{{ $penyewaan->contact_name ?? $penyewaan->nama_instansi }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500">Periode Sewa</p>
                        <p class="font-semibold text-slate-900">
                            {{ $penyewaan->start_date->format('d M Y') }} — {{ $penyewaan->end_date->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- Peringatan penting --}}
            <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800">
                <p class="font-semibold">ℹ Alur setelah form ini disubmit:</p>
                <ol class="mt-2 list-decimal list-inside space-y-1 text-blue-700">
                    <li>Sistem men-generate dokumen pengembalian berdasarkan hasil pemeriksaan Anda</li>
                    <li>Penyewa akan mendapat notifikasi untuk mengunduh & menandatangani dokumen</li>
                    <li>Penyewa mengupload dokumen yang sudah ditandatangani</li>
                    <li>Anda mengkonfirmasi penerimaan koleksi → penyewaan selesai</li>
                </ol>
            </div>

            <form action="{{ route('pengelola.penyewaan.handover.process-return', $penyewaan) }}"
                method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                @if($errors->any())
                    <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                {{-- Checklist kondisi akhir --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-1">Checklist Kondisi Akhir Koleksi</h3>
                    <p class="text-sm text-slate-500 mb-5">Periksa koleksi secara fisik dan bandingkan dengan kondisi awal saat diserahkan.</p>

                    {{-- Checklist kondisi saat kembali --}}
                    <p class="text-sm font-semibold text-slate-700 mb-3">Kondisi Saat Dikembalikan</p>
                    <div class="space-y-3">
                        @foreach([
                            ['name' => 'return_checklist_frame_safe',            'label' => 'Frame / bingkai masih dalam kondisi aman'],
                            ['name' => 'return_checklist_no_tears',              'label' => 'Tidak ada sobekan baru pada kanvas / lukisan'],
                            ['name' => 'return_checklist_color_normal',          'label' => 'Warna lukisan masih normal (tidak pudar/bercak)'],
                            ['name' => 'return_checklist_glass_safe',            'label' => 'Kaca pelindung masih aman / tidak retak (jika ada)'],
                            ['name' => 'return_checklist_no_mold',               'label' => 'Tidak ada jamur atau kerusakan biologis baru'],
                            ['name' => 'return_checklist_matches_documentation', 'label' => 'Kondisi keseluruhan sesuai dengan saat diserahkan'],
                        ] as $item)
                            <label class="flex items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5 cursor-pointer hover:bg-slate-100 transition">
                                <input type="checkbox"
                                       name="{{ $item['name'] }}"
                                       value="1"
                                       {{ old($item['name']) ? 'checked' : '' }}
                                       class="mt-0.5 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-slate-700">{{ $item['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </section>

                {{-- Catatan & kerusakan --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-4">Catatan Pemeriksaan</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">
                                Catatan Kondisi Akhir <span class="text-red-500">*</span>
                            </label>
                            <p class="mt-0.5 text-xs text-slate-500">Deskripsikan kondisi koleksi secara lengkap saat dikembalikan.</p>
                            <textarea name="return_condition_notes" rows="4" required
                                class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300"
                                placeholder="Contoh: Koleksi dikembalikan dalam kondisi baik. Frame sedikit tergores di bagian kiri bawah namun tidak signifikan...">{{ old('return_condition_notes') }}</textarea>
                            @error('return_condition_notes')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kerusakan --}}
                        <div class="rounded-2xl border border-red-100 bg-red-50 p-4 space-y-4">
                            <p class="text-sm font-semibold text-red-700">Kerusakan (isi jika ada)</p>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Deskripsi Kerusakan</label>
                                <textarea name="damage_notes" rows="3"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-200"
                                    placeholder="Deskripsikan kerusakan yang ditemukan secara detail...">{{ old('damage_notes') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">
                                    Biaya Kerusakan (Rp)
                                    <span class="text-slate-400 font-normal">— isi 0 jika tidak ada biaya</span>
                                </label>
                                <input type="number" name="damage_cost" min="0"
                                    value="{{ old('damage_cost', 0) }}"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-200"
                                    placeholder="0">
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Apakah ada kerusakan? --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-4">
                        Hasil Pemeriksaan Kerusakan <span class="text-red-500">*</span>
                    </h3>
                    @error('has_damage')
                        <p class="mb-3 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-white p-5 cursor-pointer has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-400 transition">
                            <input type="radio" name="has_damage" value="0" required
                                {{ old('has_damage') === '0' ? 'checked' : '' }}
                                class="mt-0.5 text-emerald-600"
                                onchange="document.getElementById('damage-detail').classList.add('hidden')">
                            <div>
                                <p class="text-sm font-semibold text-emerald-900">✓ Tidak Ada Kerusakan</p>
                                <p class="mt-1 text-xs text-slate-500">Deposit akan dikembalikan penuh kepada penyewa.</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 rounded-2xl border border-red-200 bg-white p-5 cursor-pointer has-[:checked]:bg-red-50 has-[:checked]:border-red-400 transition">
                            <input type="radio" name="has_damage" value="1"
                                {{ old('has_damage') === '1' ? 'checked' : '' }}
                                class="mt-0.5 text-red-600"
                                onchange="document.getElementById('damage-detail').classList.remove('hidden')">
                            <div>
                                <p class="text-sm font-semibold text-red-900">✗ Ditemukan Kerusakan</p>
                                <p class="mt-1 text-xs text-slate-500">Isi detail kerusakan di bawah untuk proses deposit.</p>
                            </div>
                        </label>
                    </div>

                    {{-- Detail kerusakan (tampil jika ada kerusakan) --}}
                    <div id="damage-detail" class="{{ old('has_damage') === '1' ? '' : 'hidden' }} mt-4 rounded-2xl border border-red-200 bg-red-50 p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">
                                Jenis Kerusakan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="damage_type"
                                value="{{ old('damage_type') }}"
                                placeholder="Contoh: Kerusakan kaca pelindung, Sobekan pada kanvas"
                                class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                            @error('damage_type')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">
                                Tingkat Kerusakan <span class="text-red-500">*</span>
                            </label>
                            <select name="damage_level"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                                <option value="">-- Pilih --</option>
                                <option value="ringan" {{ old('damage_level') === 'ringan' ? 'selected' : '' }}>Ringan</option>
                                <option value="sedang" {{ old('damage_level') === 'sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="berat"  {{ old('damage_level') === 'berat'  ? 'selected' : '' }}>Berat</option>
                            </select>
                            @error('damage_level')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                {{-- Dokumentasi foto --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-base font-semibold text-slate-900 mb-1">Dokumentasi Foto</h3>
                    <p class="text-sm text-slate-500 mb-4">Upload foto kondisi koleksi saat dikembalikan sebagai bukti pemeriksaan.</p>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">
                            Foto Kondisi Koleksi
                            <span class="text-slate-400 font-normal">(opsional, JPG/PNG/PDF, maks 10MB)</span>
                        </label>
                        <input type="file" name="return_condition_photo" accept=".jpg,.jpeg,.png,.pdf"
                            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm file:mr-4 file:rounded-full file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                    </div>
                </section>

                {{-- Tombol submit --}}
                <div class="flex gap-3">
                    <button type="submit"
                        onclick="return confirm('Simpan hasil pemeriksaan dan generate dokumen pengembalian untuk ditandatangani penyewa?')"
                        class="inline-flex items-center rounded-2xl bg-orange-600 px-6 py-3 text-sm font-semibold text-white hover:bg-orange-700 transition">
                        Simpan & Generate Dokumen Pengembalian →
                    </button>
                    <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}"
                       class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>