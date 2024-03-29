<?php

namespace App\Http\Controllers\Servers;

use App\Sshkey;
use App\Server;
use Illuminate\Support\Str;
use App\Jobs\Servers\Initialize;
use App\Http\Controllers\Controller;
use App\Notifications\Servers\Alert;
use App\Http\Resources\ServerResource;
use GuzzleHttp\Exception\GuzzleException;
use App\Exceptions\FailedToCreateServerException;
use App\Http\Requests\Servers\CreateServerRequest;
use Symfony\Component\Process\Exception\ProcessFailedException;

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

        $this->createServerDatabases($server);

        $this->copyUserSshkeysToServer($server);

        if (!$server) {
            return response()->json(
                [
                    'message' => __('Failed creating server.')
                ],
                400
            );
        }

        Initialize::dispatch($server)->delay(now()->addSeconds(60));

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
                'provider' => $request->provider,
                'sudo_password' => str_random(12),
                'databases' => $request->databases,
                'ip_address' => $request->ip_address,
                'type' => $request->type ?: 'default',
                'credential_id' => $request->credential_id,
                'private_ip_address' => $request->private_ip_address,
                'region' => get_region_name(
                    $request->provider,
                    $request->region
                ),
                'status' =>
                    $request->provider === CUSTOM_PROVIDER
                        ? STATUS_INITIALIZING
                        : 'new'
            ]);

        collect([
            [
                'name' => 'SSH',
                'port' => 22,
                'from' => 'Any',
                'status' => STATUS_ACTIVE
            ],
            [
                'name' => 'HTTP',
                'port' => 80,
                'from' => 'Any',
                'status' => STATUS_ACTIVE
            ],
            [
                'name' => 'HTTPS',
                'port' => 443,
                'from' => 'Any',
                'status' => STATUS_ACTIVE
            ]
        ])->each(function ($rule) use ($server) {
            $server->firewallRules()->create($rule);
        });

        $server->rollServerSlug();

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

        $this->generateSshKeyForServer($server);

        $rootPassword = str_root_password();

        $server->update([
            'root_password' => $rootPassword
        ]);

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
                    $rootPassword
                );

            $server->update([
                'identifier' => $linode->id,
                'ip_address' => $linode->ipv4[0]
            ]);

            return $server;
        } catch (GuzzleException | ProcessFailedException $e) {
            throw new FailedToCreateServerException($server, null);
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

        try {
            $vultrServer = $this->getVultrConnectionInstance(
                $credential->apiKey
            )
                ->server()
                ->create(
                    $server->name,
                    $request->region,
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
            throw new FailedToCreateServerException($server, null);
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
                    true,
                    [$keyId],
                    $this->getUserData($server)
                );

            $server->update([
                'identifier' => $droplet->id
            ]);

            // TODO: Delete the newly created ssh key from digital ocean

            return $server;
        } catch (ProcessFailedException $e) {
            $server->delete();

            abort(
                400,
                "Failed creating server. This is most likely an error from our servers. Please shoot us a mail immediately and we'll resolve this for you."
            );
        } catch (GuzzleException $e) {
            $server->delete();

            abort(
                400,
                "We couldn't create your server. This is most likely because your credentials have expired or do not have write access. Please update your credentials and try again."
            );
        }
    }

    public function copyUserSshkeysToServer(Server $server)
    {
        Sshkey::insert(
            $server->user->sshkeys
                ->map(function ($key) use ($server) {
                    return [
                        'server_id' => $server->id,
                        'name' => $key->name,
                        'status' => $key->status,
                        'key' => $key->key,
                        'is_app_key' => false,
                        'id' => Str::uuid()
                    ];
                })
                ->toArray()
        );
    }

    /**
     * Create the databases for the server
     *
     * @return void
     */
    public function createServerDatabases(Server $server)
    {
        foreach ($server->databases as $database):
            if ($database === MONGO_DB) {
                $rootPassword = str_random(32);

                $server->update([
                    'mongodb_admin_password' => $rootPassword
                ]);
            }

            if ($database === MYSQL8_DB) {
                $rootPassword = str_random(32);

                $server->update([
                    'mysql8_root_password' => $rootPassword
                ]);
            }

            if ($database === MARIA_DB) {
                $rootPassword = str_random(32);

                $server->update([
                    'mariadb_root_password' => $rootPassword
                ]);
            }

            if ($database === MYSQL_DB) {
                $rootPassword = str_random(32);

                $server->update([
                    'mysql_root_password' => $rootPassword
                ]);
            }

            if ($database === POSTGRES_DB) {
                $rootPassword = str_random(32);

                $server->update([
                    'postgres_root_password' => $rootPassword
                ]);
            }
        endforeach;
    }
}
