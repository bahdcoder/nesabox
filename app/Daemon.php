<?php

namespace App;

class Daemon extends Model
{
    /**
     * A daemon belongs to a server
     *
     * @return \Illuminate\Eloquent\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
