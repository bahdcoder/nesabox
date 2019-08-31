<?php

namespace App\Jobs\Servers;

use App\Job;
use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Servers\ServerIsReady;
use App\Scripts\Server\AddCronJob as AppAddCronJob;

class AddCronJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        BroadcastServer;

    public $server;

    public $cronJob;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, Job $cronJob)
    {
        $this->cronJob = $cronJob;
        $this->server = $server;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AppAddCronJob($this->server, $this->cronJob))->run();

        if ($process->isSuccessful()) {
            $this->cronJob->update([
                'status' => STATUS_ACTIVE
            ]);

            $this->broadcastServerUpdated();
        } else {
            $this->cronJob->delete();

            $this->broadcastServerUpdated();

            $this->alertServer(
                "Failed adding cron job on server {$this->server->name}. Please view server logs.",
                $process->getErrorOutput()
            );
        }
    }
}
