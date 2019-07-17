<?php

namespace App\Jobs\Sites;

use App\Site;
use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Http\Traits\HandlesProcesses;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Scripts\Sites\CreateSite as CreateSiteScript;

class CreateSite implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        HandlesProcesses;

    /**
     * @var Server
     */
    public $server;

    /**
     * @var Site
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
        $process = (new CreateSiteScript($this->server, $this->site))->run();

        if ($process->isSuccessful()) {
            dd($process->getOutput());
        } else {
            dd($process->getErrorOutput());
        }

        // $this->site->update([
        //     'environment' => [
        //         'PORTS' => explode(
        //             ' ',
        //             trim(preg_replace('/\s+/', ' ', $process->getOutput()))
        //         )
        //     ]
        // ]);

        // $process = $this->runCreateServerScript($this->server, $this->site);

        $this->site->update([
            'status' => STATUS_ACTIVE
        ]);
    }

    public function failed($e)
    {
        dd($e);
    }
}
