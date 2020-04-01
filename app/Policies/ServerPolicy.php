<?php

namespace App\Policies;

use App\User;
use App\Server;
use App\TeamInvite;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any servers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the server.
     *
     * @param  \App\User  $user
     * @param  \App\Server  $server
     * @return mixed
     */
    public function view(User $user, Server $server)
    {
        if ((int) $server->user_id === (int) $user->id) {
            return true;
        }

        return $server->canBeAccessedBy($user);
    }

    /**
     * Determine whether the user can create servers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can create servers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function isReady(User $user, Server $server)
    {
        return $server->status === STATUS_ACTIVE;
    }

    /**
     * Determine whether the server is a load balancer
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function loadBalancer(User $user, Server $server)
    {
        if ($server->type !== 'load_balancer') {
            return $this->deny('The server must be a load balancer.');
        }

        return true;
    }

    /**
     * Determine whether the user can update the server.
     *
     * @param  \App\User  $user
     * @param  \App\Server  $server
     * @return mixed
     */
    public function update(User $user, Server $server)
    {
        //
    }

    /**
     * Determine whether the user can delete the server.
     *
     * @param  \App\User  $user
     * @param  \App\Server  $server
     * @return mixed
     */
    public function delete(User $user, Server $server)
    {
        //
    }

    /**
     * Determine whether the user can restore the server.
     *
     * @param  \App\User  $user
     * @param  \App\Server  $server
     * @return mixed
     */
    public function restore(User $user, Server $server)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the server.
     *
     * @param  \App\User  $user
     * @param  \App\Server  $server
     * @return mixed
     */
    public function forceDelete(User $user, Server $server)
    {
        //
    }
}
