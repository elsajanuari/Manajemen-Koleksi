<x-app-layout>
    <x-slot name="header"></x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('gallery.partials.styles')

    <div class="page-gallery-index">
        <form method="GET" action="{{ route('gallery') }}" class="gallery-form">
            <section class="gallery-hero" aria-labelledby="gallery-title">
                <div class="gallery-hero__bg" aria-hidden="true">
                    @if($heroPhotoUrl)
                        <img src="{{ $heroPhotoUrl }}" alt="" class="gallery-hero__photo">
                    @endif
                    <span class="gallery-hero__overlay"></span>
                </div>
                <div class="gallery-hero__inner">
                    <h1 id="gallery-title">Galeri Koleksi</h1>
                    <p class="gallery-hero__lead">Jelajahi lukisan dan buku bersejarah Museum MK Lesmana</p>
                    <label class="hero-search" for="search">
                        <span class="sr-only">Cari koleksi</span>
                        <svg class="hero-search__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                        <input id="search" name="search" type="search" placeholder="Cari nama koleksi, seniman, tahun..." value="{{ $search ?? '' }}" autocomplete="off" />
                        <button type="submit" class="btn btn-hero">Cari</button>
                    </label>
                </div>
            </section>

            <section class="filter-bar" aria-label="Filter koleksi">
                <div class="filter-bar__inner page-wrap">
                    <label class="filter-select">
                        <span class="sr-only">Kategori</span>
                        <select id="kategori" name="kategori" onchange="this.form.submit()">
                            <option value="">Semua kategori</option>
                            <option value="lukisan" {{ ($kategoriFilter ?? '') === 'lukisan' ? 'selected' : '' }}>Lukisan</option>
                            <option value="buku" {{ ($kategoriFilter ?? '') === 'buku' ? 'selected' : '' }}>Buku</option>
                        </select>
                    </label>
                    <label class="filter-select">
                        <span class="sr-only">Ketersediaan</span>
                        <select id="status" name="status" onchange="this.form.submit()">
                            <option value="">Semua ketersediaan</option>
                            @foreach (\App\Models\Koleksi::statusSewaPublicOptions() as $value => $option)
                                <option value="{{ $value }}" {{ ($statusFilter ?? '') === $value ? 'selected' : '' }}>{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                    </label>
                    @if($search || $kategoriFilter || $statusFilter)
                        <a href="{{ route('gallery') }}" class="filter-reset">Reset filter</a>
                    @endif
                </div>
            </section>
        </form>

        <main class="page-wrap gallery-main" id="collection-list">
            <div class="gallery-meta-row">
                <p class="gallery-count">
                    <strong>{{ $koleksis->total() }}</strong> koleksi
                    @if($search || $kategoriFilter || $statusFilter)
                        <span class="gallery-count__hint">· filter aktif</span>
                    @endif
                </p>
            </div>

            @if($koleksis->isNotEmpty())
                <div class="masonry-grid" id="gallery-items">
                    @foreach($koleksis as $koleksi)
                        <div class="gallery-item" style="{{ $loop->index >= 10 ? 'display:none;' : '' }}">
                            @include('gallery.partials.tile', ['koleksi' => $koleksi])
                        </div>
                    @endforeach
                </div>

                <div class="load-more-wrap" id="load-more-wrap" style="display: none;">
                    <button type="button" id="load-more-button" class="btn btn-load-more">Tampilkan lebih banyak</button>
                </div>

                @if ($koleksis->hasMorePages())
                    <div class="pagination-subtle" id="pagination-links" style="display: none;">
                        {{ $koleksis->links('vendor.pagination.gallery') }}
                    </div>
                @endif

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const loadMoreWrap = document.getElementById('load-more-wrap');
                        const loadMoreButton = document.getElementById('load-more-button');
                        const paginationLinks = document.getElementById('pagination-links');
                        const galleryItems = Array.from(document.querySelectorAll('#gallery-items .gallery-item'));
                        const MAX_VISIBLE = 50;
                        const STEP = 10;

                        function updateLoadMore() {
                            const hiddenItems = galleryItems.filter(item => item.style.display === 'none');
                            const visibleCount = galleryItems.length - hiddenItems.length;

                            if (galleryItems.length <= STEP || visibleCount >= galleryItems.length || visibleCount >= MAX_VISIBLE) {
                                loadMoreWrap.style.display = 'none';
                            } else {
                                loadMoreWrap.style.display = 'block';
                            }

                            if (!hiddenItems.length) {
                                if (paginationLinks) {
                                    paginationLinks.style.display = 'block';
                                }
                            } else {
                                if (paginationLinks) {
                                    paginationLinks.style.display = 'none';
                                }
                            }
                        }

                        function showNextBatch() {
                            const hiddenItems = galleryItems.filter(item => item.style.display === 'none');
                            for (let i = 0; i < STEP && i < hiddenItems.length; i += 1) {
                                hiddenItems[i].style.display = '';
                            }
                            updateLoadMore();
                        }

                        if (galleryItems.length > STEP) {
                            updateLoadMore();
                        }

                        if (loadMoreButton) {
                            loadMoreButton.addEventListener('click', showNextBatch);
                        }
                    });
                </script>
            @else
                <div class="empty-state">
                    <h2>Tidak ada koleksi ditemukan</h2>
                    <p>Coba kata kunci lain atau reset filter.</p>
                    <a href="{{ route('gallery') }}" class="btn btn-outline">Lihat semua koleksi</a>
                </div>
            @endif
        </main>

        <footer class="site-footer">
            &copy; {{ date('Y') }} Museum MK Lesmana
        </footer>
    </div>
</x-app-layout>
