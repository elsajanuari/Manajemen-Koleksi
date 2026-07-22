@props(['required' => false, 'optional' => false])

<div>
    <p {{ $attributes->merge(['class' => 'text-xs font-semibold text-gray-500 uppercase tracking-wide']) }}>
        {{ $slot }}
    </p>
    @if ($required)
        <span class="text-xs text-red-600 font-medium">Wajib diisi</span>
    @elseif ($optional)
        <span class="text-xs text-gray-500">Opsional</span>
    @endif
</div>
