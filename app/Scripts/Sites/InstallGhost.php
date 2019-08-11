<?php

namespace App\Scripts\Sites;

use App\Site;
use App\Server;
use App\Database;
use App\Scripts\Base;
use App\DatabaseUser;
use App\Scripts\Server\AddDatabase;

class InstallGhost extends Base
{
    /**
     * The server.
     *
     * @var \App\Server
     */
    public $server;

    /**
     * Site to install ghost on
     *
     * @var \App\Site
     */
    public $site;

    /**
     * Database user for ghost
     *
     * @var \App\DatabaseUser
     */
    public $databaseUser;

    /**
     * Database for ghost
     *
     * @var \App\Database
     */
    public $database;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(
        Server $server,
        Site $site,
        DatabaseUser $databaseUser,
        Database $database
    ) {
        $this->site = $site;
        $this->server = $server;
        $this->database = $database;
        $this->databaseUser = $databaseUser;
    }

    /**
     * Generate the init script
     *
     * @return string
     */
    public function generate()
    {
        $user = SSH_USER;

        return <<<EOD
# Create site folder
mkdir /home/{$user}/{$this->site->name}

# Change folder owner to espectra
# chown {$user}:{$user} /home/{$user}/{$this->site->name}

# Change folder permissions
# chmod 775 /home/{$user}/{$this->site->name}

# Change directory to the site folder
cd /home/{$user}/{$this->site->name}

# Download the latest ghost
curl -L https://ghost.org/zip/ghost-latest.zip -o ghost-latest.zip

# Unzip download
unzip ghost-latest.zip -d ./

# Delete zip file
rm ghost-latest.zip

# Install latest node version supported by ghost
n 10.13.0

# Install ghost dependencies
npm i -g yarn

# Install pm2
npm install -g pm2

# Generate ghost blog config
cat > /home/{$user}/{$this->site->name}/config.production.json << EOF
{
    "url": "http://{$this->site->name}",
    "database": {
        "client": "mysql",
        "connection": {
            "host"     : "127.0.0.1",
            "user"     : "{$this->databaseUser->name}",
            "password" : "{$this->databaseUser->password}",
            "database" : "{$this->database->name}"
        }
    },
    "server": {
        "host": "127.0.0.1",
        "port": {$this->site->environment['PORT']}
    },
    "paths": {
        "contentPath": "content/"
    },
    "logging": {
        "level": "info",
        "rotation": {
            "enabled": true
        },
        "transports": ["file", "stdout"]
    }
}
EOF

# Change directory to the site folder
cd /home/{$user}/{$this->site->name}

# Install all dependencies
yarn install --production

# Install knex migrator
yarn add knex-migrator

# Run knex migrations
NODE_ENV=production yarn knex-migrator init

# We are going to manually remove migration lock for now
mysql -e "UPDATE migrations_lock set locked=0 where lock_key='km01';" -u {$this->databaseUser->name} -p'{$this->databaseUser->password}' {$this->database->name}

# Start the first pm2 instance
NODE_ENV=production pm2 start index.js --name {$this->site->name} --interpreter /usr/local/n/versions/node/10.13.0/bin/node
EOD;
    }
}
