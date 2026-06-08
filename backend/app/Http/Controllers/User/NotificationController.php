<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\NotificationModel;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = NotificationModel::where('user_id', auth()->id())
            ->with('report')
            ->latest()
            ->paginate(20);

        $unreadCount = auth()->user()->unreadNotificationsCount();

        return view('user.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(NotificationModel $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        if ($notification->report_id) {
            return redirect()->route('user.reports.show', $notification->report_id);
        }

        return redirect()->route('user.notifications');
    }

    public function markAllAsRead()
    {
        NotificationModel::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->route('user.notifications');
    }
}
