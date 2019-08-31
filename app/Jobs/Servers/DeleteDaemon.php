<?php

namespace App\Jobs\Servers;

use App\Server;
use App\Daemon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Servers\ServerIsReady;
use App\Scripts\Server\DeleteDaemon as AppDeleteDaemon;

class DeleteDaemon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The server.
     *
     * @var \App\Server
     */
    public $server;

    /**
     * The daemon to restart.
     *
     * @var \App\Daemon
     */
    public $daemon;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server, Daemon $daemon)
    {
        $this->daemon = $daemon;
        $this->server = $server;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AppDeleteDaemon($this->server, $this->daemon))->run();

        if ($process->isSuccessful()) {
            $this->daemon->delete();

            $this->broadcastServerUpdated();
        } else {
            $message = "Failed deleting daemon {$this->daemon->command} on server {$this->server->name}.";

            $this->daemon([
                'status' => STATUS_ACTIVE
            ]);

            $this->broadcastServerUpdated();

            $this->alertServer($message, $process->getErrorOutput());
        }
    }
}
