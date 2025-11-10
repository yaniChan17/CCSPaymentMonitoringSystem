<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.student />
    </x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Contact Support</h1>
        <p class="text-sm text-gray-500 mt-1">Get in touch with us for assistance</p>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Contact Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Block Treasurer -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">Your Block Treasurer</h3>
                        <p class="text-sm text-gray-500">For payment-related inquiries</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">Contact your block treasurer for any questions about payments, deadlines, or receipt requests.</p>
                <a href="{{ route('student.dashboard') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium text-sm">
                    View Contact Info
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <!-- System Admin -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">System Administrator</h3>
                        <p class="text-sm text-gray-500">For technical issues</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-4">For account issues, password resets, or system errors, contact the system administrator.</p>
                <div class="text-sm text-gray-600">
                    <p class="flex items-center mb-2">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        admin@ccs.edu.ph
                    </p>
                </div>
            </div>
        </div>

        <!-- Help Resources -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Common Issues & Solutions</h2>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">Payment Not Showing?</h4>
                        <p class="text-sm text-gray-600 mt-1">If you made a payment but it's not reflected in your account, please contact your block treasurer with your payment details and receipt.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">Cannot Login?</h4>
                        <p class="text-sm text-gray-600 mt-1">Use the "Forgot Password?" link on the login page. If the issue persists, contact the system administrator.</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-900">Need a Receipt?</h4>
                        <p class="text-sm text-gray-600 mt-1">You can download receipts directly from your Payment History on the dashboard. Click the "Receipt" button next to any payment.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Link -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 border border-blue-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Check Our FAQ First</h3>
                    <p class="text-sm text-gray-600">You might find your answer in our frequently asked questions.</p>
                </div>
                <a href="{{ route('student.help.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white text-primary-600 rounded-lg font-medium hover:bg-gray-50 transition-colors shadow-sm">
                    View FAQs
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</x-sidebar-layout>
