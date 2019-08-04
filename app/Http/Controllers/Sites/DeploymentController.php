<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\Http\Controllers\Controller;
use App\Scripts\Sites\DeployGitSite;
use App\Jobs\Sites\Deploy;
use App\Http\Resources\SiteResource;

class DeploymentController extends Controller
{
    public function http(Site $site)
    {
        $this->authorize('view', $site->server);

        // TODO: Deploy the application here.
    }

    public function deploy(Server $server, Site $site)
    {
        $this->authorize('view', $site->server);

        $process = (new DeployGitSite($server, $site))->generate();

        Deploy::dispatch($server, $site);

        return $process;
        return new SiteResource($site->fresh());
    }
}
