<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function unreadCount()
    {
        $count = Notification::where('user_id', auth()->id())
            ->where('read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function dropdown()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->limit(8)
            ->get();

        $unreadCount = $notifications->where('read', false)->count();

        $html = view('notifications.dropdown', compact('notifications', 'unreadCount'))->render();

        return response()->json(['html' => $html, 'unread_count' => $unreadCount]);
    }

    public function markRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['read' => true]);

        if ($notification->url) {
            return redirect($notification->url);
        }

        return back();
    }

    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('read', false)
            ->update(['read' => true]);

        return back();
    }
}
