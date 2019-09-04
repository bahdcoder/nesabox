<?php

namespace App\Scripts\Server;

use App\Database;
use App\Notifications\Servers\Alert;
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

        $nesaboxIp = config('nesa.ip');

        $metricsPort = config('nesa.metrics_port');

        $sudoPassword = str_random(32);

        return <<<EOD
#!/bin/sh

# Define script variables
USER="{$user}"
IP_ADDRESS=\$(curl ifconfig.co)
SUDO_PASSWORD="{$this->server->sudo_password}"
SWAP_SIZE="1G"
export DEBIAN_FRONTEND=noninteractive

sudo sed -i "s/#precedence ::ffff:0:0\/96  100/precedence ::ffff:0:0\/96  100/" /etc/gai.conf
# Set The Hostname If Necessary (on linode servers for example)

echo "{$this->server->name}" > /etc/hostname
sed -i 's/127\.0\.0\.1.*localhost/127.0.0.1     {$this->server->name}.localdomain {$this->server->name} localhost/' /etc/hosts
hostname {$this->server->name}

ln -sf /usr/share/zoneinfo/UTC /etc/localtime

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
mkdir -p /home/{$user}/.{$user}/ecosystems
adduser {$user} sudo

chsh -s /bin/bash {$user}
cp /root/.profile /home/{$user}/.profile
cp /root/.bashrc /home/{$user}/.bashrc

PASSWORD=$(mkpasswd {$sudoPassword})
usermod --password \$PASSWORD {$user}

usermod -a -G www-data {$user}
id {$user}
groups {$user}

# Install node, npm and n

curl --silent --location https://deb.nodesource.com/setup_10.x | bash -

apt-get update

sudo apt-get install -y --force-yes nodejs

node -v
npm -v

# Install an npm package used to replace lines in files. I can't kill myself abeg
npm i -g replace-in-file

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

# Enable access to nesabox IP ADDRESS for this server
ufw allow from {$nesaboxIp} to any port {$metricsPort}
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

# supervisor autostarts config
systemctl enable supervisor.service
service supervisor start

# Install nginx

apt-get install -y nginx
rm /etc/nginx/sites-enabled/default
rm /etc/nginx/sites-available/default

# Clone the h5bp nginx configs
git clone https://github.com/h5bp/server-configs-nginx.git /etc/nginx/h5bp-repository
mv /etc/nginx/h5bp-repository/h5bp /etc/nginx/h5bp
cp /etc/nginx/h5bp-repository/nginx.conf /etc/nginx/nginx.conf

# Make sure to change location of sites from conf.d to sites-enabled
cd /etc/nginx
replace-in-file 'include conf.d/*.conf;' 'include /etc/nginx/sites-enabled/*;' nginx.conf

cd ~

# We'll change the folder for available nginx configurations from conf.d/*.conf to /sites-available/*
rm -r /etc/nginx/h5bp-repository

# Add nesa configs folder
mkdir /etc/nginx/nesa-conf

# Enable https
sudo ufw allow 'Nginx HTTP'
sudo ufw allow 'Nginx HTTPS'
service nginx restart

# Install redis
apt-get install -y redis-server
sed -i 's/bind 127.0.0.1/bind 0.0.0.0/' /etc/redis/redis.conf
service redis-server restart
systemctl enable redis-server

# Setup beanstalkd
apt-get install -y --force-yes beanstalkd
sed -i "s/BEANSTALKD_LISTEN_ADDR.*/BEANSTALKD_LISTEN_ADDR=0.0.0.0/" /etc/default/beanstalkd

if grep START= /etc/default/beanstalkd; then
    sed -i 's/#START=yes/START=yes/' /etc/default/beanstalkd
else
    echo 'START=yes' >> /etc/default/beanstalkd
fi

service beanstalkd start
sleep 6
service beanstalkd restart

systemctl enable beanstalkd

# Install acme.sh for issuing let'sencrypt certificates
cd ~
git clone https://github.com/Neilpang/acme.sh.git
cd ./acme.sh
./acme.sh --install
cd ~
rm -rf acme.sh

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

# Install netdata

# Install latest versions of node and pm2
{$this->installLatestNodeAndPm2()}

# Configure git with user details.
git config --global user.email "{$this->server->user->email}"
git config --global user.name "{$this->server->user->name}"

