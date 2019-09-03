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
        Database $database,
        $databaseUser = null
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
        $rootPassword = $this->getRootPassword();


        switch ($this->database->type) {
            case MARIA_DB:
                return $this->generateSqlScript();
            case MYSQL8_DB:
                return $this->generateMysql8Script($rootPassword);
            case MYSQL_DB:
                return $this->generateSqlScript();
            default:
                return '';
        }
    }

    public function generateSqlAddUserScript()
    {
        if (!$this->databaseUser) {
            return '';
        }

        return <<<EOD
mysql --user="root" --password="{$this->getRootPassword()}" -e "CREATE USER '{$this->databaseUser->name}'@'{$this->server->ip_address}' IDENTIFIED BY '{$this->databaseUser->password}';"
EOD;
    }

    public function generateSqlPermissionsScript()
    {
        $rootPassword = $this->getRootPassword();

        if ($this->databaseUser) {
            return <<<EOD
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON {$this->database->name}.* TO '{$this->databaseUser->name}'@'{$this->server->ip_address}' IDENTIFIED BY '{$this->databaseUser->password}';"
EOD;
        }

        return '';
    }

    public function generateSqlScript()
    {
        $rootPassword = $this->getRootPassword();

        return <<<EOD
{$this->generateSqlAddUserScript()}
mysql --user="root" --password="{$rootPassword}" -e "CREATE DATABASE IF NOT EXISTS {$this->database->name};"
{$this->generateSqlPermissionsScript()}
mysql --user="root" --password="{$rootPassword}" -e "FLUSH PRIVILEGES;" 
EOD;
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

    public function generateMysql8AddUserScript($rootPassword)
    {
        if (! $this->databaseUser) {
            return '';
        }

        return <<<EOD
mysql --user="root" --password="{$rootPassword}" -e "CREATE USER '{$this->databaseUser->name}'@'{$this->server->ip_address}' IDENTIFIED WITH mysql_native_password BY '{$this->databaseUser->password}';"
mysql --user="root" --password="{$rootPassword}" -e "CREATE USER '{$this->databaseUser->name}'@'%' IDENTIFIED WITH mysql_native_password BY '{$this->databaseUser->password}';"
EOD;
    }

    public function generateMysql8PermissionsScript($rootPassword) {
        if (! $this->databaseUser) {
            return '';
        }

        return <<<EOD
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON {$this->database->name}.* TO '{$this->databaseUser->name}'@'{$this->server->ip_address}' WITH GRANT OPTION;"
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON {$this->database->name}.* TO '{$this->databaseUser->name}'@'%' WITH GRANT OPTION;"
EOD;
    }

    public function generateMysql8Script($rootPassword)
    {
        return <<<EOD
{$this->generateMysql8AddUserScript($rootPassword)}
mysql --user="root" --password="{$rootPassword}" -e "CREATE DATABASE {$this->database->name} CHARACTER SET utf8 COLLATE utf8_unicode_ci;"
{$this->generateMysql8PermissionsScript($rootPassword)}
mysql --user="root" --password="{$rootPassword}" -e "FLUSH PRIVILEGES;" 
EOD;
    }
}
