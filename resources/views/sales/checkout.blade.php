@extends('layouts.public')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

    <form action="{{ route('sales.processCheckout') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @csrf

        <!-- Customer Information -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Informasi Pembeli</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="customer_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="customer_email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="tel" name="customer_phone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman</label>
                        <textarea name="customer_address" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Ringkasan Pesanan</h2>

                <div class="space-y-3">
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                        @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                        <div class="flex justify-between">
                            <span>{{ $item['name'] }} ({{ $item['quantity'] }}x)</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <hr class="my-4">

                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Ongkir</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-semibold text-lg">Lanjut Pembayaran</button>
        </div>
    </form>
</div>
@endsection