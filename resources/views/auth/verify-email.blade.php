<x-guest-layout>
    {{-- Panel Kiri --}}
    <div class="hidden md:flex flex-col justify-between w-2/5 bg-blue-700 p-10 relative overflow-hidden">
        <div class="absolute -top-16 -left-16 w-56 h-56 rounded-full bg-white/5"></div>
        <div class="absolute -bottom-10 -right-12 w-40 h-40 rounded-full bg-white/8"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-10">
                <div class="w-8 h-8 bg-white/15 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M5 6l7-3 7 3M4 10v11M20 10v11M8 10v11M16 10v11M12 10v11"/>
                    </svg>
                </div>
                <span class="text-white font-medium text-sm tracking-wide">{{ config('app.name', 'Museum MK Lesmana') }}</span>
            </div>
            <h1 class="text-white text-2xl font-medium leading-snug mb-3">Verifikasi<br>Email Anda</h1>
            <p class="text-white/60 text-sm leading-relaxed">Konfirmasi alamat email Anda untuk mengakses semua layanan Museum MK Lesmana.</p>
        </div>

        <div class="relative z-10">
            <p class="text-white/50 text-xs mb-3">Sudah verifikasi email?</p>
            <a href="{{ route('login') }}" class="inline-block px-5 py-2 rounded-lg border border-white/40 text-white text-sm font-medium hover:bg-white/10 transition">
                Masuk
            </a>
        </div>
    </div>

    {{-- Panel Kanan --}}
    <div class="flex-1 bg-white p-10 flex flex-col justify-center">
        <h2 class="text-xl font-medium text-gray-800 mb-1">Verifikasi email</h2>
        <p class="text-sm text-gray-400 mb-7">Konfirmasi alamat email Anda untuk melanjutkan</p>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Terima kasih telah mendaftar! Sebelum memulai, verifikasi alamat email Anda dengan mengklik tautan yang kami kirimkan melalui email. Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkan tautan verifikasi ulang.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg">
                {{ __('Tautan verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.') }}
            </div>
        @endif

        <div class="mt-4 flex flex-col gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-lg transition">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>