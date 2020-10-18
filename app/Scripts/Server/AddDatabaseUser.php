<?php

namespace App\Scripts\Server;

use App\Server;
use App\Scripts\Base;
use App\DatabaseUser;

class AddDatabaseUser extends Base
{
    /**
     * The server to be initialized.
     *
     * @var \App\Server
     */
    public $server;

    /**
     *
     * @var \App\DatabaseUser
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

    public function generateMariadbAddUserScript($rootPassword)
    {
        return <<<EOD
mysql --user="{$this->defaultUser}" --password="{$rootPassword}" -e "CREATE USER '{$this->databaseUser->name}'@'%' IDENTIFIED BY '{$this->databaseUser->password}';"
EOD;
    }

    public function generateMariadbPermissionsScript($rootPassword)
    {
        $script = '';

        foreach ($this->databaseUser->databases as $database):
            $script .= <<<EOD
\n
            mysql --user="{$this->defaultUser}" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON {$database->name}.* TO '{$this->databaseUser->name}'@'%' WITH GRANT OPTION;"
EOD;
        endforeach;

        return $script;
    }

    public function generateMariadbScript($rootPassword)
    {
        return <<<EOD
{$this->generateMariadbAddUserScript($rootPassword)}
{$this->generateMariadbPermissionsScript($rootPassword)}
mysql --user="{$this->defaultUser}" --password="{$rootPassword}" -e "FLUSH PRIVILEGES;"
EOD;
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

        if ($this->databaseUser->type === POSTGRES_DB) {
            return $this->server->postgres_root_password;
        }
    }

    public function generateMysql8PermissionsScript($rootPassword)
    {
        $script = '';

        foreach ($this->databaseUser->databases as $database):
            $script .= <<<EOD
\n
mysql --user="{$this->defaultUser}" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON {$database->name}.* TO '{$this->databaseUser->name}'@'%' WITH GRANT OPTION;"
EOD;
        endforeach;

        return $script;
    }

    public function generateMysql8AddUserScript($rootPassword)
    {
        return <<<EOD
mysql --user="{$this->defaultUser}" --password="{$rootPassword}" -e "CREATE USER '{$this->databaseUser->name}'@'%' IDENTIFIED WITH mysql_native_password BY '{$this->databaseUser->password}';"
EOD;
    }

    public function generateMysql8Script($rootPassword)
    {
        return <<<EOD
{$this->generateMysql8AddUserScript($rootPassword)}
{$this->generateMysql8PermissionsScript($rootPassword)}
mysql --user="{$this->defaultUser}" --password="{$rootPassword}" -e "FLUSH PRIVILEGES;"
EOD;
    }
}
