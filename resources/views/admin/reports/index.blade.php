<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.admin />
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
            <p class="mt-1 text-sm text-gray-600">Generate and export payment reports</p>
        </div>

        <!-- Quick Export Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Export Payments -->
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Export Payments</h3>
                        <p class="text-sm opacity-90 mb-4">Download all payment records in CSV format</p>
                        <a href="{{ route('admin.reports.export-payments') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white text-indigo-600 font-medium rounded-lg hover:bg-indigo-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download CSV
                        </a>
                    </div>
                    <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Export Students -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">Export Students</h3>
                        <p class="text-sm opacity-90 mb-4">Download student data with payment summaries</p>
                        <a href="{{ route('admin.reports.export-students') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white text-green-600 font-medium rounded-lg hover:bg-green-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download CSV
                        </a>
                    </div>
                    <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Report Link -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <div class="p-3 bg-purple-50 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Summary Report</h3>
                        <p class="text-sm text-gray-600">View detailed financial summary with breakdowns by method, collector, and monthly trends</p>
                    </div>
                </div>
                <a href="{{ route('admin.reports.summary') }}" 
                   class="px-6 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
                    View Report
                </a>
            </div>
        </div>

        <!-- Overview Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Payments</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_payments'] }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Collected</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">₱{{ number_format($stats['total_amount'], 2) }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Paid Payments</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['paid_count'] }}</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Payments</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['pending_count'] }}</p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Payments Preview -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Payments</h3>
                <a href="{{ route('admin.payments.index') }}" 
                   class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    View All →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recent_payments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->payment_date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $payment->student->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $payment->student->student_id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    ₱{{ number_format($payment->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full 
                                        {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $payment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Monthly Trend (Last 6 Months) -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Collection Trend</h3>
            <div class="space-y-3">
                @foreach($monthly_data as $month)
                    <div class="flex items-center">
                        <div class="w-32 text-sm font-medium text-gray-700">{{ $month->month_name }}</div>
                        <div class="flex-1 mx-4">
                            <div class="bg-gray-200 rounded-full h-8 relative overflow-hidden">
                                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-full rounded-full flex items-center px-3 text-white text-xs font-semibold transition-all duration-500" 
                                     style="width: {{ $month->total > 0 ? ($month->total / $monthly_data->max('total')) * 100 : 0 }}%">
                                    @if($month->total > 0)
                                        ₱{{ number_format($month->total, 2) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="w-24 text-right text-sm text-gray-600">
                            {{ $month->payment_count }} {{ Str::plural('payment', $month->payment_count) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-sidebar-layout>
