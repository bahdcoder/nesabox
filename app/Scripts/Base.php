<?php

namespace App\Scripts;

use App\Http\Traits\HandlesProcesses;

class Base
{
    use HandlesProcesses;

    /**
     *
     * User to be used for ssh
     *
     * @var string
     */
    public $sshUser = 'root';

    /**
     *
     * @param string $user
     *
     * @return \App\Scripts\Base
     */
    public function as(string $user)
    {
        $this->sshUser = $user;

        return $this;
    }

    /**
     *
     * Run this script over ssh
     *
     * @return \Symfony\Component\Process\Process
     */
    public function run($callback = null)
    {
        $func = $callback ? 'execProcessAsync' : 'execProcess';

        return $this->$func(
            "ssh {$this->sshUser}@{$this->server->ip_address} -i ~/.ssh/{$this->server->slug} 'bash -se' <<  EOF-CUSTOM
{$this->generate()}
EOF-CUSTOM",
            $callback
        );
    }
}
