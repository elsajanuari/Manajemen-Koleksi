@props(['badge'])

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-sm font-medium ' . ($badge['bgColor'] ?? 'bg-gray-100') . ' ' . ($badge['textColor'] ?? 'text-gray-700')]) }}>
    @if (!empty($badge['icon']))
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $badge['icon'] }}" />
        </svg>
    @endif
    {{ $badge['label'] }}
</span>
