<?php

namespace App\Http\Controllers;

use App\Server;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsController extends Controller
{
    public function index(Server $server)
    {
        return $server->unreadNotifications;
    }

    public function destroy(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        $notification->delete();

        return response()->json([]);
    }
}
