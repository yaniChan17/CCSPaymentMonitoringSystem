<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.admin />
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.payments.index') }}" 
                   class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Payment Details</h1>
                    <p class="mt-1 text-sm text-gray-600">Payment #{{ $payment->id }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.payments.edit', $payment) }}" 
                   class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    Edit Payment
                </a>
                <form action="{{ route('admin.payments.destroy', $payment) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this payment?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Payment Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Payment Details Card -->
                <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Payment Date</p>
                            <p class="text-base text-gray-900">{{ $payment->payment_date->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Amount</p>
                            <p class="text-2xl font-bold text-green-600">₱{{ number_format($payment->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Payment Method</p>
                            <p class="text-base text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Status</p>
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full 
                                {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $payment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        @if($payment->reference_number)
                            <div class="col-span-2">
                                <p class="text-sm font-medium text-gray-600 mb-1">Reference Number</p>
                                <p class="text-base text-gray-900 font-mono">{{ $payment->reference_number }}</p>
                            </div>
                        @endif
                        @if($payment->notes)
                            <div class="col-span-2">
                                <p class="text-sm font-medium text-gray-600 mb-1">Notes</p>
                                <p class="text-base text-gray-700">{{ $payment->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Student Information Card -->
                <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Student Information</h3>
                        <a href="{{ route('admin.users.show', $payment->student->user) }}" 
                           class="text-sm text-primary-600 hover:text-primary-800">
                            View Profile →
                        </a>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-primary-500 to-accent-600 flex items-center justify-center">
                                <span class="text-white font-bold text-xl">{{ strtoupper(substr($payment->student->full_name, 0, 2)) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-600">Full Name</p>
                                <p class="text-base font-semibold text-gray-900">{{ $payment->student->full_name }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Student ID</p>
                                    <p class="text-sm text-gray-900 font-mono">{{ $payment->student->student_id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Email</p>
                                    <p class="text-sm text-gray-900">{{ $payment->student->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Course</p>
                                    <p class="text-sm text-gray-900">{{ $payment->student->course }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Year Level</p>
                                    <p class="text-sm text-gray-900">{{ $payment->student->year_level }}{{ $payment->student->year_level == 1 ? 'st' : ($payment->student->year_level == 2 ? 'nd' : ($payment->student->year_level == 3 ? 'rd' : 'th')) }} Year</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Treasurer Information Card -->
                <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recorded By</h3>
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                                <span class="text-green-600 font-semibold text-sm">{{ strtoupper(substr($payment->recordedBy->name, 0, 2)) }}</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-base font-semibold text-gray-900">{{ $payment->recordedBy->name }}</p>
                            <p class="text-sm text-gray-600">{{ $payment->recordedBy->email }}</p>
                            <span class="mt-1 px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($payment->recordedBy->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Current Status</span>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $payment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500">Created</p>
                            <p class="text-sm text-gray-900">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Last Updated</p>
                            <p class="text-sm text-gray-900">{{ $payment->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.payments.edit', $payment) }}" 
                           class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Payment
                        </a>
                        <a href="{{ route('admin.users.show', $payment->student->user) }}" 
                           class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            View Student Profile
                        </a>
                        <a href="{{ route('admin.payments.index', ['search' => $payment->student->student_id]) }}" 
                           class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            View All Student Payments
                        </a>
                    </div>
                </div>

                <!-- Amount Summary -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                    <p class="text-sm opacity-90 mb-1">Payment Amount</p>
                    <p class="text-4xl font-bold">₱{{ number_format($payment->amount, 2) }}</p>
                    <p class="text-xs opacity-75 mt-2">{{ $payment->payment_date->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
