@php
    use App\Models\PerawatanKoleksi;

    $p = $perawatan ?? null;
    $val = fn ($key, $default = null) => old($key, $p?->$key ?? $default);
    $selectedJenis = old('jenis_perawatan', $p?->jenis_perawatan ?? ($prefillJenis ?? null));
    $kondisiKoleksiId = old('kondisi_koleksi_id', $kondisiKoleksiId ?? $p?->kondisi_koleksi_id ?? null);
    $minJadwalTanggal = $minJadwalTanggal ?? today()->toDateString();
    $penanggungJawabDefault = old('penanggung_jawab', $p?->penanggung_jawab ?? ($prefillPenanggungJawab ?? Auth::user()->name ?? ''));
    $defaultJadwalTanggal = old('jadwal_tanggal', $p?->jadwal_tanggal?->toDateString() ?? ($prefillJadwalTanggal ?? null));
    $defaultFrekuensi = old('frekuensi', $p?->frekuensi ?? ($prefillFrekuensi ?? 'sekali'));
    $jenisHelperText = PerawatanKoleksi::JENIS_HELPER_TEXT[$selectedJenis]
        ?? PerawatanKoleksi::JENIS_HELPER_TEXT_DEFAULT;
@endphp

<div class="space-y-6">

    @if ($kondisiKoleksiId)
        <input type="hidden" name="kondisi_koleksi_id" value="{{ $kondisiKoleksiId }}">
    @endif

    {{-- Koleksi --}}
    <div>
        <x-input-label for="koleksi_id" :value="__('Koleksi')" />
        <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
        <select id="koleksi_id" name="koleksi_id"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            <option value="">Pilih koleksi...</option>
            @foreach ($koleksiList as $k)
                <option value="{{ $k->id }}"
                    {{ old('koleksi_id', $p?->koleksi_id ?? $selectedKoleksi?->id ?? null) == $k->id ? 'selected' : '' }}>
                    {{ $k->nama }} ({{ ucfirst($k->kategori) }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('koleksi_id')" class="mt-2" />
    </div>

    {{-- Jenis Konservasi --}}
    <div>
        <x-input-label for="jenis_perawatan" :value="__('Jenis Konservasi')" />
        <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
        <select id="jenis_perawatan" name="jenis_perawatan"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            <option value="">Pilih jenis konservasi...</option>
            @foreach (PerawatanKoleksi::JENIS_OPTIONS as $value => $label)
                <option value="{{ $value }}"
                    {{ $selectedJenis === $value ? 'selected' : '' }}
                    @if ($value === PerawatanKoleksi::JENIS_PENANGANAN_KERUSAKAN && ! $kondisiKoleksiId) disabled @endif>
                    {{ $label }}
                    @if ($value === PerawatanKoleksi::JENIS_PENANGANAN_KERUSAKAN && ! $kondisiKoleksiId)
                        (hanya dari detail pemeriksaan kondisi)
                    @endif
                </option>
            @endforeach
        </select>
        <p id="jenis_perawatan_helper" class="mt-2 text-xs text-gray-500">{{ $jenisHelperText }}</p>
        @unless ($kondisiKoleksiId)
            <p id="jenis_kondisi_warning" class="mt-2 rounded-md border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700">
                Jadwal <strong>Penanganan Kerusakan</strong> harus dibuat dari tombol
                "Buat Jadwal" pada detail pemeriksaan kondisi koleksi, agar dapat diselesaikan melalui alur tindakan konservasi.
            </p>
        @endunless
        <x-input-error :messages="$errors->get('jenis_perawatan')" class="mt-2" />
        <x-input-error :messages="$errors->get('kondisi_koleksi_id')" class="mt-2" />
    </div>

    {{-- Tanggal Jadwal --}}
    <div>
        <x-input-label for="jadwal_tanggal" :value="__('Tanggal Jadwal')" />
        <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
        <x-text-input id="jadwal_tanggal" name="jadwal_tanggal" type="date"
            class="mt-1 block w-full"
            value="{{ $defaultJadwalTanggal }}"
            min="{{ $minJadwalTanggal }}"
            required />
        <p class="mt-1 text-xs text-gray-500">Tanggal minimal: {{ \Carbon\Carbon::parse($minJadwalTanggal)->format('d M Y') }}.</p>
        <x-input-error :messages="$errors->get('jadwal_tanggal')" class="mt-2" />
    </div>

    {{-- Frekuensi --}}
    <div>
        <x-input-label for="frekuensi" :value="__('Frekuensi Jadwal')" />
        <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
        <select id="frekuensi" name="frekuensi"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @foreach (PerawatanKoleksi::FREKUENSI_OPTIONS as $value => $label)
                <option value="{{ $value }}" {{ $defaultFrekuensi === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-500">Jika bukan sekali, sistem akan menawarkan jadwal berikutnya setelah jadwal selesai.</p>
        <x-input-error :messages="$errors->get('frekuensi')" class="mt-2" />
    </div>

    {{-- Estimasi Durasi --}}
    <div>
        <x-input-label for="estimasi_durasi_menit" :value="__('Estimasi Durasi (menit)')" />
        <span class="text-xs text-gray-500">Opsional</span>
        <x-text-input id="estimasi_durasi_menit" name="estimasi_durasi_menit" type="number"
            class="mt-1 block w-full"
            min="15" max="1440" step="1"
            value="{{ old('estimasi_durasi_menit', $p?->estimasi_durasi_menit ?? ($prefillEstimasiDurasi ?? '')) }}"
            placeholder="Contoh: 120 (2 jam)" />
        <p class="mt-1 text-xs text-gray-500">Perkiraan waktu pelaksanaan, 15–1440 menit.</p>
        <x-input-error :messages="$errors->get('estimasi_durasi_menit')" class="mt-2" />
    </div>

    {{-- Penanggung Jawab --}}
    <div>
        <x-input-label for="penanggung_jawab" :value="__('Penanggung Jawab')" />
        <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
        <x-text-input id="penanggung_jawab" name="penanggung_jawab" type="text"
            class="mt-1 block w-full"
            value="{{ $penanggungJawabDefault }}"
            placeholder="Nama penanggung jawab jadwal"
            required />
        <p class="mt-1 text-xs text-gray-500">Notifikasi pembuatan jadwal dan pengingat pelaksanaan akan dikirim ke akun Anda.</p>
        <x-input-error :messages="$errors->get('penanggung_jawab')" class="mt-2" />
    </div>

    {{-- Catatan --}}
    <div>
        <x-input-label for="catatan" :value="__('Catatan')" />
        <span class="text-xs text-gray-500">Opsional</span>
        <textarea id="catatan" name="catatan" rows="3"
            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
            placeholder="Instruksi khusus atau catatan tambahan...">{{ old('catatan', $p?->catatan ?? ($prefillCatatan ?? '')) }}</textarea>
        <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisSelect = document.getElementById('jenis_perawatan');
        const kondisiWarning = document.getElementById('jenis_kondisi_warning');
        const jenisHelper = document.getElementById('jenis_perawatan_helper');
        const jenisHelperTexts = @json(PerawatanKoleksi::JENIS_HELPER_TEXT);
        const jenisHelperDefault = @json(PerawatanKoleksi::JENIS_HELPER_TEXT_DEFAULT);

        function updateHelperText() {
            jenisHelper.textContent = jenisHelperTexts[jenisSelect.value] ?? jenisHelperDefault;
        }

        function updateKondisiWarning() {
            if (!kondisiWarning) {
                return;
            }
            const requiresKondisi = jenisSelect.value === 'penanganan_kerusakan';
            kondisiWarning.classList.toggle('hidden', !requiresKondisi);
        }

        jenisSelect.addEventListener('change', updateHelperText);
        jenisSelect.addEventListener('change', updateKondisiWarning);
        updateHelperText();
        updateKondisiWarning();
    });
</script>
