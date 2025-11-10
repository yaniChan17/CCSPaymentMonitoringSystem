<!-- Student Navigation -->
<div class="space-y-1">
    <!-- Dashboard -->
    <a href="{{ route('student.dashboard') }}" 
       class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span>Dashboard</span>
    </a>

    <!-- My Balance -->
    <a href="{{ route('student.dashboard') }}#balance" class="flex items-center space-x-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>My Balance</span>
    </a>

    <!-- Payment History -->
    <a href="{{ route('student.dashboard') }}#history" class="flex items-center space-x-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        <span>Payment History</span>
    </a>

    <!-- Receipts -->
    <a href="{{ route('student.dashboard') }}#receipts" class="flex items-center space-x-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span>Receipts</span>
    </a>

    <!-- Payment Schedule -->
    <a href="{{ route('student.dashboard') }}#schedule" class="flex items-center space-x-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-200">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span>Payment Schedule</span>
    </a>

    <div class="pt-4 mt-4 border-t border-gray-200">
        <p class="px-4 text-xs font-semibold text-gray-500 uppercase mb-2">Support</p>
        
        <!-- Help Center -->
        <a href="{{ route('student.help.index') }}" 
           class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.help.index') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Help Center</span>
        </a>

        <!-- Contact Support -->
        <a href="{{ route('student.help.contact') }}" 
           class="flex items-center space-x-3 px-4 py-3 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('student.help.contact') ? 'bg-red-50 text-primary-600' : 'text-gray-700 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span>Contact Support</span>
        </a>
    </div>
</div>
