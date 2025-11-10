<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.admin />
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
            <p class="mt-1 text-sm text-gray-600">Manage your personal information and account settings</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Profile Information -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Personal Information</h2>
                <p class="text-sm text-gray-500 mt-1">Update your personal details</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    <!-- Profile Picture -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                        <div class="flex items-center space-x-4">
                            @if(auth()->user()->photo)
                                <img src="{{ Storage::url(auth()->user()->photo) }}" alt="Profile" class="w-20 h-20 rounded-full object-cover">
                            @else
                                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-2xl font-bold text-primary-600">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                </div>
                            @endif
                            <input type="file" name="photo" accept="image/*" class="text-sm">
                        </div>
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student ID / Employee ID -->
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Employee ID
                        </label>
                        <input type="text" name="student_id" id="student_id" value="{{ old('student_id', auth()->user()->student_id) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 @error('student_id') border-red-500 @enderror">
                        @error('student_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Course/Program -->
                    <div>
                        <label for="course" class="block text-sm font-medium text-gray-700 mb-1">
                            Course/Program
                        </label>
                        <input type="text" name="course" id="course" value="{{ old('course', auth()->user()->course) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 @error('course') border-red-500 @enderror">
                        @error('course')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year Level -->
                    <div>
                        <label for="year_level" class="block text-sm font-medium text-gray-700 mb-1">
                            Year Level
                        </label>
                        <select name="year_level" id="year_level"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 @error('year_level') border-red-500 @enderror">
                            <option value="">Select Year Level</option>
                            <option value="1st Year" {{ old('year_level', auth()->user()->year_level) === '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('year_level', auth()->user()->year_level) === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('year_level', auth()->user()->year_level) === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th Year" {{ old('year_level', auth()->user()->year_level) === '4th Year' ? 'selected' : '' }}>4th Year</option>
                        </select>
                        @error('year_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Block -->
                    <div>
                        <label for="block" class="block text-sm font-medium text-gray-700 mb-1">
                            Block
                        </label>
                        <input type="text" name="block" id="block" value="{{ old('block', auth()->user()->block) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 @error('block') border-red-500 @enderror">
                        @error('block')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Number -->
                    <div>
                        <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">
                            Contact Number
                        </label>
                        <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number', auth()->user()->contact_number) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 @error('contact_number') border-red-500 @enderror">
                        @error('contact_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Father's Full Name -->
                    <div>
                        <label for="father_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Father's Full Name
                        </label>
                        <input type="text" name="father_name" id="father_name" value="{{ old('father_name', auth()->user()->father_name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 @error('father_name') border-red-500 @enderror">
                        @error('father_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mother's Full Name -->
                    <div>
                        <label for="mother_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Mother's Full Name
                        </label>
                        <input type="text" name="mother_name" id="mother_name" value="{{ old('mother_name', auth()->user()->mother_name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 @error('mother_name') border-red-500 @enderror">
                        @error('mother_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Complete Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                            Complete Address
                        </label>
                        <textarea name="address" id="address" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 @error('address') border-red-500 @enderror">{{ old('address', auth()->user()->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Section -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Change Password</h2>
                <p class="text-sm text-gray-500 mt-1">Update your password to keep your account secure</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" class="p-6">
                @csrf
                @method('PATCH')

                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" id="current_password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password" id="password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
