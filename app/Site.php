<?php

namespace App;

use App\Scripts\Site\Deploy;
use App\Jobs\Sites\Deploy as AppDeploy;

class Site extends Model
{
    /**
     * Fields to be cast to native types
     *
     * @var array
     */
    protected $casts = [
        'environment' => 'array',
        'push_to_deploy' => 'boolean'
    ];

    /**
     * Fields to be hidden from database query results
     *
     * @var array
     */
    protected $hidden = ['deploy_script'];

    public function getDeploymentsAttribute()
    {
        return Activity::forSubject($this)
            ->where('description', 'Deployment')
            ->latest()
            ->paginate();
    }

    public function balancedServers()
    {
        return $this->hasMany(BalancedServer::class);
    }

    public function getLatestDeploymentAttribute()
    {
        return Activity::forSubject($this)
            ->where('description', 'Deployment')
            ->latest()
            ->first();
    }

    /**
     *
     * Build the ssh clone url based on the repository provider
     *
     * @return string
     */
    public function getSshUrl()
    {
        switch ($this->repository_provider) {
            case 'github':
                return "git@github.com:{$this->repository}.git";
            case 'gitlab':
                return "git@gitlab.com:{$this->repository}.git";
            default:
                return '';
        }
    }

    /**
     * A site belongs to a server
     *
     * @return \Illuminate\Database\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function pm2Processes()
    {
        return $this->hasMany(Pm2Process::class)->where(
            'status',
            '!=',
            STATUS_DELETING
        );
    }

    public function pm2ProcessesExceptWeb()
    {
        return $this->pm2Processes()
            ->where('name', '!=', $this->name)
            ->get();
    }

    /**
     *
     * Get the nexabox site domain for this site
     *
     * @return string
     */
    public function getNexaboxSiteDomain(string $slug = null)
    {
        $subdomain = $slug ?? $this->slug;
        $domain = config('services.digital-ocean.app-domain');

        return "{$subdomain}.{$domain}";
    }

    public function triggerDeployment()
    {
        $this->update([
            'deploying' => true
        ]);

        $deployment = activity()
            ->causedBy(auth()->user())
            ->performedOn($this)
            ->withProperty('log', '')
            ->withProperty('status', 'pending')
            ->log('Deployment');

        AppDeploy::dispatch($this->server, $this, $deployment);
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

    public function toTinyArray()
    {
        return [];
    }
}
