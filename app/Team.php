<?php

namespace App;

class Team extends Model
{
    public function invites()
    {
        return $this->hasMany(TeamInvite::class);
    }

    public function servers()
    {
        return $this->belongsToMany(Server::class);
    }
}
