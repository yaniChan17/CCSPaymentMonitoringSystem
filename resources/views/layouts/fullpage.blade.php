<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            
            .gradient-bg {
                background: linear-gradient(135deg, #D72638 0%, #FFCB05 100%);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Flash Messages -->
        <x-flash-message />
        
        <div class="min-h-screen flex flex-col">
            <!-- Top Navigation Bar -->
            <header class="bg-white border-b border-gray-200 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        <!-- Left side - Logo and Title -->
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-3">
                                @if(file_exists(public_path('images/ccs-logo.png')) || file_exists(public_path('images/ccs-logo.jpg')))
                                    <img src="{{ asset(file_exists(public_path('images/ccs-logo.png')) ? 'images/ccs-logo.png' : 'images/ccs-logo.jpg') }}" 
                                         alt="CCS Logo" 
                                         class="w-10 h-10 object-contain rounded-full bg-gradient-to-br from-primary-600 to-secondary-500 p-1 border border-gray-200">
                                @else
                                    <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-secondary-500 rounded-full flex items-center justify-center border border-gray-200">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h1 class="text-lg font-bold text-gray-900">CCS Payment</h1>
                                    <p class="text-xs text-gray-600">Management System</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right side - User Menu -->
                        <div class="flex items-center space-x-4">
                            <!-- Notification Bell -->
                            <x-notification-bell :unreadCount="auth()->user()->notifications()->unread()->count()" />

                            <!-- User Menu -->
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                        @if(Auth::user()->student && Auth::user()->student->profile_photo)
                                            <img src="{{ asset('storage/profile_photos/' . Auth::user()->student->profile_photo) }}" 
                                                 alt="Profile Photo" 
                                                 class="w-8 h-8 rounded-full object-cover border-2 border-gray-200">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-600 to-secondary-500 flex items-center justify-center text-white text-xs font-semibold">
                                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <span class="hidden sm:block">{{ Auth::user()->name }}</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    
                                    <x-dropdown-link :href="route('profile.edit')">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span>{{ __('Profile') }}</span>
                                        </div>
                                    </x-dropdown-link>

                                    <x-dropdown-link :href="route('settings.edit')">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span>{{ __('Settings') }}</span>
                                        </div>
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                            <div class="flex items-center space-x-2 text-red-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                <span>{{ __('Logout') }}</span>
                                            </div>
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    {{ $slot }}
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 mt-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col sm:flex-row justify-between items-center text-sm text-gray-500">
                        <p>&copy; {{ date('Y') }} CCS Payment Monitoring System. All rights reserved.</p>
                        <div class="flex space-x-4 mt-2 sm:mt-0">
                            <a href="#" class="hover:text-indigo-600 transition-colors">Privacy Policy</a>
                            <a href="#" class="hover:text-indigo-600 transition-colors">Terms of Service</a>
                            <a href="#" class="hover:text-indigo-600 transition-colors">Support</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
