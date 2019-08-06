<?php

namespace App\Http\Controllers\Sites;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Server;
use App\Site;
use App\Http\Resources\SiteResource;

class QuickDeployController extends Controller
{
    public function enable(Server $server, Site $site)
    {
        $this->authorize('view', $server);

        $site->update([
            'quick_deploy' => true,
            'quick_deploy_hook_id' => $this->addGithubPushWebhook(
                $site,
                auth()->user()
            )->id
        ]);

        switch ($site->repository_provider):
            case 'github':
                $site->update([
                    'quick_deploy' => true,
                    'quick_deploy_hook_id' => $this->addGithubPushWebhook(
                        $site,
                        auth()->user()
                    )->id
                ]);
            default:
                break;
        endswitch;

        return new SiteResource($site->fresh());
    }

    public function disable(Server $server, Site $site)
    {
        $this->authorize('view', $server);

        switch ($site->repository_provider):
            case 'github':
                $this->deleteGithubPushWebhook($site, auth()->user());
            default:
                break;
        endswitch;

        $site->update([
            'quick_deploy' => false,
            'quick_deploy_hook_id' => null
        ]);

        return new SiteResource($site->fresh());
    }
}
