<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Sshkey;
use App\Jobs\Servers\DeleteSshKey;
use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\CreateSshKeyRequest;
use App\Jobs\Servers\AddSshkey;
use App\Http\Resources\ServerResource;

class SshKeysController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSshKeyRequest $request, Server $server)
    {
        $this->authorize('view', $server);

        $this->authorize('isReady', $server);

        $key = $server->sshkeys()->create([
            'key' => $request->key,
            'name' => $request->name,
            'status' => STATUS_INSTALLING
        ]);

        AddSshkey::dispatch($server, $key);

        return new ServerResource($server);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server, Sshkey $sshkey)
    {
        $this->authorize('view', $server);

        DeleteSshKey::dispatch($server, $sshkey);

        return new ServerResource($server);
    }
}