# Setup the server monitoring script

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

    public function installLatestNodeAndPm2()
    {
        $user = SSH_USER;
        return <<<EOD
su {$user} <<EOF
cd ~
n lts
npm i -g pm2
npm i -g yarn
EOF
EOD;
    }

    public function netDataConfigMysql()
    {
        $database_user = str_random(12);
        $database_pass = str_random(12);

        if (!in_array(MYSQL_DB, $this->server->databases)) {
            return '';
        }

        return <<<EOD
mysql -e "CREATE USER '{$database_user}'@'localhost' IDENTIFIED BY '{$database_pass}';"
mysql -e "GRANT USAGE ON *.* TO {$database_user}@localhost IDENTIFIED BY '{$database_pass}';"
mysql -e "FLUSH PRIVILEGES;"

# Create a mysql.conf file to enable the mysql netdata plugin
cat >> /etc/netdata/python.d/mysql.conf << EOF
tcp:
    name: 'local'
    host: '127.0.0.1'
    port: '3306'
    user: '{$database_user}'
    pass: '{$database_pass}'
EOF   
EOD;
    }

    public function netDataConfigMongodb()
    {
        if (!in_array(MONGO_DB, $this->server->databases)) {
            return '';
        }

        $database_user = str_random(12);
        $database_pass = str_random(12);

        // Let's get the auth user for mongo db
        $mongodbDatabaseUser = $this->server
            ->databaseUsers()
            ->where('type', MONGO_DB)
            ->first();

        $mongodb_auth_user = '';
        $mongodb_auth_password = '';

        if ($mongodbDatabaseUser) {
            $mongodb_auth_user = $mongodbDatabaseUser->name;
            $mongodb_auth_password = $mongodbDatabaseUser->password;
        }

        return <<<EOD
# Create a mongodb user for monitoring
mongo --eval "db.createUser({ user: '{$database_user}', pwd: '{$database_pass}', roles: [{role: 'read', db: 'admin' }, { role: 'clusterMonitor', db: 'admin' }, {role: 'read', db: 'local' }]})" -u {$mongodb_auth_user} -p {$mongodb_auth_password} --authenticationDatabase admin

# Create a mongodb.conf file to enable the mongodb netdata plugin
cat >> /etc/netdata/python.d/mongodb.conf << EOF
    local:
        name   : 'local'
        host   : '127.0.0.1'
        port   : 27017
        authdb : 'admin'
        user   : '{$database_user}'
        pass   : '{$database_pass}'
EOF
EOD;
    }

    public function setupServerMonitoringScript()
    {
        $user = SSH_USER;
        $apiUrl = config('app.url');

        return <<<EOD
# Create system.service file
cat > /lib/systemd/system/nesa-metrics.service << EOF
[Unit]
Description=Server metrics available on https://nesabox.com
Documentation=https://docs.nesabox.com
After=network.target

[Service]
Type=simple
User=nesa  
ExecStart=/usr/local/n/versions/node/12.8.1/bin/node /home/nesa/.nesa/nesa-metrics/index.js
Restart=on-failure

[Install]
WantedBy=multi-user.target
EOF

su {$user} <<EOF
cd /home/{$user}
# Install latest version of node, just in case
n 12.8.1

# Install pm2 process manager
npm install -g pm2

# Change directory into the .nesa folder
cd ~/.{$user}

# Create the nesa-metrics project
mkdir -p /home/{$user}/.{$user}/nesa-metrics

curl -Ss '{$apiUrl}/nesa-metrics/package.json' > /home/{$user}/.{$user}/nesa-metrics/package.json
curl -Ss '{$apiUrl}/nesa-metrics/index.js' > /home/{$user}/.{$user}/nesa-metrics/index.js

cd /home/{$user}/.{$user}/nesa-metrics
npm install
EOF

systemctl daemon-reload
systemctl start nesa-metrics
EOD;
    }

    public function setupLogsMonitoringSite()
    {
        return;
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
pm2 startOrReload index.js --name nesabox-logs-watcher --interpreter /usr/local/n/versions/node/12.8.0/bin/node
EOF

# Setup the nginx config
# curl -Ss '{$apiUrl}/logs-watcher-nginx-config/{$this->server->id}' > /etc/nginx/sites-available/{$domain}

# ln -s /etc/nginx/sites-available/{$domain} /etc/nginx/sites-enabled/
# systemctl restart nginx

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
cat >> /root/.ssh/authorized_keys << EOF
# Nesabox key

{$sshKey->key}
EOF
EOD;
    }

    public function installMysql()
    {
        $user = SSH_USER;

        $rootPassword = $this->server->mysql_root_password;
        return <<<EOD
\n
export DEBIAN_FRONTEND=noninteractive
debconf-set-selections <<< "mysql-community-server mysql-community-server/data-dir select ''"
debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password ${rootPassword}"
debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password ${rootPassword}"
apt-get install -y mysql-server
echo "default_password_lifetime = 0" >> /etc/mysql/mysql.conf.d/mysqld.cnf
sed -i '/^bind-address/s/bind-address.*=.*/bind-address = */' /etc/mysql/mysql.conf.d/mysqld.cnf
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL ON *.* TO root@'\$IP_ADDRESS' IDENTIFIED BY '{$rootPassword}';"
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL ON *.* TO root@'%' IDENTIFIED BY '{$rootPassword}';"
service mysql restart
mysql --user="root" --password="{$rootPassword}" -e "CREATE USER '{$user}'@'\$IP_ADDRESS' IDENTIFIED BY '{$rootPassword}';"
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL ON *.* TO '{$user}'@'\$IP_ADDRESS' IDENTIFIED BY '{$rootPassword}' WITH GRANT OPTION;"
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL ON *.* TO '{$user}'@'%' IDENTIFIED BY '{$rootPassword}' WITH GRANT OPTION;"
mysql --user="root" --password="{$rootPassword}" -e "FLUSH PRIVILEGES;"

service mysql restart
EOD;
    }

    public function installMysql8()
    {
        $user = SSH_USER;

        $rootPassword = $this->server->mysql8_root_password;

        return <<<EOD
\n
export DEBIAN_FRONTEND=noninteractive
wget -c https://dev.mysql.com/get/mysql-apt-config_0.8.12-1_all.deb
dpkg --install mysql-apt-config_0.8.12-1_all.deb
debconf-set-selections <<< "mysql-community-server mysql-community-server/data-dir select ''"
debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password {$rootPassword}"
debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password {$rootPassword}"
apt-get update
apt-get install -y mysql-server
echo "default_password_lifetime = 0" >> /etc/mysql/mysql.conf.d/mysqld.cnf
sed -i '/^bind-address/s/bind-address.*=.*/bind-address = */' /etc/mysql/mysql.conf.d/mysqld.cnf
mysql --user="root" --password="{$rootPassword}" -e "CREATE USER 'root'@'\$IP_ADDRESS' IDENTIFIED WITH mysql_native_password BY '{$rootPassword}';"
mysql --user="root" --password="{$rootPassword}" -e "CREATE USER 'root'@'%' IDENTIFIED WITH mysql_native_password BY '{$rootPassword}';"
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON *.* TO root@'\$IP_ADDRESS' WITH GRANT OPTION;"
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON *.* TO root@'%' WITH GRANT OPTION;"
service mysql restart
mysql --user="root" --password="{$rootPassword}" -e "CREATE USER '{$user}'@'\$IP_ADDRESS' IDENTIFIED WITH mysql_native_password BY '{$rootPassword}';"
mysql --user="root" --password="{$rootPassword}" -e "CREATE USER '{$user}'@'%' IDENTIFIED WITH mysql_native_password BY '{$rootPassword}';"
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON *.* TO '{$user}'@'\$IP_ADDRESS' WITH GRANT OPTION;"
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL PRIVILEGES ON *.* TO '{$user}'@'%' WITH GRANT OPTION;"
mysql --user="root" --password="{$rootPassword}" -e "FLUSH PRIVILEGES;"
EOD;
    }

    public function installMongodb()
    {
        // for mongodb, first we'll create a root user
        // then we'll create a default nesa user (with password) in a default nesa database

        $rootUser = 'admin';

        $user = 'nesa';

        $rootPassword = $this->server->mongodb_admin_password;
        return <<<EOD
\n
wget -qO - https://www.mongodb.org/static/pgp/server-4.2.asc | sudo apt-key add -
echo "deb [ arch=amd64 ] https://repo.mongodb.org/apt/ubuntu bionic/mongodb-org/4.2 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-4.2.list

sudo apt-get update
sudo apt-get install -y mongodb-org=4.2.0 mongodb-org-server=4.2.0 mongodb-org-shell=4.2.0 mongodb-org-mongos=4.2.0 mongodb-org-tools=4.2.0
sudo service mongod start

sleep 3
# Create mongo admin user
mongo admin --eval 'db.createUser ({user: "{$rootUser}",pwd: "{$rootPassword}",roles: [{ role: "userAdminAnyDatabase", db: "admin" }, { role: "root", db: "admin" }, "readWriteAnyDatabase" ]})'

replace-in-file '#security:' '' /etc/mongod.conf
replace-in-file '127.0.0.1' '0.0.0.0' /etc/mongod.conf
cat >> /etc/mongod.conf << EOF
security:
    authorization: "enabled"
EOF

systemctl restart mongod

sleep 3

# Create the nesa user we'll use for database / user creation
mongo admin --eval 'db.createUser({ user: "{$user}", pwd: "{$rootPassword}", roles: [{ role: "userAdminAnyDatabase", db: "admin" }, "readWriteAnyDatabase"] })' -u {$rootUser} -p {$rootPassword} --authenticationDatabase admin
EOD;
    }

    public function installPostgres()
    {
        $user = SSH_USER;

        $rootPassword = $this->server->postgres_root_password;

        return <<<EOD
\n
wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt/ `lsb_release -cs`-pgdg main" >> /etc/apt/sources.list.d/pgdg.list'
sudo apt-get update
sudo apt-get install -y --force-yes postgresql postgresql-contrib

# Postgres configs
sudo sed -i "s/localtime/UTC/" /etc/postgresql/11/main/postgresql.conf
sed -i "s/#listen_addresses = 'localhost'/listen_addresses = '*'/g" /etc/postgresql/11/main/postgresql.conf
echo "host    all             all             0.0.0.0/0               md5" | tee -a /etc/postgresql/11/main/pg_hba.conf
sudo -u postgres psql -c "CREATE ROLE {$user} LOGIN PASSWORD '{$rootPassword}' SUPERUSER INHERIT CREATEDB CREATEROLE NOREPLICATION;"
service postgresql restart
EOD;
    }

    public function installMariadb()
    {
        $user = SSH_USER;
        $rootPassword = $this->server->mariadb_root_password;

        return <<<EOD
\n
export DEBIAN_FRONTEND=noninteractive
sudo apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8
sudo add-apt-repository 'deb [arch=amd64,i386] http://mirrors.accretive-networks.net/mariadb/repo/10.3/ubuntu xenial main'
apt-get update
debconf-set-selections <<< "mariadb-server-10.3 mysql-server/data-dir select ''"
debconf-set-selections <<< "mariadb-server-10.3 mysql-server/root_password password {$rootPassword}"
debconf-set-selections <<< "mariadb-server-10.3 mysql-server/root_password_again password {$rootPassword}"
apt-get install -y mariadb-server-10.3
sed -i '/^bind-address/s/bind-address.*=.*/bind-address = */' /etc/mysql/my.cnf
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL ON *.* TO root@'\$IP_ADDRESS' IDENTIFIED BY '{$rootPassword}';"
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL ON *.* TO root@'%' IDENTIFIED BY '{$rootPassword}';"
service mysql restart
mysql --user="root" --password="{$rootPassword}" -e "CREATE USER '{$user}'@'\$IP_ADDRESS' IDENTIFIED BY '{$rootPassword}';"
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL ON *.* TO '{$user}'@'\$IP_ADDRESS' IDENTIFIED BY '{$rootPassword}' WITH GRANT OPTION;"
mysql --user="root" --password="{$rootPassword}" -e "GRANT ALL ON *.* TO '$user'@'%' IDENTIFIED BY '{$rootPassword}' WITH GRANT OPTION;"
mysql --user="root" --password="{$rootPassword}" -e "FLUSH PRIVILEGES;"
echo "" >> /etc/mysql/my.cnf
echo "[mysqld]" >> /etc/mysql/my.cnf
echo "character-set-server = utf8" >> /etc/mysql/my.cnf
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
        foreach ($this->server->databases as $databaseType):
            switch ($databaseType):
                case MYSQL_DB:
                    $script .= $this->installMysql();
                    break;
                case MONGO_DB:
                    $script .= $this->installMongodb();
                    break;
                case POSTGRES_DB:
                    $script .= $this->installPostgres();
                    break;
                case MYSQL8_DB:
                    $script .= $this->installMysql8();
                    break;
                case MARIA_DB:
                    $script .= $this->installMariadb();
                    break;
            endswitch;
        endforeach;

        return $script;
    }
}
