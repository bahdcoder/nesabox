<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Notifications\Servers\ServerIsReady;

class InitializationCallbackController extends Controller
{
    /**
     * This endpoint is triggered from a server after installation/initialization is complete.
     * 
     */
    public function callback(Server $server) {
        $server->update([
            'ssh_key' => request()->all()['ssh_key'],
            'status' => STATUS_ACTIVE
        ]);

        $server->user->notify(new ServerIsReady($server->fresh()));

        return response()->json([
            'message' => 'Cheers ! Server marked as active.'
        ]);
    }
}
