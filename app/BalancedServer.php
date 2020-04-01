<?php

namespace App;

class BalancedServer extends Model
{
    public function server()
    {
        return $this->belongsTo(Site::class);
    }

    public function servers()
    {
        return $this->hasMany(Server::class, 'balanced_server_id');
    }
}
