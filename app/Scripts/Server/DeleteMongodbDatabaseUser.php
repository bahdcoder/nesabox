<?php

namespace App\Scripts\Server;

use App\Server;
use App\Database;
use App\DatabaseUser;
use App\Scripts\Base;

class DeleteMongodbDatabaseUser extends Base
{
    public $server;

    public $database;

    public $databaseUser;

    public function __construct(Server $server, Database $database, DatabaseUser $databaseUser)
    {
        $this->server = $server;
        $this->database = $database;
        $this->databaseUser = $databaseUser;
    }

    public function generate()
    {
        $rootUser = 'admin';

        return <<<EOD
mongo {$this->database->name} --eval 'db.dropUser("{$this->databaseUser->name}")' -u {$rootUser} -p {$this->server->mongodb_admin_password} --authenticationDatabase admin
EOD;
    }
}
