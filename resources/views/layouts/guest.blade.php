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
            
            .auth-pattern {
                background: linear-gradient(135deg, #D72638 0%, #FFCB05 100%);
            }
            
            .glass-effect {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
            
            .input-focus {
                transition: all 0.3s ease;
            }
            
            .input-focus:focus {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(215, 38, 56, 0.15);
            }
            
            .btn-gradient {
                background: linear-gradient(135deg, #D72638 0%, #FFCB05 100%);
                transition: all 0.3s ease;
            }
            
            .btn-gradient:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(215, 38, 56, 0.3);
            }
            
            .floating-shape {
                position: absolute;
                border-radius: 50%;
                opacity: 0.1;
                animation: float 20s infinite ease-in-out;
            }
            
            @keyframes float {
                0%, 100% { transform: translate(0, 0) rotate(0deg); }
                33% { transform: translate(30px, -30px) rotate(120deg); }
                66% { transform: translate(-20px, 20px) rotate(240deg); }
            }
            
            .logo-watermark {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0.15;
                width: 800px;
                height: 800px;
                pointer-events: none;
                object-fit: contain;
            }
            
            /* Custom Scrollbar Styling */
            .custom-scrollbar::-webkit-scrollbar {
                width: 8px;
            }
            
            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }
            
            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #D72638 0%, #FFCB05 100%);
                border-radius: 10px;
            }
            
            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #FFCB05 0%, #D72638 100%);
            }
            
            /* Firefox */
            .custom-scrollbar {
                scrollbar-width: thin;
                scrollbar-color: #D72638 #f1f1f1;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen h-screen flex overflow-hidden">
            <!-- Left Side - Form -->
            <div class="w-full lg:w-1/2 flex flex-col bg-white relative overflow-hidden">
                <!-- Floating Shapes (Fixed) -->
                <div class="floating-shape w-64 h-64 bg-primary-600 top-10 -left-20" style="animation-delay: 0s;"></div>
                <div class="floating-shape w-48 h-48 bg-secondary-500 bottom-10 -right-10" style="animation-delay: 7s;"></div>
                <div class="floating-shape w-32 h-32 bg-primary-400 top-1/2 left-1/3" style="animation-delay: 14s;"></div>
                
                <!-- Scrollable Form Container -->
                <div class="flex-1 overflow-y-auto py-8 px-8 custom-scrollbar">
                    <div class="w-full max-w-md mx-auto relative z-10">
                        <!-- Logo and Back to Home -->
                        <div class="mb-8">
                            <a href="/" class="inline-flex items-center text-gray-600 hover:text-primary-600 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                <span class="text-sm font-medium">Back to Home</span>
                            </a>
                            
                            <div class="mt-6 flex items-center">
                                @if(file_exists(public_path('images/ccs-logo.png')) || file_exists(public_path('images/ccs-logo.jpg')))
                                    <img src="{{ asset(file_exists(public_path('images/ccs-logo.png')) ? 'images/ccs-logo.png' : 'images/ccs-logo.jpg') }}" 
                                         alt="CCS Logo" 
                                         class="w-16 h-16 object-contain rounded-full">
                                @else
                                    <div class="w-16 h-16 bg-gradient-to-br from-primary-600 to-secondary-500 rounded-full flex items-center justify-center">
                                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <h1 class="text-2xl font-bold text-gray-900">CCS Payment</h1>
                                    <p class="text-sm text-gray-500">Management System</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Content -->
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Right Side - Gradient Background with Faded Logo (Hidden on Mobile, Fixed on Desktop) -->
            <div class="hidden lg:flex lg:w-1/2 auth-pattern items-center justify-center p-12 relative overflow-hidden">
                <!-- Faded Logo Watermark -->
                @if(file_exists(public_path('images/ccs-logo.png')) || file_exists(public_path('images/ccs-logo.jpg')))
                    <img src="{{ asset(file_exists(public_path('images/ccs-logo.png')) ? 'images/ccs-logo.png' : 'images/ccs-logo.jpg') }}" 
                         alt="CCS Logo" 
                         class="logo-watermark">
                @endif
                
                <!-- Decorative Elements -->
                <div class="absolute top-20 right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-20 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 text-white max-w-lg">
                    <h2 class="text-4xl font-bold mb-6">Welcome to CCS Payment Management System</h2>
                    <p class="text-lg text-white/90 mb-8">
                        Manage student payments efficiently with our comprehensive monitoring system. 
                        Track payments, generate reports, and streamline your financial operations.
                    </p>
                    
                    <!-- Feature List -->
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center mt-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-white">Real-time Payment Tracking</h3>
                                <p class="text-sm text-white/80">Monitor all transactions as they happen</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center mt-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-white">Secure & Reliable</h3>
                                <p class="text-sm text-white/80">Bank-level security for your data</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center mt-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-white">Comprehensive Reports</h3>
                                <p class="text-sm text-white/80">Generate detailed analytics and insights</p>
                            </div>
                        </div>
                    </div>
                    
                   
                </div>
            </div>
        </div>
    </body>
</html>
