<x-app-layout>
    <x-slot name="header"></x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('gallery.partials.styles')

    <div class="page-gallery-detail">
        <div class="page-wrap">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('landing') }}">Beranda</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('gallery') }}">Galeri</a>
                <span aria-hidden="true">/</span>
                <span>{{ $koleksi->nama }}</span>
            </nav>

            <article class="detail-page">
                <div class="detail-layout">
                    <section class="detail-gallery" aria-label="Foto koleksi">
                        @if($koleksi->foto)
                            @php $fotoUrl = asset('storage/' . $koleksi->foto); @endphp
                            <button type="button" class="detail-gallery__trigger" data-lightbox-open data-lightbox-src="{{ $fotoUrl }}" aria-label="Perbesar foto {{ $koleksi->nama }}">
                                <img src="{{ $fotoUrl }}" alt="{{ $koleksi->nama }}">
                                <span class="detail-gallery__zoom">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35M11 8v6M8 11h6"/></svg>
                                    Klik untuk perbesar
                                </span>
                            </button>
                        @else
                            <p class="no-photo">Belum ada foto</p>
                        @endif
                    </section>

                    <section class="detail-panel">
                        @php
                            $yearLabel = match(strtolower($koleksi->kategori)) {
                                'lukisan' => 'Tahun Pembuatan',
                                'buku' => 'Tahun Terbit',
                                default => 'Tahun Pembuatan',
                            };
                            $authorLabel = strtolower($koleksi->kategori) === 'buku' ? 'Penulis' : 'Karya';
                        @endphp
                        <header>
                            <span class="detail-kategori">{{ ucfirst($koleksi->kategori) }}</span>
                            <h1>{{ $koleksi->nama }}</h1>
                            @if($koleksi->seniman)
                                <p class="detail-artist">{{ $authorLabel }} <strong>{{ $koleksi->seniman }}</strong></p>
                            @endif
                        </header>

                        @if($koleksi->deskripsi)
                            <p class="detail-desc">{{ $koleksi->deskripsi }}</p>
                        @endif

                        <div class="info-grid">
                            @if($koleksi->tahun)
                                <div class="info-item">
                                    <span class="label">{{ $yearLabel }}</span>
                                    <span class="value">{{ $koleksi->tahun }}</span>
                                </div>
                            @endif
                            @if(strtolower($koleksi->kategori) === 'lukisan')
                                <div class="info-item">
                                    <span class="label">Teknik / Media</span>
                                    <span class="value">{{ $koleksi->teknik_media ?? '-' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">Ukuran Lukisan</span>
                                    <span class="value">{{ $koleksi->ukuran_lukisan ?? '-' }}</span>
                                </div>
                            @endif
                            <div class="info-item">
                                <span class="label">Kondisi</span>
                                <span class="value">{{ $koleksi->current_kondisi ?? 'Belum diperiksa' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="label">Tanggal Masuk</span>
                                <span class="value">{{ $koleksi->created_at?->format('d M Y') ?? '-' }}</span>
                            </div>
                        </div>

                        <section class="detail-availability" aria-label="Ketersediaan">
                            <h2 class="sr-only">Ketersediaan koleksi</h2>
                            @if($koleksi->dapatDisewa() || $koleksi->dapatDibeli())
                                <p class="avail-intro">Koleksi ini tersedia untuk:</p>
                                <div class="availability availability--detail">
                                    @if($koleksi->dapatDisewa())
                                        <span class="avail-tag avail-sewa">Dapat disewa</span>
                                        @if($koleksi->daily_rate > 0)
                                            <span class="avail-tag avail-sewa" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-weight:700;">
                                                Rp {{ number_format($koleksi->daily_rate, 0, ',', '.') }} / hari
                                            </span>
                                        @endif
                                    @endif
                                    @if($koleksi->dapatDibeli())
                                        <span class="avail-tag avail-beli">Dapat dibeli</span>
                                        @if($koleksi->sale_price > 0)
                                            <span class="avail-tag avail-beli" style="background:#fdf4ff;color:#7e22ce;border:1px solid #e9d5ff;font-weight:700;">
                                                Rp {{ number_format($koleksi->sale_price, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            @else
                                <p class="avail-intro avail-intro--muted">Koleksi ini hanya dipamerkan di museum dan tidak ditawarkan untuk disewa atau dibeli.</p>
                            @endif
                        </section>

                        <footer id="aksi" class="detail-actions">
                            @if($koleksi->dapatDisewa())
                                <a href="{{ route('penyewaan.step1', ['koleksi' => $koleksi->id]) }}" class="btn btn-sewa">
                                    Ajukan sewa
                                </a>
                            @endif
                            @if($koleksi->dapatDibeli())
                                <a href="{{ route('pembelian.step1', ['koleksi' => $koleksi->id]) }}" class="btn btn-beli">Ajukan pembelian</a>
                            @endif
                            <a href="{{ route('gallery') }}" class="btn btn-ghost">Kembali ke galeri</a>
                        </footer>
                    </section>
                </div>

                @include('gallery.partials.related-scroll', ['koleksiLain' => $koleksiLain])
            </article>

            <footer class="site-footer site-footer--compact">
                &copy; {{ date('Y') }} Museum MK Lesmana
            </footer>
        </div>

        @if($koleksi->foto)
            <dialog class="lightbox" id="gallery-lightbox" aria-label="Pratinjau foto koleksi">
                <button type="button" class="lightbox__close" data-lightbox-close aria-label="Tutup">&times;</button>
                <figure class="lightbox__figure">
                    <img src="{{ asset('storage/' . $koleksi->foto) }}" alt="{{ $koleksi->nama }}">
                    <figcaption>{{ $koleksi->nama }}</figcaption>
                </figure>
            </dialog>
        @endif

        @include('gallery.partials.scripts')
    </div>
</x-app-layout>
