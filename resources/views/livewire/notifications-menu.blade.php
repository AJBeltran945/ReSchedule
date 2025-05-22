<div class="relative">
    {{-- Bell Icon --}}
    <button wire:click="toggle" class="relative focus:outline-none">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                {{ $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    @if($open)
        <div class="absolute right-0 mt-2 w-80 bg-white border shadow-xl z-50 rounded p-3">
            <div class="flex justify-between items-center mb-2">
                <h4 class="font-semibold">Notifications</h4>
                <button wire:click="markAllAsRead" class="text-sm text-blue-600">Mark all as read</button>
            </div>
            @forelse($notifications as $notification)
                <div class="mb-2">
                    <div class="text-sm font-medium">{{ $notification->data['title'] ?? 'Notification' }}</div>
                    <div class="text-xs text-gray-600">{{ $notification->data['message'] ?? '' }}</div>
                    <div class="text-xs text-gray-400">
                        {{ $notification->created_at->diffForHumans() }}
                        @if(is_null($notification->read_at))
                            <button wire:click="markAsRead('{{ $notification->id }}')" class="ml-2 text-blue-500 text-xs">Mark as read</button>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No notifications</p>
            @endforelse
        </div>
    @endif
</div>
