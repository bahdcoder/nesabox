<?php

namespace App\Traits;

use App\Server;
use App\Team;
use App\TeamInvite;

trait HasTeams
{
    /**
     *
     * Has many relationship with teams
     *
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    /**
     *
     * Has many relationship with teams
     *
     */
    public function invites()
    {
        return $this->hasMany(TeamInvite::class);
    }

    public function memberships()
    {
        return $this->hasMany(TeamInvite::class);
    }

    public function acceptedMemberships()
    {
        return $this->memberships()->with('team.servers');
    }
}
