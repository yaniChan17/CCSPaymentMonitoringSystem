<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Block;
use App\Models\FeeSchedule;
use App\Models\Notification;
use App\Models\User;
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
            ->paginate(15);

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
            $this->sendActivationNotifications($feeSchedule);
        }

        return redirect()
            ->route('admin.fee-schedules.index')
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

        // If newly activated, send notifications
        if (!$wasActive && $feeSchedule->status === 'active') {
            $this->sendActivationNotifications($feeSchedule);
        }

        return redirect()
            ->route('admin.fee-schedules.index')
            ->with('success', 'Fee schedule updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeSchedule $feeSchedule)
    {
        $feeSchedule->delete();

        return redirect()
            ->route('admin.fee-schedules.index')
            ->with('success', 'Fee schedule deleted successfully!');
    }

    /**
     * Activate a fee schedule.
     */
    public function activate(FeeSchedule $feeSchedule)
    {
        $feeSchedule->update(['status' => 'active']);
        $this->sendActivationNotifications($feeSchedule);

        return redirect()
            ->back()
            ->with('success', 'Fee schedule activated and notifications sent!');
    }

    /**
     * Close a fee schedule.
     */
    public function close(FeeSchedule $feeSchedule)
    {
        $feeSchedule->update([
            'status' => 'closed',
            'locked_at' => now()
        ]);

        return redirect()
            ->back()
            ->with('success', 'Fee schedule closed successfully!');
    }

    /**
     * Send notifications when a fee schedule is activated.
     */
    private function sendActivationNotifications(FeeSchedule $feeSchedule)
    {
        // Get affected students
        $studentsQuery = User::where('role', 'student');

        if ($feeSchedule->target_block_id) {
            $studentsQuery->where('block_id', $feeSchedule->target_block_id);
        }

        $students = $studentsQuery->get();

        foreach ($students as $student) {
            Notification::create([
                'user_id' => $student->id,
                'type' => 'fee_posted',
                'title' => 'New Fee Posted',
                'message' => "{$feeSchedule->name} - ₱" . number_format($feeSchedule->amount, 2) . " due on " . $feeSchedule->due_date->format('M d, Y'),
                'related_id' => $feeSchedule->id
            ]);
        }

        // Notify treasurers
        $treasurersQuery = User::where('role', 'treasurer');

        if ($feeSchedule->target_block_id) {
            $treasurersQuery->where('block_id', $feeSchedule->target_block_id);
        }

        $treasurers = $treasurersQuery->get();

        foreach ($treasurers as $treasurer) {
            Notification::create([
                'user_id' => $treasurer->id,
                'type' => 'fee_posted',
                'title' => 'New Fee Schedule Active',
                'message' => "{$feeSchedule->name} is now active. Start collecting payments!",
                'related_id' => $feeSchedule->id
            ]);
        }
    }
}
