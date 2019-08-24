<?php

namespace App\Http\Controllers;

use App\Scripts\Sites\UpdateNginxConfigFile;
use App\Site;
use App\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NginxController extends Controller
{
    /**
     * Fetch nginx config for a site
     */
    public function show(Server $server, Site $site)
    {
        $pathToConfig = "/etc/nginx/conf.d/{$site->name}.conf";

        $process = $this->getFileContent($server, $pathToConfig);

        if (!$process->isSuccessFul()) {
            abort(400, $process->getErrorOutput());
        }

        return $process->getOutput();
    }

    /**
     * Update nginx config for a site
     */
    public function update(Request $request, Server $server, Site $site)
    {
        $this->authorize('view', $server);

        $this->validate($request, [
            'nginxConfig' => 'required'
        ]);

        $script = request()->nginxConfig;

        // We'll create a file that contains the config, then get the config from the server over http
        $hash = $this->createUpdateNginxConfigScript($script);

        $process = (new UpdateNginxConfigFile(
            $server,
            $site,
            $hash
        ))->run(function ($logs) {
            echo $logs;
        });

        if (!$process->isSuccessFul()) {
            abort(400, __($process->getErrorOutput()));
        }

        return $process->getOutput();
    }

    public function getUpdatingConfig(string $hash)
    {
        $file = Storage::disk('local')->get("update_nginx_config/{$hash}.conf");

        Storage::delete("update_nginx_config/{$hash}.conf");

        return $file;
    }
}
