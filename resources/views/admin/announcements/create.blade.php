<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.admin />
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Create New Announcement</h1>
                <p class="mt-1 text-sm text-gray-600">Send notifications to targeted users</p>
            </div>
            <a href="{{ route('admin.announcements.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Announcements
            </a>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        This will send notifications to all targeted users. Make sure to review your message before posting.
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.announcements.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Title Field -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        required
                        value="{{ old('title') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('title') border-red-500 @enderror"
                        placeholder="Enter announcement title"
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message Field -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        Message <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="message" 
                        id="message" 
                        rows="5"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all resize-none @error('message') border-red-500 @enderror"
                        placeholder="Enter your announcement message..."
                    >{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Write a clear and concise message for your announcement.</p>
                </div>

                <!-- Target Role Field -->
                <div>
                    <label for="target_role" class="block text-sm font-medium text-gray-700 mb-2">
                        Target Role <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="target_role" 
                        id="target_role" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('target_role') border-red-500 @enderror"
                    >
                        <option value="all" {{ old('target_role') === 'all' ? 'selected' : '' }}>All Users</option>
                        <option value="student" {{ old('target_role') === 'student' ? 'selected' : '' }}>Students</option>
                        <option value="treasurer" {{ old('target_role') === 'treasurer' ? 'selected' : '' }}>Treasurers</option>
                        <option value="admin" {{ old('target_role') === 'admin' ? 'selected' : '' }}>Admins</option>
                    </select>
                    @error('target_role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Select which user role should receive this announcement.</p>
                </div>

                <!-- Target Block Field -->
                <div>
                    <label for="target_block_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Target Block <span class="text-gray-400">(Optional)</span>
                    </label>
                    <select 
                        name="target_block_id" 
                        id="target_block_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('target_block_id') border-red-500 @enderror"
                    >
                        <option value="">All Blocks</option>
                        @foreach($blocks as $block)
                            <option value="{{ $block->id }}" {{ old('target_block_id') == $block->id ? 'selected' : '' }}>
                                {{ $block->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('target_block_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Optionally limit this announcement to a specific block.</p>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.announcements.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </a>
                    <button 
                        type="submit"
                        class="inline-flex items-center px-6 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                        Post Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
