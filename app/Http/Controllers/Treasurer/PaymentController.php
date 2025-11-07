<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\FeeSchedule;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\PaymentAuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments recorded by this treasurer.
     */
    public function index()
    {
        $treasurer = auth()->user();
        
        $payments = Payment::with(['student', 'feeSchedule'])
            ->whereHas('student', function($query) use ($treasurer) {
                $query->where('block_id', $treasurer->block_id);
            })
            ->latest('recorded_at')
            ->paginate(20);

        return view('treasurer.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request)
    {
        $treasurer = auth()->user();
        $feeSchedules = FeeSchedule::active()->get();
        
        // Get students in treasurer's block
        $students = User::where('role', 'student')
            ->where('block_id', $treasurer->block_id)
            ->orderBy('name')
            ->get();

        // If student_id is provided in query, preselect that student
        $selectedStudentId = $request->query('student_id');

        return view('treasurer.payments.create', compact('feeSchedules', 'students', 'selectedStudentId'));
    }

    /**
     * Store a newly created payment (DIRECT POSTING - NO APPROVAL).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'fee_schedule_id' => 'required|exists:fee_schedules,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,check,bank_transfer,online',
            'payment_date' => 'required|date|before_or_equal:today',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        // Block restriction for treasurer
        $student = User::findOrFail($validated['student_id']);
        if (auth()->user()->role === 'treasurer' && auth()->user()->block_id !== $student->block_id) {
            abort(403, 'You can only record payments for students in your assigned block.');
        }

        // Check for duplicates
        $duplicate = Payment::where('student_id', $validated['student_id'])
            ->where('amount', $validated['amount'])
            ->whereDate('payment_date', $validated['payment_date'])
            ->exists();

        if ($duplicate) {
            return back()->withErrors(['error' => 'A similar payment already exists for today.'])->withInput();
        }

        // Check if late
        $feeSchedule = FeeSchedule::findOrFail($validated['fee_schedule_id']);
        $isLate = now()->gt($feeSchedule->due_date);

        // Create payment with PAID status immediately (NO APPROVAL)
        $payment = Payment::create([
            'student_id' => $validated['student_id'],
            'fee_schedule_id' => $validated['fee_schedule_id'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_date' => $validated['payment_date'],
            'reference_number' => $validated['reference_number'],
            'notes' => $validated['notes'],
            'status' => 'paid', // ← DIRECT POSTING (not 'pending')
            'recorded_by' => auth()->id(),
            'is_late' => $isLate,
            'recorded_at' => now()
        ]);

        // Audit log
        PaymentAuditLog::create([
            'payment_id' => $payment->id,
            'action' => 'created',
            'user_id' => auth()->id(),
            'new_values' => $payment->toArray()
        ]);

        // Notify student
        Notification::create([
            'user_id' => $student->id,
            'type' => 'payment_recorded',
            'title' => 'Payment Received',
            'message' => "Your payment of ₱" . number_format($payment->amount, 2) . " has been recorded.",
            'related_id' => $payment->id
        ]);

        return redirect()->route('treasurer.payments.index')->with('success', 'Payment recorded successfully!');
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        // Ensure treasurer can only view payments from their block
        if (auth()->user()->block_id !== $payment->student->block_id) {
            abort(403, 'You can only view payments from your assigned block.');
        }

        $payment->load(['student', 'feeSchedule', 'recorder', 'editor', 'auditLogs.user']);

        return view('treasurer.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        // Ensure treasurer can only edit payments from their block
        if (auth()->user()->block_id !== $payment->student->block_id) {
            abort(403, 'You can only edit payments from your assigned block.');
        }

        // 24-hour edit restriction
        $recordedAt = $payment->recorded_at ?? $payment->created_at;
        if (now()->diffInHours($recordedAt) > 24) {
            abort(403, 'You can only edit payments within 24 hours of recording.');
        }

        if ($payment->recorded_by !== auth()->id()) {
            abort(403, 'You can only edit your own payments.');
        }

        $feeSchedules = FeeSchedule::active()->get();

        return view('treasurer.payments.edit', compact('payment', 'feeSchedules'));
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, Payment $payment)
    {
        // 24-hour edit restriction for treasurers
        if (auth()->user()->role === 'treasurer') {
            $recordedAt = $payment->recorded_at ?? $payment->created_at;
            if (now()->diffInHours($recordedAt) > 24) {
                abort(403, 'You can only edit payments within 24 hours of recording.');
            }

            if ($payment->recorded_by !== auth()->id()) {
                abort(403, 'You can only edit your own payments.');
            }

            // Ensure treasurer can only edit payments from their block
            if (auth()->user()->block_id !== $payment->student->block_id) {
                abort(403, 'You can only edit payments from your assigned block.');
            }
        }

        // Store old values for audit
        $oldValues = $payment->toArray();

        // Validate and update
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,check,bank_transfer,online',
            'payment_date' => 'required|date|before_or_equal:today',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $payment->update(array_merge($validated, [
            'edited_by' => auth()->id(),
            'edited_at' => now()
        ]));

        // Audit log
        PaymentAuditLog::create([
            'payment_id' => $payment->id,
            'action' => 'updated',
            'user_id' => auth()->id(),
            'old_values' => $oldValues,
            'new_values' => $payment->fresh()->toArray()
        ]);

        return redirect()->route('treasurer.payments.index')->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        // Only allow deletion by admin or within 24 hours by treasurer who created it
        if (auth()->user()->role === 'treasurer') {
            $recordedAt = $payment->recorded_at ?? $payment->created_at;
            if (now()->diffInHours($recordedAt) > 24) {
                abort(403, 'You can only delete payments within 24 hours of recording.');
            }

            if ($payment->recorded_by !== auth()->id()) {
                abort(403, 'You can only delete your own payments.');
            }

            if (auth()->user()->block_id !== $payment->student->block_id) {
                abort(403, 'You can only delete payments from your assigned block.');
            }
        }

        // Store for audit log before deletion
        $oldValues = $payment->toArray();

        // Audit log
        PaymentAuditLog::create([
            'payment_id' => $payment->id,
            'action' => 'deleted',
            'user_id' => auth()->id(),
            'old_values' => $oldValues,
            'new_values' => null
        ]);

        $payment->delete();

        return redirect()->route('treasurer.payments.index')->with('success', 'Payment deleted successfully!');
    }
}
