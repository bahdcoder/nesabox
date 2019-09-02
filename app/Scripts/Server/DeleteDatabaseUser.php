<?php

namespace App\Scripts\Server;

use App\Server;
use App\DatabaseUser;
use App\Scripts\Base;

class DeleteDatabaseUser extends Base
{
    /**
     * The server to be initialized.
     *
     * @var \App\Server
     */
    public $server;

    /**
     *
     * The database user
     *
     * @var \App\DatabaseUser
     *
     */
    public $databaseUser;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server, DatabaseUser $databaseUser)
    {
        $this->server = $server;
        $this->databaseUser = $databaseUser;
    }

    /**
     * Generate the add database script
     *
     * @return string
     */
    public function generate()
    {
        switch ($this->databaseUser->type) {
            case MARIA_DB:
                return $this->generateMariadbScript();
            default:
                return '';
        }
    }

    public function generateMariadbScript()
    {
        $rootPassword = $this->server->mariadb_root_password;

        return <<<EOD
mysql --user="root" --password="{$rootPassword}" -e "DROP USER '{$this->databaseUser->name}'@'{$this->server->ip_address}';";
EOD;
    }
}
