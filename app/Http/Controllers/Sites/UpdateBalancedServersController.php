<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\Http\Controllers\Controller;
use App\Scripts\Sites\UpdateBalancedServers;
use App\Http\Requests\Sites\UpdateBalancedServersRequest;
use App\Http\Resources\ServerResource;

class UpdateBalancedServersController extends Controller
{
    /**
     * This method adds a list of servers to the upstream of a load balancer
     * 1- Fetch all servers to make sure they are available
     * 2 - Make sure all servers are in this server's family
     * 3 - Ensure this server is a load balancer
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(
        UpdateBalancedServersRequest $request,
        Server $server,
        Site $site
    ) {
        $this->authorize('view', $server);
        $this->authorize('loadBalancer', $server);
        $this->authorize('belongsToServer', [$site, $server]);

        $servers = collect();

        foreach ($request->servers as $serverId):
            $servers->push(
                auth()
                    ->user()
                    ->servers()
                    ->where('id', $serverId)
                    ->where('region', $server->region)
                    ->where('provider', $server->provider)
                    ->firstOrFail()
            );
        endforeach;

        $process = (new UpdateBalancedServers($servers, $server, $site))->run();

        if (!$process->isSuccessful()) {
            return abort(400, $process->getErrorOutput());
        }

        $server->balancedServers()->delete();

        $servers->each(function ($balancedServer) use ($server) {
            $server->balancedServers()->create([
                'balanced_server_id' => $balancedServer->id
            ]);
        });

        return response()->json(new ServerResource($server->fresh()));
    }
}
