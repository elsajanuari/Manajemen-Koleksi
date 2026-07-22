{{-- Partial: tracking pengembalian koleksi (pembatalan kerusakan) — dipakai di serah-terima pembelian --}}
@php
    $returnTrackingUrl = $isPengelola
        ? route('pengelola.pembelian.serah-terima.tracking-data', $pembelian) . '?for=return'
        : route('pembelian.serah-terima.tracking-data', $pembelian) . '?for=return';
@endphp

{{-- Card: Info Pengembalian Koleksi --}}
<div class="st-card">
    <div class="st-card-header">
        <div class="st-card-header-accent" style="background:linear-gradient(180deg,#d97706,#f59e0b);"></div>
        <h3>📦 Info Pengembalian Koleksi</h3>
    </div>
    <div class="st-card-body">
        <p style="font-size:.84rem;color:#475569;margin:0 0 1rem;line-height:1.7;">
            Informasi pengembalian koleksi sudah terkirim. Pantau progres pengiriman balik di bawah.
        </p>
        <div class="st-meta-grid" style="margin-top:0;">
            <div class="st-meta-cell">
                <div class="lbl">Metode</div>
                <div class="val">{{ $pembelian->return_shipment_method ?? '-' }}</div>
            </div>
            <div class="st-meta-cell">
                <div class="lbl">No. Resi</div>
                <div class="val" style="font-family:monospace;">{{ $pembelian->return_shipment_tracking ?? '-' }}</div>
            </div>
            <div class="st-meta-cell">
                <div class="lbl">Pengirim</div>
                <div class="val">{{ $pembelian->return_shipment_officer ?? '-' }}</div>
            </div>
            <div class="st-meta-cell">
                <div class="lbl">Rencana Kirim</div>
                <div class="val">{{ $pembelian->return_shipment_scheduled_at?->format('d M Y H:i') ?? '-' }}</div>
            </div>
            @if($pembelian->return_shipping_cost !== null)
            <div class="st-meta-cell" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border-color:#fde68a;">
                <div class="lbl">Ongkir Pengembalian</div>
                <div class="val" style="color:#d97706;">Rp {{ number_format($pembelian->return_shipping_cost, 0, ',', '.') }}</div>
            </div>
            @endif
        </div>
        @if($pembelian->return_shipment_notes)
            <div class="st-catatan" style="margin-top:.875rem;">
                <div class="lbl">Catatan</div>
                <div class="val">{{ $pembelian->return_shipment_notes }}</div>
            </div>
        @endif
        @if($pembelian->refund_bank_name)
            <div style="margin-top:1rem;padding-top:1rem;border-top:1.5px solid #f0f4f8;">
                <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:.6rem;">Data Rekening Refund</div>
                <div class="st-meta-grid" style="margin-top:0;">
                    <div class="st-meta-cell">
                        <div class="lbl">Bank Refund</div>
                        <div class="val">{{ $pembelian->refund_bank_name }}</div>
                    </div>
                    <div class="st-meta-cell">
                        <div class="lbl">No. Rekening</div>
                        <div class="val">{{ $pembelian->refund_account_number }}</div>
                    </div>
                    <div class="st-meta-cell">
                        <div class="lbl">Atas Nama</div>
                        <div class="val">{{ $pembelian->refund_account_holder }}</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@if($pembelian->return_shipment_tracking)
    @include('penyewaan.partials.tracking-card', ['trackingUrl' => $returnTrackingUrl])
