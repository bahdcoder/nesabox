 <?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Daemon;
use App\Jobs\Servers\AddDaemon;
use App\Http\Controllers\Controller;
use App\Scripts\Server\DaemonStatus;
use App\Scripts\Server\RestartDaemon;
use App\Http\Resources\ServerResource;
use App\Http\Requests\Servers\AddDaemonRequest;
use App\Jobs\Servers\DeleteDaemon;

class DaemonController extends Controller
{
    public function store(AddDaemonRequest $request, Server $server)
    {
        $this->authorize('view', $server);

        $daemon = $server->daemons()->create([
            'command' => $request->command,
            'processes' => $request->processes,
            'directory' => $request->directory,
            'user' => $request->user,
            'slug' => str_random(8)
        ]);

        $daemon->rollSlug();

        AddDaemon::dispatch($daemon);

        return new ServerResource($server->fresh());
    }

    public function status(Server $server, Daemon $daemon)
    {
        $this->authorize('view', $server);

        $process = (new DaemonStatus($server, $daemon))->run();

        return response()->json([
            'data' => $process->isSuccessful()
                ? $process->getOutput()
                : $process->getErrorOutput()
        ]);
    }

    public function restart(Server $server, Daemon $daemon)
    {
        $this->authorize('view', $server);

        $process = (new RestartDaemon($server, $daemon))->run();

        return response()->json([
            'data' => $process->isSuccessful()
                ? $process->getOutput()
                : $process->getErrorOutput()
        ]);
    }

    public function destroy(Server $server, Daemon $daemon)
    {
        $this->authorize('view', $server);

        $daemon->update([
            'status' => STATUS_DELETING
        ]);

        DeleteDaemon::dispatch($server, $daemon);

        return new ServerResource($server->fresh());
    }
}
