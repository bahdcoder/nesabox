<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\DatabaseUser;
use App\Jobs\Sites\InstallGhost;
use App\Jobs\Sites\UninstallGhost;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteResource;
use App\Http\Resources\ServerResource;
use App\Scripts\Sites\UpdateGhostConfig;

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

        abort(
            400,
            'Sorry, we are updating some scripts, so this would be available soon.'
        );

        $site->update([
            'logs' => null,
            'app_type' => 'ghost',
            'installing_ghost_status' => STATUS_INSTALLING
        ]);

        // Create a ghost database user and database
        $databaseUser = DatabaseUser::create([
            'type' => MYSQL_DB,
            'server_id' => $server->id,
            'password' => str_random(16),
            'name' => str_random(16)
        ]);

        $database = $databaseUser->databases()->create([
            'type' => MYSQL_DB,
            'server_id' => $server->id,
            'name' => str_random(16),
            'status' => STATUS_ACTIVE
        ]);

        $databaseUser->databases()->attach($database->id);

        InstallGhost::dispatch($server, $site, $databaseUser, $database);

        return new SiteResource($site->fresh());
    }

    /**
     * Uninstall ghost blog from a site
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server, Site $site)
    {
        $this->authorize('view', $server);

        $site->update([
            'installing_ghost_status' => STATUS_UNINSTALLING
        ]);

        UninstallGhost::dispatch($server, $site);

        return new ServerResource($server);
    }

    /**
     * Get the config of a ghost blog
     *
     * @return \Illuminate\Http\Response
     */
    public function getConfig(Server $server, Site $site)
    {
        $user = SSH_USER;

        $pathToConfig = "/home/{$user}/{$site->name}/config.production.json";

        $process = $this->getFileContent($server, $pathToConfig);

        if (!$process->isSuccessFul()) {
            abort(400, $process->getErrorOutput());
        }

        return $process->getOutput();
    }

    /**
     * Set the config of a ghost blog. Also reload pm2 for this site
     *
     * @return \Illuminate\Http\Response
     */
    public function setConfig(Server $server, Site $site)
    {
        $this->authorize('view', $server);

        $process = (new UpdateGhostConfig(
            $server,
            $site,
            request()->configProductionJson
        ))->run();

        if (!$process->isSuccessFul()) {
            abort(400, $process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
