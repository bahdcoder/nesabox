<?php

namespace App;

class Database extends Model
{
    /**
     * A database belongs to a database user
     *
     * @return \Illuminate\Database\Eloquent\BelongsToMany
     */
    public function databaseUsers()
    {
        return $this->belongsToMany(DatabaseUser::class);
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
