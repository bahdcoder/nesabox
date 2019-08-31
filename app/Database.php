<?php

namespace App;

class Database extends Model
{
    /**
     * A database belongs to a database user
     *
     * @return \Illuminate\Database\Eloquent\BelongsTo
     */
    public function databaseUser()
    {
        return $this->belongsTo(DatabaseUser::class);
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
