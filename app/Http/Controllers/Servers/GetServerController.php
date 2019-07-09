<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Http\Requests\Servers\GetServerRequest;

class GetServerController extends Controller
{
    /**
     * Get the details of a server
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Server $server)
    {
        $this->authorize('view', $server);

        return request()->query('sync')
            ? $this->sync($server)
            : $this->serverResource($server);
    }

    /**
     * Return a new server resource
     *
     * @return ServerResource
     */
    public function serverResource($server)
    {
        return new ServerResource($server->fresh());
    }

    public function handleInitializedServer(Server $server)
    {
        $process = $this->verifyServerIsReady($server);

        if ($process->isSuccessful()) {
            $this->generateSshkeyOnServer($server);

            $server->update([
                'status' => 'active',
                'is_ready' => true
            ]);
        }

        return $this->serverResource($server);
    }

    /**
     * Sync the details of a server from the provider.
     *
     * @return Illuminate\Http\Response
     */
    public function sync(Server $server)
    {
        switch ($server->provider) {
            case DIGITAL_OCEAN:
                if ($server->status === 'active') {
                    return $this->serverResource($server);
                }

                if ($server->status === 'initializing') {
                    return $this->handleInitializedServer($server);
                }

                $droplet = $this->getDigitalOceanDroplet($server->identifier);

                if ($droplet->status === 'active') {
                    $server->update([
                        'ip_address' => $droplet->networks->v4[0]->ip_address,
                        'status' => 'initializing'
                    ]);

                    return $this->serverResource($server);
                }

                return $this->serverResource($server);
            case VULTR:
                if ($server->status === 'active') {
                    return $this->serverResource($server);
                }

                if ($server->status === 'initializing') {
                    return $this->handleInitializedServer($server);
                }

                $vultrServer = $this->getVultrServer($server->identifier);

                if ($vultrServer->status === 'active') {
                    $server->update([
                        'ip_address' => $vultrServer->main_ip,
                        'status' => 'initializing'
                    ]);

                    return $this->serverResource($server);
                }

                return $this->serverResource($server);
            default:
                return $this->serverResource($server);
        }
    }
}
