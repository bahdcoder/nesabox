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
        } else {
            // TODO: Alert user of why cron job deletion failed
            $this->cronJob->update([
                'status' => STATUS_ACTIVE
            ]);
        }

        $this->server->user->notify(new ServerIsReady($this->server));
    }
}
