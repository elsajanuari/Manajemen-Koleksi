{{-- ══════════════════════════════════════════════════════════
    DOKUMENTASI KONDISI KOLEKSI SAAT DITERIMA PENYEWA
    Tampil di halaman detail pengajuan penyewa & pengelola
    — kondisi baik (condition_check_status = 'good')
    — ada kerusakan (condition_check_status = 'damaged')
══════════════════════════════════════════════════════════ --}}

{{-- ── KONDISI BAIK ── --}}
@if($serahTerima && $serahTerima->condition_check_status === 'good'
    && ($serahTerima->condition_front_photo || $serahTerima->condition_back_photo))
<div class="ps-card">
    <div class="ps-card-header">
        <div class="ps-card-header-accent" style="background:linear-gradient(180deg,#059669,#10b981);"></div>
        <h3>Dokumentasi Kondisi Koleksi Saat Diterima</h3>
        <span style="margin-left:auto;display:inline-flex;align-items:center;gap:.35rem;padding:.28rem .85rem;border-radius:99px;font-size:.7rem;font-weight:700;background:#d1fae5;color:#065f46;border:1px solid #6ee7b7;">
            ✅ Kondisi Baik
        </span>
    </div>
    <div class="ps-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

        @if($serahTerima->confirmed_received_at)
        <div style="display:flex;gap:.875rem;align-items:center;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:1rem;padding:.875rem 1rem;">
            <span style="font-size:1.25rem;">✅</span>
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.2rem;">Status Kondisi</div>
                <div style="font-size:.88rem;font-weight:700;color:#059669;">
                    Koleksi diterima dalam kondisi baik
                    <span style="font-weight:400;color:#64748b;"> — {{ $serahTerima->confirmed_received_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Foto Depan & Belakang --}}
        @if($serahTerima->condition_front_photo || $serahTerima->condition_back_photo)
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Koleksi</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                @if($serahTerima->condition_front_photo)
                <div>
                    <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Depan</div>
                    <img src="{{ asset('storage/' . $serahTerima->condition_front_photo) }}"
                        style="width:100%;border-radius:.875rem;border:1.5px solid #bbf7d0;object-fit:cover;max-height:280px;cursor:zoom-in;"
                        alt="Foto Depan Koleksi Saat Diterima"
                        onclick="openConditionLightbox(this.src, this.alt)">
                </div>
                @endif
                @if($serahTerima->condition_back_photo)
                <div>
                    <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Belakang</div>
                    <img src="{{ asset('storage/' . $serahTerima->condition_back_photo) }}"
                        style="width:100%;border-radius:.875rem;border:1.5px solid #bbf7d0;object-fit:cover;max-height:280px;cursor:zoom-in;"
                        alt="Foto Belakang Koleksi Saat Diterima"
                        onclick="openConditionLightbox(this.src, this.alt)">
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Video kondisi (opsional) --}}
        @if($serahTerima->condition_video)
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Video Kondisi Koleksi</div>
            <video controls style="width:100%;border-radius:.875rem;border:1.5px solid #bbf7d0;max-height:320px;background:#000;">
                <source src="{{ asset('storage/' . $serahTerima->condition_video) }}" type="video/mp4">
                Browser Anda tidak mendukung pemutaran video.
            </video>
        </div>
        @endif

    </div>
</div>
@endif

{{-- ── KERUSAKAN ── --}}
@if($serahTerima && $serahTerima->condition_check_status === 'damaged'
    && $serahTerima->arrival_damage_reported_at
    && (
        !empty($serahTerima->arrival_damage_photos)
        || !empty($serahTerima->arrival_damage_checklist)
        || $serahTerima->arrival_condition_front_photo
        || $serahTerima->arrival_condition_back_photo
        || $serahTerima->damage_video_path
        || !empty($serahTerima->packing_condition_photos)
        || !empty($serahTerima->courier_receipt_photos)
    ))
