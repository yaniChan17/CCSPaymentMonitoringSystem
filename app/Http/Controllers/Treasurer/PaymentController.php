<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\FeeSchedule;
use App\Models\Payment;
use App\Models\PaymentAuditLog;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $treasurer = auth()->user();
        
        // Get payments for students in treasurer's block
        $payments = Payment::with(['student', 'feeSchedule'])
            ->whereHas('student', function ($q) use ($treasurer) {
                $q->where('block_id', $treasurer->block_id);
            })
            ->where('recorded_by', $treasurer->id)
            ->latest('recorded_at')
            ->paginate(20);

        return view('treasurer.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $treasurer = auth()->user();
        
        // Get students in treasurer's block only
        $students = User::where('role', 'student')
            ->where('block_id', $treasurer->block_id)
            ->orderBy('name')
            ->get();

        // Get active fee schedules
        $feeSchedules = FeeSchedule::active()->get();

        return view('treasurer.payments.create', compact('students', 'feeSchedules'));
    }

    /**
     * Store a newly created resource in storage - DIRECT POSTING (NO APPROVAL)
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
        if (auth()->user()->block_id !== $student->block_id) {
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
        UserNotification::create([
            'user_id' => $student->id,
            'type' => 'payment_recorded',
            'title' => 'Payment Received',
            'message' => "Your payment of ₱" . number_format($payment->amount, 2) . " has been recorded.",
            'related_id' => $payment->id
        ]);

        return redirect()->route('treasurer.payments.index')->with('success', 'Payment recorded successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $treasurer = auth()->user();

        // Check 24-hour restriction
        $recordedAt = $payment->recorded_at ?? $payment->created_at;
        if (now()->diffInHours($recordedAt) > 24) {
            abort(403, 'You can only edit payments within 24 hours of recording.');
        }

        // Check ownership
        if ($payment->recorded_by !== auth()->id()) {
            abort(403, 'You can only edit your own payments.');
        }

        $students = User::where('role', 'student')
            ->where('block_id', $treasurer->block_id)
            ->orderBy('name')
            ->get();

        $feeSchedules = FeeSchedule::active()->get();

        return view('treasurer.payments.edit', compact('payment', 'students', 'feeSchedules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        // 24-hour edit restriction for treasurers
        $recordedAt = $payment->recorded_at ?? $payment->created_at;
        if (now()->diffInHours($recordedAt) > 24) {
            abort(403, 'You can only edit payments within 24 hours of recording.');
        }

        if ($payment->recorded_by !== auth()->id()) {
            abort(403, 'You can only edit your own payments.');
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
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $treasurer = auth()->user();

        // Ensure payment is from treasurer's block
        if ($payment->student->block_id !== $treasurer->block_id) {
            abort(403);
        }

        $payment->load(['student', 'feeSchedule', 'recorder', 'editor', 'auditLogs.user']);

        return view('treasurer.payments.show', compact('payment'));
    }
}
