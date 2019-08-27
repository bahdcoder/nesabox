<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\User;
use App\Server;
use App\Jobs\Sites\Deploy;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteResource;
use App\Notifications\Sites\SiteUpdated;

class DeploymentController extends Controller
{
    public function http(Site $site)
    {
        $token = request()->query('api_token');

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            abort(403, __('Invalid api token.'));
        }

        if ($user->id !== $site->server->user_id) {
            // TODO: Make this available to contributors of teams too

            abort(401, __('Unauthorized.'));
        }

        if ($site->deploying) {
            return new SiteResource($site->fresh());
        }

        $this->triggerDeployment($site->server, $site);

        $user->notify(new SiteUpdated($site));

        return new SiteResource($site->fresh());
    }

    public function triggerDeployment(Server $server, Site $site)
    {
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
        $this->authorize('view', $site->server);

        if ($site->deploying) {
            return new SiteResource($site->fresh());
        }

        $this->triggerDeployment($server, $site);

        return new SiteResource($site->fresh());
    }
}
