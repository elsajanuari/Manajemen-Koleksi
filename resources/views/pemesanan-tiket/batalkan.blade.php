<x-app-layout>
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
            <span class="text-gray-700 font-medium">
                @if($pemesananTiket->isPaid())
                    Ajukan Refund
                @else
                    Batalkan Pemesanan
                @endif
            </span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-red-50 to-rose-50 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-gray-900">
                            @if($pemesananTiket->isPaid())
                                Ajukan Pembatalan & Refund
                            @else
                                Batalkan Pemesanan
                            @endif
                        </h1>
                        <p class="text-sm text-gray-500 mt-0.5">
                            No. Pemesanan: <span class="font-mono font-semibold text-gray-700">#{{ str_pad($pemesananTiket->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6">

                {{-- Error Summary --}}
                <div id="validation-errors" class="hidden mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="font-medium">Mohon perbaiki kesalahan berikut:</p>
                            <ul id="error-list" class="list-disc list-inside mt-1 space-y-0.5"></ul>
                        </div>
                    </div>
                </div>

                {{-- Error Laravel --}}
                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3.5 text-sm text-red-700">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="font-medium">Terjadi kesalahan:</p>
                                <ul class="list-disc list-inside mt-1 space-y-0.5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5">

                    {{-- LEFT COLUMN --}}
                    <div class="lg:col-span-2 space-y-5">

                        @if($pemesananTiket->isPaid())
                            {{-- ===== SUDAH BAYAR: Form Refund ===== --}}

                            {{-- Peringatan --}}
                            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-amber-800">Perhatian</p>
                                    <p class="text-sm text-amber-700 mt-0.5">Pembatalan akan memproses pengembalian dana ke rekening yang Anda daftarkan. Pastikan data rekening benar sebelum mengajukan.</p>
                                    <p class="text-sm text-amber-700 mt-1">Proses refund membutuhkan <strong>3–5 hari kerja</strong> setelah pengajuan disetujui pengelola.</p>
                                </div>
                            </div>

                            <form action="{{ route('pemesanan-tiket.batalkan', $pemesananTiket) }}" method="POST" id="form-batal" novalidate>
                                @csrf

                                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 sm:p-5 space-y-4">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Data Rekening Refund</p>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Nama Bank <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="nama_bank" id="nama_bank" value="{{ old('nama_bank') }}"
                                               class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition"
                                               placeholder="Contoh: BCA, Mandiri, BNI, BRI" required>
                                        @error('nama_bank')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Atas Nama Rekening <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="atas_nama" id="atas_nama" value="{{ old('atas_nama') }}"
                                               class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition"
                                               placeholder="Nama pemilik rekening" required>
                                        @error('atas_nama')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Nomor Rekening <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="no_rekening" id="no_rekening" value="{{ old('no_rekening') }}"
                                               class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition"
                                               placeholder="Nomor rekening bank" required>
                                        @error('no_rekening')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Catatan <span class="text-gray-400 font-normal">(Opsional)</span>
                                        </label>
                                        <textarea name="catatan" id="catatan" rows="3"
                                                  class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition"
                                                  placeholder="Tambahkan catatan jika diperlukan">{{ old('catatan') }}</textarea>
                                        @error('catatan')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3 mt-5">
                                    <a href="{{ route('pemesanan-tiket.show', $pemesananTiket) }}"
                                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        Kembali
                                    </a>
                                    <button type="submit"
                                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Ajukan Pembatalan & Refund
                                    </button>
                                </div>
                            </form>

                        @else
                            {{-- ===== BELUM BAYAR: Konfirmasi Langsung ===== --}}

                            {{-- Peringatan --}}
                            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-4 flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-red-800">Batalkan Pemesanan?</p>
                                    <p class="text-sm text-red-700 mt-0.5">
                                        Pemesanan ini belum dibayar sehingga <strong>tidak ada refund</strong>. Pemesanan akan langsung dihapus dari sistem dan tidak dapat dikembalikan.
                                    </p>
                                </div>
                            </div>

                            <form action="{{ route('pemesanan-tiket.batalkan', $pemesananTiket) }}" method="POST" id="form-batal" novalidate>
                                @csrf

                                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 sm:p-5 space-y-4">
                                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Catatan</p>
                                    <textarea name="catatan" id="catatan" rows="3"
                                              class="w-full rounded-lg border border-gray-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-transparent transition"
                                              placeholder="Alasan pembatalan (opsional)">{{ old('catatan') }}</textarea>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3 mt-5">
                                    <a href="{{ route('pemesanan-tiket.show', $pemesananTiket) }}"
                                       class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        Tidak, Kembali
                                    </a>
                                    <button type="submit"
                                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Ya, Batalkan Pemesanan
                                    </button>
                                </div>
                            </form>
                        @endif

                    </div>

                    {{-- RIGHT COLUMN --}}
                    <div>
                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 sm:p-5 lg:sticky lg:top-24">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-4">Detail Pemesanan</p>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Tiket</span>
                                    <span class="font-medium text-gray-900 text-right max-w-[60%] sm:max-w-[150px] leading-snug break-words">{{ $pemesananTiket->ticket?->nama_tiket ?? '[Dihapus]' }}</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-3">
                                    <span class="text-gray-500">Jumlah</span>
                                    <span class="font-medium text-gray-900">{{ $pemesananTiket->jumlah_tiket }} tiket</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-3">
                                    <span class="text-gray-500">Kunjungan</span>
                                    <span class="font-medium text-gray-900">{{ $pemesananTiket->tanggal_pemesanan->locale('id')->translatedFormat('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-3 mt-1">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="font-bold text-lg text-gray-900">Rp {{ number_format($pemesananTiket->total_harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-3">
                                    <span class="text-gray-500">Status Bayar</span>
                                    @if($pemesananTiket->isPaid())
                                        <span class="text-xs font-semibold text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded-full">Lunas</span>
                                    @else
                                        <span class="text-xs font-semibold text-orange-700 bg-orange-50 border border-orange-200 px-2 py-0.5 rounded-full">Belum Dibayar</span>
                                    @endif
                                </div>
                            </div>

                            @if(!$pemesananTiket->isPaid())
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-xs text-gray-400 leading-relaxed">
                                        Karena belum ada pembayaran, tidak ada dana yang perlu dikembalikan. Pemesanan akan langsung dihapus.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        /* Error styling */
        .input-error {
            border-color: #dc2626 !important;
            background-color: #fef2f2 !important;
        }
        
        .input-error:focus {
            ring-color: #dc2626 !important;
            border-color: #dc2626 !important;
        }
        
        .error-message {
            color: #dc2626;
            font-size: 11px;
            font-weight: 500;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .error-message svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
        }
        
        /* Animasi shake untuk error */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
            20%, 40%, 60%, 80% { transform: translateX(4px); }
        }
        .shake {
            animation: shake 0.5s ease-in-out;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-batal');
        const errorContainer = document.getElementById('validation-errors');
        const errorList = document.getElementById('error-list');
        
        // Hapus semua error sebelumnya
        function clearErrors() {
            document.querySelectorAll('.input-error').forEach(el => {
                el.classList.remove('input-error', 'shake');
            });
            document.querySelectorAll('.error-message').forEach(el => {
                el.remove();
            });
            if (errorContainer) {
                errorContainer.classList.add('hidden');
            }
        }
        
        // Tampilkan error pada field
        function showFieldError(input, message) {
            input.classList.add('input-error', 'shake');
            
            // Hapus error message lama jika ada
            const existingError = input.parentElement.querySelector('.error-message');
            if (existingError) existingError.remove();
            
            // Buat error message baru
            const errorMsg = document.createElement('div');
            errorMsg.className = 'error-message';
            errorMsg.innerHTML = `
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                ${message}
            `;
            input.parentElement.appendChild(errorMsg);
            
            // Scroll ke field error
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        // Validasi form
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                clearErrors();
                
                let isValid = true;
                let errorMessages = [];
                
                // Cek field required
                const requiredFields = form.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    const value = field.value.trim();
                    const label = field.closest('div')?.querySelector('label')?.innerText?.replace(/\s*\*\s*$/, '') || 'Field';
                    
                    if (!value) {
                        isValid = false;
                        showFieldError(field, `Silakan isi ${label}`);
                        errorMessages.push(`${label} wajib diisi.`);
                    }
                });
                
                // Tampilkan error summary jika ada
                if (!isValid && errorContainer) {
                    errorContainer.classList.remove('hidden');
                    errorList.innerHTML = errorMessages.map(msg => 
                        `<li>${msg}</li>`
                    ).join('');
                    errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                // Jika valid, submit form
                if (isValid) {
                    form.submit();
                }
            });
        }
        
        // Hapus error saat user mulai mengetik
        document.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', function() {
                this.classList.remove('input-error', 'shake');
                const errorMsg = this.parentElement.querySelector('.error-message');
                if (errorMsg) errorMsg.remove();
            });
            
            field.addEventListener('change', function() {
                this.classList.remove('input-error', 'shake');
                const errorMsg = this.parentElement.querySelector('.error-message');
                if (errorMsg) errorMsg.remove();
            });
        });
    });
    </script>
</x-app-layout>