<?php

namespace App\Jobs\Sites;

use App\Site;
use Exception;
use App\Server;
use App\Database;
use App\DatabaseUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Servers\ServerIsReady;
use App\Scripts\Sites\InstallGhost as InstallGhostScript;
use App\Notifications\Sites\SiteUpdated;
use App\Scripts\Server\AddDatabase;

class InstallGhost implements ShouldQueue
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
     * The database user for ghost
     *
     * @var \App\DatabaseUser
     */
    public $databaseUser;

    /**
     * The database for ghost
     *
     * @var \App\Database
     */
    public $database;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Server $server,
        Site $site,
        DatabaseUser $databaseUser,
        Database $database
    ) {
        $this->site = $site;
        $this->server = $server;
        $this->database = $database;
        $this->databaseUser = $databaseUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new AddDatabase(
            $this->server,
            $this->database,
            $this->databaseUser
        ))->run();

        $process = (new InstallGhostScript(
            $this->server,
            $this->site,
            $this->databaseUser,
            $this->database
        ))
            ->as(SSH_USER)
            ->run(function ($log) {
                echo '--------------->' . $log;
                $this->site->update([
                    'logs' => $this->site->logs . $log
                ]);

                $this->server->user->notify(
                    new SiteUpdated($this->site->fresh())
                );
            });

        if ($process->isSuccessful()) {
            $this->site->update([
                'app_type' => 'ghost',
                'status' => STATUS_ACTIVE,
                'installing_ghost_status' => STATUS_ACTIVE
            ]);

            $this->databaseUser->update([
                'status' => STATUS_ACTIVE
            ]);

            $this->server->user->notify(new SiteUpdated($this->site->fresh()));
        } else {
            echo $process->getErrorOutput();
            $this->handleFailed();
        }
    }

    public function handleFailed()
    {
        $this->site->update([
            'logs' => null,
            'app_type' => null,
            'installing_ghost_status' => null
        ]);

        $this->databaseUser->delete();

        $this->database->delete();

        $this->server->user->notify(new SiteUpdated($this->site->fresh()));
    }

    public function failed(Exception $e)
    {
        $this->handleFailed();
    }
}
