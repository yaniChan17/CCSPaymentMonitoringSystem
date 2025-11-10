<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->notifications();

        // Apply filter
        $filter = $request->get('filter', 'all');
        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);
        $allCount = auth()->user()->notifications()->count();

        return view('notifications.index', compact('notifications', 'allCount'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function unreadCount()
    {
        $count = auth()->user()->notifications()->unread()->count();

        return response()->json(['count' => $count]);
    }
}
