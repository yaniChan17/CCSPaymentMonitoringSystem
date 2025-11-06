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
                            @if(Auth::user()->student && Auth::user()->student->profile_photo)
                                <img src="{{ asset('storage/profile_photos/' . Auth::user()->student->profile_photo) }}" 
                                     alt="Profile Photo" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-primary-600 to-secondary-500 flex items-center justify-center text-white text-2xl font-bold border-4 border-gray-200">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
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

                        @if(Auth::user()->student && Auth::user()->student->profile_photo)
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

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    @php
                        $student = Auth::user()->student;
                        $isAdmin = Auth::user()->role === 'admin';
                        $isTreasurer = Auth::user()->role === 'treasurer';
                        $isStudent = Auth::user()->role === 'student';
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', Auth::user()->name) }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email (Read-only for non-admin) -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', Auth::user()->email) }}"
                                   {{ !$isAdmin ? 'readonly' : '' }}
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ !$isAdmin ? 'bg-gray-50 cursor-not-allowed' : '' }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Student/Employee ID -->
                        @if($student)
                            <div>
                                <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Student/Employee ID <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="student_id" 
                                       id="student_id" 
                                       value="{{ old('student_id', $student->student_id) }}"
                                       {{ !$isAdmin ? 'readonly' : '' }}
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ !$isAdmin ? 'bg-gray-50 cursor-not-allowed' : '' }}">
                                @error('student_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Course -->
                            <div>
                                <label for="course" class="block text-sm font-medium text-gray-700 mb-1">
                                    Course/Program
                                </label>
                                <input type="text" 
                                       name="course" 
                                       id="course" 
                                       value="{{ old('course', $student->course ?? 'BSIT') }}"
                                       readonly
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed">
                            </div>

                            <!-- Block (Readonly for Treasurer) -->
                            <div>
                                <label for="block" class="block text-sm font-medium text-gray-700 mb-1">
                                    Block
                                </label>
                                <input type="text" 
                                       name="block" 
                                       id="block" 
                                       value="{{ old('block', $student->block) }}"
                                       {{ ($isTreasurer || $isStudent) ? 'readonly' : '' }}
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ ($isTreasurer || $isStudent) ? 'bg-gray-50 cursor-not-allowed' : '' }}">
                                @if($isTreasurer || $isStudent)
                                    <p class="mt-1 text-xs text-gray-500">Block is assigned by admin</p>
                                @endif
                            </div>

                            <!-- Contact Number (Editable by all) -->
                            <div>
                                <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">
                                    Contact Number
                                </label>
                                <input type="text" 
                                       name="contact_number" 
                                       id="contact_number" 
                                       value="{{ old('contact_number', $student->contact_number) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('contact_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Guardian Name -->
                            <div>
                                <label for="guardian_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Guardian Name
                                </label>
                                <input type="text" 
                                       name="guardian_name" 
                                       id="guardian_name" 
                                       value="{{ old('guardian_name', $student->guardian_name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('guardian_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Guardian Contact -->
                            <div>
                                <label for="guardian_contact" class="block text-sm font-medium text-gray-700 mb-1">
                                    Guardian Contact
                                </label>
                                <input type="text" 
                                       name="guardian_contact" 
                                       id="guardian_contact" 
                                       value="{{ old('guardian_contact', $student->guardian_contact) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                @error('guardian_contact')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Complete Address
                                </label>
                                <textarea name="address" 
                                          id="address" 
                                          rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('address', $student->address) }}</textarea>
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

        <!-- Change Password -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Change Password</h2>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
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
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required
                                   minlength="8"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Minimum 8 characters required</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                Confirm New Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   required
                                   minlength="8"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-primary-600 to-secondary-500 hover:from-primary-700 hover:to-secondary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-sidebar-layout>
