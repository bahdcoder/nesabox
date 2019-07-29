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
cat > app-new-mongodb-database-9490.js << EOF
    db.createUser ({
        user: "{$this->databaseUser->name}",
        pwd: "{$this->databaseUser->password}",
        roles: ['dbOwner']
    })
EOF

mongo {$this->database->name} app-new-mongodb-database-9490.js -u {$nesaUser->name} -p {$nesaUser->password} --authenticationDatabase admin

rm app-new-mongodb-database-9490.js

systemctl restart mongod

EOD;
    }

    public function generateMysqlPermissionsScript()
    {
        if (!$this->databaseUser) {
            return '';
        }

        return <<<EOD
mysql -e "GRANT ALL PRIVILEGES ON {$this->database->name}.* TO '{$this->databaseUser->name}'@'localhost'"

# Flush privileges
mysql -e "FLUSH PRIVILEGES"    
EOD;
    }

    public function generateMysqlCreateUserScript()
    {
        if (!$this->databaseUser) {
            return '';
        }

        return <<<EOD
mysql -e "CREATE USER '{$this->databaseUser->name}'@'localhost' IDENTIFIED BY '{$this->databaseUser->password}';"     
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
