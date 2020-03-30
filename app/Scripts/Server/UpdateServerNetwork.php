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

    public $ports;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(
        Collection $servers,
        Collection $serversToDelete,
        Server $server,
        $ports
    ) {
        $this->ports = $ports;
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
            foreach($this->ports as $port):
$firewallScript .= <<<EOD
ufw allow from {$server->ip_address} to any port {$port};
\n
ufw allow from {$server->private_ip_address} to any port {$port};
\n
EOD;
            endforeach;
        endforeach;
        foreach ($this->serversToDelete as $server):
            foreach(explode(',', $server->ports) as $port):
                $firewallDeleteScript .= <<<EOD
ufw delete allow from {$server->ip_address} to any port {$port};
\n
ufw delete allow from {$server->private_ip_address} to any port {$port};
\n
EOD;
            endforeach;
        endforeach;

        return <<<EOD
{$firewallDeleteScript}
{$firewallScript}
EOD;
    }
}
