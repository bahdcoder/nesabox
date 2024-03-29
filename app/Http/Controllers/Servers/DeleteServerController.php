<?php

namespace App\Http\Controllers\Servers;

use App\Daemon;
use App\Database;
use App\DatabaseUser;
use App\FirewallRule;
use App\Server;
use App\Http\Controllers\Controller;
use App\Job;
use App\Site;
use App\Sshkey;

class DeleteServerController extends Controller
{
    public function destroy(Server $server)
    {
        if ($server->user_id !== auth()->user()->id) {
            abort(401, 'You are not authorized to perform this action.');
        }

        $server->explode();

        return response()->json([]);
    }
}
