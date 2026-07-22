<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-900">Ubah Pengajuan</h2>
                <p class="mt-1 text-sm text-gray-600">Perbarui data pengajuan sebelum disetujui oleh pengelola.</p>
            </div>
            <a href="{{ route('penyewaan.requests.show', ['penyewaan' => $penyewaan->id]) }}" class="inline-flex items-center rounded-md border border-blue-600 bg-white px-4 py-2 text-sm font-semibold text-blue-600 shadow-sm hover:bg-blue-50">
                Kembali ke Detail
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="rounded-3xl border border-red-200 bg-red-50 p-4 text-red-700">
                    <h3 class="font-semibold">Periksa kembali data Anda</h3>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="bg-blue-600 px-6 py-5 text-white">
                    <h3 class="text-lg font-semibold">Form Ubah Pengajuan</h3>
                </div>
                <form action="{{ route('penyewaan.requests.update', $penyewaan) }}" method="POST" class="space-y-6 p-6">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div>
                            <label for="contact_name" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                            <input id="contact_name" name="contact_name" type="text" value="{{ old('contact_name', $penyewaan->contact_name) }}" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500" required>
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-slate-700">Email</label>
                            <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $penyewaan->contact_email) }}" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500" required>
                        </div>
                    </div>

                    <div>
                        <label for="full_address" class="block text-sm font-medium text-slate-700">Alamat Lengkap</label>
                        <textarea id="full_address" name="full_address" rows="3" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500" required>{{ old('full_address', $penyewaan->full_address) }}</textarea>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div>
                            <label for="rental_type" class="block text-sm font-medium text-slate-700">Tipe Penyewa</label>
                            <select id="rental_type" name="rental_type" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500" required>
                                <option value="perseorangan" {{ old('rental_type', $penyewaan->rental_type) === 'perseorangan' ? 'selected' : '' }}>Perseorangan</option>
                                <option value="instansi" {{ old('rental_type', $penyewaan->rental_type) === 'instansi' ? 'selected' : '' }}>Instansi</option>
                            </select>
                        </div>

                        <div>
                            <label for="institution_name" class="block text-sm font-medium text-slate-700">Nama Instansi (jika instansi)</label>
                            <input id="institution_name" name="institution_name" type="text" value="{{ old('institution_name', $penyewaan->institution_name) }}" placeholder="Contoh: PT. Museum Nusantara" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label for="purpose" class="block text-sm font-medium text-slate-700">Tujuan / Keperluan Penyewaan</label>
                        <textarea id="purpose" name="purpose" rows="3" placeholder="Contoh: Pameran seni di kantor, dekorasi acara, penelitian" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500">{{ old('purpose', $penyewaan->purpose) }}</textarea>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-slate-700">Tanggal Mulai</label>
                            <input id="start_date" name="start_date" type="date" value="{{ old('start_date', $penyewaan->start_date->format('Y-m-d')) }}" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500" required>
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-slate-700">Tanggal Selesai</label>
                            <input id="end_date" name="end_date" type="date" value="{{ old('end_date', $penyewaan->end_date->format('Y-m-d')) }}" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500" required>
                        </div>
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-slate-700">Nomor Telepon</label>
                        <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', $penyewaan->contact_phone) }}" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-slate-700">Catatan Tambahan</label>
                        <textarea id="notes" name="notes" rows="4" class="mt-2 block w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-blue-500">{{ old('notes', $penyewaan->notes) }}</textarea>
                    </div>

                    <div class="rounded-3xl bg-slate-50 p-4 text-sm text-slate-700">
                        <p><span class="font-semibold">Koleksi:</span> {{ $penyewaan->painting->title }}</p>
                        <p><span class="font-semibold">Artist:</span> {{ $penyewaan->painting->artist }}</p>
                        <p><span class="font-semibold">Harga sewa:</span> Rp {{ number_format($penyewaan->painting->daily_rate, 0, ',', '.') }}/hari</p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('penyewaan.requests.show', ['penyewaan' => $penyewaan->id]) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
