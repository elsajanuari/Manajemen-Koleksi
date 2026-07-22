@extends('layouts.public')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Penjualan Produk Museum</h1>

    <!-- Search and Filter -->
    <div class="mb-8 bg-white p-6 rounded-lg shadow">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="md:w-48">
                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Kategori</option>
                    <option value="lukisan_replika" {{ request('category') == 'lukisan_replika' ? 'selected' : '' }}>Lukisan Replika</option>
                    <option value="merchandise" {{ request('category') == 'merchandise' ? 'selected' : '' }}>Merchandise</option>
                    <option value="souvenir" {{ request('category') == 'souvenir' ? 'selected' : '' }}>Souvenir</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Cari</button>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="aspect-w-1 aspect-h-1 bg-gray-200">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-500">No Image</span>
                    </div>
                @endif
            </div>
            <div class="p-4">
                <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ $product->category }}</p>
                <p class="text-lg font-bold text-blue-600 mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500 mb-4">Stok: {{ $product->stock }}</p>
                <div class="flex gap-2">
                    <a href="{{ route('sales.show', $product) }}" class="flex-1 bg-gray-600 text-white text-center py-2 rounded hover:bg-gray-700 transition">Lihat Detail</a>
                    <form action="{{ route('sales.addToCart', $product) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500">Tidak ada produk ditemukan.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="mt-8">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection