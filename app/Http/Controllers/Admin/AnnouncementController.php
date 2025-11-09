<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Block;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::with(['poster', 'targetBlock'])
            ->latest()
            ->paginate(20);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $blocks = Block::all();
        return view('admin.announcements.create', compact('blocks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_role' => 'required|in:all,student,treasurer,admin',
            'target_block_id' => 'nullable|exists:blocks,id',
        ]);

        $validated['posted_by'] = auth()->id();
        $announcement = Announcement::create($validated);

        // Send notifications to target users
        $users = User::query();

        if ($announcement->target_role !== 'all') {
            $users->where('role', $announcement->target_role);
        }

        if ($announcement->target_block_id) {
            $users->where('block_id', $announcement->target_block_id);
        }

        foreach ($users->get() as $user) {
            UserNotification::create([
                'user_id' => $user->id,
                'type' => 'announcement',
                'title' => 'New Announcement: ' . $announcement->title,
                'message' => $announcement->message,
                'related_id' => $announcement->id
            ]);
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement posted successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }
}
