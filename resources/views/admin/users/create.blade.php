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
                <h1 class="text-2xl font-bold text-gray-900">Create New User</h1>
                <p class="mt-1 text-sm text-gray-600">Add a new user to the system</p>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100">
            <form method="POST" action="{{ route('admin.users.store') }}" x-data="{ role: 'student' }">
                @csrf

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
                                       value="{{ old('name') }}"
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
                                       value="{{ old('email') }}"
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
                                    <option value="student" {{ old('role', 'student') === 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="treasurer" {{ old('role') === 'treasurer' ? 'selected' : '' }}>Treasurer</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('password') border-red-500 @enderror">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
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
                                       value="{{ old('student_id') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('student_id') border-red-500 @enderror">
                                @error('student_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Course (Fixed to BSIT for CCS) -->
                            <input type="hidden" name="course" value="BSIT">
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
                                    <option value="1" {{ old('year_level') == '1' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2" {{ old('year_level') == '2' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3" {{ old('year_level') == '3' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4" {{ old('year_level') == '4' ? 'selected' : '' }}>4th Year</option>
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
                                    <option value="1" {{ old('block') == '1' ? 'selected' : '' }}>Block 1</option>
                                    <option value="2" {{ old('block') == '2' ? 'selected' : '' }}>Block 2</option>
                                    <option value="3" {{ old('block') == '3' ? 'selected' : '' }}>Block 3</option>
                                    <option value="4" {{ old('block') == '4' ? 'selected' : '' }}>Block 4</option>
                                    <option value="5" {{ old('block') == '5' ? 'selected' : '' }}>Block 5</option>
                                </select>
                                @error('block')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information Fields (Admin/Treasurer Only) -->
                    <div x-show="role === 'admin' || role === 'treasurer'" 
                         x-transition
                         x-cloak 
                         class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Personal Information (Required)</h3>
                        <p class="text-sm text-gray-600 mb-4">Additional information for admin and treasurer accounts.</p>

                        <div class="space-y-4">
                            <!-- Course/Program -->
                            <div>
                                <label for="course" class="block text-sm font-medium text-gray-700 mb-1">
                                    Course/Program <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="course" 
                                       id="course" 
                                       value="{{ old('course') }}"
                                       placeholder="e.g., BSIT, BSCS"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('course') border-red-500 @enderror">
                                @error('course')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Year Level -->
                            <div>
                                <label for="admin_year_level" class="block text-sm font-medium text-gray-700 mb-1">
                                    Year Level <span class="text-red-500">*</span>
                                </label>
                                <select name="year_level" 
                                        id="admin_year_level"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('year_level') border-red-500 @enderror">
                                    <option value="">Select Year Level</option>
                                    <option value="1st Year" {{ old('year_level') === '1st Year' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2nd Year" {{ old('year_level') === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3rd Year" {{ old('year_level') === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4th Year" {{ old('year_level') === '4th Year' ? 'selected' : '' }}>4th Year</option>
                                </select>
                                @error('year_level')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Block -->
                            <div>
                                <label for="admin_block" class="block text-sm font-medium text-gray-700 mb-1">
                                    Block <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="block" 
                                       id="admin_block" 
                                       value="{{ old('block') }}"
                                       placeholder="e.g., Block 1, Block A"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('block') border-red-500 @enderror">
                                @error('block')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Number -->
                            <div>
                                <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">
                                    Contact Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="contact_number" 
                                       id="contact_number" 
                                       value="{{ old('contact_number') }}"
                                       placeholder="e.g., 09123456789"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('contact_number') border-red-500 @enderror">
                                @error('contact_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Father's Name -->
                            <div>
                                <label for="father_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Father's Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="father_name" 
                                       id="father_name" 
                                       value="{{ old('father_name') }}"
                                       placeholder="Enter father's full name"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('father_name') border-red-500 @enderror">
                                @error('father_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mother's Name -->
                            <div>
                                <label for="mother_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Mother's Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="mother_name" 
                                       id="mother_name" 
                                       value="{{ old('mother_name') }}"
                                       placeholder="Enter mother's full name"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('mother_name') border-red-500 @enderror">
                                @error('mother_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Complete Address -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Complete Address <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    name="address" 
                                    id="address" 
                                    rows="3"
                                    placeholder="Enter complete address"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
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
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
