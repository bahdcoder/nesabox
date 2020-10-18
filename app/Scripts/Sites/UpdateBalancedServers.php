<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Scripts\Base;
use App\Server;
use Illuminate\Support\Collection;

class UpdateBalancedServers extends Base
{
    /**
     * The server.
     *
     * @var Collection
     */
    public $servers;

    /**
     * The server.
     *
     * @var App\Server
     */
    public $server;

    /**
     * Site to delete
     *
     * @var \App\Site
     */
    public $site;

    public $port;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(
        Collection $servers,
        Server $server,
        Site $site,
        $port
    ) {
        $this->site = $site;
        $this->port = $port;
        $this->server = $server;
        $this->servers = $servers;
    }

    /**
     * Generate the init script
     *
     * @return string
     */
    public function generate()
    {
        $serverScript = '';

        $upstreamName = str_slug($this->site->name);

        foreach ($this->servers as $server):
            $serverScript .= <<<EOD
\n
server {$server->private_ip_address}:{$this->port};
EOD;
        endforeach;

        return <<<EOD
cat > /etc/nginx/nesa-conf/{$this->site->name}/upstream.conf << EOF
upstream {$upstreamName} {
    {$serverScript}

}
EOF

nginx -t

systemctl reload nginx
EOD;
    }
}
