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
            return response()->json([
                'message' => 'Site is already deploying.'
            ]);
        }

        $site->triggerDeployment();

        $user->notify(new SiteUpdated($site));

        return response()->json([
            'message' => 'Deployment queued.'
        ]);
    }

    public function deploy(Server $server, Site $site)
    {
        $this->authorize('view', $site->server);

        if ($site->deploying) {
            return new SiteResource($site->fresh());
        }

        $site->triggerDeployment();

        return new SiteResource($site->fresh());
    }
}
