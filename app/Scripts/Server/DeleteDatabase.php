<?php

namespace App\Scripts\Server;

use App\Server;
use App\Database;
use App\Scripts\Base;

class DeleteDatabase extends Base
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
        return <<<EOD

EOD;
    }

    public function generateMysqlDeleteUserScript()
    {
        if ($this->database->databaseUser->status !== STATUS_DELETING) {
            return '';
        }

        return <<<EOD
mysql -e "DROP USER '{$this->database->databaseUser->name}'@'localhost'";
EOD;
    }

    public function generateMysqlScript()
    {
        return <<<EOD
mysql -e "DROP DATABASE IF EXISTS {$this->database->name}";
{$this->generateMysqlDeleteUserScript()}
EOD;
    }
}
