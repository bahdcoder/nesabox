<?php

namespace App\Http\Controllers\Sites;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\Sites\SiteUpdated;
use App\Site;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class GithubWebhookController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Site $site, Request $request)
    {
        $branchFromRequest = explode('refs/heads/', $request->ref);

        if (
            isset($branchFromRequest) &&
            isset($branchFromRequest[1]) &&
            $branchFromRequest[1] === $site->repository_branch
        ) {
            $site->triggerDeployment();

            Notification::send(
                $site->server->getAllMembers(),
                new SiteUpdated($site)
            );

            return response()->json([
                'message' => 'Deployment triggered.'
            ]);
        }

        return response()->json([
            'message' => 'Event ignored.'
        ]);
    }
}
