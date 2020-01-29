<?php

namespace App;

use GuzzleHttp\Client;
use App\Notifications\Servers\Alert;
use Notification;

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

    public function balancedServers()
    {
        return $this->hasMany(BalancedServer::class);
    }

    public function friendServers()
    {
        return $this->hasMany(FriendServer::class);
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
        return $this->hasMany(DatabaseUser::class)->where(
            'status',
            '!=',
            STATUS_DELETING
        );
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
        return $this->hasMany(Database::class)->where(
            'status',
            '!=',
            STATUS_DELETING
        );
    }

    public function firewallRules()
    {
        return $this->hasMany(FirewallRule::class)->where(
            'status',
            '!=',
            STATUS_DELETING
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
        return $this->databaseInstances()->where('type', MONGO_DB);
    }

    public function mysqlDatabases()
    {
        return $this->databaseInstances()->where('type', MYSQL_DB);
    }

    public function mysql8Databases()
    {
        return $this->databaseInstances()->where('type', MYSQL8_DB);
    }

    public function mysql8DatabaseUsers()
    {
        return $this->databaseUsers()->where('type', MYSQL8_DB);
    }

    public function mariadbDatabases()
    {
        return $this->databaseInstances()->where('type', MARIA_DB);
    }

    public function mariadbDatabaseUsers()
    {
        return $this->databaseUsers()->where('type', MARIA_DB);
    }

    public function postgresdbDatabases()
    {
        return $this->databaseInstances()
            ->where('type', POSTGRES_DB)
            ->where('status', '!=', STATUS_DELETING);
    }

    public function postgresdbDatabaseUsers()
    {
        return $this->databaseUsers()->where('type', POSTGRES_DB);
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
    public function alert($message, $output = null, $type = 'error')
    {
        Notification::send($this->getAllMembers(), new Alert($this, $message, $output, $type));
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function canBeAccessedBy(User $user) {
        return (bool) TeamInvite::where('user_id', $user->id)->with('team.servers')->get()->first(function ($membership) {
            return (bool) $membership->team->servers->first(function ($server) {
                return $server->id === $this->id;
            });
        });;
    }

    public function getAllMembers() {
        $teams = $this->teams()->with('invites.user')->get();

        $users = collect([$this->user]);

        $teams->each(function ($team) use ($users) {
            $team->invites->each(function ($invite) use ($users)  {
                if ($invite->status === 'active') {
                    $users->push($invite->user);
                }
            });
        });

        return $users;
    }
}
