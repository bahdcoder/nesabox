<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Database;
use App\DatabaseUser;
use App\Http\Controllers\Controller;
use App\Jobs\Servers\AddDatabaseUser;
use App\Http\Resources\ServerResource;
use App\Jobs\Servers\DeleteDatabaseUser;
use App\Http\Requests\Servers\AddDatabaseUserRequest;

class DatabaseUserController extends Controller
{
    public function store(AddDatabaseUserRequest $request, Server $server)
    {
        $this->authorize('view', $server);

        if ($request->databases):
            foreach ($request->databases as $databaseId):
                Database::where('id', $databaseId)
                    ->where('type', $request->type)
                    ->firstOrFail();
            endforeach;
        endif;

        $user = $server->databaseUsers()->create([
            'status' => STATUS_INSTALLING,
            'type' => $request->type,
            'name' => $request->name,
            'password' => $request->password
        ]);

        if ($request->databases) {
            $user->databases()->attach($request->databases);
        }

        AddDatabaseUser::dispatch($server, $user);

        return new ServerResource($server->fresh());
    }

    public function destroy(Server $server, DatabaseUser $databaseUser)
    {
        $this->authorize('view', $server);

        $databaseUser->update([
            'status' => STATUS_DELETING
        ]);

        DeleteDatabaseUser::dispatch($server, $databaseUser);

        return new ServerResource($server->fresh());
    }
}