<div class="ps-card">
    <div class="ps-card-header">
        <div class="ps-card-header-accent" style="background:linear-gradient(180deg,#dc2626,#ef4444);"></div>
        <h3>Dokumentasi Kerusakan Saat Diterima</h3>
        <span style="margin-left:auto;display:inline-flex;align-items:center;gap:.35rem;padding:.28rem .85rem;border-radius:99px;font-size:.7rem;font-weight:700;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;">
            ⚠️ {{ $serahTerima->arrival_damage_severity === 'parah' ? 'Kerusakan Parah' : 'Kerusakan Ringan' }}
        </span>
    </div>
    <div class="ps-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Info waktu & keputusan penyewa --}}
        <div style="display:flex;gap:.875rem;align-items:center;background:#fef2f2;border:1.5px solid #fecaca;border-radius:1rem;padding:.875rem 1rem;">
            <span style="font-size:1.25rem;">⚠️</span>
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.2rem;">Dilaporkan Pada</div>
                <div style="font-size:.88rem;font-weight:700;color:#dc2626;">
                    {{ $serahTerima->arrival_damage_reported_at->format('d M Y, H:i') }}
                    @php
                        $dec = $serahTerima->arrival_damage_buyer_decision
                            ?? $serahTerima->arrival_damage_tenant_decision;
                        $decLabel = match($dec) {
                            'batalkan', 'batalkan' => 'Ajukan Pembatalan',
                            'lanjut', 'lanjutkan' => 'Ajukan Kompensasi',
                            default => '-',
                        };
                    @endphp
                    <span style="font-weight:400;color:#64748b;"> — Keputusan: {{ $decLabel }}</span>
                </div>
            </div>
        </div>

        {{-- Jenis kerusakan dari checklist --}}
        @php $checkedItems = $serahTerima->getCheckedDamageItems(); @endphp
        @if(!empty($checkedItems))
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.5rem;">Jenis Kerusakan</div>
            <div style="display:flex;gap:.4rem;flex-wrap:wrap;">
                @foreach($checkedItems as $item)
                    <span style="background:#fff;border:1.5px solid #fecaca;border-radius:.6rem;padding:.3rem .75rem;font-size:.76rem;font-weight:600;color:#991b1b;">⚠ {{ $item }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Foto depan & belakang saat kerusakan ditemukan --}}
        @if($serahTerima->arrival_condition_front_photo || $serahTerima->arrival_condition_back_photo)
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Koleksi Saat Diterima</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                @if($serahTerima->arrival_condition_front_photo)
                <div>
                    <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Depan</div>
                    <img src="{{ asset('storage/' . $serahTerima->arrival_condition_front_photo) }}"
                        style="width:100%;border-radius:.875rem;border:1.5px solid #fecaca;object-fit:cover;max-height:220px;cursor:zoom-in;"
                        alt="Foto Depan Kerusakan"
                        onclick="openConditionLightbox(this.src, this.alt)">
                </div>
                @endif
                @if($serahTerima->arrival_condition_back_photo)
                <div>
                    <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Belakang</div>
                    <img src="{{ asset('storage/' . $serahTerima->arrival_condition_back_photo) }}"
                        style="width:100%;border-radius:.875rem;border:1.5px solid #fecaca;object-fit:cover;max-height:220px;cursor:zoom-in;"
                        alt="Foto Belakang Kerusakan"
                        onclick="openConditionLightbox(this.src, this.alt)">
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Foto tambahan kerusakan (array) --}}
        @if(!empty($serahTerima->arrival_damage_photos))
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Bukti Kerusakan</div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:.6rem;">
                @foreach($serahTerima->arrival_damage_photos as $photo)
                <img src="{{ asset('storage/' . $photo) }}"
                    style="width:100%;border-radius:.875rem;border:1.5px solid #fecaca;object-fit:cover;height:180px;cursor:zoom-in;"
                    alt="Foto Kerusakan"
                    onclick="openConditionLightbox(this.src, this.alt)">
                @endforeach
            </div>
        </div>
        @endif

        {{-- Foto kondisi packing --}}
        @if(!empty($serahTerima->packing_condition_photos))
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Packing</div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:.6rem;">
                @foreach($serahTerima->packing_condition_photos as $photo)
                <img src="{{ asset('storage/' . $photo) }}"
                    style="width:100%;border-radius:.875rem;border:1.5px solid #fed7aa;object-fit:cover;height:180px;cursor:zoom-in;"
                    alt="Foto Packing"
                    onclick="openConditionLightbox(this.src, this.alt)">
                @endforeach
            </div>
        </div>
        @endif

        {{-- Foto bukti kurir (jika ada) --}}
        @if(!empty($serahTerima->courier_receipt_photos))
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Bukti Penerimaan Kurir</div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:.6rem;">
                @foreach($serahTerima->courier_receipt_photos as $photo)
                <img src="{{ asset('storage/' . $photo) }}"
                    style="width:100%;border-radius:.875rem;border:1.5px solid #bae6fd;object-fit:cover;height:180px;cursor:zoom-in;"
                    alt="Foto Bukti Kurir"
                    onclick="openConditionLightbox(this.src, this.alt)">
                @endforeach
            </div>
        </div>
        @endif

        {{-- Video kerusakan (wajib diisi penyewa) --}}
        @if($serahTerima->damage_video_path)
        <div>
            <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Video Bukti Kerusakan</div>
            <video controls style="width:100%;max-width:480px;border-radius:.875rem;border:1.5px solid #fecaca;height:220px;background:#000;">
                <source src="{{ asset('storage/' . $serahTerima->damage_video_path) }}" type="video/mp4">
                Browser Anda tidak mendukung pemutaran video.
            </video>
        </div>
        @endif

        {{-- Deskripsi kerusakan dari penyewa --}}
        @if($serahTerima->arrival_damage_description)
        <div style="background:#fff;border:1.5px solid #fecaca;border-radius:1rem;padding:1rem 1.1rem;">
            <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#dc2626;margin-bottom:.35rem;">Keterangan dari Penyewa</div>
            <div style="font-size:.84rem;color:#334155;line-height:1.65;">{{ $serahTerima->arrival_damage_description }}</div>
        </div>
        @endif

    </div>
</div>
@endif

{{-- ── LIGHTBOX (satu instance, pakai @once agar tidak duplikat) ── --}}
@once
<div id="condition-lightbox" onclick="closeConditionLightbox(event)"
    style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.85);backdrop-filter:blur(4px);align-items:center;justify-content:center;cursor:zoom-out;">
    <span onclick="closeConditionLightbox({target:this})"
        style="position:absolute;top:1.25rem;right:1.5rem;color:#fff;font-size:2rem;font-weight:300;cursor:pointer;line-height:1;opacity:.7;">&times;</span>
    <img id="condition-lightbox-img" src="" alt=""
        style="max-width:90vw;max-height:90vh;border-radius:1rem;box-shadow:0 24px 80px rgba(0,0,0,.6);object-fit:contain;"
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
    if (e.target === lb || e.target.tagName === 'SPAN') {
        lb.style.display = 'none';
        document.getElementById('condition-lightbox-img').src = '';
        document.body.style.overflow = '';
    }
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const lb = document.getElementById('condition-lightbox');
        if (lb && lb.style.display !== 'none') {
            lb.style.display = 'none';
            document.getElementById('condition-lightbox-img').src = '';
            document.body.style.overflow = '';
        }
    }
});
</script>
@endonce