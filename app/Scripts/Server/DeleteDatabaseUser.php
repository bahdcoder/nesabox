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

    public function getRootPassword()
    {
        if ($this->databaseUser->type === MYSQL8_DB) {
            return $this->server->mysql8_root_password;
        }

        if ($this->databaseUser->type === MYSQL_DB) {
            return $this->server->mysql_root_password;
        }

        if ($this->databaseUser->type === MARIA_DB) {
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

        switch ($this->databaseUser->type) {
            case MARIA_DB:
                return $this->generateMariadbScript($rootPassword);
            case MYSQL8_DB:
                return $this->generateMysql8Script($rootPassword);
            case MYSQL_DB:
                return $this->generateMariadbScript($rootPassword);
            default:
                return '';
        }
    }

    public function generateMysql8Script($rootPassword)
    {
        return <<<EOD
mysql --user="root" --password="{$rootPassword}" -e "DROP USER IF EXISTS'{$this->databaseUser->name}'@'{$this->server->ip_address}';";
mysql --user="root" --password="{$rootPassword}" -e "DROP USER IF EXISTS'{$this->databaseUser->name}'@'%';";
EOD;
    }

    public function generateMariadbScript($rootPassword)
    {
        return <<<EOD
mysql --user="root" --password="{$rootPassword}" -e "DROP USER IF EXISTS '{$this->databaseUser->name}'@'{$this->server->ip_address}';";
mysql --user="root" --password="{$rootPassword}" -e "DROP USER IF EXISTS '{$this->databaseUser->name}'@'%';";
EOD;
    }
}
