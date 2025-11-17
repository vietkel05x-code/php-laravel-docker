<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->with('creator')
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        $userNotification = UserNotification::where('notification_id', $notification->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($userNotification && !$userNotification->read_at) {
            $userNotification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        UserNotification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Đã đánh dấu tất cả là đã đọc!');
    }

    public function count()
    {
        $count = Auth::user()->unreadNotificationsCount();
        return response()->json(['count' => $count]);
    }
}

