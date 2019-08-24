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

        $nesaboxIp = config('nesa.ip');

        $metricsPort = config('nesa.metrics_port');

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
mkdir -p /home/{$user}/.{$user}/ecosystems
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

# Make sure supervisor autostarts
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

# Install netdata
{$this->installNetData()}

# Install latest versions of node and pm2
{$this->installLatestNodeAndPm2()}

# Setup the server monitoring script
{$this->setupServerMonitoringScript()}

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

    public function installNetData()
    {
        return <<<EOD
curl -Ss 'https://raw.githubusercontent.com/netdata/netdata-demo-site/master/install-required-packages.sh' >/tmp/kickstart.sh && bash /tmp/kickstart.sh -i netdata-all --non-interactive

# clone the netdata repository and run netdata installer

git clone https://github.com/netdata/netdata.git netdata-temp-folder --depth=100

# Execute the netdata install script
cd netdata-temp-folder

./netdata-installer.sh --dont-wait

cd ~

# Download fresh netdata.conf file
curl -o /etc/netdata/netdata.conf http://localhost:19999/netdata.conf


# Enable python.d plugins
cat >> /etc/netdata/python.d.conf << EOF
# netdata python.d.plugin configuration
#
# This file is in YaML format.
# Generally the format is:
#
# name: value
#

# Enable / disable the whole python.d.plugin (all its modules)
enabled: yes

# ----------------------------------------------------------------------
# Enable / Disable python.d.plugin modules
default_run: yes
#
# If "default_run" = "yes" the default for all modules is enabled (yes).
# Setting any of these to "no" will disable it.
#
# If "default_run" = "no" the default for all modules is disabled (no).
# Setting any of these to "yes" will enable it.

# Enable / Disable explicit garbage collection (full collection run). Default is enabled.
gc_run: yes

# Garbage collection interval in seconds. Default is 300.
gc_interval: 300

# apache: yes

# apache_cache has been replaced by web_log
# adaptec_raid: yes
apache_cache: no
# beanstalk: yes
# bind_rndc: yes
# boinc: yes
# ceph: yes
chrony: no
# couchdb: yes
# dns_query_time: yes
# dnsdist: yes
# dockerd: yes
# dovecot: yes
# elasticsearch: yes
# energid: yes

# this is just an example
example: no

# exim: yes
# fail2ban: yes
# freeradius: yes
go_expvar: no

# gunicorn_log has been replaced by web_log
gunicorn_log: no
# haproxy: yes
# hddtemp: yes
# httpcheck: yes
# icecast: yes
# ipfs: yes
# isc_dhcpd: yes
# litespeed: yes
logind: no
# megacli: yes
# memcached: yes
mongodb: yes
# monit: yes
mysql: yes
nginx: yes
# nginx_plus: yes
# nvidia_smi: yes

# nginx_log has been replaced by web_log
nginx_log: no
# nsd: yes
# ntpd: yes
# openldap: yes
# oracledb: yes
# ovpn_status_log: yes
# phpfpm: yes
# portcheck: yes
# postfix: yes
# postgres: yes
# powerdns: yes
# proxysql: yes
# puppet: yes
# rabbitmq: yes
redis: yes
# rethinkdbs: yes
# retroshare: yes
# riakkv: yes
# samba: yes
# sensors: yes
# smartd_log: yes
# spigotmc: yes
# springboot: yes
# squid: yes
# traefik: yes
# tomcat: yes
# tor: yes
unbound: no
# uwsgi: yes
# varnish: yes
# w1sensor: yes
# web_log: yes
EOF

cat >> /etc/nginx/conf.d/stub-status.conf << EOF
# -----------------------------------------------------------------------------
# | Stub module provides access to basic status information. (Do not remove)  |
# -----------------------------------------------------------------------------

server {
     listen 80 default_server;
     listen [::]:80 default_server;
     location /stub_status {
        stub_status;
        allow 127.0.0.1;
        deny all;
    }
}
EOF

cat >> /etc/netdata/python.d/nginx.conf << EOF
localhost:
  name : 'local'
  url  : 'http://127.0.0.1/stub_status'
EOF

{$this->netDataConfigMysql()}

{$this->netDataConfigMongodb()}

# Add recommended configuration for netdata

cat >> /etc/sysctl.conf << EOF
vm.dirty_expire_centisecs=60000
vm.dirty_background_ratio=80
vm.dirty_ratio=90
EOF

# Increase max open files limit
mkdir -p /etc/systemd/system/netdata.service.d
cat >> /etc/systemd/system/netdata.service.d/limits.conf << EOF
[Service]
LimitNOFILE=30000
EOF

# Reload system daemon
systemctl daemon-reload

# Restart netdata
systemctl restart netdata

# Cleanup

cd ~
rm -r netdata-temp-folder
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
pm2 start index.js --name nesabox-logs-watcher --interpreter /usr/local/n/versions/node/12.8.0/bin/node
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
wget -qO - https://www.mongodb.org/static/pgp/server-4.2.asc | sudo apt-key add -
echo "deb [ arch=amd64 ] https://repo.mongodb.org/apt/ubuntu bionic/mongodb-org/4.2 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-4.2.list

sudo apt-get update
sudo apt-get install -y mongodb-org
sudo service mongod start

systemctl restart mongod
systemctl status mongod

cat > app.js << EOF
db.createUser ({
    user: "{$database->databaseUser->name}",
    pwd: "{$database->databaseUser->password}",
    roles: [{ role: 'root', db: 'admin' }, { role: "userAdminAnyDatabase", db: "admin" }, "readWriteAnyDatabase" ]}
)
printjson(db.getUsers())
EOF

mongo admin app.js

rm app.js

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
