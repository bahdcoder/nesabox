<?php

namespace App\Jobs\Sites;

use App\Site;
use Exception;
use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\Sites\SiteUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Scripts\Sites\UninstallGhost as UninstallGhostScript;

class UninstallGhost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 3600;

    /**
     * The server to ssh into
     *
     * @var \App\Server
     */
    public $server;

    /**
     * The site on which we're installing ghost
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
        $process = (new UninstallGhostScript(
            $this->server,
            $this->site
        ))->run();

        if ($process->isSuccessful()) {
            $this->site->update([
                'installing_ghost_status' => null,
                'app_type' => null
            ]);

            $this->server->user->notify(new SiteUpdated($this->server));
        } else {
            // $this->handleFailed();
        }
    }
}
