<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-slate-900">Pengelolaan Deposit</h2>
                <p class="mt-1 text-sm text-slate-600">
                    SP-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}
                    &mdash; {{ $penyewaan->painting->title }}
                </p>
            </div>
            <a href="{{ route('pengelola.penyewaan.handover.show', $penyewaan) }}"
               class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                ← Kembali ke Serah Terima
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            {{-- Ringkasan deposit & pemeriksaan --}}
            <section class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 mb-5">Ringkasan Deposit & Pemeriksaan</p>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs text-slate-500">Penyewa</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $penyewaan->contact_name ?? $penyewaan->nama_instansi }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs text-slate-500">Nominal Deposit</p>
                        <p class="mt-1 text-sm font-semibold text-emerald-700">
                            Rp {{ number_format($depositAmount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="rounded-2xl {{ $serahTerima->has_damage ? 'bg-red-50 border-red-200' : 'bg-emerald-50 border-emerald-200' }} border p-4">
                        <p class="text-xs text-slate-500">Hasil Pemeriksaan</p>
                        <p class="mt-1 text-sm font-semibold {{ $serahTerima->has_damage ? 'text-red-700' : 'text-emerald-700' }}">
                            {{ $serahTerima->has_damage ? '✗ Ada Kerusakan' : '✓ Tidak Ada Kerusakan' }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                        <p class="text-xs text-slate-500">Diperiksa Oleh</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900">{{ $serahTerima->final_inspection_by }}</p>
                        <p class="text-xs text-slate-400">{{ $serahTerima->final_inspection_at->format('d M Y') }}</p>
                    </div>
                </div>

                @if($serahTerima->has_damage)
                    <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 p-4">
                        <div class="grid gap-4 sm:grid-cols-3 text-sm">
                            <div>
                                <p class="text-xs text-slate-500">Jenis Kerusakan</p>
                                <p class="mt-1 font-semibold text-slate-900">{{ $serahTerima->final_damage_type ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Tingkat Kerusakan</p>
                                <p class="mt-1 font-semibold text-slate-900">
                                    {{ ucfirst($serahTerima->final_damage_level ?? '-') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Estimasi Biaya</p>
                                <p class="mt-1 font-semibold text-red-700">
                                    Rp {{ number_format($serahTerima->final_damage_cost, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @if($serahTerima->final_damage_notes)
                            <p class="mt-3 text-xs text-slate-600">{{ $serahTerima->final_damage_notes }}</p>
                        @endif

                        {{-- Perbandingan biaya vs deposit --}}
                        @php
                            $damageCost = $serahTerima->final_damage_cost;
                            $excessCost = max(0, $damageCost - $depositAmount);
                            $sisa       = max(0, $depositAmount - $damageCost);
                        @endphp
                        <div class="mt-4 rounded-xl bg-white border border-red-200 p-4 text-sm">
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-600">Biaya kerusakan</span>
                                <span class="font-semibold text-red-700">Rp {{ number_format($damageCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-100">
                                <span class="text-slate-600">Deposit penyewa</span>
                                <span class="font-semibold text-slate-900">Rp {{ number_format($depositAmount, 0, ',', '.') }}</span>
                            </div>
                            @if($excessCost > 0)
                                <div class="flex justify-between py-1 mt-1">
                                    <span class="font-semibold text-red-700">Tagihan tambahan penyewa</span>
                                    <span class="font-bold text-red-700">Rp {{ number_format($excessCost, 0, ',', '.') }}</span>
                                </div>
                            @else
                                <div class="flex justify-between py-1 border-b border-slate-100">
                                    <span class="text-slate-600">Potongan dari deposit</span>
                                    <span class="font-semibold text-red-700">- Rp {{ number_format($damageCost, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between py-1 mt-1">
                                    <span class="font-semibold text-emerald-700">Sisa deposit dikembalikan</span>
                                    <span class="font-bold text-emerald-700">Rp {{ number_format($sisa, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="mt-4 flex justify-end">
                    <a href="{{ route('pengelola.deposit.final-inspection', $penyewaan) }}"
                       class="text-xs text-slate-500 hover:text-slate-700 underline">
                        Lihat detail pemeriksaan akhir
                    </a>
                </div>
            </section>

            {{-- Rekening Penyewa --}}
            <section class="rounded-3xl bg-white border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 mb-4">Data Rekening Penyewa</p>
                @if($penyewaan->bank_name && $penyewaan->account_number)
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <p class="text-xs text-slate-500">Bank</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">{{ $penyewaan->bank_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Nomor Rekening</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900 font-mono">{{ $penyewaan->account_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Nama Pemilik</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900">{{ $penyewaan->account_holder }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic">Data rekening belum diisi oleh penyewa.</p>
                @endif
            </section>

            {{-- ═══════════════════════════════════════════════════════
                 SUDAH DIPROSES
            ═══════════════════════════════════════════════════════ --}}

            @if($penyewaan->depositRefund)
                <section class="rounded-3xl border border-emerald-200 bg-emerald-50 shadow-sm p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Refund Deposit Selesai</p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-900">Deposit Telah Dikembalikan</h2>
                    @php $refund = $penyewaan->depositRefund; @endphp
                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 text-sm">
                        <div>
                            <p class="text-xs text-slate-500">Nominal Refund</p>
                            <p class="mt-1 font-semibold text-emerald-700">Rp {{ number_format($refund->refund_amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Potongan Kerusakan</p>
                            <p class="mt-1 font-semibold text-slate-900">Rp {{ number_format($refund->damage_deduction, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Tanggal Refund</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $refund->refund_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Diproses Oleh</p>
                            <p class="mt-1 font-semibold text-slate-900">{{ $refund->processed_by }}</p>
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
                </section>

            @elseif($penyewaan->damageInvoice)
                @php $invoice = $penyewaan->damageInvoice; @endphp
                <section class="rounded-3xl border border-red-200 bg-red-50 shadow-sm p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-red-700">Invoice Kerusakan</p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-900">{{ $invoice->invoice_number }}</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-3 text-sm">
                        <div>
                            <p class="text-xs text-slate-500">Deposit Hangus</p>
                            <p class="mt-1 font-semibold text-red-700">Rp {{ number_format($invoice->deposit_used, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Tagihan Tambahan</p>
                            <p class="mt-1 font-semibold text-red-700">Rp {{ number_format($invoice->additional_charge, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Status Pembayaran</p>
                            <span class="mt-1 inline-block rounded-full px-3 py-1 text-xs font-bold
                                {{ $invoice->isPaid() ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $invoice->status_label }}
                            </span>
                        </div>
                    </div>
                    @if($invoice->isPaid())
                        <p class="mt-4 text-sm text-emerald-700 font-semibold">✓ Penyewa sudah melunasi tagihan. Penyewaan selesai.</p>
                    @else
                        <p class="mt-4 text-sm text-amber-700">Menunggu penyewa melakukan pembayaran tagihan kerusakan.</p>
                    @endif
                </section>

            @else
                {{-- ═══════════════════════════════════════════════════════
                     FORM PROSES DEPOSIT
                ═══════════════════════════════════════════════════════ --}}

                @php
                    $hasDamage  = $serahTerima->has_damage;
                    $damageCost = $serahTerima->final_damage_cost ?? 0;
                    $excessCost = max(0, $damageCost - $depositAmount);
                    $sisa       = max(0, $depositAmount - $damageCost);
                @endphp

                @if(! $hasDamage || ($hasDamage && $damageCost <= $depositAmount))
                    {{-- FORM REFUND DEPOSIT --}}
                    <section class="rounded-3xl border border-blue-200 bg-blue-50 shadow-sm p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-blue-700">Aksi Diperlukan</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-900">
                            {{ $hasDamage ? 'Kembalikan Sisa Deposit (Setelah Potongan)' : 'Kembalikan Deposit' }}
                        </h2>
                        @if($hasDamage)
                            <div class="mt-3 rounded-2xl bg-amber-50 border border-amber-200 p-4 text-sm">
                                <p class="text-amber-700">
                                    Deposit dipotong Rp {{ number_format($damageCost, 0, ',', '.') }} untuk biaya kerusakan.
                                    @if($sisa > 0)
                                        Sisa <strong>Rp {{ number_format($sisa, 0, ',', '.') }}</strong> dikembalikan ke penyewa.
                                    @else
                                        Deposit habis digunakan. Tidak ada yang perlu dikembalikan.
                                    @endif
                                </p>
                            </div>
                        @endif

                        <form action="{{ route('pengelola.deposit.refund', $penyewaan) }}"
                              method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
                            @csrf

                            @if($errors->any())
                                <div class="rounded-2xl border border-red-200 bg-white px-4 py-3 text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">
                                        Nominal Refund (Rp) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="refund_amount" min="0"
                                           value="{{ old('refund_amount', $sisa) }}"
                                           class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    <p class="mt-1 text-xs text-slate-400">
                                        Maks: Rp {{ number_format($depositAmount, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">
                                        Tanggal Refund <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="refund_date"
                                           value="{{ old('refund_date', now()->format('Y-m-d')) }}"
                                           class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">
                                        Nama Bank <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="bank_name"
                                           value="{{ old('bank_name', $penyewaan->bank_name) }}"
                                           placeholder="Contoh: BCA, BNI, Mandiri"
                                           class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">
                                        Nomor Rekening <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="account_number"
                                           value="{{ old('account_number', $penyewaan->account_number) }}"
                                           class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">
                                        Nama Pemilik Rekening <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="account_holder"
                                           value="{{ old('account_holder', $penyewaan->account_holder) }}"
                                           class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700">
                                        Bukti Transfer <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="transfer_proof"
                                           accept="image/*,.pdf" required
                                           class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm
                                                  file:mr-4 file:rounded-full file:border-0 file:bg-blue-50
                                                  file:px-4 file:py-1.5 file:text-xs file:font-semibold file:text-blue-700
                                                  hover:file:bg-blue-100">
                                    <p class="mt-1 text-xs text-slate-400">JPG, PNG, atau PDF. Maks 5MB.</p>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700">Catatan</label>
                                <textarea name="notes" rows="2"
                                    class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                                    placeholder="Catatan tambahan (opsional)...">{{ old('notes') }}</textarea>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                        onclick="return confirm('Konfirmasi proses refund deposit kepada penyewa?')"
                                        class="rounded-2xl bg-blue-600 px-8 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                                    💸 Proses Refund Deposit →
                                </button>
                            </div>
                        </form>
                    </section>

                @else
                    {{-- FORM DAMAGE INVOICE (biaya > deposit) --}}
                    <section class="rounded-3xl border border-red-200 bg-red-50 shadow-sm p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-red-700">Aksi Diperlukan</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-900">Buat Invoice Tagihan Kerusakan</h2>
                        <p class="mt-2 text-sm text-slate-600">
                            Biaya kerusakan <strong>Rp {{ number_format($damageCost, 0, ',', '.') }}</strong>
                            melebihi deposit <strong>Rp {{ number_format($depositAmount, 0, ',', '.') }}</strong>.
                            Deposit penyewa hangus seluruhnya dan penyewa perlu membayar kekurangan
                            <strong class="text-red-700">Rp {{ number_format($excessCost, 0, ',', '.') }}</strong>.
                        </p>

                        <div class="mt-5 rounded-2xl bg-white border border-red-200 p-4 text-sm space-y-2">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Total biaya kerusakan</span>
                                <span class="font-semibold text-red-700">Rp {{ number_format($damageCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-100 pb-2">
                                <span class="text-slate-600">Deposit yang hangus</span>
                                <span class="font-semibold text-slate-900">- Rp {{ number_format($depositAmount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between pt-1">
                                <span class="font-semibold text-red-700">Tagihan tambahan penyewa</span>
                                <span class="font-bold text-red-700">Rp {{ number_format($excessCost, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <form action="{{ route('pengelola.deposit.damage-invoice', $penyewaan) }}"
                              method="POST" class="mt-6">
                            @csrf
                            <div class="rounded-2xl border border-slate-200 bg-white p-4 mb-5 text-sm text-slate-600">
                                <p>Invoice akan dibuat dan penyewa akan mendapat notifikasi untuk membayar tagihan melalui payment gateway.</p>
                            </div>
                            <button type="submit"
                                    onclick="return confirm('Buat invoice tagihan kerusakan untuk penyewa? Deposit akan hangus.')"
                                    class="rounded-2xl bg-red-600 px-8 py-3 text-sm font-semibold text-white hover:bg-red-700 transition">
                                Buat Invoice Tagihan Kerusakan →
                            </button>
                        </form>
                    </section>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>