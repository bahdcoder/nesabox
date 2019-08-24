<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Server;
use App\Scripts\Base;

class UpdateNginxConfigFile extends Base
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
     * The file hash in storage
     * 
     * @var string
     */
    public $hash;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server, Site $site, string $hash)
    {
        $this->site = $site;
        $this->hash = $hash;
        $this->server = $server;
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
curl -Ss "{$apiUrl}/get-update-nginx-config/{$this->hash}" > /etc/nginx/conf.d/{$this->site->name}.conf

nginx -t
systemctl reload nginx
EOD;
    }
}
