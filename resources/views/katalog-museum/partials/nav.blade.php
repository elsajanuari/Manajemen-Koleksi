<header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-md">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('landing') }}" class="font-['Playfair_Display'] text-lg font-semibold tracking-tight text-[#5B79B6] sm:text-xl">
            Museum MK Lesmana
        </a>
        <nav class="hidden items-center gap-6 text-sm font-medium text-slate-600 md:flex">
            <a href="{{ route('landing') }}" class="transition hover:text-[#5B79B6]">Beranda</a>
            <a href="{{ route('katalog-museum.index') }}" class="transition {{ request()->routeIs('katalog-museum.*') ? 'text-[#5B79B6]' : '' }} hover:text-[#5B79B6]">Katalog Koleksi</a>
            <a href="{{ route('gallery') }}" class="transition hover:text-[#5B79B6]">Galeri</a>
            <a href="{{ auth()->check() && auth()->user()->role === 'pengguna' 
                        ? route('pembelian.index') 
                        : (auth()->check() ? route('katalog-museum.index') : route('login')) }}" 
            class="transition {{ request()->routeIs('pembelian.*') ? 'text-[#5B79B6]' : '' }} hover:text-[#5B79B6]">
                Pembelian
            </a>
        </nav>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('profile.edit') }}" class="hidden text-sm font-medium text-slate-600 hover:text-[#5B79B6] sm:inline">Profil</a>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:inline">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-slate-600 hover:text-[#5B79B6]">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="rounded-full px-4 py-2 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50">Masuk</a>
                <a href="{{ route('register') }}" class="rounded-full bg-[#5B79B6] px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-[#4a6499]">Daftar</a>
            @endauth
        </div>
    </div>
</header>
