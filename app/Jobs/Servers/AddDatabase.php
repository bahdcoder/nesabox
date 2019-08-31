<?php

namespace App\Jobs\Servers;

use App\Server;
use App\Database;
use App\DatabaseUser;
use App\Notifications\Servers\ServerIsReady;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, Database $database)
    {
        $this->server = $server;

        $this->database = $database;

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
            $this->database
        ))->run();

        if ($process->isSuccessful()) {
            $this->database->update([
                'status' => STATUS_ACTIVE
            ]);

            if ($this->database->databaseUser !== STATUS_ACTIVE) {
                $this->database->databaseUser->update([
                    'status' => STATUS_ACTIVE
                ]);
            }

            $this->broadcastServerUpdated();
        } else {
            $message = "Failed adding database {$this->database->name} on server {$this->server->name}.";

            $this->database->delete();

            $user = DatabaseUser::where('name', SSH_USER)
                ->where('server_id', $this->server->id)
                ->first();

            if ($this->database->database_user_id !== $user->id) {
                $this->database->databaseUser->delete();
            }

            $this->broadcastServerUpdated();

            $this->alertServer($message, $process->getErrorOutput());
        }
    }
}
