<span class="image-badges">
    <span class="image-badge image-badge--category">{{ ucfirst($koleksi->kategori) }}</span>
    @if($koleksi->dapatDisewa())
        <span class="image-badge image-badge--sewa">Dapat disewa</span>
    @endif
    @if($koleksi->dapatDibeli())
        <span class="image-badge image-badge--beli">Dapat dibeli</span>
    @endif
    @if(!$koleksi->dapatDisewa() && !$koleksi->dapatDibeli())
        <span class="image-badge image-badge--display">Hanya dipamerkan</span>
    @endif
</span>
