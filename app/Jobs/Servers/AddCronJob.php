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
        } else {
            // TODO: Alert user of failure.
            $this->cronJob->delete();
        }

        $this->server->user->notify(new ServerIsReady($this->server));
    }
}
