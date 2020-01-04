<?php

namespace App\Jobs\Servers;

use App\Notifications\Servers\ServerIsReady;

trait BroadcastServer
{
    public function broadcastServerUpdated()
    {
        $this->server->user->notify(new ServerIsReady($this->server->fresh()));

        // TODO: Get all teams this server has been added to, and broadcast this notification to all the users
        // on all the teams
    }

    public function alertServer($message, $output, $type = 'error')
    {
        $this->server->alert($message, $output, $type);
    }
}