@else
    @php
        $returnStatuses = \App\Models\Pembelian::returnShipmentStatuses();
        $statusKeys = array_keys($returnStatuses);
        $currentStatus = $pembelian->return_shipment_status;
        $currentIdx = $currentStatus ? array_search($currentStatus, $statusKeys) : -1;
    @endphp
    <div class="st-catatan" style="background:#f8fafc;border-color:#e2e8f0;">
        <div class="lbl">Pengiriman Mandiri</div>
        <div class="val">
            @if($isPengelola)
                Pembeli mengirim balik koleksi tanpa nomor resi. Monitor progres berdasarkan update dari pembeli.
            @else
                Update status secara bertahap agar pengelola dapat memantau progres pengembalian.
            @endif
        </div>
    </div>

    @if(!$isPengelola)
        @if($pembelian->return_shipment_status === 'tiba_di_tujuan')
            <div class="st-catatan" style="background:#d1fae5;border-color:#6ee7b7;">
                <div class="lbl" style="color:#065f46;">✅ Koleksi Sudah Tiba di Museum</div>
                <div class="val">Tunggu konfirmasi pengelola bahwa koleksi sudah diterima.</div>
            </div>
        @else
            <div class="st-card">
                <div class="st-card-header">
                    <div class="st-card-header-accent" style="background:linear-gradient(180deg,#6d28d9,#7c3aed);"></div>
                    <h3>Update Status Pengembalian</h3>
                </div>
                <div class="st-card-body">
                    <form action="{{ route('pembelian.return-status', $pembelian) }}" method="POST">
                        @csrf
                        @if($errors->any())<div class="st-errors"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                        <div class="st-form-group">
                            <div class="st-radio-grid">
                                @foreach($returnStatuses as $value => $label)
                                    @php
                                        $statusIndex = array_search($value, $statusKeys);
                                        $canUpdate = $currentIdx === -1 ? $statusIndex === 0 : $statusIndex === $currentIdx + 1;
                                    @endphp
                                    <label class="st-radio-label">
                                        <input type="radio" name="return_shipment_status" value="{{ $value }}"
                                            {{ old('return_shipment_status', $currentStatus) === $value ? 'checked' : '' }}
                                            {{ $canUpdate ? '' : 'disabled' }}>
                                        <span>{!! $label !!}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="st-form-group">
                            <label class="st-form-label">Catatan <span class="opt">(opsional)</span></label>
                            <input name="catatan_status" value="{{ old('catatan_status') }}" class="st-form-input" placeholder="Detail update..."/>
                        </div>
                        <div style="display:flex;justify-content:flex-end;">
                            <button type="submit" class="st-btn st-btn-violet">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif

    @if(!empty($pembelian->return_shipment_timeline))
        <div class="st-card">
            <div class="st-card-header">
                <div class="st-card-header-accent" style="background:linear-gradient(180deg,#0284c7,#38bdf8);"></div>
                <h3>Timeline Pengembalian{{ $isPengelola ? ' (Update dari Pembeli)' : '' }}</h3>
            </div>
            <div class="st-card-body">
                <div class="st-timeline">
                    @foreach(array_reverse($pembelian->return_shipment_timeline) as $entry)
                        <div class="st-timeline-item" style="border-color:#bae6fd;">
                            <div class="st-timeline-dot" style="background:#38bdf8;"></div>
                            <div class="st-timeline-body">
                                <div class="tlabel">{{ $entry['label'] }}</div>
                                @if(!empty($entry['catatan']))<div class="tnote">{{ $entry['catatan'] }}</div>@endif
                                <div class="tmeta">{{ \Carbon\Carbon::parse($entry['timestamp'])->format('d M Y, H:i') }} • oleh {{ $entry['by'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endif

{{-- Card: Konfirmasi Koleksi Tiba di Museum (hanya pengelola) --}}
@if($isPengelola && !$pembelian->collection_arrived_at)
    <div class="st-card" style="border-color:#6ee7b7;">
        <div class="st-card-header" style="background:linear-gradient(135deg,#f0fdf4,#ecfdf5);border-bottom-color:#bbf7d0;">
            <div class="st-card-header-accent" style="background:linear-gradient(180deg,#059669,#10b981);"></div>
            <h3 style="color:#064e3b;">⚡ Konfirmasi Penerimaan Koleksi</h3>
        </div>
        <div class="st-card-body" style="background:linear-gradient(135deg,#f0fdf4,#ecfdf5);">
            <p style="font-size:.84rem;color:#065f46;margin:0 0 1.25rem;line-height:1.7;">
                Setelah koleksi benar-benar sudah diterima kembali di museum, konfirmasi untuk melanjutkan proses refund ke pembeli (termasuk ongkir pengembalian).
            </p>
            <form method="POST" action="{{ route('pengelola.pembelian.serah-terima.confirm-collection-arrived', $pembelian) }}">
                @csrf
                <button type="submit"
                    onclick="return confirm('Konfirmasi koleksi sudah tiba di museum?')"
                    class="st-btn st-btn-emerald">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Konfirmasi Koleksi Tiba di Museum
                </button>
            </form>
        </div>
    </div>
@endif