<x-sidebar-layout>
    <x-slot name="navigation">
        @if(auth()->user()->isAdmin())
            <x-nav.admin />
        @elseif(auth()->user()->isTreasurer())
            <x-nav.treasurer />
        @else
            <x-nav.student />
        @endif
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
                <p class="text-sm text-gray-600 mt-1">Stay updated with your latest notifications</p>
            </div>
            @if($notifications->where('is_read', false)->count() > 0)
            <button onclick="markAllAsRead()" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                Mark All as Read
            </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @forelse($notifications as $notification)
            <div class="px-6 py-4 border-b border-gray-100 hover:bg-gray-50 transition-colors {{ $notification->is_read ? 'opacity-75' : 'bg-blue-50' }}">
                <div class="flex items-start space-x-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0 w-12 h-12 rounded-full {{ $notification->is_read ? 'bg-gray-100' : 'bg-indigo-100' }} flex items-center justify-center">
                        @if($notification->type === 'fee_posted')
                        <svg class="w-6 h-6 {{ $notification->is_read ? 'text-gray-600' : 'text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @elseif($notification->type === 'payment_recorded')
                        <svg class="w-6 h-6 {{ $notification->is_read ? 'text-gray-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @elseif($notification->type === 'announcement')
                        <svg class="w-6 h-6 {{ $notification->is_read ? 'text-gray-600' : 'text-purple-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        @else
                        <svg class="w-6 h-6 {{ $notification->is_read ? 'text-gray-600' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-gray-900">{{ $notification->title }}</h3>
                            @if(!$notification->is_read)
                            <span class="flex-shrink-0 w-2 h-2 bg-indigo-600 rounded-full"></span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-700 mt-1">{{ $notification->message }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                            @if(!$notification->is_read)
                            <button onclick="markAsRead({{ $notification->id }})" 
                                    class="text-xs text-indigo-600 hover:text-indigo-800">
                                Mark as read
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-16 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No notifications</h3>
                <p class="text-sm text-gray-600">You're all caught up!</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>

    <script>
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(() => {
            window.location.reload();
        });
    }

    function markAllAsRead() {
        fetch('{{ route('notifications.read-all') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(() => {
            window.location.reload();
        });
    }
    </script>
</x-sidebar-layout>
