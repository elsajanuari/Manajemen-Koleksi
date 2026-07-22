<x-app-layout>
    <x-slot name="header"></x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    <style>
        :root { --navy:#0b1d35; --blue:#1d4ed8; --sky:#38bdf8; --cream:#f2f5f9; --slate:#64748b; --border:#e2e8f0; --white:#fff; }
        .cc-root { font-family:'DM Sans',sans-serif; background:var(--cream); min-height:100vh; padding-bottom:4rem; }
        .cc-hero { background:linear-gradient(140deg,#0b1d35,#142744 55%,#1c3a68); padding:2rem 0; }
        .cc-hero-inner { max-width:1100px; margin:0 auto; padding:0 2rem; }
        .cc-hero-id { font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700; color:#fff; margin:0 0 .25rem; }
        .cc-hero-title { font-size:.85rem; color:rgba(255,255,255,.55); margin:0; }
        .cc-content { max-width:1100px; margin:0 auto; padding:1.5rem 2rem 0; display:grid; gap:1.25rem; }
        .cc-card { background:var(--white); border:1.5px solid var(--border); border-radius:1.25rem; overflow:hidden; }
        .cc-card-header { padding:1rem 1.25rem; border-bottom:1px solid #f0f4f8; font-size:.75rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--navy); }
        .cc-card-body { padding:1.25rem; }
        .cc-flash { border-radius:.75rem; padding:.75rem 1rem; font-size:.83rem; font-weight:600; }
        .cc-flash.ok { background:#d1fae5; color:#065f46; }
        .cc-flash.err { background:#fee2e2; color:#991b1b; }
        .cc-choice-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        @media(max-width:600px){ .cc-choice-grid { grid-template-columns:1fr; } }
        .cc-panel { border-radius:1rem; padding:1.25rem; border:2px solid transparent; cursor:pointer; }
        .cc-panel.good { background:#f0fdf4; border-color:#bbf7d0; }
        .cc-panel.damage { background:#fef2f2; border-color:#fecaca; }
        .cc-panel h3 { font-family:'Playfair Display',serif; font-size:1.1rem; margin:0 0 .35rem; color:var(--navy); }
        .cc-panel p { font-size:.8rem; color:#475569; margin:0 0 1rem; line-height:1.6; }
        .cc-btn { display:inline-flex; align-items:center; gap:.4rem; padding:.65rem 1.25rem; border-radius:.75rem; font-size:.82rem; font-weight:600; border:none; cursor:pointer; font-family:'DM Sans',sans-serif; }
        .cc-btn-emerald { background:#059669; color:#fff; }
        .cc-btn-red { background:#dc2626; color:#fff; }
        .cc-btn-ghost { background:transparent; border:1.5px solid var(--border); color:var(--slate); }
        .cc-form-section { display:none; }
        .cc-form-section.active { display:block; }
        .cc-label { display:block; font-size:.78rem; font-weight:700; color:var(--navy); margin-bottom:.35rem; }
        .cc-input { width:100%; border:1.5px solid var(--border); border-radius:.65rem; padding:.6rem .8rem; font-size:.85rem; font-family:'DM Sans',sans-serif; }
        .cc-textarea { resize:vertical; min-height:80px; }
        .cc-check-grid { display:grid; grid-template-columns:1fr 1fr; gap:.6rem; }
        @media(max-width:560px){ .cc-check-grid { grid-template-columns:1fr; } }
        .cc-check-item { display:flex; gap:.5rem; align-items:flex-start; background:#f8fafc; border:1.5px solid var(--border); border-radius:.75rem; padding:.75rem; }
        .cc-check-item input { margin-top:.15rem; }
        .cc-severity-grid { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
        .cc-sev-card { border:2px solid var(--border); border-radius:.875rem; padding:1rem; cursor:pointer; }
        .cc-sev-card.ringan.selected { border-color:#f59e0b; background:#fffbeb; }
        .cc-sev-card.parah.selected { border-color:#ef4444; background:#fef2f2; }
        .cc-sev-card input { display:none; }
        .cc-guide { background:#f0f9ff; border:1.5px solid #bae6fd; border-radius:.875rem; padding:1rem; font-size:.8rem; color:#0369a1; line-height:1.65; }
        .cc-guide h4 { margin:0 0 .5rem; font-size:.85rem; color:#0284c7; }
        .cc-guide ul { margin:0; padding-left:1.1rem; }
        .cc-error { font-size:.72rem; color:#dc2626; margin-top:.25rem; }
        .cc-item-desc { margin-top:.5rem; }
        .cc-item-desc textarea { font-size:.78rem; }
    </style>

    <div class="cc-root">
        <div class="cc-hero">
            <div class="cc-hero-inner">
                <h1 class="cc-hero-id">Pengecekan Kondisi Koleksi</h1>
                <p class="cc-hero-title">{{ $pembelian->painting->title }} — BLI-{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="cc-hero-title" style="margin-top:.35rem;">
                    Metode pengiriman: {{ $isKurir ? 'Kurir (' . ($pembelian->courier_name ?? 'Eksternal') . ')' : 'Pengiriman oleh Pengelola' }}
                </p>
            </div>
        </div>

        <div class="cc-content">
            @if(session('success'))<div class="cc-flash ok">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="cc-flash err">{{ session('error') }}</div>@endif
            @if($errors->any())<div class="cc-flash err">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif

            @if($pembelian->status === 'menunggu_review_kerusakan')
                <div class="cc-card">
                    <div class="cc-card-header">Laporan Terkirim</div>
                    <div class="cc-card-body">
                        <p style="font-size:.85rem;color:#475569;line-height:1.7;">
                            Laporan kerusakan sudah dikirim pada
                            {{ $pembelian->arrival_damage_reported_at?->format('d M Y, H:i') }}.
                            Proses dokumen serah terima ditahan sementara. Tunggu review pengelola.
                        </p>
                        <a href="{{ route('pembelian.show', $pembelian) }}" class="cc-btn cc-btn-ghost" style="margin-top:1rem;text-decoration:none;">← Kembali ke Detail Pengajuan</a>
                    </div>
                </div>
            @else
                <div class="cc-guide">
                    <h4>📋 Panduan Penilaian Kerusakan</h4>
                    <ul>
                        <li><strong>Ringan:</strong> goresan halus, noda kecil, retak minor pada bingkai yang tidak mempengaruhi nilai utama koleksi. Dapat dilanjutkan dengan kompensasi parsial.</li>
                        <li><strong>Parah:</strong> sobekan kanvas, pecah kaca pelindung, retak signifikan, deformasi fisik berat. Transaksi diarahkan ke pembatalan dengan refund penuh (dikurangi ongkir).</li>
                    </ul>
                </div>

                <div class="cc-card">
                    <div class="cc-card-header">Kondisi Koleksi Saat Diterima</div>
                    <div class="cc-card-body">
                        <div class="cc-choice-grid">
                            <div class="cc-panel good" onclick="showForm('good')">
                                <h3>✅ Kondisi Baik</h3>
                                <p>Tidak ada kerusakan. Lanjut ke proses unduh dan upload dokumen serah terima.</p>
                                <button type="button" class="cc-btn cc-btn-emerald" onclick="showForm('good')">Konfirmasi Kondisi Baik</button>
                            </div>
                            <div class="cc-panel damage" onclick="showForm('damage')">
                                <h3>⚠️ Ada Kerusakan</h3>
                                <p>Laporkan kerusakan beserta bukti lengkap dalam satu kali pengisian.</p>
                                <button type="button" class="cc-btn cc-btn-red" onclick="showForm('damage')">Laporkan Kerusakan</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="form-good" class="cc-form-section">
                    <div class="cc-card">
                        <div class="cc-card-header">Konfirmasi Kondisi Baik</div>
                        <div class="cc-card-body">
                            <p style="font-size:.84rem;color:#475569;margin:0 0 1rem;">Anda menyatakan koleksi diterima tanpa kerusakan. Proses akan dilanjutkan ke dokumen serah terima.</p>
                            <form action="{{ route('pembelian.condition-good', $pembelian) }}" method="POST">
                                @csrf
                                <button type="submit" class="cc-btn cc-btn-emerald" onclick="return confirm('Konfirmasi koleksi dalam kondisi baik?')">
                                    Lanjut ke Dokumen Serah Terima
                                </button>
                                <button type="button" class="cc-btn cc-btn-ghost" onclick="hideForm()">Batal</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div id="form-damage" class="cc-form-section">
                    <div class="cc-card">
                        <div class="cc-card-header">Form Laporan Kerusakan</div>
                        <div class="cc-card-body">
                            <form action="{{ route('pembelian.condition-damage', $pembelian) }}" method="POST" enctype="multipart/form-data" id="damage-form">
                                @csrf

                                <p class="cc-label" style="margin-bottom:.75rem;">1. Jenis Kerusakan & Deskripsi per Item</p>
                                <div class="cc-check-grid" style="margin-bottom:1.25rem;">
                                    @foreach($damageChecklistItems as $key => $label)
                                        <div class="cc-check-item">
                                            <input type="checkbox" name="arrival_damage_checklist[{{ $key }}]" value="{{ $key }}" id="chk-{{ $key }}"
                                                   onchange="toggleDesc('{{ $key }}', this.checked)">
                                            <div style="flex:1;">
                                                <label for="chk-{{ $key }}" style="font-size:.82rem;font-weight:600;color:var(--navy);">{{ $label }}</label>
                                                <div class="cc-item-desc" id="desc-wrap-{{ $key }}" style="display:none;">
                                                    <textarea name="item_descriptions[{{ $key }}]" class="cc-input cc-textarea" rows="2"
                                                              placeholder="Deskripsi kerusakan pada item ini..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <p class="cc-label">2. Foto / Video Bukti Kerusakan <span style="color:#ef4444">*</span></p>
                                <input type="file" name="arrival_damage_photos[]" class="cc-input" multiple accept="image/*,video/mp4,video/quicktime" required style="margin-bottom:1rem;">

                                <p class="cc-label">3. Deskripsi Umum Kerusakan</p>
                                <textarea name="arrival_damage_description" class="cc-input cc-textarea" placeholder="Ceritakan kondisi kerusakan secara umum...">{{ old('arrival_damage_description') }}</textarea>

                                <p class="cc-label" style="margin-top:1rem;">4. Tingkat Keparahan <span style="color:#ef4444">*</span></p>
                                <div class="cc-severity-grid" style="margin-bottom:1rem;">
                                    <label class="cc-sev-card ringan" id="sev-ringan" onclick="selectSev('ringan')">
                                        <input type="radio" name="arrival_damage_severity" value="ringan">
                                        <strong>🟡 Ringan</strong><br>
                                        <span style="font-size:.75rem;color:var(--slate);">Kerusakan kecil, nilai utama koleksi masih terjaga.</span>
                                    </label>
                                    <label class="cc-sev-card parah" id="sev-parah" onclick="selectSev('parah')">
                                        <input type="radio" name="arrival_damage_severity" value="parah">
                                        <strong>🔴 Parah</strong><br>
                                        <span style="font-size:.75rem;color:var(--slate);">Kerusakan signifikan yang mempengaruhi nilai atau tampilan.</span>
                                    </label>
                                </div>

                                <p class="cc-label">5. Foto Kondisi Packing <span style="color:#ef4444">*</span></p>
                                <input type="file" name="packing_condition_photos[]" class="cc-input" multiple accept="image/*" required style="margin-bottom:1rem;">

                                @if($isKurir)
                                    <p class="cc-label">6. Bukti Penerimaan dari Kurir <span style="color:#ef4444">*</span></p>
                                    <p style="font-size:.75rem;color:var(--slate);margin:0 0 .5rem;">Foto bukti serah terima dari kurir / kondisi saat diterima dari kurir.</p>
                                    <input type="file" name="courier_receipt_photos[]" class="cc-input" multiple accept="image/*" required style="margin-bottom:1rem;">
                                @endif

                                <button type="submit" class="cc-btn cc-btn-red" onclick="return confirm('Kirim laporan kerusakan? Dokumen serah terima akan ditahan hingga review selesai.')">
                                    Kirim Laporan Kerusakan
                                </button>
                                <button type="button" class="cc-btn cc-btn-ghost" onclick="hideForm()">Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function showForm(type) {
            document.querySelectorAll('.cc-form-section').forEach(el => el.classList.remove('active'));
            const t = document.getElementById('form-' + type);
            if (t) { t.classList.add('active'); t.scrollIntoView({ behavior: 'smooth' }); }
        }
        function hideForm() {
            document.querySelectorAll('.cc-form-section').forEach(el => el.classList.remove('active'));
        }
        function toggleDesc(key, show) {
            const w = document.getElementById('desc-wrap-' + key);
            if (w) w.style.display = show ? 'block' : 'none';
        }
        function selectSev(v) {
            document.querySelectorAll('.cc-sev-card').forEach(c => c.classList.remove('selected'));
            const el = document.getElementById('sev-' + v);
            if (el) el.classList.add('selected');
            const inp = el.querySelector('input');
            if (inp) inp.checked = true;
        }
    </script>
</x-app-layout>
