<?php

namespace App\Jobs\Servers;

use App\Server;
use App\Database;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Scripts\Server\DeleteMongodbDatabase as AppDeleteMongodbDatabase;

class DeleteMongodbDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BroadcastServer;

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

        $this->onQueue('deletions');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AppDeleteMongodbDatabase($this->server, $this->database))->run();

        if ($process->isSuccessful()) {
            $this->database->databaseUsers()->detach();
            foreach ($this->database->databaseUsers as $databaseUser):
                $databaseUser->delete();
            endforeach;
        } else {
            $this->database->update([
                'status' => STATUS_ACTIVE
            ]);

            $this->alertServer("Failed deleting Mongodb v4.2 database {$this->database->name}.", $process->getOutput());
        }
    }
}
