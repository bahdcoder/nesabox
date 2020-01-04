<?php

namespace App;

use App\Traits\HasTeams;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Http\ServerProviders\HasServerProviders;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasServerProviders, HasTeams;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'providers' => 'array',
        'source_control' => 'array'
    ];

    /**
     *
     * A user has many servers
     *
     * @return \Illuminate\Database\Relations\HasMany
     *
     */
    public function servers()
    {
        return $this->hasMany(Server::class);
    }

    /**
     *
     * A user has many personal sshkeys
     *
     * @return \Illuminate\Database\Relations\HasMany
     *
     */
    public function sshkeys()
    {
        return $this->hasMany(Sshkey::class);
    }

    /**
     * Roll API Key
     *
     *
     */
    public function rollApiKey()
    {
        do {
            $this->api_token = str_random(40);
        } while ($this->where('api_token', $this->api_token)->exists());

        $this->save();
    }
}
