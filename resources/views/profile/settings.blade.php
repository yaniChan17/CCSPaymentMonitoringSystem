<x-layouts.fullpage>
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header with Back Button -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Account Settings</h1>
                <p class="mt-2 text-sm text-gray-600">Manage your account security and preferences</p>
            </div>
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Change Password Section -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Change Password</h2>
                <p class="mt-1 text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
            </div>

            <form method="POST" action="{{ route('settings.password.update') }}" class="p-6 space-y-6">
                @csrf
                @method('PATCH')

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Current Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="current_password" 
                           id="current_password"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           id="password"
                           required
                           minlength="8"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters long.</p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           id="password_confirmation"
                           required
                           minlength="8"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-primary-600 to-secondary-500 hover:from-primary-700 hover:to-secondary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        <!-- Delete Account Section -->
        <div class="bg-white shadow-md rounded-xl border border-red-200 overflow-hidden">
            <div class="p-6 border-b border-red-100 bg-red-50">
                <h2 class="text-lg font-semibold text-red-900">Delete Account</h2>
                <p class="mt-1 text-sm text-red-700">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
            </div>

            <form method="POST" action="{{ route('settings.account.destroy') }}" class="p-6 space-y-6" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                @csrf
                @method('DELETE')

                <!-- Confirm Password -->
                <div>
                    <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Your Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           id="delete_password"
                           required
                           placeholder="Enter your password to confirm"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    @error('password', 'userDeletion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Delete Button -->
                <div class="flex justify-end">
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Account
                    </button>
                </div>
            </form>
        </div>

        <!-- Back to Profile Link -->
        <div class="text-center">
            <a href="{{ route('profile.edit') }}" 
               class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Profile
            </a>
        </div>
    </div>
</x-layouts.fullpage>
