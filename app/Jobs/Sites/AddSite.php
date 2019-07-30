<?php

namespace App\Jobs\Sites;

use App\Site;
use App\Server;
use Illuminate\Bus\Queueable;
use App\Http\Traits\HandlesProcesses;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Servers\ServerIsReady;
use App\Http\ServerProviders\InteractsWithDigitalOcean;

class AddSite implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        HandlesProcesses,
        InteractsWithDigitalOcean;

    /**
     * The server to ssh into
     *
     * @var \App\Server
     */
    public $server;

    /**
     * The site we're creatig
     *
     * @var \App\Site
     */
    public $site;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, Site $site)
    {
        $this->site = $site;
        $this->server = $server;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Because we can't escape the $ (in nginx config) properly in the CreateSiteScript, we'll use
        // a manual file based script for this one.
        $process = $this->runCreateSiteScript($this->server, $this->site);

        if (!$process->isSuccessful()) {
            $this->site->delete();

            abort(400, $process->getErrorOutput());
        }

        $this->site->update([
            'environment' => [
                'PORT' => $process->getOutput()
            ],
            'status' => STATUS_ACTIVE
        ]);

        $this->createDomainRecord($this->site);

        $this->server->user->notify(new ServerIsReady($this->server));
    }

    public function failed($e)
    {
        echo $e;
        dd($e);
    }
}
