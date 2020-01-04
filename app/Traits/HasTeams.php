<?php

namespace App\Traits;

use App\Team;

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
}
