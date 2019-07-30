<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\DatabaseUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Jobs\Sites\InstallGhost;

class GhostController extends Controller
{
    /**
     * Install ghost blog on a site
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Server $server, Site $site)
    {
        $this->authorize('view', $server);

        $site->update([
            'installing_ghost_status' => STATUS_INSTALLING
        ]);

        // Create a ghost database user and database
        $databaseUser = DatabaseUser::create([
            'type' => MYSQL_DB,
            'server_id' => $server->id,
            'password' => str_random(12),
            'name' => str_slug("ghost-user-{$site->slug}", '_')
        ]);

        $database = $databaseUser->databases()->create([
            'type' => MYSQL_DB,
            'server_id' => $server->id,
            'name' => str_slug("ghost-db-{$site->slug}", '_')
        ]);

        InstallGhost::dispatch($server, $site, $databaseUser, $database);

        return new ServerResource($server);
    }
}
