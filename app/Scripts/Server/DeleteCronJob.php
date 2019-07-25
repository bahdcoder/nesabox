<?php

namespace App\Scripts\Server;

use App\Job;
use App\Server;
use App\Scripts\Base;

class DeleteCronJob extends Base
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
if grep -v "{$this->cronJob->cron} {$this->cronJob->user} {$this->cronJob->command} > /home/{$user}/.{$user}/cron-job-{$this->cronJob->slug}.log" /etc/crontab > temp_cron_to_be_deleted; then
    cat temp_cron_to_be_deleted > /etc/crontab

    rm temp_cron_to_be_deleted
fi;

if grep -v "# Nesabox cron job - {$this->cronJob->slug}" /etc/crontab > temp_cron_to_be_deleted; then
    cat temp_cron_to_be_deleted > /etc/crontab

    rm temp_cron_to_be_deleted
fi;
EOD;
    }
}
