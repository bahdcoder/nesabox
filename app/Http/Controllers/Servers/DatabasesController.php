<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Database;
use App\DatabaseUser;
use App\Jobs\Servers\AddDatabase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Http\Requests\Servers\CreateDatabaseRequest;

class DatabasesController extends Controller
{
    public function store(CreateDatabaseRequest $request, Server $server)
    {
        $databaseUser = null;

        if ($request->user && $request->password) {
            $databaseUser = $server->databaseUsers()->create(
                array_merge(
                    $request->only(
                        ['name', 'password', 'type'],
                        [
                            'status' => STATUS_INSTALLING
                        ]
                    )
                )
            );
        }

        $database = Database::create([
            'type' => $request->type,
            'name' => $request->name,
            'server_id' => $server->id,
            'status' => STATUS_INSTALLING,
            'database_user_id' => $databaseUser
                ? $databaseUser->id
                : DatabaseUser::where('name', SSH_USER)
                    ->where('server_id', $server->id)
                    ->first()->id
        ]);

        AddDatabase::dispatch($server, $database, $databaseUser);

        return new ServerResource($server->fresh());
    }

    public function destroy(Server $server, Database $database)
    {
        $database->update([
            'status' => STATUS_DELETING
        ]);

        return new ServerResource($server);
    }
}
