<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Pembayaran Penyewaan</h2>
                <p class="mt-2 text-sm text-slate-500">Selesaikan pembayaran untuk mengaktifkan rental Anda.</p>
            </div>
            <a href="{{ route('penyewaan.requests.show', ['penyewaan' => $penyewaan->id]) }}" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-5 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-100">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="rounded-3xl border border-red-200 bg-red-50 p-4 text-red-800 font-semibold mb-6">{{ session('error') }}</div>
            @endif

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm p-6">
                <h3 class="text-lg font-semibold text-slate-900">Detail Pembayaran</h3>
                
                <div class="mt-6 space-y-4">
                    <div class="flex justify-between py-2 border-b border-slate-200">
                        <span class="text-slate-600">Koleksi</span>
                        <span class="font-semibold text-slate-900">{{ $penyewaan->painting->title }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-200">
                        <span class="text-slate-600">Periode</span>
                        <span class="font-semibold text-slate-900">{{ $penyewaan->duration_days }} hari</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-slate-200">
                        <span class="text-slate-600">Nomor Referensi</span>
                        <span class="font-semibold text-slate-900">{{ $penyewaan->payment_reference }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-t-2 border-slate-300 bg-slate-50 px-4 rounded-2xl">
                        <span class="font-semibold text-slate-900">Total Pembayaran</span>
                        <span class="text-xl font-bold text-emerald-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-6 p-4 rounded-2xl bg-blue-50 border border-blue-200">
                    <p class="text-sm text-blue-900"><strong>ℹ️ Informasi:</strong> Klik tombol di bawah untuk membuka gateway pembayaran Midtrans dengan berbagai metode pembayaran.</p>
                </div>

                <button id="pay-button" class="mt-6 w-full rounded-3xl bg-emerald-600 px-6 py-3 text-center font-semibold text-white hover:bg-emerald-700 transition">
                    Bayar Sekarang dengan Midtrans
                </button>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            fetch('{{ route("penyewaan.requests.payment.process", $penyewaan) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: function() {
                            window.location.href = '{{ route("penyewaan.requests.payment.success", $penyewaan) }}';
                        },
                        onPending: function() {
                            window.location.href = '{{ route("penyewaan.requests.payment.status", $penyewaan) }}';
                        },
                        onError: function() {
                            window.location.href = '{{ route("penyewaan.requests.payment.failed", $penyewaan) }}';
                        },
                        onClose: function() {
                            alert('Pembayaran dibatalkan.');
                        }
                    });
                } else {
                    alert('Error: ' + (data.message || 'Gagal membuat transaksi'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            });
        });
    </script>
</x-app-layout>
