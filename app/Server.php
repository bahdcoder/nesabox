<?php

namespace App;

use GuzzleHttp\Client;
use App\Notifications\Servers\AlertError;

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
        });
    }

    /**
     * Roll model slug
     *
     * @return null
     */
    public function rollServerSlug()
    {
        do {
            $this->slug = strtolower(
                str_slug($this->name) . '-' . str_random(8)
            );
        } while ($this->where('slug', $this->slug)->exists());

        $this->save();
    }

    /**
     * A server has many ssh key
     *
     * @return
     */
    public function sshkeys()
    {
        return $this->hasMany(Sshkey::class)->where(
            'status',
            '!=',
            STATUS_DELETING
        );
    }

    public function daemons()
    {
        return $this->hasMany(Daemon::class)->where(
            'status',
            '!=',
            STATUS_DELETING
        );
    }

    public function jobs()
    {
        return $this->hasMany(Job::class)->where(
            'status',
            '!=',
            STATUS_DELETING
        );
    }

    /**
     * A server has many ssh keys created by server owner
     *
     * @return
     */
    public function personalSshkeys()
    {
        return $this->hasMany(Sshkey::class, 'server_id')
            ->where('is_app_key', false)
            ->where('status', '!=', STATUS_DELETING);
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

    public function firewallRules()
    {
        return $this->hasMany(FirewallRule::class)->where(
            'status',
            '!=',
            'deleting'
        );
    }

    /**
     * A server has many sites
     *
     * @return
     */
    public function sites()
    {
        return $this->hasMany(Site::class)->where('deleting_site', false);
    }

    public function mongodbDatabases()
    {
        return $this->databaseInstances()->where('type', 'mongodb');
    }

    public function mysqlDatabases()
    {
        return $this->databaseInstances()->where('type', 'mysql');
    }

    public function getLogWatcherSiteDomain()
    {
        $domain = config('services.digital-ocean.app-domain');

        return "{$this->slug}.{$domain}";
    }

    public function fetchMetrics()
    {
        $nesaMetricsPort = config('nesa.metrics_port');

        return json_decode(
            (new Client([
                'base_uri' => "http://{$this->ip_address}:{$nesaMetricsPort}"
            ]))
                ->post('/', [
                    'json' => [
                        'url' => '?chart=system.cpu&after=-60&format=json'
                    ]
                ])
                ->getBody()
        );
    }

    /**
     *
     * Get the nexabox server monitoring domain for this server
     *
     * @return string
     */
    public function getNesaboxServerMonitoringDomain(string $slug = null)
    {
        $subdomain = $slug ?? $this->slug;

        $subdomain = $subdomain . '-metrics';

        $domain = config('services.digital-ocean.metrics-domain');

        return "{$subdomain}.{$domain}";
    }

    /**
     * This method notifies the owner of the server with the alert.
     * TODO: When teams are supported, we'll notify all teammates that share this server.
     * 
     * @param string $message
     * @param string $output
     * 
     * @return null
     */
    public function alertError($message, $output = null)
    {
        $this->user->notify(new AlertError($this, $message, $output));
    }
}
