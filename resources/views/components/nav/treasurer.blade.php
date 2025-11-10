<!-- Treasurer Navigation -->
<div class="space-y-1">
    <!-- Dashboard -->
    <a href="{{ route('treasurer.dashboard') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('treasurer.dashboard') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span>Dashboard</span>
    </a>

    <!-- Payments -->
    <a href="{{ route('treasurer.payments.index') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('treasurer.payments.*') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <span>My Payments</span>
    </a>

    <!-- My Students -->
    <a href="{{ route('treasurer.students.index') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('treasurer.students.*') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <span>My Students</span>
    </a>

    <!-- Profile -->
    <a href="{{ route('profile.edit') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('profile.edit') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
        </svg>
        <span>Profile</span>
    </a>

    <!-- Settings -->
    <a href="{{ route('settings.edit') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('settings.*') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <span>Settings</span>
    </a>
</div>
