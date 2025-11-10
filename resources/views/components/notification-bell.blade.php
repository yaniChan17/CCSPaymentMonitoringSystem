@props(['unreadCount' => 0])

<div class="relative" x-data="{ open: false }" @click.away="open = false">
    <!-- Notification Bell Button -->
    <button 
        @click="open = !open" 
        class="relative inline-flex items-center p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500"
        aria-label="Notifications"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        <!-- Unread Badge -->
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full min-w-[1.25rem]">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Notification Dropdown -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 overflow-hidden"
        style="display: none;"
    >
        <!-- Dropdown Header -->
        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto custom-scrollbar">
            @forelse(auth()->user()->notifications()->orderBy('created_at', 'desc')->take(5)->get() as $notification)
                <div class="px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 {{ $notification->is_read ? '' : 'bg-blue-50' }}">
                    <div class="flex items-start space-x-3">
                        <!-- Icon based on type -->
                        <div class="flex-shrink-0 text-2xl">
                            @if($notification->type === 'fee_posted')
                                📋
                            @elseif($notification->type === 'payment_recorded')
                                💵
                            @else
                                🔔
                            @endif
                        </div>

                        <!-- Notification Content -->
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                                {{ Str::limit($notification->message, 80) }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <!-- Mark as Read Button -->
                        @if(!$notification->is_read)
                            <button 
                                @click.stop="
                                    fetch('{{ route('notifications.read', $notification->id) }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json'
                                        }
                                    }).then(() => window.location.reload())
                                "
                                class="flex-shrink-0 text-blue-600 hover:text-blue-800 text-xs font-medium"
                                title="Mark as read"
                            >
                                ✓
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-sm">No notifications yet</p>
                </div>
            @endforelse
        </div>

        <!-- View All Link -->
        @if(auth()->user()->notifications()->count() > 0)
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-800 flex items-center justify-center">
                    View all notifications
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div>
