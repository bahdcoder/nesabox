<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use App\Rules\Subdomain;
use App\Jobs\Sites\AddSite;
use Illuminate\Http\Request;
use App\Jobs\Sites\UpdateSiteSlug;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Http\Requests\Sites\CreateSiteRequest;

class SitesController extends Controller
{
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

        $site->rollSlug();

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
        $site->update(
            $request->only(['before_deploy_script', 'after_deploy_script', 'before_start_script'])
        );

        return new ServerResource($server->fresh());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSlug(Server $server, Site $site, Request $request)
    {
        $this->validate($request, [
            'slug' => ['unique:sites,slug,' . $site->id, new Subdomain()]
        ]);

        $site->update([
            'updating_slug_status' => STATUS_UPDATING
        ]);

        UpdateSiteSlug::dispatch($server, $site, $request->slug);

        return new ServerResource($server->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
