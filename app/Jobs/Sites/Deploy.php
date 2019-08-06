<?php

namespace App\Jobs\Sites;

use App\Site;
use App\Server;
use App\Activity;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Scripts\Sites\DeployGitSite;
use App\Notifications\Sites\SiteUpdated;

class Deploy implements ShouldQueue
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
    public $timeout = 7200;

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
     * The deployment activity instance
     *
     * @var \App\Activity
     */
    public $deployment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Server $server,
        Site $site,
        Activity $deployment
    ) {
        $this->site = $site;
        $this->server = $server;
        $this->deployment = $deployment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new DeployGitSite($this->server, $this->site))
            ->as(SSH_USER)
            ->run(function ($log) {
                $this->deployment->update([
                    'properties->log' =>
                        $this->deployment->properties['log'] . $log
                ]);

                $this->server->user->notify(
                    new SiteUpdated($this->site->fresh())
                );
            });

        $this->site->update([
            'deploying' => false
        ]);

        if ($process->isSuccessful()) {
            $this->deployment->update([
                'properties->status' => 'success'
            ]);
        } else {
            $this->deployment->update([
                'properties->status' => 'failed'
            ]);
        }

        $this->server->user->notify(new SiteUpdated($this->site->fresh()));
    }
}
