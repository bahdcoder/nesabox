<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\CreateServerRequest;
use GuzzleHttp\Exception\GuzzleException;
use App\Http\Resources\ServerResource;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Exceptions\InvalidProviderCredentials;
use App\Exceptions\FailedCreatingServer;

class CreateServersController extends Controller
{
    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateServerRequest $request)
    {
        switch ($request->provider):
            case VULTR:
                $server = $this->createVultrServer($request);
                break;
            case DIGITAL_OCEAN:
                $server = $this->createDigitalOceanServer($request);
            case CUSTOM_PROVIDER:
                $server = $this->createCustomServer();
                break;
        endswitch;

        if (!$server) {
            return response()->json(
                [
                    'message' => __('Failed creating digital ocean server.')
                ],
                400
            );
        }

        return new ServerResource($server);
    }

    /**
     *
     * Create a server for user using relationship
     *
     * @return \App\Server
     */
    public function createServerForAuthUser()
    {
        $request = request();

        return auth()
            ->user()
            ->servers()
            ->create([
                'name' => $request->name,
                'size' => $request->size,
                'region' => $request->region,
                'provider' => $request->provider,
                'databases' => $request->databases,
                'ip_address' => $request->ip_address,
                'credential_id' => $request->credential_id,
                'private_ip_address' => $request->private_ip_address,
                'status' => $request->provider === CUSTOM_PROVIDER ? 'initializing' : 'new',
            ]);
    }

    /**
     * Create a custom server
     *
     * @return \App\Server|boolean
     */
    public function createCustomServer()
    {
        $server = $this->createServerForAuthUser();

        $this->createServerDatabases($server);

        $this->generateSshKeyForServer($server);

        return $server;
    }

    /**
     * Create a vultr server
     *
     * @return
     */
    public function createVultrServer(CreateServerRequest $request)
    {
        $user = auth()->user();

        $credential = $this->getAuthUserCredentialsFor(
            VULTR,
            $request->credential_id
        );

        $server = $this->createServerForAuthUser();

        $this->createServerDatabases($server);

        try {
            $vultrServer = $this->getVultrConnectionInstance(
                $credential->apiKey
            )
                ->server()
                ->create(
                    $server->name,
                    $server->region,
                    $server->size,
                    '270', // This represents the OS - Ubuntu 18.04
                    [$this->getSshKeyForVultr($server, $credential)],
                    $this->getUserDataForVultr($server, $credential)
                );

            $server->update([
                'identifier' => $vultrServer->SUBID
            ]);

            return $server;
        } catch (GuzzleException | ProcessFailedException $e) {
            throw new FailedCreatingServer($server, $e);
        }
    }

    /**
     * Create a digital ocean server
     *
     * @return
     */
    public function createDigitalOceanServer(CreateServerRequest $request)
    {
        $user = auth()->user();

        $credential = $this->getAuthUserCredentialsFor(
            DIGITAL_OCEAN,
            $request->credential_id
        );

        $server = $user->servers()->create([
            'node_version' => 'node',
            'name' => $request->name,
            'size' => $request->size,
            'region' => $request->region,
            'provider' => DIGITAL_OCEAN,
            'databases' => $request->databases,
            'credential_id' => $credential->id
        ]);

        $this->createServerDatabases($server);

        try {
            $droplet = $this->getDigitalOceanConnectionInstance(
                $credential->apiToken
            )
                ->droplet()
                ->create(
                    $server->name,
                    $request->region,
                    $request->size,
                    DIGITAL_OCEAN_SERVER_TYPE,
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
        } catch (GuzzleException | ProcessFailedException $e) {
            throw new FailedCreatingServer($server, $e);
        }
    }

    /**
     * Create the databases for the server
     *
     * @return void
     */
    public function createServerDatabases(Server $server)
    {
        $password = str_random(36);

        foreach ($server->databases as $database):
            $server
                ->databaseUsers()
                ->create([
                    'name' => USER_NAME,
                    'password' => $password,
                    'type' => $database,
                    'is_ready' => true
                ])
                ->databases()
                ->create([
                    'name' => USER_NAME,
                    'type' => $database,
                    'is_ready' => true
                ]);
        endforeach;
    }
}
