<?php

namespace App\Scripts\Server;

use App\Server;
use App\Sshkey;
use App\Scripts\Base;

class AddSshkey extends Base
{
    /**
     * The server to be initialized.
     *
     * @var \App\Server
     */
    public $server;

    /**
     *
     * The key to be added to server
     *
     * @var \App\Sshkey
     *
     */
    public $key;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server, Sshkey $key)
    {
        $this->key = $key;
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
# this script adds an ssh key to espectra user.
# SSH access via password will be disabled. Use keys instead.
SSH_USER='{$user}'
PUBLIC_SSH_KEYS='{$this->key->key}'

cat >> /home/{$user}/.ssh/authorized_keys << EOF
# {$this->key->name}

{$this->key->key}

EOF

# Restart SSH
service ssh restart        
EOD;
    }
}
