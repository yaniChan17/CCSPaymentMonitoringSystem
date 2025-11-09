<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.admin />
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create Fee Schedule</h1>
                <p class="text-sm text-gray-600 mt-1">Set up a new fee collection period</p>
            </div>
            <a href="{{ route('admin.fee-schedules.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Back to List
            </a>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('admin.fee-schedules.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Fee Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Academic Year & Semester -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="academic_year" class="block text-sm font-medium text-gray-700">Academic Year *</label>
                        <input type="text" name="academic_year" id="academic_year" value="{{ old('academic_year', '2024-2025') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="e.g., 2024-2025">
                        @error('academic_year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700">Semester *</label>
                        <select name="semester" id="semester" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="1st" {{ old('semester') === '1st' ? 'selected' : '' }}>1st Semester</option>
                            <option value="2nd" {{ old('semester') === '2nd' ? 'selected' : '' }}>2nd Semester</option>
                            <option value="Summer" {{ old('semester') === 'Summer' ? 'selected' : '' }}>Summer</option>
                        </select>
                        @error('semester')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Amount & Due Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount (₱) *</label>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}" required step="0.01" min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date *</label>
                        <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Late Penalty & Allow Partial -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="late_penalty" class="block text-sm font-medium text-gray-700">Late Penalty (₱)</label>
                        <input type="number" name="late_penalty" id="late_penalty" value="{{ old('late_penalty', 0) }}" step="0.01" min="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        @error('late_penalty')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-end">
                        <label class="flex items-center">
                            <input type="checkbox" name="allow_partial" value="1" {{ old('allow_partial', true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <span class="ml-2 text-sm text-gray-700">Allow Partial Payments</span>
                        </label>
                    </div>
                </div>

                <!-- Target Options (Optional) -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Target Options (Optional)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="target_program" class="block text-sm font-medium text-gray-700">Program</label>
                            <input type="text" name="target_program" id="target_program" value="{{ old('target_program') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                placeholder="e.g., BSCS">
                        </div>

                        <div>
                            <label for="target_year" class="block text-sm font-medium text-gray-700">Year Level</label>
                            <select name="target_year" id="target_year"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">All Years</option>
                                <option value="1" {{ old('target_year') == 1 ? 'selected' : '' }}>1st Year</option>
                                <option value="2" {{ old('target_year') == 2 ? 'selected' : '' }}>2nd Year</option>
                                <option value="3" {{ old('target_year') == 3 ? 'selected' : '' }}>3rd Year</option>
                                <option value="4" {{ old('target_year') == 4 ? 'selected' : '' }}>4th Year</option>
                            </select>
                        </div>

                        <div>
                            <label for="target_block_id" class="block text-sm font-medium text-gray-700">Block</label>
                            <select name="target_block_id" id="target_block_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <option value="">All Blocks</option>
                                @foreach ($blocks as $block)
                                    <option value="{{ $block->id }}" {{ old('target_block_id') == $block->id ? 'selected' : '' }}>
                                        {{ $block->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div>
                    <label for="instructions" class="block text-sm font-medium text-gray-700">Payment Instructions</label>
                    <textarea name="instructions" id="instructions" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        placeholder="Enter payment instructions for students...">{{ old('instructions') }}</textarea>
                    @error('instructions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                    <select name="status" id="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Don't send notifications yet)</option>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active (Send notifications immediately)</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.fee-schedules.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-br from-primary-600 to-secondary-500 hover:from-primary-700 hover:to-secondary-600 text-white rounded-md font-semibold">
                        Create Fee Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
