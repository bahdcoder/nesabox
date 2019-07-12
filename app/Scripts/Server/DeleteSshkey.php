<?php

namespace App\Scripts\Server;

use App\Server;
use App\Sshkey;
use App\Scripts\Base;

class DeleteSshkey extends Base
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
cd /home/{$user}/.ssh

if grep -v '{$this->key->key}' authorized_keys > authorized_keys_temp; then
    cat authorized_keys_temp > authorized_keys

    rm authorized_keys_temp
fi;



if grep -v '# {$this->key->name}' authorized_keys > authorized_keys_temp_name; then
    cat authorized_keys_temp_name > authorized_keys

    rm authorized_keys_temp_name
fi;


service ssh restart     
EOD;
    }
}
