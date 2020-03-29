<?php

namespace App\Jobs\Servers;

use App\DatabaseUser;
use App\Notifications\Servers\ServerIsReady;
use App\Scripts\Server\DeleteDatabaseUser as AppDeleteDatabaseUser;
use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteDatabaseUser implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        BroadcastServer;

    public $server;

    public $databaseUser;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, DatabaseUser $databaseUser)
    {
        $this->server = $server;
        $this->databaseUser = $databaseUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AppDeleteDatabaseUser(
            $this->server,
            $this->databaseUser
        ))->run();

        if ($process->isSuccessful()) {
            $this->databaseUser->delete();
        } else {
            $this->databaseUser->update([
                'status' => STATUS_ACTIVE
            ]);

            $this->alertServer(
                "Failed to delete database user {$this->databaseUser->name} on server {$this->server->name}.",
                $process->getErrorOutput()
            );

            $this->server->user->notify(new ServerIsReady($this->server));
        }
    }
}
