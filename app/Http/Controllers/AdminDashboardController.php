<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::where('status', 'active')->count(),
            'total_payments' => Payment::where('status', 'paid')->sum('amount'),
            'total_users' => User::count(),
            'total_balance' => Student::sum('balance'),
        ];

        $recentPayments = Payment::with(['student', 'treasurer'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPayments'));
    }
}
