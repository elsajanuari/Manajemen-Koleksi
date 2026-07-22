@props([
    'action',
    'step',
    'variant' => 'breadcrumb',
])

@php
    $detailUrl = route('konservasi.tindakan.show', $action);
    $indexUrl = route('konservasi.tindakan.index');

    $stepLabel = match ($step) {
        'rencana' => 'Rencana',
        'pelaksanaan' => 'Pelaksanaan',
        'hasil' => 'Hasil',
        default => ucfirst($step),
    };

    $previousStep = match ($step) {
        'pelaksanaan' => ['url' => route('konservasi.tindakan.plan', $action), 'label' => 'Rencana'],
        'hasil' => ['url' => route('konservasi.tindakan.pelaksanaan', $action), 'label' => 'Pelaksanaan'],
        default => null,
    };

    $nextStep = match ($step) {
        'rencana' => ['url' => route('konservasi.tindakan.pelaksanaan', $action), 'label' => 'Pelaksanaan'],
        'pelaksanaan' => ['url' => route('konservasi.tindakan.hasil', $action), 'label' => 'Hasil'],
        default => null,
    };

    $buttonClass = 'inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold transition shrink-0';
@endphp

@if ($variant === 'actions')
    <div {{ $attributes->merge(['class' => 'flex flex-wrap items-center justify-end gap-2']) }}>
        @if ($previousStep)
            <a href="{{ $previousStep['url'] }}" class="{{ $buttonClass }} border border-gray-200 bg-white text-gray-700 hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ $previousStep['label'] }}
            </a>
        @endif
        @if ($nextStep)
            <a href="{{ $nextStep['url'] }}" class="{{ $buttonClass }} border border-indigo-200 bg-indigo-50 text-indigo-700 hover:bg-indigo-100">
                {{ $nextStep['label'] }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @endif
        <a href="{{ $detailUrl }}" class="{{ $buttonClass }} bg-gray-100 text-gray-700 hover:bg-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Detail
        </a>
        <a href="{{ $indexUrl }}" class="{{ $buttonClass }} border border-gray-200 bg-white text-gray-600 hover:bg-gray-50">
            Tindakan Konservasi
        </a>
    </div>
@else
    <div {{ $attributes->merge(['class' => 'space-y-2']) }}>
        <nav class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500" aria-label="Navigasi">
            <a href="{{ $indexUrl }}" class="hover:text-indigo-600">Tindakan Konservasi</a>
            <span class="text-gray-300" aria-hidden="true">/</span>
            <a href="{{ $detailUrl }}" class="hover:text-indigo-600">Detail</a>
            <span class="text-gray-300" aria-hidden="true">/</span>
            <span class="font-medium text-gray-800">{{ $stepLabel }}</span>
        </nav>
        <x-back-link :href="$detailUrl" label="Kembali ke detail tindakan" />
    </div>
@endif
