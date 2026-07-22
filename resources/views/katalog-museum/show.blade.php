@extends('layouts.museum-katalog')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="mb-6 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-900">{{ session('info') }}</div>
        @endif

        <nav class="mb-8 text-sm text-slate-500">
            <a href="{{ route('katalog-museum.index') }}" class="font-medium text-[#5B79B6] hover:underline">Katalog</a>
            <span class="mx-2">/</span>
            <span class="text-slate-700">{{ $painting->title }}</span>
        </nav>

        <div class="grid gap-12 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)] lg:gap-16">
            <div class="space-y-8">
                <div class="overflow-hidden rounded-[2rem] border border-slate-200/90 bg-white shadow-lg shadow-slate-200/50 ring-1 ring-slate-100">
                    <div class="aspect-[4/3] bg-slate-100">
                        @if($painting->image_url)
                            <img id="main-art-img" src="{{ $painting->image_url }}" alt="{{ $painting->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-slate-400">Tanpa gambar utama</div>
                        @endif
                    </div>
                </div>

                @php $gallery = $painting->gallery_image_urls; @endphp
                @if(count($gallery))
                    <div>
                        <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Galeri</h2>
                        <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                            @foreach($gallery as $url)
                                <button type="button" onclick="(function(u){var m=document.getElementById('main-art-img');if(m)m.src=u;})({{ json_encode($url) }})"
                                    class="group overflow-hidden rounded-2xl border border-slate-200 bg-white ring-0 transition hover:ring-2 hover:ring-[#5B79B6]/40">
                                    <img src="{{ $url }}" alt="" class="aspect-square w-full object-cover transition group-hover:scale-105">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="lg:pt-4">
                <div class="flex flex-wrap gap-2">
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700">{{ $painting->category }}</span>
                    @if($painting->available)
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">Tersedia disewa</span>
                    @else
                        <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-800">Tidak tersedia disewa</span>
                    @endif
                    @if($painting->isForSale())
                        <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-800">Dijual — stok tersedia</span>
                    @elseif($painting->sale_price)
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-900">Harga jual tertera</span>
                    @endif
                </div>

                <h1 class="mt-4 font-['Playfair_Display'] text-4xl font-semibold leading-tight text-slate-900 sm:text-5xl">
                    {{ $painting->title }}
                </h1>
                <p class="mt-2 text-lg text-slate-600">{{ $painting->artist }}</p>

                <dl class="mt-8 grid grid-cols-2 gap-4 text-sm sm:grid-cols-3">
                    @if($painting->year_created)
                        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Tahun</dt>
                            <dd class="mt-1 font-semibold text-slate-900">{{ $painting->year_created }}</dd>
                        </div>
                    @endif
                    @if($painting->media)
                        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Media</dt>
                            <dd class="mt-1 font-semibold text-slate-900">{{ $painting->media }}</dd>
                        </div>
                    @endif
                    @if($painting->dimensions)
                        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                            <dt class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Ukuran</dt>
                            <dd class="mt-1 font-semibold text-slate-900">{{ $painting->dimensions }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="mt-10 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl border border-slate-200 bg-gradient-to-br from-white to-slate-50 p-6 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Harga sewa</p>
                        <p class="mt-2 text-2xl font-bold text-slate-900">Rp {{ number_format($painting->daily_rate, 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs text-slate-500">Per hari — proses melalui pengajuan penyewaan</p>
                    </div>
                    <div class="rounded-3xl border border-indigo-100 bg-gradient-to-br from-indigo-50/80 to-white p-6 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-wider text-indigo-800/80">Harga jual</p>
                        @if($painting->sale_price)
                            <p class="mt-2 text-2xl font-bold text-indigo-900">Rp {{ number_format($painting->sale_price, 0, ',', '.') }}</p>
                            <p class="mt-1 text-xs text-indigo-800/70">Harga tetap (fixed price)</p>
                        @else
                            <p class="mt-2 text-lg font-medium text-slate-500">Hubungi museum</p>
                        @endif
                    </div>
                </div>

                <div class="mt-10 space-y-4">
                    <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Deskripsi</h2>
                    <div class="max-w-none text-base leading-relaxed text-slate-700">
                        <p>{{ $painting->description }}</p>
                    </div>
                </div>

                @if($painting->extra_info)
                    <div class="mt-8 rounded-3xl border border-slate-100 bg-slate-50/80 p-6">
                        <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Informasi tambahan</h2>
                        <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-slate-700">{{ $painting->extra_info }}</p>
                    </div>
                @endif

                <div class="mt-12 flex flex-col gap-3 sm:flex-row">
                    @auth
                        @if(auth()->user()->role === 'pengguna')
                            @if($painting->available)
                                <a href="{{ route('katalog-museum.ajukan-sewa', $painting->slug) }}"
                                    class="inline-flex flex-1 items-center justify-center rounded-2xl bg-[#5B79B6] px-6 py-4 text-center text-sm font-semibold text-white shadow-lg shadow-[#5B79B6]/25 transition hover:bg-[#4a6499]">
                                    Ajukan penyewaan
                                </a>
                            @else
                                <span class="inline-flex flex-1 cursor-not-allowed items-center justify-center rounded-2xl bg-slate-200 px-6 py-4 text-center text-sm font-semibold text-slate-500">
                                    Penyewaan tidak tersedia
                                </span>
                            @endif

                        @elseif(auth()->user()->role === 'pengelola')
                            {{-- Pengelola lihat pengajuan dari dashboard, bukan dari sini --}}
                            <a href="{{ route('pengelola.penyewaan.index') }}"
                                class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 px-6 py-4 text-center text-sm text-slate-600 hover:bg-slate-100 transition">
                                Lihat pengajuan penyewaan
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="inline-flex flex-1 items-center justify-center rounded-2xl bg-[#5B79B6] px-6 py-4 text-center text-sm font-semibold text-white shadow-lg transition hover:bg-[#4a6499]">
                            Masuk untuk mengajukan penyewaan
                        </a>
                    @endauth

                    @if($painting->isForSale())
                        <a href="{{ route('katalog-museum.beli', $painting->slug) }}"
                            class="inline-flex flex-1 items-center justify-center rounded-2xl border-2 border-indigo-600 bg-white px-6 py-4 text-center text-sm font-semibold text-indigo-700 transition hover:bg-indigo-50">
                            Beli koleksi
                        </a>
                    @else
                        <a href="{{ route('pembelian.index') }}"
                            class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 px-6 py-4 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Lihat katalog pembelian
                        </a>
                    @endif
                </div>
                <p class="mt-4 text-xs text-slate-500">Pengajuan penyewaan menggunakan alur langkah yang sudah ada; karya ini otomatis terpilih di formulir.</p>
            </div>
        </div>

        @if($related->isNotEmpty())
            <section class="mt-20 border-t border-slate-200 pt-14">
                <h2 class="font-['Playfair_Display'] text-2xl font-semibold text-slate-900">Karya terkait</h2>
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($related as $item)
                        <a href="{{ route('katalog-museum.show', $item->slug) }}" class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                                @if($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="" class="h-full w-full object-cover transition group-hover:scale-105">
                                @endif
                            </div>
                            <div class="p-4">
                                <p class="font-medium text-slate-900 group-hover:text-[#5B79B6]">{{ $item->title }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $item->artist }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
