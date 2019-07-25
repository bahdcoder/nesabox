<?php

namespace App\Scripts\Server;

use App\Job;
use App\Server;
use App\Scripts\Base;

class AddCronJob extends Base
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
    public $cronJob;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server, Job $cronJob)
    {
        $this->cronJob = $cronJob;
        $this->server = $server;
    }


    public function generate()
    {
        $user = SSH_USER;

        return <<<EOD
cat >> /etc/crontab << EOF
# Nesabox cron job - {$this->cronJob->slug}

{$this->cronJob->cron} {$this->cronJob->user} {$this->cronJob->command} > /home/{$user}/.{$user}/cron-job-{$this->cronJob->slug}.log

EOF
EOD;
    }
}
