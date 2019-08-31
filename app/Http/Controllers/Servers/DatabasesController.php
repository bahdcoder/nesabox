<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Database;
use App\DatabaseUser;
use App\Jobs\Servers\AddDatabase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Http\Requests\Servers\CreateDatabaseRequest;
use App\Jobs\Servers\DeleteDatabase;

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
        } else {
            $databaseUser = DatabaseUser::findOrFail($request->database_user_id);
        }

        $database = Database::create([
            'type' => $request->type,
            'name' => $request->name,
            'server_id' => $server->id,
            'status' => STATUS_INSTALLING,
            'database_user_id' => $databaseUser->id
        ]);

        AddDatabase::dispatch($server, $database);

        return new ServerResource($server->fresh());
    }

    public function destroy(Server $server, Database $database)
    {
        $database->update([
            'status' => STATUS_DELETING
        ]);

        if (request()->query('delete_user')) {
            if ($database->databaseUser->name !== SSH_USER ) {
                $database->databaseUser->update([
                    'status' => STATUS_DELETING
                ]);
            }
        }

        DeleteDatabase::dispatch($server, $database);

        return new ServerResource($server);
    }
}
