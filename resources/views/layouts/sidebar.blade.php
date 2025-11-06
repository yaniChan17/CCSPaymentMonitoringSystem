<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false }" x-init="sidebarOpen = window.innerWidth >= 1024">
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
            
            .sidebar-transition {
                transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
            }
            
            .hover-lift {
                transition: all 0.2s ease;
            }
            
            .hover-lift:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }
            
            .stat-card {
                position: relative;
                overflow: hidden;
            }
            
            .stat-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.1) 100%);
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            
            .stat-card:hover::before {
                opacity: 1;
            }
            
            /* Custom Scrollbar */
            .custom-scrollbar::-webkit-scrollbar {
                width: 8px;
            }
            
            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f1f5f9;
            }
            
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #D72638 0%, #FFCB05 100%);
                border-radius: 4px;
            }
            
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #FFCB05 0%, #D72638 100%);
            }
            
            /* Smooth scroll behavior */
            .custom-scrollbar {
                scroll-behavior: smooth;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <!-- Flash Messages -->
        <x-flash-message />
        
        <div class="h-screen flex overflow-hidden">
            <!-- Sidebar -->
            <aside 
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="fixed lg:relative inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 sidebar-transition flex flex-col h-screen"
                @click.away="if (window.innerWidth < 1024) sidebarOpen = false"
            >
                <!-- Sidebar Header (Fixed) -->
                <div class="gradient-bg p-6 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            @if(file_exists(public_path('images/ccs-logo.png')) || file_exists(public_path('images/ccs-logo.jpg')))
                                <img src="{{ asset(file_exists(public_path('images/ccs-logo.png')) ? 'images/ccs-logo.png' : 'images/ccs-logo.jpg') }}" 
                                     alt="CCS Logo" 
                                     class="w-10 h-10 object-contain rounded-full bg-white p-1 border border-white/20">
                            @else
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center border border-white/20">
                                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="text-white">
                                <h1 class="text-lg font-bold">CCS Payment</h1>
                                <p class="text-xs text-white/90">Management System</p>
                            </div>
                        </div>
                        <button 
                            @click="sidebarOpen = false" 
                            class="lg:hidden text-white hover:bg-white/10 p-2 rounded-lg transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- User Profile Section (Fixed) -->
                <div class="p-4 border-b border-gray-200 flex-shrink-0">
                    <div class="flex items-center space-x-3">
                        @if(Auth::user()->student && Auth::user()->student->profile_photo)
                            <img src="{{ asset('storage/profile_photos/' . Auth::user()->student->profile_photo) }}" 
                                 alt="Profile Photo" 
                                 class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary-600 to-secondary-500 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-secondary-100 text-gray-800 mt-1">
                                {{ ucfirst(Auth::user()->role ?? 'user') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links (Scrollable) -->
                <nav class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-1">
                    {{ $navigation ?? '' }}
                </nav>

                <!-- Sidebar Footer (Fixed) -->
                <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-gray-50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button 
                            type="submit"
                            class="w-full flex items-center space-x-3 px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Overlay for mobile -->
            <div 
                x-show="sidebarOpen && window.innerWidth < 1024"
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-gray-900 bg-opacity-50 lg:hidden z-40"
            ></div>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
                <!-- Top Navigation Bar (Fixed) -->
                <header class="bg-white border-b border-gray-200 flex-shrink-0 z-30">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center justify-between h-16">
                            <!-- Left side -->
                            <div class="flex items-center space-x-4">
                                <!-- Mobile menu button -->
                                <button 
                                    @click="sidebarOpen = !sidebarOpen"
                                    class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                    </svg>
                                </button>

                                <!-- Page Title -->
                                <div>
                                    @isset($header)
                                        {{ $header }}
                                    @else
                                        <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
                                    @endisset
                                </div>
                            </div>

                            <!-- Right side -->
                            <div class="flex items-center space-x-4">
                                <!-- Notifications -->
                                <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                                </button>

                                <!-- User Menu (Desktop) -->
                                <div class="hidden sm:block">
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

                                            <x-dropdown-link href="#">
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
                    </div>
                </header>

                <!-- Page Content (Scrollable) -->
                <main class="flex-1 overflow-y-auto custom-scrollbar bg-gray-50">
                    <div class="px-4 sm:px-6 lg:px-8 py-8">
                        {{ $slot }}
                    </div>
                    
                    <!-- Footer -->
                    <footer class="bg-white border-t border-gray-200 px-4 sm:px-6 lg:px-8 py-4 mt-auto">
                        <div class="flex flex-col sm:flex-row justify-between items-center text-sm text-gray-500">
                            <p>&copy; {{ date('Y') }} CCS Payment Monitoring System. All rights reserved.</p>
                            <div class="flex space-x-4 mt-2 sm:mt-0">
                                <a href="#" class="hover:text-indigo-600 transition-colors">Privacy Policy</a>
                                <a href="#" class="hover:text-indigo-600 transition-colors">Terms of Service</a>
                                <a href="#" class="hover:text-indigo-600 transition-colors">Support</a>
                            </div>
                        </div>
                    </footer>
                </main>
            </div>
        </div>
    </body>
</html>
