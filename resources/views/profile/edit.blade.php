<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Pengaturan Profil</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola informasi akun dan keamanan Anda</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-sm text-gray-700 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Avatar/Header Card -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 mb-8 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <span class="text-2xl font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold">{{ auth()->user()->name }}</h2>
                        <p class="text-blue-100 text-sm">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Profile Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Informasi Pribadi</h3>
                        <p class="text-xs text-gray-400">Perbarui data diri Anda</p>
                    </div>
                </div>
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Keamanan</h3>
                        <p class="text-xs text-gray-400">Ganti kata sandi akun Anda</p>
                    </div>
                </div>
                @include('profile.partials.update-password-form')
            </div>

            <!-- Delete Account -->
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Hapus Akun</h3>
                        <p class="text-xs text-gray-400">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>