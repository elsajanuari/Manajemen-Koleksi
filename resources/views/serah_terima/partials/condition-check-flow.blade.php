            @if(!$isPengelola && $status === 'menunggu_data_rekening')
                @if($isDamageCancellation)
                    <div class="st-section st-section-amber">
                        <div class="st-eyebrow">⚡ Aksi Diperlukan</div>
                        <h2>Kembalikan Koleksi ke Museum</h2>
                        <p>Pembatalan disetujui. Kembalikan koleksi ke museum, isi informasi pengiriman balik, data rekening refund, dan nominal ongkir pengembalian beserta buktinya. Refund akan diproses setelah pengelola mengkonfirmasi koleksi tiba di museum.</p>
                    </div>
                    <div class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-header-accent" style="background:linear-gradient(180deg,#d97706,#f59e0b);"></div>
                            <h3>Proses Pengembalian Koleksi &amp; Data Refund</h3>
                        </div>
                        <div class="st-card-body" style="display:grid;gap:1.25rem;">
                            @if($errors->any())
                                <div class="st-errors" style="margin-bottom:1rem;">
                                    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                                </div>
                            @endif

                            <form action="{{ route('penyewaan.requests.handover.submit-bank-account', $penyewaan) }}" method="POST" enctype="multipart/form-data" style="display:grid;gap:1rem;">
                                @csrf
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;">Informasi Pengiriman Balik</div>
                                <div class="st-form-grid">
                                    <div class="st-form-group">
                                        <label class="st-form-label">Metode Pengiriman <span class="req">*</span></label>
                                        <input type="text" name="return_shipment_method" class="st-form-input" required
                                            value="{{ old('return_shipment_method') }}" placeholder="JNE, TIKI, kurir museum, dll">
                                    </div>
                                    <div class="st-form-group">
                                        <label class="st-form-label">Nama Pengirim / Petugas <span class="req">*</span></label>
                                        <input type="text" name="return_shipment_officer" class="st-form-input" required
                                            value="{{ old('return_shipment_officer') }}">
                                    </div>
                                    <div class="st-form-group">
                                        <label class="st-form-label">Nomor Resi <span class="opt">(opsional)</span></label>
                                        <input type="text" name="return_shipment_tracking" class="st-form-input"
                                            value="{{ old('return_shipment_tracking') }}">
                                    </div>
                                    <div class="st-form-group">
                                        <label class="st-form-label">Rencana Tanggal Kirim <span class="req">*</span></label>
                                        <input type="datetime-local" name="return_shipment_scheduled_at" class="st-form-input" required
                                            value="{{ old('return_shipment_scheduled_at') }}">
                                    </div>
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Catatan Pengiriman <span class="opt">(opsional)</span></label>
                                    <textarea name="return_shipment_notes" class="st-form-textarea" rows="2" placeholder="Catatan khusus terkait pengiriman balik...">{{ old('return_shipment_notes') }}</textarea>
                                </div>

                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-top:.5rem;">Ongkir Pengembalian</div>
                                <div class="st-form-grid">
                                    <div class="st-form-group">
                                        <label class="st-form-label">Nominal Ongkir (Rp) <span class="req">*</span></label>
                                        <input type="number" name="return_shipping_cost" class="st-form-input" required min="0"
                                            value="{{ old('return_shipping_cost', 0) }}">
                                    </div>
                                    <div class="st-form-group">
                                        <label class="st-form-label">Bukti Ongkir <span class="req">*</span></label>
                                        <input type="file" name="return_shipping_proof" class="st-form-input" style="padding:.5rem .75rem;" accept="image/*,.pdf" required>
                                    </div>
                                </div>

                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-top:.5rem;">Data Rekening Refund</div>
                                <div class="st-form-grid">
                                    <div class="st-form-group">
                                        <label class="st-form-label">Nama Bank <span class="req">*</span></label>
                                        <input type="text" name="refund_bank_name" class="st-form-input" required
                                            value="{{ old('refund_bank_name') }}" placeholder="Contoh: BCA, Mandiri, BNI">
                                    </div>
                                    <div class="st-form-group">
                                        <label class="st-form-label">Nomor Rekening <span class="req">*</span></label>
                                        <input type="text" name="refund_account_number" class="st-form-input" required
                                            value="{{ old('refund_account_number') }}">
                                    </div>
                                    <div class="st-form-group" style="grid-column:1/-1;">
                                        <label class="st-form-label">Nama Pemilik Rekening <span class="req">*</span></label>
                                        <input type="text" name="refund_account_holder" class="st-form-input" required
                                            value="{{ old('refund_account_holder') }}">
                                    </div>
                                </div>

                                <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:.875rem;padding:.75rem 1rem;">
                                    <p style="font-size:.72rem;color:#92400e;margin:0;line-height:1.6;">
                                        ℹ️ Estimasi refund dasar (total bayar − ongkir awal):
                                        <strong>Rp {{ number_format($st->calculateBaseDamageRefundAmount(), 0, ',', '.') }}</strong>.
                                        Ongkir pengembalian akan ditambahkan setelah koleksi diterima museum.
                                    </p>
                                </div>

                                <div style="display:flex;justify-content:flex-end;">
                                    <button type="submit" class="st-btn st-btn-amber"
                                        onclick="return confirm('Kirim data pengembalian koleksi, ongkir, dan rekening refund?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Kirim Data Pengembalian &amp; Rekening
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#0284c7,#38bdf8);"></div>
                        <h3>{{ $isDamageCompensation ? 'Isi Data Rekening untuk Kompensasi' : 'Isi Data Rekening untuk Refund' }}</h3>
                    </div>
                    <div class="st-card-body">
                        <p style="font-size:.84rem;color:#475569;margin:0 0 1.25rem;line-height:1.7;">
                            @if($isDamageCompensation)
                                Pengelola telah menyetujui kompensasi kerusakan sebesar
                                <strong>Rp {{ number_format($st->arrival_damage_compensation_amount ?? 0, 0, ',', '.') }}</strong>.
                                Isi data rekening di bawah agar transfer kompensasi dapat diproses. Setelah kompensasi diterima,
                                proses penyewaan akan dilanjutkan ke dokumen serah terima tanpa pengembalian koleksi ke museum.
                            @else
                                Pengelola telah meninjau laporan kerusakan Anda. Silakan isi data rekening di bawah ini
                                untuk proses refund manual. Pengelola akan melakukan transfer dan mengunggah bukti transfer
                                setelah data ini terkirim.
                            @endif
                        </p>

                        @if($isDamageCompensation && $st->arrival_damage_manager_notes)
                        <div class="st-catatan" style="margin-bottom:1rem;">
                            <div class="lbl">Catatan Pengelola</div>
                            <div class="val">{{ $st->arrival_damage_manager_notes }}</div>
                        </div>
                        @endif

                        @if($errors->any())
                            <div class="st-errors" style="margin-bottom:1rem;">
                                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <form action="{{ route('penyewaan.requests.handover.submit-bank-account', $penyewaan) }}" method="POST" style="display:grid;gap:.875rem;">                            @csrf
                            <div class="st-form-group">
                                <label class="st-form-label">Nama Bank <span class="req">*</span></label>
                                <input type="text" name="refund_bank_name" class="st-form-input" required
                                    value="{{ old('refund_bank_name') }}" placeholder="Contoh: BCA, Mandiri, BNI">
                            </div>
                            <div class="st-form-group">
                                <label class="st-form-label">Nomor Rekening <span class="req">*</span></label>
                                <input type="text" name="refund_account_number" class="st-form-input" required
                                    value="{{ old('refund_account_number') }}">
                            </div>
                            <div class="st-form-group">
                                <label class="st-form-label">Nama Pemilik Rekening <span class="req">*</span></label>
                                <input type="text" name="refund_account_holder" class="st-form-input" required
                                    value="{{ old('refund_account_holder') }}">
                            </div>
                            <div>
                                <button type="submit" class="st-btn st-btn-sky">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Kirim Data Rekening
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            @endif

            {{-- PEMBELI: Menunggu konfirmasi penerimaan koleksi di museum --}}
            @if(!$isPengelola && $status === 'menunggu_penerimaan_koleksi')
                <div class="st-section st-section-sky">
                    <div class="st-eyebrow">📦 Pengembalian Koleksi</div>
                    <h2>Menunggu Konfirmasi Museum</h2>
                    <p>Data pengembalian koleksi dan rekening refund sudah terkirim. Pantau pengiriman balik di bawah. Proses refund akan dilanjutkan setelah pengelola mengkonfirmasi koleksi tiba di museum.</p>
                </div>
                @include('serah_terima.partials.damage-return-tracking', ['isPengelola' => false])
            @endif

            {{-- ════════════════════════════════════════════════════════════
                PEMBELI: Menunggu Proses Refund (transfer manual oleh pengelola)
            ════════════════════════════════════════════════════════════ --}}
            @if(!$isPengelola && $status === 'menunggu_refund_kerusakan')
                <div class="st-section st-section-sky">
                    <div class="st-eyebrow">⏳ Menunggu Transfer</div>
                    <h2>{{ $isDamageCompensation ? 'Kompensasi Sedang Diproses' : 'Refund Sedang Diproses' }}</h2>
                    <p>
                        @if($isDamageCompensation)
                            Data rekening Anda telah kami terima. Pengelola akan melakukan transfer kompensasi dan mengunggah bukti transfer ke sistem ini.
                        @elseif($isDamageCancellation && $st->collection_arrived_at)
                            Koleksi sudah dikonfirmasi tiba di museum. Pengelola akan melakukan transfer refund
                            (termasuk ongkir pengembalian) dan mengunggah bukti transfer ke sistem ini.
                        @else
                            Data rekening Anda telah kami terima. Pengelola akan melakukan transfer manual dan mengunggah bukti transfer ke sistem ini.
                        @endif
                    </p>
                    @if($st->refund_bank_name)
                        <div class="st-meta-grid">
                            <div class="st-meta-cell">
                                <div class="lbl">Bank</div>
                                <div class="val">{{ $st->refund_bank_name }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">No. Rekening</div>
                                <div class="val">{{ $st->refund_account_number }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">Atas Nama</div>
                                <div class="val">{{ $st->refund_account_holder }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Penyewa: Konfirmasi Penerimaan Refund --}}
            @if(!$isPengelola && $status === 'menunggu_konfirmasi_refund' && $st->refund_transfer_proof_path)                <div class="st-section st-section-emerald">
                    <div class="st-eyebrow">{{ $isDamageCompensation ? '💰 Kompensasi Telah Dikirim' : '💸 Refund Telah Dikirim' }}</div>
                    <h2>{{ $isDamageCompensation ? 'Konfirmasi Penerimaan Kompensasi' : 'Konfirmasi Penerimaan Refund' }}</h2>
                    <p>
                        @if($isDamageCompensation)
                            Pengelola telah mentransfer kompensasi. Cek detail di bawah dan konfirmasi setelah dana masuk ke rekening Anda. Setelah konfirmasi, Anda dapat melanjutkan proses dokumen serah terima.
                        @else
                            Pengelola telah mentransfer refund. Cek detail di bawah dan konfirmasi setelah dana masuk ke rekening Anda.
                        @endif
                    </p>
                </div>

                {{-- Detail bukti transfer --}}
                @if($st->refund_transfer_proof_path)
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#059669,#10b981);"></div>
                        <h3>{{ $isDamageCompensation ? 'Bukti Transfer Kompensasi' : 'Bukti Transfer Refund' }}</h3>
                    </div>
                    <div class="st-card-body">
                        <div class="st-meta-grid" style="margin-bottom:1.25rem;">
                            @if($st->refund_bank_name)
                            <div class="st-meta-cell">
                                <div class="lbl">Bank</div>
                                <div class="val">{{ $st->refund_bank_name }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">No. Rekening</div>
                                <div class="val">{{ $st->refund_account_number }}</div>
                            </div>
                            <div class="st-meta-cell">
                                <div class="lbl">Atas Nama</div>
                                <div class="val">{{ $st->refund_account_holder }}</div>
                            </div>
                            @endif
                            @if($st->refund_amount)
                            <div class="st-meta-cell" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#bbf7d0;">
                                <div class="lbl">{{ $isDamageCompensation ? 'Nominal Kompensasi' : 'Nominal Refund' }}</div>
                                <div class="val" style="color:#059669;">Rp {{ number_format($st->refund_amount, 0, ',', '.') }}</div>
                            </div>
                            @endif
                            @if($st->refund_date)
                            <div class="st-meta-cell">
                                <div class="lbl">Tanggal Transfer</div>
                                <div class="val">{{ \Carbon\Carbon::parse($st->refund_date)->format('d M Y') }}</div>
                            </div>
                            @endif
                            @if($st->refund_processed_at)
                            <div class="st-meta-cell">
                                <div class="lbl">Diproses Pada</div>
                                <div class="val">{{ $st->refund_processed_at->format('d M Y H:i') }}</div>
                            </div>
                            @endif
                        </div>

                        {{-- Preview bukti transfer --}}
                        @php
                            $proofExt = pathinfo($st->refund_transfer_proof_path, PATHINFO_EXTENSION);
                        @endphp
                        @if(in_array(strtolower($proofExt), ['jpg','jpeg','png']))
                            <div style="margin-bottom:1rem;">
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.5rem;">Bukti Transfer</div>
                                <img src="{{ asset('storage/' . $st->refund_transfer_proof_path) }}"
                                    style="max-width:100%;border-radius:.875rem;border:1.5px solid var(--border);max-height:400px;object-fit:contain;"
                                    alt="Bukti Transfer"
                                    class="st-zoomable"
                                    onclick="openLightbox(this.src, this.alt)">
                            </div>
                        @elseif(strtolower($proofExt) === 'pdf')
                            <div style="margin-bottom:1rem;">
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.5rem;">Bukti Transfer (PDF)</div>
                                <iframe src="{{ asset('storage/' . $st->refund_transfer_proof_path) }}"
                                    style="width:100%;height:380px;border:1.5px solid var(--border);border-radius:.875rem;"
                                    title="Bukti Transfer"></iframe>
                            </div>
                        @endif

                        @if($st->refund_notes)
                        <div class="st-catatan">
                            <div class="lbl">Catatan Pengelola</div>
                            <div class="val">{{ $st->refund_notes }}</div>
                        </div>
                        @endif

                        <div class="st-action-row" style="margin-top:1.25rem;">
                            <form action="{{ route('penyewaan.requests.handover.confirm-refund', $penyewaan) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('{{ $isDamageCompensation ? 'Konfirmasi bahwa Anda sudah menerima kompensasi? Setelah ini Anda dapat melanjutkan upload dokumen serah terima.' : 'Konfirmasi bahwa Anda sudah menerima refund? Tindakan ini tidak dapat dibatalkan.' }}')"
                                    class="st-btn st-btn-emerald">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $isDamageCompensation ? 'Konfirmasi Kompensasi Sudah Diterima' : 'Konfirmasi Refund Sudah Diterima' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @endif
            @if($isPengelola && $status === 'menunggu_review_kerusakan')

                {{-- Detail Laporan Penyewa --}}
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#dc2626,#ef4444);"></div>
                        <h3>Detail Laporan Kerusakan dari Penyewa</h3>
                        <span style="margin-left:auto;font-size:.7rem;font-weight:600;color:#94a3b8;">
                            Dilaporkan {{ $st->arrival_damage_reported_at?->format('d M Y, H:i') }}
                        </span>
                    </div>
                    <div class="st-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

                        {{-- Keputusan penyewa --}}
                        <div style="display:flex;gap:.75rem;align-items:center;padding:.875rem 1rem;border-radius:1rem;
                            background:{{ $st->arrival_damage_buyer_decision === 'lanjut' ? '#f0fdf4' : '#fef2f2' }};
                            border:1.5px solid {{ $st->arrival_damage_buyer_decision === 'lanjut' ? '#bbf7d0' : '#fecaca' }};">
                            <span style="font-size:1.25rem;">
                                {{ $st->arrival_damage_buyer_decision === 'lanjut' ? '✅' : '❌' }}
                            </span>
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.2rem;">Keputusan Penyewa</div>
                                <div style="font-size:.88rem;font-weight:700;color:{{ $st->arrival_damage_buyer_decision === 'lanjut' ? '#059669' : '#dc2626' }};">
                                    {{ $st->arrival_damage_buyer_decision === 'lanjut' ? 'Ajukan Kompensasi' : 'Ajukan Pembatalan' }}
                                </div>
                            </div>
                        </div>

                        {{-- Jenis kerusakan --}}
                        @if($st->arrival_damage_items && count($st->arrival_damage_items) > 0)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Jenis Kerusakan yang Dilaporkan</div>
                                <div style="display:flex;flex-direction:column;gap:.4rem;">
                                    @foreach($st->arrival_damage_items as $item)
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
                        @if($st->arrival_damage_description)
                            <div class="st-catatan">
                                <div class="lbl">Deskripsi Umum Kerusakan</div>
                                <div class="val">{{ $st->arrival_damage_description }}</div>
                            </div>
                        @endif

                        {{-- Foto depan & belakang --}}
                        @if($st->condition_front_photo || $st->condition_back_photo)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Koleksi</div>
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                                    @if($st->condition_front_photo)
                                        <div>
                                            <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Depan</div>
                                            <img src="{{ asset('storage/' . $st->condition_front_photo) }}"
                                                style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);object-fit:cover;max-height:220px;"
                                                alt="Foto Depan Koleksi"
                                                class="st-zoomable"
                                                onclick="openLightbox(this.src, this.alt)">
                                        </div>
                                    @endif
                                    @if($st->condition_back_photo)
                                        <div>
                                            <div style="font-size:.72rem;font-weight:600;color:#64748b;margin-bottom:.35rem;">Tampak Belakang</div>
                                            <img src="{{ asset('storage/' . $st->condition_back_photo) }}"
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
                        @if($st->damage_video_path)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Video Bukti Kerusakan</div>
                                <video controls
                                    style="width:100%;border-radius:.875rem;border:1.5px solid var(--border);max-height:320px;background:#000;">
                                    <source src="{{ asset('storage/' . $st->damage_video_path) }}" type="video/mp4">
                                    Browser Anda tidak mendukung pemutaran video.
                                </video>
                            </div>
                        @endif

                        {{-- Foto packing --}}
                        @if($st->packing_condition_photos && count($st->packing_condition_photos) > 0)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Foto Kondisi Packing</div>
                                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:.6rem;">
                                    @foreach($st->packing_condition_photos as $photo)
                                        <img src="{{ asset('storage/' . $photo) }}"
                                            style="width:100%;border-radius:.75rem;border:1.5px solid var(--border);object-fit:cover;height:120px;"
                                            alt="Foto Packing"
                                            class="st-zoomable"
                                            onclick="openLightbox(this.src, this.alt)">
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Foto bukti kurir --}}
                        @if($st->courier_receipt_photos && count($st->courier_receipt_photos) > 0)
                            <div>
                                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Bukti Penerimaan dari Kurir</div>
                                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:.6rem;">
                                    @foreach($st->courier_receipt_photos as $photo)
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

                {{-- Form Keputusan Pengelola --}}
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#d97706,#f59e0b);"></div>
                        <h3>Keputusan Pengelola</h3>
                    </div>
                    <div class="st-card-body">
                        <p style="font-size:.84rem;color:#475569;margin:0 0 1.25rem;line-height:1.7;">
                            Tinjau bukti kerusakan di atas lalu tentukan keputusan.
                            @if($st->arrival_damage_buyer_decision === 'batalkan')
                                Penyewa mengajukan <strong>pembatalan transaksi</strong>. Setujui jika bukti kerusakan valid, atau tolak jika bukti tidak mencukupi.
                            @else
                                Penyewa meminta <strong>kompensasi parsial</strong> dan tetap menerima koleksi. Setujui dengan menentukan nominal, atau tolak jika bukti tidak valid.
                            @endif
                        </p>

                        @if($errors->any())
                            <div class="st-errors" style="margin-bottom:1rem;">
                                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        @if($st->arrival_damage_buyer_decision === 'batalkan')
                            {{-- ── PEMBELI AJUKAN PEMBATALAN: Setujui atau Tolak ── --}}
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">

                                {{-- Panel: Setujui Pembatalan --}}
                                <div id="panel-setujui"
                                    style="border:2px solid var(--border);border-radius:1rem;padding:1.25rem;cursor:pointer;transition:all .15s;background:#fff;"
                                    onclick="selectDecision2('setujui')">
                                    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.6rem;">
                                        <div id="dot-setujui" style="width:16px;height:16px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .15s;"></div>
                                        <span style="font-size:.88rem;font-weight:700;color:#059669;">✅ Setujui Pembatalan</span>
                                    </div>
                                    <div style="font-size:.75rem;color:#475569;line-height:1.6;padding-left:1.6rem;">
                                        Bukti kerusakan valid. Penyewa wajib mengembalikan koleksi ke museum.
                                        Refund dasar (total bayar − ongkir awal), ditambah ongkir pengembalian setelah koleksi diterima.
                                        <div style="margin-top:.5rem;padding:.5rem .75rem;background:#f0fdf4;border-radius:.6rem;font-size:.73rem;font-weight:600;color:#059669;">
                                            Estimasi refund dasar: Rp {{ number_format($st->calculateBaseDamageRefundAmount(), 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Panel: Tolak Pembatalan --}}
                                <div id="panel-tolak"
                                    style="border:2px solid var(--border);border-radius:1rem;padding:1.25rem;cursor:pointer;transition:all .15s;background:#fff;"
                                    onclick="selectDecision2('tolak')">
                                    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.6rem;">
                                        <div id="dot-tolak" style="width:16px;height:16px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .15s;"></div>
                                        <span style="font-size:.88rem;font-weight:700;color:#dc2626;">❌ Tolak Pembatalan</span>
                                    </div>
                                    <div style="font-size:.75rem;color:#475569;line-height:1.6;padding-left:1.6rem;">
                                        Bukti tidak mencukupi. Transaksi tetap sah, penyewa lanjut ke proses serah terima.
                                        <div style="margin-top:.5rem;padding:.5rem .75rem;background:#fef2f2;border-radius:.6rem;font-size:.73rem;font-weight:600;color:#dc2626;">
                                            Penyewa tetap wajib upload dokumen serah terima
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('pengelola.penyewaan.handover.decide-damage', $penyewaan) }}"
                                method="POST" style="display:grid;gap:.875rem;" id="form-keputusan-batal">
                                @csrf
                                <input type="hidden" name="final_severity" value="parah">
                                <input type="hidden" name="manager_decision" id="input-manager-decision" value="">

                                <div class="st-form-group">
                                    <label class="st-form-label">
                                        Alasan / Catatan untuk Penyewa <span class="req">*</span>
                                    </label>
                                    <textarea name="notes" rows="4" class="st-form-textarea"
                                            id="notes-field"
                                            placeholder="Wajib diisi — jelaskan alasan keputusan Anda kepada penyewa..."
                                            required>{{ old('notes') }}</textarea>
                                    <p id="notes-hint" style="font-size:.72rem;color:#64748b;margin-top:.3rem;display:none;"></p>
                                </div>

                                <div>
                                    <button type="submit" id="btn-submit-keputusan"
                                            class="st-btn st-btn-amber"
                                            style="opacity:.4;pointer-events:none;"
                                            onclick="return confirmKeputusan()">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Simpan Keputusan
                                    </button>
                                </div>
                            </form>

                            <script>
                            function selectDecision2(val) {
                                const panels = { setujui: 'panel-setujui', tolak: 'panel-tolak' };
                                const dots   = { setujui: 'dot-setujui',   tolak: 'dot-tolak'   };
                                const colors = { setujui: '#059669',        tolak: '#dc2626'     };
                                const bgs    = { setujui: '#f0fdf4',        tolak: '#fef2f2'     };
                                const hints  = {
                                    setujui: 'Penyewa akan dinotifikasi bahwa pembatalan disetujui dan refund akan segera diproses.',
                                    tolak:   'Penyewa akan dinotifikasi bahwa klaim kerusakan ditolak dan diminta melanjutkan proses serah terima.'
                                };

                                Object.keys(panels).forEach(k => {
                                    const panel = document.getElementById(panels[k]);
                                    const dot   = document.getElementById(dots[k]);
                                    if (k === val) {
                                        panel.style.border     = '2px solid ' + colors[k];
                                        panel.style.background = bgs[k];
                                        dot.style.border       = '5px solid ' + colors[k];
                                        dot.style.background   = k === 'setujui' ? '#d1fae5' : '#fee2e2';
                                    } else {
                                        panel.style.border     = '2px solid var(--border)';
                                        panel.style.background = '#fff';
                                        dot.style.border       = '2px solid #d1d5db';
                                        dot.style.background   = 'transparent';
                                    }
                                });

                                document.getElementById('input-manager-decision').value = val;

                                const btn  = document.getElementById('btn-submit-keputusan');
                                btn.style.opacity        = '1';
                                btn.style.pointerEvents  = 'auto';
                                btn.className = val === 'setujui'
                                    ? 'st-btn st-btn-emerald'
                                    : 'st-btn st-btn-danger';

                                const hint = document.getElementById('notes-hint');
                                hint.textContent    = hints[val];
                                hint.style.display  = '';

                                // Update placeholder textarea
                                document.getElementById('notes-field').placeholder = val === 'setujui'
                                    ? 'Contoh: Bukti kerusakan valid. Kami akan memproses refund dalam 3-5 hari kerja.'
                                    : 'Contoh: Setelah ditinjau, bukti yang dikirimkan tidak mencukupi untuk membuktikan kerusakan. Transaksi tetap dilanjutkan.';
                            }

                            function confirmKeputusan() {
                                const val = document.getElementById('input-manager-decision').value;
                                if (!val) { alert('Pilih keputusan terlebih dahulu.'); return false; }
                                const notes = document.getElementById('notes-field').value.trim();
                                if (!notes) { alert('Alasan / catatan untuk penyewa wajib diisi.'); return false; }
                                const msg = val === 'setujui'
                                    ? 'Setujui pembatalan dan proses refund Rp {{ number_format($st->calculateFullDamageRefundAmount(), 0, ',', '.') }}?'
                                    : 'Tolak pembatalan? Penyewa akan diminta melanjutkan proses serah terima.';
                                return confirm(msg);
                            }

                            // Auto-restore jika ada old input
                            @if(old('manager_decision'))
                                document.addEventListener('DOMContentLoaded', () => selectDecision2('{{ old('manager_decision') }}'));
                            @endif
                            </script>

                        @else
                            {{-- ── PEMBELI MINTA KOMPENSASI: Setujui atau Tolak ── --}}
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">

                                <div id="panel-komp-setujui"
                                    style="border:2px solid var(--border);border-radius:1rem;padding:1.25rem;cursor:pointer;transition:all .15s;background:#fff;"
                                    onclick="selectKompDecision('setujui')">
                                    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.6rem;">
                                        <div id="dot-komp-setujui" style="width:16px;height:16px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .15s;"></div>
                                        <span style="font-size:.88rem;font-weight:700;color:#059669;">✅ Setujui Kompensasi</span>
                                    </div>
                                    <div style="font-size:.75rem;color:#475569;line-height:1.6;padding-left:1.6rem;">
                                        Bukti kerusakan valid. Tentukan nominal kompensasi, lalu penyewa diminta mengisi rekening untuk transfer.
                                        <div style="margin-top:.5rem;padding:.5rem .75rem;background:#f0fdf4;border-radius:.6rem;font-size:.73rem;font-weight:600;color:#059669;">
                                            Maks kompensasi: Rp {{ number_format($st->calculateBaseDamageRefundAmount(), 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                <div id="panel-komp-tolak"
                                    style="border:2px solid var(--border);border-radius:1rem;padding:1.25rem;cursor:pointer;transition:all .15s;background:#fff;"
                                    onclick="selectKompDecision('tolak')">
                                    <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.6rem;">
                                        <div id="dot-komp-tolak" style="width:16px;height:16px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .15s;"></div>
                                        <span style="font-size:.88rem;font-weight:700;color:#dc2626;">❌ Tolak Klaim Kerusakan</span>
                                    </div>
                                    <div style="font-size:.75rem;color:#475569;line-height:1.6;padding-left:1.6rem;">
                                        Bukti tidak mencukupi. Transaksi tetap sah, penyewa lanjut ke proses serah terima tanpa kompensasi.
                                        <div style="margin-top:.5rem;padding:.5rem .75rem;background:#fef2f2;border-radius:.6rem;font-size:.73rem;font-weight:600;color:#dc2626;">
                                            Penyewa tetap wajib upload dokumen serah terima
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('pengelola.penyewaan.handover.decide-damage', $penyewaan) }}"
                                method="POST" style="display:grid;gap:.875rem;" id="form-keputusan-komp">
                                @csrf
                                <input type="hidden" name="final_severity" value="ringan">
                                <input type="hidden" name="manager_decision" id="input-komp-decision" value="">

                                <div class="st-form-group" id="komp-amount-wrap" style="display:none;">
                                    <label class="st-form-label">Jumlah Kompensasi (Rp) <span class="req">*</span></label>
                                    <input type="number" name="compensation_amount" id="komp-amount-field" class="st-form-input"
                                        value="{{ old('compensation_amount') }}"
                                        min="1" max="{{ $st->calculateBaseDamageRefundAmount() }}"
                                        placeholder="Masukkan jumlah kompensasi">
                                    <p style="font-size:.72rem;color:#64748b;margin-top:.3rem;">
                                        Maks kompensasi: Rp {{ number_format($st->calculateBaseDamageRefundAmount(), 0, ',', '.') }}
                                    </p>
                                </div>

                                <div class="st-form-group">
                                    <label class="st-form-label">Alasan / Catatan untuk Penyewa <span class="req">*</span></label>
                                    <textarea name="notes" rows="4" class="st-form-textarea" id="komp-notes-field"
                                        placeholder="Wajib diisi — jelaskan alasan keputusan Anda kepada penyewa..."
                                        required>{{ old('notes') }}</textarea>
                                    <p id="komp-notes-hint" style="font-size:.72rem;color:#64748b;margin-top:.3rem;display:none;"></p>
                                </div>

                                <div>
                                    <button type="submit" id="btn-submit-komp"
                                            class="st-btn st-btn-slate" disabled style="opacity:.45;pointer-events:none;"
                                            onclick="return confirmKompKeputusan()">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Simpan Keputusan
                                    </button>
                                </div>
                            </form>

                            <script>
                            function selectKompDecision(val) {
                                document.getElementById('input-komp-decision').value = val;
                                ['setujui','tolak'].forEach(v => {
                                    const panel = document.getElementById('panel-komp-' + v);
                                    const dot   = document.getElementById('dot-komp-' + v);
                                    if (!panel) return;
                                    const active = v === val;
                                    panel.style.border     = '2px solid ' + (active ? (v === 'setujui' ? '#059669' : '#ef4444') : 'var(--border)');
                                    panel.style.background = active ? (v === 'setujui' ? '#f0fdf4' : '#fef2f2') : '#fff';
                                    if (dot) {
                                        dot.style.border     = active ? '5px solid ' + (v === 'setujui' ? '#059669' : '#ef4444') : '2px solid #d1d5db';
                                        dot.style.background = active ? (v === 'setujui' ? '#d1fae5' : '#fee2e2') : 'transparent';
                                    }
                                });
                                const amountWrap = document.getElementById('komp-amount-wrap');
                                const amountField = document.getElementById('komp-amount-field');
                                if (amountWrap) amountWrap.style.display = val === 'setujui' ? 'block' : 'none';
                                if (amountField) amountField.required = val === 'setujui';

                                const hints = {
                                    setujui: 'Penyewa akan diminta mengisi rekening untuk menerima transfer kompensasi.',
                                    tolak:   'Penyewa akan dinotifikasi bahwa klaim kompensasi ditolak dan diminta melanjutkan proses serah terima.'
                                };
                                const hint = document.getElementById('komp-notes-hint');
                                if (hint) { hint.textContent = hints[val] || ''; hint.style.display = val ? '' : 'none'; }

                                const btn = document.getElementById('btn-submit-komp');
                                if (btn) {
                                    btn.disabled = !val;
                                    btn.style.opacity = val ? '1' : '.45';
                                    btn.style.pointerEvents = val ? 'auto' : 'none';
                                    btn.className = val === 'setujui' ? 'st-btn st-btn-amber' : (val === 'tolak' ? 'st-btn st-btn-sky' : 'st-btn st-btn-slate');
                                }

                                document.getElementById('komp-notes-field').placeholder = val === 'setujui'
                                    ? 'Contoh: Kompensasi diberikan atas goresan pada bingkai. Akan ditransfer setelah penyewa mengisi rekening.'
                                    : (val === 'tolak' ? 'Contoh: Bukti yang dikirimkan tidak mencukupi untuk membuktikan kerusakan. Transaksi tetap dilanjutkan.' : '');
                            }

                            function confirmKompKeputusan() {
                                const val = document.getElementById('input-komp-decision').value;
                                if (!val) { alert('Pilih keputusan terlebih dahulu.'); return false; }
                                const notes = document.getElementById('komp-notes-field').value.trim();
                                if (!notes) { alert('Alasan / catatan untuk penyewa wajib diisi.'); return false; }
                                if (val === 'setujui') {
                                    const amt = document.getElementById('komp-amount-field').value;
                                    if (!amt || parseInt(amt) < 1) { alert('Jumlah kompensasi wajib diisi.'); return false; }
                                    return confirm('Setujui kompensasi Rp ' + parseInt(amt).toLocaleString('id-ID') + '?');
                                }
                                return confirm('Tolak klaim kompensasi? Penyewa akan diminta melanjutkan proses serah terima tanpa kompensasi.');
                            }

                            @if(old('manager_decision'))
                                document.addEventListener('DOMContentLoaded', () => selectKompDecision('{{ old('manager_decision') }}'));
                            @endif
                            </script>
                        @endif

                    </div>
                </div>

                <script>
                function toggleStCompensation() {
                    const sel  = document.getElementById('st-final-severity');
                    const wrap = document.getElementById('st-compensation-wrap');
                    if (!sel || !wrap) return;
                    wrap.style.display = sel.value === 'ringan' ? 'block' : 'none';
                }
                document.addEventListener('DOMContentLoaded', toggleStCompensation);
                </script>

            @endif
            @if($isPengelola && $status === 'menunggu_data_rekening')
                <div class="st-section st-section-amber">
                    <div class="st-eyebrow">⏳ Menunggu Penyewa</div>
                    <h2>{{ $isDamageCancellation ? 'Menunggu Pengembalian Koleksi & Data Refund' : ($isDamageCompensation ? 'Menunggu Data Rekening Kompensasi' : 'Menunggu Data Rekening Penyewa') }}</h2>
                    <p>
                        @if($isDamageCancellation)
                            Pembatalan disetujui. Penyewa perlu mengembalikan koleksi ke museum sekaligus mengisi data rekening refund dan ongkir pengembalian.
                            Setelah data terkirim, pantau pengiriman balik dan konfirmasi penerimaan koleksi di museum sebelum memproses refund.
                        @elseif($isDamageCompensation)
                            Kompensasi disetujui sebesar
                            <strong>Rp {{ number_format($st->arrival_damage_compensation_amount ?? 0, 0, ',', '.') }}</strong>.
                            Penyewa sedang diminta mengisi data rekening untuk transfer kompensasi. Setelah kompensasi diterima,
                            proses akan dilanjutkan ke dokumen serah terima tanpa pengembalian koleksi.
                        @else
                            Keputusan review kerusakan sudah disimpan. Penyewa sedang diminta mengisi data rekening
                            untuk proses refund manual.
                        @endif
                    </p>
                    @if($isDamageCompensation && $st->arrival_damage_manager_notes)
                    <div class="st-catatan" style="margin-top:1rem;">
                        <div class="lbl">Catatan Pengelola</div>
                        <div class="val">{{ $st->arrival_damage_manager_notes }}</div>
                    </div>
                    @endif
                </div>
            @endif

            {{-- PENGELOLA: Pantau pengembalian koleksi --}}
            @if($isPengelola && $status === 'menunggu_penerimaan_koleksi')
                <div class="st-section st-section-orange">
                    <div class="st-eyebrow">⚡ Tracking Pengembalian</div>
                    <h2>Pantau Pengembalian Koleksi ke Museum</h2>
                    <p>Penyewa telah mengirimkan informasi pengembalian koleksi. Pantau status pengiriman balik, lalu konfirmasi saat koleksi benar-benar tiba di museum.</p>
                </div>
                @include('serah_terima.partials.damage-return-tracking', ['isPengelola' => true])
            @endif
            @if($isPengelola && $status === 'pengecekan_kondisi')
                <div class="st-section st-section-sky">
                    <div class="st-eyebrow">🔍 Pengecekan Kondisi</div>
                    <h2>Menunggu Penyewa Memeriksa Kondisi Koleksi</h2>
                    <p>
                        Koleksi telah diterima oleh penyewa pada
                        <strong>{{ $penyewaan->received_at?->format('d M Y, H:i') ?? '-' }}</strong>.
                        Penyewa sedang melakukan pengecekan kondisi — apakah koleksi tiba dalam kondisi
                        baik atau terdapat kerusakan. Tidak ada aksi yang diperlukan dari pengelola saat ini.
                    </p>
                </div>

                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#0284c7,#38bdf8);"></div>
                        <h3>Detail Proses Pengecekan</h3>
                    </div>
                    <div class="st-card-body" style="display:flex;flex-direction:column;gap:1.25rem;">

                        {{-- Info pengiriman --}}
                        <div class="st-meta-grid">
                            @if($st->delivery_method)
                            <div class="st-meta-cell">
                                <div class="lbl">Metode Pengiriman</div>
                                <div class="val">
                                    {{ $penyewaan->shipping_method_type === 'courier' ? 'Kurir' : 'Pengelola' }}
                                    @if($st->delivery_method)
                                        — {{ $st->delivery_method }}
                                    @endif
                                </div>
                            </div>
                            @endif
                            @if($st->recipient_name)
                            <div class="st-meta-cell">
                                <div class="lbl">Diterima Oleh</div>
                                <div class="val">{{ $st->recipient_name }}</div>
                            </div>
                            @endif
                            @if($st->shipped_at)
                            <div class="st-meta-cell">
                                <div class="lbl">Dikirim Pada</div>
                                <div class="val">{{ $st->shipped_at->format('d M Y H:i') }}</div>
                            </div>
                            @endif
                            @if($penyewaan->received_at)
                            <div class="st-meta-cell" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border-color:#bae6fd;">
                                <div class="lbl">Diterima Pada</div>
                                <div class="val" style="color:#0369a1;">{{ $penyewaan->received_at->format('d M Y H:i') }}</div>
                            </div>
                            @endif
                        </div>

                        {{-- Alur selanjutnya --}}
                        <div style="background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:1rem;padding:1.1rem;">
                            <div style="font-size:.7rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#0369a1;margin-bottom:.75rem;">
                                📋 Alur Selanjutnya
                            </div>
                            <div style="display:flex;flex-direction:column;gap:.6rem;">
                                <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                    <div style="width:22px;height:22px;border-radius:50%;background:#0284c7;color:#fff;font-size:.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.05rem;">1</div>
                                    <div>
                                        <div style="font-size:.83rem;font-weight:600;color:#0b1d35;">Penyewa memeriksa kondisi koleksi</div>
                                        <div style="font-size:.74rem;color:#64748b;margin-top:.1rem;">Penyewa memilih apakah kondisi baik atau ada kerusakan.</div>
                                    </div>
                                </div>
                                <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                    <div style="width:22px;height:22px;border-radius:50%;background:#e2e8f0;color:#94a3b8;font-size:.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.05rem;">2a</div>
                                    <div>
                                        <div style="font-size:.83rem;font-weight:600;color:#475569;">Jika kondisi baik</div>
                                        <div style="font-size:.74rem;color:#64748b;margin-top:.1rem;">Penyewa langsung lanjut ke proses unduh dan upload dokumen serah terima.</div>
                                    </div>
                                </div>
                                <div style="display:flex;gap:.75rem;align-items:flex-start;">
                                    <div style="width:22px;height:22px;border-radius:50%;background:#e2e8f0;color:#94a3b8;font-size:.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.05rem;">2b</div>
                                    <div>
                                        <div style="font-size:.83rem;font-weight:600;color:#475569;">Jika ada kerusakan</div>
                                        <div style="font-size:.74rem;color:#64748b;margin-top:.1rem;">Penyewa melaporkan kerusakan beserta bukti. Status akan berubah ke <em>Menunggu Review Kerusakan</em> dan pengelola perlu meninjau laporan tersebut.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:1rem;padding:.875rem 1rem;display:flex;gap:.6rem;align-items:flex-start;">
                            <span style="font-size:1rem;flex-shrink:0;">⏳</span>
                            <p style="font-size:.78rem;color:#92400e;margin:0;line-height:1.6;">
                                Tidak ada aksi yang diperlukan dari pihak pengelola saat ini.
                                Halaman ini akan otomatis diperbarui setelah penyewa menyelesaikan pengecekan kondisi.
                            </p>
                        </div>

                    </div>
                </div>
            @endif
            @if($isPengelola && $status === 'menunggu_refund_kerusakan')
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#d97706,#f59e0b);"></div>
                        <h3>{{ $isDamageCompensation ? 'Proses Transfer Kompensasi' : 'Proses Refund Kerusakan' }}</h3>
                    </div>
                    <div class="st-card-body">
                        <p style="font-size:.84rem;color:#475569;margin:0 0 1.25rem;line-height:1.7;">
                            Transfer ke rekening penyewa sesuai data di bawah, lalu unggah bukti transfer.
                            @if($st->isFinalSeverityParah())
                                Refund penuh:
                                <strong>Rp {{ number_format($st->calculateFullDamageRefundAmount(), 0, ',', '.') }}</strong>
                                (dasar Rp {{ number_format($st->calculateBaseDamageRefundAmount(), 0, ',', '.') }}
                                @if((int)($st->return_shipping_cost ?? 0) > 0)
                                    + ongkir pengembalian Rp {{ number_format($st->return_shipping_cost, 0, ',', '.') }}
                                @endif
                                )
                            @else
                                Kompensasi: <strong>Rp {{ number_format($st->arrival_damage_compensation_amount ?? 0, 0, ',', '.') }}</strong>
                            @endif
                        </p>

                        @if($st->refund_bank_name)
                            <div class="st-meta-grid" style="margin-bottom:1rem;">
                                <div class="st-meta-cell">
                                    <div class="lbl">Bank</div>
                                    <div class="val">{{ $st->refund_bank_name }}</div>
                                </div>
                                <div class="st-meta-cell">
                                    <div class="lbl">No. Rekening</div>
                                    <div class="val">{{ $st->refund_account_number }}</div>
                                </div>
                                <div class="st-meta-cell">
                                    <div class="lbl">Atas Nama</div>
                                    <div class="val">{{ $st->refund_account_holder }}</div>
                                </div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="st-errors" style="margin-bottom:1rem;">
                                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <form action="{{ route('pengelola.penyewaan.handover.store-refund-proof', $penyewaan) }}"
                            method="POST" enctype="multipart/form-data" style="display:grid;gap:.875rem;">
                            @csrf
                            <div class="st-form-grid">
                                <div class="st-form-group">
                                    <label class="st-form-label">Nominal Transfer (Rp) <span class="req">*</span></label>
                                    <input type="number" name="refund_amount" class="st-form-input" required
                                        value="{{ $st->isFinalSeverityParah() ? $st->calculateFullDamageRefundAmount() : $st->arrival_damage_compensation_amount }}">
                                </div>
                                <div class="st-form-group">
                                    <label class="st-form-label">Tanggal Transfer <span class="req">*</span></label>
                                    <input type="date" name="refund_date" class="st-form-input" required value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="st-form-group" style="grid-column:1/-1;">
                                    <label class="st-form-label">Bukti Transfer <span class="req">*</span></label>
                                    <input type="file" name="transfer_proof" class="st-form-input" style="padding:.5rem .75rem;" accept="image/*,.pdf" required>
                                </div>
                                <div class="st-form-group" style="grid-column:1/-1;">
                                    <label class="st-form-label">Catatan <span class="opt">(opsional)</span></label>
                                    <textarea name="refund_notes" class="st-form-textarea" rows="2" placeholder="Contoh: Transfer berhasil, refund pembatalan kerusakan."></textarea>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="st-btn st-btn-amber">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Kirim Bukti Transfer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            {{-- PENGELOLA: Menunggu konfirmasi kompensasi/refund kerusakan saat penerimaan --}}
            @if($isPengelola && $status === 'menunggu_konfirmasi_refund' && $st->refund_processed_at && ! $penyewaan->depositRefund)
                <div class="st-section st-section-teal">
                    <div class="st-eyebrow">⏳ Menunggu Penyewa</div>
                    <h2>{{ $isDamageCompensation ? 'Kompensasi Telah Dikirim — Menunggu Konfirmasi' : 'Refund Telah Dikirim — Menunggu Konfirmasi' }}</h2>
                    <p>
                        Bukti transfer {{ $isDamageCompensation ? 'kompensasi' : 'refund' }} telah diunggah pada
                        <strong>{{ $st->refund_processed_at->format('d M Y H:i') }}</strong>.
                        @if($isDamageCompensation)
                            Menunggu penyewa mengkonfirmasi penerimaan kompensasi sebelum melanjutkan ke dokumen serah terima.
                        @else
                            Menunggu penyewa mengkonfirmasi penerimaan dana. Proses pembatalan akan selesai setelah konfirmasi.
                        @endif
                    </p>
                    @if($st->refund_bank_name)
                    <div class="st-meta-grid">
                        <div class="st-meta-cell">
                            <div class="lbl">Bank</div>
                            <div class="val">{{ $st->refund_bank_name }}</div>
                        </div>
                        <div class="st-meta-cell">
                            <div class="lbl">No. Rekening</div>
                            <div class="val">{{ $st->refund_account_number }}</div>
                        </div>
                        <div class="st-meta-cell">
                            <div class="lbl">Atas Nama</div>
                            <div class="val">{{ $st->refund_account_holder }}</div>
                        </div>
                        @if($st->refund_amount)
                        <div class="st-meta-cell success">
                            <div class="lbl">{{ $isDamageCompensation ? 'Nominal Kompensasi' : 'Nominal Refund' }}</div>
                            <div class="val">Rp {{ number_format($st->refund_amount, 0, ',', '.') }}</div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            @endif

            {{-- Info keputusan kerusakan saat lanjut ke dokumen serah terima --}}
            @if($status === 'menunggu_dokumen_serah_terima' && $st->arrival_damage_manager_decision === 'tolak_kompensasi')
                <div class="st-section" style="background:#fffbeb;border:1.5px solid #fde68a;">
                    <div class="st-eyebrow" style="color:#d97706;">⚠️ Klaim Kompensasi Ditolak</div>
                    <h2>Sewa Dilanjutkan Tanpa Kompensasi</h2>
                    @if($st->arrival_damage_manager_notes)
                    <div class="st-catatan" style="margin-top:1rem;background:#fff;border-color:#fde68a;">
                        <div class="lbl" style="color:#d97706;">Catatan Pengelola</div>
                        <div class="val">{{ $st->arrival_damage_manager_notes }}</div>
                    </div>
                    @endif
                </div>
            @elseif($status === 'menunggu_dokumen_serah_terima' && $st->arrival_damage_manager_decision === 'tolak_pembatalan')
                <div class="st-section" style="background:#fffbeb;border:1.5px solid #fde68a;">
                    <div class="st-eyebrow" style="color:#d97706;">⚠️ Pengajuan Pembatalan Ditolak</div>
                    <h2>Sewa Dilanjutkan</h2>
                    <p>Pengelola menolak pengajuan pembatalan akibat kerusakan saat pengiriman. Proses penyewaan tetap berlanjut — silakan lanjutkan ke unduh dan upload dokumen serah terima.</p>
                    @if($st->arrival_damage_manager_notes)
                    <div class="st-catatan" style="margin-top:1rem;background:#fff;border-color:#fde68a;">
                        <div class="lbl" style="color:#d97706;">Catatan Pengelola</div>
                        <div class="val">{{ $st->arrival_damage_manager_notes }}</div>
                    </div>
                    @endif
                </div>
            @elseif($status === 'menunggu_dokumen_serah_terima' && $isDamageCompensation && $st->refund_confirmed_at)
                <div class="st-section st-section-emerald">
                    <div class="st-eyebrow">✅ Kompensasi Selesai</div>
                    <h2>Lanjut ke Dokumen Serah Terima</h2>
                    <p>
                        Penyewa telah mengkonfirmasi penerimaan kompensasi pada
                        <strong>{{ $st->refund_confirmed_at->format('d M Y H:i') }}</strong>.
                        @if($isPengelola)
                            Menunggu penyewa mengunduh, menandatangani, dan mengunggah dokumen serah terima.
                        @else
                            Silakan unduh dokumen serah terima, periksa kondisi koleksi, tanda tangani, lalu upload kembali.
                        @endif
                    </p>
                    @if($st->refund_amount)
                    <div class="st-meta-grid">
                        <div class="st-meta-cell success">
                            <div class="lbl">Kompensasi Diterima</div>
                            <div class="val">Rp {{ number_format($st->refund_amount, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            @endif
            
            @if(!$isPengelola && $status === 'pengecekan_kondisi')

                {{-- Panduan --}}
                <div style="background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:1.25rem;padding:1.25rem;">
                    <div style="font-size:.67rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:#0369a1;margin-bottom:.5rem;">📋 Panduan Penilaian Kerusakan</div>
                    <p style="font-size:.82rem;color:#0369a1;margin:0 0 .35rem;"><strong>Ringan:</strong> goresan halus, noda kecil, retak minor pada bingkai yang tidak mempengaruhi nilai utama koleksi.</p>
                    <p style="font-size:.82rem;color:#0369a1;margin:0;"><strong>Parah:</strong> sobekan kanvas, pecah kaca pelindung, retak signifikan, deformasi fisik berat. Transaksi diarahkan ke pembatalan dengan refund penuh (dikurangi ongkir).</p>
                </div>

                {{-- Pilihan kondisi --}}
                <div class="st-card">
                    <div class="st-card-header">
                        <div class="st-card-header-accent"></div>
                        <h3>Kondisi Koleksi Saat Diterima</h3>
                    </div>
                    <div class="st-card-body">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                            {{-- Panel Kondisi Baik --}}
                            <div style="background:#f0fdf4;border:2px solid #bbf7d0;border-radius:1rem;padding:1.25rem;">
                                <h3 style="font-family:'Playfair Display',serif;font-size:1.1rem;margin:0 0 .35rem;color:var(--navy);">✅ Kondisi Baik</h3>
                                <p style="font-size:.8rem;color:#475569;margin:0 0 1rem;line-height:1.6;">Tidak ada kerusakan. Lanjut ke proses unduh dan upload dokumen serah terima.</p>
                                <button type="button" onclick="showConditionForm('good')"
                                    style="display:inline-flex;align-items:center;gap:.4rem;padding:.65rem 1.25rem;border-radius:.75rem;font-size:.82rem;font-weight:600;background:#059669;color:#fff;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;">
                                    Konfirmasi Kondisi Baik
                                </button>
                            </div>
                            {{-- Panel Ada Kerusakan --}}
                            <div style="background:#fef2f2;border:2px solid #fecaca;border-radius:1rem;padding:1.25rem;">
                                <h3 style="font-family:'Playfair Display',serif;font-size:1.1rem;margin:0 0 .35rem;color:var(--navy);">⚠️ Ada Kerusakan</h3>
                                <p style="font-size:.8rem;color:#475569;margin:0 0 1rem;line-height:1.6;">Laporkan kerusakan beserta bukti lengkap dalam satu kali pengisian.</p>
                                <button type="button" onclick="showConditionForm('damage')"
                                    style="display:inline-flex;align-items:center;gap:.4rem;padding:.65rem 1.25rem;border-radius:.75rem;font-size:.82rem;font-weight:600;background:#dc2626;color:#fff;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;">
                                    Laporkan Kerusakan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form: Konfirmasi Kondisi Baik --}}
                <div id="cek-form-good" style="display:none;">
                    <div class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-header-accent"></div>
                            <h3>Konfirmasi Kondisi Baik</h3>
                        </div>
                        <div class="st-card-body">
                            <p style="font-size:.84rem;color:#475569;margin:0 0 1.25rem;line-height:1.7;">
                                Anda menyatakan koleksi diterima dalam kondisi baik. Unggah foto sebagai dokumentasi penerimaan, lalu lanjutkan ke dokumen serah terima.
                            </p>

                            @if($errors->any() && !old('arrival_damage_severity'))
                                <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:.875rem;padding:.875rem 1rem;margin-bottom:1rem;">
                                    <ul style="margin:0;padding-left:1.25rem;font-size:.81rem;color:#991b1b;">
                                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('penyewaan.requests.handover.condition-good', $penyewaan) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- Foto Depan --}}
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        1. Foto Depan Koleksi <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Foto tampak depan koleksi saat diterima sebagai dokumentasi kondisi.</p>
                                    <input type="file" name="condition_front_photo"
                                        accept="image/jpg,image/jpeg,image/png" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- Foto Belakang --}}
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        2. Foto Belakang Koleksi <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Foto tampak belakang koleksi saat diterima.</p>
                                    <input type="file" name="condition_back_photo"
                                        accept="image/jpg,image/jpeg,image/png" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- Video (opsional) --}}
                                <div style="margin-bottom:1.5rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        3. Video Kondisi Koleksi <span style="font-weight:400;color:#94a3b8;">(opsional)</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Rekam video singkat kondisi koleksi jika diperlukan. Maks 50MB (MP4/MOV/AVI).</p>
                                    <input type="file" name="condition_video"
                                        accept="video/mp4,video/quicktime,video/avi"
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- Info box --}}
                                <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:.875rem;padding:.875rem 1rem;margin-bottom:1.25rem;display:flex;gap:.65rem;align-items:flex-start;">
                                    <span style="font-size:1rem;flex-shrink:0;">ℹ️</span>
                                    <p style="font-size:.78rem;color:#166534;margin:0;line-height:1.6;">
                                        Foto ini akan disimpan sebagai dokumentasi resmi penerimaan koleksi. Pastikan foto jelas dan menunjukkan kondisi koleksi secara keseluruhan.
                                    </p>
                                </div>

                                <div style="display:flex;gap:.65rem;flex-wrap:wrap;">
                                    <button type="submit"
                                        onclick="return confirm('Konfirmasi koleksi dalam kondisi baik dan lanjut ke dokumen serah terima?')"
                                        class="st-btn st-btn-emerald">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Lanjut ke Dokumen Serah Terima
                                    </button>
                                    <button type="button" onclick="hideConditionForm()" class="st-btn st-btn-ghost">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Form: Laporan Kerusakan --}}
                <div id="cek-form-damage" style="display:none;">
                    <div class="st-card">
                        <div class="st-card-header">
                            <div class="st-card-header-accent"></div>
                            <h3>Form Laporan Kerusakan</h3>
                        </div>
                        <div class="st-card-body">
                            @if($errors->any())
                                <div style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:.875rem;padding:.875rem 1rem;margin-bottom:1rem;">
                                    <ul style="margin:0;padding-left:1.25rem;font-size:.81rem;color:#991b1b;">
                                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('penyewaan.requests.handover.condition-damage', $penyewaan) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- ── 1. Foto Depan ── --}}
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        1. Foto Depan Koleksi <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Foto tampak depan koleksi saat diterima.</p>
                                    <input type="file" name="condition_front_photo"
                                        accept="image/jpg,image/jpeg,image/png" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- ── 2. Foto Belakang ── --}}
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        2. Foto Belakang Koleksi <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Foto tampak belakang koleksi saat diterima.</p>
                                    <input type="file" name="condition_back_photo"
                                        accept="image/jpg,image/jpeg,image/png" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- ── 3. Video Kerusakan ── --}}
                                <div style="margin-bottom:1.25rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        3. Video Bukti Kerusakan <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Rekam video yang menunjukkan kerusakan secara jelas. Maks 50MB (MP4/MOV/AVI).</p>
                                    <input type="file" name="damage_video"
                                        accept="video/mp4,video/quicktime,video/avi" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- ── 4. Checklist Jenis Kerusakan ── --}}
                                <div style="margin-bottom:1.25rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.5rem;">
                                        4. Jenis Kerusakan <span style="color:#ef4444">*</span>
                                    </label>
                                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                                        @foreach($damageChecklistItems as $key => $label)
                                            <div style="display:flex;gap:.5rem;align-items:flex-start;background:#f8fafc;border:1.5px solid var(--border);border-radius:.75rem;padding:.75rem;">
                                                <input type="checkbox"
                                                    name="arrival_damage_checklist[{{ $key }}]"
                                                    value="{{ $key }}" id="chk-{{ $key }}"
                                                    @if($loop->last) data-target="desc-lainnya" onchange="toggleDescById(this)" @endif
                                                    style="margin-top:.15rem;flex-shrink:0;">
                                                <div style="flex:1;">
                                                    <label for="chk-{{ $key }}" style="font-size:.82rem;font-weight:600;color:var(--navy);cursor:pointer;">{{ $label }}</label>
                                                    @if($loop->last)
                                                        <div id="desc-lainnya" style="display:none;margin-top:.4rem;">
                                                            <textarea name="item_descriptions[{{ $key }}]" rows="2"
                                                                    style="width:100%;border:1.5px solid var(--border);border-radius:.55rem;padding:.45rem .65rem;font-size:.78rem;font-family:'DM Sans',sans-serif;resize:vertical;"
                                                                    placeholder="Jelaskan jenis kerusakan lainnya..."></textarea>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- ── 5. Deskripsi umum ── --}}
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        5. Deskripsi Umum Kerusakan
                                    </label>
                                    <textarea name="arrival_damage_description" rows="3"
                                            class="st-form-textarea"
                                            placeholder="Ceritakan kondisi kerusakan secara umum...">{{ old('arrival_damage_description') }}</textarea>
                                </div>

                                {{-- ── 6. Keputusan Anda ── --}}
                                <div style="margin-bottom:1.25rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.25rem;">
                                        6. Keputusan Anda <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.75rem;color:var(--slate);margin:0 0 .75rem;line-height:1.6;">
                                        Pilih keputusan Anda terkait kerusakan yang ditemukan. Keputusan ini akan diverifikasi terlebih dahulu oleh pengelola — jika pengelola menilai kerusakan tidak terbukti, maka pembatalan dapat ditolak.
                                    </p>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                                        <label id="dec-lanjut"
                                            style="border:2px solid var(--border);border-radius:.875rem;padding:1rem;cursor:pointer;transition:all .15s;background:#fff;"
                                            onclick="selectDecision('lanjut')">
                                            <input type="radio" name="buyer_decision" value="lanjut" style="display:none;">
                                            <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.5rem;">
                                                <div id="dec-lanjut-dot" style="width:16px;height:16px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .15s;"></div>
                                                <span style="font-size:.88rem;font-weight:700;color:#059669;">✅ Ajukan Kompensasi</span>
                                            </div>
                                            <div style="font-size:.74rem;color:var(--slate);line-height:1.6;padding-left:1.6rem;">
                                                Saya menerima koleksi dan mengajukan kompensasi atas kerusakan yang terjadi.<br>                                            </div>
                                        </label>
                                        <label id="dec-batalkan"
                                            style="border:2px solid var(--border);border-radius:.875rem;padding:1rem;cursor:pointer;transition:all .15s;background:#fff;"
                                            onclick="selectDecision('batalkan')">
                                            <input type="radio" name="buyer_decision" value="batalkan" style="display:none;">
                                            <div style="display:flex;align-items:center;gap:.6rem;margin-bottom:.5rem;">
                                                <div id="dec-batalkan-dot" style="width:16px;height:16px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .15s;"></div>
                                                <span style="font-size:.88rem;font-weight:700;color:#dc2626;">❌ Ajukan Pembatalan</span>
                                            </div>
                                            <div style="font-size:.74rem;color:var(--slate);line-height:1.6;padding-left:1.6rem;">
                                                Saya mengajukan pembatalan transaksi dan pengembalian dana.<br>                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- ── 8. Foto Packing ── --}}
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        8. Foto Kondisi Packing <span style="color:#ef4444">*</span>
                                    </label>
                                    <input type="file" name="packing_condition_photos[]" multiple
                                        accept="image/*" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>

                                {{-- ── 9. Bukti Kurir (jika kurir) ── --}}
                                @if($isKurir)
                                <div style="margin-bottom:1rem;">
                                    <label style="display:block;font-size:.78rem;font-weight:700;color:var(--navy);margin-bottom:.35rem;">
                                        9. Bukti Penerimaan dari Kurir <span style="color:#ef4444">*</span>
                                    </label>
                                    <p style="font-size:.74rem;color:var(--slate);margin:0 0 .4rem;">Foto tanda terima atau kondisi paket saat diterima dari kurir.</p>
                                    <input type="file" name="courier_receipt_photos[]" multiple
                                        accept="image/*" required
                                        class="st-form-input" style="padding:.5rem .75rem;">
                                </div>
                                @endif

                                <div style="display:flex;gap:.65rem;margin-top:1.5rem;flex-wrap:wrap;">
                                    <button type="submit"
                                        onclick="return validateDamageForm()"
                                        style="display:inline-flex;align-items:center;gap:.4rem;padding:.65rem 1.35rem;border-radius:.875rem;font-size:.82rem;font-weight:600;background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;border:none;cursor:pointer;font-family:'DM Sans',sans-serif;">
                                        Kirim Laporan Kerusakan
                                    </button>
                                    <button type="button" onclick="hideConditionForm()" class="st-btn st-btn-ghost">Batal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                function showConditionForm(type) {
                    document.getElementById('cek-form-good').style.display   = 'none';
                    document.getElementById('cek-form-damage').style.display = 'none';
                    const el = document.getElementById('cek-form-' + type);
                    if (el) { el.style.display = ''; el.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
                }
                function hideConditionForm() {
                    document.getElementById('cek-form-good').style.display   = 'none';
                    document.getElementById('cek-form-damage').style.display = 'none';
                }
                function handleChecklistChange(el) {
                    if (el.dataset.hasDesc !== 'true') return;
                    const descId = 'desc-' + el.value;
                    const desc = document.getElementById(descId);
                    if (desc) desc.style.display = el.checked ? 'block' : 'none';
                }
                function toggleDescById(el) {
                    const targetId = el.getAttribute('data-target');
                    const desc = document.getElementById(targetId);
                    if (desc) desc.style.display = el.checked ? 'block' : 'none';
                }

                // Pilih severity → tampilkan opsi keputusan yang sesuai
                function selectSev(v) {
                    ['ringan','parah'].forEach(s => {
                        const el = document.getElementById('sev-' + s);
                        if (!el) return;
                        if (s === v) {
                            el.style.border    = '2px solid ' + (s === 'ringan' ? '#f59e0b' : '#ef4444');
                            el.style.background = s === 'ringan' ? '#fffbeb' : '#fef2f2';
                        } else {
                            el.style.border    = '2px solid var(--border)';
                            el.style.background = 'transparent';
                        }
                        const inp = el.querySelector('input');
                        if (inp) inp.checked = (s === v);
                    });

                    const decisionBox   = document.getElementById('decision-box');
                    const decRingan     = document.getElementById('decision-ringan');
                    const decParah      = document.getElementById('decision-parah');
                    const parahInput    = document.getElementById('decision-parah-input');

                    decisionBox.style.display = '';

                    if (v === 'ringan') {
                        decRingan.style.display = 'grid';
                        decParah.style.display  = 'none';
                        if (parahInput) parahInput.disabled = true;
                    } else {
                        decRingan.style.display = 'none';
                        decParah.style.display  = '';
                        if (parahInput) parahInput.disabled = false;
                        // Reset pilihan ringan jika ada
                        ['lanjut','batalkan'].forEach(d => {
                            const el = document.getElementById('dec-' + d);
                            if (el) { el.style.border = '2px solid var(--border)'; el.style.background = 'transparent'; }
                            const inp = el?.querySelector('input');
                            if (inp) inp.checked = false;
                        });
                    }
                }

                // Pilih keputusan (hanya untuk ringan)
                function selectDecision(v) {
                    ['lanjut','batalkan'].forEach(d => {
                        const el  = document.getElementById('dec-' + d);
                        const dot = document.getElementById('dec-' + d + '-dot');
                        if (!el) return;
                        if (d === v) {
                            el.style.border     = '2px solid ' + (d === 'lanjut' ? '#059669' : '#ef4444');
                            el.style.background = d === 'lanjut' ? '#f0fdf4' : '#fef2f2';
                            if (dot) {
                                dot.style.border     = '5px solid ' + (d === 'lanjut' ? '#059669' : '#ef4444');
                                dot.style.background = d === 'lanjut' ? '#d1fae5' : '#fee2e2';
                            }
                        } else {
                            el.style.border     = '2px solid var(--border)';
                            el.style.background = '#fff';
                            if (dot) {
                                dot.style.border     = '2px solid #d1d5db';
                                dot.style.background = 'transparent';
                            }
                        }
                        const inp = el.querySelector('input');
                        if (inp) inp.checked = (d === v);
                    });
                }

                // Validasi sebelum submit
                function validateDamageForm() {
                    const decision = document.querySelector('input[name="buyer_decision"]:checked');
                    if (!decision) { alert('Pilih keputusan Anda terlebih dahulu (Lanjut Beli atau Batalkan).'); return false; }

                    const checklist = document.querySelectorAll('input[name^="arrival_damage_checklist"]:checked');
                    if (checklist.length === 0) { alert('Pilih minimal satu jenis kerusakan.'); return false; }

                    return confirm('Kirim laporan kerusakan? Data yang sudah dikirim tidak dapat diubah.');
                }
                </script>
            @endif
