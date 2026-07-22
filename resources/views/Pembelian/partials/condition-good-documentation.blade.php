{{-- ══════════════════════════════════════════════════════════
    DOKUMENTASI KONDISI KOLEKSI (Kondisi Baik)
    Tampil di halaman detail pengajuan pembeli & pengelola
    ketika condition_check_status = 'good'
══════════════════════════════════════════════════════════ --}}
@if($pembelian->condition_check_status === 'good' && ($pembelian->condition_front_photo || $pembelian->condition_back_photo))
<div class="ps-card">
    <div class="ps-card-header">
        <div class="ps-card-header-accent" style="background:linear-gradient(180deg,#059669,#10b981);"></div>
        <h3>Dokumentasi Kondisi Koleksi Saat Diterima</h3>
        <span style="margin-left:auto;display:inline-flex;align-items:center;gap:.35rem;padding:.28rem .85rem;border-radius:99px;font-size:.7rem;font-weight:700;background:#d1fae5;color:#065f46;border:1px solid #6ee7b7;">
            ✅ Kondisi Baik
        </span>
    </div>
    <div class="ps-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Meta info waktu --}}
        @if($pembelian->received_at)
        <div style="display:flex;gap:.875rem;align-items:center;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:1rem;padding:.875rem 1rem;">
            <span style="font-size:1.25rem;">✅</span>
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.2rem;">Status Kondisi</div>
                <div style="font-size:.88rem;font-weight:700;color:#059669;">
                    Koleksi diterima dalam kondisi baik
                    @if($pembelian->received_at)
                        <span style="font-weight:400;color:#64748b;"> — {{ $pembelian->received_at->format('d M Y, H:i') }}</span>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Foto Depan & Belakang --}}
        @if($pembelian->condition_front_photo || $pembelian->condition_back_photo)
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Koleksi</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                @if($pembelian->condition_front_photo)
                <div>
                    <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Depan</div>
                    <img src="{{ asset('storage/' . $pembelian->condition_front_photo) }}"
                        style="width:100%;border-radius:.875rem;border:1.5px solid #bbf7d0;object-fit:cover;max-height:280px;cursor:zoom-in;"
                        alt="Foto Depan Koleksi"
                        onclick="openConditionLightbox(this.src, this.alt)">
                </div>
                @endif
                @if($pembelian->condition_back_photo)
                <div>
                    <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Belakang</div>
                    <img src="{{ asset('storage/' . $pembelian->condition_back_photo) }}"
                        style="width:100%;border-radius:.875rem;border:1.5px solid #bbf7d0;object-fit:cover;max-height:280px;cursor:zoom-in;"
                        alt="Foto Belakang Koleksi"
                        onclick="openConditionLightbox(this.src, this.alt)">
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Video kondisi (jika ada) --}}
        @if($pembelian->condition_video)
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Video Kondisi Koleksi</div>
            <video controls
                style="width:100%;border-radius:.875rem;border:1.5px solid #bbf7d0;max-height:320px;background:#000;">
                <source src="{{ asset('storage/' . $pembelian->condition_video) }}" type="video/mp4">
                Browser Anda tidak mendukung pemutaran video.
            </video>
        </div>
        @endif

    </div>
</div>

{{-- Lightbox untuk kondisi baik --}}
<div id="condition-lightbox" onclick="closeConditionLightbox(event)"
    style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.85);backdrop-filter:blur(4px);align-items:center;justify-content:center;cursor:zoom-out;">
    <span onclick="closeConditionLightbox({target:this})"
        style="position:absolute;top:1.25rem;right:1.5rem;color:#fff;font-size:2rem;font-weight:300;cursor:pointer;line-height:1;opacity:.7;"
        onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=.7">&times;</span>
    <img id="condition-lightbox-img" src="" alt=""
        style="max-width:90vw;max-height:90vh;border-radius:1rem;box-shadow:0 24px 80px rgba(0,0,0,.6);cursor:default;object-fit:contain;"
        onclick="event.stopPropagation()">
</div>
<script>
function openConditionLightbox(src, alt) {
    const lb = document.getElementById('condition-lightbox');
    document.getElementById('condition-lightbox-img').src = src;
    document.getElementById('condition-lightbox-img').alt = alt || '';
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeConditionLightbox(e) {
    const lb = document.getElementById('condition-lightbox');
    const closeBtn = lb.querySelector('span');
    if (e.target === lb || e.target === closeBtn) {
        lb.style.display = 'none';
        document.getElementById('condition-lightbox-img').src = '';
        document.body.style.overflow = '';
    }
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const lb = document.getElementById('condition-lightbox');
        if (lb) { lb.style.display = 'none'; document.body.style.overflow = ''; }
    }
});
</script>
@endif