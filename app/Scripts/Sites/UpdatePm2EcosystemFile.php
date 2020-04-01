<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Server;
use App\Scripts\Base;

class UpdatePm2EcosystemFile extends Base
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
cat > /home/{$user}/.{$user}/ecosystems/{$this->site->name}.config.js << EOF
{$this->config}
EOF
EOD;
    }
}
