<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with(['creator', 'users'])
            ->latest()
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $totalUsers = User::where('role', 'student')->count();
        return view('admin.notifications.form', ['notification' => new Notification(), 'totalUsers' => $totalUsers]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'target' => 'required|in:all,students,admins',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $notification = Notification::create([
                'title' => $validated['title'],
                'body' => $validated['body'],
                'created_by' => auth()->id(),
            ]);

            // Determine target users
            $users = [];
            if ($validated['target'] === 'all') {
                $users = User::pluck('id')->toArray();
            } elseif ($validated['target'] === 'students') {
                $users = User::where('role', 'student')->pluck('id')->toArray();
            } elseif ($validated['target'] === 'admins') {
                $users = User::where('role', 'admin')->pluck('id')->toArray();
            }

            // Attach notification to users
            foreach ($users as $userId) {
                DB::table('user_notifications')->insert([
                    'notification_id' => $notification->id,
                    'user_id' => $userId,
                    'read_at' => null,
                ]);
            }
        });

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Đã gửi thông báo thành công!');
    }

    public function show(Notification $notification)
    {
        $notification->load(['creator', 'users']);
        return view('admin.notifications.show', compact('notification'));
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return back()->with('success', 'Đã xóa thông báo!');
    }
}

