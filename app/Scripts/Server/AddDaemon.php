<?php

namespace App\Scripts\Server;

use App\Daemon;
use App\Scripts\Base;

class AddDaemon extends Base
{
    /**
     * The server to be initialized.
     *
     * @var \App\Daemon
     */
    public $daemon;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Daemon $daemon)
    {
        $this->daemon = $daemon;
    }


    public function generate()
    {
        $user = SSH_USER;

        return <<<EOD
touch "/home/{$user}/.{$user}/daemon-{$this->daemon->id}.out.log"
touch "/home/{$user}/.{$user}/daemon-{$this->daemon->id}.error.log"

cat >> /etc/supervisor/conf.d/daemon-{$this->daemon->id}.conf << EOF
{$this->getDaemonConfig()}
EOF
EOD;
        // first, we'll create the stdout and stderr log files

        // secondly, we'll create the supervisor daemon config file

        // 
    }

    public function getDaemonConfig()
    {
        $user = SSH_USER;

        return <<<EOD
[program:daemon-{$this->daemon->id}]
{$this->getDirectory()}
command={$this->daemon->command}

process_name=%(program_name)s_%(process_num)02d
autostart=true
autorestart=true
user={$this->daemon->user}
numprocs={$this->daemon->processes}
stderr_logfile=/home/{$user}/.{$user}/daemon-{$this->daemon->id}.error.log
stdout_logfile=/home/{$user}/.{$user}/daemon-{$this->daemon->id}.out.log
EOD;
    }

    public function getDirectory()
    {
        if ($this->daemon->directory) {
            return "directory={$this->daemon->directory}";
        }

        return '';
    }
}
