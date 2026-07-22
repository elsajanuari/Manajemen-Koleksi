<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Riwayat Pembayaran</h2>
                <p class="mt-2 text-sm text-slate-500">Lihat semua transaksi Midtrans untuk invoice rental ini.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('penyewaan.requests.payment.status', $penyewaan) }}" class="inline-flex items-center rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Status Pembayaran</a>
                <a href="{{ route('penyewaan.requests.show', ['penyewaan' => $penyewaan->id]) }}" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.18em] text-slate-500">Invoice</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900">INV-{{ str_pad($penyewaan->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="space-y-1 text-sm text-slate-600">
                        <p>Metode pembayaran: Midtrans Snap</p>
                        <p>Status saat ini: <span class="font-semibold">{{ ucfirst($penyewaan->payment_status ?: 'unpaid') }}</span></p>
                    </div>
                </div>

                <div class="mt-8 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-slate-700">
                            <tr>
                                <th class="whitespace-nowrap px-4 py-3 text-left font-semibold">Waktu</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left font-semibold">Order ID</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left font-semibold">Metode</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left font-semibold">Jumlah</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left font-semibold">Status</th>
                                <th class="whitespace-nowrap px-4 py-3 text-left font-semibold">Detail</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse($payments as $payment)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-700">{{ $payment->created_at->format('d M Y H:i') }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-900">{{ $payment->order_id }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-900">{{ $payment->payment_type ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-900">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</td>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ ucfirst($payment->transaction_status ?? 'pending') }}</span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-700">
                                        <details class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                            <summary class="cursor-pointer font-semibold">Lihat payload</summary>
                                            <pre class="mt-2 max-h-48 overflow-auto text-xs text-slate-800">{{ json_encode($payment->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </details>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada transaksi pembayaran untuk invoice ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
