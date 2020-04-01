<?php

namespace App\Jobs\Servers;

use App\Server;
use App\Database;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Servers\ServerIsReady;
use App\Scripts\Server\AddDatabase as AddDatabaseScript;

class AddDatabase implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        BroadcastServer;

    public $server;

    public $database;

    /**
     *
     * @var \App\DatabaseUser|null
     */
    public $databaseUser;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Server $server,
        Database $database,
        $databaseUser = null
    ) {
        $this->server = $server;

        $this->database = $database;

        $this->databaseUser = $databaseUser;

        $this->onQueue('databases');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AddDatabaseScript(
            $this->server,
            $this->database,
            $this->databaseUser
        ))->run();

        if ($process->isSuccessful()) {
            $this->database->update([
                'status' => STATUS_ACTIVE
            ]);

            if ((bool) $this->databaseUser) {
                $this->databaseUser->update([
                    'status' => STATUS_ACTIVE
                ]);
            }

            $this->server->user->notify(new ServerIsReady($this->server));
        } else {
            $message = "Failed adding database {$this->database->name} on server {$this->server->name}.";

            $this->database->delete();

            if ((bool) $this->databaseUser) {
                $this->databaseUser->delete();
            }

            $this->server->user->notify(new ServerIsReady($this->server));

            $this->alertServer($message, $process->getErrorOutput());
        }
    }
}
