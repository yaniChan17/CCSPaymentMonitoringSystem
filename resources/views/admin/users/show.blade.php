<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.admin />
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.users.index') }}" 
               class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Details</h1>
                <p class="mt-1 text-sm text-gray-600">View and manage user information</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-32 text-sm font-medium text-gray-600">Full Name:</div>
                            <div class="flex-1 text-sm text-gray-900">{{ $user->name }}</div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-32 text-sm font-medium text-gray-600">Email:</div>
                            <div class="flex-1 text-sm text-gray-900">{{ $user->email }}</div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-32 text-sm font-medium text-gray-600">Role:</div>
                            <div class="flex-1">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $user->role === 'treasurer' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $user->role === 'student' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-32 text-sm font-medium text-gray-600">Joined:</div>
                            <div class="flex-1 text-sm text-gray-900">{{ $user->created_at->format('F d, Y') }}</div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-32 text-sm font-medium text-gray-600">Last Updated:</div>
                            <div class="flex-1 text-sm text-gray-900">{{ $user->updated_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                @if($user->role === 'student' && $user->student)
                    <!-- Student Info Card -->
                    <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Information</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-32 text-sm font-medium text-gray-600">Student ID:</div>
                                <div class="flex-1 text-sm text-gray-900 font-mono">{{ $user->student->student_id }}</div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-32 text-sm font-medium text-gray-600">Course:</div>
                                <div class="flex-1 text-sm text-gray-900">{{ $user->student->course }}</div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-32 text-sm font-medium text-gray-600">Year Level:</div>
                                <div class="flex-1 text-sm text-gray-900">{{ $user->student->year_level }}{{ $user->student->year_level == 1 ? 'st' : ($user->student->year_level == 2 ? 'nd' : ($user->student->year_level == 3 ? 'rd' : 'th')) }} Year</div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-32 text-sm font-medium text-gray-600">Block Number:</div>
                                <div class="flex-1 text-sm text-gray-900">{{ $user->student->block ? 'Block ' . $user->student->block : 'Not assigned' }}</div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-32 text-sm font-medium text-gray-600">Status:</div>
                                <div class="flex-1">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $user->student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($user->student->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $payments = $user->student->payments()->latest()->take(5)->get();
                        $totalPayments = $user->student->payments()->count();
                        $totalPaid = $user->student->payments()->where('status', 'paid')->sum('amount');
                    @endphp

                    <!-- Payment Statistics -->
                    <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Statistics</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm text-blue-600 font-medium">Total Payments</p>
                                <p class="text-2xl font-bold text-blue-900 mt-1">{{ $totalPayments }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm text-green-600 font-medium">Total Paid</p>
                                <p class="text-2xl font-bold text-green-900 mt-1">₱{{ number_format($totalPaid, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Payments -->
                    @if($payments->count() > 0)
                        <div class="bg-white shadow-md rounded-xl border border-gray-100">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Recent Payments</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($payments as $payment)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $payment->payment_date->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    ₱{{ number_format($payment->amount, 2) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                        {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ ucfirst($payment->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Profile Avatar -->
                <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
                    <div class="flex flex-col items-center">
                        <div class="h-24 w-24 rounded-full bg-gradient-to-br from-primary-500 to-accent-600 flex items-center justify-center">
                            <span class="text-white font-bold text-3xl">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900 text-center">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-600 text-center">{{ $user->email }}</p>
                        <span class="mt-3 px-4 py-1 text-sm font-semibold rounded-full 
                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                            {{ $user->role === 'treasurer' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $user->role === 'student' ? 'bg-blue-100 text-blue-800' : '' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white shadow-md rounded-xl border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit User
                        </a>
                        @if($user->role === 'student')
                            <a href="{{ route('admin.payments.index', ['student' => $user->student->student_id]) }}" 
                               class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                View Payments
                            </a>
                        @endif
                        
                        <!-- Delete User -->
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <form action="{{ route('admin.users.destroy', $user) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
