<?php

namespace App\Scripts\Server;

use App\Server;
use App\Database;
use App\Scripts\Base;
use App\DatabaseUser;

class AddMongodbUser extends Base
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
        DatabaseUser $databaseUser
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
        $user = SSH_USER;

        $role = (bool) $this->databaseUser->read_only ? 'read' : 'readWrite';

        return <<<EOD
mongo {$this->database->name} --eval 'db.createUser({ user: "{$this->databaseUser->name}", pwd: "{$this->databaseUser->password}", roles: [{ "role": "{$role}", "db": "{$this->database->name}" }] })' -u {$user} -p {$this->server->mongodb_admin_password} --authenticationDatabase admin
EOD;
    }
}
