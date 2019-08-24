<?php

namespace App\Jobs\Sites;

use App\Site;
use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Scripts\Sites\DeleteSite as AppDeleteSite;

class DeleteSite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

        $this->onQueue('deletions');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AppDeleteSite($this->server, $this->site))->run();

        if ($process->isSuccessful()) {
            $this->site->delete();
        } else {
            $this->site->update([
                'deleting_site' => false
            ]);
            // TODO: Create a server alert asking the user to do some cleanup
        }
    }
}
