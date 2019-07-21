<?php

namespace App;

class Server extends Model
{
    /**
     * Fields to cast to native types
     *
     * @var array
     */
    protected $casts = [
        'details' => 'array',
        'databases' => 'array',
        'ssh_key_added_to_source_provider' => 'array'
    ];

    /**
     * Boot the model
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->ssh_key_added_to_source_provider = [
                'github' => false,
                'bitbucket' => false,
                'gitlab' => false
            ];

            $model->slug = str_slug($model->name) . '-' . str_random(8);
        });
    }

    /**
     * A server has many ssh key
     *
     * @return
     */
    public function sshkeys()
    {
        return $this->hasMany(Sshkey::class);
    }

    /**
     * A server has many ssh keys created by server owner
     *
     * @return
     */
    public function personalSshkeys()
    {
        return $this->hasMany(Sshkey::class, 'server_id')->where(
            'is_app_key',
            false
        )->where('status', '!=', STATUS_DELETING);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A server has many database users
     *
     * @return
     */
    public function databaseUsers()
    {
        return $this->hasMany(DatabaseUser::class);
    }

    public function mongoDbDatabaseUsers()
    {
        return $this->databaseUsers()->where('type', 'mongodb');
    }

    public function mysqlDatabaseUsers()
    {
        return $this->databaseUsers()->where('type', 'mysql');
    }

    public function databaseInstances()
    {
        return $this->hasMany(Database::class);
    }

    /**
     * A server has many sites
     *
     * @return
     */
    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    public function mongodbDatabases()
    {
        return $this->databaseInstances()->where('type', 'mongodb');
    }
}
