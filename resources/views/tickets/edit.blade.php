{{-- resources/views/tickets/edit.blade.php --}}
@php
    $tanggalMulaiValue = old('tanggal_mulai', is_string($ticket->tanggal_mulai) ? $ticket->tanggal_mulai : ($ticket->tanggal_mulai?->format('Y-m-d') ?? ''));
    $tanggalSelesaiValue = old('tanggal_selesai', is_string($ticket->tanggal_selesai) ? $ticket->tanggal_selesai : ($ticket->tanggal_selesai?->format('Y-m-d') ?? ''));
    $kategoriTersimpan = old('kategori_pengunjung', $ticket->kategori_pengunjung ?? '');
    $subJenisTersimpan = old('sub_jenis', $ticket->sub_jenis ?? '');
    
    // Load existing available dates from quotas
    $existingAvailableDates = [];
    foreach ($ticket->quotas as $quota) {
        $quotaDate = is_string($quota->tanggal) ? \Carbon\Carbon::parse($quota->tanggal) : $quota->tanggal;
        if ($quota->kuota_max > 0) {
            $existingAvailableDates[] = $quotaDate->toDateString();
        }
    }
@endphp

<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('tickets.index') }}" class="hover:text-gray-700 transition">Tiket</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 font-medium">Edit Tiket</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-gray-900">Edit Tiket</h1>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $ticket->nama_tiket }}</p>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6">

                {{-- Error Summary (Frontend Validation) --}}
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

                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data" id="ticket-form" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Catatan --}}
                    <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-200 flex items-start gap-3 mb-6">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-blue-700">
                            <span class="font-semibold">Catatan:</span> Kolom dengan tanda <span class="text-red-500">*</span> wajib diisi.
                        </p>
                    </div>

                    {{-- GRID UTAMA: 2 Kolom (Desktop) / 1 Kolom (Mobile) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">

                        {{-- KOLOM KIRI: Informasi Dasar (Section 1) --}}
                        <div class="space-y-6 order-1">

                            {{-- Nama Tiket --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Tiket <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_tiket" id="nama_tiket" value="{{ old('nama_tiket', $ticket->nama_tiket) }}"
                                       class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                                       placeholder="Contoh: Tiket Pameran Reguler" required>
                                @error('nama_tiket')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Jenis Tiket & Sub Jenis --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Jenis Tiket <span class="text-red-500">*</span>
                                    </label>
                                    <select name="jenis_tiket" id="jenis_tiket"
                                        class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white" required>
                                        <option value="reguler" {{ old('jenis_tiket', $ticket->jenis_tiket) === 'reguler' ? 'selected' : '' }}>Reguler</option>
                                        <option value="event" {{ old('jenis_tiket', $ticket->jenis_tiket) === 'event' ? 'selected' : '' }}>Event</option>
                                    </select>
                                    @error('jenis_tiket')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div id="sub-jenis-wrap" class="{{ $ticket->jenis_tiket === 'event' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Sub Jenis <span class="text-red-500">*</span>
                                    </label>
                                    <select name="sub_jenis" id="sub_jenis"
                                        class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                                        <option value="">Pilih Sub Jenis</option>
                                        <option value="Sunday Painting" {{ $subJenisTersimpan === 'Sunday Painting' ? 'selected' : '' }}>Sunday Painting</option>
                                        <option value="Pameran" {{ $subJenisTersimpan === 'Pameran' ? 'selected' : '' }}>Pameran</option>
                                        <option value="Workshop" {{ $subJenisTersimpan === 'Workshop' ? 'selected' : '' }}>Workshop</option>
                                        <option value="Lainnya" {{ $subJenisTersimpan === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('sub_jenis')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            {{-- Sub Kategori & Kategori Pengunjung --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div id="sub-kategori-wrap" class="{{ $subJenisTersimpan === 'Pameran' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Sub Kategori <span class="text-red-500">*</span>
                                    </label>
                                    <select name="sub_kategori" id="sub_kategori"
                                        class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white">
                                        <option value="">Pilih Sub Kategori</option>
                                        <option value="Pameran Rutin" {{ old('sub_kategori', $ticket->sub_kategori) === 'Pameran Rutin' ? 'selected' : '' }}>Pameran Rutin</option>
                                        <option value="Pameran Berkala" {{ old('sub_kategori', $ticket->sub_kategori) === 'Pameran Berkala' ? 'selected' : '' }}>Pameran Berkala</option>
                                        <option value="Pameran Museum" {{ old('sub_kategori', $ticket->sub_kategori) === 'Pameran Museum' ? 'selected' : '' }}>Pameran Museum</option>
                                    </select>
                                    @error('sub_kategori')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div id="kategori-wrap">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Kategori Pengunjung <span class="text-red-500">*</span>
                                    </label>
                                    <select name="kategori_pengunjung" id="kategori_pengunjung"
                                        class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white" required>
                                        <option value="">Pilih Kategori Pengunjung</option>
                                    </select>
                                    @error('kategori_pengunjung')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            {{-- Minimal Anggota (khusus Sunday Painting Kelompok) --}}
                            <div id="minimal-anggota-wrap" class="{{ $subJenisTersimpan === 'Sunday Painting' && $ticket->kategori_pengunjung === 'Kelompok' ? '' : 'hidden' }}">
                                <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200 flex items-start gap-3">
                                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm text-yellow-700">
                                            <span class="font-semibold">Khusus Sunday Painting Kelompok:</span> Minimal 5 orang per kelompok.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Minimal Anggota Kelompok <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="minimal_anggota" id="minimal_anggota" min="5" value="{{ old('minimal_anggota', $ticket->minimal_anggota ?? 5) }}"
                                        class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" required>
                                    <p class="text-xs text-gray-400 mt-1">Minimal 5 orang per kelompok</p>
                                    @error('minimal_anggota')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            {{-- Harga & Kuota --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Harga <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                                        <input type="number" name="harga" id="harga" value="{{ old('harga', $ticket->harga) }}"
                                            placeholder="0"
                                            class="w-full rounded-lg border-gray-300 pl-10 pr-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" required>
                                    </div>
                                    @error('harga')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Kuota per Hari <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="kuota" id="kuota" value="{{ old('kuota', $ticket->kuota) }}"
                                        placeholder="Jumlah slot tersedia per hari"
                                        class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" required>
                                    @error('kuota')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Deskripsi <span class="text-red-500">*</span>
                                </label>
                                <textarea name="deskripsi" id="deskripsi" rows="3"
                                    class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition resize-none"
                                    placeholder="Deskripsi singkat tentang tiket" required>{{ old('deskripsi', $ticket->deskripsi) }}</textarea>
                                @error('deskripsi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            {{-- Periode & Jam --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Periode Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai_input" value="{{ $tanggalMulaiValue }}" min="{{ date('Y-m-d') }}"
                                        class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" required>
                                    @error('tanggal_mulai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Periode Selesai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai_input" value="{{ $tanggalSelesaiValue }}"
                                        class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition" required>
                                    @error('tanggal_selesai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                        Jam Mulai
                                    </label>
                                    <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai', $ticket->jam_mulai ? \Carbon\Carbon::parse($ticket->jam_mulai)->format('H:i') : '') }}"
                                        class="w-full rounded-lg border-gray-300 px-3.5 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                                    <p class="text-xs text-gray-400 mt-1">Isi jika acara dimulai pada jam tertentu</p>
                                    @error('jam_mulai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            {{-- Gambar --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Gambar
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition cursor-pointer"
                                    onclick="document.getElementById('gambar-input').click()">
                                    <input type="file" name="gambar" id="gambar-input" class="hidden" accept="image/*">
                                    <div id="img-preview-wrap" class="{{ $ticket->gambar ? '' : 'hidden' }} mb-3">
                                        <img id="img-preview" src="{{ $ticket->gambar ? asset('storage/gambar/'.$ticket->gambar) : '' }}" class="h-24 mx-auto rounded-lg object-cover">
                                    </div>
                                    <div id="img-placeholder" class="{{ $ticket->gambar ? 'hidden' : '' }}">
                                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm text-gray-400">Klik untuk ganti gambar</p>
                                        <p class="text-xs text-gray-300 mt-1">PNG, JPG, WEBP (maks. 2MB)</p>
                                    </div>
                                </div>
                                @if($ticket->gambar)
                                    <p class="text-xs text-gray-400 mt-2">Gambar saat ini: <span class="font-medium">{{ $ticket->gambar }}</span></p>
                                @endif
                                @error('gambar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- KOLOM KANAN: Kalender (Section 2) --}}
                        <div class="order-2 lg:order-2">
                            <div class="bg-gray-50/50 rounded-xl p-4 sm:p-5 border border-gray-200 sticky top-4">
                                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold flex-shrink-0">2</span>
                                    Pengaturan Jadwal & Ketersediaan
                                </h3>
                                <p class="text-xs text-gray-500 mb-4">Pilih tanggal yang tersedia. Klik tombol hari untuk memilih semua tanggal pada hari itu.</p>

                                <div class="overflow-x-auto -mx-3 sm:-mx-0">
                                    <x-availability-calendar
                                        schedule-config-name="schedule_config"
                                        start-input-id="tanggal_mulai_input"
                                        end-input-id="tanggal_selesai_input"
                                        :existing-dates="$existingAvailableDates"
                                        :skip-empty-months="false"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: Kebijakan (di bawah, full width) --}}
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">3</span>
                            Kebijakan Reschedule & Pembatalan
                        </h3>
                        <p class="text-xs text-gray-500 mb-4">Pengguna hanya dapat reschedule atau cancel (refund) jika diizinkan, dan paling lambat 48 jam sebelum tanggal kunjungan.</p>

                        <div class="space-y-3">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="hidden" name="boleh_reschedule" value="0">
                                <input type="checkbox" name="boleh_reschedule" value="1" class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 flex-shrink-0"
                                    {{ old('boleh_reschedule', $ticket->boleh_reschedule) ? 'checked' : '' }}>
                                <span>
                                    <span class="text-sm font-medium text-gray-800">Izinkan reschedule</span>
                                    <span class="block text-xs text-gray-500">Pengguna dapat mengubah tanggal kunjungan setelah membayar.</span>
                                </span>
                            </label>
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="hidden" name="boleh_cancel" value="0">
                                <input type="checkbox" name="boleh_cancel" value="1" class="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 flex-shrink-0"
                                    {{ old('boleh_cancel', $ticket->boleh_cancel) ? 'checked' : '' }}>
                                <span>
                                    <span class="text-sm font-medium text-gray-800">Izinkan pembatalan (refund)</span>
                                    <span class="block text-xs text-gray-500">Pengguna dapat membatalkan pemesanan; jika sudah lunas, dana dikembalikan transfer manual.</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 mt-6 border-t border-gray-200">
                        <a href="{{ route('tickets.index') }}"
                            class="flex-1 inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm order-2 sm:order-1">
                            Batal
                        </a>
                        <button type="submit" id="submit-btn"
                            class="flex-1 inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition shadow-sm order-1 sm:order-2">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <style>
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
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
            20%, 40%, 60%, 80% { transform: translateX(4px); }
        }
        .shake {
            animation: shake 0.5s ease-in-out;
        }

        @media (min-width: 1024px) {
            .sticky {
                position: sticky;
                top: 1rem;
            }
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('ticket-form');
        const errorContainer = document.getElementById('validation-errors');
        const errorList = document.getElementById('error-list');
        const submitBtn = document.getElementById('submit-btn');

        // ============================================================
        // PREVIEW IMAGE
        // ============================================================
        const gambarInput = document.getElementById('gambar-input');
        
        if (gambarInput) {
            gambarInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewWrap = document.getElementById('img-preview-wrap');
                        const preview = document.getElementById('img-preview');
                        const placeholder = document.getElementById('img-placeholder');
                        
                        if (preview && previewWrap && placeholder) {
                            preview.src = e.target.result;
                            previewWrap.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // ============================================================
        // CLEAR ERRORS
        // ============================================================
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

        function showFieldError(input, message) {
            input.classList.add('input-error', 'shake');

            const existingError = input.parentElement.querySelector('.error-message');
            if (existingError) existingError.remove();

            const errorMsg = document.createElement('div');
            errorMsg.className = 'error-message';
            errorMsg.innerHTML = `
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                ${message}
            `;
            input.parentElement.appendChild(errorMsg);

            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // ============================================================
        // FORM VALIDATION
        // ============================================================
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
            });

            const minimalAnggota = document.getElementById('minimal_anggota');
            if (minimalAnggota && !minimalAnggota.closest('.hidden')) {
                const val = parseInt(minimalAnggota.value);
                if (isNaN(val) || val < 5) {
                    isValid = false;
                    showFieldError(minimalAnggota, 'Minimal anggota kelompok adalah 5 orang');
                    errorMessages.push('Minimal anggota kelompok adalah 5 orang.');
                }
            }

            const calendarRoot = document.querySelector('[data-calendar-root]');
            calendarRoot?.syncScheduleConfig();

            if (calendarRoot && !calendarRoot?.hasAnyAvailableDate()) {
                isValid = false;
                errorMessages.push('Silakan pilih minimal satu tanggal yang tersedia pada kalender.');
                calendarRoot.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            if (!isValid && errorContainer) {
                errorContainer.classList.remove('hidden');
                errorList.innerHTML = errorMessages.map(msg =>
                    `<li>${msg}</li>`
                ).join('');
                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            if (isValid) {
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '⏳ Menyimpan...';
                }
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

        // ============================================================
        // CATEGORY MANAGEMENT
        // ============================================================

        const jenisSelect = document.getElementById('jenis_tiket');
        const subJenisSelect = document.getElementById('sub_jenis');
        const kategoriSelect = document.getElementById('kategori_pengunjung');

        const subJenisWrap = document.getElementById('sub-jenis-wrap');
        const subKategoriWrap = document.getElementById('sub-kategori-wrap');
        const kategoriWrap = document.getElementById('kategori-wrap');
        const minimalAnggotaWrap = document.getElementById('minimal-anggota-wrap');
        const minimalAnggotaInput = document.getElementById('minimal_anggota');

        const kategoriTersimpan = "{{ $kategoriTersimpan }}";

        function setRequired(field, required) {
            if (required) {
                field.setAttribute('required', 'required');
                const label = field.closest('div')?.querySelector('label');
                if (label && !label.innerHTML.includes('<span class="text-red-500">*</span>')) {
                    label.insertAdjacentHTML('beforeend', ' <span class="text-red-500">*</span>');
                }
            } else {
                field.removeAttribute('required');
                const label = field.closest('div')?.querySelector('label');
                if (label) {
                    label.innerHTML = label.innerHTML.replace(' <span class="text-red-500">*</span>', '');
                }
            }
        }

        function setKategoriOptions(options, current = '') {
            kategoriSelect.innerHTML = '<option value="">Pilih Kategori Pengunjung</option>';
            options.forEach(opt => {
                kategoriSelect.insertAdjacentHTML('beforeend',
                    `<option value="${opt}" ${current === opt ? 'selected' : ''}>${opt}</option>`);
            });
            setRequired(kategoriSelect, options.length > 0);
        }

        function refreshVisibility() {
            const jenis = jenisSelect.value.toLowerCase();
            const subJenis = subJenisSelect.value.toLowerCase();
            const currentKategori = kategoriSelect.value || kategoriTersimpan;

            subJenisWrap.classList.add('hidden');
            subKategoriWrap.classList.add('hidden');
            minimalAnggotaWrap.classList.add('hidden');
            minimalAnggotaInput.removeAttribute('required');

            if (jenis === 'reguler') {
                kategoriWrap.classList.remove('hidden');
                setKategoriOptions(['Pelajar', 'Umum', 'WNA'], currentKategori);
                return;
            }

            subJenisWrap.classList.remove('hidden');
            kategoriWrap.classList.remove('hidden');

            if (subJenis === 'sunday painting') {
                setKategoriOptions(['Individu', 'Kelompok', 'WNA'], currentKategori);
                if (currentKategori === 'Kelompok') {
                    minimalAnggotaWrap.classList.remove('hidden');
                    minimalAnggotaInput.setAttribute('required', 'required');
                    if (!minimalAnggotaInput.value || Number(minimalAnggotaInput.value) < 5) {
                        minimalAnggotaInput.value = 5;
                    }
                }
                setRequired(kategoriSelect, true);
            } else if (subJenis === 'workshop' || subJenis === 'lainnya') {
                setKategoriOptions(['Pelajar', 'Umum', 'WNA'], currentKategori);
                setRequired(kategoriSelect, true);
            } else if (subJenis === 'pameran') {
                subKategoriWrap.classList.remove('hidden');
                setKategoriOptions(['Pelajar', 'Umum', 'WNA'], currentKategori);
                setRequired(kategoriSelect, true);
            } else {
                kategoriWrap.classList.add('hidden');
                setRequired(kategoriSelect, false);
            }
        }

        jenisSelect.addEventListener('change', refreshVisibility);
        subJenisSelect.addEventListener('change', refreshVisibility);
        kategoriSelect.addEventListener('change', function() {
            const subJenis = subJenisSelect.value.toLowerCase();
            if (subJenis === 'sunday painting' && this.value === 'Kelompok') {
                minimalAnggotaWrap.classList.remove('hidden');
                minimalAnggotaInput.setAttribute('required', 'required');
                if (!minimalAnggotaInput.value || Number(minimalAnggotaInput.value) < 5) {
                    minimalAnggotaInput.value = 5;
                }
            } else {
                minimalAnggotaWrap.classList.add('hidden');
                minimalAnggotaInput.removeAttribute('required');
            }
        });

        refreshVisibility();

        // ============================================================
        // VALIDASI TANGGAL
        // ============================================================

        const tanggalMulai = document.getElementById('tanggal_mulai_input');
        const tanggalSelesai = document.getElementById('tanggal_selesai_input');

        tanggalMulai.addEventListener('change', function() {
            if (tanggalSelesai.value && this.value > tanggalSelesai.value) {
                tanggalSelesai.value = this.value;
            }
            tanggalSelesai.min = this.value;
        });

        tanggalSelesai.addEventListener('change', function() {
            if (tanggalMulai.value && this.value < tanggalMulai.value) {
                alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
                this.value = tanggalMulai.value;
            }
        });

        if (tanggalMulai.value) {
            tanggalSelesai.min = tanggalMulai.value;
        }
    });
    </script>
</x-app-layout>