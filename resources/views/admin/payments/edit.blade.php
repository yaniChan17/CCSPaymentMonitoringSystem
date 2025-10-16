<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.admin />
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.payments.show', $payment) }}" 
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

        <!-- Form -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100">
            <form method="POST" action="{{ route('admin.payments.update', $payment) }}">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Student Information (Read-only) -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Student Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-600">Student Name</p>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->student->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Student ID</p>
                                <p class="text-sm font-medium text-gray-900 font-mono">{{ $payment->student->student_id }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Course</p>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->student->course }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600">Year Level</p>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->student->year_level }}{{ $payment->student->year_level == 1 ? 'st' : ($payment->student->year_level == 2 ? 'nd' : ($payment->student->year_level == 3 ? 'rd' : 'th')) }} Year</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
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
                                       class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('amount') border-red-500 @enderror">
                            </div>
                            @error('amount')
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
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('payment_date') border-red-500 @enderror">
                            @error('payment_date')
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
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('payment_method') border-red-500 @enderror">
                                <option value="cash" {{ old('payment_method', $payment->payment_method) === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="check" {{ old('payment_method', $payment->payment_method) === 'check' ? 'selected' : '' }}>Check</option>
                                <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="online" {{ old('payment_method', $payment->payment_method) === 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                Payment Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" 
                                    id="status"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('status') border-red-500 @enderror">
                                <option value="paid" {{ old('status', $payment->status) === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ old('status', $payment->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="cancelled" {{ old('status', $payment->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
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
                                   placeholder="Optional"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('reference_number') border-red-500 @enderror">
                            @error('reference_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Check number, transaction ID, etc.</p>
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
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('notes') border-red-500 @enderror">{{ old('notes', $payment->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Metadata (Read-only) -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Additional Information</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Created At</p>
                                <p class="text-gray-900">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Last Updated</p>
                                <p class="text-gray-900">{{ $payment->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Recorded By</p>
                                <p class="text-gray-900">{{ $payment->recordedBy->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Payment ID</p>
                                <p class="text-gray-900 font-mono">#{{ $payment->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center rounded-b-xl">
                    <a href="{{ route('admin.payments.show', $payment) }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <div class="flex space-x-3">
                        <button type="submit" 
                                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                            Update Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-sidebar-layout>
