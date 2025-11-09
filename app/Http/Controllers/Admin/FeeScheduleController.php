<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\FeeSchedule;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class FeeScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feeSchedules = FeeSchedule::with(['creator', 'targetBlock'])
            ->latest()
            ->paginate(20);

        return view('admin.fee-schedules.index', compact('feeSchedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $blocks = Block::all();
        return view('admin.fee-schedules.create', compact('blocks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:1st,2nd,Summer',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'late_penalty' => 'nullable|numeric|min:0',
            'allow_partial' => 'boolean',
            'target_program' => 'nullable|string|max:50',
            'target_year' => 'nullable|integer|min:1|max:5',
            'target_block_id' => 'nullable|exists:blocks,id',
            'instructions' => 'nullable|string',
            'status' => 'required|in:draft,active',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['allow_partial'] = $request->has('allow_partial');
        $validated['late_penalty'] = $validated['late_penalty'] ?? 0;

        $feeSchedule = FeeSchedule::create($validated);

        // If status is active, send notifications
        if ($feeSchedule->status === 'active') {
            $this->notifyUsers($feeSchedule);
        }

        return redirect()->route('admin.fee-schedules.index')
            ->with('success', 'Fee schedule created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeSchedule $feeSchedule)
    {
        $blocks = Block::all();
        return view('admin.fee-schedules.edit', compact('feeSchedule', 'blocks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FeeSchedule $feeSchedule)
    {
        // Don't allow editing if locked
        if ($feeSchedule->locked_at) {
            return back()->withErrors(['error' => 'Cannot edit a locked fee schedule.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:1st,2nd,Summer',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'late_penalty' => 'nullable|numeric|min:0',
            'allow_partial' => 'boolean',
            'target_program' => 'nullable|string|max:50',
            'target_year' => 'nullable|integer|min:1|max:5',
            'target_block_id' => 'nullable|exists:blocks,id',
            'instructions' => 'nullable|string',
            'status' => 'required|in:draft,active,closed',
        ]);

        $validated['allow_partial'] = $request->has('allow_partial');
        $validated['late_penalty'] = $validated['late_penalty'] ?? 0;

        $wasActive = $feeSchedule->status === 'active';
        $feeSchedule->update($validated);

        // If status changed to active, send notifications
        if (!$wasActive && $feeSchedule->status === 'active') {
            $this->notifyUsers($feeSchedule);
        }

        return redirect()->route('admin.fee-schedules.index')
            ->with('success', 'Fee schedule updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeSchedule $feeSchedule)
    {
        // Don't allow deletion if there are payments
        if ($feeSchedule->payments()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete fee schedule with existing payments.']);
        }

        $feeSchedule->delete();

        return redirect()->route('admin.fee-schedules.index')
            ->with('success', 'Fee schedule deleted successfully!');
    }

    /**
     * Activate a fee schedule
     */
    public function activate(FeeSchedule $feeSchedule)
    {
        // Deactivate other active schedules first
        FeeSchedule::where('status', 'active')->update(['status' => 'closed']);

        $feeSchedule->update(['status' => 'active']);
        $this->notifyUsers($feeSchedule);

        return redirect()->route('admin.fee-schedules.index')
            ->with('success', 'Fee schedule activated successfully!');
    }

    /**
     * Close a fee schedule
     */
    public function close(FeeSchedule $feeSchedule)
    {
        $feeSchedule->update([
            'status' => 'closed',
            'locked_at' => now()
        ]);

        return redirect()->route('admin.fee-schedules.index')
            ->with('success', 'Fee schedule closed successfully!');
    }

    /**
     * Notify users when fee schedule is activated
     */
    private function notifyUsers(FeeSchedule $feeSchedule)
    {
        $students = User::where('role', 'student')
            ->when($feeSchedule->target_block_id, function ($q) use ($feeSchedule) {
                $q->where('block_id', $feeSchedule->target_block_id);
            })
            ->get();

        foreach ($students as $student) {
            UserNotification::create([
                'user_id' => $student->id,
                'type' => 'fee_posted',
                'title' => 'New Fee Posted',
                'message' => "{$feeSchedule->name} - ₱" . number_format($feeSchedule->amount, 2) . " due on " . $feeSchedule->due_date->format('M d, Y'),
                'related_id' => $feeSchedule->id
            ]);
        }

        // Also notify treasurers
        $treasurers = User::where('role', 'treasurer')
            ->when($feeSchedule->target_block_id, function ($q) use ($feeSchedule) {
                $q->where('block_id', $feeSchedule->target_block_id);
            })
            ->get();

        foreach ($treasurers as $treasurer) {
            UserNotification::create([
                'user_id' => $treasurer->id,
                'type' => 'fee_posted',
                'title' => 'New Fee Schedule Activated',
                'message' => "{$feeSchedule->name} - ₱" . number_format($feeSchedule->amount, 2) . " is now active. Due date: " . $feeSchedule->due_date->format('M d, Y'),
                'related_id' => $feeSchedule->id
            ]);
        }
    }
}
