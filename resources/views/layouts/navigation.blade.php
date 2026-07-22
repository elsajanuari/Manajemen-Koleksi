@once
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .welcome-logo {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 1.125rem;
            color: #1f2937;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            white-space: nowrap;
        }
        .welcome-logo .logo-icon { color: #2563eb; font-size: 1.125rem; flex-shrink: 0; }
        .welcome-logo .logo-mk { color: #2563eb; }
        .welcome-logo .badge {
            display: inline-block;
            background: #2563eb;
            color: #ffffff;
            font-size: 0.6rem;
            padding: 2px 10px;
            border-radius: 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            flex-shrink: 0;
        }
        @media (max-width: 768px) {
            .welcome-logo { font-size: 1rem; }
            .welcome-logo .logo-icon { font-size: 1rem; }
        }
        @media (max-width: 480px) {
            .welcome-logo { font-size: 0.875rem; }
            .welcome-logo .logo-icon { font-size: 0.875rem; }
        }
    </style>
@endonce

<nav x-data="{ mobileOpen: false, profileOpen: false }" class="bg-white border-b border-gray-200 shadow-sm font-sans sticky top-0 z-50">
    <div class="w-full px-5 sm:px-8 lg:px-12 xl:px-[60px]">
        <div class="flex justify-between items-center h-16 w-full gap-4">
            {{-- Logo --}}
            <div class="flex items-center flex-shrink-0 min-w-0">
                <a href="{{ route('landing') }}" class="welcome-logo">
                    <i class="fas fa-landmark logo-icon"></i>
                    MUSEUM <span class="logo-mk">MK</span>
                    <span class="badge">LESMANA</span>
                </a>
            </div>

            {{-- Desktop Navigation --}}
            <div class="flex items-center gap-1 flex-shrink-0 ml-auto">
                @if(auth()->user()->role !== 'pengelola')
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('landing') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('landing') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Home
                            </span>
                        </a>

                        <a href="{{ route('gallery') }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('gallery*') ? 'text-blue-600 bg-blue-50' : '' }}">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Galeri
                            </span>
                        </a>

                        @if(auth()->user()->role == 'pengguna')
                            <a href="{{ route('tiket.index') }}"
                               class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('tiket.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    Tiket
                                </span>
                            </a>

                            <a href="{{ route('penyewaan.index') }}"
                               class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('penyewaan.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Penyewaan
                                </span>
                            </a>

                            <a href="{{ route('pembelian.index') }}"
                               class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('pembelian.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Pembelian
                                </span>
                            </a>
                        @endif
                    </div>
                @elseif(auth()->user()->role == 'pengelola' && request()->routeIs('landing'))  
                        <div class="hidden md:flex items-center gap-1">
                            <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50' : '' }}">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    Dashboard
                                </span>
                            </a>
                        </div>
                @endif

                @auth
                    <div class="relative ml-2 @if(auth()->user()->role !== 'pengelola') hidden md:block @endif">
                        @include('layouts.partials.notification-dropdown')
                    </div>
                @endauth

                {{-- Profile Dropdown --}}
                <div class="relative ml-2 @if(auth()->user()->role !== 'pengelola') hidden md:block @endif" @click.away="profileOpen = false">
                    <button @click="profileOpen = !profileOpen"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700 hidden lg:block">
                            {{ auth()->user()->name }}
                        </span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="profileOpen"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-50">
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile Menu Button --}}
            @if(auth()->user()->role !== 'pengelola')
            <div class="flex items-center flex-shrink-0 md:hidden">
                <button @click="mobileOpen = !mobileOpen"
                        class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- Mobile Menu --}}
    @if(auth()->user()->role !== 'pengelola')
    <div x-show="mobileOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="md:hidden border-t border-gray-200 bg-white px-5 sm:px-8 lg:px-12 xl:px-[60px] py-3 space-y-1">

        @if(auth()->user()->role !== 'pengelola')
            <a href="{{ route('landing') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('landing') ? 'text-blue-600 bg-blue-50' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Home
            </a>

            <a href="{{ route('gallery') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('gallery*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Galeri
            </a>
        @endif

        @if(auth()->user()->role == 'pengguna')
            <a href="{{ route('tiket.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('tiket.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
                Tiket
            </a>

            <a href="{{ route('penyewaan.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('penyewaan.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Penyewaan
            </a>

            <a href="{{ route('pembelian.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('pembelian.*') ? 'text-blue-600 bg-blue-50' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Pembelian
            </a>
        @endif

        <div class="border-t border-gray-200 my-2"></div>

        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profil
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
    @endif
</nav>