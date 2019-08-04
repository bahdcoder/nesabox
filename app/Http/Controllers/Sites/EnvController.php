<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sites\AddEnvVariableRequest;

class EnvController extends Controller
{
    /**
     * Fetch all environment variables for a site
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Server $server, Site $site)
    {
        $this->authorize('view', $server);

        return $site->environment;
    }

    /**
     * Store a new environment variable for a site
     *
     * @return \Illuminate\Http\Response
     */
    public function store(
        AddEnvVariableRequest $request,
        Server $server,
        Site $site
    ) {
        $this->authorize('view', $server);

        $env = collect($site->environment);

        if (
            (bool) $env->first(function ($value, $key) use ($request) {
                return $key === $request->key;
            })
        ) {
            abort(400, __('Environment variable already exists.'));
        }

        $site->update([
            'environment' => array_merge($site->environment, [
                $request->key => $request->value
            ])
        ]);

        return $site->fresh()->environment;
    }

    /**
     * Delete an environment variable by key
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server, Site $site, $key)
    {
        $this->authorize('view', $server);

        $env = collect($site->environment);

        if (
            !(bool) $env->first(function ($value, $envKey) use ($key) {
                return $envKey === $key;
            })
        ) {
            abort(400, __('Environment variable does not exist.'));
        }

        if ($key === 'PORT') {
            abort(400, __('Cannot delete PORT environment variable.'));
        }

        $site->update([
            'environment' => $env
                ->filter(function ($value, $envKey) use ($key) {
                    return $envKey !== $key;
                })
                ->all()
        ]);

        return $site->fresh()->environment;
    }
}
