<!-- FLOATING HAMBURGER -->
<button
    x-show="!sidebarOpen"
    @click="sidebarOpen = true"
    class="fixed top-20 left-2 z-50 p-2 rounded-lg bg-indigo-500 text-white shadow-md hover:bg-indigo-600 transition"
>
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
</button>

@php
    $sidebarActiveMenu = request()->routeIs('manajemen-koleksi.*') || request()->routeIs('tickets.dashboard')
        ? 'dashboard'
        : (request()->routeIs('koleksi.*') || request()->routeIs('kondisi.*')
            ? 'koleksi'
            : (request()->routeIs('jadwal-konservasi.*') || request()->routeIs('konservasi.tindakan.*')
                ? 'konservasi'
                : ((request()->routeIs('tickets.*') && !request()->routeIs('tickets.dashboard'))
                    || request()->routeIs('pengelola.verifikasi-tiket.*')
                    || request()->routeIs('pengelola.scan-tiket')
                    || request()->routeIs('pengelola.riwayat-tiket.*')
                    || request()->routeIs('pengelola.riwayat-pemesanan.*')
                    ? 'tiket'
                    : null)));
@endphp

<!-- SIDEBAR -->
<aside
    class="fixed top-16 left-0 z-40 w-64 h-[calc(100vh-4rem)] bg-[#F8FAFC] border-r border-gray-200 shadow-md transition-all duration-300 ease-in-out flex flex-col"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    x-data="{
        activeMenu: {{ json_encode($sidebarActiveMenu) }}
    }"
>

    <!-- CLOSE BUTTON -->
    <div class="flex items-center justify-end p-4 border-b border-gray-200">
        <button
            @click="sidebarOpen = false"
            class="p-2 rounded-lg hover:bg-gray-200 transition"
        >
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- MENU -->
    <nav class="flex-1 px-4 py-4 space-y-3 overflow-y-auto">

        <!-- DASHBOARD -->
        <div class="space-y-1">
            <button
                @click="activeMenu = activeMenu === 'dashboard' ? null : 'dashboard'"
                class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('manajemen-koleksi.*') || request()->routeIs('tickets.dashboard')
                    ? 'bg-indigo-600 text-white shadow'
                    : 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200' }}"
            >
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/>
                    </svg>
                    Dashboard
                </span>
                <svg class="w-4 h-4 transition-transform"
                    :class="activeMenu === 'dashboard' ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div class="space-y-1 px-2 overflow-hidden transition-all duration-300"
                x-show="activeMenu === 'dashboard'"
                x-collapse
            >
                <a
                    href="{{ route('manajemen-koleksi.index') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('manajemen-koleksi.*')
                        ? 'bg-indigo-500 text-white'
                        : 'bg-white text-slate-700 hover:bg-indigo-50' }}"
                >
                    Manajemen Koleksi
                </a>
                <a
                    href="{{ route('tickets.dashboard') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('tickets.dashboard')
                        ? 'bg-indigo-500 text-white'
                        : 'bg-white text-slate-700 hover:bg-indigo-50' }}"
                >
                    Tiket
                </a>
            </div>
        </div>

        <!-- KOLEKSI -->
        <div class="space-y-1">
            <button
                @click="activeMenu = activeMenu === 'koleksi' ? null : 'koleksi'"
                class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('koleksi.*') || request()->routeIs('kondisi.*')
                    ? 'bg-indigo-600 text-white shadow'
                    : 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200' }}"
            >
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Koleksi
                </span>
                <svg class="w-4 h-4 transition-transform"
                    :class="activeMenu === 'koleksi' ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div class="space-y-1 px-2 overflow-hidden transition-all duration-300"
                x-show="activeMenu === 'koleksi'"
                x-collapse
            >
                <a
                    href="{{ route('koleksi.index') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('koleksi.index')
                        ? 'bg-indigo-500 text-white'
                        : 'bg-white text-slate-700 hover:bg-indigo-50' }}"
                >
                    Pengelolaan Koleksi
                </a>
                <a
                    href="{{ route('kondisi.index') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('kondisi.*')
                        ? 'bg-indigo-500 text-white'
                        : 'bg-white text-slate-700 hover:bg-indigo-50' }}"
                >
                    Kondisi Koleksi
                </a>
            </div>
        </div>

        <!-- KONSERVASI -->
        <div class="space-y-1">
            <button
                @click="activeMenu = activeMenu === 'konservasi' ? null : 'konservasi'"
                class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('jadwal-konservasi.*') || request()->routeIs('konservasi.tindakan.*')
                    ? 'bg-indigo-600 text-white shadow'
                    : 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200' }}"
            >
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    Konservasi
                </span>
                <svg class="w-4 h-4 transition-transform"
                    :class="activeMenu === 'konservasi' ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div class="space-y-1 px-2 overflow-hidden transition-all duration-300"
                x-show="activeMenu === 'konservasi'"
                x-collapse
            >
                <a
                    href="{{ route('jadwal-konservasi.index') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('jadwal-konservasi.*')
                        ? 'bg-indigo-500 text-white'
                        : 'bg-white text-slate-700 hover:bg-indigo-50' }}"
                >
                    Jadwal Konservasi
                </a>
                <a
                    href="{{ route('konservasi.tindakan.index') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('konservasi.tindakan.*')
                        ? 'bg-indigo-500 text-white'
                        : 'bg-white text-slate-700 hover:bg-indigo-50' }}"
                >
                    Tindakan Konservasi
                </a>
            </div>
        </div>

        <!-- PENGAJUAN PENYEWAAN -->
        <a
            href="{{ route('pengelola.penyewaan.index') }}"
            class="block px-4 py-3 rounded-lg text-sm font-medium transition
            {{ request()->routeIs('pengelola.penyewaan.*')
                ? 'bg-indigo-600 text-white shadow'
                : 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200' }}"
        >
            <span class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Pengajuan Penyewaan
            </span>
        </a>

        @if(auth()->user()->role === 'pengelola')
            <a
                href="{{ route('pengelola.pembelian.index') }}"
                class="block px-4 py-3 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('pengelola.pembelian.*') && !request()->routeIs('pengelola.pembelian.transactions.*')
                    ? 'bg-indigo-600 text-white shadow'
                    : 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200' }}"
            >
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Pengajuan Pembelian
                </span>
            </a>
            <a
                href="{{ route('pengelola.transactions.dashboard') }}"
                class="block px-4 py-3 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('pengelola.transactions.dashboard')
                    ? 'bg-indigo-600 text-white shadow'
                    : 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200' }}"
            >
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Dashboard Transaksi
                </span>
            </a>
            <a
                href="{{ route('pengelola.pembelian.transactions.index') }}"
                class="block px-4 py-3 rounded-lg text-sm font-medium transition
                {{ request()->routeIs('pengelola.pembelian.transactions.*')
                    ? 'bg-indigo-600 text-white shadow'
                    : 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200' }}"
            >
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Riwayat Transaksi
                </span>
            </a>
        @endif

        <!-- TIKET -->
        <div class="space-y-1">
            <button
                @click="activeMenu = activeMenu === 'tiket' ? null : 'tiket'"
                class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium transition
                {{ (request()->routeIs('tickets.*') && !request()->routeIs('tickets.dashboard')) || request()->routeIs('pengelola.verifikasi-tiket.*') || request()->routeIs('pengelola.scan-tiket') || request()->routeIs('pengelola.riwayat-tiket.*') || request()->routeIs('pengelola.riwayat-pemesanan.*')
                    ? 'bg-indigo-600 text-white shadow'
                    : 'bg-indigo-100 text-indigo-800 hover:bg-indigo-200' }}"
            >
                <span class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    Tiket
                </span>
                <svg class="w-4 h-4 transition-transform"
                    :class="activeMenu === 'tiket' ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div class="space-y-1 px-2 overflow-hidden transition-all duration-300"
                x-show="activeMenu === 'tiket'"
                x-collapse
            >
                <a
                    href="{{ route('tickets.data-pengunjung.index') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('tickets.data-pengunjung.*')
                        ? 'bg-indigo-500 text-white'
                        : 'bg-white text-slate-700 hover:bg-indigo-50' }}"
                >
                    Data Pengunjung
                </a>
                <a
                    href="{{ route('tickets.index') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('tickets.index') || request()->routeIs('tickets.create') || request()->routeIs('tickets.edit') || request()->routeIs('tickets.show')
                        ? 'bg-indigo-500 text-white'
                        : 'bg-white text-slate-700 hover:bg-indigo-50' }}"
                >
                    Pengelolaan Tiket
                </a>
                <a
                    href="{{ route('pengelola.verifikasi-tiket.form') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('pengelola.verifikasi-tiket.*') || request()->routeIs('pengelola.scan-tiket')
                        ? 'bg-indigo-500 text-white'
                        : 'bg-white text-slate-700 hover:bg-indigo-50' }}"
                >
                    Verifikasi Tiket
                </a>
                <a
                    href="{{ route('pengelola.riwayat-tiket.index') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('pengelola.riwayat-tiket.*')
                        ? 'bg-indigo-500 text-white'
                        : 'bg-white text-slate-700 hover:bg-indigo-50' }}"
                >
                    Riwayat Tiket
                </a>
                <a
                    href="{{ route('pengelola.riwayat-pemesanan.index') }}"
                    class="block px-4 py-2 rounded-lg text-sm font-medium transition
                    {{ request()->routeIs('pengelola.riwayat-pemesanan.*')
                        ? 'bg-indigo-500 text-white'
                        : 'bg-white text-slate-700 hover:bg-indigo-50' }}"
                >
                    Riwayat Pemesanan
                </a>

                <div class="border-t border-gray-200 my-2"></div>

                <!-- LAPORAN (Sub Parent) -->
                <div class="space-y-1"
                    x-data="{ laporanOpen: {{ request()->routeIs('tickets.laporan.*') ? 'true' : 'false' }} }"
                >
                    <button
                        @click="laporanOpen = !laporanOpen"
                        class="w-full flex items-center justify-between px-4 py-2 rounded-lg text-sm font-medium transition
                        {{ request()->routeIs('tickets.laporan.*')
                            ? 'bg-indigo-500 text-white'
                            : 'bg-white text-slate-700 hover:bg-indigo-50' }}"
                    >
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/>
                            </svg>
                            Laporan
                        </span>
                        <svg class="w-3 h-3 transition-transform"
                            :class="laporanOpen ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div class="space-y-1 px-2 overflow-hidden transition-all duration-300"
                        x-show="laporanOpen"
                        x-collapse
                    >
                        <a
                            href="{{ route('tickets.laporan.pendapatan') }}"
                            class="block px-4 py-1.5 rounded-lg text-xs font-medium transition
                            {{ request()->routeIs('tickets.laporan.pendapatan')
                                ? 'bg-indigo-400 text-white'
                                : 'bg-white text-slate-600 hover:bg-indigo-50' }}"
                        >
                            Laporan Pendapatan
                        </a>
                        <a
                            href="{{ route('tickets.laporan.penjualan') }}"
                            class="block px-4 py-1.5 rounded-lg text-xs font-medium transition
                            {{ request()->routeIs('tickets.laporan.penjualan')
                                ? 'bg-indigo-400 text-white'
                                : 'bg-white text-slate-600 hover:bg-indigo-50' }}"
                        >
                            Laporan Penjualan
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Divider Sebelum Logout -->
        <div class="border-t border-gray-200 my-3"></div>

    </nav>

    <!-- LOGOUT -->
    <div class="p-4 border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center space-x-3 px-4 py-3 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors duration-200 text-sm font-medium">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
