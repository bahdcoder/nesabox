<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Jobs\Sites\InstallGitRepository;
use App\Http\Requests\Sites\InstallRepositoryRequest;

class GitRepositoryController extends Controller
{
    public function store(
        Server $server,
        Site $site,
        InstallRepositoryRequest $request
    ) {
        $this->authorize('view', $server);

        $user = SSH_USER;

        $beforeDeployScript = <<<EOD
cd /home/{$user}/{$site->name}

git pull origin {$request->branch}

npm install --production  

pm2 startOrReload /home/{$user}/.{$user}/ecosystems/{$site->name}.config.js
EOD;

        $site->update([
            'app_type' => 'git',
            'repository' => $request->repository,
            'repository_branch' => $request->branch,
            'repository_status' => STATUS_INSTALLING,
            'repository_provider' => $request->provider,
            'before_deploy_script' => $beforeDeployScript
        ]);

        InstallGitRepository::dispatch($server, $site->fresh());

        return new ServerResource($server);
    }
}
