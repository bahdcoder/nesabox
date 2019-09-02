<?php

namespace App\Jobs\Servers;

use App\Server;
use App\DatabaseUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Servers\DatabasesUpdated;
use App\Scripts\Server\AddDatabaseUser as AppAddDatabaseUser;

class AddDatabaseUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $process = (new AppAddDatabaseUser($this->server, $this->databaseUser))->run();

        if ($process->isSuccessful()) {
            $this->databaseUser->update([
                'status' => STATUS_ACTIVE
            ]);

            $this->server->user->notify(new DatabasesUpdated($this->server));
        } else {
            $message = "Failed adding database user {$this->databaseUser->name} on server {$this->server->name}.";

            $this->databaseUser->delete();

            $this->server->user->notify(new DatabasesUpdated($this->server));

            $this->alertServer($message, $process->getErrorOutput());
        }
    }
}
