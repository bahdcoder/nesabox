<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Database;
use App\DatabaseUser;
use App\Jobs\Servers\AddDatabase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServerResource;
use App\Http\Requests\Servers\CreateDatabaseRequest;
use App\Scripts\Server\AddDatabase as AddDatabaseScript;
use App\Scripts\Server\DeleteDatabase as DeleteDatabaseScript;

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
                            'status' => STATUS_ACTIVE
                        ]
                    )
                )
            );
        }

        $database = Database::create([
            'type' => $request->type,
            'name' => $request->name,
            'server_id' => $server->id,
            'status' => STATUS_ACTIVE,
            'database_user_id' => $databaseUser ? $databaseUser->id : DatabaseUser::where('name', SSH_USER)->where('server_id', $server->id)->first()->id
        ]);

        $process = (new AddDatabaseScript($server, $database, $databaseUser))->run();

        if (! $process->isSuccessful()) {
            $database->delete();

            $databaseUser && $databaseUser->delete();

            abort(400, $process->getErrorOutput());
        }

        return new ServerResource($server->fresh());
    }

    public function destroy(Server $server, Database $database)
    {
        $process = (new DeleteDatabaseScript($server, $database))->run();

        if (! $process->isSuccessful()) {
            abort(400, $process->getErrorOutput());
        }

        $database->databaseUser->delete();

        $database->delete();

        return new ServerResource($server);
    }
}
