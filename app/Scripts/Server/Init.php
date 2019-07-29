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
        return <<<EOD
#!/bin/sh

# Define script variables
USER="{$user}"
SUDO_PASSWORD="{$user}"
SWAP_SIZE="1G"
export DEBIAN_FRONTEND=noninteractive

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
    IdentityFile ~/.ssh/\$USER

Host gitlab.com
    StrictHostKeyChecking no
    IdentityFile ~/.ssh/\$USER

Host bitbucket.org
    StrictHostKeyChecking no
    IdentityFile ~/.ssh/\$USER
EOF

# Add deployment user
useradd \$USER
mkdir -p /home/\$USER/.ssh
mkdir -p /home/\$USER/.\$USER
adduser \$USER sudo

chsh -s /bin/bash \$USER
cp /root/.profile /home/\$USER/.profile
cp /root/.bashrc /home/\$USER/.bashrc

PASSWORD=$(mkpasswd \$SUDO_PASSWORD)
usermod --password \$PASSWORD \$USER

usermod -a -G www-data \$USER
id \$USER
groups \$USER

{$this->addSshKeysToServer()}

# Setup ssh keys for deployment user
cp /root/.ssh/config /home/\$USER/.ssh/config
cp /root/.ssh/authorized_keys /home/\$USER/.ssh/authorized_keys
chown -R \$USER:\$USER /home/\$USER
chmod 0700 /home/\$USER/.ssh
chmod 0600 /home/\$USER/.ssh/authorized_keys
ssh-keygen -A
service ssh restart

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

# Install node, npm and n

curl -o- https://deb.nodesource.com/setup_10.x | bash
apt-get install nodejs
npm i -g n
n latest
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
MONGO_DB_ADMIN_USERNAME="{$database->databaseUser->name}"
MONGO_DB_ADMIN_PASSWORD="{$database->databaseUser->password}"
apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 9DA31620334BD75D9DCB49F368818C72E52529D4
echo "deb [ arch=amd64 ] https://repo.mongodb.org/apt/ubuntu bionic/mongodb-org/4.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-4.0.list
apt-get update
apt-get install -y mongodb-org
systemctl enable mongod
systemctl restart mongod

mongo --eval "db.getSiblingDB('admin').createUser({ user: '{$database->databaseUser->name}', pwd: '{$database->databaseUser->password}', roles: [{ role: 'userAdminAnyDatabase', db: 'admin' }]})"
mongo << EOF
use admin
db.createUser({ user: '{$database->databaseUser->name}', pwd: '{$database->databaseUser->password}', roles: [{ role: 'userAdminAnyDatabase', db: 'admin' }]})
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
