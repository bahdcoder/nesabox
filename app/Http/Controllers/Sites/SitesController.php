<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use Illuminate\Http\Request;
use App\Scripts\Sites\GetSitePort;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Http\Requests\Sites\CreateSiteRequest;
use App\Scripts\Sites\CreateSite as CreateSiteScript;

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
            'status' => STATUS_ACTIVE
        ]);

        // Because we can't escape the $ (in nginx config) properly in the CreateSiteScript, we'll use 
        // a manual file based script for this one.
        $process = $this->runCreateSiteScript($server, $site->fresh());

        if (! $process->isSuccessful()) {
            $site->delete();

            abort(400, 'Failed adding site.');
        }

        $site->update([
            'environment' => [
                'PORT' => $process->getOutput()
            ]
        ]);

        return new ServerResource($server);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Server $server, Site $site)
    {
        if (request()->ajax()) {
            return response()->json($site);
        }

        return view('sites.show')->withSite($site);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
