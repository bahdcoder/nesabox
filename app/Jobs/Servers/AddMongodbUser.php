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
use App\Notifications\Servers\ServerIsReady;
use App\Scripts\Server\AddMongodbUser as AppAddMongodbUser;

class AddMongodbUser implements ShouldQueue
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
        DatabaseUser $user
    ) {
        $this->server = $server;
        $this->database = $database;
        $this->databaseUser = $user;

        $this->onQueue('databases');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AppAddMongodbUser(
            $this->server,
            $this->database,
            $this->databaseUser
        ))->run();

        if ($process->isSuccessful()) {
            $this->databaseUser->update([
                'status' => STATUS_ACTIVE
            ]);

            $this->server->user->notify(new ServerIsReady($this->server));
        } else {
            $message = "Failed creating Mongodb user {$this->databaseUser->name} on database {$this->database->name}.";

            $this->server->user->notify(new ServerIsReady($this->server));

            $this->alertServer($message, $process->getErrorOutput());

            $this->databaseUser->delete();
        }
    }
}
