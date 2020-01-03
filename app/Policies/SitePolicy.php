<?php

namespace App\Policies;

use App\Server;
use App\Site;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SitePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function belongsToServer(User $ser, Site $site, Server $server)
    {
        return $site->server_id === $server->id;
    }
}
