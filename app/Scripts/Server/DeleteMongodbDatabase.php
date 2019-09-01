<?php

namespace App\Scripts\Server;

use App\Server;
use App\Database;
use App\Scripts\Base;

class DeleteMongodbDatabase extends Base
{
    public $server;

    public $database;

    public function __construct(Server $server, Database $database)
    {
        $this->server = $server;
        $this->database = $database;
    }

    public function generate()
    {
        $rootUser = 'admin';

        return <<<EOD
mongo {$this->database->name} --eval 'db.dropDatabase()' -u {$rootUser} -p {$this->server->mongodb_admin_password} --authenticationDatabase admin
mongo {$this->database->name} --eval 'db.dropAllUsers()' -u {$rootUser} -p {$this->server->mongodb_admin_password} --authenticationDatabase admin
EOD;
    }
}
