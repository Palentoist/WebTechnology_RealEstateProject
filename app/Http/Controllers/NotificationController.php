<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = \App\Models\ActivityLog::findOrFail($id);

        // Mark the notification as read by the current user
        $notification->markAsRead(auth()->id());

        // Get the updated unread count
        $unreadCount = \App\Models\ActivityLog::unread()->count();

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
        ]);
    }
}
