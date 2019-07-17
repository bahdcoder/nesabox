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
            default:
                # code...
                break;
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
}
