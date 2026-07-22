@if($koleksiLain->isNotEmpty())
    <section class="related-section related-section--inline" aria-labelledby="related-heading">
        <header class="related-header">
            <h2 id="related-heading">Koleksi lainnya</h2>
        </header>
        <div class="related-scroll-wrap">
            <button type="button" class="scroll-btn scroll-btn--prev" data-scroll-target="related-track" aria-label="Geser ke kiri">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <div class="related-track" id="related-track" tabindex="0">
                @foreach($koleksiLain as $item)
                    <a href="{{ route('gallery.show', $item) }}" class="related-card">
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="" loading="lazy">
                        @else
                            <span class="related-placeholder"></span>
                        @endif
                        @include('gallery.partials.image-badges', ['koleksi' => $item])
                        <span class="related-card-overlay">
                            <span class="related-card-title">{{ $item->nama }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
            <button type="button" class="scroll-btn scroll-btn--next" data-scroll-target="related-track" aria-label="Geser ke kanan">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            </button>
        </div>
    </section>
@endif
