<?php

namespace App\Http\Controllers;

use App\Site;
use App\Server;
use Illuminate\Http\Request;
use App\Scripts\Sites\UpdatePm2EcosystemFile;

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
