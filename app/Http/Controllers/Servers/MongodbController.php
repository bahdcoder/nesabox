<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Database;
use App\DatabaseUser;
use App\Http\Controllers\Controller;
use App\Jobs\Servers\AddMongodbUser;
use App\Http\Resources\ServerResource;
use App\Http\Requests\Servers\AddMongodbUserRequest;
use App\Http\Requests\Servers\AddMongodbDatabaseRequest;
use App\Jobs\Servers\DeleteMongodbDatabase;
use App\Jobs\Servers\DeleteMongodbDatabaseUser;

class MongodbController extends Controller
{
    public function deleteUsers(
        Server $server,
        Database $database,
        DatabaseUser $databaseUser
    ) {
        $databaseUser->update([
            'status' => STATUS_DELETING
        ]);

        DeleteMongodbDatabaseUser::dispatch($server, $database, $databaseUser);

        return new ServerResource($server->fresh());
    }

    public function deleteDatabases(Server $server, Database $database)
    {
        if ($database->type !== MONGO_DB) {
            return response()->json(
                [
                    'message' => 'Database was not found.'
                ],
                400
            );
        }

        if ($database->status !== STATUS_ACTIVE) {
            return response()->json(
                [
                    'message' => 'Cannot deleted a database that is not active.'
                ],
                400
            );
        }

        $database->update([
            'status' => STATUS_DELETING
        ]);

        DeleteMongodbDatabase::dispatch($server, $database);

        return new ServerResource($server->fresh());
    }

    public function databases(
        AddMongodbDatabaseRequest $request,
        Server $server
    ) {
        $server->databaseInstances()->create([
            'type' => MONGO_DB,
            'status' => STATUS_ACTIVE,
            'name' => $request->name
        ]);

        return new ServerResource($server->fresh());
    }

    public function users(
        AddMongodbUserRequest $request,
        Server $server,
        Database $database
    ) {
        if (
            $database
                ->databaseUsers()
                ->where('name', $request->name)
                ->first()
        ) {
            return response()->json(
                [
                    'errors' => [
                        'name' => ['The user already exists in this database.']
                    ]
                ],
                400
            );
        }

        $user = $server->databaseUsers()->create([
            'type' => MONGO_DB,
            'name' => $request->name,
            'status' => STATUS_INSTALLING,
            'password' => $request->password,
            'read_only' => (bool) $request->readonly
        ]);

        $database->databaseUsers()->attach($user->id);

        AddMongodbUser::dispatch($server, $database, $user);

        return new ServerResource($server->fresh());
    }
}
