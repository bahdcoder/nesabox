<?php

namespace App\Policies;

use App\TeamInvite;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamInvitePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the server.
     *
     * @param  \App\User  $user
     * @param  \App\Server  $server
     * @return mixed
     */
    public function update(User $user, TeamInvite $teamInvite)
    {
        return $teamInvite->user_id === (string) $user->id;
    }
}
