<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsController extends Controller
{
    public function index()
    {
        return auth()->user()->unreadNotifications;
    }

    public function markAsRead(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return response()->json([]);
    }
}
