<?php

namespace App\Jobs\Servers;

use App\Server;
use App\Sshkey;
use Illuminate\Bus\Queueable;
use App\Http\Traits\HandlesProcesses;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Scripts\Server\AddSshkey as AddSshkeyScript;

class AddSshkey implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        HandlesProcesses;

    /**
     * The server to add ssh key to
     *
     * @var Server
     */
    public $server;

    /**
     * The ssh key to add to server
     *
     * @var \App\Sshkey
     */
    public $key;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, Sshkey $key)
    {
        $this->key = $key;
        $this->server = $server;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $process = (new AddSshkeyScript($this->server, $this->key))->run();

        if ($process->isSuccessful()) {
            $this->key->update([
                'status' => STATUS_ACTIVE
            ]);
        }
    }

    public function failed($e)
    {
        // TODO: Add server error saying key was deleted.

        $this->key->delete();
    }
}
