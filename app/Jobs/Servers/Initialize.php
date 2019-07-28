<?php

namespace App\Jobs\Servers;

use App\Server;
use Illuminate\Bus\Queueable;
use App\Scripts\Server\VerifyIsReady;
use Illuminate\Queue\SerializesModels;
use App\Scripts\Server\GenerateSshkey;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Exceptions\ServerNotReadyException;
use App\Notifications\Servers\ServerIsReady;
use App\Http\ServerProviders\InteractsWithVultr;
use App\Http\ServerProviders\HasServerProviders;
use App\Http\ServerProviders\InteractWithLinode;
use App\Http\ServerProviders\InteractsWithDigitalOcean;

class Initialize implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        InteractsWithDigitalOcean,
        InteractsWithVultr,
        HasServerProviders,
        InteractWithLinode;

    /**
     * Server to initialize
     *
     * @var \App\Server
     */
    public $server;

    /**
     * Retry job after set seconds
     *
     * @var integer
     */
    public $retryAfter = 120;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 15;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sync();

        $this->server->user->notify(new ServerIsReady($this->server));
    }

    /**
     *
     * Handle server initialization
     *
     *
     */
    public function handleInitializedServer()
    {
        $process = (new VerifyIsReady($this->server))->as(SSH_USER)->run();

        if ($process->isSuccessful()) {
            $generateKeyProcess = (new GenerateSshkey($this->server))->run();

            if ($generateKeyProcess->isSuccessful()) {
                $key = explode('ssh-rsa', $generateKeyProcess->getOutput())[1];

                $key = trim("ssh-rsa{$key}");

                return $this->server->update([
                    'ssh_key' => $key,
                    'status' => STATUS_ACTIVE
                ]);
            }
        }

        throw new ServerNotReadyException('Failed verify is ready script.');
    }

    /**
     * Sync the details of a server from the provider.
     *
     * @return Illuminate\Http\Response
     */
    public function sync()
    {
        $server = $this->server;

        switch ($server->provider) {
            case DIGITAL_OCEAN:
                if ($server->status === STATUS_ACTIVE) {
                    return;
                }

                if ($server->status === STATUS_INITIALIZING) {
                    return $this->handleInitializedServer($server);
                }

                $droplet = $this->getDigitalOceanDroplet(
                    $server->identifier,
                    $server->user
                );

                if ($droplet->status === 'active') {
                    $server->update([
                        'ip_address' => $droplet->networks->v4[0]->ip_address,
                        'status' => STATUS_INITIALIZING
                    ]);

                    return $this->handleInitializedServer($server);
                } else {
                    throw new ServerNotReadyException(
                        'Droplet not yet active.'
                    );
                }
            break;
            case VULTR:
                if ($server->status === STATUS_ACTIVE) {
                    return;
                }

                if ($server->status === STATUS_INITIALIZING) {
                    return $this->handleInitializedServer($server);
                }

                $vultrServer = $this->getVultrServer(
                    $server->identifier,
                    $server->user,
                    $server->credential_id
                );

                if ($vultrServer->status === STATUS_ACTIVE) {
                    $server->update([
                        'ip_address' => $vultrServer->main_ip,
                        'status' => STATUS_INITIALIZING
                    ]);

                    return $this->handleInitializedServer($server);
                } else {
                    throw new ServerNotReadyException(
                        'Vultr server not ready.'
                    );
                }
                break;
            case CUSTOM_PROVIDER:
                if ($server->status === STATUS_ACTIVE) {
                    return;
                }

                return $this->handleInitializedServer($server);
                break;
            case LINODE:
                if ($server->status === STATUS_ACTIVE) {
                    return;
                }

                if ($server->status === STATUS_INITIALIZING) {
                    return $this->handleInitializedServer($server);
                }

                $linode = $this->getLinode(
                    $server->identifier,
                    $server->user,
                    $server->credential_id
                );

                if ($linode->status === 'running') {
                    $server->update([
                        'status' => STATUS_INITIALIZING
                    ]);

                    return $this->handleInitializedServer($server);
                }

                return $this->handleInitializedServer($server);
                break;
        }
    }
}
