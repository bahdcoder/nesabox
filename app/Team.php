<?php

namespace App;

class Team extends Model
{
    public function invites()
    {
        return $this->hasMany(TeamInvite::class);
    }
}
