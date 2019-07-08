<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\CreateServerRequest;

class CreateServersController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateServerRequest $request)
    {
        switch ($request->provider):
            case 'vultr':
                return $this->createVultrServer($request);
            case 'digital-ocean':
                return $this->createDigitalOceanServer($request);
            default:
                return response()->json([
                    'message' => __('Server created successfully.')
                ]);
        endswitch;
    }

    /**
     * Create a vultr server
     *
     * @return
     */
    public function createVultrServer(CreateServerRequest $request)
    {
        $user = auth()->user();

        dd($request->all());

        $server = $user->servers()->create([
            'name' => $request->name,
            'size' => $request->size,
            'region' => $request->region,
            'provider' => $request->provider,
            'databases' => $request->databases,
            'credential_id' => $request->credential_id || null
        ]);

        $this->createServerDatabases($server);

        $vultrServer = $this->getVultrConnectionInstance(
            $user->getDefaultCredentialsFor('vultr', $request->credential_id)
                ->apiKey
        )
            ->server()
            ->create(
                $server->name,
                $server->region,
                $server->size,
                '270',
                [$this->getSshKeyForVultr($server)],
                $this->getUserDataForVultr($server)
            );

        $server->update([
            'identifier' => $vultrServer->SUBID
        ]);

        return $server;
    }

    /**
     * Create a digital ocean server
     *
     * @return
     */
    public function createDigitalOceanServer(CreateServerRequest $request)
    {
        $user = auth()->user();

        $server = $user->servers()->create([
            'name' => $request->name,
            'size' => $request->size,
            'region' => $request->region,
            'provider' => $request->provider,
            'databases' => $request->databases,
            'credential_id' => $request->credential_id || null
        ]);

        $this->createServerDatabases($server);

        $droplet = $this->getDigitalOceanConnectionInstance(
            $user->getDefaultCredentialsFor(
                'digital-ocean',
                $request->credential_id
            )->apiToken
        )
            ->droplet()
            ->create(
                $server->name,
                $request->region,
                $request->size,
                'ubuntu-18-04-x64',
                false,
                false,
                false,
                [$this->getSshKeyForDigitalOcean($server)],
                $this->getUserData($server)
            );

        $server->update([
            'identifier' => $droplet->id
        ]);

        return $server;
    }

    /**
     * Create the databases for the server
     *
     * @return void
     */
    public function createServerDatabases(Server $server)
    {
        $password = str_random(12);

        foreach ($server->databases as $database):
            $server
                ->databaseUsers()
                ->create([
                    'name' => USER_NAME,
                    'password' => $password,
                    'type' => $database,
                    'status' => 'active'
                ])
                ->databases()
                ->create([
                    'name' => USER_NAME,
                    'type' => $database,
                    'status' => 'active'
                ]);
        endforeach;
    }
}
