<?php

namespace App\Jobs\Servers;

use App\Job;
use App\Notifications\Servers\ServerIsReady;
use App\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Scripts\Server\DeleteCronJob as AppDeleteCronJob;

class DeleteCronJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $server;

    public $cronJob;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, Job $cronJob)
    {
        $this->server = $server;
        $this->cronJob = $cronJob;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AppDeleteCronJob($this->server, $this->cronJob))->run();

        if ($process->isSuccessful()) {
            $this->cronJob->delete();

            $this->broadcastServerUpdated();
        } else {
            $message = "Failed deleting cron job {$this->cronJob->command} on server {$this->server->name}.";

            $this->cronJob->update([
                'status' => STATUS_ACTIVE
            ]);

            $this->broadcastServerUpdated();

            $this->alertServer($message, $process->getErrorOutput());
        }
    }
}
