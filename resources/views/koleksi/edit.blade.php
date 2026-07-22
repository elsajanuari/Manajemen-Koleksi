<x-app-layout>
    @php
        $koleksiShowUrl = route('koleksi.show', $koleksi);
        $batalUrl = url()->previous();
        if (! $batalUrl || $batalUrl === url()->current()) {
            $batalUrl = $koleksiShowUrl;
        }
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Koleksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Form Title --}}
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Koleksi</h3>

                    <form method="POST" action="{{ route('koleksi.update', $koleksi) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <x-input-label for="nama" :value="__('Nama Koleksi')" />
                                <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
                                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" value="{{ old('nama', $koleksi->nama) }}" required autofocus />
                                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label :value="__('Nomor Inventaris')" />
                                <p class="mt-1 text-sm text-gray-700">{{ $koleksi->nomor_inventaris ?? 'Akan dibuat otomatis setelah disimpan' }}</p>
                            </div>

                            <div>
                                <x-input-label for="seniman" :value="__('Seniman/Penulis')" />
                                <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
                                <x-text-input id="seniman" name="seniman" type="text" class="mt-1 block w-full" value="{{ old('seniman', $koleksi->seniman) }}" required />
                                <x-input-error :messages="$errors->get('seniman')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="kategori" :value="__('Kategori')" />
                                <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
                                <div class="mt-1 flex flex-col gap-2 sm:flex-row sm:items-center">
                                    <select id="kategori" name="kategori" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">Pilih kategori</option>
                                        @foreach ($categories as $kategori)
                                            <option value="{{ $kategori }}" {{ old('kategori', $koleksi->kategori) === $kategori ? 'selected' : '' }}>{{ ucfirst($kategori) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'tambah-kategori' }))">
                                        + Tambah Kategori
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
                            </div>

                            <div id="lukisan-fields" style="{{ old('kategori', $koleksi->kategori) === 'lukisan' ? '' : 'display:none' }}">
                                <div>
                                    <x-input-label for="teknik_media" :value="__('Teknik / Media')" />
                                    <span class="text-xs text-red-600 font-medium">Wajib diisi untuk lukisan</span>
                                    <x-text-input id="teknik_media" name="teknik_media" type="text" class="mt-1 block w-full" value="{{ old('teknik_media', $koleksi->teknik_media) }}" />
                                    <x-input-error :messages="$errors->get('teknik_media')" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="ukuran_lukisan" :value="__('Ukuran Lukisan')" />
                                    <span class="text-xs text-red-600 font-medium">Wajib diisi untuk lukisan</span>
                                    <x-text-input id="ukuran_lukisan" name="ukuran_lukisan" type="text" class="mt-1 block w-full" value="{{ old('ukuran_lukisan', $koleksi->ukuran_lukisan) }}" />
                                    <x-input-error :messages="$errors->get('ukuran_lukisan')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="tahun" id="tahun-label" :value="strtolower(old('kategori', $koleksi->kategori) ?? '') === 'buku' ? __('Tahun Terbit') : __('Tahun Pembuatan')" />
                                <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
                                <x-text-input id="tahun" name="tahun" type="number" min="1500" max="{{ date('Y') }}" step="1" placeholder="Contoh: 2024" class="mt-1 block w-full" value="{{ old('tahun', $koleksi->tahun) }}" required />
                                <x-input-error :messages="$errors->get('tahun')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="deskripsi" :value="__('Deskripsi')" />
                                <span class="text-xs text-gray-500">Opsional</span>
                                <textarea id="deskripsi" name="deskripsi" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi', $koleksi->deskripsi) }}</textarea>
                                <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                            </div>

                            @if ($koleksi->foto)
                                <div>
                                    <x-input-label :value="__('Foto Saat Ini')" />
                                    <img src="{{ asset('storage/' . $koleksi->foto) }}" alt="{{ $koleksi->nama }}" class="mt-2 max-h-48 rounded-md border border-gray-200 object-cover" />
                                </div>
                            @endif

                            <div>
                                <x-input-label for="foto" :value="__('Unggah Foto Baru')" />
                                <span class="text-xs text-gray-500">Opsional (bisa tidak diganti)</span>
                                <input id="foto" name="foto" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                <x-input-error :messages="$errors->get('foto')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="status_sewa" :value="__('Ketersediaan Sewa & Beli')" />
                                <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
                                <select id="status_sewa" name="status_sewa" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Pilih ketersediaan</option>
                                    @php
                                        $statusSewaSaatIni = array_key_exists($koleksi->status_sewa, \App\Models\Koleksi::statusSewaOptions())
                                            ? $koleksi->status_sewa
                                            : 'tidak';
                                    @endphp
                                    @foreach (\App\Models\Koleksi::statusSewaOptions() as $value => $option)
                                        <option value="{{ $value }}" {{ old('status_sewa', $statusSewaSaatIni) === $value ? 'selected' : '' }}>{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('status_sewa')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div id="daily_rate_wrapper">
                                    <x-input-label for="daily_rate" :value="__('Tarif Sewa / Hari (Rp)')" />
                                    <span id="daily_rate_hint" class="text-xs text-gray-500">Opsional</span>
                                    <x-text-input id="daily_rate" name="daily_rate" type="number" min="0" class="mt-1 block w-full" value="{{ old('daily_rate', $koleksi->daily_rate ?? 0) }}" />
                                    <x-input-error :messages="$errors->get('daily_rate')" class="mt-2" />
                                </div>
                                <div id="sale_price_wrapper">
                                    <x-input-label for="sale_price" :value="__('Harga Jual (Rp)')" />
                                    <span id="sale_price_hint" class="text-xs text-gray-500">Opsional</span>
                                    <x-text-input id="sale_price" name="sale_price" type="number" min="0" class="mt-1 block w-full" value="{{ old('sale_price', $koleksi->sale_price) }}" />
                                    <x-input-error :messages="$errors->get('sale_price')" class="mt-2" />
                                </div>
                                <div id="weight_gram_wrapper">
                                    <x-input-label for="weight_gram" :value="__('Berat (gram)')" />
                                    <span id="weight_gram_hint" class="text-xs text-red-600 font-medium">Wajib diisi</span>
                                    <x-text-input id="weight_gram" name="weight_gram" type="number" min="1" class="mt-1 block w-full" value="{{ old('weight_gram', $koleksi->weight_gram ?? 1000) }}" required />
                                    <p class="mt-1 text-xs text-gray-500">Berat fisik koleksi (juga dipakai untuk perhitungan ongkir bila dijual).</p>
                                    <x-input-error :messages="$errors->get('weight_gram')" class="mt-2" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="lokasi" :value="__('Lokasi Fisik')" />
                                <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
                                <select id="lokasi" name="lokasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Pilih lokasi fisik</option>
                                    @foreach (\App\Models\Koleksi::lokasiOptions() as $value => $option)
                                        <option value="{{ $value }}" {{ old('lokasi', $koleksi->lokasi) === $value ? 'selected' : '' }}>{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                            </div>

                            <div class="flex flex-wrap items-center gap-3 pt-2">
                                <x-primary-button>{{ __('Perbarui') }}</x-primary-button>
                                <a href="{{ $batalUrl }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                            </div>
                        </div>
                    </form>
                    @include('koleksi._category-modal')
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categorySelect = document.getElementById('kategori');
            const lukisanFields = document.getElementById('lukisan-fields');
            const teknikInput = document.getElementById('teknik_media');
            const ukuranInput = document.getElementById('ukuran_lukisan');
            const statusSewaSelect = document.getElementById('status_sewa');
            const dailyRateInput = document.getElementById('daily_rate');
            const salePriceInput = document.getElementById('sale_price');
            const dailyRateHint = document.getElementById('daily_rate_hint');
            const salePriceHint = document.getElementById('sale_price_hint');
            const dailyRateWrapper = document.getElementById('daily_rate_wrapper');
            const salePriceWrapper = document.getElementById('sale_price_wrapper');

            function toggleField(wrapper, input, hint, isVisible) {
                wrapper.style.display = isVisible ? '' : 'none';
                input.required = isVisible;
                if (isVisible) {
                    hint.textContent = 'Wajib diisi';
                    hint.className = 'text-xs text-red-600 font-medium';
                }
            }

            function updateAvailabilityFields() {
                const status = statusSewaSelect.value;
                const needsRent = status === 'sewa' || status === 'sewa_beli';
                const needsSale = status === 'beli' || status === 'sewa_beli';

                toggleField(dailyRateWrapper, dailyRateInput, dailyRateHint, needsRent);
                toggleField(salePriceWrapper, salePriceInput, salePriceHint, needsSale);
            }

            function updateLukisanFields() {
                const isLukisan = categorySelect.value === 'lukisan';
                lukisanFields.style.display = isLukisan ? '' : 'none';
                teknikInput.required = isLukisan;
                ukuranInput.required = isLukisan;
            }

            const tahunLabel = document.getElementById('tahun-label');
            function updateTahunLabel() {
                tahunLabel.textContent = categorySelect.value.toLowerCase() === 'buku'
                    ? 'Tahun Terbit'
                    : 'Tahun Pembuatan';
            }

            categorySelect.addEventListener('change', updateLukisanFields);
            categorySelect.addEventListener('change', updateTahunLabel);
            statusSewaSelect.addEventListener('change', updateAvailabilityFields);
            updateLukisanFields();
            updateTahunLabel();
            updateAvailabilityFields();
        });
    </script>
</x-app-layout>
