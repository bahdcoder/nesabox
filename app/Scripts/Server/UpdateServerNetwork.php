<?php

namespace App\Scripts\Server;

use App\Server;
use App\Scripts\Base;
use Illuminate\Support\Collection;

class UpdateServerNetwork extends Base
{
    /**
     * The server.
     *
     * @var Collection
     */
    public $servers;

    /**
     * The servers to revoke firewall access.
     *
     * @var Collection
     */
    public $serversToDelete;

    /**
     * The server.
     *
     * @var App\Server
     */
    public $server;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Collection $servers, Collection $serversToDelete, Server $server)
    {
        $this->server = $server;
        $this->servers = $servers;
        $this->serversToDelete = $serversToDelete;
    }

    /**
     * Generate the init script
     *
     * @return string
     */
    public function generate()
    {
        $firewallScript = '';

        $firewallDeleteScript = '';

        foreach ($this->servers as $server):
            $firewallScript .= <<<EOD
\n
ufw allow from {$server->ip_address} to any port 3306;
\n
ufw allow from {$server->ip_address} to any port 6379;
\n
ufw allow from {$server->ip_address} to any port 5432;
\n
ufw allow from {$server->ip_address} to any port 27017;
\n
ufw allow from {$server->ip_address} to any port 3306;
\n
ufw allow from {$server->ip_address} to any port 6379;
\n
ufw allow from {$server->ip_address} to any port 5432;
\n
ufw allow from {$server->ip_address} to any port 27017;

\n
ufw allow from {$server->private_ip_address} to any port 3306;
\n
ufw allow from {$server->private_ip_address} to any port 6379;
\n
ufw allow from {$server->private_ip_address} to any port 5432;
\n
ufw allow from {$server->private_ip_address} to any port 27017;
\n
ufw allow from {$server->private_ip_address} to any port 3306;
\n
ufw allow from {$server->private_ip_address} to any port 6379;
\n
ufw allow from {$server->private_ip_address} to any port 5432;
\n
ufw allow from {$server->private_ip_address} to any port 27017;
EOD;
        endforeach;

        foreach ($this->serversToDelete as $server): 
            $firewallDeleteScript .= <<<EOD
\n
ufw delete allow from {$server->ip_address} to any port 3306;
\n
ufw delete allow from {$server->ip_address} to any port 6379;
\n
ufw delete allow from {$server->ip_address} to any port 5432;
\n
ufw delete allow from {$server->ip_address} to any port 27017;
\n
ufw delete allow from {$server->ip_address} to any port 3306;
\n
ufw delete allow from {$server->ip_address} to any port 6379;
\n
ufw delete allow from {$server->ip_address} to any port 5432;
\n
ufw delete allow from {$server->ip_address} to any port 27017;

\n
ufw delete allow from {$server->private_ip_address} to any port 3306;
\n
ufw delete allow from {$server->private_ip_address} to any port 6379;
\n
ufw delete allow from {$server->private_ip_address} to any port 5432;
\n
ufw delete allow from {$server->private_ip_address} to any port 27017;
\n
ufw delete allow from {$server->private_ip_address} to any port 3306;
\n
ufw delete allow from {$server->private_ip_address} to any port 6379;
\n
ufw delete allow from {$server->private_ip_address} to any port 5432;
\n
ufw delete allow from {$server->private_ip_address} to any port 27017;
EOD;
        endforeach;

        return <<<EOD
{$firewallDeleteScript}
{$firewallScript}
EOD;
    }
}
