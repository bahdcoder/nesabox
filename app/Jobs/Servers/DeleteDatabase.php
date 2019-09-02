<?php

namespace App\Jobs\Servers;

use App\Server;
use App\Database;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Servers\DatabasesUpdated;
use App\Scripts\Server\DeleteDatabase as AppDeleteDatabase;

class DeleteDatabase implements ShouldQueue
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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AppDeleteDatabase(
            $this->server,
            $this->database
        ))->run();

        if ($process->isSuccessful()) {
            $this->database->delete();
        } else {
            $this->database->update([
                'status' => STATUS_ACTIVE
            ]);

            $this->server->user->notify(new DatabasesUpdated($this->server));

            $this->alertServer(
                "Failed deleting database {$this->database->name} on server {$this->server->name}",
                $process->getErrorOutput()
            );
        }
    }
}
