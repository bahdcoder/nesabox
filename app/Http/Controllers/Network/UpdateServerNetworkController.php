<?php

namespace App\Http\Controllers\Network;

use App\FriendServer;
use App\Server;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Scripts\Server\UpdateServerNetwork;
use App\Http\Requests\Network\UpdateServerNetworkRequest;
use Illuminate\Support\Facades\Log;

class UpdateServerNetworkController extends Controller
{
    /**
     * This method receives a list of servers.
     *
     * It opens up a bunch of its ports to all the selected servers.
     *
     * First this method runs a script that closes
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(
        UpdateServerNetworkRequest $request,
        Server $server
    ) {
        $this->authorize('view', $server);

        $servers = collect();
        $serversToDelete = collect();

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

        foreach ($server->friendServers as $friendServer):
            $serversToDelete->push(
                collect(
                    Server::find($friendServer->friend_server_id)->toArray()
                )->merge(collect($friendServer->toArray()))
            );
        endforeach;

        $process = (new UpdateServerNetwork(
            $servers,
            $serversToDelete,
            $server,
            $request->ports
        ))->run();

        if (!$process->isSuccessful()) {
            return abort(400, $process->getErrorOutput());
        }

        $server->friendServers()->delete();

        $servers->each(function ($friendServer) use ($server, $request) {
            $server->friendServers()->create([
                'friend_server_id' => $friendServer->id,
                'ports' => implode(',', $request->ports)
            ]);
        });

        return new ServerResource($server->fresh());
    }
}
