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

        $deployment = activity()
            ->causedBy(auth()->user())
            ->performedOn($site)
            ->withProperty('log', '')
            ->withProperty('status', 'pending')
            ->log('Deployment');

        if ($site->deploying) {
            return new SiteResource($site->fresh());
        }
        
        $site->update([
            'deploying' => true
        ]);

        Deploy::dispatch($server, $site, $deployment);

        return new SiteResource($site->fresh());
    }
}
