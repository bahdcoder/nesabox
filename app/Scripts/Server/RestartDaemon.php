<?php

namespace App\Scripts\Server;

use App\Server;
use App\Daemon;
use App\Scripts\Base;

class RestartDaemon extends Base
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
        return <<<EOD
supervisorctl restart daemon-{$this->daemon->slug}:*
EOD;
    }

}
