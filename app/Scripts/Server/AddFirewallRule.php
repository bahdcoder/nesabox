<?php

namespace App\Scripts\Server;

use App\Server;
use App\Scripts\Base;
use App\FirewallRule;

class AddFirewallRule extends Base
{
    /**
     * The daemon to be added.
     *
     * @var \App\Daemon
     */
    public $server;

    /**
     * The server to be initialized.
     *
     * @var \App\Server
     */
    public $rule;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server, FirewallRule $rule)
    {
        $this->rule = $rule;
        $this->server = $server;
    }

    public function generate()
    {
        return <<<EOD
{$this->generateFireWallRules()}
EOD;
    }

    public function generateFireWallRules()
    {
        $script = '';
        if ($this->rule->from) {
            foreach(explode(',', $this->rule->from) as $ip):
                $script .= <<<EOD
ufw allow from {$ip} to any port {$this->rule->port}
EOD;
            endforeach;

            return $script;
        } else {
            return "ufw allow {$this->rule->port}";
        }
    }
}
