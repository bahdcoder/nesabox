<?php

namespace App\Jobs\Sites;

use App\Site;
use App\Server;
use Illuminate\Bus\Queueable;
use App\Http\Traits\HandlesProcesses;
use App\Notifications\Sites\SiteUpdated;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class SslCertificate implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        HandlesProcesses;

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
        $process = $this->addLetsEncryptCertificate($this->server, $this->site);

        if ($process->isSuccessful()) {
            $this->site->update([
                'installing_certificate_status' => STATUS_ACTIVE
            ]);

            $this->broadcastToUser();
        } else {
            $this->site->update([
                'installing_certificate_status' => null
            ]);

            $this->broadcastToUser();

            $this->server->alert(
                'Failed to install SSL certificate. View log output for more details.',
                $process->getErrorOutput()
            );
        }
    }

    public function broadcastToUser()
    {
        Notification::send(
            $this->server->getAllMembers(),
            new SiteUpdated($this->site)
        );
    }
}
