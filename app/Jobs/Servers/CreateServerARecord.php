<?php

namespace App\Jobs\Servers;

use App\Server;
use Illuminate\Bus\Queueable;
use App\Http\Traits\HandlesProcesses;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\ServerProviders\InteractsWithDigitalOcean;

class CreateServerARecord implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        InteractsWithDigitalOcean,
        HandlesProcesses;

    /**
     * Server to initialize
     *
     * @var \App\Server
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
        $this->createLoggingDomainRecord($this->server);

        $this->runAddCertificateScript(
            $this->server,
            $this->server->getLogWatcherSiteDomain()
        );
    }
}
