<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     *
     * Turn of mass assignment protection
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    /**
     * Roll model slug
     *
     * @return null
     */
    public function rollSlug()
    {
        do {
            $this->slug = strtolower(str_random(8));
        } while ($this->where('slug', $this->slug)->exists());

        $this->save();
    }
}
