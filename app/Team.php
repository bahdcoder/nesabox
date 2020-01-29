<?php

namespace App;

class Team extends Model
{
    public function invites()
    {
        return $this->hasMany(TeamInvite::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function servers()
    {
        return $this->belongsToMany(Server::class);
    }

    public function hasMember(User $user)
    {
        if ((int) $this->user_id === (int) $user->id) {
            return true;
        }

        return (bool) TeamInvite::where('status', 'accepted')
            ->where('user_id', $user->id)
            ->first();
    }
}
