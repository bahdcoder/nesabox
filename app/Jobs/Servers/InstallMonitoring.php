<?php

namespace App\Jobs\Servers;

use Illuminate\Bus\Queueable;
use App\Http\Traits\HandlesProcesses;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\ServerProviders\InteractsWithDigitalOcean;
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
        // first, we'll add the dns record to digital ocean
        $this->createMetricsDomainRecord($this->server);
        // next, we'll call the process background job

        $this->server->update([
            'server_monitoring_username' => str_random(12),
            'server_monitoring_password' => str_random(12)
        ]);

        // finally dispatch a notification to the user once its done.
        $process = $this->installServerMonitoring($this->server->fresh());

        if ($process->isSuccessful()) {
            $this->site->update([
                'server_monitoring_status' => STATUS_ACTIVE
            ]);
        }
    }

    public function failed($e)
    {
        echo $e;
    }
}
