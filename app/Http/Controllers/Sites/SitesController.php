<?php

namespace App\Http\Controllers\Sites;

use App\Site;
use App\Server;
use Illuminate\Http\Request;
use App\Jobs\Sites\CreateSite;
use App\Http\Controllers\Controller;
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
            'status' => STATUS_INSTALLING,
            'wild_card_subdomains' => $request->wild_card_subdomains
                ? true
                : false
        ]);

        CreateSite::dispatch($server, $site);

        return response()->json($site);
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
