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

    public function getRootPassword()
    {
        if ($this->database->type === MYSQL8_DB) {
            return $this->server->mysql8_root_password;
        }

        if ($this->database->type === MYSQL_DB) {
            return $this->server->mysql_root_password;
        }

        if ($this->database->type === MARIA_DB) {
            return $this->server->mariadb_root_password;
        }
    }

    /**
     * Generate the add database script
     *
     * @return string
     */
    public function generate()
    {
        $rootPassword = $this->getRootPassword();

        switch ($this->database->type) {
            case MARIA_DB:
                return $this->generateMariadbScript($rootPassword);
            case MYSQL8_DB:
                return $this->generateMariadbScript($rootPassword);
            default:
                return '';
        }
    }

    public function generateMariadbScript($rootPassword)
    {
        return <<<EOD
mysql --user="root" --password="{$rootPassword}" -e "DROP DATABASE {$this->database->name}";
EOD;
    }
}
