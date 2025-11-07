<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.admin />
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Fee Schedule</h1>
                <p class="text-sm text-gray-600 mt-1">Set up a new fee schedule for students</p>
            </div>
            <a href="{{ route('admin.fee-schedules.index') }}" 
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Back to List
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.fee-schedules.update', $feeSchedule) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
                <!-- Basic Information -->
                <div class="border-b border-gray-200 pb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Basic Information</h2>
                    <p class="text-sm text-gray-600 mt-1">Enter the basic details of the fee schedule</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Fee Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $feeSchedule->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="e.g., Tuition Fee, Laboratory Fee"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Academic Year -->
                    <div>
                        <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-2">
                            Academic Year <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="academic_year" 
                               id="academic_year" 
                               value="{{ old('academic_year', $feeSchedule->academic_year) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="2024-2025"
                               required>
                        @error('academic_year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Semester -->
                    <div>
                        <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select name="semester" 
                                id="semester" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                required>
                            <option value="">Select Semester</option>
                            <option value="1st" {{ old('semester', $feeSchedule->semester) == '1st' ? 'selected' : '' }}>1st Semester</option>
                            <option value="2nd" {{ old('semester', $feeSchedule->semester) == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                            <option value="Summer" {{ old('semester', $feeSchedule->semester) == 'Summer' ? 'selected' : '' }}>Summer</option>
                        </select>
                        @error('semester')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Amount (₱) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="amount" 
                               id="amount" 
                               value="{{ old('amount', $feeSchedule->amount) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="0.00"
                               required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Due Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="due_date" 
                               id="due_date" 
                               value="{{ old('due_date', $feeSchedule->due_date->format('Y-m-d')) }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               required>
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Late Penalty -->
                    <div>
                        <label for="late_penalty" class="block text-sm font-medium text-gray-700 mb-2">
                            Late Penalty (₱)
                        </label>
                        <input type="number" 
                               name="late_penalty" 
                               id="late_penalty" 
                               value="{{ old('late_penalty', $feeSchedule->late_penalty) }}"
                               step="0.01"
                               min="0"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="0.00">
                        @error('late_penalty')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Allow Partial Payment -->
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   name="allow_partial" 
                                   id="allow_partial" 
                                   value="1"
                                   {{ old('allow_partial', $feeSchedule->allow_partial) ? 'checked' : '' }}
                                   class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                            <label for="allow_partial" class="ml-2 block text-sm text-gray-700">
                                Allow partial payments
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Target Filters -->
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-1">Target Students (Optional)</h2>
                    <p class="text-sm text-gray-600 mb-4">Leave blank to apply to all students</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Target Program -->
                        <div>
                            <label for="target_program" class="block text-sm font-medium text-gray-700 mb-2">
                                Program
                            </label>
                            <input type="text" 
                                   name="target_program" 
                                   id="target_program" 
                                   value="{{ old('target_program', $feeSchedule->target_program) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                   placeholder="e.g., BSCS, BSIT">
                            @error('target_program')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Year -->
                        <div>
                            <label for="target_year" class="block text-sm font-medium text-gray-700 mb-2">
                                Year Level
                            </label>
                            <select name="target_year" 
                                    id="target_year" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="">All Years</option>
                                <option value="1" {{ old('target_year', $feeSchedule->target_year) == '1' ? 'selected' : '' }}>1st Year</option>
                                <option value="2" {{ old('target_year', $feeSchedule->target_year) == '2' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3" {{ old('target_year', $feeSchedule->target_year) == '3' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4" {{ old('target_year', $feeSchedule->target_year) == '4' ? 'selected' : '' }}>4th Year</option>
                            </select>
                            @error('target_year')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Target Block -->
                        <div>
                            <label for="target_block_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Block
                            </label>
                            <select name="target_block_id" 
                                    id="target_block_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                                <option value="">All Blocks</option>
                                @foreach($blocks as $block)
                                <option value="{{ $block->id }}" {{ old('target_block_id', $feeSchedule->target_block_id) == $block->id ? 'selected' : '' }}>
                                    {{ $block->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('target_block_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="border-t border-gray-200 pt-6">
                    <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Instructions
                    </label>
                    <textarea name="instructions" 
                              id="instructions" 
                              rows="4" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                              placeholder="Enter any special instructions for students regarding this payment...">{{ old('instructions', $feeSchedule->instructions) }}</textarea>
                    @error('instructions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="border-t border-gray-200 pt-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" 
                            id="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                            required>
                        <option value="draft" {{ old('status', $feeSchedule->status) == 'draft' ? 'selected' : '' }}>Draft (Save without notifying)</option>
                        <option value="active" {{ old('status', $feeSchedule->status) == 'active' ? 'selected' : '' }}>Active (Notify students immediately)</option>
                        <option value="closed" {{ old('status', $feeSchedule->status) == 'closed' ? 'selected' : '' }}>Closed (Locked)</option>
                    </select>
                    <p class="mt-1 text-sm text-gray-500">
                        Selecting "Active" will immediately notify all target students and treasurers
                    </p>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.fee-schedules.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg transition-all duration-200 shadow-lg">
                    Update Fee Schedule
                </button>
            </div>
        </form>
    </div>
</x-sidebar-layout>
