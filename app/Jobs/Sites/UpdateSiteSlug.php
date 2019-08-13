<?php

namespace App\Jobs\Sites;

use App\Site;
use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Http\Traits\HandlesProcesses;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Servers\ServerIsReady;
use App\Http\ServerProviders\InteractsWithDigitalOcean;

class UpdateSiteSlug implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        HandlesProcesses,
        InteractsWithDigitalOcean;

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
     * The new slug from request
     *
     * @var string
     */
    public $slug;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, Site $site, string $slug)
    {
        $this->slug = $slug;
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
        $oldSiteSlug = $this->site->slug;

        $this->site->update([
            'slug' => $this->slug
        ]);

        $this->updateDomainRecord($this->site->fresh());

        $process = $this->updateSiteSlug(
            $this->server,
            $this->site->fresh(),
            $this->slug,
            $oldSiteSlug
        );

        if ($process->isSuccessful()) {
            $this->site->update([
                'slug' => $this->slug,
                'updating_slug_status' => null
            ]);
        } else {
            echo $process->getErrorOutput();
            $this->site->update([
                'slug' => $oldSiteSlug,
                'updating_slug_status' => null
            ]);

            $this->updateDomainRecord($this->site->fresh());
        }

        $this->server->user->notify(new ServerIsReady($this->server));
    }

    public function failed($e)
    {
        echo $e;
    }
}
