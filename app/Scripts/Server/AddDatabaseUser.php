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
    public function __construct(
        Server $server,
        DatabaseUser $databaseUser
    ) {
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
            default:
                return '';
        }
    }

    public function generateMariadbAddUserScript($rootPassword)
    {
        return <<<EOD
mysql --user="root" --password="{$rootPassword}" -e "CREATE USER '{$this->databaseUser->name}'@'{$this->server->ip_address}' IDENTIFIED BY '{$this->databaseUser->password}';"
EOD;
    }

    public function generateMariadbPermissionsScript($rootPassword)
    {
        $script = '';

        foreach($this->databaseUser->databases as $database):
            $script .= <<<EOD
\n
            mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON {$database->name}.* TO '{$this->databaseUser->name}'@'{$this->server->ip_address}' IDENTIFIED BY '{$this->databaseUser->password}';"
EOD;
        endforeach;

        return $script;
    }

    public function generateMariadbScript($rootPassword)
    {
        return <<<EOD
{$this->generateMariadbAddUserScript($rootPassword)}
{$this->generateMariadbPermissionsScript($rootPassword)}
mysql --user="root" --password="{$rootPassword}" -e "FLUSH PRIVILEGES;"
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
    }

    public function generateMysql8PermissionsScript($rootPassword)
    {
        $script = '';

        foreach($this->databaseUser->databases as $database):
            $script .= <<<EOD
\n
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON {$database->name}.* TO '{$this->databaseUser->name}'@'{$this->server->ip_address}' WITH GRANT OPTION;"
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON {$database->name}.* TO '{$this->databaseUser->name}'@'%' WITH GRANT OPTION;"
EOD;
        endforeach;

        return $script;
    }

    public function generateMysql8AddUserScript($rootPassword)
    {
        return <<<EOD
mysql --user="root" --password="{$rootPassword}" -e "CREATE USER '{$this->databaseUser->name}'@'{$this->server->ip_address}' IDENTIFIED WITH mysql_native_password BY '{$this->databaseUser->password}';"
mysql --user="root" --password="{$rootPassword}" -e "CREATE USER '{$this->databaseUser->name}'@'%' IDENTIFIED WITH mysql_native_password BY '{$this->databaseUser->password}';"
EOD;
    }

    public function generateMysql8Script($rootPassword) {
        return <<<EOD
{$this->generateMysql8AddUserScript($rootPassword)}
{$this->generateMysql8PermissionsScript($rootPassword)}
mysql --user="root" --password="{$rootPassword}" -e "FLUSH PRIVILEGES;"
EOD;
    }
}
