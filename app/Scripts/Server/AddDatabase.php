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
            case MYSQL_DB:
                return $this->generateMysqlScript();
            case MONGO_DB:
                return $this->generateMongodbScript();
            default:
                return '';
        }
    }

    public function generateMongodbScript()
    {
        $nesaUser = \App\DatabaseUser::where('server_id', $this->server->id)
            ->where('name', SSH_USER)
            ->first();

        return <<<EOD
mongo --eval "db.getSiblingDB('admin').createUser({ user: '{$this->databaseUser->name}', pwd: '{$this->databaseUser->password}', roles: ['dbOwner']})" -u {$nesaUser->name} -p {$nesaUser->password} --authenticationDatabase admin
EOD;
    }

    public function generateMysqlPermissionsScript()
    {
        return <<<EOD
mysql -e "GRANT ALL PRIVILEGES ON {$this->database->name}.* TO '{$this->database->databaseUser->name}'@'localhost'"

# Flush privileges
mysql -e "FLUSH PRIVILEGES"    
EOD;
    }

    public function generateMysqlCreateUserScript()
    {
        $user = DatabaseUser::where('name', SSH_USER)
            ->where('server_id', $this->database->server->id)
            ->first();

        if ($user->id === $this->database->database_user_id) {
            return '';
        }

        return <<<EOD
mysql -e "CREATE USER '{$this->database->databaseUser->name}'@'localhost' IDENTIFIED BY '{$this->database->databaseUser->password}';"     
EOD;
    }

    public function generateMysqlScript()
    {
        return <<<EOD
{$this->generateMysqlCreateUserScript()}

mysql -e "CREATE DATABASE {$this->database->name}";

{$this->generateMysqlPermissionsScript()}
EOD;
    }
}
