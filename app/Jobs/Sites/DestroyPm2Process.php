<?php

namespace App\Jobs\Sites;

use App\Site;
use App\Server;
use App\Pm2Process;
use App\Scripts\Sites\DeletePm2Process;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DestroyPm2Process implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * The pm2 process to delete
     *
     * @var \App\Pm2Process
     */
    public $pm2Process;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Server $server,
        Site $site,
        Pm2Process $pm2Process
    ) {
        $this->site = $site;
        $this->server = $server;
        $this->pm2Process = $pm2Process;

        $this->onQueue('deletions');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new DeletePm2Process($this->server, $this->site, $this->pm2Process))
            ->as(SSH_USER)
            ->run();

        $this->pm2Process->delete();
    }
}
