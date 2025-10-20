<!-- Admin Navigation -->
<div class="space-y-1">
    <!-- Dashboard -->
    <a href="{{ route('admin.dashboard') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span>Dashboard</span>
    </a>

    <!-- Users Management (Combined Students & Users) -->
    <div x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Users</span>
            </div>
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" x-transition class="ml-11 mt-1 space-y-1">
            <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors">All Users</a>
            <a href="{{ route('admin.users.create') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors">Add New User</a>
        </div>
    </div>

    <!-- Payments Management -->
    <div x-data="{ open: {{ request()->routeIs('admin.payments.*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.payments.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Payments</span>
            </div>
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" x-transition class="ml-11 mt-1 space-y-1">
            <a href="{{ route('admin.payments.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors">All Payments</a>
            <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors">Pending</a>
            <a href="{{ route('admin.payments.index', ['status' => 'paid']) }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors">Completed</a>
        </div>
    </div>



    <!-- Reports -->
    <div x-data="{ open: {{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }} }">
        <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
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
            <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors">Dashboard</a>
            <a href="{{ route('admin.reports.summary') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors">Summary Report</a>
            <a href="{{ route('admin.reports.export-payments') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors">Export Payments</a>
            <a href="{{ route('admin.reports.export-students') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors">Export Students</a>
        </div>
    </div>

    <!-- Settings -->
    <a href="{{ route('admin.settings.index') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <span>Settings</span>
    </a>
</div>
