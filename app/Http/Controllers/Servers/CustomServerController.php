<?php

namespace App\Http\Controllers\Servers;

use App\User;
use App\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scripts\Server\Init;

class CustomServerController extends Controller
{
    /**
     * Get the deployment script for a custom server
     *
     * @return \Illuminate\Http\Response
     */
    public function vps(Server $server)
    {
        $token = request()->query('api_token');

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            abort(403, __('Invalid api token.'));
        }

        if ($user->id !== $server->user_id) {
            abort(401, __('Unauthorized.'));
        }

        return (new Init($server))->generate();
    }
}
