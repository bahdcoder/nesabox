<?php

namespace App\Scripts\Server;

use App\Server;
use App\Database;
use App\Scripts\Base;
use App\DatabaseUser;

class AddDatabase extends Base
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

    public $databaseUser;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(
        Server $server,
        Database $database,
        DatabaseUser $databaseUser = null
    ) {
        $this->server = $server;
        $this->database = $database;
        $this->databaseUser = $databaseUser;
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

    public function generateMariadbAddUserScript()
    {
        if (!$this->databaseUser) {
            return '';
        }

        return <<<EOD
mysql --user="root" --password="{$this->server->mariadb_root_password}" -e "CREATE USER '{$this->databaseUser->name}'@'{$this->server->ip_address}' IDENTIFIED BY '{$this->databaseUser->password}';"
EOD;
    }

    public function generateMariadbPermissionsScript()
    {
        $rootPassword = $this->server->mariadb_root_password;

        if ($this->databaseUser) {
            return <<<EOD
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON {$this->database->name}.* TO '{$this->databaseUser->name}'@'localhost'"
EOD;
        }

        return '';
    }

    public function generateMariadbScript()
    {
        $rootPassword = $this->server->mariadb_root_password;

        return <<<EOD
{$this->generateMariadbAddUserScript()}
mysql --user="root" --password="{$rootPassword}" -e "CREATE DATABASE IF NOT EXISTS {$this->database->name};"
{$this->generateMariadbPermissionsScript()}
mysql --user="root" --password="{$rootPassword}" -e "FLUSH PRIVILEGES;" 
EOD;
    }
}
