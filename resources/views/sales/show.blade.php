@extends('layouts.public')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div>
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full rounded-lg shadow">
            @else
                <div class="w-full h-96 bg-gray-300 rounded-lg flex items-center justify-center">
                    <span class="text-gray-500">No Image</span>
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
            <p class="text-lg text-gray-600 mb-2">{{ $product->category }}</p>
            <p class="text-3xl font-bold text-blue-600 mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mb-4">Stok: {{ $product->stock }}</p>

            <div class="mb-6">
                <h3 class="font-semibold mb-2">Deskripsi</h3>
                <p class="text-gray-700">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>
            </div>

            <form action="{{ route('sales.addToCart', $product) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">Tambah ke Keranjang</button>
            </form>

            <div class="mt-8">
                <h3 class="font-semibold mb-4">Informasi Pengiriman</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Pengiriman ke seluruh Indonesia</li>
                    <li>• Biaya pengiriman dihitung saat checkout</li>
                    <li>• Estimasi pengiriman 3-7 hari kerja</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Produk Terkait</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Placeholder for related products -->
            <div class="text-center py-8 text-gray-500">
                Produk terkait akan ditampilkan di sini
            </div>
        </div>
    </div>
</div>
@endsection