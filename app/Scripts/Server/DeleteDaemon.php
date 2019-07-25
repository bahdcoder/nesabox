<?php

namespace App\Scripts\Server;

use App\Server;
use App\Daemon;
use App\Scripts\Base;

class DeleteDaemon extends Base
{
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

    public function generate()
    {
        $user = SSH_USER;

        return <<<EOD
supervisorctl remove daemon-{$this->daemon->slug}
rm "/home/{$user}/.{$user}/daemon-{$this->daemon->slug}.out.log"
rm "/home/{$user}/.{$user}/daemon-{$this->daemon->slug}.error.log"

rm /etc/supervisor/conf.d/daemon-{$this->daemon->slug}.conf

EOD;
    }

}
