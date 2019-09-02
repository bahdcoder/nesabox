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
        switch ($this->databaseUser->type) {
            case MARIA_DB:
                return $this->generateMariadbScript();
            default:
                return '';
        }
    }

    public function generateMariadbAddUserScript()
    {
        return <<<EOD
mysql --user="root" --password="{$this->server->mariadb_root_password}" -e "CREATE USER '{$this->databaseUser->name}'@'{$this->server->ip_address}' IDENTIFIED BY '{$this->databaseUser->password}';"
EOD;
    }

    public function generateMariadbPermissionsScript()
    {
        $rootPassword = $this->server->mariadb_root_password;

        $script = '';

        foreach($this->databaseUser->databases as $database):
            $script .= <<<EOD
\n
            mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON {$database->name}.* TO '{$this->databaseUser->name}'@'{$this->server->ip_address}' IDENTIFIED BY '{$this->databaseUser->password}';"
EOD;
        endforeach;

        return $script;
    }

    public function generateMariadbScript()
    {
        $rootPassword = $this->server->mariadb_root_password;

        return <<<EOD
{$this->generateMariadbAddUserScript()}
{$this->generateMariadbPermissionsScript()}
mysql --user="root" --password="{$rootPassword}" -e "FLUSH PRIVILEGES;"
EOD;
    }
}
