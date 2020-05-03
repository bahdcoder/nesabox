<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Team;
use App\Server;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Server.{id}', function ($user, $id) {
    $server = Server::findOrFail($id);

    return $server->canBeAccessedBy($user);
});

Broadcast::channel('App.Team.{id}', function ($user, $id) {
    $team = Team::firstOrFail($id);

    if ($team->hasMember($user)) {
        return [
            'id' => $user->id,
            'name' => $user->name
        ];
    }

    return false;
});
