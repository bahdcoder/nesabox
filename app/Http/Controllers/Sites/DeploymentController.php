<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteResource;
use App\Notifications\Sites\SiteUpdated;
use Illuminate\Support\Facades\Notification;

class DeploymentController extends Controller
{
    public function http(Site $site)
    {
        $this->authorize('view', $site->server);

        if ($site->deploying) {
            return response()->json([
                'message' => 'Site is already deploying.'
            ]);
        }

        $site->triggerDeployment();

        Notification::send($site->server->getAllMembers(), new SiteUpdated($site));

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

        Notification::send($site->server->getAllMembers(), new SiteUpdated($site));

        $site->triggerDeployment();

        return new SiteResource($site->fresh());
    }
}
