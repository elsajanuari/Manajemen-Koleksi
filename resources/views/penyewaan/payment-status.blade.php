<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Status Pembayaran</h2>
                <p class="mt-2 text-sm text-slate-500">Pantau status pembayaran secara realtime untuk pengajuan ini.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('penyewaan.requests.payment.history', ['penyewaan' => $penyewaan->id]) }}" class="inline-flex items-center rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Riwayat Pembayaran</a>
                <a href="{{ route('penyewaan.requests.show', ['penyewaan' => $penyewaan->id]) }}" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-700">Status Pembayaran Saat Ini</p>
                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center rounded-full bg-{{ $penyewaan->payment_status === 'paid' ? 'emerald' : ($penyewaan->payment_status === 'pending' ? 'amber' : ($penyewaan->payment_status === 'failed' || $penyewaan->payment_status === 'expired' ? 'rose' : 'slate')) }}-100 px-4 py-2 text-sm font-semibold text-{{ $penyewaan->payment_status === 'paid' ? 'emerald' : ($penyewaan->payment_status === 'pending' ? 'amber' : ($penyewaan->payment_status === 'failed' || $penyewaan->payment_status === 'expired' ? 'rose' : 'slate')) }}-700">
                                {{ ucfirst($penyewaan->payment_status ?: 'unpaid') }}
                            </span>
                            <span class="text-sm text-slate-600">Status rental: <strong>{{ ucfirst($penyewaan->status) }}</strong></span>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 p-6">
                        <h3 class="text-lg font-semibold text-slate-900">Info Pembayaran</h3>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2 text-sm text-slate-700">
                            <div>
                                <p class="font-semibold text-slate-900">Total tagihan</p>
                                <p>Rp {{ number_format(($penyewaan->painting->daily_rate ?? 0) * $penyewaan->duration_days + round(($penyewaan->painting->daily_rate ?? 0) * $penyewaan->duration_days * 0.11) + round(($penyewaan->painting->daily_rate ?? 0) * $penyewaan->duration_days * 0.5), 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">Order ID</p>
                                <p>{{ $penyewaan->payment_reference ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">Nama</p>
                                <p>{{ $penyewaan->invoice_name ?? $penyewaan->contact_name }}</p>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">Email</p>
                                <p>{{ $penyewaan->invoice_email ?? $penyewaan->contact_email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-slate-50 p-6 text-sm text-slate-700">
                        <p class="font-semibold text-slate-900">Panduan</p>
                        <ol class="mt-3 list-decimal space-y-2 pl-5">
                            <li>Tekan tombol bayar untuk membuka Midtrans Snap.</li>
                            <li>Pilih metode transfer bank, virtual account, QRIS, atau e-wallet.</li>
                            <li>Selesaikan pembayaran, lalu lihat status pada halaman ini.</li>
                        </ol>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <a href="{{ route('penyewaan.requests.payment.gateway', $penyewaan) }}" class="inline-flex items-center justify-center rounded-3xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white hover:bg-emerald-700">Lihat Instruksi Pembayaran</a>
                        <a href="{{ route('penyewaan.requests.payment.history', ['penyewaan' => $penyewaan->id]) }}" class="inline-flex items-center justify-center rounded-3xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Lihat Riwayat Penuh</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($penyewaan->payment_status === 'pending')
        <script>
            const checkPaymentStatus = () => {
                fetch('{{ route('penyewaan.requests.payment.check', ['penyewaan' => $penyewaan->id]) }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.payment_status === 'paid') {
                            window.location.href = '{{ route('penyewaan.requests.payment.success', ['penyewaan' => $penyewaan->id]) }}';
                        } else if (data.payment_status === 'failed' || data.payment_status === 'expired') {
                            window.location.href = '{{ route('penyewaan.requests.payment.failed', ['penyewaan' => $penyewaan->id]) }}';
                        }
                    })
                    .catch(error => {
                        console.error('Error checking payment status:', error);
                    });
            };

            setInterval(checkPaymentStatus, 10000);
        </script>
    @endif
</x-app-layout>
