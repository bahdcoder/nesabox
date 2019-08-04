<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Server;
use App\Database;
use App\Scripts\Base;
use App\DatabaseUser;
use App\Scripts\Server\AddDatabase;

class UpdateGhostConfig extends Base
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
     * The new ghost config
     *
     * @var string
     */
    public $config;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server, Site $site, string $config)
    {
        $this->site = $site;
        $this->config = $config;
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
echo '{$this->config}' > /home/{$user}/{$this->site->name}/config.production.json

# Reload pm2 site - no downtime
pm2 reload {$this->site->name} --update-env
EOD;
    }
}
