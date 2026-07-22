<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Catatan Pemeriksaan
            </h2>
            <a href="{{ route('koleksi.show', $koleksi) }}" class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Detail Koleksi
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            {{-- Info koleksi --}}
            <div class="mb-4 rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-3 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-indigo-800">
                    Mengedit catatan pemeriksaan untuk koleksi: <span class="font-semibold">{{ $koleksi->nama }}</span>
                </p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Catatan Pemeriksaan</h3>

                    <form method="POST" action="{{ route('koleksi.kondisi.update', [$koleksi, $kondisi]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @include('kondisi._form', ['kondisi' => $kondisi, 'previousInspection' => $previousInspection])

                        <div class="flex items-center gap-3 pt-2">
                            <x-primary-button>Perbarui Catatan</x-primary-button>
                            <a href="{{ route('koleksi.show', $koleksi) }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>