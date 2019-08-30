<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\Rules\Subdomain;
use App\Jobs\Sites\AddSite;
use Illuminate\Http\Request;
use App\Jobs\Sites\UpdateSiteSlug;
use App\Scripts\Sites\DeleteSite;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Http\Requests\Sites\CreateSiteRequest;
use App\Http\Resources\SiteResource;
use App\Jobs\Sites\DeleteSite as AppDeleteSite;

class SitesController extends Controller
{
    public function show(Server $server, Site $site)
    {
        $this->authorize($server, 'view');
        
        return new SiteResource($site);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSiteRequest $request, Server $server)
    {
        $site = $server->sites()->create([
            'name' => $request->name,
            'status' => STATUS_INSTALLING
        ]);

        AddSite::dispatch($server, $site);

        return new ServerResource($server->fresh());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Server $server, Site $site, Request $request)
    {
        $site->update($request->only(['before_deploy_script']));

        return new ServerResource($server->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server, Site $site)
    {
        if (
            $site->deploying ||
            $site->repository_status === STATUS_INSTALLING ||
            $site->repository_status === STATUS_UNINSTALLING ||
            $site->installing_ghost_status === STATUS_INSTALLING ||
            $site->installing_ghost_status === STATUS_UNINSTALLING
        ) {
            abort(
                400,
                __(
                    'Cannot delete site. Site is currently running some server actions.'
                )
            );
        }

        AppDeleteSite::dispatch($server, $site);

        $site->update([
            'deleting_site' => true
        ]);

        return new ServerResource($server);
    }
}
