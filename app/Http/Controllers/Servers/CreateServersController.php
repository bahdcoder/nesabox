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
                break;
            case CUSTOM_PROVIDER:
                $server = $this->createCustomServer();
                break;
            case LINODE:
                $server = $this->createLinodeServer($request);
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

        $server = auth()
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
                'status' =>
                    $request->provider === CUSTOM_PROVIDER
                        ? STATUS_INITIALIZING
                        : 'new'
            ]);

        $this->createServerDatabases($server);

        if (!in_array($request->provider, [DIGITAL_OCEAN])) {
            $this->generateSshKeyForServer($server);
        }

        return $server;
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

    public function createLinodeServer(CreateServerRequest $request)
    {
        // linode/ubuntu18.04 represents the image prop
        $credential = $this->getAuthUserCredentialsFor(
            LINODE,
            $request->credential_id
        );

        $server = $this->createServerForAuthUser();

        try {
            $linode = $this->getLinodeConnectionInstance(
                $credential->accessToken
            )
                ->linode()
                ->create(
                    $server->name,
                    $server->region,
                    'linode/ubuntu18.04', // This represents the OS - Ubuntu 18.04
                    $server->size, // equivalent to linode types
                    $this->getStackScriptForLinode($server, $credential),
                    str_root_password()
                );

            $server->update([
                'identifier' => $linode->id,
                'ip_address' => $linode->ipv4[0]
            ]);

            return $server;
        } catch (GuzzleException | ProcessFailedException $e) {
            throw new FailedCreatingServer($server, $e);
        }
    }

    /**
     * Create a vultr server
     *
     * @return
     */
    public function createVultrServer(CreateServerRequest $request)
    {
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
        $credential = $this->getAuthUserCredentialsFor(
            DIGITAL_OCEAN,
            $request->credential_id
        );

        $server = $this->createServerForAuthUser();

        try {
            $keyId = $this->getSshKeyForDigitalOcean($server, $credential);

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
                    [$keyId],
                    $this->getUserData($server)
                );

            $server->update([
                'identifier' => $droplet->id
            ]);

            // TODO: Delete the newly created ssh key from digital ocean

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
                    'name' => SSH_USER,
                    'type' => $database,
                    'password' => $password,
                    'status' => STATUS_ACTIVE
                ])
                ->databases()
                ->create([
                    'name' => SSH_USER,
                    'type' => $database,
                    'status' => STATUS_ACTIVE,
                    'server_id' => $server->id
                ]);
        endforeach;
    }
}
