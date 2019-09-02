<?php

namespace App\Scripts\Server;

use App\Server;
use App\Database;
use App\Scripts\Base;

class DeleteDatabase extends Base
{
    /**
     * The server to be initialized.
     *
     * @var \App\Server
     */
    public $server;

    /**
     *
     * The database
     *
     * @var \App\Database
     *
     */
    public $database;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server, Database $database)
    {
        $this->server = $server;
        $this->database = $database;
    }

    /**
     * Generate the add database script
     *
     * @return string
     */
    public function generate()
    {
        switch ($this->database->type) {
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
mysql --user="root" --password="{$rootPassword}" -e "DROP DATABASE IF EXISTS {$this->database->name}";
EOD;
    }
}
