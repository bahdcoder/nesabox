<?php

namespace App\Http\Controllers\Servers;

use App\Server;
use App\Database;
use App\DatabaseUser;
use App\Jobs\Servers\AddDatabase;
use App\Http\Controllers\Controller;
use App\Jobs\Servers\DeleteDatabase;
use App\Http\Resources\ServerResource;
use App\Http\Resources\DatabaseResource;
use App\Http\Resources\DatabaseUserResource;
use App\Http\Requests\Servers\CreateDatabaseRequest;

class DatabasesController extends Controller
{
    public function index(Server $server, string $databaseType)
    {
        switch ($databaseType) {
            case MYSQL8_DB:
                return response()->json([
                    'databases' => DatabaseResource::collection(
                        $server->mysql8Databases
                    ),
                    'database_users' => DatabaseUserResource::collection(
                        $server->mysql8DatabaseUsers
                    )
                ]);
            case MONGO_DB:
                return response()->json([
                    'databases' => DatabaseResource::collection(
                        $server->mongodbDatabases
                    ),
                    'database_users' => []
                ]);
            case POSTGRES_DB:
                return response()->json([
                    'databases' => DatabaseResource::collection(
                        $server->postgresdbDatabases
                    ),
                    'database_users' => DatabaseUserResource::collection(
                        $server->postgresdbDatabaseUsers
                    )
                ]);
            case MYSQL_DB:
                return response()->json([
                    'databases' => DatabaseResource::collection(
                        $server->mysqlDatabases
                    ),
                    'database_users' => DatabaseUserResource::collection(
                        $server->mysqlDatabaseUsers
                    )
                ]);
            case MARIA_DB:
                return response()->json([
                    'databases' => DatabaseResource::collection(
                        $server->mariadbDatabases
                    ),
                    'database_users' => DatabaseUserResource::collection(
                        $server->mariadbDatabaseUsers
                    )
                ]);
            default:
                return response()->json(__('Unsupported database.'), 400);
                break;
        }
    }
    public function store(CreateDatabaseRequest $request, Server $server)
    {
        if ($request->user && $request->password) {
            $databaseUser = $server->databaseUsers()->create([
                'name' => $request->user,
                'type' => $request->type,
                'status' => STATUS_INSTALLING,
                'password' => $request->password
            ]);
        } else {
            $databaseUser = null;
        }

        $database = Database::create([
            'type' => $request->type,
            'name' => $request->name,
            'server_id' => $server->id,
            'status' => STATUS_INSTALLING
        ]);

        if ($databaseUser) {
            $database->databaseUsers()->attach($databaseUser->id);
        }

        AddDatabase::dispatch($server, $database);

        return new ServerResource($server->fresh());
    }

    public function destroy(Server $server, Database $database)
    {
        $database->update([
            'status' => STATUS_DELETING
        ]);

        if (request()->query('delete_user')) {
            if ($database->databaseUser->name !== SSH_USER) {
                $database->databaseUser->update([
                    'status' => STATUS_DELETING
                ]);
            }
        }

        DeleteDatabase::dispatch($server, $database);

        return new ServerResource($server);
    }
}
