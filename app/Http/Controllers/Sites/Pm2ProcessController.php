<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\Pm2Process;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sites\AddPm2ProcessRequest;
use App\Http\Resources\SiteResource;
use App\Jobs\Sites\DestroyPm2Process;

class Pm2ProcessController extends Controller
{
    public function store(
        Server $server,
        Site $site,
        AddPm2ProcessRequest $request
    ) {
        $this->authorize('view', $server);

        $user = SSH_USER;

        $slug = strtolower(str_slug($request->name . '-' . str_random(3)));

        $site->pm2Processes()->create([
            'slug' => $slug,
            'name' => $request->name,
            'status' => STATUS_ACTIVE,
            'command' => $request->command,
            'logs_path' => "/home/{$user}/.pm2/logs/{$slug}"
        ]);

        return new SiteResource($site->fresh());
    }

    public function destroy(Server $server, Site $site, Pm2Process $pm2Process)
    {
        $pm2Process->update([
            'status' => STATUS_DELETING
        ]);

        if ($site->pm2Processes()->first() && $pm2Process->id === $site->pm2Processes()->first()->id) abort(400, __('Cannot delete the main web process.'));

        DestroyPm2Process::dispatch($server, $site, $pm2Process);

        return new SiteResource($site->refresh());
    }
}
