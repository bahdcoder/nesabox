<?php

namespace App\Jobs\Servers;

use App\Daemon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Servers\ServerIsReady;
use App\Scripts\Server\AddDaemon as AddDaemonScript;

class AddDaemon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The daemon to be added
     *
     * @var \App\Daemon
     */
    public $daemon;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Daemon $daemon)
    {
        $this->daemon = $daemon;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AddDaemonScript(
            $this->daemon->server,
            $this->daemon
        ))->run();

        if ($process->isSuccessful()) {
            $this->daemon->update([
                'status' => STATUS_ACTIVE
            ]);
        }

        $this->daemon->server->user->notify(
            new ServerIsReady($this->daemon->server)
        );
    }

    public function failed($e)
    {
        dd($e);
    }
}
