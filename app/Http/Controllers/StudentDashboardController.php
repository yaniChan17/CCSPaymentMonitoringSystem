<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\FeeSchedule;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        $activeFeeSchedule = FeeSchedule::active()->first();

        if ($activeFeeSchedule) {
            $totalPaid = Payment::where('student_id', $student->id)
                ->where('fee_schedule_id', $activeFeeSchedule->id)
                ->where('status', 'paid')
                ->sum('amount');

            $balance = $activeFeeSchedule->amount - $totalPaid;
        } else {
            $totalPaid = 0;
            $balance = 0;
        }

        // My treasurer
        $myTreasurer = User::where('role', 'treasurer')
            ->where('block_id', $student->block_id)
            ->first();

        // Payment history
        $paymentHistory = Payment::where('student_id', $student->id)
            ->where('status', 'paid')
            ->latest('payment_date')
            ->take(10)
            ->get();

        // Announcements
        $announcements = Announcement::where(function ($query) use ($student) {
            $query->where('target_role', 'all')
                ->orWhere('target_role', 'student')
                ->orWhere('target_block_id', $student->block_id);
        })
            ->latest()
            ->take(5)
            ->get();

        // Get student record
        $studentRecord = \App\Models\Student::where('id', $student->student_id)->first();
        $totalFees = $studentRecord ? $studentRecord->total_fees : 0;
        
        // Count all payments
        $paymentCount = Payment::where('student_id', $student->id)
            ->where('status', 'paid')
            ->count();

        // Calculate total paid from all payments
        $allPaid = Payment::where('student_id', $student->id)
            ->where('status', 'paid')
            ->sum('amount');

        // Create stats array
        $stats = [
            'total_fees' => $totalFees,
            'total_paid' => $allPaid,
            'balance' => max(0, $totalFees - $allPaid),
            'payment_count' => $paymentCount,
        ];

        return view('student.dashboard', compact(
            'student',
            'stats',
            'activeFeeSchedule',
            'totalPaid',
            'balance',
            'myTreasurer',
            'paymentHistory',
            'announcements'
        ));
    }
}
