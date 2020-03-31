<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Scripts\Base;

class UpdateFileContent extends Base
{
    /**
     * Site to install ghost on
     *
     * @var \App\Site
     */
    public $site;

    public $server;

    /**
     * The new content
     *
     * @var string
     */
    public $content;

    public $path;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Site $site, string $path, string $content)
    {
        $this->site = $site;
        $this->path = $path;
        $this->content = $content;
        $this->server = $site->server;
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
cat > {$this->path} << EOF
{$this->content}
EOF
EOD;
    }
}
