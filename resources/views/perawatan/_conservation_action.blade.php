@php
    $perawatan = $perawatan ?? null;
    $compact = $compact ?? false;
    $fixedWidth = $fixedWidth ?? false;
    $step = $perawatan?->getConservationWorkflowStep();

    $compactClass = $fixedWidth
        ? 'inline-flex w-28 items-center justify-center rounded-md px-3 py-1.5 text-xs font-semibold whitespace-nowrap'
        : ($compact ? 'text-xs px-2 py-1 rounded' : 'px-4 py-2 text-sm rounded-lg') . ' inline-flex items-center whitespace-nowrap';
@endphp

@if ($perawatan && $perawatan->requiresConservation() && $perawatan->isScheduled())
    @if ($perawatan->conservationAction)
        <a href="{{ $perawatan->conservation_action_url }}"
            class="{{ $compactClass }} font-medium transition
                @if ($step === 'hasil') bg-green-100 text-green-700 hover:bg-green-200
                @elseif ($step === 'selesai') bg-gray-100 text-gray-700 hover:bg-gray-200
                @else bg-blue-100 text-blue-700 hover:bg-blue-200 @endif">
            @if ($step === 'rencana')
                Isi Rencana
            @elseif ($step === 'pelaksanaan')
                Catat Pelaksanaan
            @elseif ($step === 'hasil')
                Catat Hasil
            @else
                Lihat Konservasi
            @endif
        </a>
    @elseif ($perawatan->canStartConservation())
        <form method="POST" action="{{ route('konservasi.tindakan.store') }}" class="inline">
            @csrf
            <input type="hidden" name="koleksi_id" value="{{ $perawatan->koleksi_id }}" />
            <input type="hidden" name="kondisi_koleksi_id" value="{{ $perawatan->kondisi_koleksi_id }}" />
            <input type="hidden" name="perawatan_koleksi_id" value="{{ $perawatan->id }}" />
            <button type="submit"
                class="{{ $compactClass }} bg-blue-600 font-medium text-white hover:bg-blue-700 transition">
                {{ $compact ? 'Konservasi' : 'Catat Tindakan Konservasi' }}
            </button>
        </form>
    @else
        <span class="{{ $compactClass }} bg-gray-100 text-gray-500"
            title="Jadwal tidak terhubung ke pemeriksaan kondisi">
            Konservasi tidak tersedia
        </span>
    @endif
@endif
