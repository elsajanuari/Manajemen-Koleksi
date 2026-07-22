<x-app-layout>
    @php
        $snapJsBase = config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp

    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('pemesanan-tiket.index') }}" class="hover:text-gray-700 transition">Pemesanan</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('pemesanan-tiket.show', $pemesananTiket) }}" class="hover:text-gray-700 transition">Detail</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 font-medium">Pembayaran</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-gray-900">Pembayaran Tiket</h1>
                        <p class="text-sm text-gray-500 mt-0.5">
                            No. Pemesanan: <span class="font-mono font-semibold text-gray-700">#{{ str_pad($pemesananTiket->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6">

                {{-- Ringkasan --}}
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 sm:p-5 mb-6">
                    <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Ringkasan Pemesanan</h2>
                    <div class="space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tiket</span>
                            <span class="font-medium text-gray-800">{{ $pemesananTiket->ticket->nama_tiket }}</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-2.5">
                            <span class="text-gray-500">Tanggal Kunjungan</span>
                            <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($pemesananTiket->tanggal_pemesanan)->locale('id')->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-2.5">
                            <span class="text-gray-500">Jumlah Tiket</span>
                            <span class="font-medium text-gray-800">{{ $pemesananTiket->jumlah_tiket }} tiket</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-2.5">
                            <span class="text-gray-500">Harga per Tiket</span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($pemesananTiket->ticket->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex flex-wrap justify-between gap-1 bg-white rounded-lg p-3 border border-gray-200 -mx-1 mt-2">
                            <span class="font-bold text-gray-800">Total Pembayaran</span>
                            <span class="font-bold text-lg sm:text-xl text-green-600">Rp {{ number_format($pemesananTiket->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Error Midtrans --}}
                @if(! config('midtrans.client_key') || ! config('midtrans.server_key'))
                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3.5 text-sm text-amber-700 flex items-start gap-2 mb-4">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <p class="font-medium">Konfigurasi Midtrans Belum Siap</p>
                            <p class="text-amber-600 text-xs mt-0.5">
                                Tambahkan <code class="bg-amber-100 px-1.5 py-0.5 rounded text-xs">MIDTRANS_CLIENT_KEY</code> dan
                                <code class="bg-amber-100 px-1.5 py-0.5 rounded text-xs">MIDTRANS_SERVER_KEY</code> pada file <code class="bg-amber-100 px-1.5 py-0.5 rounded text-xs">.env</code>.
                            </p>
                        </div>
                    </div>
                @endif

                {{-- Error Payment --}}
                <div id="pay-error" class="hidden mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700 flex items-start gap-2"></div>

                {{-- Tombol Bayar --}}
                <button type="button" id="btn-bayar-midtrans"
                        class="w-full flex items-center justify-center rounded-lg bg-green-600 px-6 py-3.5 text-sm font-semibold text-white hover:bg-green-700 transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Bayar Sekarang
                </button>

                {{-- Footer Info --}}
                <div class="mt-4 flex items-start gap-2 text-xs text-gray-400 bg-gray-50 rounded-lg p-3">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p>
                        Anda akan diarahkan ke halaman pembayaran aman Midtrans.
                        Setelah selesai, status akan diperbarui otomatis. Jika belum berubah, segarkan halaman detail pemesanan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if(config('midtrans.client_key'))
        <script src="{{ $snapJsBase }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif

    <script>
        (function () {
            const btn = document.getElementById('btn-bayar-midtrans');
            const errBox = document.getElementById('pay-error');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const snapTokenUrl = @json(route('pemesanan-tiket.midtrans.snap-token', $pemesananTiket));
            const syncUrl = @json(route('pemesanan-tiket.midtrans.sync-status', $pemesananTiket));
            const afterUrl = @json(route('pemesanan-tiket.show', $pemesananTiket));

            function showErr(msg) {
                errBox.innerHTML = `
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>${msg}</span>
                `;
                errBox.classList.remove('hidden');
            }

            function syncThenRedirect() {
                return fetch(syncUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                }).finally(function () {
                    window.location.href = afterUrl;
                });
            }

            if (!btn) return;

            btn.addEventListener('click', function () {
                errBox.classList.add('hidden');
                if (typeof snap === 'undefined') {
                    showErr('Skrip Midtrans gagal dimuat. Periksa koneksi internet atau konfigurasi MIDTRANS_IS_PRODUCTION.');
                    return;
                }
                btn.disabled = true;
                fetch(snapTokenUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                })
                    .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, body: j }; }); })
                    .then(function (res) {
                        if (!res.ok || !res.body.snap_token) {
                            throw new Error(res.body.message || 'Tidak dapat memulai pembayaran.');
                        }
                        snap.pay(res.body.snap_token, {
                            onSuccess: function () { syncThenRedirect(); },
                            onPending: function () { syncThenRedirect(); },
                            onError: function () {
                                btn.disabled = false;
                                showErr('Pembayaran dibatalkan atau gagal.');
                            },
                            onClose: function () {
                                btn.disabled = false;
                            },
                        });
                    })
                    .catch(function (e) {
                        btn.disabled = false;
                        showErr(e.message || 'Terjadi kesalahan.');
                    });
            });
        })();
    </script>
</x-app-layout>