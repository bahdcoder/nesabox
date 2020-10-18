<?php

namespace App;

use App\Traits\HasTeams;
use App\Traits\HasSubscription;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Http\ServerProviders\HasServerProviders;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasServerProviders, HasTeams, HasSubscription;

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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
