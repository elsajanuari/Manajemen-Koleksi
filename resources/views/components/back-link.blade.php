@props(['href', 'label' => 'Kembali'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
    </svg>
    {{ $label }}
</a>
