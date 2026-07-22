<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('tickets.data-pengunjung.index') }}" class="hover:text-gray-700 transition">Data Pengunjung</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 font-medium">Detail Pengunjung</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Detail Pengunjung</h1>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $detail->getDisplayName() }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @if($detail->tiket_terpakai_at)
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
            <div class="p-4 sm:p-6">

                {{-- Data Pribadi --}}
                <div class="mb-8">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">1</span>
                        Data Pribadi
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Nama Lengkap</p>
                            <p class="mt-1 text-base font-semibold text-gray-800">{{ $detail->getDisplayName() }}</p>
                            @if($detail->tipe_pengunjung === 'kelompok')
                                <span class="mt-1 inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                    Kelompok
                                </span>
                            @endif
                        </div>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Email</p>
                            <p class="mt-1 text-base font-medium text-gray-800">{{ $detail->email ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Nomor Telepon</p>
                            <p class="mt-1 text-base font-medium text-gray-800">{{ $detail->nomor_ponsel ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Alamat</p>
                            <p class="mt-1 text-base font-medium text-gray-800">{{ $detail->alamat ?? '-' }}</p>
                        </div>
                        @if($detail->tipe_pengunjung === 'kelompok')
                            <div class="sm:col-span-2 bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Nama Kelompok</p>
                                <p class="mt-1 text-base font-medium text-gray-800">{{ $detail->nama_kelompok ?? '-' }}</p>
                            </div>
                            <div class="sm:col-span-2 bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                                <p class="text-xs text-gray-400 uppercase tracking-wider">Daftar Anggota</p>
                                <div class="mt-1 flex flex-wrap gap-1.5">
                                    @php
                                        $anggotaList = is_string($detail->daftar_anggota) ? json_decode($detail->daftar_anggota, true) : ($detail->daftar_anggota ?? []);
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
                        @endif
                    </div>
                </div>

                {{-- Data Pemesanan --}}
                <div class="mb-8">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">2</span>
                        Data Pemesanan
                    </h3>
                    @php $pemesanan = $detail->pemesananTiket; @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">No. Pemesanan</p>
                            <p class="mt-1 font-mono font-semibold text-gray-800">#{{ str_pad($pemesanan->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Tanggal Pemesanan</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $pemesanan->created_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                        </div>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Jenis Tiket</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $pemesanan->ticket->nama_tiket }}</p>
                        </div>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Tanggal Kunjungan</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $pemesanan->tanggal_pemesanan->locale('id')->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Jumlah Tiket</p>
                            <p class="mt-1 font-medium text-gray-800">{{ $pemesanan->jumlah_tiket }} tiket</p>
                            <p class="text-xs text-gray-400 mt-0.5">Urutan: #{{ $detail->urutan_pengunjung }}</p>
                        </div>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Total Pembayaran</p>
                            <p class="mt-1 text-xl font-bold text-green-600">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Status Pemesanan</p>
                            @php
                                $statusColors = [
                                    'lunas' => 'bg-green-100 text-green-700',
                                    'dibatalkan' => 'bg-red-100 text-red-700',
                                    'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-700',
                                    'proses_pembatalan' => 'bg-blue-100 text-blue-700',
                                    'pengembalian_berhasil' => 'bg-emerald-100 text-emerald-700',
                                ];
                                $statusLabels = [
                                    'lunas' => 'Lunas',
                                    'dibatalkan' => 'Dibatalkan',
                                    'menunggu_pembayaran' => 'Menunggu Bayar',
                                    'proses_pembatalan' => 'Proses Refund',
                                    'pengembalian_berhasil' => 'Refund Berhasil',
                                ];
                            @endphp
                            <span class="mt-1 inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $statusColors[$pemesanan->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$pemesanan->status] ?? ucfirst(str_replace('_', ' ', $pemesanan->status)) }}
                            </span>
                        </div>
                        <div class="bg-gray-50/50 rounded-xl p-4 border border-gray-200">
                            <p class="text-xs text-gray-400 uppercase tracking-wider">Status Scan Tiket</p>
                            @if($detail->tiket_terpakai_at)
                                <p class="mt-1 text-sm font-medium text-amber-600">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Sudah discan
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $detail->tiket_terpakai_at->locale('id')->translatedFormat('d F Y H:i') }}</p>
                            @else
                                <p class="mt-1 text-sm font-medium text-emerald-600">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Belum discan
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Token Verifikasi --}}
                <div class="mb-8">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">3</span>
                        Token Verifikasi
                    </h3>
                    <div class="bg-gray-50/50 rounded-xl px-4 py-3 font-mono text-sm text-gray-700 break-all border border-gray-200">
                        @if($detail->tiket_verifikasi_token)
                            <span class="bg-gray-100 px-2 py-0.5 rounded">{{ $detail->tiket_verifikasi_token }}</span>
                        @else
                            <span class="text-gray-400">Token belum di-generate</span>
                        @endif
                    </div>
                </div>

                {{-- Riwayat Kunjungan --}}
                @if($riwayat->isNotEmpty())
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">4</span>
                            Riwayat Kunjungan Lainnya
                            <span class="text-xs font-normal text-gray-400 ml-1">({{ $riwayat->count() }})</span>
                        </h3>
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3">Tiket</th>
                                        <th class="px-4 py-3 text-center">Jumlah</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                        <th class="px-4 py-3 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($riwayat as $item)
                                        @php $order = $item->pemesananTiket; @endphp
                                        @if(!$order)
                                            @continue
                                        @endif
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 text-gray-700">{{ $order->tanggal_pemesanan?->locale('id')->translatedFormat('d F Y') ?? '-' }}</td>
                                            <td class="px-4 py-3 text-gray-700">{{ optional($order->ticket)->nama_tiket ?? '[Tiket tidak tersedia]' }}</td>
                                            <td class="px-4 py-3 text-center text-gray-700">{{ $order->jumlah_tiket }} tiket</td>
                                            <td class="px-4 py-3 text-right font-medium text-gray-800">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-700">
                                                    Lunas
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- Tombol Kembali --}}
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('tickets.data-pengunjung.index') }}" 
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
</x-app-layout>