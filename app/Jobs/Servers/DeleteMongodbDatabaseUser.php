<?php

namespace App\Jobs\Servers;

use App\Server;
use App\Database;
use App\DatabaseUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Servers\DatabasesUpdated;
use App\Scripts\Server\DeleteMongodbDatabaseUser as AppDeleteMongodbDatabaseUser;

class DeleteMongodbDatabaseUser implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        BroadcastServer;

    public $server;

    public $database;

    public $databaseUser;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Server $server,
        Database $database,
        DatabaseUser $databaseUser
    ) {
        $this->server = $server;
        $this->database = $database;
        $this->databaseUser = $databaseUser;

        $this->onQueue('deletions');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AppDeleteMongodbDatabaseUser(
            $this->server,
            $this->database,
            $this->databaseUser
        ))->run();

        if ($process->isSuccessful()) {
            $this->databaseUser->delete();
        } else {
            $this->databaseUser->update([
                'status' => STATUS_ACTIVE
            ]);

            $this->server->user->notify(new DatabasesUpdated($this->server));

            $this->alertServer(
                "Failed deleting user {$this->databaseUser->name} from database {$this->database->name} on server : {$this->server->name}",
                $process->getOutput()
            );
        }
    }
}
