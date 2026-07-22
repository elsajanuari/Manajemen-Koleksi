<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Jadwal Konservasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Jadwal Konservasi</h3>

                    <form method="POST" action="{{ route('jadwal-konservasi.update', $perawatan) }}">
                        @csrf
                        @method('PUT')

                        @include('perawatan._form', [
                            'perawatan' => $perawatan,
                            'minJadwalTanggal' => $minJadwalTanggal,
                        ])

                        <div class="flex items-center gap-3 pt-6">
                            <x-primary-button>{{ __('Perbarui Jadwal') }}</x-primary-button>
                            <a href="{{ route('jadwal-konservasi.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
