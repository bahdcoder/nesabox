<?php

namespace App\Jobs\Servers;

use App\Notifications\Servers\ServerIsReady;

trait BroadcastServer
{
    public function broadcastServerUpdated()
    {
        $this->server->user->notify(new ServerIsReady($this->server->fresh()));
    }

    public function alertServer($message, $output, $type = 'error')
    {
        $this->server->alert($message, $output, $type);
    }
}
