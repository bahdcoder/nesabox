<?php

namespace App\Scripts\Server;

use App\Server;
use App\Daemon;
use App\Scripts\Base;

class DaemonStatus extends Base
{
    /**
     * The server to be initialized.
     *
     * @var \App\Daemon
     */
    public $daemon;

    /**
     * The server to be initialized.
     *
     * @var \App\Server
     */
    public $server;

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
supervisorctl status daemon-{$this->daemon->slug}:*
EOD;
    }

}
