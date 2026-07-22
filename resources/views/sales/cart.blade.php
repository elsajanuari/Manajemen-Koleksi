@extends('layouts.public')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

    @if(empty($cart))
        <div class="text-center py-12">
            <p class="text-gray-500 mb-4">Keranjang Anda kosong.</p>
            <a href="{{ route('sales.index') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">Lanjut Belanja</a>
        </div>
    @else
        <form action="{{ route('sales.updateCart') }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold">Produk di Keranjang</h2>
                </div>

                <div class="divide-y divide-gray-200">
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                        @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                        <div class="p-6 flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gray-200 rounded">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover rounded">
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold">{{ $item['name'] }}</h3>
                                <p class="text-gray-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <label class="text-sm">Qty:</label>
                                <input type="number" name="quantities[{{ $id }}]" value="{{ $item['quantity'] }}" min="1" class="w-16 px-2 py-1 border border-gray-300 rounded">
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                <a href="{{ route('sales.removeFromCart', $id) }}" class="text-red-600 text-sm hover:text-red-800">Hapus</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Total:</span>
                        <span class="text-2xl font-bold text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('sales.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">Lanjut Belanja</a>
                <div class="space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">Update Keranjang</button>
                    <a href="{{ route('sales.checkout') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">Checkout</a>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection