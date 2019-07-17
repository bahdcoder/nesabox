<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Server;
use App\Scripts\Base;

class CreateSite extends Base
{
    /**
     * The server.
     *
     * @var \App\Server
     */
    public $server;

    /**
     *
     * The site to be added to server
     *
     * @var \App\Site
     *
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
SITE_PORT=`python -c 'import socket; s=socket.socket(); s.bind(("", 0)); print(s.getsockname()[1])')`;
\$SITE_PORT
EOD;
    }

    public function getPortNumber()
    {
        return <<<EOD
echo 'Getting site port ...';
SITE_PORT="$(python -c 'import socket; s=socket.socket(); s.bind(("", 0)); print(s.getsockname()[1])')";
echo \$SITE_PORT;
EOD;
    }

    public function getNginxConfig()
    {
        if ($this->site->wild_card_subdomains) {
            return <<<EOD
server {
    listen 80;
    server_name *.{$this->site->name};
    
    location / {
        proxy_pass http://localhost:\$SITE_PORT;
        proxy_set_header Host \\\\\$http_host;
        proxy_set_header X-NginX-Proxy true;
        proxy_set_header X-Forwarded-For \\\\\$proxy_add_x_forwarded_for;
        proxy_http_version 1.1;
        proxy_set_header Upgrade \\\\\$http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_max_temp_file_size 0;
        proxy_redirect off;
        proxy_read_timeout 240s;
        proxy_set_header X-Forwarded-Proto \\\\\$scheme;
        proxy_set_header X-Real-IP \\\\\$remote_addr;
    }
}
EOD;
        } else {
            return <<<EOD
server {
    listen 80;
    server_name {$this->site->name};

    location / {
        proxy_pass http://localhost:\$SITE_PORT;
        proxy_set_header X-Forwarded-For \\\$proxy_add_x_forwarded_for;
        proxy_set_header Host \\\$http_host;
        proxy_set_header X-NginX-Proxy true;
        proxy_http_version 1.1;
        proxy_set_header Upgrade \\\$http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_max_temp_file_size 0;
        proxy_redirect off;
        proxy_read_timeout 240s;
        proxy_set_header X-Forwarded-Proto \\\$scheme;
        proxy_set_header X-Real-IP \\\$remote_addr;
    }
}
EOD;
        }
    }
}
