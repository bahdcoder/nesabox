<?php

namespace App\Jobs\Servers;

use Illuminate\Bus\Queueable;
use App\Http\Traits\HandlesProcesses;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\ServerProviders\InteractsWithDigitalOcean;
use App\Notifications\Servers\ServerIsReady;
use App\Server;

class InstallMonitoring implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        HandlesProcesses,
        InteractsWithDigitalOcean;

    /**
     * The server to install monitoring on
     *
     * @var Server
     */
    public $server;

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
        // finally dispatch a notification to the user once its done.
        $process = $this->installServerMonitoring($this->server->fresh());

        if ($process->isSuccessful()) {
            $this->server->update([
                'server_monitoring_status' => STATUS_ACTIVE
            ]);
        } else {
            $this->handleFailed();
        }

        $this->server->user->notify(new ServerIsReady($this->server));
    }

    public function handleFailed($error = null)
    {
        $this->server->update([
            'server_monitoring_status' => null,
            'server_monitoring_username' => null,
            'server_monitoring_password' => null
        ]);
    }

    public function failed($e)
    {
        $this->handleFailed();
    }
}
