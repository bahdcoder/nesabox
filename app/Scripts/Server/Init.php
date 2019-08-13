<?php

namespace App\Scripts\Server;

use App\Server;
use App\Scripts\Base as BaseScript;

class Init extends BaseScript
{
    /**
     * The server to be initialized.
     *
     * @var \App\Server
     */
    public $server;

    /**
     * Initialize this class
     *
     * @return void
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Generate the init script
     *
     * @return string
     */
    public function generate()
    {
        $user = SSH_USER;

        $callbackEndpoint = route('servers.initialization-callback', [
            $this->server->id,
            'api_token' => $this->server->user->api_token
        ]);

        $defaultNginxConfigEndpoint = route('default-servers-nginx-config');

        return <<<EOD
#!/bin/sh

# Define script variables
USER="{$user}"
SUDO_PASSWORD="{$this->server->sudo_password}"
SWAP_SIZE="1G"
export DEBIAN_FRONTEND=noninteractive

# Set The Hostname If Necessary (on linode servers for example)

echo "{$this->server->name}" > /etc/hostname
sed -i 's/127\.0\.0\.1.*localhost/127.0.0.1     {$this->server->name}.localdomain {$this->server->name} localhost/' /etc/hosts
hostname {$this->server->name}

apt-get update
apt-get upgrade -y
apt-get install -y --force-yes software-properties-common
apt-add-repository ppa:nginx/development -y
apt-add-repository ppa:chris-lea/redis-server -y

# Install essential packages

apt-get install -y --force-yes build-essential curl fail2ban gcc git libmcrypt4 libpcre3-dev \
make python2.7 python-pip supervisor ufw unattended-upgrades unzip whois zsh mc p7zip-full htop

# Turn off password based authentication, and setup ssh

sed -i "/PasswordAuthentication yes/d" /etc/ssh/sshd_config
echo "" | sudo tee -a /etc/ssh/sshd_config
echo "" | sudo tee -a /etc/ssh/sshd_config
echo "PasswordAuthentication no" | sudo tee -a /etc/ssh/sshd_config

# Just in case root ssh folder does not exist, (for some providers), create it.

if [ ! -d /root/.ssh ]
then
    mkdir -p /root/.ssh
    touch /root/.ssh/authorized_keys
fi

# Automatically add github.com as a known host for the deployment user
cat >> /root/.ssh/config << EOF
Host github.com
    StrictHostKeyChecking no
    IdentityFile ~/.ssh/{$user}

Host gitlab.com
    StrictHostKeyChecking no
    IdentityFile ~/.ssh/{$user}

Host bitbucket.org
    StrictHostKeyChecking no
    IdentityFile ~/.ssh/{$user}
EOF

# Add deployment user
useradd {$user}
mkdir -p /home/{$user}/.ssh
mkdir -p /home/{$user}/.{$user}
adduser {$user} sudo

chsh -s /bin/bash {$user}
cp /root/.profile /home/{$user}/.profile
cp /root/.bashrc /home/{$user}/.bashrc

PASSWORD=$(mkpasswd \$SUDO_PASSWORD)
usermod --password \$PASSWORD {$user}

usermod -a -G www-data {$user}
id {$user}
groups {$user}

# Install node, npm and n

curl --silent --location https://deb.nodesource.com/setup_10.x | bash -

apt-get update

sudo apt-get install -y --force-yes nodejs

npm i -g n
# Install latest version of node
n latest

# Give permissions to nesa user to be able to manage npm and node
chown -R {$user} /usr/local
chmod -R 755 /usr/local

{$this->addSshKeysToServer()}

# Setup ssh keys for deployment user
cp /root/.ssh/config /home/{$user}/.ssh/config
cp /root/.ssh/authorized_keys /home/{$user}/.ssh/authorized_keys
chown -R {$user}:{$user} /home/{$user}
chown {$user} -R /home/{$user}
chmod -R 755 /home/{$user}
chmod 700 /home/{$user}/.ssh
chmod 600 /home/{$user}/.ssh/authorized_keys
ssh-keygen -A
service ssh restart

# Geenerate ssh key
ssh-keygen -f /home/{$user}/.ssh/{$user} -t rsa -b 4096 -P '' -C root@{$this->server->name}
chown nesa -R /home/{$user}/.ssh

# Enable default ufw ports
ufw allow 22
ufw allow 80
ufw allow 443
ufw --force enable

# Create swap file if it does not exist 
if [ -f /swapfile ]; then
    echo "A swap was already created by some providers (Linode)."
else
    fallocate -l \$SWAP_SIZE /swapfile
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    echo "/swapfile none swap sw 0 0" >> /etc/fstab
    echo "vm.swappiness=30" >> /etc/sysctl.conf
    echo "vm.vfs_cache_pressure=50" >> /etc/sysctl.conf
fi

# Make sure supervisor autostarts
systemctl enable supervisor.service
service supervisor start

# Install nginx

apt-get install -y nginx
rm /etc/nginx/sites-enabled/default
rm /etc/nginx/sites-available/default

# Enable https
sudo ufw allow 'Nginx HTTP'

# Update config to use default for nesabox servers
rm /etc/nginx/nginx.conf
curl -Ss '{$defaultNginxConfigEndpoint}' > /etc/nginx/nginx.conf

service nginx restart

# Install redis
apt-get install -y redis-server
sed -i 's/bind 127.0.0.1/bind 0.0.0.0/' /etc/redis/redis.conf
service redis-server restart
systemctl enable redis-server

# Install certbot
add-apt-repository -y ppa:certbot/certbot
apt-get install -y python-certbot-nginx

# Setup security updates
cat > /etc/apt/apt.conf.d/50unattended-upgrades << EOF
Unattended-Upgrade::Allowed-Origins {
    "Ubuntu bionic-security";
};
Unattended-Upgrade::Package-Blacklist {
    //
};
EOF

cat > /etc/apt/apt.conf.d/10periodic << EOF
APT::Periodic::Update-Package-Lists "1";
APT::Periodic::Download-Upgradeable-Packages "1";
APT::Periodic::AutocleanInterval "7";
APT::Periodic::Unattended-Upgrade "1";
EOF

# Install all databases user selected
{$this->getDatabasesInstallationScripts()}

# Setup the logs monitoring site
{$this->setupLogsMonitoringSite()}

generate_post_data()
{
    cat <<EOF
{
    "ssh_key": "$(cat /home/{$user}/.ssh/{$user}.pub)"
}
EOF
}

# Call API to mark this server as completely initialized.
curl -i \
-H "Accept: application/json" \
-H "Content-Type:application/json" \
-X POST --data "$(generate_post_data)" "{$callbackEndpoint}"
EOD;
    }

