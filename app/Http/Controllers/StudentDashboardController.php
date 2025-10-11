<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;

        if (!$student) {
            abort(404, 'Student profile not found.');
        }

        $payments = $student->payments()
            ->orderBy('payment_date', 'desc')
            ->get();

        $stats = [
            'total_fees' => $student->total_fees,
            'total_paid' => $student->total_paid,
            'balance' => $student->balance,
            'payment_count' => $payments->count(),
        ];

        return view('student.dashboard', compact('student', 'payments', 'stats'));
    }
}
