<?php

namespace App\Http\Controllers\Servers;

use App\Database;
use App\DatabaseUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Servers\AddDatabaseUserRequest;
use App\Jobs\Servers\AddDatabaseUser;
use App\Jobs\Servers\DeleteDatabaseUser;
use App\Server;

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

        return response()->json([]);
    }

    public function destroy(Server $server, DatabaseUser $databaseUser)
    {
        $this->authorize('view', $server);

        $databaseUser->update([
            'status' => STATUS_DELETING
        ]);

        DeleteDatabaseUser::dispatch($server, $databaseUser);

        return response()->json([]);
    }
}
