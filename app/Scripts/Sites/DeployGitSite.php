<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Server;
use App\Scripts\Base;

class DeployGitSite extends Base
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
        return <<<EOD
{$this->site->before_deploy_script}
pm2 reload {$this->site->name}-log-watcher
EOD;
    }
}