    public function setupLogsMonitoringSite()
    {
        $user = SSH_USER;
        $domain = $this->server->getLogWatcherSiteDomain();
        $apiUrl = config('app.url');
        return <<<EOD
su {$user} <<EOF
cd /home/{$user}
# Install latest version of node, just in case
n 12.8.0

# Install pm2 process manager
npm install -g pm2

# Change directory into the .nesa folder
cd ~/.{$user}

# Create the file watching project
mkdir -p /home/{$user}/.{$user}/nesabox-logs-watcher
curl -Ss '{$apiUrl}/logs-watcher-index-js' > /home/{$user}/.{$user}/nesabox-logs-watcher/index.js
curl -Ss '{$apiUrl}/logs-watcher-package-json' > /home/{$user}/.{$user}/nesabox-logs-watcher/package.json

cd /home/{$user}/.{$user}/nesabox-logs-watcher
npm install
export API_URL={$apiUrl}
export PORT=23443
export NODE_ENV=production
pm2 start index.js --name nesabox-logs-watcher --interpreter /usr/local/n/versions/node/12.8.0/bin/node
EOF

# Setup the nginx config
curl -Ss '{$apiUrl}/logs-watcher-nginx-config/{$this->server->id}' > /etc/nginx/sites-available/{$domain}

ln -s /etc/nginx/sites-available/{$domain} /etc/nginx/sites-enabled/
systemctl restart nginx

EOD;
    }

    /**
     * Return script to add ssh keys to server
     *
     * @return string
     */
    public function addSshKeysToServer()
    {
        $sshKey = $this->server
            ->sshkeys()
            ->where('is_app_key', true)
            ->first();

        return <<<EOD
cat > /root/.ssh/authorized_keys << EOF
# Nesabox key

{$sshKey->key}
EOF
EOD;
    }

    /**
     * Generate the scripts for installing all databases selected by user.
     *
     * @return string
     */
    public function getDatabasesInstallationScripts()
    {
        $script = '';
        foreach ($this->server->databaseInstances as $database):
            switch ($database->type):
                case MYSQL_DB:
                    $script .= <<<EOD
\n
apt-get install -y mysql-server
USER="{$database->databaseUser->name}"
MYSQL_ROOT_PASSWORD="{$database->databaseUser->password}"
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON *.* TO root@'localhost' IDENTIFIED BY '\$MYSQL_ROOT_PASSWORD';"
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON *.* TO root@'%' IDENTIFIED BY '\$MYSQL_ROOT_PASSWORD';"
service mysql restart
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "CREATE USER '\$USER'@'localhost' IDENTIFIED BY '\$MYSQL_ROOT_PASSWORD';"
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON *.* TO '\$USER'@'localhost' IDENTIFIED BY '\$MYSQL_ROOT_PASSWORD' WITH GRANT OPTION;"
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "GRANT ALL ON *.* TO '\$USER'@'%' IDENTIFIED BY '\$MYSQL_ROOT_PASSWORD' WITH GRANT OPTION;"
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "FLUSH PRIVILEGES;"
mysql --user="root" --password="\$MYSQL_ROOT_PASSWORD" -e "CREATE DATABASE {$database->name}";
EOD;
                    break;
                case MONGO_DB:
                    $script .= <<<EOD
\n
apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 9DA31620334BD75D9DCB49F368818C72E52529D4
echo "deb [ arch=amd64 ] https://repo.mongodb.org/apt/ubuntu bionic/mongodb-org/4.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-4.0.list
apt-get update
apt-get install -y mongodb-org
systemctl enable mongod
systemctl restart mongod

mongo << EOF
use admin
db.createUser({ user: '{$database->databaseUser->name}', pwd: '{$database->databaseUser->password}', roles: [{ role: 'root', db: 'admin' }, { role: 'userAdminAnyDatabase', db: 'admin' }]})
EOF

cat >> /etc/mongod.conf << EOF
security:
  authorization: "enabled"
EOF
systemctl restart mongod
EOD;
                    break;
                default:
                    break;
            endswitch;
        endforeach;

        return $script;
    }
}
