<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Http\Controllers\Controller;
use App\Notifications\Servers\ServerIsReady;
use App\Notifications\Servers\ServerProvisioned;
use Illuminate\Support\Facades\Notification;

class InitializationCallbackController extends Controller
{
    /**
     * This endpoint is triggered from a server after installation/initialization is complete.
     *
     */
    public function callback(Server $server)
    {
        $this->authorize('view', $server);

        $server->update([
            'ssh_key' => request()->all()['ssh_key'],
            'status' => STATUS_ACTIVE
        ]);

        $server->user->notify(new ServerProvisioned($server));

        Notification::send(
            $server->getAllMembers(),
            new ServerIsReady($server->fresh())
        );

        return response()->json([
            'message' => 'Great ! Server is now active.'
        ]);
    }
}
