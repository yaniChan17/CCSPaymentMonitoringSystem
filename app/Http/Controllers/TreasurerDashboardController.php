<?php

namespace App\Http\Controllers;

use App\Models\FeeSchedule;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class TreasurerDashboardController extends Controller
{
    public function index()
    {
        $treasurer = auth()->user();
        $activeFeeSchedule = FeeSchedule::active()->first();

        // Today's collection
        $todayTotal = Payment::where('recorded_by', $treasurer->id)
            ->whereDate('recorded_at', today())
            ->sum('amount');
        $todayCount = Payment::where('recorded_by', $treasurer->id)
            ->whereDate('recorded_at', today())
            ->count();

        // This week
        $weekTotal = Payment::where('recorded_by', $treasurer->id)
            ->whereBetween('recorded_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount');
        $weekCount = Payment::where('recorded_by', $treasurer->id)
            ->whereBetween('recorded_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        // Active students in block
        $activeStudents = User::where('role', 'student')
            ->where('block_id', $treasurer->block_id)
            ->count();

        if ($activeFeeSchedule) {
            $myBlockStudents = $activeStudents;
            $myBlockExpected = $myBlockStudents * $activeFeeSchedule->amount;
            $myBlockCollected = Payment::whereHas('student', function ($q) use ($treasurer) {
                $q->where('block_id', $treasurer->block_id);
            })
                ->where('fee_schedule_id', $activeFeeSchedule->id)
                ->where('status', 'paid')
                ->sum('amount');

            $myBlockRemaining = $myBlockExpected - $myBlockCollected;
            $myBlockPercentage = $myBlockExpected > 0 ? round(($myBlockCollected / $myBlockExpected) * 100, 1) : 0;
            $myBlockPaidCount = User::where('role', 'student')
                ->where('block_id', $treasurer->block_id)
                ->whereHas('payments', function ($q) use ($activeFeeSchedule) {
                    $q->where('fee_schedule_id', $activeFeeSchedule->id)
                        ->where('status', 'paid')
                        ->havingRaw('SUM(amount) >= ?', [$activeFeeSchedule->amount]);
                })
                ->count();

            // Unpaid students
            $unpaidStudents = User::where('role', 'student')
                ->where('block_id', $treasurer->block_id)
                ->get()
                ->map(function ($student) use ($activeFeeSchedule) {
                    $paid = $student->payments()
                        ->where('fee_schedule_id', $activeFeeSchedule->id)
                        ->where('status', 'paid')
                        ->sum('amount');
                    $balance = $activeFeeSchedule->amount - $paid;

                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'balance' => $balance
                    ];
                })
                ->where('balance', '>', 0)
                ->values();
        } else {
            $myBlockStudents = 0;
            $myBlockExpected = 0;
            $myBlockCollected = 0;
            $myBlockRemaining = 0;
            $myBlockPercentage = 0;
            $myBlockPaidCount = 0;
            $unpaidStudents = collect();
        }

        // Recent payments by this treasurer
        $recentPayments = Payment::with('student')
            ->where('recorded_by', $treasurer->id)
            ->latest('recorded_at')
            ->take(10)
            ->get();

        return view('treasurer.dashboard', compact(
            'activeFeeSchedule',
            'todayTotal',
            'todayCount',
            'weekTotal',
            'weekCount',
            'activeStudents',
            'myBlockStudents',
            'myBlockExpected',
            'myBlockCollected',
            'myBlockRemaining',
            'myBlockPercentage',
            'myBlockPaidCount',
            'unpaidStudents',
            'recentPayments'
        ));
    }
}
