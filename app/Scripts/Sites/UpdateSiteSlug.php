<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Server;
use App\Scripts\Base;

class UpdateSiteSlug extends Base
{
    /**
     * The server.
     *
     * @var \App\Server
     */
    public $server;

    /**
     * Site to install ghost on
     *
     * @var \App\Site
     */
    public $site;

    /**
     * The new site slug
     * 
     * @var string
     */
    public $slug;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(
        Server $server,
        Site $site,
        string $slug
    ) {
        $this->site = $site;
        $this->slug = $slug;
        $this->server = $server;
    }

    /**
     * Generate the init script
     *
     * @return string
     */
    public function generate()
    {
        $user = SSH_USER;
        return <<<EOD

EOD;
    }
}
