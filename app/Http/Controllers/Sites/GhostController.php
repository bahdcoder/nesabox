<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;

class GhostController extends Controller
{
    /**
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Server $server, Site $site)
    {
        $site->update([
            'installing_ghost_status' => STATUS_INSTALLING
        ]);

        return new ServerResource($server);
    }
}
