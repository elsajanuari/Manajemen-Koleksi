<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <button @click="open = !open"
            type="button"
            class="relative p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-all duration-200 {{ request()->routeIs('notifications.*') ? 'text-blue-600 bg-blue-50' : '' }}"
            aria-label="Notifikasi">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if (($unreadNotificationCount ?? 0) > 0)
            <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center px-1 text-[10px] font-bold text-white bg-red-500 rounded-full">
                {{ ($unreadNotificationCount ?? 0) > 99 ? '99+' : $unreadNotificationCount }}
            </span>
        @endif
    </button>

    <div x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
            <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
            @if (($unreadNotificationCount ?? 0) > 0)
                <span class="text-xs text-red-600 font-medium">{{ $unreadNotificationCount }} belum dibaca</span>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse ($recentNotifications ?? [] as $notification)
                @php($presenter = \App\Services\NotificationPresenter::from($notification))
                <a href="{{ route('notifications.show', ['notification' => $notification->id]) }}"
                   @click="open = false"
                   class="block px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition {{ $notification->read_at ? '' : 'bg-blue-50/50' }}">
                    <div class="flex items-start gap-2">
                        @unless ($notification->read_at)
                            <span class="mt-1.5 w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                        @else
                            <span class="mt-1.5 w-2 h-2 flex-shrink-0"></span>
                        @endunless
                        <div class="min-w-0 flex-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $presenter->categoryBadgeClass() }}">
                                {{ $presenter->category() }}
                            </span>
                            <p class="mt-1 text-sm text-gray-800 line-clamp-2">{{ $presenter->message() }}</p>
                            <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->locale('id')->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-8 text-center text-sm text-gray-500">
                    Tidak ada notifikasi belum dibaca.
                </div>
            @endforelse
        </div>

        <div class="px-4 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between gap-2">
            <a href="{{ route('notifications.index') }}"
               @click="open = false"
               class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                Lihat semua
            </a>
            @if (($unreadNotificationCount ?? 0) > 0)
                <form method="POST" action="{{ route('notifications.markAllRead') }}">
                    @csrf
                    <button type="submit" class="text-xs text-gray-500 hover:text-gray-700">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
