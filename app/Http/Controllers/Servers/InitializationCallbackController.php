<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\Servers\ServerIsReady;
use App\Notifications\Servers\ServerProvisioned;

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

        $server->user->notify(new ServerIsReady($server->fresh()));

        return response()->json([
            'message' => 'Great ! Server is now active.'
        ]);
    }
}
