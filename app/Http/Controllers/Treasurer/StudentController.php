<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\FeeSchedule;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students in treasurer's block.
     */
    public function index()
    {
        $treasurer = auth()->user();
        
        // Get students in the same block as the treasurer
        $students = User::where('role', 'student')
            ->where('block_id', $treasurer->block_id)
            ->with(['student', 'payments'])
            ->orderBy('name')
            ->get();
        
        // Get active fee schedule
        $activeFeeSchedule = FeeSchedule::active()->first();
        
        // Calculate payment status for each student
        $studentsData = $students->map(function($user) use ($activeFeeSchedule) {
            $totalPaid = 0;
            $balance = 0;
            
            if ($activeFeeSchedule && $user->student) {
                $totalPaid = Payment::where('student_id', $user->id)
                    ->where('fee_schedule_id', $activeFeeSchedule->id)
                    ->where('status', 'paid')
                    ->sum('amount');
                
                $balance = $activeFeeSchedule->amount - $totalPaid;
            }
            
            return [
                'user' => $user,
                'total_paid' => $totalPaid,
                'balance' => $balance,
                'status' => $balance <= 0 ? 'paid' : 'pending',
            ];
        });
        
        $stats = [
            'total_students' => $students->count(),
            'fully_paid' => $studentsData->where('status', 'paid')->count(),
            'with_balance' => $studentsData->where('status', 'pending')->count(),
            'total_collected' => $studentsData->sum('total_paid'),
        ];

        return view('treasurer.students.index', compact('studentsData', 'stats', 'activeFeeSchedule'));
    }
}
