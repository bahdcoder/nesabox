<?php

namespace App;

class DatabaseUser extends Model
{
    /**
     * Eagerly loaded property
     *
     * @var array
     */
    protected $with = ['databases'];

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
