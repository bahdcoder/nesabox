<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Server;
use App\Scripts\Base;

class AddSiteSsl extends Base
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
     * @var \array
     */
    public $sites;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(
        Server $server,
        Site $site,
        array $sites
    ) {
        $this->site = $site;
        $this->server = $server;
        $this->sites = $sites;
    }

    /**
     * Generate the init script
     *
     * @return string
     */
    public function generate()
    {
        $apiUrl = config('app.url');

        return <<<EOD
curl -Ss "{$apiUrl}/get-update-nginx-config/{$this->hash}" > /etc/nginx/sites-available/{$this->site->name}

nginx -t
systemctl reload nginx
EOD;
    }
}
