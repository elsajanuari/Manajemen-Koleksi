@php
    $k = $kondisi ?? null;
    $previousInspection = $previousInspection ?? null;
    $val = fn($key, $default = null) => old($key, $k?->$key ?? $default);
    $checkedKondisi = fn($value) => (old('kondisi', $k?->kondisi ?? null) === $value) ? 'checked' : '';
    $tanggalDefault = old('tanggal_periksa', $k?->tanggal_periksa?->format('Y-m-d') ?? ($defaultTanggalPeriksa ?? now()->format('Y-m-d')));
    $pemeriksaDefault = old('pemeriksa', $k?->pemeriksa ?? Auth::user()->name ?? '');
    $fotoKondisiRequired = ! $k?->foto_kondisi_saat_ini_url;
    $fotoKerusakanRequired = ! $k?->foto_kerusakan_url;
@endphp

<div class="space-y-6">

    <div>
        <x-input-label for="tanggal_periksa" :value="__('Tanggal Pemeriksaan')" />
        <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
        <x-text-input
            id="tanggal_periksa"
            name="tanggal_periksa"
            type="date"
            class="mt-1 block w-full"
            value="{{ $tanggalDefault }}"
            required
        />
        <x-input-error :messages="$errors->get('tanggal_periksa')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="pemeriksa" :value="__('Nama Pemeriksa')" />
        <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
        <x-text-input
            id="pemeriksa"
            name="pemeriksa"
            type="text"
            class="mt-1 block w-full"
            value="{{ $pemeriksaDefault }}"
            placeholder="Nama petugas yang memeriksa"
            required
        />
        <x-input-error :messages="$errors->get('pemeriksa')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="kondisi" :value="__('Kondisi Koleksi')" />
        <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
        <div class="mt-2 grid grid-cols-3 gap-3">
            @foreach ([
                'baik' => [
                    'label' => 'Baik',
                    'desc' => 'Kondisi normal, tidak ada kerusakan',
                    'dot' => 'bg-green-500',
                    'card' => 'hover:border-green-300 hover:bg-green-50/60 peer-focus-visible:ring-2 peer-focus-visible:ring-green-400 peer-focus-visible:ring-offset-1 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:shadow-sm peer-checked:[&_.kondisi-title]:text-green-900 peer-checked:[&_.kondisi-desc]:text-green-800',
                ],
                'rusak_ringan' => [
                    'label' => 'Rusak Ringan',
                    'desc' => 'Ada kerusakan ringan, perlu tindakan',
                    'dot' => 'bg-amber-500',
                    'card' => 'hover:border-amber-300 hover:bg-amber-50/60 peer-focus-visible:ring-2 peer-focus-visible:ring-amber-400 peer-focus-visible:ring-offset-1 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:shadow-sm peer-checked:[&_.kondisi-title]:text-amber-900 peer-checked:[&_.kondisi-desc]:text-amber-800',
                ],
                'rusak_berat' => [
                    'label' => 'Rusak Berat',
                    'desc' => 'Ada kerusakan berat, perlu tindakan',
                    'dot' => 'bg-red-500',
                    'card' => 'hover:border-red-300 hover:bg-red-50/60 peer-focus-visible:ring-2 peer-focus-visible:ring-red-400 peer-focus-visible:ring-offset-1 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:shadow-sm peer-checked:[&_.kondisi-title]:text-red-900 peer-checked:[&_.kondisi-desc]:text-red-800',
                ],
            ] as $value => $opt)
                <label class="relative cursor-pointer">
                    <input type="radio" name="kondisi" value="{{ $value }}" class="peer sr-only"
                           {{ $checkedKondisi($value) }} required />
                    <div class="rounded-lg border-2 border-gray-200 bg-white p-4 text-center transition-colors {{ $opt['card'] }}">
                        <div class="mx-auto mb-2 h-4 w-4 rounded-full {{ $opt['dot'] }}"></div>
                        <p class="kondisi-title text-sm font-semibold text-gray-900">{{ $opt['label'] }}</p>
                        <p class="kondisi-desc mt-1 text-xs text-gray-500">{{ $opt['desc'] }}</p>
                    </div>
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('kondisi')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="jenis_kerusakan" :value="__('Jenis Kerusakan')" />
        <span class="text-xs text-gray-500">Opsional</span>
        <x-text-input
            id="jenis_kerusakan"
            name="jenis_kerusakan"
            type="text"
            class="mt-1 block w-full"
            value="{{ $val('jenis_kerusakan') }}"
            placeholder="Contoh: retak, pudar, noda"
        />
        <x-input-error :messages="$errors->get('jenis_kerusakan')" class="mt-2" />
    </div>

    <div>
        <p class="text-sm font-medium text-gray-700 uppercase mb-4">Dokumentasi Foto Kondisi Koleksi</p>
        
        {{-- Display Previous Inspection Photo --}}
        @if ($previousInspection?->foto_kondisi_saat_ini_url)
            <div class="mb-6 p-4 rounded-lg border border-blue-200 bg-blue-50">
                <p class="text-sm font-semibold text-blue-900 mb-3">Referensi: Foto Pemeriksaan Sebelumnya</p>
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <img src="{{ $previousInspection->foto_kondisi_saat_ini_url }}" alt="Foto Pemeriksaan Sebelumnya" class="h-24 w-24 rounded object-cover border border-blue-300" />
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-blue-700 font-medium uppercase mb-1">Tanggal Pemeriksaan</p>
                        <p class="text-sm text-blue-900">{{ $previousInspection->tanggal_periksa->format('d M Y') }}</p>
                        <p class="text-xs text-blue-700 font-medium uppercase mb-1 mt-2">Kondisi</p>
                        <p class="text-sm text-blue-900">{{ $previousInspection->label_kondisi }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-1">
            {{-- Foto Kondisi Saat Ini (Main Upload) --}}
            <div>
                <x-input-label for="foto_kondisi_saat_ini" :value="__('Foto Kondisi Saat Ini')" />
                @if ($fotoKondisiRequired)
                    <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
                @else
                    <span class="text-xs text-gray-500">Opsional — foto tersimpan akan dipertahankan jika tidak diunggah ulang</span>
                @endif
                @if ($k?->foto_kondisi_saat_ini_url)
                    <div class="mt-2 mb-2">
                        <a href="{{ $k->foto_kondisi_saat_ini_url }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-900">
                            <img src="{{ $k->foto_kondisi_saat_ini_url }}" alt="Foto Kondisi Saat Ini" class="h-16 w-16 rounded object-cover border border-gray-200" />
                            <span>Lihat foto saat ini</span>
                        </a>
                    </div>
                @endif
                <input
                    id="foto_kondisi_saat_ini"
                    name="foto_kondisi_saat_ini"
                    type="file"
                    accept="image/*"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    @if ($fotoKondisiRequired) required @endif
                />
                <x-input-error :messages="$errors->get('foto_kondisi_saat_ini')" class="mt-2" />
            </div>

            {{-- Foto Kerusakan (Conditional - shown only for rusak_ringan or rusak_berat) --}}
            <div id="foto_kerusakan_wrapper" style="display: none;">
                <x-input-label for="foto_kerusakan" :value="__('Foto Detail Kerusakan')" />
                @if ($fotoKerusakanRequired)
                    <span class="text-xs text-red-600 font-medium">Wajib diisi jika kondisi rusak</span>
                @else
                    <span class="text-xs text-gray-500">Opsional — foto tersimpan akan dipertahankan jika tidak diunggah ulang</span>
                @endif
                @if ($k?->foto_kerusakan_url)
                    <div class="mt-2 mb-2">
                        <a href="{{ $k->foto_kerusakan_url }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-900">
                            <img src="{{ $k->foto_kerusakan_url }}" alt="Foto Kerusakan" class="h-16 w-16 rounded object-cover border border-gray-200" />
                            <span>Lihat foto kerusakan</span>
                        </a>
                    </div>
                @endif
                <input
                    id="foto_kerusakan"
                    name="foto_kerusakan"
                    type="file"
                    accept="image/*"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
                <x-input-error :messages="$errors->get('foto_kerusakan')" class="mt-2" />
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kondisiRadios = document.querySelectorAll('input[name="kondisi"]');
            const fotoKerusakanWrapper = document.getElementById('foto_kerusakan_wrapper');
            const fotoKerusakanInput = document.getElementById('foto_kerusakan');
            const fotoKerusakanRequired = @json($fotoKerusakanRequired);
            
            function updateKerusakanDisplay() {
                const selectedKondisi = document.querySelector('input[name="kondisi"]:checked');
                const isRusak = selectedKondisi
                    && (selectedKondisi.value === 'rusak_ringan' || selectedKondisi.value === 'rusak_berat');

                if (isRusak) {
                    fotoKerusakanWrapper.style.display = 'block';
                    fotoKerusakanInput.required = fotoKerusakanRequired;
                } else {
                    fotoKerusakanWrapper.style.display = 'none';
                    fotoKerusakanInput.required = false;
                }
            }
            
            kondisiRadios.forEach(radio => {
                radio.addEventListener('change', updateKerusakanDisplay);
            });
            
            updateKerusakanDisplay();
        });
    </script>

    {{-- Recommendation Section --}}
    <div>
        <x-input-label for="rekomendasi_tindak_lanjut" :value="__('Rekomendasi Tindak Lanjut')" />
        <span id="rekomendasi-required-label" class="text-xs text-red-600 font-medium hidden">Wajib diisi jika kondisi rusak</span>
        <span id="rekomendasi-optional-label" class="text-xs text-gray-500">Opsional</span>
        <select id="rekomendasi_tindak_lanjut" name="rekomendasi_tindak_lanjut" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            <option value="">Pilih rekomendasi (jika ada)</option>
            @foreach (\App\Models\KondisiKoleksi::REKOMENDASI_OPTIONS as $value => $label)
                <option value="{{ $value }}" {{ old('rekomendasi_tindak_lanjut', $k?->rekomendasi_tindak_lanjut) === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('rekomendasi_tindak_lanjut')" class="mt-2" />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kondisiRadios = document.querySelectorAll('input[name="kondisi"]');
            const rekomendasiSelect = document.getElementById('rekomendasi_tindak_lanjut');
            const requiredLabel = document.getElementById('rekomendasi-required-label');
            const optionalLabel = document.getElementById('rekomendasi-optional-label');

            // Harus selaras dengan KondisiKoleksiController::allowedRekomendasiForKondisi().
            const allowedRekomendasi = {
                baik: ['', 'tidak_perlu_tindakan', 'pemeliharaan', 'pemeriksaan_ulang'],
                rusak_ringan: ['penanganan_kerusakan', 'pemeriksaan_ulang'],
                rusak_berat: ['penanganan_kerusakan'],
            };

            function updateRekomendasiOptions() {
                const selectedKondisi = document.querySelector('input[name="kondisi"]:checked');
                const isRusak = selectedKondisi
                    && (selectedKondisi.value === 'rusak_ringan' || selectedKondisi.value === 'rusak_berat');

                rekomendasiSelect.required = isRusak;
                requiredLabel.classList.toggle('hidden', !isRusak);
                optionalLabel.classList.toggle('hidden', isRusak);

                const allowed = selectedKondisi ? allowedRekomendasi[selectedKondisi.value] : null;

                Array.from(rekomendasiSelect.options).forEach(option => {
                    const ok = !allowed || allowed.includes(option.value);
                    option.disabled = !ok;
                    option.hidden = !ok;
                });

                if (allowed && rekomendasiSelect.value && !allowed.includes(rekomendasiSelect.value)) {
                    rekomendasiSelect.value = '';
                }
            }

            kondisiRadios.forEach(radio => {
                radio.addEventListener('change', updateRekomendasiOptions);
            });

            updateRekomendasiOptions();
        });
    </script>

    <div>
        <p class="text-sm font-medium text-gray-700 uppercase mb-4">Kondisi Lingkungan</p>
    </div>
    <div class="grid gap-6 lg:grid-cols-2">
        <div>
            <x-input-label for="kebersihan_lingkungan" :value="__('Kebersihan Lingkungan')" />
            <span class="text-xs text-gray-500">Opsional</span>
            <select id="kebersihan_lingkungan" name="kebersihan_lingkungan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Pilih tingkat kebersihan</option>
                <option value="baik" {{ old('kebersihan_lingkungan', $k?->kebersihan_lingkungan) === 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="cukup" {{ old('kebersihan_lingkungan', $k?->kebersihan_lingkungan) === 'cukup' ? 'selected' : '' }}>Cukup</option>
                <option value="buruk" {{ old('kebersihan_lingkungan', $k?->kebersihan_lingkungan) === 'buruk' ? 'selected' : '' }}>Buruk</option>
            </select>
            <x-input-error :messages="$errors->get('kebersihan_lingkungan')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="suhu" :value="__('Suhu (°C)')" />
            <span class="text-xs text-gray-500">Opsional</span>
            <x-text-input
                id="suhu"
                name="suhu"
                type="number"
                step="0.1"
                min="-10"
                max="60"
                class="mt-1 block w-full"
                value="{{ $val('suhu') }}"
                placeholder="Contoh: 22.5"
            />
            <x-input-error :messages="$errors->get('suhu')" class="mt-2" />
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div>
            <x-input-label for="kelembapan" :value="__('Kelembapan (%)')" />
            <span class="text-xs text-gray-500">Opsional</span>
            <x-text-input
                id="kelembapan"
                name="kelembapan"
                type="number"
                step="1"
                min="0"
                max="100"
                class="mt-1 block w-full"
                value="{{ $val('kelembapan') }}"
                placeholder="Contoh: 55"
            />
            <x-input-error :messages="$errors->get('kelembapan')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="pencahayaan" :value="__('Pencahayaan')" />
            <span class="text-xs text-gray-500">Opsional</span>
            <select id="pencahayaan" name="pencahayaan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Pilih tingkat pencahayaan</option>
                <option value="rendah" {{ old('pencahayaan', $k?->pencahayaan) === 'rendah' ? 'selected' : '' }}>Rendah</option>
                <option value="sedang" {{ old('pencahayaan', $k?->pencahayaan) === 'sedang' ? 'selected' : '' }}>Sedang</option>
                <option value="tinggi" {{ old('pencahayaan', $k?->pencahayaan) === 'tinggi' ? 'selected' : '' }}>Tinggi</option>
            </select>
            <x-input-error :messages="$errors->get('pencahayaan')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="catatan" :value="__('Catatan Pemeriksaan')" />
        <span class="text-xs text-gray-500">Opsional — deskripsikan temuan, tindakan yang diambil, dll.</span>
        <textarea
            id="catatan"
            name="catatan"
            rows="4"
            placeholder="Contoh: Terdapat retakan kecil di sudut kanan bawah. Sudah diberi lapisan pelindung sementara."
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
        >{{ $val('catatan') }}</textarea>
        <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
    </div>

    <div class="flex items-center gap-3 pt-2">
        {{-- The caller should render the submit button and cancel link. --}}
    </div>

</div>
