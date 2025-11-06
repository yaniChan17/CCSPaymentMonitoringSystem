<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreasurerDashboardController extends Controller
{
    public function index()
    {
        // Get treasurer's assigned block
        $treasurerStudent = Auth::user()->student;
        $assignedBlock = $treasurerStudent ? $treasurerStudent->block : null;
        
        // Filter by block if treasurer has one assigned
        if ($assignedBlock) {
            // Get student IDs from treasurer's block
            $blockStudentIds = Student::where('block', $assignedBlock)->pluck('id')->toArray();
            
            $stats = [
                'total_collected_today' => Payment::whereDate('created_at', today())
                    ->where('status', 'paid')
                    ->whereIn('student_id', $blockStudentIds)
                    ->sum('amount'),
                'payments_today' => Payment::whereDate('created_at', today())
                    ->whereIn('student_id', $blockStudentIds)
                    ->count(),
                'pending_payments' => Payment::where('status', 'pending')
                    ->whereIn('student_id', $blockStudentIds)
                    ->count(),
                'active_students' => Student::where('status', 'active')
                    ->where('block', $assignedBlock)
                    ->count(),
            ];

            $recentPayments = Payment::with(['student'])
                ->whereIn('student_id', $blockStudentIds)
                ->where('recorded_by', Auth::id())
                ->latest()
                ->take(10)
                ->get();
        } else {
            // If no block assigned, show all (admin might not have block)
            $stats = [
                'total_collected_today' => Payment::whereDate('created_at', today())
                    ->where('status', 'paid')
                    ->sum('amount'),
                'payments_today' => Payment::whereDate('created_at', today())->count(),
                'pending_payments' => Payment::where('status', 'pending')->count(),
                'active_students' => Student::where('status', 'active')->count(),
            ];

            $recentPayments = Payment::with(['student'])
                ->where('recorded_by', Auth::id())
                ->latest()
                ->take(10)
                ->get();
        }

        return view('treasurer.dashboard', compact('stats', 'recentPayments', 'assignedBlock'));
    }
}
