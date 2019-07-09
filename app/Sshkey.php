<?php

namespace App;

class Sshkey extends Model
{
    /**
     * Hidden fields from database queries
     *
     * @var array
     */
    protected $hidden = ['key'];
}
