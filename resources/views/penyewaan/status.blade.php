<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Status Penyewaan</h2>
                <p class="mt-2 text-sm text-slate-500">Pantau progress penyewaan koleksi museum secara real-time.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Status Badge -->
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">{{ $penyewaan->painting->title }}</h3>
                        <p class="text-sm text-slate-500">Nomor Penyewaan: SP-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold
                        @if($penyewaan->status === 'active') bg-emerald-100 text-emerald-800
                        @elseif($penyewaan->status === 'delivered') bg-blue-100 text-blue-800
                        @elseif($penyewaan->status === 'preparing_delivery') bg-yellow-100 text-yellow-800
                        @else bg-slate-100 text-slate-800 @endif">
                        {{ $penyewaan->status_label }}
                    </span>
                </div>
            </div>

            <!-- Timeline -->
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-6">Timeline Proses</h3>
                <div class="space-y-4">
                    <!-- Pengajuan -->
                    <div class="flex items-start gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100">
                            <svg class="h-4 w-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-900">Pengajuan Penyewaan</p>
                            <p class="text-sm text-slate-500">Pengajuan telah diajukan dan menunggu pembayaran.</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $penyewaan->submitted_at?->format('d M Y H:i') ?? $penyewaan->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Verifikasi -->
                    <div class="flex items-start gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full {{ in_array($penyewaan->status, ['preparing_delivery', 'delivered', 'active', 'completed']) ? 'bg-emerald-100' : 'bg-slate-100' }}">
                            <svg class="h-4 w-4 {{ in_array($penyewaan->status, ['preparing_delivery', 'delivered', 'active', 'completed']) ? 'text-emerald-600' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-900">Verifikasi Pengelola</p>
                            <p class="text-sm text-slate-500">Pengajuan telah diverifikasi dan disetujui oleh pengelola.</p>
                        </div>
                    </div>

                    <!-- Pembayaran -->
                    <div class="flex items-start gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $penyewaan->payment_status === 'paid' ? 'bg-emerald-100' : ($penyewaan->payment_status === 'pending' ? 'bg-yellow-100' : 'bg-slate-100') }}">
                            @if($penyewaan->payment_status === 'paid')
                                <svg class="h-4 w-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @elseif($penyewaan->payment_status === 'pending')
                                <svg class="h-4 w-4 text-yellow-600 animate-spin" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg class="h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-900">Pembayaran</p>
                            <p class="text-sm text-slate-500">
                                @if($penyewaan->payment_status === 'paid')
                                    Pembayaran telah berhasil diproses.
                                    @if($penyewaan->latestPayment?->paid_at)
                                        ({{ $penyewaan->latestPayment->paid_at->format('d M Y H:i') }})
                                    @endif
                                @elseif($penyewaan->payment_status === 'pending')
                                    Menunggu pembayaran dari penyewa.
                                @else
                                    Pembayaran belum diproses.
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Persiapan Pengiriman -->
                    <div class="flex items-start gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full {{ in_array($penyewaan->status, ['preparing_delivery', 'delivered', 'active', 'completed']) ? 'bg-emerald-100' : 'bg-slate-100' }}">
                            <svg class="h-4 w-4 {{ in_array($penyewaan->status, ['preparing_delivery', 'delivered', 'active', 'completed']) ? 'text-emerald-600' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1V8a1 1 0 00-1-1h-3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-900">Persiapan Pengiriman</p>
                            <p class="text-sm text-slate-500">
                                @if($penyewaan->status === 'preparing_delivery')
                                    Pengelola sedang menyiapkan koleksi untuk dikirim.
                                @elseif(in_array($penyewaan->status, ['delivered', 'active', 'completed']))
                                    Koleksi telah disiapkan dan siap dikirim.
                                @else
                                    Menunggu pembayaran berhasil.
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Pengiriman Koleksi -->
                    <div class="flex items-start gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full {{ in_array($penyewaan->status, ['delivered', 'active', 'completed']) ? 'bg-emerald-100' : 'bg-slate-100' }}">
                            <svg class="h-4 w-4 {{ in_array($penyewaan->status, ['delivered', 'active', 'completed']) ? 'text-emerald-600' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-900">Pengiriman Koleksi</p>
                            <p class="text-sm text-slate-500">
                                @if($penyewaan->status === 'delivered')
                                    Koleksi telah dikirim oleh pengelola.
                                    @if($penyewaan->delivery_at)
                                        ({{ $penyewaan->delivery_at->format('d M Y H:i') }})
                                    @endif
                                @elseif($penyewaan->status === 'active')
                                    Koleksi telah diterima dan penyewaan aktif.
                                @elseif($penyewaan->status === 'completed')
                                    Penyewaan telah selesai.
                                @else
                                    Menunggu pengelola mengirim koleksi.
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Pemeriksaan & Upload Dokumen -->
                    <div class="flex items-start gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $penyewaan->status === 'active' ? 'bg-emerald-100' : ($penyewaan->status === 'delivered' ? 'bg-yellow-100' : 'bg-slate-100') }}">
                            @if($penyewaan->status === 'active')
                                <svg class="h-4 w-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @elseif($penyewaan->status === 'delivered')
                                <svg class="h-4 w-4 text-yellow-600 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.409l-7-14z" />
                                </svg>
                            @else
                                <svg class="h-4 w-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-900">Pemeriksaan & Upload Dokumen</p>
                            <p class="text-sm text-slate-500">
                                @if($penyewaan->status === 'active')
                                    Dokumen telah diupload, penyewaan aktif.
                                    @if($penyewaan->rental_started_at)
                                        ({{ $penyewaan->rental_started_at->format('d M Y H:i') }})
                                    @endif
                                @elseif($penyewaan->status === 'delivered')
                                    Menunggu penyewa memeriksa kondisi dan mengupload dokumen serah terima.
                                @else
                                    Menunggu koleksi dikirim.
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Penyewaan Aktif -->
                    <div class="flex items-start gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $penyewaan->status === 'active' ? 'bg-emerald-100' : 'bg-slate-100' }}">
                            <svg class="h-4 w-4 {{ $penyewaan->status === 'active' ? 'text-emerald-600' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-900">Penyewaan Aktif</p>
                            <p class="text-sm text-slate-500">
                                @if($penyewaan->status === 'active')
                                    Masa penyewaan sedang berjalan.
                                @else
                                    Menunggu dokumen serah terima diupload.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            @if($penyewaan->status === 'delivered' && $penyewaan->serahTerima && $penyewaan->serahTerima->status === 'generated')
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Tindakan</h3>
                    <p class="text-sm text-slate-500 mb-4">Koleksi telah dikirim. Silakan unduh dokumen serah terima, periksa kondisi koleksi, tandatangani, dan upload kembali.</p>
                    <div class="flex gap-3">
                        <a href="{{ route('penyewaan.requests.handover.download', ['penyewaan' => $penyewaan->id]) }}" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Download Dokumen</a>
                        <a href="{{ route('penyewaan.requests.handover.upload.form', ['penyewaan' => $penyewaan->id]) }}" class="inline-flex items-center rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700">Upload Dokumen</a>
                    </div>
                </div>
            @endif

            <!-- Detail Koleksi -->
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Detail Koleksi</h3>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Judul</p>
                        <p class="text-slate-900">{{ $penyewaan->painting->title }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Pelukis</p>
                        <p class="text-slate-900">{{ $penyewaan->painting->artist }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Tahun</p>
                        <p class="text-slate-900">{{ $penyewaan->painting->year }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Periode</p>
                        <p class="text-slate-900">{{ $penyewaan->start_date->format('d M Y') }} - {{ $penyewaan->end_date->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>