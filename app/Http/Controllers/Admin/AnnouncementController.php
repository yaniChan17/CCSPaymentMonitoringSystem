<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Block;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index()
    {
        $announcements = Announcement::with(['poster', 'targetBlock'])
            ->latest()
            ->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        $blocks = Block::all();
        return view('admin.announcements.create', compact('blocks'));
    }

    /**
     * Store a newly created announcement.
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

        // Send notifications to targeted users
        $this->sendAnnouncementNotifications($announcement);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement posted successfully!');
    }

    /**
     * Remove the specified announcement.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    /**
     * Send notifications for the announcement.
     */
    private function sendAnnouncementNotifications(Announcement $announcement)
    {
        $usersQuery = User::query();

        // Filter by role
        if ($announcement->target_role !== 'all') {
            $usersQuery->where('role', $announcement->target_role);
        }

        // Filter by block
        if ($announcement->target_block_id) {
            $usersQuery->where('block_id', $announcement->target_block_id);
        }

        $users = $usersQuery->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'announcement',
                'title' => $announcement->title,
                'message' => $announcement->message,
                'related_id' => $announcement->id
            ]);
        }
    }
}
