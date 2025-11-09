<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\FeeSchedule;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $activeFeeSchedule = FeeSchedule::active()->first();

        if ($activeFeeSchedule) {
            // Calculate totals
            $totalStudents = User::where('role', 'student')->count();
            $expectedTotal = $totalStudents * $activeFeeSchedule->amount;

            $collectedTotal = Payment::where('fee_schedule_id', $activeFeeSchedule->id)
                ->where('status', 'paid')
                ->sum('amount');

            $collectionRate = $expectedTotal > 0 ? round(($collectedTotal / $expectedTotal) * 100, 1) : 0;

            // Block progress
            $blockProgress = Block::withCount(['students' => function ($query) {
                $query->where('role', 'student');
            }])
                ->with(['students' => function ($query) use ($activeFeeSchedule) {
                    $query->where('role', 'student')
                        ->with(['payments' => function ($q) use ($activeFeeSchedule) {
                            $q->where('fee_schedule_id', $activeFeeSchedule->id)
                                ->where('status', 'paid');
                        }]);
                }])
                ->get()
                ->map(function ($block) use ($activeFeeSchedule) {
                    $expected = $block->students_count * $activeFeeSchedule->amount;
                    $collected = $block->students->sum(function ($student) {
                        return $student->payments->sum('amount');
                    });
                    $percentage = $expected > 0 ? round(($collected / $expected) * 100, 1) : 0;

                    return [
                        'name' => $block->name,
                        'total_students' => $block->students_count,
                        'expected' => $expected,
                        'collected' => $collected,
                        'percentage' => $percentage,
                        'paid_count' => $block->students->filter(function ($student) use ($activeFeeSchedule) {
                            return $student->payments->sum('amount') >= $activeFeeSchedule->amount;
                        })->count()
                    ];
                });

            // Treasurer performance
            $treasurerPerformance = User::where('role', 'treasurer')
                ->with('block')
                ->get()
                ->map(function ($treasurer) {
                    return [
                        'name' => $treasurer->name,
                        'block' => $treasurer->block,
                        'today_total' => Payment::where('recorded_by', $treasurer->id)
                            ->whereDate('recorded_at', today())
                            ->sum('amount'),
                        'week_total' => Payment::where('recorded_by', $treasurer->id)
                            ->whereBetween('recorded_at', [now()->startOfWeek(), now()->endOfWeek()])
                            ->sum('amount'),
                        'month_total' => Payment::where('recorded_by', $treasurer->id)
                            ->whereMonth('recorded_at', now()->month)
                            ->sum('amount')
                    ];
                });
        } else {
            $expectedTotal = 0;
            $collectedTotal = 0;
            $collectionRate = 0;
            $blockProgress = collect();
            $treasurerPerformance = collect();
        }

        // Recent payments (all blocks)
        $recentPayments = Payment::with(['student', 'recorder'])
            ->latest('recorded_at')
            ->take(10)
            ->get();

        // Calculate stats for the view
        $totalStudents = User::where('role', 'student')->count();
        $activeStudents = User::where('role', 'student')->whereNotNull('block_id')->count();
        $totalUsers = User::count(); // All users (admin, treasurer, student)
        $totalPayments = Payment::where('status', 'paid')->sum('amount');
        
        // Calculate total balance (total fees - total paid)
        $totalFees = \App\Models\Student::sum('total_fees');
        $totalPaid = Payment::where('status', 'paid')->sum('amount');
        $totalBalance = max(0, $totalFees - $totalPaid);

        $stats = [
            'total_students' => $totalStudents,
            'active_students' => $activeStudents,
            'total_users' => $totalUsers,
            'total_payments' => $totalPayments,
            'total_balance' => $totalBalance,
            'total_collected' => $totalPayments,
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact(
            'stats',
            'activeFeeSchedule',
            'expectedTotal',
            'collectedTotal',
            'collectionRate',
            'blockProgress',
            'treasurerPerformance',
            'recentPayments'
        ));
    }
}
