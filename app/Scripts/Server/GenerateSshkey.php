<?php

namespace App\Scripts\Server;

use App\Server;
use App\Scripts\Base;

class GenerateSshkey extends Base
{
    /**
     * The server to be initialized.
     *
     * @var \App\Server
     */
    public $server;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server)
    {
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
ssh-keygen -f /home/{$user}/.ssh/{$user} -t rsa -b 4096 -P '' -C root@{$this->server->name}

chown nesa -R /home/{$user}/.ssh
 
cat /home/{$user}/.ssh/{$user}.pub
EOD;
    }
}
