<?php

namespace App\Http\Controllers;

use App\Notifications\Sites\SiteUpdated;
use App\Site;
use App\Server;
use Illuminate\Http\Request;
use App\Scripts\Sites\UpdatePm2EcosystemFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class Pm2Controller extends Controller
{
    /**
     * Fetch a pm2 ecosystem file from the server
     */
    public function show(Server $server, Site $site)
    {
        $user = SSH_USER;

        $pathToConfig = "/home/{$user}/.{$user}/ecosystems/{$site->name}.config.js";

        $process = $this->getFileContent($server, $pathToConfig);

        if (!$process->isSuccessFul()) {
            abort(400, $process->getErrorOutput());
        }

        return $process->getOutput();
    }

    public function logs(Site $site) {
        Log::info(request()->ip());
        Log::info(strlen($site->logs));

        $logs = request()->logs;

        if (strlen($site->logs) > 3000) {
            $site->update([
                'logs' => $logs
            ]);
        } else {
            $site->update([
                'logs' => <<<EOF
$site->logs
{$logs}
EOF
        ]);
        }

        Notification::send(
            $site->server->getAllMembers(),
            new SiteUpdated($site)
        );

        return response()->json(request()->all());
    }

    /**
     * Update a pm2 ecosystem file on the server, and reload the pm2 process
     */
    public function update(Request $request, Server $server, Site $site)
    {
        $this->authorize('view', $server);

        $this->validate($request, [
            'ecosystemFile' => 'required'
        ]);

        $process = (new UpdatePm2EcosystemFile(
            $server,
            $site,
            request()->ecosystemFile
        ))->run();

        if (!$process->isSuccessFul()) {
            abort(400, $process->getErrorOutput());
        }

        return $process->getOutput();
    }
}
