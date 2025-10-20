<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of all payments.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['student', 'recordedBy']);

        // Filter by block
        if ($request->filled('block')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('block', $request->block);
            });
        }

        // Filter by year level
        if ($request->filled('year_level')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('year_level', $request->year_level);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Search by student name or ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'payment_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $payments = $query->paginate(20);

        // Statistics - apply same filters
        $statsQuery = Payment::query();

        if ($request->filled('block')) {
            $statsQuery->whereHas('student', function ($q) use ($request) {
                $q->where('block', $request->block);
            });
        }

        if ($request->filled('year_level')) {
            $statsQuery->whereHas('student', function ($q) use ($request) {
                $q->where('year_level', $request->year_level);
            });
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'total_amount' => (clone $statsQuery)->where('status', 'paid')->sum('amount'),
            'pending_count' => (clone $statsQuery)->where('status', 'pending')->count(),
            'pending_amount' => (clone $statsQuery)->where('status', 'pending')->sum('amount'),
            'today' => (clone $statsQuery)->where('status', 'paid')
                ->whereDate('payment_date', today())
                ->sum('amount'),
        ];

        // Get available blocks and year levels for filters
        $blocks = Student::whereNotNull('block')->distinct()->pluck('block')->sort();
        $yearLevels = Student::distinct()->pluck('year_level')->sort();

        return view('admin.payments.index', compact('payments', 'stats', 'blocks', 'yearLevels'));
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load(['student', 'recordedBy']);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        $payment->load('student');
        $students = Student::where('status', 'active')->orderBy('full_name')->get();
        $treasurers = User::where('role', 'treasurer')->orderBy('name')->get();

        return view('admin.payments.edit', compact('payment', 'students', 'treasurers'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,check,bank_transfer,online'],
            'status' => ['required', 'in:paid,pending,cancelled'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully!');
    }
}
