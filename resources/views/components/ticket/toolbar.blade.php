@props(['pemesananTiket', 'detailPengunjungs', 'showDownloadAll' => true])

<div class="multi-toolbar">
    <div class="tab-scroll">
        <span class="tab-lbl">📋</span>
        @foreach($detailPengunjungs as $idx => $detail)
            <button class="tab-pill {{ $idx === 0 ? 'active' : '' }} {{ $detail->tiket_terpakai_at ? 'used-pill' : '' }}"
                id="tab-{{ $idx }}" onclick="showTicket({{ $idx }})">
                {{ $detail->getDisplayName() }}
                @if($detail->tiket_terpakai_at)<span class="badge">✓</span>@endif
            </button>
        @endforeach
    </div>
    <div class="toolbar-actions">
        @if($showDownloadAll)
            <button class="btn-dl-all" id="btn-dl-all" onclick="downloadAll({{ $detailPengunjungs->count() }},'{{ str_pad((string) $pemesananTiket->id, 5, '0', STR_PAD_LEFT) }}')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Unduh Semua (ZIP)
            </button>
        @endif
    </div>
</div>