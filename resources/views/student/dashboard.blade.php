<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.student />
    </x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Student Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">View your payment status and transaction history</p>
    </x-slot>

    @if($activeFeeSchedule)
        <!-- Current Fees Card -->
        <div class="bg-gradient-to-br from-red-500 via-pink-500 to-red-600 overflow-hidden shadow-lg rounded-xl mb-8">
            <div class="p-6 sm:p-8">
                <div class="flex items-start justify-between mb-6">
                    <div class="text-white">
                        <h3 class="text-2xl font-bold">{{ $activeFeeSchedule->name }}</h3>
                        <p class="text-pink-100 text-sm mt-1">{{ $activeFeeSchedule->academic_year }} - {{ $activeFeeSchedule->semester }}</p>
                    </div>
                    @php
                        $daysRemaining = $activeFeeSchedule->daysUntilDue();
                        $colorClass = $daysRemaining > 7 ? 'bg-green-400' : ($daysRemaining >= 3 ? 'bg-yellow-400' : 'bg-red-200');
                        $textColor = $daysRemaining > 7 ? 'text-green-900' : ($daysRemaining >= 3 ? 'text-yellow-900' : 'text-red-900');
                    @endphp
                    <div class="text-right">
                        <p class="text-sm text-pink-100">Due Date</p>
                        <p class="text-lg font-bold text-white">{{ $activeFeeSchedule->due_date->format('M d, Y') }}</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $colorClass }} {{ $textColor }} mt-2">
                            {{ abs($daysRemaining) }} day{{ abs($daysRemaining) !== 1 ? 's' : '' }} {{ $daysRemaining >= 0 ? 'remaining' : 'overdue' }}
                        </span>
                    </div>
                </div>

                <!-- Fee Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 border border-white/30">
                        <div class="text-xs font-semibold text-pink-100 uppercase tracking-wider mb-1">Total Fee</div>
                        <div class="text-2xl font-bold text-white">₱{{ number_format($activeFeeSchedule->amount, 2) }}</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 border border-white/30">
                        <div class="text-xs font-semibold text-pink-100 uppercase tracking-wider mb-1">Paid</div>
                        <div class="text-2xl font-bold text-white">₱{{ number_format($totalPaid, 2) }}</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 border border-white/30">
                        <div class="text-xs font-semibold text-pink-100 uppercase tracking-wider mb-1">Balance</div>
                        <div class="text-2xl font-bold {{ $balance > 0 ? 'text-yellow-300' : 'text-green-300' }}">₱{{ number_format($balance, 2) }}</div>
                    </div>
                </div>

                <!-- Payment Instructions -->
                @if($activeFeeSchedule->instructions)
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 mb-4">
                        <h4 class="text-sm font-semibold text-white mb-2">Payment Instructions</h4>
                        <p class="text-sm text-pink-100">{{ $activeFeeSchedule->instructions }}</p>
                    </div>
                @endif

                <!-- Treasurer Contact -->
                @if($myTreasurer)
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                        <h4 class="text-sm font-semibold text-white mb-2">Your Block Treasurer</h4>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-red-600 text-sm font-bold mr-3">
                                {{ strtoupper(substr($myTreasurer->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-white">{{ $myTreasurer->name }}</p>
                                <p class="text-xs text-pink-100">{{ $myTreasurer->email }}</p>
                                @if($myTreasurer->contact_number)
                                    <p class="text-xs text-pink-100">{{ $myTreasurer->contact_number }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Student Profile Card -->
    <div class="bg-gradient-to-br from-primary-500 to-accent-600 overflow-hidden shadow-lg rounded-xl mb-8">
        <div class="p-6 sm:p-8">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-primary-600 text-2xl font-bold shadow-lg">
                        {{ strtoupper(substr($student->full_name, 0, 2)) }}
                    </div>
                    <div class="text-white">
                        <h3 class="text-2xl font-bold">{{ $student->full_name }}</h3>
                        <p class="text-indigo-100 text-sm mt-1">{{ $student->student_id }}</p>
                        <div class="flex items-center space-x-2 mt-2 flex-wrap">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium">
                                {{ $student->course }}
                            </span>
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium">
                                Year {{ $student->year_level }}
                            </span>
                            @if($student->block)
                                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-medium">
                                    Block {{ $student->block }}
                                </span>
                            @endif
                            @if($student->status === 'active')
                                <span class="px-3 py-1 bg-green-400/30 backdrop-blur-sm rounded-full text-xs font-semibold flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Active
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <button class="px-4 py-2 bg-white text-primary-600 rounded-lg font-medium text-sm hover:bg-primary-50 transition-colors duration-200">
                        Edit Profile
                    </button>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-white/20">
                <div class="grid grid-cols-2 gap-4 text-white">
                    <div>
                        <p class="text-xs text-indigo-100">Email</p>
                        <p class="text-sm font-medium mt-1">{{ $student->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-indigo-100">Contact</p>
                        <p class="text-sm font-medium mt-1">{{ $student->phone ?? 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Fees -->
        <div class="stat-card bg-white overflow-hidden shadow-md rounded-xl hover-lift border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Fees</div>
                        <div class="text-3xl font-bold text-gray-900">₱{{ number_format($stats['total_fees'], 2) }}</div>
                        <div class="mt-2 text-sm text-gray-500">This semester</div>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Paid -->
        <div class="stat-card bg-white overflow-hidden shadow-md rounded-xl hover-lift border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Paid</div>
                        <div class="text-3xl font-bold text-green-600">₱{{ number_format($stats['total_paid'], 2) }}</div>
                        <div class="mt-2 flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            {{ $stats['payment_count'] }} payments
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Outstanding Balance -->
        <div class="stat-card bg-white overflow-hidden shadow-md rounded-xl hover-lift border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Balance</div>
                        <div class="text-3xl font-bold {{ $stats['balance'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                            ₱{{ number_format($stats['balance'], 2) }}
                        </div>
                        @if($stats['balance'] <= 0)
                            <div class="mt-2 flex items-center text-sm text-green-600 font-semibold">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Fully Paid
                            </div>
                        @else
                            <div class="mt-2 text-sm text-red-500">Payment required</div>
                        @endif
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br {{ $stats['balance'] > 0 ? 'from-red-500 to-red-600' : 'from-green-500 to-green-600' }} rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Section -->
    @if($announcements && $announcements->count() > 0)
        <div class="bg-white shadow-md rounded-xl border border-gray-100 mb-8">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Announcements</h3>
                <p class="text-sm text-gray-500 mt-1">Latest updates and notices</p>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($announcements as $announcement)
                    <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-md font-semibold text-gray-900 mb-2">{{ $announcement->title }}</h4>
                                <p class="text-sm text-gray-700 mb-3">{{ $announcement->message }}</p>
                                <div class="flex items-center text-xs text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Posted {{ $announcement->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            <span class="ml-4 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                {{ ucfirst($announcement->target_role) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

            <!-- Payment History -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Payment History</h3>
                        <button class="text-sm text-purple-600 hover:text-purple-700 font-medium transition-colors duration-200">
                            Download Statement →
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Schedule</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($paymentHistory as $payment)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $payment->payment_date->format('g:i A') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $payment->feeSchedule->name ?? 'N/A' }}</div>
                                        @if($payment->feeSchedule)
                                            <div class="text-xs text-gray-500">{{ $payment->feeSchedule->academic_year }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">₱{{ number_format($payment->amount, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $method = strtolower($payment->payment_method);
                                            $badgeColors = [
                                                'cash' => 'bg-green-100 text-green-800',
                                                'gcash' => 'bg-blue-100 text-blue-800',
                                                'maya' => 'bg-orange-100 text-orange-800',
                                                'paypal' => 'bg-indigo-100 text-indigo-800',
                                            ];
                                            $badgeColor = $badgeColors[$method] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                            {{ ucfirst($method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            PAID ✓
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="inline-flex items-center px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-200 hover:shadow-md">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Receipt
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="mt-4 text-gray-500 text-lg">No payment records found.</p>
                                        <p class="mt-1 text-gray-400 text-sm">Your payment history will appear here once you make a payment.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .stat-card {
            transition: all 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15);
        }
    </style>
</x-sidebar-layout>
