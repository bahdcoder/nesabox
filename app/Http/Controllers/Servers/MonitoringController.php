<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Jobs\Servers\InstallMonitoring;

class MonitoringController extends Controller
{
    public function index(Server $server)
    {
        $this->authorize('view', $server);

        return response()->json($server->fetchMetrics());
    }

    public function store(Server $server)
    {
        $this->authorize('view', $server);

        $server->update([
            'server_monitoring_status' => STATUS_INSTALLING
        ]);

        InstallMonitoring::dispatch($server->fresh());

        return new ServerResource($server->fresh());
    }
}
