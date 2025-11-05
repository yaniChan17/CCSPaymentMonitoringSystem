<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.admin />
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.users.index') }}" 
               class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
                <p class="mt-1 text-sm text-gray-600">Update user information</p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" x-data="{ role: '{{ old('role', $user->role) }}' }">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $user->name) }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email', $user->email) }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                    User Role <span class="text-red-500">*</span>
                                </label>
                                <select name="role" 
                                        id="role" 
                                        x-model="role"
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('role') border-red-500 @enderror">
                                    <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="treasurer" {{ old('role', $user->role) === 'treasurer' ? 'selected' : '' }}>Treasurer</option>
                                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                @if($user->role === 'student' && $user->student && $user->student->payments()->count() > 0)
                                    <p class="mt-1 text-xs text-amber-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        Warning: This student has existing payments. Consider archiving instead of changing role.
                                    </p>
                                @endif
                            </div>

                            <!-- Password (Optional) -->
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Change Password (Optional)</h4>
                                <p class="text-xs text-gray-500 mb-3">Leave blank to keep current password</p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                            New Password
                                        </label>
                                        <input type="password" 
                                               name="password" 
                                               id="password" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('password') border-red-500 @enderror">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                            Confirm New Password
                                        </label>
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password_confirmation" 
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Student Information (Conditional) -->
                    <div x-show="role === 'student'" 
                         x-transition
                         class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
                        
                        <div class="space-y-4">
                            <!-- Student ID -->
                            <div>
                                <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Student ID <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="student_id" 
                                       id="student_id" 
                                       value="{{ old('student_id', $user->student->student_id ?? '') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('student_id') border-red-500 @enderror">
                                @error('student_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Course (Hidden, defaults to BSIT for CCS) -->
                            <input type="hidden" name="course" value="{{ old('course', $user->student->course ?? 'BSIT') }}">
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm text-blue-800">
                                    <strong>Course:</strong> BSIT (Bachelor of Science in Information Technology)
                                    <br><span class="text-xs">All CCS students are enrolled in the BSIT program</span>
                                </p>
                            </div>

                            <!-- Year Level -->
                            <div>
                                <label for="year_level" class="block text-sm font-medium text-gray-700 mb-1">
                                    Year Level <span class="text-red-500">*</span>
                                </label>
                                <select name="year_level" 
                                        id="year_level"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('year_level') border-red-500 @enderror">
                                    <option value="">Select Year Level</option>
                                    <option value="1" {{ old('year_level', $user->student->year_level ?? '') == '1' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2" {{ old('year_level', $user->student->year_level ?? '') == '2' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3" {{ old('year_level', $user->student->year_level ?? '') == '3' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4" {{ old('year_level', $user->student->year_level ?? '') == '4' ? 'selected' : '' }}>4th Year</option>
                                </select>
                                @error('year_level')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Block Number -->
                            <div>
                                <label for="block" class="block text-sm font-medium text-gray-700 mb-1">
                                    Block Number <span class="text-red-500">*</span>
                                </label>
                                <select name="block" 
                                        id="block"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('block') border-red-500 @enderror">
                                    <option value="">Select Block</option>
                                    <option value="1" {{ old('block', $user->student->block ?? '') == '1' ? 'selected' : '' }}>Block 1</option>
                                    <option value="2" {{ old('block', $user->student->block ?? '') == '2' ? 'selected' : '' }}>Block 2</option>
                                    <option value="3" {{ old('block', $user->student->block ?? '') == '3' ? 'selected' : '' }}>Block 3</option>
                                    <option value="4" {{ old('block', $user->student->block ?? '') == '4' ? 'selected' : '' }}>Block 4</option>
                                    <option value="5" {{ old('block', $user->student->block ?? '') == '5' ? 'selected' : '' }}>Block 5</option>
                                </select>
                                @error('block')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if($user->student)
                                <!-- Student Status -->
                                <div>
                                    <label for="student_status" class="block text-sm font-medium text-gray-700 mb-1">
                                        Student Status
                                    </label>
                                    <select name="student_status" 
                                            id="student_status"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                        <option value="active" {{ old('student_status', $user->student->status) === 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('student_status', $user->student->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="graduated" {{ old('student_status', $user->student->status) === 'graduated' ? 'selected' : '' }}>Graduated</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3 rounded-b-xl">
                    <a href="{{ route('admin.users.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-sm">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
