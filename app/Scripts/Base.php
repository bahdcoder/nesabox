<?php

namespace App\Scripts;

use App\Http\Traits\HandlesProcesses;
use Illuminate\Support\Facades\Log;

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

    public $defaultUser = SSH_USER;

    /**
     *
     * Exit on first error
     *
     * @var boolean;
     */
    public $setE = true;

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

    public function noSetE()
    {
        $this->setE = false;

        return $this;
    }

    public function setE()
    {
        if ($this->setE) {
            return 'set -e';
        }

        return '';
    }

    public function generate() {}

    /**
     *
     * Run this script over ssh
     *
     * @return \Symfony\Component\Process\Process
     */
    public function run($callback = null)
    {
        $func = $callback ? 'execProcessAsync' : 'execProcess';

        Log::info($this->generate());

        return $this->$func(
            "ssh -o StrictHostKeyChecking=no {$this->sshUser}@{$this->server->ip_address} -i ~/.ssh/{$this->server->slug} 'bash -se' <<  EOF-CUSTOM
{$this->setE()}

{$this->generate()}
EOF-CUSTOM",
            $callback
        );
    }
}
