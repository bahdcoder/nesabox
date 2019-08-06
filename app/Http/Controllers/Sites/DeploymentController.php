<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\Jobs\Sites\Deploy;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteResource;

class DeploymentController extends Controller
{
    public function http(Site $site)
    {
        if ($site->deploying) {
            return new SiteResource($site->fresh());
        }

        $this->triggerDeployment($site->server, $site);

        return new SiteResource($site->fresh());
    }

    public function triggerDeployment(Server $server, Site $site)
    {
        $this->authorize('view', $site->server);

        $deployment = activity()
            ->causedBy(auth()->user())
            ->performedOn($site)
            ->withProperty('log', '')
            ->withProperty('status', 'pending')
            ->log('Deployment');

        $site->update([
            'deploying' => true
        ]);

        Deploy::dispatch($server, $site, $deployment);
    }

    public function deploy(Server $server, Site $site)
    {
        if ($site->deploying) {
            return new SiteResource($site->fresh());
        }

        $this->triggerDeployment($server, $site);

        return new SiteResource($site->fresh());
    }
}
