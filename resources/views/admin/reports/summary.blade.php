<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.admin />
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.reports.index') }}" 
                       class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900">Summary Report</h1>
                </div>
                <p class="mt-1 ml-11 text-sm text-gray-600">Detailed financial breakdown and analytics</p>
            </div>
            <form method="GET" action="{{ route('admin.reports.export-summary') }}" class="inline">
                @if(request('date_from'))
                    <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                @endif
                @if(request('date_to'))
                    <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                @endif
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Report
                </button>
            </form>
        </div>

        <!-- Date Range Filter -->
        <form method="GET" action="{{ route('admin.reports.summary') }}" 
              class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" 
                           id="date_from" 
                           name="date_from" 
                           value="{{ request('date_from') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" 
                           id="date_to" 
                           name="date_to" 
                           value="{{ request('date_to') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                        Apply
                    </button>
                    @if(request()->hasAny(['date_from', 'date_to']))
                        <a href="{{ route('admin.reports.summary') }}" 
                           class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Period Summary -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold mb-1">Period Summary</h3>
                    <p class="text-sm opacity-90">
                        @if(request('date_from') && request('date_to'))
                            {{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }} - 
                            {{ \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') }}
                        @elseif(request('date_from'))
                            From {{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }}
                        @elseif(request('date_to'))
                            Until {{ \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') }}
                        @else
                            All Time
                        @endif
                    </p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <p class="text-sm opacity-90 mb-1">Total Payments</p>
                    <p class="text-2xl font-bold">{{ $summary['total_payments'] }}</p>
                </div>
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <p class="text-sm opacity-90 mb-1">Total Collected</p>
                    <p class="text-2xl font-bold">₱{{ number_format($summary['total_amount'], 2) }}</p>
                </div>
                <div class="bg-white bg-opacity-10 rounded-lg p-4">
                    <p class="text-sm opacity-90 mb-1">Pending Amount</p>
                    <p class="text-2xl font-bold">₱{{ number_format($summary['pending_amount'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Breakdown by Payment Method -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Breakdown by Payment Method</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Method</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Count</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Amount</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Percentage</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($payment_methods as $method)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="p-2 bg-indigo-50 rounded-lg mr-3">
                                            @if($method->payment_method === 'cash')
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            @elseif($method->payment_method === 'check')
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                </svg>
                                            @elseif($method->payment_method === 'bank_transfer')
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ ucfirst(str_replace('_', ' ', $method->payment_method)) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                    {{ $method->count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                                    ₱{{ number_format($method->total, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end">
                                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="bg-indigo-600 h-2 rounded-full" 
                                                 style="width: {{ $summary['total_amount'] > 0 ? ($method->total / $summary['total_amount']) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $summary['total_amount'] > 0 ? number_format(($method->total / $summary['total_amount']) * 100, 1) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    No payment data available for this period
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Collectors -->
        <div class="bg-white shadow-md rounded-xl border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Collectors</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Treasurer</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Payments</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Collected</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($top_collectors as $index => $collector)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center w-8 h-8 rounded-full font-bold
                                        {{ $index === 0 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $index === 1 ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $index === 2 ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ $index > 2 ? 'bg-blue-50 text-blue-800' : '' }}">
                                        {{ $index + 1 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-semibold mr-3">
                                            {{ strtoupper(substr($collector->treasurer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $collector->treasurer->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $collector->treasurer->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                    {{ $collector->payment_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900">
                                    ₱{{ number_format($collector->total_collected, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                    No collector data available for this period
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Status Breakdown -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-medium text-gray-600">Paid Payments</h4>
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $status_breakdown['paid_count'] ?? 0 }}</p>
                    <p class="text-sm text-gray-600">₱{{ number_format($status_breakdown['paid_amount'] ?? 0, 2) }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-medium text-gray-600">Pending Payments</h4>
                    <div class="p-2 bg-yellow-50 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $status_breakdown['pending_count'] ?? 0 }}</p>
                    <p class="text-sm text-gray-600">₱{{ number_format($status_breakdown['pending_amount'] ?? 0, 2) }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-medium text-gray-600">Cancelled Payments</h4>
                    <div class="p-2 bg-red-50 rounded-lg">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-3xl font-bold text-gray-900">{{ $status_breakdown['cancelled_count'] ?? 0 }}</p>
                    <p class="text-sm text-gray-600">₱{{ number_format($status_breakdown['cancelled_amount'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
