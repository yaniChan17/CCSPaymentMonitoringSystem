<!-- Admin Navigation -->
<div class="space-y-1">
    <!-- Dashboard -->
    <a href="{{ route('admin.dashboard') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span>Dashboard</span>
    </a>

    <!-- Users Management (Direct Link - No Dropdown) -->
    <a href="{{ route('admin.users.index') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <span>Users</span>
    </a>

    <!-- Payments Management (Direct Link - No Dropdown) -->
    <a href="{{ route('admin.payments.index') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.payments.*') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span>Payments</span>
    </a>

    <!-- Fee Schedules -->
    <a href="{{ route('admin.fee-schedules.index') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.fee-schedules.*') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span>Fee Schedules</span>
    </a>

    <!-- Announcements -->
    <a href="{{ route('admin.announcements.index') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.announcements.*') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
        </svg>
        <span>Announcements</span>
    </a>

    <!-- Blocks Management -->
    <a href="{{ route('admin.blocks.index') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.blocks.*') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
        <span>Blocks</span>
    </a>

    <!-- Reports (Dropdown Only) -->
    <div x-data="{ open: {{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Reports</span>
            </div>
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" x-transition class="ml-11 mt-1 space-y-1">
            <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary-600 hover:bg-red-50 rounded-lg transition-colors">Dashboard Report</a>
            <a href="{{ route('admin.reports.summary') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-primary-600 hover:bg-red-50 rounded-lg transition-colors">Summary Report</a>
        </div>
    </div>
</div>
