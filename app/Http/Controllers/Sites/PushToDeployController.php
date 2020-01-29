<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteResource;

class PushToDeployController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Server $server, Site $site)
    {
        $this->authorize('view', $server);

        if ($site->push_to_deploy) {
            return $this->disable($site);
        } else {
            return $this->enable($site);
        }
    }

    public function enable(Site $site)
    {
        switch ($site->repository_provider):
            case 'github':
                $site->update([
                    'push_to_deploy' => true,
                    'push_to_deploy_hook_id' => $this->addGithubPushWebhook(
                        $site,
                        $site->server->user
                    )->id
                ]);
            default:
                break;
        endswitch;

        return new SiteResource($site->fresh());
    }

    public function disable(Site $site)
    {
        switch ($site->repository_provider):
            case 'github':
                $this->deleteGithubPushWebhook($site, $site->server->user);
            default:
                break;
        endswitch;

        $site->update([
            'push_to_deploy' => false,
            'push_to_deploy_hook_id' => null
        ]);

        return new SiteResource($site->fresh());
    }
}
