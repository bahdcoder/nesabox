<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Sshkey;
use App\Jobs\Servers\AddSshkey;
use App\Jobs\Servers\DeleteSshKey;
use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\CreateSshKeyRequest;

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
        $this->authorize('isReady', $server);

        $key = $server->sshkeys()->create([
            'key' => $request->key,
            'name' => $request->name
        ]);

        AddSshkey::dispatch($server, $key);

        return $key;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Server $server, Sshkey $sshkey)
    {
        $sshkey->update([
            'status' => 'deleted'
        ]);

        DeleteSshKey::dispatch($server, $sshkey);

        return response()->json();
    }
}
