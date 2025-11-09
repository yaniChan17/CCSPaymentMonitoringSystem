<x-sidebar-layout>
    <x-slot name="navigation">
        @if(Auth::user()->isAdmin())
            <x-nav.admin />
        @elseif(Auth::user()->isTreasurer())
            <x-nav.treasurer />
        @else
            <x-nav.student />
        @endif
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                <p class="mt-1 text-sm text-gray-600">Stay updated with your payment activities</p>
            </div>
            @if($notifications->where('is_read', false)->count() > 0)
                <button 
                    @click="
                        fetch('{{ route('notifications.read-all') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        }).then(() => window.location.reload())
                    "
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mark All as Read
                </button>
            @endif
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px" aria-label="Tabs">
                    <a href="{{ route('notifications.index') }}" 
                       class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm {{ !request('filter') || request('filter') === 'all' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        All
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ !request('filter') || request('filter') === 'all' ? 'bg-primary-100 text-primary-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ $allCount ?? $notifications->total() }}
                        </span>
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                       class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm {{ request('filter') === 'unread' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Unread
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ request('filter') === 'unread' ? 'bg-primary-100 text-primary-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ auth()->user()->notifications()->unread()->count() }}
                        </span>
                    </a>
                    <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
                       class="flex-1 py-4 px-1 text-center border-b-2 font-medium text-sm {{ request('filter') === 'read' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Read
                        <span class="ml-2 py-0.5 px-2 rounded-full text-xs {{ request('filter') === 'read' ? 'bg-primary-100 text-primary-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ auth()->user()->notifications()->where('is_read', true)->count() }}
                        </span>
                    </a>
                </nav>
            </div>

            <!-- Notifications List -->
            <div class="divide-y divide-gray-100">
                @forelse($notifications as $notification)
                    <div class="p-6 hover:bg-gray-50 transition-colors {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}">
                        <div class="flex items-start space-x-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0 text-3xl">
                                @if($notification->type === 'fee_posted')
                                    📋
                                @elseif($notification->type === 'payment_recorded')
                                    💵
                                @else
                                    🔔
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <h3 class="text-base font-semibold text-gray-900">
                                            {{ $notification->title }}
                                        </h3>
                                        <p class="mt-2 text-sm text-gray-600">
                                            {{ $notification->message }}
                                        </p>
                                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $notification->created_at->format('M d, Y h:i A') }} 
                                            ({{ $notification->created_at->diffForHumans() }})
                                        </p>
                                    </div>

                                    <!-- Mark as Read Button -->
                                    @if(!$notification->is_read)
                                        <button 
                                            @click="
                                                fetch('{{ route('notifications.read', $notification->id) }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Content-Type': 'application/json',
                                                        'Accept': 'application/json'
                                                    }
                                                }).then(() => window.location.reload())
                                            "
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-md hover:bg-blue-700 transition-colors shadow-sm">
                                            Mark as Read
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No notifications</h3>
                        <p class="text-sm text-gray-500">
                            @if(request('filter') === 'unread')
                                You have no unread notifications.
                            @elseif(request('filter') === 'read')
                                You have no read notifications.
                            @else
                                You don't have any notifications yet.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-sidebar-layout>
