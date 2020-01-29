<?php

namespace App\Http\Controllers\Servers;

use App\Daemon;
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
        // databases and database users
        $server->hasMany(DatabaseUser::class)->delete();
        // sites
        $server->hasMany(Site::class)->delete();
        // sshkeys
        $server->hasMany(Sshkey::class)->delete();
        // daemons
        $server->hasMany(Daemon::class)->delete();
        // cron jobs
        $server->hasMany(Job::class)->delete();
        // firewallRules
        $server->hasMany(FirewallRule::class)->delete();
        // delete ssh keys of server on system
        $this->execProcess("rm ~/.ssh/{$server->slug}");
        $this->execProcess("rm ~/.ssh/{$server->slug}.pub");

        $server->teams()->sync([]);

        $server->delete();

        return response()->json([]);
    }
}
