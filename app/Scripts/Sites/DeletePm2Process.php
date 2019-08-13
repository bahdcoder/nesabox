<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Server;
use App\Pm2Process;
use App\Scripts\Base;

class DeletePm2Process extends Base
{
    /**
     * The server.
     *
     * @var \App\Server
     */
    public $server;

    /**
     * Site to install ghost on
     *
     * @var \App\Site
     */
    public $site;

    /**
     * The pm2 process to delete
     *
     * @var \App\Pm2Process
     */
    public $pm2Process;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(
        Server $server,
        Site $site,
        Pm2Process $pm2Process
    ) {
        $this->site = $site;
        $this->server = $server;
        $this->pm2Process = $pm2Process;
    }

    /**
     * Generate the init script
     *
     * @return string
     */
    public function generate()
    {
        return <<<EOD
pm2 delete {$this->pm2Process->slug}
EOD;
    }
}
