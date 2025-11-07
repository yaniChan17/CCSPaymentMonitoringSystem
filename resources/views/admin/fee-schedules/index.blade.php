<x-sidebar-layout>
    <x-slot name="navigation">
        <x-nav.admin />
    </x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Fee Schedules</h1>
                <p class="text-sm text-gray-600 mt-1">Manage and track fee schedules for students</p>
            </div>
            <a href="{{ route('admin.fee-schedules.create') }}" 
               class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg transition-all duration-200 shadow-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Create Fee Schedule</span>
            </a>
        </div>

        <!-- Fee Schedules List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($feeSchedules as $schedule)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $schedule->name }}</div>
                                @if($schedule->instructions)
                                <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($schedule->instructions, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $schedule->academic_year }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $schedule->semester }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">₱{{ number_format($schedule->amount, 2) }}</div>
                                @if($schedule->late_penalty > 0)
                                <div class="text-xs text-red-600">+₱{{ number_format($schedule->late_penalty, 2) }} late penalty</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $schedule->due_date->format('M d, Y') }}</div>
                                @if($schedule->isOverdue() && $schedule->status === 'active')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 mt-1">
                                    Overdue
                                </span>
                                @else
                                <div class="text-xs text-gray-500">{{ abs($schedule->daysUntilDue()) }} days {{ $schedule->daysUntilDue() < 0 ? 'ago' : 'remaining' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($schedule->status === 'active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                                @elseif($schedule->status === 'draft')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Draft
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Closed
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($schedule->target_block_id)
                                    {{ $schedule->targetBlock->name ?? 'N/A' }}
                                @else
                                    <span class="text-gray-500">All Blocks</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('admin.fee-schedules.edit', $schedule) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">
                                    Edit
                                </a>
                                
                                @if($schedule->status === 'draft')
                                <form action="{{ route('admin.fee-schedules.activate', $schedule) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-green-600 hover:text-green-900"
                                            onclick="return confirm('Activate this fee schedule? Students and treasurers will be notified.')">
                                        Activate
                                    </button>
                                </form>
                                @endif
                                
                                @if($schedule->status === 'active' && $schedule->isOverdue())
                                <form action="{{ route('admin.fee-schedules.close', $schedule) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Close this fee schedule? It will be locked and cannot be edited.')">
                                        Close
                                    </button>
                                </form>
                                @endif
                                
                                @if($schedule->status === 'draft')
                                <form action="{{ route('admin.fee-schedules.destroy', $schedule) }}" 
                                      method="POST" 
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Delete this fee schedule?')">
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No fee schedules yet</h3>
                                <p class="text-sm text-gray-600 mb-4">Get started by creating your first fee schedule</p>
                                <a href="{{ route('admin.fee-schedules.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Create Fee Schedule
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($feeSchedules->hasPages())
        <div class="mt-6">
            {{ $feeSchedules->links() }}
        </div>
        @endif
    </div>
</x-sidebar-layout>
