<?php

namespace App\Http\Controllers\Network;

use App\Http\Controllers\Controller;
use App\Server;
use Illuminate\Http\Request;

class GetFamilyServersController extends Controller
{
    /**
     * This method queries all servers from the same
     * provider, and the same provider region
     * for this user.
     *
     * @return {\Illuminate\Http\Response}
     */
    public function index(Server $server)
    {
        $servers = auth()
            ->user()
            ->servers()
            ->where('provider', $server->provider)
            ->where('region', $server->region)
            ->where('id', '!=', $server->id)
            ->get();

        return response()->json($servers);
    }
}
