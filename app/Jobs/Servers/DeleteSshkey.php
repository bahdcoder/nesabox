<?php

namespace App\Jobs\Servers;

use App\Server;
use App\Sshkey;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Scripts\Server\DeleteSshkey as DeleteSshkeyScript;

class DeleteSshkey implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        (new DeleteSshkeyScript($this->server, $this->key))->run();

        $this->key->delete();
    }
}
