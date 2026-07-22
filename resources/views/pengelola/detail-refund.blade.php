<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('pengelola.riwayat-pemesanan.index') }}" class="hover:text-gray-700 transition">Riwayat Pemesanan</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 font-medium">Detail Refund</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13h.01M9 13h.01"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Detail Refund</h1>
                            <p class="text-sm text-gray-500 mt-0.5">
                                Pemesanan: <span class="font-mono font-semibold text-gray-700">#{{ str_pad($pemesananTiket->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Proses Refund
                        </span>
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

                @if(session('error'))
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700 flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Grid Informasi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Kolom Kiri: Informasi Pemesanan --}}
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">1</span>
                            Informasi Pemesanan
                        </h3>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Pemesan</span>
                                <span class="text-sm font-medium text-gray-800">{{ $pemesananTiket->user->name }}</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-3">
                                <span class="text-sm text-gray-500">Email</span>
                                <span class="text-sm font-medium text-gray-800">{{ $pemesananTiket->user->email }}</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-3">
                                <span class="text-sm text-gray-500">Jenis Tiket</span>
                                <span class="text-sm font-medium text-gray-800">{{ $pemesananTiket->ticket->nama_tiket }}</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-3">
                                <span class="text-sm text-gray-500">Jumlah Tiket</span>
                                <span class="text-sm font-medium text-gray-800">{{ $pemesananTiket->jumlah_tiket }} tiket</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-3">
                                <span class="text-sm text-gray-500">Tanggal Kunjungan</span>
                                <span class="text-sm font-medium text-gray-800">{{ $pemesananTiket->tanggal_pemesanan->locale('id')->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Informasi Refund --}}
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-xs font-bold">2</span>
                            Informasi Refund
                        </h3>
                        <div class="bg-red-50/50 rounded-xl p-4 border border-red-200 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Total Refund</span>
                                <span class="text-lg font-bold text-red-600">Rp {{ number_format($pemesananTiket->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-t border-red-200 pt-3">
                                <span class="text-sm text-gray-600">Status</span>
                                <span class="text-sm font-medium text-blue-600">Proses Pengembalian</span>
                            </div>
                            @if($pemesananTiket->refund_requested_at)
                                <div class="flex justify-between border-t border-red-200 pt-3">
                                    <span class="text-sm text-gray-600">Diajukan</span>
                                    <span class="text-sm font-medium text-gray-800">{{ $pemesananTiket->refund_requested_at->locale('id')->translatedFormat('d F Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Informasi Rekening --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">3</span>
                        Rekening Tujuan Refund
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Nama Bank</p>
                            <p class="mt-1.5 text-base font-semibold text-gray-800">{{ $pemesananTiket->nama_bank_refund ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Atas Nama</p>
                            <p class="mt-1.5 text-base font-semibold text-gray-800">{{ $pemesananTiket->atas_nama_refund ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Nomor Rekening</p>
                            <p class="mt-1.5 text-base font-mono font-semibold text-gray-800">{{ $pemesananTiket->no_rekening_refund ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Catatan --}}
                @if($pemesananTiket->catatan)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center text-xs font-bold">4</span>
                            Catatan Pemesan
                        </h3>
                        <div class="bg-yellow-50/50 rounded-xl p-4 border border-yellow-200">
                            <p class="text-sm text-gray-700">{{ $pemesananTiket->catatan }}</p>
                        </div>
                    </div>
                @endif

                {{-- Upload Bukti Refund --}}
                @if($pemesananTiket->status === 'proses_pembatalan')
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">5</span>
                            Upload Bukti Transfer Refund
                        </h3>
                        <div class="bg-gray-50/50 rounded-xl p-6 border-2 border-dashed border-gray-300 hover:border-blue-400 transition">
                            <form action="{{ route('pengelola.riwayat-pemesanan.kirim', $pemesananTiket) }}"
                                  method="POST"
                                  enctype="multipart/form-data"
                                  class="space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih File Bukti Transfer
                                    </label>
                                    <div class="relative">
                                        <input type="file"
                                               name="bukti_pengembalian"
                                               accept="image/*,application/pdf"
                                               required
                                               id="refund-file"
                                               class="absolute inset-0 w-full h-full cursor-pointer opacity-0 z-10"
                                               onchange="updateRefundFileName(this)">
                                        <div id="refund-file-label"
                                             class="flex items-center justify-center gap-3 rounded-lg border-2 border-dashed border-gray-300 bg-white px-6 py-6 text-gray-500 hover:border-blue-400 hover:bg-blue-50 transition cursor-pointer">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                            </svg>
                                            <div class="text-center">
                                                <p class="font-medium">Klik untuk pilih file</p>
                                                <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF • Maks 5MB</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="file-selected-info" class="mt-3 hidden">
                                        <div class="flex items-center gap-2 text-sm text-green-600 bg-green-50 rounded-lg px-4 py-2 border border-green-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span id="file-name-display">File telah dipilih</span>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit"
                                        class="inline-flex items-center justify-center w-full md:w-auto rounded-lg bg-blue-600 px-8 py-3 text-sm font-medium text-white hover:bg-blue-700 transition shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Kirim Bukti Refund
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Bukti Terkirim --}}
                @if($pemesananTiket->status === 'pengembalian_berhasil' && $pemesananTiket->bukti_pengembalian)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold">6</span>
                            Bukti Refund Terkirim
                        </h3>
                        <div class="bg-emerald-50/50 rounded-xl p-5 border border-emerald-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-emerald-800">Refund Berhasil Diproses</p>
                                    <p class="text-sm text-emerald-600">{{ $pemesananTiket->refund_completed_at?->locale('id')->translatedFormat('d F Y H:i') }}</p>
                                </div>
                            </div>
                            <button onclick="openRefundModal('{{ asset('storage/' . $pemesananTiket->bukti_pengembalian) }}')"
                                    class="inline-flex items-center rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Bukti
                            </button>
                        </div>
                    </div>
                @endif

                {{-- Tombol Kembali --}}
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('pengelola.riwayat-pemesanan.index') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div id="refundModal" 
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4 transition-opacity duration-300"
         onclick="closeRefundModal(event)">
        <div class="relative max-w-4xl w-full mx-auto" onclick="event.stopPropagation()">
            <button onclick="closeRefundModal()"
                    class="absolute -top-12 right-0 text-white hover:text-gray-300 transition text-4xl font-light">
                &times;
            </button>
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <svg class="w-5 h-5 inline mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Bukti Transfer Refund
                    </h3>
                    <button onclick="closeRefundModal()" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="p-4 bg-gray-50 flex items-center justify-center" style="min-height: 400px;">
                    <img id="refundImage" 
                         src="" 
                         alt="Bukti Transfer Refund"
                         class="max-w-full max-h-[70vh] rounded-lg shadow-md object-contain"
                         style="display: none;">
                    <div id="refundLoading" class="flex flex-col items-center justify-center py-12">
                        <svg class="w-12 h-12 text-gray-300 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <p class="mt-3 text-sm text-gray-500">Memuat gambar...</p>
                    </div>
                </div>
                <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <span class="text-xs text-gray-500">Klik di luar modal untuk menutup</span>
                    <div class="flex gap-2">
                        <button onclick="closeRefundModal()"
                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition text-sm font-medium">
                            Tutup
                        </button>
                        <a id="refundDownloadLink" 
                           href="#" 
                           target="_blank"
                           class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Buka di Tab Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateRefundFileName(input) {
            const label = document.getElementById('refund-file-label');
            const info = document.getElementById('file-selected-info');
            const nameDisplay = document.getElementById('file-name-display');

            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);

                label.innerHTML = `
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-center">
                        <p class="font-medium text-green-600">${fileName}</p>
                        <p class="text-xs text-gray-400">${fileSize} MB</p>
                    </div>
                `;
                label.classList.add('border-green-400', 'bg-green-50');
                nameDisplay.textContent = fileName + ' (' + fileSize + ' MB)';
                info.classList.remove('hidden');
            }
        }

        function openRefundModal(imageUrl) {
            const modal = document.getElementById('refundModal');
            const image = document.getElementById('refundImage');
            const loading = document.getElementById('refundLoading');
            const downloadLink = document.getElementById('refundDownloadLink');

            image.style.display = 'none';
            loading.style.display = 'flex';
            downloadLink.href = imageUrl;

            const img = new Image();
            img.onload = function() {
                image.src = imageUrl;
                image.style.display = 'block';
                loading.style.display = 'none';
            };
            img.onerror = function() {
                loading.innerHTML = `
                    <svg class="w-12 h-12 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-3 text-sm text-red-500">Gagal memuat gambar.</p>
                    <a href="${imageUrl}" target="_blank" class="mt-2 text-blue-500 hover:underline text-sm">Buka di tab baru</a>
                `;
            };
            img.src = imageUrl;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeRefundModal(event) {
            if (event && event.target !== document.getElementById('refundModal')) return;
            const modal = document.getElementById('refundModal');
            const image = document.getElementById('refundImage');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            image.src = '';
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeRefundModal();
        });

        const style = document.createElement('style');
        style.textContent = `
            #refundModal { backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }
            #refundModal .max-w-4xl { animation: modalFadeIn 0.3s ease-out; }
            @keyframes modalFadeIn {
                from { opacity: 0; transform: scale(0.95) translateY(10px); }
                to { opacity: 1; transform: scale(1) translateY(0); }
            }
            .animate-spin { animation: spin 1s linear infinite; }
            @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>