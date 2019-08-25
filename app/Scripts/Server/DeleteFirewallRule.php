<?php

namespace App\Scripts\Server;

use App\Server;
use App\Scripts\Base;
use App\FirewallRule;

class DeleteFirewallRule extends Base
{
    /**
     * The daemon to be added.
     *
     * @var \App\Daemon
     */
    public $server;

    /**
     * The rule to be deleted.
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
{$this->generateDeleteFireWallRules()}
EOD;
    }

    public function generateDeleteFireWallRules()
    {
        $script = '';
        if ($this->rule->from) {
            foreach(explode(',', $this->rule->from) as $ip):
                $script .= <<<EOD
ufw delete allow from {$ip} to any port {$this->rule->port}
EOD;
            endforeach;

            return $script;
        } else {
            return "ufw delete allow {$this->rule->port}";
        }
    }
}
