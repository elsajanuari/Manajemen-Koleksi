<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifikasi') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Semua Notifikasi</h3>
                    </div>
                    <form method="POST" action="{{ route('notifications.markAllRead') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                            Tandai Semua Dibaca
                        </button>
                    </form>
                </div>

                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-4">
                    @forelse ($notifications as $notification)
                        @php($presenter = \App\Services\NotificationPresenter::from($notification))
                        <a href="{{ route('notifications.show', ['notification' => $notification->id]) }}"
                            class="block rounded-xl border p-4 {{ $notification->read_at ? 'bg-gray-50 border-gray-200' : 'bg-blue-50 border-blue-200' }} hover:bg-gray-100 transition">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $presenter->categoryBadgeClass() }}">
                                    {{ $presenter->category() }}
                                </span>
                                @unless ($notification->read_at)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        Baru
                                    </span>
                                @endunless
                            </div>
                            <p class="text-sm text-gray-900">{{ $presenter->message() }}</p>
                            <div class="mt-2 text-xs text-gray-500">
                                {{ $presenter->formattedCreatedAt() }}
                                @if ($notification->read_at)
                                    · Dibaca
                                @else
                                    · Belum dibaca
                                @endif
                            </div>
                        </a>
                    @empty
                        <div class="rounded-xl border border-gray-200 p-6 text-center text-sm text-gray-500">
                            Tidak ada notifikasi saat ini.
                        </div>
                    @endforelse
                </div>

                @if ($notifications->hasPages())
                    <div class="mt-6">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
