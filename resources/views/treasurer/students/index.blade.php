<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.treasurer />
    </x-slot>

    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">My Students</h1>
        <p class="text-sm text-gray-500 mt-1">Students in my assigned block</p>
    </x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow-md rounded-xl hover-lift border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Students</div>
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['total_students'] }}</div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-md rounded-xl hover-lift border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Fully Paid</div>
                    <div class="text-3xl font-bold text-green-600">{{ $stats['fully_paid'] }}</div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-md rounded-xl hover-lift border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">With Balance</div>
                    <div class="text-3xl font-bold text-orange-600">{{ $stats['with_balance'] }}</div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-md rounded-xl hover-lift border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Collected</div>
                    <div class="text-2xl font-bold text-primary-600">₱{{ number_format($stats['total_collected'], 2) }}</div>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-accent-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white shadow-md rounded-xl border border-gray-100">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Student List</h3>
            <p class="text-sm text-gray-500 mt-1">Payment status for students in your block</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Paid Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($studentsData as $data)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-accent-600 rounded-full flex items-center justify-center text-white text-xs font-semibold mr-3">
                                        {{ strtoupper(substr($data['user']->name, 0, 2)) }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">{{ $data['user']->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $data['user']->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600">₱{{ number_format($data['total_paid'], 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold {{ $data['balance'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    ₱{{ number_format($data['balance'], 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($data['status'] === 'paid')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Fully Paid
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        Has Balance
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('treasurer.payments.create') }}" class="inline-flex items-center px-3 py-1.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-xs">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Record Payment
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No students found</h3>
                                <p class="mt-1 text-sm text-gray-500">No students have been assigned to your block yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .hover-lift {
            transition: all 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15);
        }
    </style>
</x-sidebar-layout>
