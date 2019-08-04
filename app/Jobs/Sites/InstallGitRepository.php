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
use App\Http\SourceControlProviders\InteractsWithGithub;

class InstallGitRepository implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        HandlesProcesses,
        InteractsWithGithub;

    /**
     * Site on which we want to install repository
     *
     * @var Site
     */
    public $site;

    /**
     * Server on which site exists
     *
     * @var Server
     */
    public $server;

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
        if (
            !$this->server->ssh_key_added_to_source_provider[
                $this->site->repository_provider
            ]
        ) {
            $this->addPublicKey(
                $this->site->repository_provider,
                $this->server,
                $this->server->ssh_key
            );

            $updates = [];
            $updates[$this->site->repository_provider] = true;

            $this->server->update([
                'ssh_key_added_to_source_provider' => array_merge(
                    $this->server->ssh_key_added_to_source_provider,
                    $updates
                )
            ]);
        }

        $process = $this->runInstallGitRepositoryScript(
            $this->server->fresh(),
            $this->site
        );

        if ($process->isSuccessful()) {
            $this->site->update([
                'repository_status' => STATUS_ACTIVE
            ]);
        }

        $this->server->user->notify(new ServerIsReady($this->server->fresh()));
    }

    /**
     * Add public key to source code provider
     *
     * @return void
     */
    public function addPublicKey(string $provider, Server $server, string $key)
    {
        switch ($provider) {
            case 'github':
                return $this->addGithubPublicKey(
                    config('app.name') . " - {$server->name}",
                    $key,
                    $this->server->user->source_control['github']
                );
            default:
                break;
        }
    }
}
