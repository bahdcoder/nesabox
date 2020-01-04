<?php

namespace App;

class TeamInvite extends Model
{
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
