<section class="space-y-5">
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-red-800">Peringatan!</p>
                <p class="text-sm text-red-700 mt-0.5">Setelah akun dihapus, semua data akan hilang secara permanen. Pastikan Anda sudah mengunduh data penting sebelum melanjutkan.</p>
            </div>
        </div>
    </div>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-sm hover:shadow-md"
    >
        Hapus Akun Saya
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus Akun</h2>
                    <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>

            <p class="text-sm text-gray-600 mb-6">
                Untuk melanjutkan, masukkan kata sandi Anda sebagai konfirmasi bahwa Anda ingin menghapus akun secara permanen.
            </p>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-400 focus:ring-2 focus:ring-red-100 transition-all duration-200 outline-none"
                        placeholder="Masukkan kata sandi Anda"
                    />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5 text-sm text-red-600" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-all duration-200">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                    Hapus Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>