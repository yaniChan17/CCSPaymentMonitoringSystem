<x-sidebar-layout>
    <x-slot name="navigation">
        @if(Auth::user()->role === 'admin')
            <x-nav.admin />
        @elseif(Auth::user()->role === 'treasurer')
            <x-nav.treasurer />
        @else
            <x-nav.student />
        @endif
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
            <p class="mt-2 text-sm text-gray-600">Manage your account information and settings</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @php
            $user = Auth::user();
            $isAdmin = $user->role === 'admin';
            $isTreasurer = $user->role === 'treasurer';
            $isStudent = $user->role === 'student';
            $student = $user->student;
        @endphp

        <!-- Profile Photo Section -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Profile Photo</h2>
                
                <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="flex items-center space-x-6">
                        <!-- Current Photo -->
                        <div class="flex-shrink-0">
                            @if($student && $student->profile_photo)
                                <img src="{{ asset('storage/profile_photos/' . $student->profile_photo) }}" 
                                     alt="Profile Photo" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                            @elseif($user->photo)
                                <img src="{{ Storage::url($user->photo) }}" 
                                     alt="Profile Photo" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary-600 to-secondary-500 flex items-center justify-center text-white text-2xl font-bold border-4 border-gray-200">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Upload Controls -->
                        <div class="flex-1">
                            <input type="file" 
                                   name="profile_photo" 
                                   id="profile_photo"
                                   accept="image/*"
                                   class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-primary-50 file:text-primary-700
                                          hover:file:bg-primary-100
                                          cursor-pointer">
                            <p class="mt-2 text-xs text-gray-500">
                                JPG, PNG or GIF (MAX. 2MB). Recommended: 400x400px
                            </p>
                            @error('profile_photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 pt-2">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-600 to-secondary-500 hover:from-primary-700 hover:to-secondary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Upload Photo
                        </button>

                        @if(($student && $student->profile_photo) || $user->photo)
                            <a href="{{ route('profile.photo.delete') }}" 
                               onclick="return confirm('Are you sure you want to remove your profile photo?')"
                               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Remove
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $user->name) }}"
                                   {{ $isStudent ? 'readonly' : '' }}
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $isStudent ? 'bg-gray-50 cursor-not-allowed' : '' }}">
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
                                   {{ $isStudent ? 'readonly' : '' }}
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $isStudent ? 'bg-gray-50 cursor-not-allowed' : '' }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Student ID -->
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Student ID
                            </label>
                            <input type="text" 
                                   name="student_id" 
                                   id="student_id" 
                                   value="{{ old('student_id', $isStudent && $student ? $student->student_id : $user->student_id) }}"
                                   readonly
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed">
                        </div>

                        <!-- Course/Program -->
                        <div>
                            <label for="course" class="block text-sm font-medium text-gray-700 mb-1">
                                Course/Program
                            </label>
                            <input type="text" 
                                   name="course" 
                                   id="course" 
                                   value="{{ old('course', $isStudent && $student ? $student->course : $user->course) }}"
                                   {{ $isStudent ? 'readonly' : '' }}
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $isStudent ? 'bg-gray-50 cursor-not-allowed' : '' }}">
                            @error('course')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Year Level -->
                        <div>
                            <label for="year_level" class="block text-sm font-medium text-gray-700 mb-1">
                                Year Level
                            </label>
                            @if($isStudent)
                                <input type="text" 
                                       name="year_level" 
                                       id="year_level" 
                                       value="{{ old('year_level', $student ? $student->year_level : $user->year_level) }}"
                                       readonly
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed">
                            @else
                                <select name="year_level" 
                                        id="year_level"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                    <option value="">Select Year Level</option>
                                    <option value="1st Year" {{ old('year_level', $user->year_level) === '1st Year' ? 'selected' : '' }}>1st Year</option>
                                    <option value="2nd Year" {{ old('year_level', $user->year_level) === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3rd Year" {{ old('year_level', $user->year_level) === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4th Year" {{ old('year_level', $user->year_level) === '4th Year' ? 'selected' : '' }}>4th Year</option>
                                </select>
                            @endif
                            @error('year_level')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Block -->
                        <div>
                            <label for="block" class="block text-sm font-medium text-gray-700 mb-1">
                                Block
                            </label>
                            <input type="text" 
                                   name="block" 
                                   id="block" 
                                   value="{{ old('block', $isStudent && $student ? $student->block : $user->block) }}"
                                   {{ $isStudent ? 'readonly' : '' }}
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $isStudent ? 'bg-gray-50 cursor-not-allowed' : '' }}">
                            @error('block')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Number (Editable for all) -->
                        <div>
                            <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">
                                Contact Number
                            </label>
                            <input type="text" 
                                   name="contact_number" 
                                   id="contact_number" 
                                   value="{{ old('contact_number', $isStudent && $student ? $student->contact_number : $user->contact_number) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            @error('contact_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Father's Name (Admin/Treasurer Only) -->
                        @if($isAdmin || $isTreasurer)
                            <div>
                                <label for="father_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Father's Full Name
                                </label>
                                <input type="text" 
                                       name="father_name" 
                                       id="father_name" 
                                       value="{{ old('father_name', $user->father_name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('father_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mother's Name (Admin/Treasurer Only) -->
                            <div>
                                <label for="mother_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Mother's Full Name
                                </label>
                                <input type="text" 
                                       name="mother_name" 
                                       id="mother_name" 
                                       value="{{ old('mother_name', $user->mother_name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('mother_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Complete Address (Admin/Treasurer Only) -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Complete Address
                                </label>
                                <textarea name="address" 
                                          id="address" 
                                          rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-primary-600 to-secondary-500 hover:from-primary-700 hover:to-secondary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Changes
                        </button>
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Settings Link -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Account Settings</h2>
                        <p class="mt-1 text-sm text-gray-600">Manage your password and account security</p>
                    </div>
                    <a href="{{ route('settings.edit') }}" 
                       class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-primary-600 to-secondary-500 hover:from-primary-700 hover:to-secondary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Go to Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
