{{-- Riwayat & bukti penanganan kerusakan (detail pengajuan — bagian bawah) --}}
@if($pembelian->hasDamageReport() || !empty($pembelian->damage_handling_timeline))
<div class="ps-card" style="margin-top:1.25rem;">
    <div class="ps-card-header">
        <div class="ps-card-header-accent" style="background:linear-gradient(180deg,#dc2626,#f97316);"></div>
        <h3>Riwayat Pengecekan Kondisi</h3>
        @if($pembelian->arrival_damage_reported_at)
        <span style="margin-left:auto;font-size:.7rem;font-weight:600;color:#94a3b8;">
            Dilaporkan {{ $pembelian->arrival_damage_reported_at->format('d M Y, H:i') }}
        </span>
        @endif
    </div>
    <div class="ps-card-body" style="display:grid;gap:1.25rem;">

        @if($pembelian->hasDamageReport())
            @if($pembelian->arrival_damage_buyer_decision)
            <div style="display:flex;gap:.75rem;align-items:center;padding:.875rem 1rem;border-radius:1rem;
                background:{{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#f0fdf4' : '#fef2f2' }};
                border:1.5px solid {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#bbf7d0' : '#fecaca' }};">
                <span style="font-size:1.25rem;">
                    {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '✅' : '❌' }}
                </span>
                <div>
                    <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.2rem;">Keputusan Pembeli</div>
                    <div style="font-size:.88rem;font-weight:700;color:{{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? '#059669' : '#dc2626' }};">
                        {{ $pembelian->arrival_damage_buyer_decision === 'lanjut' ? 'Ajukan Kompensasi' : 'Ajukan Pembatalan' }}
                    </div>
                </div>
            </div>
            @endif

            <div class="ps-data-row">                @if($pembelian->arrival_damage_manager_decision)
                <div class="ps-field">
                    <div class="lbl">Keputusan Pengelola</div>
                    <div class="val">
                        @if($pembelian->arrival_damage_manager_decision === 'setujui_kompensasi')
                            Menyetujui Kompensasi
                        @elseif($pembelian->arrival_damage_manager_decision === 'setujui_pembatalan')
                            Menyetujui Pembatalan
                        @else
                            {{ ucfirst(str_replace('_', ' ', $pembelian->arrival_damage_manager_decision)) }}
                        @endif
                    </div>
                </div>
                @endif
            </div>

            @php $checkedItems = $pembelian->getCheckedDamageItems(); @endphp
            @if(count($checkedItems))
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Jenis Kerusakan</div>
                <div style="display:flex;flex-direction:column;gap:.4rem;">
                    @foreach($checkedItems as $item)
                    <div style="display:flex;gap:.6rem;align-items:flex-start;background:#fef2f2;border:1.5px solid #fecaca;border-radius:.75rem;padding:.65rem .875rem;">
                        <span style="color:#dc2626;flex-shrink:0;margin-top:.1rem;">⚠️</span>
                        <div>
                            <div style="font-size:.83rem;font-weight:600;color:#0b1d35;">{{ $item['label'] }}</div>
                            @if(!empty($item['description']))
                            <div style="font-size:.75rem;color:#64748b;margin-top:.2rem;">{{ $item['description'] }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($pembelian->arrival_damage_description)
            <div class="ps-catatan">
                <div class="lbl">Deskripsi Umum Kerusakan</div>
                <div class="val">{{ $pembelian->arrival_damage_description }}</div>
            </div>
            @endif

            @if($pembelian->arrival_damage_manager_notes)
            <div class="ps-catatan" style="background:#f8fafc;">
                <div class="lbl">Catatan Pengelola</div>
                <div class="val">{{ $pembelian->arrival_damage_manager_notes }}</div>
            </div>
            @endif

            @if($pembelian->condition_front_photo || $pembelian->condition_back_photo)
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Koleksi</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                    @if($pembelian->condition_front_photo)
                    <div>
                        <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Depan</div>
                        <a href="{{ asset('storage/' . $pembelian->condition_front_photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $pembelian->condition_front_photo) }}"
                                style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;"
                                alt="Foto Depan Koleksi">
                        </a>
                    </div>
                    @endif
                    @if($pembelian->condition_back_photo)
                    <div>
                        <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Belakang</div>
                        <a href="{{ asset('storage/' . $pembelian->condition_back_photo) }}" target="_blank">
                            <img src="{{ asset('storage/' . $pembelian->condition_back_photo) }}"
                                style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;"
                                alt="Foto Belakang Koleksi">
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @if($pembelian->damage_video_path)
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Video Bukti Kerusakan</div>
                <video controls style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);max-height:320px;background:#000;">
                    <source src="{{ asset('storage/' . $pembelian->damage_video_path) }}" type="video/mp4">
                    Browser Anda tidak mendukung pemutaran video.
                </video>
            </div>
            @endif

            @if($pembelian->packing_condition_photos && count($pembelian->packing_condition_photos) > 0)
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Packing</div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:.6rem;">
                    @foreach($pembelian->packing_condition_photos as $photo)
                    <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                        <img src="{{ asset('storage/' . $photo) }}"
                            style="width:100%;border-radius:.75rem;border:1.5px solid var(--border);object-fit:cover;height:120px;"
                            alt="Foto Packing">
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if($pembelian->courier_receipt_photos && count($pembelian->courier_receipt_photos) > 0)
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Bukti Penerimaan dari Kurir</div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:.6rem;">
                    @foreach($pembelian->courier_receipt_photos as $photo)
                    <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                        <img src="{{ asset('storage/' . $photo) }}"
                            style="width:100%;border-radius:.75rem;border:1.5px solid var(--border);object-fit:cover;height:120px;"
                            alt="Bukti Kurir">
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if($pembelian->arrival_damage_compensation_amount || $pembelian->refund_processed_at || $pembelian->refund_confirmed_at)
            <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:1rem;padding:1rem;">
                <div style="font-size:.72rem;font-weight:700;color:#166534;text-transform:uppercase;margin-bottom:.5rem;">Informasi Refund / Kompensasi</div>
                @if($pembelian->arrival_damage_compensation_amount)
                <div style="font-size:.82rem;margin-bottom:.35rem;"><strong>Kompensasi disetujui:</strong> Rp {{ number_format($pembelian->arrival_damage_compensation_amount, 0, ',', '.') }}</div>
                @endif
                @if($pembelian->refund_amount && $pembelian->refund_processed_at)
                <div style="font-size:.82rem;margin-bottom:.35rem;"><strong>Transfer:</strong> Rp {{ number_format($pembelian->refund_amount, 0, ',', '.') }} pada {{ $pembelian->refund_processed_at->format('d M Y H:i') }}</div>
                @endif
                @if($pembelian->refund_confirmed_at)
                <div style="font-size:.82rem;"><strong>Dikonfirmasi pembeli:</strong> {{ $pembelian->refund_confirmed_at->format('d M Y H:i') }}</div>
                @endif
                @if($pembelian->refund_transfer_proof_path)
                <div style="margin-top:.5rem;">
                    <a href="{{ asset('storage/' . $pembelian->refund_transfer_proof_path) }}" target="_blank" style="font-size:.8rem;color:#059669;font-weight:600;">Lihat bukti transfer →</a>
                </div>
                @endif
            </div>
            @endif
        @endif

        @if(!empty($pembelian->damage_handling_timeline))
        <div>
            <div style="font-size:.72rem;font-weight:700;color:var(--navy);text-transform:uppercase;margin-bottom:.65rem;">Timeline</div>
            @foreach(array_reverse($pembelian->damage_handling_timeline) as $entry)
            <div style="display:flex;gap:.75rem;padding:.65rem 0;border-bottom:1px solid #f0f4f8;font-size:.8rem;">
                <div style="color:var(--slate);font-size:.72rem;white-space:nowrap;">
                    {{ isset($entry['timestamp']) ? \Carbon\Carbon::parse($entry['timestamp'])->format('d M Y H:i') : '-' }}
                </div>
                <div>
                    <div style="font-weight:600;color:var(--navy);">{{ $entry['by'] ?? 'Sistem' }}</div>
                    <div style="color:#475569;line-height:1.5;">{{ $entry['message'] ?? '' }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endif
