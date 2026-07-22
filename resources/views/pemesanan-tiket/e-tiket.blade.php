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
                <button class="btn-download" id="btn-dl" onclick="downloadSingleTicket()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Unduh Tiket
                </button>
            </div>

            {{-- Single ticket --}}
            <x-ticket.single 
                :pemesananTiket="$pemesananTiket"
                :detailPengunjung="$detailPengunjung"
                :index="0"
                :total="1"
                :showDownload="false"
            />
        </div>
    </div>

    <x-ticket.scripts />

    <script>
    // Override untuk single ticket
    async function downloadSingleTicket() {
        const btn = document.getElementById('btn-dl');
        const orig = btn.innerHTML;
        btn.innerHTML = '⏳ Memproses...';
        btn.disabled = true;
        showOvl();
        setProgress(20, 'Merender tiket...', '');

        try {
            const canvas = await renderCard(0);
            setProgress(70, 'Membuat PDF...', '');
            const pdf = makePdf(canvas);
            setProgress(95, 'Mengunduh...', '');
            pdf.save('e-tiket-{{ str_pad((string) $pemesananTiket->id, 5, '0', STR_PAD_LEFT) }}-1-{{ Str::slug($detailPengunjung->getDisplayName()) }}.pdf');
            setProgress(100, 'Selesai!', '');
        } catch (e) {
            alert('Gagal mengunduh. Coba lagi.');
            console.error(e);
        } finally {
            setTimeout(hideOvl, 400);
            btn.innerHTML = orig;
            btn.disabled = false;
        }
    }
    </script>
</x-app-layout>