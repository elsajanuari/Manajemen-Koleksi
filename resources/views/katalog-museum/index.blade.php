@extends('layouts.museum-katalog')

@section('content')
    <div class="border-b border-slate-200/80 bg-gradient-to-br from-[#5B79B6]/10 via-white to-sky-50/40">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#5B79B6]">Sewa &amp; beli karya</p>
            <h1 class="mt-3 font-['Playfair_Display'] text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">
                Katalog koleksi museum
            </h1>
            <p class="mt-4 max-w-2xl text-lg leading-relaxed text-slate-600">
                Jelajahi lukisan pilihan museum. Lihat detail karya, status ketersediaan, lalu lanjutkan ke
                <span class="font-medium text-slate-800">pengajuan penyewaan</span> atau
                <span class="font-medium text-slate-800">pembelian koleksi</span>.
            </p>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="mb-6 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-900">{{ session('info') }}</div>
        @endif

        <form method="GET" action="{{ route('katalog-museum.index') }}" class="mb-10 flex flex-col gap-4 rounded-3xl border border-slate-200/80 bg-white p-5 shadow-sm lg:flex-row lg:items-end">
            <div class="flex-1">
                <label for="search" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Cari</label>
                <div class="mt-2">
                    <input type="search" id="search" name="search" value="{{ $search }}"
                        placeholder="Judul, pelukis, atau deskripsi…"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm outline-none ring-[#5B79B6]/30 transition placeholder:text-slate-400 focus:border-[#5B79B6] focus:bg-white focus:ring-2">
                </div>
            </div>
            <div class="w-full lg:max-w-xs">
                <label for="kategori" class="text-xs font-semibold uppercase tracking-wider text-slate-500">Kategori</label>
                <div class="mt-2">
                    <select id="kategori" name="kategori"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3 text-sm outline-none ring-[#5B79B6]/30 transition focus:border-[#5B79B6] focus:bg-white focus:ring-2">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $kategori === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-2xl bg-[#5B79B6] px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#4a6499]">
                    Terapkan
                </button>
                <a href="{{ route('katalog-museum.index') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Reset
                </a>
            </div>
        </form>

        <div class="grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
            @forelse($paintings as $painting)
                <a href="{{ route('katalog-museum.show', $painting->slug) }}"
                    class="group relative flex flex-col overflow-hidden rounded-3xl border border-slate-200/90 bg-white shadow-sm ring-0 transition duration-300 hover:-translate-y-1 hover:border-[#5B79B6]/25 hover:shadow-xl hover:shadow-[#5B79B6]/10">
                    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                        @if($painting->image_url)
                            <img src="{{ $painting->image_url }}" alt="{{ $painting->title }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        @else
                            <div class="flex h-full items-center justify-center text-sm text-slate-400">Tanpa gambar</div>
                        @endif
                        <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                            <span class="rounded-full bg-white/95 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-slate-700 shadow-sm backdrop-blur">
                                {{ $painting->category }}
                            </span>
                            @if($painting->available)
                                <span class="rounded-full bg-emerald-500/95 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white shadow-sm">Sewa tersedia</span>
                            @else
                                <span class="rounded-full bg-rose-500/95 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white shadow-sm">Sewa penuh</span>
                            @endif
                            @if($painting->isPurchasable())
                                <span class="rounded-full bg-indigo-500/95 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white shadow-sm">Dijual</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <h2 class="font-['Playfair_Display'] text-xl font-semibold text-slate-900 transition group-hover:text-[#5B79B6]">
                            {{ $painting->title }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $painting->artist }}</p>
                        <div class="mt-4 flex flex-wrap gap-4 text-sm">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Sewa / hari</p>
                                <p class="mt-0.5 font-semibold text-slate-900">Rp {{ number_format($painting->daily_rate, 0, ',', '.') }}</p>
                            </div>
                            @if($painting->sale_price)
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Harga jual</p>
                                    <p class="mt-0.5 font-semibold text-indigo-700">Rp {{ number_format($painting->sale_price, 0, ',', '.') }}</p>
                                </div>
                            @endif
                        </div>
                        <span class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-[#5B79B6]">
                            Lihat detail
                            <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-3xl border border-dashed border-slate-200 bg-white py-20 text-center text-slate-500">
                    Tidak ada karya yang cocok dengan filter Anda.
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $paintings->links() }}
        </div>
    </div>
@endsection
