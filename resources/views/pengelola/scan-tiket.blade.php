<x-app-layout>
    <div class="max-w-3xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('pengelola.verifikasi-tiket.form') }}" class="hover:text-gray-700 transition">Verifikasi Tiket</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 font-medium">Detail Tiket</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Detail Tiket</h1>
                            <p class="text-sm text-gray-500 mt-0.5">
                                Pemesanan: <span class="font-mono font-semibold text-gray-700">#{{ str_pad((string) ($pemesanan->id ?? 0), 5, '0', STR_PAD_LEFT) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @if($detailPengunjung->tiket_terpakai_at)
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-amber-100 text-amber-700">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Sudah Discan
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-emerald-100 text-emerald-700">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Belum Discan
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6 space-y-6">

                {{-- Alert --}}
                @if(session('success'))
                    <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3.5 text-sm text-green-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3.5 text-sm text-amber-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        {{ session('warning') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Grid Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Nama Tiket</span>
                            <span class="text-sm font-medium text-gray-800">
                                @if($pemesanan && $pemesanan->ticket)
                                    {{ $pemesanan->ticket->nama_tiket }}
                                @else
                                    <span class="text-red-500">[Tiket Dihapus]</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="text-sm text-gray-500">Tanggal Kunjungan</span>
                            <span class="text-sm font-medium text-gray-800">
                                @if($pemesanan && $pemesanan->tanggal_pemesanan)
                                    {{ $pemesanan->tanggal_pemesanan->locale('id')->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="text-sm text-gray-500">Tiket Ke</span>
                            <span class="text-sm font-medium text-gray-800">
                                {{ $detailPengunjung->urutan_pengunjung }} dari {{ $pemesanan?->jumlah_tiket ?? 0 }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Pemesan</span>
                            <span class="text-sm font-medium text-gray-800">
                                {{ $pemesanan?->user?->name ?? 'Tidak Diketahui' }}
                            </span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="text-sm text-gray-500">Nama Pengunjung</span>
                            <span class="text-sm font-medium text-gray-800">{{ $detailPengunjung->getDisplayName() }}</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="text-sm text-gray-500">Status</span>
                            <span class="text-sm font-medium {{ $detailPengunjung->tiket_terpakai_at ? 'text-amber-600' : 'text-emerald-600' }}">
                                {{ $detailPengunjung->tiket_terpakai_at ? 'Sudah Digunakan' : 'Belum Digunakan' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Token --}}
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Token Verifikasi</p>
                    <div class="bg-gray-100 rounded-xl px-4 py-3 font-mono text-xs text-gray-700 break-all border border-gray-200">
                        {{ $detailPengunjung->tiket_verifikasi_token ?? 'Token belum di-generate' }}
                    </div>
                </div>

                {{-- Kontak --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Email</p>
                        <p class="mt-1 text-sm font-medium text-gray-800">{{ $detailPengunjung->email ?? '-' }}</p>
                    </div>
                    <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Nomor Ponsel</p>
                        <p class="mt-1 text-sm font-medium text-gray-800">{{ $detailPengunjung->nomor_ponsel ?? '-' }}</p>
                    </div>
                </div>

                @if($detailPengunjung->tipe_pengunjung === 'kelompok' && $detailPengunjung->daftar_anggota)
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Anggota Kelompok</p>
                        <div class="bg-gray-50/50 rounded-xl px-4 py-3 border border-gray-200">
                            <div class="flex flex-wrap gap-1.5">
                                @php
                                    $anggotaList = is_string($detailPengunjung->daftar_anggota) ? json_decode($detailPengunjung->daftar_anggota, true) : ($detailPengunjung->daftar_anggota ?? []);
                                @endphp
                                @forelse($anggotaList as $anggota)
                                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700">
                                        {{ $anggota }}
                                    </span>
                                @empty
                                    <span class="text-gray-400">-</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif

                @if($detailPengunjung->tiket_terpakai_at)
                    <div class="bg-amber-50 rounded-xl p-4 border border-amber-200">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-amber-800">Tiket ini sudah digunakan</p>
                                <p class="text-xs text-amber-600">{{ \Carbon\Carbon::parse($detailPengunjung->tiket_terpakai_at)->locale('id')->translatedFormat('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    @if(!$detailPengunjung->tiket_terpakai_at)
                        <form action="{{ route('pengelola.scan-tiket.pakai', ['token' => $token]) }}" 
                              method="POST" 
                              class="flex-1"
                              onsubmit="return confirm('Tandai tiket ini sebagai sudah digunakan? Tindakan ini tidak dapat dibatalkan.');">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-sm font-medium text-white hover:bg-blue-700 transition shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Tandai Sudah Digunakan
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('pengelola.verifikasi-tiket.form') }}" 
                       class="flex-1 flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Verifikasi Tiket Lain
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>