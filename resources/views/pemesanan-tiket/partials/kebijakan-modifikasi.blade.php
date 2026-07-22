@php
    $jam = (int) config('museum.batas_modifikasi_jam', 48);
    $pemesanan->loadMissing('ticket');
@endphp

<div class="flex flex-wrap gap-2 text-xs">
    @if($pemesanan->ticket->boleh_reschedule)
        <span class="inline-flex items-center rounded-full bg-sky-100 text-sky-800 px-2.5 py-1 font-medium">Reschedule</span>
    @else
        <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-600 px-2.5 py-1">Reschedule tidak tersedia</span>
    @endif

    @if($pemesanan->ticket->boleh_cancel)
        <span class="inline-flex items-center rounded-full bg-violet-100 text-violet-800 px-2.5 py-1 font-medium">Cancel/refund</span>
    @else
        <span class="inline-flex items-center rounded-full bg-gray-100 text-gray-600 px-2.5 py-1">Cancel tidak tersedia</span>
    @endif
</div>

@if(! $pemesanan->isCancelled() && in_array($pemesanan->status, ['lunas', 'menunggu_pembayaran'], true))
    @if($pemesanan->dapatReschedule() || $pemesanan->dapatCancel())
        <p class="text-xs text-gray-500 mt-2">Batas perubahan: {{ $jam }} jam sebelum kunjungan ({{ $pemesanan->batasWaktuModifikasi()->translatedFormat('d M Y H:i') }})</p>
    @elseif($pemesanan->ticket->boleh_reschedule || $pemesanan->ticket->boleh_cancel)
        <p class="text-xs text-amber-700 mt-2">Batas waktu {{ $jam }} jam sebelum kunjungan telah lewat.</p>
    @endif
@endif
