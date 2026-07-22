<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-slate-900">Pemeriksaan Akhir Koleksi</h2>
                <p class="mt-1 text-sm text-slate-600">
                    SP-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}
                    &mdash; {{ $penyewaan->painting->title }}
                </p>
            </div>
            <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}"
               class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                ← Kembali ke Serah Terima
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alert --}}
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-800">
                    ✗ {{ session('error') }}
                </div>
            @endif

            {{-- Info Penyewaan --}}
            <section class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Informasi Penyewaan</p>
                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <p class="text-xs text-slate-500">Penyewa</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $penyewaan->contact_name ?? $penyewaan->nama_instansi }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Koleksi</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $penyewaan->painting->title }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Periode Sewa</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $penyewaan->start_date->format('d M Y') }} – {{ $penyewaan->end_date->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Nominal Deposit</p>
                        <p class="mt-1 text-sm font-semibold text-emerald-700">
                            Rp {{ number_format($depositAmount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- Perbandingan kondisi awal vs kondisi dikembalikan --}}
            <section class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 mb-5">Perbandingan Kondisi Koleksi</p>
                <div class="grid gap-4 sm:grid-cols-2">
                    {{-- Kondisi Awal --}}
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                        <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide mb-3">Kondisi Awal (Saat Dipinjam)</p>
                        @if($serahTerima->received_condition_photo_path)
                            <img src="{{ Storage::url($serahTerima->received_condition_photo_path) }}"
                                 alt="Foto kondisi awal"
                                 class="w-full h-40 object-cover rounded-xl mb-3 border border-emerald-200">
                        @else
                            <div class="w-full h-40 rounded-xl bg-emerald-100 border border-emerald-200 flex items-center justify-center mb-3">
                                <p class="text-xs text-emerald-500">Tidak ada foto</p>
                            </div>
                        @endif
                        <div class="space-y-1.5">
                            @foreach([
                                ['label' => 'Frame / bingkai',    'value' => $serahTerima->checklist_frame_safe],
                                ['label' => 'Tidak ada sobekan',  'value' => $serahTerima->checklist_no_tears],
                                ['label' => 'Warna normal',       'value' => $serahTerima->checklist_color_normal],
                                ['label' => 'Kaca pelindung',     'value' => $serahTerima->checklist_glass_safe],
                                ['label' => 'Tidak ada jamur',    'value' => $serahTerima->checklist_no_mold],
                                ['label' => 'Sesuai dokumentasi', 'value' => $serahTerima->checklist_matches_documentation],
                            ] as $item)
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="{{ $item['value'] ? 'text-emerald-600' : 'text-red-400' }} font-bold w-4">
                                        {{ $item['value'] ? '✓' : '✗' }}
                                    </span>
                                    <span class="text-slate-700">{{ $item['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        @if($serahTerima->initial_condition_note)
                            <p class="mt-3 text-xs text-slate-500 bg-white rounded-xl p-2 border border-emerald-200">
                                {{ $serahTerima->initial_condition_note }}
                            </p>
                        @endif
                    </div>

                    {{-- Kondisi Saat Dikembalikan (dari pengelola) --}}
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                        <p class="text-xs font-semibold text-amber-700 uppercase tracking-wide mb-3">Kondisi Saat Dikembalikan (Laporan Pengelola)</p>
                        @if($serahTerima->return_condition_photo_path)
                            <img src="{{ Storage::url($serahTerima->return_condition_photo_path) }}"
                                 alt="Foto kondisi pengembalian"
                                 class="w-full h-40 object-cover rounded-xl mb-3 border border-amber-200">
                        @else
                            <div class="w-full h-40 rounded-xl bg-amber-100 border border-amber-200 flex items-center justify-center mb-3">
                                <p class="text-xs text-amber-500">Tidak ada foto</p>
                            </div>
                        @endif
                        <div class="space-y-1.5">
                            @foreach([
                                ['label' => 'Frame / bingkai',    'value' => $serahTerima->return_checklist_frame_safe],
                                ['label' => 'Tidak ada sobekan',  'value' => $serahTerima->return_checklist_no_tears],
                                ['label' => 'Warna normal',       'value' => $serahTerima->return_checklist_color_normal],
                                ['label' => 'Kaca pelindung',     'value' => $serahTerima->return_checklist_glass_safe],
                                ['label' => 'Tidak ada jamur',    'value' => $serahTerima->return_checklist_no_mold],
                                ['label' => 'Sesuai dokumentasi', 'value' => $serahTerima->return_checklist_matches_documentation],
                            ] as $item)
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="{{ $item['value'] ? 'text-emerald-600' : 'text-red-400' }} font-bold w-4">
                                        {{ $item['value'] ? '✓' : '✗' }}
                                    </span>
                                    <span class="text-slate-700">{{ $item['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                        @if($serahTerima->return_condition_notes)
                            <p class="mt-3 text-xs text-slate-500 bg-white rounded-xl p-2 border border-amber-200">
                                {{ $serahTerima->return_condition_notes }}
                            </p>
                        @endif
                        @if($serahTerima->damage_notes || $serahTerima->damage_cost > 0)
                            <div class="mt-3 rounded-xl bg-red-50 border border-red-200 p-3">
                                <p class="text-xs font-semibold text-red-700">Kerusakan Dilaporkan</p>
                                @if($serahTerima->damage_notes)
                                    <p class="mt-1 text-xs text-slate-700">{{ $serahTerima->damage_notes }}</p>
                                @endif
                                @if($serahTerima->damage_cost > 0)
                                    <p class="mt-1 text-xs font-semibold text-red-700">
                                        Estimasi: Rp {{ number_format($serahTerima->damage_cost, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            {{-- Form Pemeriksaan Akhir --}}
            @if(! $serahTerima->final_inspection_at)
                <section class="rounded-3xl border border-orange-200 bg-orange-50 shadow-sm p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-orange-700">Aksi Diperlukan</p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-900">Isi Hasil Pemeriksaan Akhir</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Lakukan pemeriksaan fisik koleksi yang telah diterima kembali, isi checklist dan detail kondisi di bawah.
                    </p>

                    <form action="{{ route('pengelola.deposit.store-final-inspection', $penyewaan) }}"
                          method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
                        @csrf

                        @if($errors->any())
                            <div class="rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Checklist Kondisi Akhir --}}
                        <div>
                            <p class="text-sm font-semibold text-slate-800 mb-3">Checklist Kondisi Akhir Koleksi</p>
                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach([
                                    ['name' => 'final_checklist_frame_safe',            'label' => 'Frame / bingkai dalam kondisi aman'],
                                    ['name' => 'final_checklist_no_tears',              'label' => 'Tidak ada sobekan atau kerusakan baru'],
                                    ['name' => 'final_checklist_color_normal',          'label' => 'Warna lukisan masih normal'],
                                    ['name' => 'final_checklist_glass_safe',            'label' => 'Kaca pelindung aman'],
                                    ['name' => 'final_checklist_no_mold',               'label' => 'Tidak ada jamur baru'],
                                    ['name' => 'final_checklist_packaging_safe',        'label' => 'Kemasan / packing aman'],
                                    ['name' => 'final_checklist_matches_documentation', 'label' => 'Sesuai kondisi awal dokumentasi'],
                                ] as $item)
                                    <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 cursor-pointer hover:bg-slate-50 transition">
                                        <input type="checkbox" name="{{ $item['name'] }}" value="1"
                                               {{ old($item['name']) ? 'checked' : '' }}
                                               class="w-4 h-4 rounded text-emerald-600 border-slate-300 focus:ring-emerald-400">
                                        <span class="text-sm text-slate-700">{{ $item['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Catatan & Foto --}}
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Catatan Pemeriksaan</label>
                                <textarea name="final_inspection_notes" rows="4"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"
                                    placeholder="Deskripsi kondisi koleksi secara umum...">{{ old('final_inspection_notes') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">
                                    Foto Dokumentasi Kondisi Akhir
                                    <span class="text-slate-400 font-normal">(opsional)</span>
                                </label>
                                <input type="file" name="final_inspection_photo"
                                       accept="image/*,.pdf"
                                       class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm
                                              file:mr-4 file:rounded-full file:border-0 file:bg-orange-50
                                              file:px-4 file:py-1.5 file:text-xs file:font-semibold file:text-orange-700
                                              hover:file:bg-orange-100">
                                <p class="mt-1 text-xs text-slate-400">JPG, PNG, atau PDF. Maks 10MB.</p>
                            </div>
                        </div>

                        {{-- Apakah ada kerusakan? --}}
                        <div>
                            <p class="text-sm font-semibold text-slate-800 mb-3">Hasil Pemeriksaan Kerusakan <span class="text-red-500">*</span></p>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <label class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-white p-5 cursor-pointer has-[:checked]:bg-emerald-50 has-[:checked]:border-emerald-400 transition">
                                    <input type="radio" name="has_damage" value="0" required
                                           {{ old('has_damage') === '0' ? 'checked' : '' }}
                                           class="mt-0.5 text-emerald-600"
                                           onchange="toggleDamageForm(false)">
                                    <div>
                                        <p class="text-sm font-semibold text-emerald-900">✓ Tidak Ada Kerusakan</p>
                                        <p class="mt-1 text-xs text-slate-500">Deposit akan dikembalikan penuh kepada penyewa.</p>
                                    </div>
                                </label>
                                <label class="flex items-start gap-3 rounded-2xl border border-red-200 bg-white p-5 cursor-pointer has-[:checked]:bg-red-50 has-[:checked]:border-red-400 transition">
                                    <input type="radio" name="has_damage" value="1"
                                           {{ old('has_damage') === '1' ? 'checked' : '' }}
                                           class="mt-0.5 text-red-600"
                                           onchange="toggleDamageForm(true)">
                                    <div>
                                        <p class="text-sm font-semibold text-red-900">✗ Ditemukan Kerusakan</p>
                                        <p class="mt-1 text-xs text-slate-500">Isi detail kerusakan di bawah untuk proses deposit.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Form Detail Kerusakan --}}
                        <div id="damage-form" class="{{ old('has_damage') === '1' ? '' : 'hidden' }} rounded-3xl border border-red-200 bg-red-50 p-6 space-y-4">
                            <p class="text-sm font-semibold text-red-900">Detail Kerusakan</p>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">
                                        Jenis Kerusakan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="final_damage_type"
                                           value="{{ old('final_damage_type', $serahTerima->damage_notes) }}"
                                           placeholder="Contoh: Kerusakan kaca pelindung, Sobekan pada kanvas"
                                           class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">
                                        Tingkat Kerusakan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="final_damage_level"
                                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                                        <option value="">-- Pilih --</option>
                                        <option value="ringan"  {{ old('final_damage_level') === 'ringan'  ? 'selected' : '' }}>Ringan</option>
                                        <option value="sedang"  {{ old('final_damage_level') === 'sedang'  ? 'selected' : '' }}>Sedang</option>
                                        <option value="berat"   {{ old('final_damage_level') === 'berat'   ? 'selected' : '' }}>Berat</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">
                                        Estimasi Biaya Restorasi / Penggantian (Rp) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="final_damage_cost" min="0"
                                           value="{{ old('final_damage_cost', $serahTerima->damage_cost ?? 0) }}"
                                           id="damage-cost-input"
                                           oninput="calculateDeposit()"
                                           class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">
                                </div>
                                <div class="rounded-2xl bg-white border border-slate-200 p-4 flex flex-col justify-center">
                                    <p class="text-xs text-slate-500">Deposit Penyewa</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-900">
                                        Rp {{ number_format($depositAmount, 0, ',', '.') }}
                                    </p>
                                    <div id="deposit-calc" class="mt-2 text-xs text-slate-500"></div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Catatan Kerusakan</label>
                                <textarea name="final_damage_notes" rows="3"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-300"
                                    placeholder="Deskripsi detail kerusakan yang ditemukan...">{{ old('final_damage_notes') }}</textarea>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                    onclick="return confirm('Yakin data pemeriksaan sudah lengkap dan benar?')"
                                    class="rounded-2xl bg-orange-600 px-8 py-3 text-sm font-semibold text-white hover:bg-orange-700 transition">
                                Simpan Hasil Pemeriksaan →
                            </button>
                        </div>
                    </form>
                </section>

            @else
                {{-- Pemeriksaan sudah dilakukan --}}
                <section class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
                    <div class="flex items-center justify-between mb-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Hasil Pemeriksaan Akhir</p>
                        <span class="rounded-full px-3 py-1 text-xs font-bold
                            {{ $serahTerima->has_damage ? 'bg-red-100 text-red-700 ring-1 ring-red-200' : 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200' }}">
                            {{ $serahTerima->has_damage ? '✗ Ada Kerusakan' : '✓ Tidak Ada Kerusakan' }}
                        </span>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-3 text-sm">
                        <div>
                            <p class="text-xs text-slate-500">Diperiksa Oleh</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $serahTerima->final_inspection_by }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Tanggal Pemeriksaan</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $serahTerima->final_inspection_at->format('d M Y H:i') }}</p>
                        </div>
                        @if($serahTerima->has_damage)
                            <div>
                                <p class="text-xs text-slate-500">Biaya Kerusakan</p>
                                <p class="mt-1 font-semibold text-red-700">Rp {{ number_format($serahTerima->final_damage_cost, 0, ',', '.') }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-5 flex justify-end">
                        <a href="{{ route('pengelola.deposit.show', $penyewaan) }}"
                           class="rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-700 transition">
                            Lanjut ke Pengelolaan Deposit →
                        </a>
                    </div>
                </section>
            @endif

        </div>
    </div>

    <script>
        function toggleDamageForm(show) {
            document.getElementById('damage-form').classList.toggle('hidden', !show);
        }

        const depositAmount = {{ $depositAmount }};

        function calculateDeposit() {
            const cost = parseInt(document.getElementById('damage-cost-input').value) || 0;
            const el   = document.getElementById('deposit-calc');
            if (cost <= 0) { el.textContent = ''; return; }
            const formatter = v => 'Rp ' + v.toLocaleString('id-ID');
            if (cost <= depositAmount) {
                const sisa = depositAmount - cost;
                el.innerHTML = `<span class="text-amber-700">Potongan: ${formatter(cost)}</span>`
                    + (sisa > 0 ? `<br><span class="text-emerald-700">Sisa refund: ${formatter(sisa)}</span>` : '<br><span class="text-slate-500">Deposit habis terpakai</span>');
            } else {
                const lebih = cost - depositAmount;
                el.innerHTML = `<span class="text-red-700 font-semibold">Deposit hangus semua</span>`
                    + `<br><span class="text-red-700">Tagihan tambahan: ${formatter(lebih)}</span>`;
            }
        }

        // Jalankan saat load jika ada nilai lama
        document.addEventListener('DOMContentLoaded', calculateDeposit);
    </script>
</x-app-layout>