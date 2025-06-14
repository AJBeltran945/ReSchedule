<div class="relative">
    {{-- Bell Icon --}}
    <button wire:click="toggle" class="relative focus:outline-none group">
        <svg class="w-6 h-6 text-white group-hover:text-royal transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        @if($unreadCount > 0)
        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center animate-ping">
            {{ $unreadCount }}
        </span>
        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center">
            {{ $unreadCount }}
        </span>
        @endif
    </button>

    {{-- Dropdown --}}
    @if($open)
        <div
            wire:click.away="$set('open', false)"
            class="
            absolute right-4 top-14 z-50
            w-[20rem] max-w-[90vw]
            bg-midnight-dark text-white
            border border-royal rounded-lg shadow-xl
            p-4 overflow-y-auto max-h-[80vh]
        "
        >
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold text-lg text-royal">Notifications</h4>
                <button wire:click="markAllAsRead" class="text-sm text-royal hover:underline">
                    Mark all as read
                </button>
            </div>

            @forelse($notifications as $notification)
                <div class="mb-3 border-b border-royal pb-2">
                    <div class="text-sm font-semibold text-white">
                        {{ $notification->data['title'] ?? 'Notification' }}
                    </div>
                    <div class="text-xs text-gray-300 whitespace-normal break-words">
                        {{ $notification->data['message'] ?? '' }}
                    </div>
                    <div class="text-xs text-gray-400 flex items-center justify-between">
                        <span>{{ $notification->created_at->diffForHumans() }}</span>
                        @if(is_null($notification->read_at))
                            <button wire:click="markAsRead('{{ $notification->id }}')" class="text-royal hover:underline text-xs">
                                Mark as read
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400">No notifications</p>
            @endforelse
        </div>
    @endif
</div>
