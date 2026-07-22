@props(['ticket', 'pemesananTiket', 'detailPengunjung', 'index', 'total'])

<div class="tk-stub">
    <div>
        <p class="stub-label">E-Ticket · Museum</p>
        <h1 class="stub-title">{{ $ticket->nama_tiket }}</h1>
        <p class="stub-id">{{ isset($total) ? 'Tiket ' . ($index + 1) . ' / ' . $total : 'ID #' . str_pad((string) $pemesananTiket->id, 5, '0', STR_PAD_LEFT) }}</p>
    </div>
    <div>
        {{-- Tanggal dan Status dalam satu baris --}}
        <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; position:relative; z-index:1;">
            <div>
                <p class="stub-date-label">Tanggal Kunjungan</p>
                <p class="stub-date-val">{{ $pemesananTiket->tanggal_pemesanan->locale('id')->translatedFormat('d F Y') }}</p>
            </div>
            @if($detailPengunjung->tiket_terpakai_at)
                <span class="stub-badge badge-used"><span class="badge-dot"></span> Digunakan</span>
            @else
                <span class="stub-badge badge-ok"><span class="badge-dot"></span> Aktif</span>
            @endif
        </div>
    </div>
</div>