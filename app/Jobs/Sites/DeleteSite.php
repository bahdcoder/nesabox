<?php

namespace App\Jobs\Sites;

use App\Http\SourceControlProviders\InteractsWithGithub;
use App\Http\SourceControlProviders\InteractsWithGitlab;
use App\Site;
use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\Servers\ServerIsReady;
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Scripts\Sites\DeleteSite as AppDeleteSite;

class DeleteSite implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        InteractsWithGithub,
        InteractsWithGitlab;

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
            $this->removeWebhooks();

            $this->site->delete();
        } else {
            $this->site->update([
                'deleting_site' => false
            ]);

            Notification::send(
                $this->server->getAllMembers(),
                new ServerIsReady($this->server)
            );

            $this->server->alert(
                "Failed to delete site {$this->site->name}. View log for more details.",
                $process->getErrorOutput()
            );
        }
    }

    public function removeWebhooks()
    {
        if ($this->site->push_to_deploy) {
            switch ($this->site->repository_provider):
                case 'github':
                    $this->deleteGithubPushWebhook(
                        $this->site,
                        $this->site->server->user
                    );
                default:
                    break;
            endswitch;
        }
    }
}
