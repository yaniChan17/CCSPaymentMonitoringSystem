<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;

class TreasurerDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_collected_today' => Payment::whereDate('created_at', today())
                ->where('status', 'paid')
                ->sum('amount'),
            'payments_today' => Payment::whereDate('created_at', today())->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'active_students' => Student::where('status', 'active')->count(),
        ];

        $recentPayments = Payment::with(['student'])
            ->where('recorded_by', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        return view('treasurer.dashboard', compact('stats', 'recentPayments'));
    }
}
