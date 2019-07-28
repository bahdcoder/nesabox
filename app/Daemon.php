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

    /**
     * Roll daemon slug
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