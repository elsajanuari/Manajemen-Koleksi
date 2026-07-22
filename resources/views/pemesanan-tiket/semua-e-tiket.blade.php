<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">

    <x-ticket.styles />
    <x-ticket.progress />

    <div class="eti-page">
        <div class="eti-container">
            <div class="eti-actions" style="flex-wrap:wrap; gap:0.75rem;">
                <a href="{{ route('pemesanan-tiket.show', $pemesananTiket) }}" class="btn-back">&#8592; Kembali</a>
            </div>

            <x-ticket.toolbar 
                :pemesananTiket="$pemesananTiket"
                :detailPengunjungs="$pemesananTiket->detailPengunjungs"
            />

            @foreach($pemesananTiket->detailPengunjungs as $idx => $detail)
                <x-ticket.single 
                    :pemesananTiket="$pemesananTiket"
                    :detailPengunjung="$detail"
                    :index="$idx"
                    :total="$pemesananTiket->detailPengunjungs->count()"
                />
            @endforeach
        </div>
    </div>

    <x-ticket.scripts />
</x-app-layout>