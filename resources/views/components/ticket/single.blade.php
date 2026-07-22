@props(['pemesananTiket', 'detailPengunjung', 'index', 'total', 'showDownload' => true])

@php
    $verUrl = route('pengelola.scan-tiket', ['token' => $detailPengunjung->tiket_verifikasi_token], absolute: true);
    $builder = new \Endroid\QrCode\Builder\Builder(
        writer: new \Endroid\QrCode\Writer\PngWriter(),
        validateResult: false
    );
    $qrUri = $builder->build(data: $verUrl, size: 220)->getDataUri();
    $prefix = str_pad((string) $pemesananTiket->id, 5, '0', STR_PAD_LEFT);
@endphp

<div class="ticket-wrap {{ $index === 0 ? 'visible' : '' }}" id="ticket-wrap-{{ $index }}">
    @if($showDownload)
        <div class="ticket-dl-row">
            <button class="btn-dl-one" id="btn-dl-{{ $index }}"
                onclick="downloadSingle({{ $index }},'{{ Str::slug($detailPengunjung->getDisplayName()) }}',{{ $index + 1 }},'{{ $prefix }}')">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Unduh Tiket #{{ $index + 1 }}
            </button>
        </div>
    @endif

    <div class="ticket-outer">
        <div class="ticket-card" id="ticket-card-{{ $index }}" data-idx="{{ $index }}">
            <x-ticket.stub 
                :ticket="$pemesananTiket->ticket"
                :pemesananTiket="$pemesananTiket"
                :detailPengunjung="$detailPengunjung"
                :index="$index"
                :total="$total"
            />
            <div class="tk-perforation"></div>
            <x-ticket.body 
                :detailPengunjung="$detailPengunjung"
                :pemesananTiket="$pemesananTiket"
                :qrUri="$qrUri"
                :verificationUrl="$verUrl"
            />
        </div>
    </div>
</div>