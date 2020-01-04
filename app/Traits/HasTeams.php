<?php

namespace App\Traits;

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

    public function memberships()
    {
        return $this->hasMany(TeamInvite::class);
    }
}
