<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">Detail Koleksi</h2>
                <p class="mt-2 text-sm text-slate-500">Lihat informasi lengkap koleksi lukisan sebelum mengajukan penyewaan.</p>
            </div>
            <a href="{{ route('penyewaan.create', ['koleksi' => $painting->id]) }}" class="inline-flex items-center rounded-full bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Ajukan Penyewaan
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="bg-blue-600 px-6 py-7 text-white">
                    <h3 class="text-2xl font-semibold">{{ $painting->title }}</h3>
                    <p class="mt-2 text-sm text-blue-100">{{ $painting->artist }}</p>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid gap-6 lg:grid-cols-[1.4fr,0.9fr]">
                        <div class="space-y-6">
                            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-100">
                                @if($painting->image_url)
                                    <img src="{{ $painting->image_url }}" alt="{{ $painting->title }}" class="h-80 w-full object-cover">
                                @else
                                    <div class="flex h-80 items-center justify-center bg-slate-200 text-slate-500">Foto tidak tersedia</div>
                                @endif
                            </div>
                            <section class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                                <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Deskripsi Koleksi</h4>
                                <p class="mt-4 text-slate-700 leading-relaxed">{{ $painting->description }}</p>
                            </section>
                            <section class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Tarif per hari</p>
                                    <p class="mt-3 text-xl font-semibold text-slate-900">Rp {{ number_format($painting->daily_rate, 0, ',', '.') }}</p>
                                </div>
                                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Status</p>
                                    <p class="mt-3 text-lg font-semibold text-slate-900">{{ $painting->available ? 'Tersedia' : 'Tidak tersedia' }}</p>
                                </div>
                            </section>
                        </div>
                        <aside class="rounded-3xl border border-blue-100 bg-blue-50 p-6">
                            <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-600">Ringkasan</h4>
                            <dl class="mt-6 space-y-4 text-sm text-slate-700">
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="font-medium text-slate-600">Judul</dt>
                                    <dd class="font-semibold text-slate-900">{{ $painting->title }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="font-medium text-slate-600">Artis</dt>
                                    <dd class="font-semibold text-slate-900">{{ $painting->artist }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="font-medium text-slate-600">Tarif</dt>
                                    <dd class="font-semibold text-slate-900">Rp {{ number_format($painting->daily_rate, 0, ',', '.') }}/hari</dd>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <dt class="font-medium text-slate-600">Ketersediaan</dt>
                                    <dd class="font-semibold text-slate-900">{{ $painting->available ? 'Bisa disewa' : 'Saat ini tidak tersedia' }}</dd>
                                </div>
                            </dl>
                        </aside>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ route('penyewaan.index') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                            Kembali ke Katalog
                        </a>
                        <a href="{{ route('penyewaan.create', ['koleksi' => $painting->id]) }}" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 {{ $painting->available ? '' : 'opacity-50 pointer-events-none' }}">
                            Ajukan Penyewaan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
