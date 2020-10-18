<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Server;
use App\Scripts\Base;

class UninstallGhost extends Base
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
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server, Site $site)
    {
        $this->site = $site;
        $this->server = $server;
    }

    /**
     * Generate the init script
     *
     * @return string
     */
    public function generate()
    {
        $user = SSH_USER;

        return <<<EOD
# Delete pm2 process
pm2 delete {$this->site->name}

# Delete ghost site folder
rm -r /home/{$user}/{$this->site->name}
EOD;
    }
}
