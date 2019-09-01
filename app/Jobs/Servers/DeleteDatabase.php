<?php

namespace App\Jobs\Servers;

use App\Server;
use App\Database;
use App\Scripts\Server\DeleteDatabase as AppDeleteDatabase;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
    }
}
