<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#dc2626,#ef4444);"></div>
        <h3>Laporan Kerusakan</h3>
        <span style="margin-left:auto;font-size:.7rem;font-weight:600;color:#94a3b8;">
            Dilaporkan {{ $pembelian->arrival_damage_reported_at->format('d M Y, H:i') }}
        </span>
    </div>
    <div class="st-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Keputusan pembeli --}}
        <div style="display:flex;gap:.75rem;align-items:center;padding:.875rem 1rem;border-radius:1rem;
            background:{{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#f0fdf4' : '#fef2f2' }};
            border:1.5px solid {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#bbf7d0' : '#fecaca' }};">
            <span style="font-size:1.25rem;">
                {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '✅' : '❌' }}
            </span>
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.2rem;">Keputusan</div>
                <div style="font-size:.88rem;font-weight:700;color:{{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#059669' : '#dc2626' }};">
                    {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? 'Terima dengan Kompensasi' : 'Ajukan Pembatalan' }}
                </div>
            </div>
        </div>

        {{-- Jenis kerusakan --}}
        @if($pembelian->arrival_damage_items && count($pembelian->arrival_damage_items) > 0)
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Jenis Kerusakan</div>
                <div style="display:flex;flex-direction:column;gap:.4rem;">
                    @foreach($pembelian->arrival_damage_items as $item)
                        @if(!empty($item['checked']))
                            <div style="display:flex;gap:.6rem;align-items:flex-start;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.75rem;padding:.65rem .875rem;">
                                <span style="color:#dc2626;flex-shrink:0;margin-top:.1rem;">⚠️</span>
                                <div>
                                    <div style="font-size:.83rem;font-weight:600;color:#0b1d35;">{{ $item['label'] }}</div>
                                    @if(!empty($item['description']))
                                        <div style="font-size:.75rem;color:#64748b;margin-top:.2rem;">{{ $item['description'] }}</div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Deskripsi umum --}}
        @if($pembelian->arrival_damage_description)
            <div class="st-catatan">
                <div class="lbl">Deskripsi Umum Kerusakan</div>
                <div class="val">{{ $pembelian->arrival_damage_description }}</div>
            </div>
        @endif

        {{-- Foto depan & belakang --}}
        @if($pembelian->condition_front_photo || $pembelian->condition_back_photo)
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Koleksi</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                    @if($pembelian->condition_front_photo)
                        <div>
                            <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Depan</div>
                            <img src="{{ asset('storage/' . $pembelian->condition_front_photo) }}"
                                style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;"
                                alt="Foto Depan Koleksi"
                                class="st-zoomable"
                                onclick="openLightbox(this.src, this.alt)">
                        </div>
                    @endif
                    @if($pembelian->condition_back_photo)
                        <div>
                            <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Belakang</div>
                            <img src="{{ asset('storage/' . $pembelian->condition_back_photo) }}"
                                style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;"
                                alt="Foto Belakang Koleksi"
                                class="st-zoomable"
                                onclick="openLightbox(this.src, this.alt)">
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Video kerusakan --}}
        @if($pembelian->damage_video_path)
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Video Bukti Kerusakan</div>
                <video controls
                    style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);max-height:320px;background:#000;">
                    <source src="{{ asset('storage/' . $pembelian->damage_video_path) }}" type="video/mp4">
                    Browser Anda tidak mendukung pemutaran video.
                </video>
            </div>
        @endif

        {{-- Foto packing --}}
        @if($pembelian->packing_condition_photos && count($pembelian->packing_condition_photos) > 0)
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Packing</div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:.6rem;">
                    @foreach($pembelian->packing_condition_photos as $photo)
                        <img src="{{ asset('storage/' . $photo) }}"
                            style="width:100%;border-radius:.75rem;border:1.5px solid var(--border);object-fit:cover;height:120px;"
                            alt="Foto Packing"
                            class="st-zoomable"
                            onclick="openLightbox(this.src, this.alt)">
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Foto bukti kurir (jika ada) --}}
        @if($pembelian->courier_receipt_photos && count($pembelian->courier_receipt_photos) > 0)
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Bukti Penerimaan dari Kurir</div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:.6rem;">
                    @foreach($pembelian->courier_receipt_photos as $photo)
                        <img src="{{ asset('storage/' . $photo) }}"
                            style="width:100%;border-radius:.75rem;border:1.5px solid var(--border);object-fit:cover;height:120px;"
                            alt="Bukti Kurir"
                            class="st-zoomable"
                            onclick="openLightbox(this.src, this.alt)">
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>