<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Notifikasi') }}
        </h2>
    </x-slot>

    @php($presenter = \App\Services\NotificationPresenter::from($notification))

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $presenter->categoryBadgeClass() }}">
                            {{ $presenter->category() }}
                        </span>
                        <h3 class="mt-3 text-lg font-semibold text-gray-900">Detail Notifikasi</h3>
                        <p class="text-sm text-gray-500">Informasi lengkap pembaruan sistem.</p>
                    </div>
                    <div class="text-right text-xs text-gray-400">
                        {{ $presenter->formattedCreatedAt() }}
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 mb-6">
                    <p class="text-sm text-gray-900">{{ $presenter->message() }}</p>
                </div>

                @if (count($presenter->details()) > 0)
                    <div class="rounded-xl border border-gray-200 p-4 bg-white mb-6">
                        <p class="text-xs uppercase tracking-wider text-gray-500 mb-3">Informasi Terkait</p>
                        <dl class="space-y-3">
                            @foreach ($presenter->details() as $label => $value)
                                <div>
                                    <dt class="text-xs text-gray-500">{{ $label }}</dt>
                                    <dd class="text-sm text-gray-900 font-medium">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                @endif

                @if ($presenter->actionUrl() && $presenter->actionLabel())
                    <a href="{{ $presenter->actionUrl() }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 transition">
                        {{ $presenter->actionLabel() }}
                    </a>
                @endif

                <div class="mt-8">
                    <a href="{{ route('notifications.index') }}"
                        class="text-sm text-indigo-600 hover:text-indigo-800">Kembali ke daftar notifikasi</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
