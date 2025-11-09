<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.treasurer />
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('treasurer.payments.index') }}" 
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
            @php
                $recordedAt = $payment->recorded_at ?? $payment->created_at;
                $hoursElapsed = now()->diffInHours($recordedAt);
                $canEdit = $hoursElapsed < 24 && $payment->recorded_by === auth()->id();
            @endphp
            @if($canEdit)
                <a href="{{ route('treasurer.payments.edit', $payment) }}" 
                   class="px-4 py-2 bg-gradient-to-r from-primary-600 to-secondary-500 text-white text-sm font-medium rounded-lg hover:from-primary-700 hover:to-secondary-600 transition-all">
                    Edit Payment
                </a>
            @endif
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column: Student Information -->
            <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
                <div class="flex items-start space-x-4 mb-4">
                    <div class="flex-shrink-0">
                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-primary-500 to-accent-600 flex items-center justify-center">
                            <span class="text-white font-bold text-xl">{{ strtoupper(substr($payment->student->full_name, 0, 2)) }}</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600">Full Name</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $payment->student->full_name }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Email</p>
                        <p class="text-sm text-gray-900">{{ $payment->student->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Student ID</p>
                        <p class="text-sm text-gray-900 font-mono">{{ $payment->student->student_number ?? $payment->student->student_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Block</p>
                        <p class="text-sm text-gray-900">Block {{ $payment->student->block_id }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Payment Information -->
            <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Amount</p>
                        <p class="text-3xl font-bold text-green-600">₱{{ number_format($payment->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Payment Method</p>
                        <p class="text-base text-gray-900 capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Payment Date</p>
                        <p class="text-base text-gray-900">{{ $payment->payment_date->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Status</p>
                        <div class="flex flex-wrap gap-2 mt-1">
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                PAID ✓
                            </span>
                            @if($payment->is_late)
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Late
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fee Schedule Details -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Fee Schedule Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-600">Fee Name</p>
                    <p class="text-base text-gray-900">{{ $payment->feeSchedule->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Academic Year</p>
                    <p class="text-base text-gray-900">{{ $payment->feeSchedule->academic_year }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Semester</p>
                    <p class="text-base text-gray-900">{{ $payment->feeSchedule->semester ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($payment->reference_number)
                    <div>
                        <p class="text-sm font-medium text-gray-600">Reference Number</p>
                        <p class="text-base text-gray-900 font-mono">{{ $payment->reference_number }}</p>
                    </div>
                @endif
                @if($payment->notes)
                    <div class="md:col-span-2">
                        <p class="text-sm font-medium text-gray-600">Notes</p>
                        <p class="text-base text-gray-700 mt-1">{{ $payment->notes }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-sm font-medium text-gray-600">Recorded By</p>
                    <p class="text-base text-gray-900">{{ $payment->recordedBy->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Recorded At</p>
                    <p class="text-base text-gray-900">{{ ($payment->recorded_at ?? $payment->created_at)->format('M d, Y h:i A') }}</p>
                </div>
                @if($payment->edited_at)
                    <div>
                        <p class="text-sm font-medium text-gray-600">Last Edited By</p>
                        <p class="text-base text-gray-900">{{ $payment->editor->name ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Last Edited At</p>
                        <p class="text-base text-gray-900">{{ $payment->edited_at->format('M d, Y h:i A') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Card -->
        @if($canEdit)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-900">
                                You can still edit this payment for {{ 24 - now()->diffInHours($recordedAt) }} more hours
                            </p>
                            <p class="text-xs text-blue-700 mt-1">
                                After 24 hours, only administrators can make changes to this payment record.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('treasurer.payments.edit', $payment) }}" 
                       class="ml-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors whitespace-nowrap">
                        Edit Now
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-sidebar-layout>
