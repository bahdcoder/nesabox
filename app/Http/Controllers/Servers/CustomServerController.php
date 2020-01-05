<?php

namespace App\Http\Controllers\Servers;

use App\User;
use App\Server;
use App\Scripts\Server\Init;
use App\Http\Controllers\Controller;
use App\Scripts\Server\InitLoadBalancerServer;

class CustomServerController extends Controller
{
    /**
     * Get the deployment script for a custom server
     *
     * @return \Illuminate\Http\Response
     */
    public function vps(Server $server)
    {
        $this->authorize('view', $server);

        if ($server->type === 'load_balancer') {
            return (new InitLoadBalancerServer($server))->generate();
        }

        return (new Init($server))->generate();
    }
}
