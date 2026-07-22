<x-app-layout>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">

        {{-- Breadcrumb --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('pemesanan-tiket.index') }}" class="hover:text-gray-700 transition">Pemesanan</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-700 font-medium">Detail Pemesanan</span>
        </nav>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg sm:text-xl font-bold text-gray-900">Detail Pemesanan</h1>
                            <p class="text-sm text-gray-500 mt-0.5">
                                No. <span class="font-mono font-semibold text-gray-700">#{{ str_pad($pemesananTiket->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </p>
                        </div>
                    </div>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                            'menunggu_pembayaran' => 'bg-orange-50 text-orange-700 border border-orange-200',
                            'lunas' => 'bg-green-50 text-green-700 border border-green-200',
                            'proses_pembatalan' => 'bg-blue-50 text-blue-700 border border-blue-200',
                            'pengembalian_berhasil' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                            'dibatalkan' => 'bg-red-50 text-red-700 border border-red-200',
                        ];
                        $statusLabels = [
                            'pending' => 'Pending',
                            'menunggu_pembayaran' => 'Menunggu Bayar',
                            'lunas' => 'Lunas',
                            'proses_pembatalan' => 'Proses Refund',
                            'pengembalian_berhasil' => 'Refund Berhasil',
                            'dibatalkan' => 'Dibatalkan',
                        ];
                    @endphp
                    <span class="inline-flex items-center rounded-full px-3.5 py-1.5 text-xs font-semibold {{ $statusColors[$pemesananTiket->status] ?? 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                        {{ $statusLabels[$pemesananTiket->status] ?? $pemesananTiket->status }}
                    </span>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-4 sm:p-6">

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5">

                    {{-- LEFT COLUMN --}}
                    <div class="lg:col-span-2 space-y-4 sm:space-y-5">

                        {{-- Info Tiket --}}
                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 sm:p-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">Informasi Tiket</p>
                            <div class="space-y-3 text-sm">
                                @if($pemesananTiket->ticket && $pemesananTiket->ticket->gambar)
                                    <div class="h-40 overflow-hidden rounded-lg mb-3">
                                        <img src="{{ asset('storage/gambar/'.$pemesananTiket->ticket->gambar) }}"
                                             alt="{{ $pemesananTiket->ticket->nama_tiket }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Nama Tiket</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $pemesananTiket->ticket?->nama_tiket ?? '[Tiket Dihapus]' }}
                                    </span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-3">
                                    <span class="text-gray-500">Jenis Tiket</span>
                                    <span class="font-medium text-gray-900">
                                        {{ $pemesananTiket->ticket ? ucfirst($pemesananTiket->ticket->jenis_tiket) : '-' }}
                                    </span>
                                </div>
                                @if($pemesananTiket->ticket?->sub_jenis)
                                    <div class="flex justify-between border-t border-gray-200 pt-3">
                                        <span class="text-gray-500">Sub Jenis</span>
                                        <span class="font-medium text-gray-900">{{ $pemesananTiket->ticket->sub_jenis }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between border-t border-gray-200 pt-3">
                                    <span class="text-gray-500">Tanggal Kunjungan</span>
                                    <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($pemesananTiket->tanggal_pemesanan)->locale('id')->translatedFormat('d F Y') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Data Pengunjung --}}
                        @if($pemesananTiket->detailPengunjungs->isNotEmpty())
                            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 sm:p-5">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-4">Data Pengunjung</p>
                                @php $isKelompok = $pemesananTiket->detailPengunjungs->first()->tipe_pengunjung === 'kelompok'; @endphp

                                @if($isKelompok)
                                    @foreach($pemesananTiket->detailPengunjungs as $detail)
                                        <div class="space-y-3 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Nama Kelompok</span>
                                                <span class="font-medium text-gray-900">{{ $detail->nama_kelompok }}</span>
                                            </div>
                                            <div class="flex justify-between border-t border-gray-200 pt-3">
                                                <span class="text-gray-500 flex-shrink-0 mr-4">Daftar Anggota</span>
                                                <div class="text-right text-gray-900 font-medium space-y-0.5">
                                                    @foreach($detail->daftar_anggota ?? [] as $anggota)
                                                        <div>{{ $anggota }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="flex justify-between border-t border-gray-200 pt-3">
                                                <span class="text-gray-500">Penanggung Jawab</span>
                                                <span class="font-medium text-gray-900">{{ $detail->nama_penanggung_jawab }}</span>
                                            </div>
                                            <div class="flex justify-between border-t border-gray-200 pt-3">
                                                <span class="text-gray-500">Nomor Ponsel</span>
                                                <span class="font-medium text-gray-900">{{ $detail->nomor_ponsel_penanggung_jawab }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="space-y-3">
                                        @foreach($pemesananTiket->detailPengunjungs->sortBy('urutan_pengunjung') as $detail)
                                            <div class="rounded-lg border border-gray-200 p-4 bg-white">
                                                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600 mb-3">Pengunjung #{{ $detail->urutan_pengunjung }}</p>
                                                <div class="space-y-2 text-sm">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500">Nama</span>
                                                        <span class="font-medium text-gray-900">{{ $detail->nama_lengkap }}</span>
                                                    </div>
                                                    @if($detail->pendidikan)
                                                        <div class="flex justify-between border-t border-gray-100 pt-2">
                                                            <span class="text-gray-500">Pendidikan</span>
                                                            <span class="font-medium text-gray-900">{{ $detail->pendidikan }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="flex justify-between border-t border-gray-100 pt-2">
                                                        <span class="text-gray-500">Nomor Ponsel</span>
                                                        <span class="font-medium text-gray-900">{{ $detail->nomor_ponsel }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif


                        @php
                            $proofDetails = $pemesananTiket->detailPengunjungs->filter(function ($detail) {
                                return !empty($detail->bukti_pelajar_path);
                            });
                        @endphp

                        @if($proofDetails->isNotEmpty())
                            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5">
                                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-4">Bukti Pelajar</p>
                                <div class="space-y-4">
                                    @foreach($proofDetails as $detail)
                                        @php $proofPath = $detail->bukti_pelajar_path; @endphp
                                        <div class="rounded-2xl overflow-hidden border border-gray-200 bg-gray-50">
                                            <img src="{{ asset('storage/' . $proofPath) }}"
                                                 alt="Bukti Pelajar"
                                                 class="w-full object-contain max-h-80 bg-white" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Refund Info (Proses) --}}
                        @if($pemesananTiket->isRefundProcess())
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 sm:p-5">
                                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600 mb-4">Proses Refund</p>
                                <p class="text-sm text-gray-600 mb-4">Permintaan refund sedang diproses oleh pengelola.</p>
                                @if($pemesananTiket->no_rekening_refund)
                                    <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                        <div class="rounded-lg bg-white border border-gray-200 p-2.5 sm:p-3">
                                            <p class="text-xs text-gray-400 mb-1">Bank</p>
                                            <p class="text-sm font-medium text-gray-800">{{ $pemesananTiket->nama_bank_refund }}</p>
                                        </div>
                                        <div class="rounded-lg bg-white border border-gray-200 p-2.5 sm:p-3">
                                            <p class="text-xs text-gray-400 mb-1">Nomor Rekening</p>
                                            <p class="text-sm font-medium text-gray-800">{{ $pemesananTiket->no_rekening_refund }}</p>
                                        </div>
                                        <div class="rounded-lg bg-white border border-gray-200 p-2.5 sm:p-3">
                                            <p class="text-xs text-gray-400 mb-1">Atas Nama</p>
                                            <p class="text-sm font-medium text-gray-800">{{ $pemesananTiket->atas_nama_refund }}</p>
                                        </div>
                                        <div class="rounded-lg bg-white border border-gray-200 p-2.5 sm:p-3">
                                            <p class="text-xs text-gray-400 mb-1">Diajukan</p>
                                            <p class="text-sm font-medium text-gray-800">{{ $pemesananTiket->refund_requested_at?->locale('id')->translatedFormat('d F Y H:i') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>

                    {{-- RIGHT COLUMN --}}
                    <div class="space-y-5">

                        {{-- Ringkasan Pembayaran --}}
                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 sm:p-5">
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-4">Ringkasan Pembayaran</p>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Harga per Tiket</span>
                                    <span class="text-gray-900">
                                        {{ $pemesananTiket->ticket ? 'Rp '.number_format($pemesananTiket->ticket->harga, 0, ',', '.') : '-' }}
                                    </span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-3">
                                    <span class="text-gray-500">Jumlah Tiket</span>
                                    <span class="text-gray-900">{{ $pemesananTiket->jumlah_tiket }}</span>
                                </div>
                                <div class="flex justify-between border-t border-gray-200 pt-3 mt-1">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="font-bold text-lg text-gray-900">Rp {{ number_format($pemesananTiket->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Info Bayar --}}
                            @if($pemesananTiket->isPaid())
                                @php
                                    $metodeLabels = [
                                        'transfer_bank' => 'Transfer Bank',
                                        'e_wallet' => 'E-Wallet',
                                        'kartu_kredit' => 'Kartu Kredit',
                                        'midtrans' => 'Midtrans',
                                    ];
                                @endphp
                                <div class="border-t border-gray-200 pt-4 mt-4 space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Metode</span>
                                        <span class="font-medium text-gray-900">{{ $metodeLabels[$pemesananTiket->metode_pembayaran] ?? $pemesananTiket->metode_pembayaran }}</span>
                                    </div>
                                    @if($pemesananTiket->tanggal_bayar)
                                        <div class="flex justify-between">
                                            <span class="text-gray-500">Tanggal Bayar</span>
                                            <span class="font-medium text-gray-900">{{ $pemesananTiket->tanggal_bayar->locale('id')->translatedFormat('d M Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Aksi --}}
                        <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 sm:p-5 space-y-3">

                            @if($pemesananTiket->isWaitingPayment())
                                <a href="{{ route('pemesanan-tiket.bayar', $pemesananTiket) }}"
                                   class="w-full flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Lakukan Pembayaran
                                </a>
                            @endif

                            @if($pemesananTiket->isPending() || $pemesananTiket->isWaitingPayment())
                                @if($pemesananTiket->dapatCancel())
                                    <a href="{{ route('pemesanan-tiket.batalkan.form', $pemesananTiket) }}"
                                       class="w-full flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Batalkan Pemesanan
                                    </a>
                                @endif
                            @endif

                            @if($pemesananTiket->isPaid())
                                @php $totalTiket = $pemesananTiket->detailPengunjungs->count(); @endphp
                                @if($totalTiket == 1)
                                    <a href="{{ route('pemesanan-tiket.e-tiket', $pemesananTiket) }}"
                                       class="w-full flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                        Lihat E-Tiket & QR
                                    </a>
                                @else
                                    <a href="{{ route('pemesanan-tiket.semua-e-tiket', $pemesananTiket) }}"
                                       class="w-full flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                        Lihat Semua E-Tiket ({{ $totalTiket }})
                                    </a>
                                @endif
                            @endif

                            @if($pemesananTiket->dapatReschedule())
                                <a href="{{ route('pemesanan-tiket.reschedule', $pemesananTiket) }}"
                                   class="w-full flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Reschedule
                                </a>
                            @endif

                            @if($pemesananTiket->dapatCancel() && $pemesananTiket->isPaid())
                                <a href="{{ route('pemesanan-tiket.batalkan.form', $pemesananTiket) }}"
                                   class="w-full flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Batalkan & Refund
                                </a>
                            @endif
                        </div>

                        {{-- ================= BUKTI TRANSFER (REFUND BERHASIL) ================= --}}
                        @if($pemesananTiket->isRefundCompleted() && $pemesananTiket->bukti_pengembalian)
                            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 sm:p-5">
                                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600 mb-2">
                                    <svg class="w-4 h-4 inline mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Refund Berhasil
                                </p>
                                <p class="text-sm text-gray-600 mb-3">Dana refund telah berhasil diproses oleh pengelola.</p>
                                <button onclick="openRefundModal('{{ asset('storage/' . $pemesananTiket->bukti_pengembalian) }}')"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Lihat Bukti Transfer
                                </button>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODAL OVERLAY REFUND ================= --}}
    <div id="refundModal" 
         class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4 transition-opacity duration-300"
         onclick="closeRefundModal(event)">
        <div class="relative max-w-4xl w-full mx-auto max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <button onclick="closeRefundModal()"
                    class="absolute -top-10 sm:-top-12 right-0 text-white hover:text-gray-300 transition text-3xl sm:text-4xl font-light">
                &times;
            </button>
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <img id="refundImage" src="" alt="Bukti Transfer Refund"
                         class="max-w-full max-h-[70vh] rounded-lg shadow-md object-contain" style="display: none;">
                    <div id="refundLoading" class="flex flex-col items-center justify-center py-12">
                        <svg class="w-12 h-12 text-gray-300 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <p class="mt-3 text-sm text-gray-500">Memuat gambar...</p>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <span class="text-xs text-gray-500">Klik di luar modal untuk menutup</span>
                    <div class="flex gap-2">
                        <button onclick="closeRefundModal()" 
                                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition text-sm font-medium">
                            Tutup
                        </button>
                        <a id="refundDownloadLink" href="#" target="_blank"
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

    <style>
        #refundModal { backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }
        #refundModal .max-w-4xl { animation: modalFadeIn 0.3s ease-out; }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.95) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-spin { animation: spin 1s linear infinite; }
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>

    <script>
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
                    <p class="mt-3 text-sm text-red-500">Gagal memuat gambar. Silakan coba lagi.</p>
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
    </script>
</x-app-layout>