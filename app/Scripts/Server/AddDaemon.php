<?php

namespace App\Scripts\Server;

use App\Daemon;
use App\Server;
use App\Scripts\Base;

class AddDaemon extends Base
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
    public $daemon;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server, Daemon $daemon)
    {
        $this->daemon = $daemon;
        $this->server = $server;
    }


    public function generate()
    {
        $user = SSH_USER;

        return <<<EOD
touch "/home/{$user}/.{$user}/daemon-{$this->daemon->slug}.out.log"

cat >> /etc/supervisor/conf.d/daemon-{$this->daemon->slug}.conf << EOF
{$this->getDaemonConfig()}
EOF

supervisorctl reread
supervisorctl update 
EOD;
    }

    public function getDaemonConfig()
    {
        $user = SSH_USER;

        return <<<EOD
[program:daemon-{$this->daemon->slug}]
{$this->getDirectory()}
command={$this->daemon->command}

process_name=%(program_name)s_%(process_num)02d
autostart=true
autorestart=true
stopasgroup=true
stopsignal=QUIT
user={$this->daemon->user}
numprocs={$this->daemon->processes}
redirect_stderr=true
stdout_logfile=/home/{$user}/.{$user}/daemon-{$this->daemon->slug}.out.log
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
