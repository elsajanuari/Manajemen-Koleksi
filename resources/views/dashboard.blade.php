<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Welcome Section --}}
                    <div class="text-center py-8">
                        <div class="mb-6">
                            <svg class="w-24 h-24 mx-auto text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v4a2 2 0 002 2h2a2 2 0 002-2V3"/>
                            </svg>
                        </div>
                        
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">
                            {{ __('Selamat Datang di Museum MK Lesmana') }}
                        </h1>
                        
                        <p class="text-lg text-gray-600 mb-4">
                            {{ __('Anda telah berhasil masuk sebagai pengelola museum.') }}
                        </p>
                        
                        <div class="max-w-2xl mx-auto bg-indigo-50 border border-indigo-200 rounded-lg p-6 mt-6">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-indigo-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-left">
                                    <p class="text-sm text-gray-700">
                                        <span class="font-medium">{{ __('Petunjuk:') }}</span>
                                        {{ __('Gunakan menu di sisi kiri (sidebar) untuk mulai mengelola koleksi, penyewaan, pembelian, dan tiket museum.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fitur Utama --}}
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-6 text-center">
                            {{ __('Fitur Pengelolaan Museum') }}
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Fitur 1: Pengelolaan Koleksi --}}
                            <div class="bg-gradient-to-br from-indigo-50 to-white rounded-xl p-6 border border-indigo-100 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mx-auto mb-4">
                                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 text-center mb-2">
                                    {{ __('Pengelolaan Koleksi') }}
                                </h4>
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Daftar Koleksi') }}
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Kondisi Koleksi') }}
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Konservasi') }}
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Jadwal Konservasi') }}
                                    </li>
                                </ul>
                            </div>

                            {{-- Fitur 2: Penyewaan & Pembelian --}}
                            <div class="bg-gradient-to-br from-emerald-50 to-white rounded-xl p-6 border border-emerald-100 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mx-auto mb-4">
                                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 text-center mb-2">
                                    {{ __('Penyewaan & Pembelian') }}
                                </h4>
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Pengajuan Penyewaan') }}
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Pengajuan Pembelian') }}
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Dashboard Transaksi') }}
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Riwayat Transaksi') }}
                                    </li>
                                </ul>
                            </div>

                            {{-- Fitur 3: Tiket --}}
                            <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl p-6 border border-blue-100 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mx-auto mb-4">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800 text-center mb-2">
                                    {{ __('Manajemen Tiket') }}
                                </h4>
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Manajemen Tiket') }}
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Data Pengunjung') }}
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Verifikasi Tiket') }}
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Laporan Pendapatan & Penjualan') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Panduan Penggunaan --}}
                    <div class="mt-8 pt-8 border-t border-gray-200">
                        <div class="bg-gray-50 rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <h4 class="text-lg font-semibold text-gray-800">
                                    {{ __('Panduan Penggunaan') }}
                                </h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                <div class="flex items-start gap-2">
                                    <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold">1</span>
                                    <p>{{ __('Pilih menu yang diinginkan dari sidebar untuk mulai mengelola data') }}</p>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold">2</span>
                                    <p>{{ __('Setiap menu memiliki sub-menu untuk mengelola data secara lebih spesifik') }}</p>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold">3</span>
                                    <p>{{ __('Kelola koleksi, penyewaan, pembelian, dan tiket dengan mudah dan terstruktur') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>