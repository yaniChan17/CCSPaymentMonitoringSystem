<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.student />
    </x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Help Center</h1>
        <p class="text-sm text-gray-500 mt-1">Frequently asked questions and support</p>
    </x-slot>

    <div class="space-y-6">
        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('student.dashboard') }}" class="block bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Dashboard</h3>
                        <p class="text-sm text-gray-500">View your balance</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('profile.edit') }}" class="block bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Profile Settings</h3>
                        <p class="text-sm text-gray-500">Update your info</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('student.help.contact') }}" class="block bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Contact Support</h3>
                        <p class="text-sm text-gray-500">Get help</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- FAQ Sections -->
        @foreach($faqs as $section)
            <div class="bg-white shadow-md rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900">{{ $section['category'] }}</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($section['questions'] as $index => $faq)
                        <div x-data="{ open: false }" class="p-6">
                            <button @click="open = !open" class="w-full flex items-center justify-between text-left">
                                <h3 class="text-lg font-semibold text-gray-900 flex-1">{{ $faq['question'] }}</h3>
                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200 flex-shrink-0 ml-4" 
                                     :class="{ 'rotate-180': open }" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="mt-4 text-gray-600 leading-relaxed">
                                {{ $faq['answer'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Still Need Help -->
        <div class="bg-gradient-to-r from-primary-500 to-accent-600 rounded-xl p-8 text-white text-center">
            <h2 class="text-2xl font-bold mb-2">Still need help?</h2>
            <p class="mb-6 text-pink-100">Can't find the answer you're looking for? Contact our support team.</p>
            <a href="{{ route('student.help.contact') }}" 
               class="inline-flex items-center px-6 py-3 bg-white text-primary-600 rounded-lg font-semibold hover:bg-pink-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Contact Support
            </a>
        </div>
    </div>
</x-sidebar-layout>
