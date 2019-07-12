<?php

namespace App;

class DatabaseUser extends Model
{
    /**
     * A database user has many databases
     *
     * @return
     */
    public function databases()
    {
        return $this->hasMany(Database::class);
    }
}
