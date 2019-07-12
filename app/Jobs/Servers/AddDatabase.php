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
use App\Scripts\Server\AddDatabase as AddDatabaseScript;

class AddDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        DatabaseUser $user = null
    ) {
        $this->server = $server;

        $this->database = $database;

        $this->databaseUser = $user;
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
            dd('success', $process->getOutput());
            $this->database->update([
                'status' => STATUS_ACTIVE
            ]);

            if ($this->databaseUser) {
                $this->databaseUser->update([
                    'status' => STATUS_ACTIVE
                ]);
            }
        } else {
            dd('failed', $process->getErrorOutput());
            $this->database->delete();

            if ($this->databaseUser) {
                $this->databaseUser->delete();
            }
        }
    }
}
