<div x-data="{ open: false, count: 0, notifications: [] }" 
     x-init="
        // Fetch unread count on load
        fetch('{{ route('notifications.unread-count') }}')
            .then(res => res.json())
            .then(data => count = data.count);
        
        // Poll for updates every 30 seconds
        setInterval(() => {
            fetch('{{ route('notifications.unread-count') }}')
                .then(res => res.json())
                .then(data => count = data.count);
        }, 30000);
     "
     @click.away="open = false"
     class="relative">
    
    <!-- Bell Button -->
    <button @click="open = !open" 
            class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        <!-- Badge -->
        <span x-show="count > 0" 
              x-text="count > 99 ? '99+' : count"
              class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full min-w-[20px]">
        </span>
    </button>
    
    <!-- Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
         style="display: none;">
        
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
            @if(auth()->user()->notifications()->unread()->count() > 0)
            <button onclick="markAllAsRead()" 
                    class="text-xs text-indigo-600 hover:text-indigo-800">
                Mark all as read
            </button>
            @endif
        </div>
        
        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @php
                $recentNotifications = auth()->user()->notifications()->latest()->take(5)->get();
            @endphp
            
            @forelse($recentNotifications as $notification)
            <a href="{{ route('notifications.index') }}" 
               class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition-colors {{ $notification->is_read ? 'opacity-75' : '' }}">
                <div class="flex items-start space-x-3">
                    <!-- Icon -->
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                        @if($notification->type === 'fee_posted')
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @elseif($notification->type === 'payment_recorded')
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @else
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                        <p class="text-xs text-gray-600 mt-0.5 line-clamp-2">{{ $notification->message }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    
                    <!-- Unread indicator -->
                    @if(!$notification->is_read)
                    <div class="flex-shrink-0 w-2 h-2 bg-indigo-600 rounded-full mt-2"></div>
                    @endif
                </div>
            </a>
            @empty
            <div class="px-4 py-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-sm">No notifications yet</p>
            </div>
            @endforelse
        </div>
        
        <!-- Footer -->
        @if($recentNotifications->count() > 0)
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            <a href="{{ route('notifications.index') }}" 
               class="block text-center text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                View all notifications
            </a>
        </div>
        @endif
    </div>
</div>

<script>
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
