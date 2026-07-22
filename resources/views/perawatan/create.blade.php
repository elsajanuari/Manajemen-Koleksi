<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Jadwal Konservasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if ($kondisiKoleksi ?? null)
                <div class="mb-4 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm text-indigo-900">
                    Membuat jadwal tindak lanjut dari pemeriksaan kondisi
                    <span class="font-semibold">{{ $kondisiKoleksi->koleksi->nama }}</span>
                    ({{ $kondisiKoleksi->tanggal_periksa->format('d M Y') }}).
                    Rekomendasi: <span class="font-semibold">{{ $kondisiKoleksi->label_rekomendasi }}</span>.
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tambah Jadwal Konservasi</h3>

                    <form method="POST" action="{{ route('jadwal-konservasi.store') }}">
                        @csrf

                        @include('perawatan._form', [
                            'selectedKoleksi' => $selectedKoleksi,
                            'prefillJenis' => $prefillJenis ?? null,
                            'prefillCatatan' => $prefillCatatan ?? null,
                            'prefillFrekuensi' => $prefillFrekuensi ?? null,
                            'prefillJadwalTanggal' => $prefillJadwalTanggal ?? null,
                            'prefillPenanggungJawab' => $prefillPenanggungJawab ?? null,
                            'prefillEstimasiDurasi' => $prefillEstimasiDurasi ?? null,
                            'kondisiKoleksiId' => $kondisiKoleksi?->id,
                            'minJadwalTanggal' => $minJadwalTanggal,
                        ])

                        <div class="flex items-center gap-3 pt-6">
                            <x-primary-button>{{ __('Simpan Jadwal') }}</x-primary-button>
                            @if ($kondisiKoleksi ?? null)
                                <a href="{{ route('koleksi.kondisi.show', [$kondisiKoleksi->koleksi, $kondisiKoleksi]) }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                            @else
                                <a href="{{ route('jadwal-konservasi.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
