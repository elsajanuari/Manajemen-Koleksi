<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('tiket.index') }}" class="hover:text-gray-700 transition">Tiket</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 font-medium">Lengkapi Data Pengunjung</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-gray-900">Lengkapi Data Pengunjung</h1>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Tiket: <span class="font-medium text-gray-700">{{ $ticket->nama_tiket }}</span>
                            &bull; Tanggal: <span class="font-medium text-gray-700">{{ $pemesananTiket->tanggal_pemesanan->locale('id')->translatedFormat('d F Y') }}</span>
                            &bull; Jumlah: <span class="font-medium text-gray-700">{{ $pemesananTiket->jumlah_tiket }} tiket</span>
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

                <form action="{{ route('pemesanan-tiket.store-detail-pengunjung', $pemesananTiket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="detail-pengunjung-form" novalidate>
                    @csrf

                    @php
                        $jenisTiket = strtolower((string) $ticket->jenis_tiket);
                        $subJenis = strtolower((string) $ticket->sub_jenis);
                        $kategori = strtolower((string) $ticket->kategori_pengunjung);
                        
                        $isKelompok = $jenisTiket === 'event' && 
                                      $subJenis === 'sunday painting' && 
                                      $kategori === 'kelompok';
                        
                        $isWorkshop = $subJenis === 'workshop';
                        $isPameran = $subJenis === 'pameran';
                        $isReguler = $jenisTiket === 'reguler';
                    @endphp

                    @if($isKelompok)
                        {{-- Form untuk Sunday Painting Kelompok --}}
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-blue-700">
                                <span class="font-semibold">Catatan:</span> Silakan isi data kelompok dan penanggung jawab di bawah ini.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Kelompok <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_kelompok" value="{{ old('nama_kelompok') }}"
                                       class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                                       placeholder="Masukkan nama kelompok" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Daftar Anggota <span class="text-red-500">*</span>
                                </label>
                                <textarea name="daftar_anggota" rows="3"
                                          class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                                          placeholder="Masukkan nama anggota, pisahkan dengan koma" required>{{ old('daftar_anggota') }}</textarea>
                                <p class="text-xs text-gray-400 mt-1">Contoh: Andi Wijaya, Budi Santoso, Citra Dewi</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4 mt-2">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4">Data Penanggung Jawab</h3>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Nama Penanggung Jawab <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_penanggung_jawab" value="{{ old('nama_penanggung_jawab') }}"
                                           class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Alamat Penanggung Jawab/Instansi <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="alamat_penanggung_jawab" rows="2"
                                              class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" required>{{ old('alamat_penanggung_jawab') }}</textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Nomor Ponsel <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" name="nomor_ponsel_penanggung_jawab" value="{{ old('nomor_ponsel_penanggung_jawab') }}"
                                               class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                                               placeholder="08xxxxxxxxxx" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="email_penanggung_jawab" value="{{ old('email_penanggung_jawab') }}"
                                               class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Form untuk Individu --}}
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-blue-700">
                                <span class="font-semibold">Catatan:</span> Silakan isi data untuk setiap pengunjung di bawah ini.
                            </p>
                        </div>

                        @for($i = 1; $i <= $pemesananTiket->jumlah_tiket; $i++)
                            <div class="rounded-xl border {{ $i > 1 ? 'border-gray-200' : 'border-blue-300 bg-blue-50/50' }} p-5" id="pengunjung-{{ $i }}-container">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                                    <h3 class="font-semibold text-gray-800">
                                        Pengunjung #{{ $i }}
                                        @if($i === 1)
                                            <span class="text-xs font-normal text-gray-500 ml-1">(Utama)</span>
                                        @endif
                                    </h3>
                                    @if($i > 1)
                                        <button type="button" 
                                                class="copy-pengunjung-btn text-sm text-blue-600 hover:text-blue-800 hover:underline transition flex items-center gap-1"
                                                data-target="{{ $i }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                            </svg>
                                            Samakan dengan pengunjung 1
                                        </button>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="pengunjung[{{ $i }}][nama_lengkap]"
                                               class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition pengunjung-nama"
                                               data-pengunjung="{{ $i }}"
                                               data-field="nama_lengkap"
                                               value="{{ old('pengunjung.' . $i . '.nama_lengkap') }}" required>
                                    </div>

                                    @if($isWorkshop)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Pendidikan / Instansi <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="pengunjung[{{ $i }}][pendidikan]"
                                               class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition pengunjung-pendidikan"
                                               data-pengunjung="{{ $i }}"
                                               data-field="pendidikan"
                                               placeholder="Contoh: SMA Negeri 1, Universitas Indonesia, Umum"
                                               value="{{ old('pengunjung.' . $i . '.pendidikan') }}" required>
                                    </div>
                                    @endif

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                            Alamat <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="pengunjung[{{ $i }}][alamat]" rows="2"
                                                  class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition pengunjung-alamat"
                                                  data-pengunjung="{{ $i }}"
                                                  data-field="alamat" required>{{ old('pengunjung.' . $i . '.alamat') }}</textarea>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                Nomor Ponsel <span class="text-red-500">*</span>
                                            </label>
                                            <input type="tel" 
                                                   name="pengunjung[{{ $i }}][nomor_ponsel]"
                                                   class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition pengunjung-ponsel"
                                                   data-pengunjung="{{ $i }}"
                                                   data-field="nomor_ponsel"
                                                   placeholder="08xxxxxxxxxx"
                                                   value="{{ old('pengunjung.' . $i . '.nomor_ponsel') }}" required>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                                Email <span class="text-red-500">*</span>
                                            </label>
                                            <input type="email" 
                                                   name="pengunjung[{{ $i }}][email]"
                                                   class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition pengunjung-email"
                                                   data-pengunjung="{{ $i }}"
                                                   data-field="email"
                                                   value="{{ old('pengunjung.' . $i . '.email') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    @endif

                    @php
                        $isPelajar = strtolower((string) $ticket->kategori_pengunjung) === 'pelajar';
                    @endphp

                    @if($isPelajar)
                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-amber-800">Bukti pelajar wajib diunggah</p>
                                    <p class="text-sm text-amber-700 mt-1">Silakan unggah kartu pelajar atau kartu tanda mahasiswa untuk setiap pemesanan kategori pelajar.</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Bukti Pelajar / KTM <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="bukti_pelajar" accept="image/*,.pdf"
                                       class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       required>
                                <p class="text-xs text-gray-400 mt-1">Format yang diterima: JPG, JPEG, PNG, PDF (maks. 2 MB)</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                        <button type="submit" 
                                class="flex-1 inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Lihat Detail Pemesanan
                        </button>
                    </div>
                </form>
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
        const form = document.getElementById('detail-pengunjung-form');
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
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();
            
            let isValid = true;
            let errorMessages = [];
            const requiredFields = form.querySelectorAll('[required]');
            
            requiredFields.forEach(field => {
                const value = field.value.trim();
                const label = field.closest('div')?.querySelector('label')?.innerText?.replace(/\s*\*\s*$/, '') || 'Field';
                
                if (!value) {
                    isValid = false;
                    showFieldError(field, `Silakan isi ${label}`);
                    errorMessages.push(`${label} wajib diisi.`);
                }
                
                // Validasi email
                if (field.type === 'email' && value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                        showFieldError(field, 'Format email tidak valid (contoh: nama@email.com)');
                        errorMessages.push('Format email tidak valid.');
                    }
                }
                
                // Validasi nomor ponsel
                if (field.type === 'tel' && value) {
                    const phoneRegex = /^[0-9]{10,13}$/;
                    const cleanValue = value.replace(/[^0-9]/g, '');
                    if (!phoneRegex.test(cleanValue)) {
                        isValid = false;
                        showFieldError(field, 'Nomor ponsel harus 10-13 digit angka');
                        errorMessages.push('Nomor ponsel harus 10-13 digit angka.');
                    }
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
        
        // Hapus error saat user mulai mengetik
        form.querySelectorAll('input, textarea, select').forEach(field => {
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

        // Copy Pengunjung
        const copyButtons = document.querySelectorAll('.copy-pengunjung-btn');
        
        copyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetIndex = this.getAttribute('data-target');
                
                const firstPengunjung = {
                    nama_lengkap: document.querySelector('input[name="pengunjung[1][nama_lengkap]"]')?.value || '',
                    alamat: document.querySelector('textarea[name="pengunjung[1][alamat]"]')?.value || '',
                    nomor_ponsel: document.querySelector('input[name="pengunjung[1][nomor_ponsel]"]')?.value || '',
                    email: document.querySelector('input[name="pengunjung[1][email]"]')?.value || ''
                };
                
                const pendidikanField = document.querySelector('input[name="pengunjung[1][pendidikan]"]');
                if (pendidikanField) {
                    firstPengunjung.pendidikan = pendidikanField.value;
                }
                
                const targetNama = document.querySelector(`input[name="pengunjung[${targetIndex}][nama_lengkap]"]`);
                const targetAlamat = document.querySelector(`textarea[name="pengunjung[${targetIndex}][alamat]"]`);
                const targetPonsel = document.querySelector(`input[name="pengunjung[${targetIndex}][nomor_ponsel]"]`);
                const targetEmail = document.querySelector(`input[name="pengunjung[${targetIndex}][email]"]`);
                const targetPendidikan = document.querySelector(`input[name="pengunjung[${targetIndex}][pendidikan]"]`);
                
                if (targetNama) targetNama.value = firstPengunjung.nama_lengkap;
                if (targetAlamat) targetAlamat.value = firstPengunjung.alamat;
                if (targetPonsel) targetPonsel.value = firstPengunjung.nomor_ponsel;
                if (targetEmail) targetEmail.value = firstPengunjung.email;
                if (targetPendidikan && firstPengunjung.pendidikan) targetPendidikan.value = firstPengunjung.pendidikan;
                
                const tempAlert = document.createElement('div');
                tempAlert.innerHTML = `
                    <div class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-4 sm:w-auto bg-green-600 text-white px-4 sm:px-5 py-3 rounded-xl shadow-lg text-sm flex items-center gap-2 z-50 transition-opacity duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Data berhasil disalin dari pengunjung pertama
                    </div>
                `;
                document.body.appendChild(tempAlert);
                
                setTimeout(() => {
                    tempAlert.querySelector('div').style.opacity = '0';
                    setTimeout(() => {
                        document.body.removeChild(tempAlert);
                    }, 300);
                }, 2500);
            });
        });
    });
    </script>
</x-app-layout>