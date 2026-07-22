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
            <h1 class="text-white text-2xl font-medium leading-snug mb-3">Buat kata<br>sandi baru</h1>
            <p class="text-white/60 text-sm leading-relaxed">Buat kata sandi baru untuk akun Anda. Pastikan kata sandi kuat dan mudah diingat.</p>
        </div>

        <div class="relative z-10">
            <p class="text-white/50 text-xs mb-3">Ingat kata sandi Anda?</p>
            <a href="{{ route('login') }}" class="inline-block px-5 py-2 rounded-lg border border-white/40 text-white text-sm font-medium hover:bg-white/10 transition">
                Kembali masuk
            </a>
        </div>
    </div>

    {{-- Panel Kanan --}}
    <div class="flex-1 bg-white p-10 flex flex-col justify-center">
        <h2 class="text-xl font-medium text-gray-800 mb-1">Reset kata sandi</h2>
        <p class="text-sm text-gray-400 mb-7">Buat kata sandi baru untuk akun Anda</p>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" class="text-xs uppercase tracking-widest text-gray-500 font-medium" />
                <div class="relative mt-1">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <x-text-input id="email" class="block w-full pl-9" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="grid grid-cols-2 gap-4 mb-2">
                <div>
                    <x-input-label for="password" :value="__('Kata Sandi Baru')" class="text-xs uppercase tracking-widest text-gray-500 font-medium" />
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <x-text-input id="password" class="block w-full pl-9" type="password" name="password" required autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>
                <div>
                    <x-input-label for="password_confirmation" :value="__('Konfirmasi')" class="text-xs uppercase tracking-widest text-gray-500 font-medium" />
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </span>
                        <x-text-input id="password_confirmation" class="block w-full pl-9" type="password" name="password_confirmation" required autocomplete="new-password" />
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>
            </div>

            <button type="submit" class="w-full mt-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-lg transition">
                Reset Kata Sandi
            </button>

            <p class="text-center text-xs text-gray-400 mt-5">
                Ingat kata sandi?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Kembali masuk</a>
            </p>
        </form>
    </div>
</x-guest-layout>