<?php

namespace App;

class Job extends Model
{
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * Roll job slug
     *
     *
     */
    public function rollSlug()
    {
        do {
            $this->slug = str_random(8);
        } while ($this->where('slug', $this->slug)->exists());

        $this->save();
    }
}
