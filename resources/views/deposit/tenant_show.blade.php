<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-slate-900">Detail Deposit & Pengembalian</h2>
                <p class="mt-1 text-sm text-slate-600">
                    SP-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}
                    &mdash; {{ $penyewaan->painting->title }}
                </p>
            </div>
            <a href="{{ route('penyewaan.requests.show', $penyewaan) }}"
               class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                ← Kembali ke Detail Penyewaan
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alert --}}
            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                    ✓ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-800">
                    ✗ {{ session('error') }}
                </div>
            @endif

            {{-- Status deposit --}}
            @php
                $depositStatus = $penyewaan->deposit_status ?? 'unpaid';
                $statusConfig = [
                    'unpaid'                     => ['bg-slate-100 text-slate-700',   'Belum Dibayar'],
                    'paid'                        => ['bg-blue-100 text-blue-700',     'Deposit Dibayar'],
                    'returned'                    => ['bg-emerald-100 text-emerald-700', 'Dikembalikan'],
                    'partially_returned'          => ['bg-teal-100 text-teal-700',    'Dikembalikan Sebagian'],
                    'deducted'                    => ['bg-amber-100 text-amber-700',  'Dipotong Kerusakan'],
                    'additional_payment_required' => ['bg-red-100 text-red-700',      'Perlu Pembayaran Tambahan'],
                ];
                [$badgeClass, $statusLabel] = $statusConfig[$depositStatus] ?? ['bg-slate-100 text-slate-700', ucfirst($depositStatus)];
            @endphp

            <section class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Status Deposit</p>
                    <span class="rounded-full px-3 py-1.5 text-xs font-bold ring-1 {{ $badgeClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <p class="text-xs text-slate-500">Nominal Deposit</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">
                            Rp {{ number_format($depositAmount, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-slate-400">50% dari biaya sewa</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Koleksi</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $penyewaan->painting->title }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Periode Sewa</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $penyewaan->start_date->format('d M Y') }} – {{ $penyewaan->end_date->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </section>

            {{-- Hasil pemeriksaan akhir --}}
            @if($serahTerima?->final_inspection_at)
                <section class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 mb-5">Hasil Pemeriksaan Akhir Koleksi</p>

                    <div class="flex items-center gap-3 mb-5">
                        <span class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-semibold
                            {{ $serahTerima->has_damage ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $serahTerima->has_damage ? '✗ Ditemukan Kerusakan' : '✓ Tidak Ada Kerusakan' }}
                        </span>
                        <span class="text-xs text-slate-400">
                            Diperiksa: {{ $serahTerima->final_inspection_at->format('d M Y H:i') }}
                        </span>
                    </div>

                    {{-- Checklist akhir --}}
                    <div class="grid gap-2 sm:grid-cols-2 mb-4">
                        @foreach([
                            ['label' => 'Frame / bingkai aman',       'value' => $serahTerima->final_checklist_frame_safe],
                            ['label' => 'Tidak ada sobekan baru',     'value' => $serahTerima->final_checklist_no_tears],
                            ['label' => 'Warna lukisan normal',       'value' => $serahTerima->final_checklist_color_normal],
                            ['label' => 'Kaca pelindung aman',        'value' => $serahTerima->final_checklist_glass_safe],
                            ['label' => 'Tidak ada jamur baru',       'value' => $serahTerima->final_checklist_no_mold],
                            ['label' => 'Kemasan aman',               'value' => $serahTerima->final_checklist_packaging_safe],
                            ['label' => 'Sesuai kondisi dokumentasi', 'value' => $serahTerima->final_checklist_matches_documentation],
                        ] as $item)
                            <div class="flex items-center gap-3 rounded-xl border {{ $item['value'] ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50' }} px-4 py-2.5">
                                <span class="{{ $item['value'] ? 'text-emerald-600' : 'text-red-400' }} font-bold">
                                    {{ $item['value'] ? '✓' : '✗' }}
                                </span>
                                <span class="text-sm text-slate-700">{{ $item['label'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    @if($serahTerima->final_inspection_notes)
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs font-semibold text-slate-600">Catatan Pemeriksaan</p>
                            <p class="mt-1 text-sm text-slate-700">{{ $serahTerima->final_inspection_notes }}</p>
                        </div>
                    @endif

                    @if($serahTerima->final_inspection_photo_path)
                        <div class="mt-4">
                            <p class="text-xs font-semibold text-slate-600 mb-2">Foto Kondisi Akhir</p>
                            <img src="{{ Storage::url($serahTerima->final_inspection_photo_path) }}"
                                 alt="Foto kondisi akhir"
                                 class="w-full max-w-sm h-48 object-cover rounded-2xl border border-slate-200">
                        </div>
                    @endif

                    {{-- Detail kerusakan --}}
                    @if($serahTerima->has_damage)
                        <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 p-5">
                            <p class="text-xs font-semibold text-red-700 uppercase tracking-wide mb-3">Detail Kerusakan</p>
                            <div class="grid gap-4 sm:grid-cols-3 text-sm">
                                <div>
                                    <p class="text-xs text-slate-500">Jenis Kerusakan</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $serahTerima->final_damage_type ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Tingkat</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ ucfirst($serahTerima->final_damage_level ?? '-') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Estimasi Biaya</p>
                                    <p class="mt-1 font-semibold text-red-700">
                                        Rp {{ number_format($serahTerima->final_damage_cost, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            @if($serahTerima->final_damage_notes)
                                <p class="mt-3 text-sm text-slate-600">{{ $serahTerima->final_damage_notes }}</p>
                            @endif
                        </div>
                    @endif
                </section>
            @else
                <section class="rounded-3xl bg-slate-50 border border-slate-200 shadow-sm p-6 text-center">
                    <p class="text-sm text-slate-500">Pengelola sedang melakukan pemeriksaan akhir koleksi. Hasil akan ditampilkan di sini.</p>
                </section>
            @endif

            {{-- Informasi Refund --}}
            @if($penyewaan->depositRefund)
                @php $refund = $penyewaan->depositRefund; @endphp
                <section class="rounded-3xl border border-emerald-200 bg-emerald-50 shadow-sm p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 mb-5">Pengembalian Deposit</p>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 text-sm">
                        <div>
                            <p class="text-xs text-slate-500">Total Deposit</p>
                            <p class="mt-1 font-semibold text-slate-900">Rp {{ number_format($refund->deposit_amount, 0, ',', '.') }}</p>
                        </div>
                        @if($refund->damage_deduction > 0)
                            <div>
                                <p class="text-xs text-slate-500">Potongan Kerusakan</p>
                                <p class="mt-1 font-semibold text-red-700">- Rp {{ number_format($refund->damage_deduction, 0, ',', '.') }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-slate-500">Nominal Dikembalikan</p>
                            <p class="mt-1 font-bold text-emerald-700 text-lg">Rp {{ number_format($refund->refund_amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Tanggal Transfer</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $refund->refund_date->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="mt-4 rounded-2xl bg-white border border-emerald-200 p-4 text-sm">
                        <div class="grid gap-3 sm:grid-cols-3">
                            <div>
                                <p class="text-xs text-slate-500">Bank Tujuan</p>
                                <p class="mt-1 font-semibold text-slate-900">{{ $refund->bank_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Nomor Rekening</p>
                                <p class="mt-1 font-semibold text-slate-900 font-mono">{{ $refund->account_number }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Atas Nama</p>
                                <p class="mt-1 font-semibold text-slate-900">{{ $refund->account_holder }}</p>
                            </div>
                        </div>
                    </div>
                    @if($refund->transfer_proof_path)
                        <div class="mt-4">
                            <a href="{{ Storage::url($refund->transfer_proof_path) }}" target="_blank"
                               class="inline-flex items-center rounded-full border border-emerald-300 bg-white px-5 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-50">
                               🧾 Lihat Bukti Transfer
                            </a>
                        </div>
                    @endif
                    @if($refund->notes)
                        <p class="mt-3 text-xs text-slate-500">{{ $refund->notes }}</p>
                    @endif
                </section>
            @endif

            {{-- Damage Invoice --}}
            @if($penyewaan->damageInvoice)
                @php $invoice = $penyewaan->damageInvoice; @endphp
                <section class="rounded-3xl border border-red-200 bg-red-50 shadow-sm p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-red-700 mb-2">Tagihan Kerusakan</p>
                    <h2 class="text-lg font-semibold text-slate-900">{{ $invoice->invoice_number }}</h2>

                    <div class="mt-4 rounded-2xl bg-white border border-red-200 p-4 text-sm space-y-2">
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-600">Total biaya kerusakan</span>
                            <span class="font-semibold text-red-700">Rp {{ number_format($invoice->restoration_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-600">Deposit hangus (digunakan)</span>
                            <span class="font-semibold text-slate-900">- Rp {{ number_format($invoice->deposit_used, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1">
                            <span class="font-semibold text-red-700">Tagihan yang harus dibayar</span>
                            <span class="font-bold text-red-700 text-base">Rp {{ number_format($invoice->additional_charge, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between flex-wrap gap-3">
                        <span class="inline-flex items-center rounded-full px-4 py-1.5 text-sm font-bold
                            {{ $invoice->isPaid() ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ $invoice->status_label }}
                        </span>

                        @if(! $invoice->isPaid() && ! $invoice->isNotRequired())
                            <a href="{{ route('penyewaan.requests.deposit.damage-payment', $penyewaan) }}"
                               class="rounded-2xl bg-red-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition">
                               💳 Bayar Tagihan Sekarang →
                            </a>
                        @elseif($invoice->isPaid())
                            <p class="text-sm text-emerald-700 font-semibold">✓ Tagihan sudah dilunasi.</p>
                        @endif
                    </div>

                    @if($invoice->paid_at)
                        <p class="mt-3 text-xs text-slate-500">
                            Dibayar pada: {{ $invoice->paid_at->format('d M Y H:i') }}
                        </p>
                    @endif
                </section>
            @endif

            {{-- Status penyewaan akhir --}}
            @if($penyewaan->status === 'selesai')
                <section class="rounded-3xl border border-emerald-200 bg-emerald-50 shadow-sm p-6 text-center">
                    <p class="text-2xl mb-2">🎉</p>
                    <h2 class="text-lg font-semibold text-emerald-900">Penyewaan Selesai</h2>
                    <p class="mt-2 text-sm text-slate-600">Seluruh proses penyewaan telah selesai. Terima kasih.</p>
                </section>
            @elseif($penyewaan->status === 'menunggu_pembayaran_kerusakan')
                <section class="rounded-3xl border border-amber-200 bg-amber-50 shadow-sm p-6 text-center">
                    <p class="text-sm font-semibold text-amber-800">
                        ⚠ Penyewaan akan dinyatakan selesai setelah tagihan kerusakan dilunasi.
                    </p>
                </section>
            @elseif($penyewaan->status === 'pemeriksaan_akhir')
                <section class="rounded-3xl border border-slate-200 bg-slate-50 shadow-sm p-6 text-center">
                    <p class="text-sm text-slate-500">Pengelola sedang memproses pengembalian deposit Anda.</p>
                </section>
            @endif

        </div>
    </div>
</x-app-layout>