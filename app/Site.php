<?php

namespace App;

use App\Scripts\Site\Deploy;

class Site extends Model
{
    /**
     * Fields to be cast to native types
     *
     * @var array
     */
    protected $casts = [
        'environment' => 'array'
    ];

    /**
     * Fields to be hidden from database query results
     *
     * @var array
     */
    protected $hidden = ['deploy_script'];

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

    /**
     *
     * @return string
     */
    public function getDeployScript()
    {
        return (new Deploy($this->server, $this))->generate();
    }

    public function pm2Processes()
    {
        return $this->hasMany(Pm2Process::class)->where(
            'status',
            '!=',
            STATUS_DELETING
        );
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
}
