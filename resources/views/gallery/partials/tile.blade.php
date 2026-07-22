@php
    $sizeClass = match ($loop->index % 5) {
        0 => 'tile--tall',
        2 => 'tile--wide',
        default => '',
    };
@endphp
<a href="{{ route('gallery.show', $koleksi) }}" class="gallery-tile {{ $sizeClass }}" aria-label="Lihat detail {{ $koleksi->nama }}">
    @if($koleksi->foto)
        <img src="{{ asset('storage/' . $koleksi->foto) }}" alt="" loading="lazy">
    @else
        <span class="tile-placeholder" aria-hidden="true"></span>
    @endif

    @include('gallery.partials.image-badges', ['koleksi' => $koleksi])

    <span class="tile-overlay">
        <span class="tile-overlay-inner">
            <span class="tile-title">{{ $koleksi->nama }}</span>
            @if($koleksi->seniman)
                <span class="tile-sub">{{ $koleksi->seniman }}</span>
            @endif
        </span>
    </span>
</a>
