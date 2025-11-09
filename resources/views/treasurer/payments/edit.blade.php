<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.treasurer />
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('treasurer.payments.show', $payment) }}" 
               class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Payment</h1>
                <p class="mt-1 text-sm text-gray-600">Update payment information</p>
            </div>
        </div>

        @php
            $recordedAt = $payment->recorded_at ?? $payment->created_at;
            $hoursElapsed = now()->diffInHours($recordedAt);
            $hoursRemaining = 24 - $hoursElapsed;
            $minutesRemaining = (24 * 60) - now()->diffInMinutes($recordedAt);
        @endphp

        <!-- Time Warning -->
        <div class="bg-{{ $hoursRemaining < 2 ? 'yellow' : 'blue' }}-50 border border-{{ $hoursRemaining < 2 ? 'yellow' : 'blue' }}-200 text-{{ $hoursRemaining < 2 ? 'yellow' : 'blue' }}-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-semibold">
                        @if($hoursRemaining < 1)
                            You have less than 1 hour left to edit this payment
                        @elseif($hoursRemaining == 1)
                            You have 1 hour left to edit this payment
                        @else
                            You have {{ $hoursRemaining }} hours left to edit this payment
                        @endif
                    </p>
                    <p class="text-sm mt-1">
                        Payments can only be edited within 24 hours of being recorded. 
                        After that, contact an administrator for changes.
                    </p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100">
            <form method="POST" action="{{ route('treasurer.payments.update', $payment) }}">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Student Information (Read-only) -->
                    <div class="bg-gray-100 rounded-lg p-4 border border-gray-300">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Student Information (Read-only)</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600">Student Name</p>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->student->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Student ID</p>
                                <p class="text-sm font-medium text-gray-900 font-mono">{{ $payment->student->student_number ?? $payment->student->student_id }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Email</p>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->student->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Block</p>
                                <p class="text-sm font-medium text-gray-900">Block {{ $payment->student->block_id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Fee Schedule (Read-only) -->
                    <div class="bg-gray-100 rounded-lg p-4 border border-gray-300">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Fee Schedule (Read-only)</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600">Fee Name</p>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->feeSchedule->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Academic Year</p>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->feeSchedule->academic_year }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Editable Fields -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Payment Details</h3>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
                                Amount <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">₱</span>
                                <input type="number" 
                                       name="amount" 
                                       id="amount" 
                                       value="{{ old('amount', $payment->amount) }}"
                                       step="0.01"
                                       min="0.01"
                                       required
                                       class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('amount') border-red-500 @enderror">
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">
                                Payment Method <span class="text-red-500">*</span>
                            </label>
                            <select name="payment_method" 
                                    id="payment_method"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                                <option value="cash" {{ old('payment_method', $payment->payment_method) === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="gcash" {{ old('payment_method', $payment->payment_method) === 'gcash' ? 'selected' : '' }}>GCash</option>
                                <option value="maya" {{ old('payment_method', $payment->payment_method) === 'maya' ? 'selected' : '' }}>Maya</option>
                                <option value="paypal" {{ old('payment_method', $payment->payment_method) === 'paypal' ? 'selected' : '' }}>PayPal</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div>
                            <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-1">
                                Payment Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="payment_date" 
                                   id="payment_date" 
                                   value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('payment_date') border-red-500 @enderror">
                            @error('payment_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Reference Number -->
                        <div>
                            <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-1">
                                Reference Number
                            </label>
                            <input type="text" 
                                   name="reference_number" 
                                   id="reference_number" 
                                   value="{{ old('reference_number', $payment->reference_number) }}"
                                   placeholder="Optional - Transaction ID, check number, etc."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('reference_number') border-red-500 @enderror">
                            @error('reference_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                Notes
                            </label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3"
                                      placeholder="Optional notes or remarks..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $payment->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Edit History -->
                    @if($payment->edited_at)
                        <div class="border-t border-gray-200 pt-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">Edit History</h3>
                            <p class="text-sm text-gray-600">
                                Last edited by <span class="font-medium">{{ $payment->editor->name ?? 'Unknown' }}</span> 
                                on {{ $payment->edited_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                    @endif

                    <!-- Metadata (Read-only) -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Additional Information</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Recorded At</p>
                                <p class="text-gray-900">{{ $recordedAt->format('M d, Y h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Recorded By</p>
                                <p class="text-gray-900">{{ $payment->recordedBy->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center rounded-b-xl">
                    <a href="{{ route('treasurer.payments.show', $payment) }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-primary-600 to-secondary-500 text-white rounded-lg hover:from-primary-700 hover:to-secondary-600 transition-all shadow-sm">
                        Update Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
