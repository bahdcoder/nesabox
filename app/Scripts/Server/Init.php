<?php

namespace App\Scripts\Server;

use App\Server;

class Init
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
        return <<<EOD
#!/bin/sh
USER="espectra"
SUDO_PASSWORD="espectra"
SWAP_SIZE="1G"
apt-get update
apt-get upgrade -y
apt-get install -y --force-yes software-properties-common
apt-add-repository ppa:nginx/development -y
apt-add-repository ppa:chris-lea/redis-server -y
apt-get install -y --force-yes build-essential curl fail2ban gcc git libmcrypt4 libpcre3-dev \
make python2.7 python-pip supervisor ufw unattended-upgrades unzip whois zsh mc p7zip-full htop
sed -i "/PasswordAuthentication yes/d" /etc/ssh/sshd_config
echo "" | sudo tee -a /etc/ssh/sshd_config
echo "" | sudo tee -a /etc/ssh/sshd_config
echo "PasswordAuthentication no" | sudo tee -a /etc/ssh/sshd_config
if [ ! -d /root/.ssh ]
then
    mkdir -p /root/.ssh
    touch /root/.ssh/authorized_keys
fi
cat >> /root/.ssh/config << EOF
Host github.com
    StrictHostKeyChecking no
    IdentityFile ~/.ssh/espectra
EOF
useradd \$USER
mkdir -p /home/\$USER/.ssh
adduser \$USER sudo
chsh -s /bin/bash \$USER
cp /root/.profile /home/\$USER/.profile
cp /root/.bashrc /home/\$USER/.bashrc
PASSWORD=$(mkpasswd \$SUDO_PASSWORD)
usermod --password \$PASSWORD \$USER
usermod -a -G www-data \$USER
id \$USER
groups \$USER
cp /root/.ssh/config /home/\$USER/.ssh/config
cp /root/.ssh/authorized_keys /home/\$USER/.ssh/authorized_keys
chown -R espectra:espectra /home/\$USER
chmod 0700 /home/\$USER/.ssh
chmod 0600 /home/\$USER/.ssh/authorized_keys
ssh-keygen -A
service ssh restart
ufw allow 22
ufw allow 80
ufw allow 443
ufw --force enable
if [ -f /swapfile ]; then
    echo "Swap exists."
else
    fallocate -l \$SWAP_SIZE /swapfile
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    echo "/swapfile none swap sw 0 0" >> /etc/fstab
    echo "vm.swappiness=30" >> /etc/sysctl.conf
    echo "vm.vfs_cache_pressure=50" >> /etc/sysctl.conf
fi
apt-get install -y nginx
rm /etc/nginx/sites-enabled/default
rm /etc/nginx/sites-available/default
sudo ufw allow 'Nginx HTTP'
service nginx restart
apt-get install -y redis-server
add-apt-repository -y ppa:certbot/certbot
apt-get install -y python-certbot-nginx
{$this->getDatabasesInstallationScripts()}
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
                case 'mysql':
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
EOD;
                case 'mongodb':
                    $script .= <<<EOD
\n
MONGO_DB_ADMIN_USERNAME="{$database->databaseUser->name}"
MONGO_DB_ADMIN_PASSWORD="{$database->databaseUser->password}"
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 9DA31620334BD75D9DCB49F368818C72E52529D4
sudo echo "deb [ arch=amd64 ] https://repo.mongodb.org/apt/ubuntu bionic/mongodb-org/4.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-4.0.list
sudo apt-get update
sudo apt-get install -y mongodb-org
sudo service mongod start
sudo systemctl enable mongod
cat > app.js << EOF
db.createUser ({
    user: "\$MONGO_DB_ADMIN_USERNAME",
    pwd: "\$MONGO_DB_ADMIN_PASSWORD",
    roles: [ { role: "userAdminAnyDatabase", db: "admin" }, "readWriteAnyDatabase" ]
    }
)
printjson(db.getUsers())
EOF
mongo admin app.js
rm app.js
cat >> /etc/mongod.conf << EOF
security:
    authorization: enabled
EOF
EOD;
                default:
                    break;
            endswitch;
        endforeach;

        return $script;
    }
}
