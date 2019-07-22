<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\AddDaemonRequest;
use App\Scripts\Server\AddDaemon;

class DaemonController extends Controller
{
    public function store(AddDaemonRequest $request, Server $server)
    {
        $this->authorize('view', $server);

        $daemon = $server->daemons()->create([
            'command' => $request->command,
            'processes' => $request->processes,
            'directory' => $request->directory,
            'user' => $request->user
        ]);

        return (new AddDaemon($daemon))->generate();
    }
}
