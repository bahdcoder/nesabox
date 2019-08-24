<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Server;
use App\Scripts\Base;

class DeleteSite extends Base
{
    /**
     * The server.
     *
     * @var \App\Server
     */
    public $server;

    /**
     * Site to delete
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
# Remove nginx config of site
rm /etc/nginx/conf.d/{$this->site->name}.conf

rm -rf /etc/nginx/nesa-conf/{$this->site->name}

# TODO: Remove all generated ssl certificates for this site

# Reload nginx
systemctl reload nginx

# Remove site directory if exists
su {$user} <<EOF
if [ -d '/home/{$user}/{$this->site->name}' ]
then
    rm -rf /home/{$user}/{$this->site->name}
fi

# Remove all running pm2 instances for this site
if [ -f '/home/{$user}/.{$user}/ecosystems/{$this->site->name}.config.js' ]
then
pm2 delete /home/{$user}/.{$user}/ecosystems/{$this->site->name}.config.js

rm /home/{$user}/.{$user}/ecosystems/{$this->site->name}.config.js
fi
EOF
EOD;
    }
}
