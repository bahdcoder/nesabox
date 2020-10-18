#!/bin/sh

# Define script variables
USER="nesa"
IP_ADDRESS=$(curl ifconfig.co)
SUDO_PASSWORD="c9hiE8B8rqRl"
SWAP_SIZE="1G"
export DEBIAN_FRONTEND=noninteractive

sudo sed -i "s/#precedence ::ffff:0:0\/96  100/precedence ::ffff:0:0\/96  100/" /etc/gai.conf
# Set The Hostname If Necessary (on linode servers for example)

echo "fiscal-nomenclature" > /etc/hostname
sed -i 's/127\.0\.0\.1.*localhost/127.0.0.1     fiscal-nomenclature.localdomain fiscal-nomenclature localhost/' /etc/hosts
hostname fiscal-nomenclature

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
    IdentityFile ~/.ssh/nesa

Host gitlab.com
    StrictHostKeyChecking no
    IdentityFile ~/.ssh/nesa

Host bitbucket.org
    StrictHostKeyChecking no
    IdentityFile ~/.ssh/nesa
EOF

# Add deployment user
useradd nesa
mkdir -p /home/nesa/.ssh
mkdir -p /home/nesa/.nesa
mkdir -p /home/nesa/.nesa/ecosystems
mkdir -p /home/nesa/.nesa/cron-job-logs
adduser nesa sudo

chsh -s /bin/bash nesa
cp /root/.profile /home/nesa/.profile
cp /root/.bashrc /home/nesa/.bashrc

PASSWORD=$(mkpasswd c9hiE8B8rqRl)
usermod --password $PASSWORD nesa

usermod -a -G www-data nesa
id nesa
groups nesa

# Install node, npm and n

curl --silent --location https://deb.nodesource.com/setup_12.x | bash -

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
chown -R nesa /usr/local
chmod -R 755 /usr/local

cat >> /root/.ssh/authorized_keys << EOF
# Nesabox key

ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAILl62jhdMYv+bj9OlRzsgx7BSIWhrcMGrnwy5VJw61Dc Nesabox



# fiscal-nomenclature-dgxi2qph key

ssh-ed25519 AAAAC3NzaC1lZDI1NTE5AAAAILl62jhdMYv+bj9OlRzsgx7BSIWhrcMGrnwy5VJw61Dc Nesabox

# MAcbook key

ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDk6VHttwDI8F/bjFzFNX9OVkO45DPcTFtA16slXPLnMSFNBmcWQPiFAfk6G/57JlBbdFZstp+KoRmh7KXJGaPcbjInEnPVcm3cHrsXpHPfyTnvIJkN9eqaLlny19xPGQfrLOBO92n4m+/ZLrmhD0d0a1ohyxJBnfxIg5qs9MtAaX+79rAiAT1D050E89S7truZhVH0ur8Aclo2TdEBf3aHlo9gKgYqNf9F+BQV7jCZTobUNWzbjiP7gYkz7KkH3L8jATqc5OVmUGO/iUotaDFGYXU0qlrGOGi2PwIb5sgu9DNdF2ZWZZuATjZKym7+ngMk5J2XdgErb/tvEqodrNTP bahdcoder@Frantzs-MacBook-Pro.local
EOF

# Setup ssh keys for deployment user
cp /root/.ssh/config /home/nesa/.ssh/config
cp /root/.ssh/authorized_keys /home/nesa/.ssh/authorized_keys
chown -R nesa:nesa /home/nesa
chown nesa -R /home/nesa
chmod -R 755 /home/nesa
chmod 700 /home/nesa/.ssh
chmod 600 /home/nesa/.ssh/authorized_keys
ssh-keygen -A
service ssh restart

# Geenerate ssh key
ssh-keygen -o -a 100 -t ed25519 -P '' -f /home/nesa/.ssh/nesa -C fiscal-nomenclature
chown nesa -R /home/nesa/.ssh

# Enable default ufw ports
ufw allow 22
ufw allow 80
ufw allow 443

ufw --force enable

# Create swap file if it does not exist 
if [ -f /swapfile ]; then
    echo "A swap was already created by some providers (Linode)."
else
    fallocate -l $SWAP_SIZE /swapfile
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
bash ./acme.sh --install
cd ~
rm -rf acme.sh
apt-get install socat -y
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


wget -qO - https://www.mongodb.org/static/pgp/server-4.2.asc | sudo apt-key add -
echo "deb [ arch=amd64 ] https://repo.mongodb.org/apt/ubuntu bionic/mongodb-org/4.2 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-4.2.list

sudo apt-get update
sudo apt-get install -y mongodb-org=4.2.0 mongodb-org-server=4.2.0 mongodb-org-shell=4.2.0 mongodb-org-mongos=4.2.0 mongodb-org-tools=4.2.0
sudo service mongod start

sleep 3
# Create mongo admin user
mongo admin --eval 'db.createUser ({user: "admin",pwd: "lJJLYCAUTmeNyYericVQoq6pRCmoV8tU",roles: [{ role: "userAdminAnyDatabase", db: "admin" }, { role: "root", db: "admin" }, "readWriteAnyDatabase" ]})'

replace-in-file '#security:' '' /etc/mongod.conf
replace-in-file '127.0.0.1' '0.0.0.0' /etc/mongod.conf
cat >> /etc/mongod.conf << EOF
security:
    authorization: "enabled"
EOF

systemctl restart mongod

sleep 3

# Create the nesa user we'll use for database / user creation
mongo admin --eval 'db.createUser({ user: "nesa", pwd: "lJJLYCAUTmeNyYericVQoq6pRCmoV8tU", roles: [{ role: "userAdminAnyDatabase", db: "admin" }, "readWriteAnyDatabase"] })' -u admin -p lJJLYCAUTmeNyYericVQoq6pRCmoV8tU --authenticationDatabase admin

wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt/ `lsb_release -cs`-pgdg main" >> /etc/apt/sources.list.d/pgdg.list'
sudo apt-get update
sudo apt-get install -y --force-yes postgresql postgresql-contrib

# Postgres configs
sudo sed -i "s/localtime/UTC/" /etc/postgresql/11/main/postgresql.conf
sed -i "s/#listen_addresses = 'localhost'/listen_addresses = '*'/g" /etc/postgresql/11/main/postgresql.conf
echo "host    all             all             0.0.0.0/0               md5" | tee -a /etc/postgresql/11/main/pg_hba.conf
sudo -u postgres psql -c "CREATE ROLE nesa LOGIN PASSWORD 'Ap4EluEz1CTeHTRxghHTqoAHvcv0J5ld' SUPERUSER INHERIT CREATEDB CREATEROLE NOREPLICATION;"
service postgresql restart

export DEBIAN_FRONTEND=noninteractive
debconf-set-selections <<< "mysql-community-server mysql-community-server/data-dir select ''"
debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9"
debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9"
apt-get install -y mysql-server
echo "default_password_lifetime = 0" >> /etc/mysql/mysql.conf.d/mysqld.cnf
sed -i '/^bind-address/s/bind-address.*=.*/bind-address = */' /etc/mysql/mysql.conf.d/mysqld.cnf
mysql --user="root" --password="zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9" -e "GRANT ALL ON *.* TO root@'$IP_ADDRESS' IDENTIFIED BY 'zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9';"
mysql --user="root" --password="zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9" -e "GRANT ALL ON *.* TO root@'%' IDENTIFIED BY 'zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9';"
service mysql restart
mysql --user="root" --password="zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9" -e "CREATE USER 'nesa'@'$IP_ADDRESS' IDENTIFIED BY 'zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9';"
mysql --user="root" --password="zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9" -e "GRANT ALL ON *.* TO 'nesa'@'$IP_ADDRESS' IDENTIFIED BY 'zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9' WITH GRANT OPTION;"
mysql --user="root" --password="zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9" -e "GRANT ALL ON *.* TO 'nesa'@'%' IDENTIFIED BY 'zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9' WITH GRANT OPTION;"
mysql --user="root" --password="zmX6AgFsPTaAEwVkAk9yzHVff5Te61n9" -e "FLUSH PRIVILEGES;"

service mysql restart

# Install netdata

# Install latest versions of node and pm2
su nesa <<EOF
cd ~
n 10.15.0
npm i -g pm2
npm i -g yarn
EOF

# Configure git with user details.
git config --global user.email "bahdcoder@gmail.com"
git config --global user.name "Kati Frantz"

# Setup the server monitoring script

generate_post_data()
{
    cat <<EOF
{
    "ssh_key": "$(cat /home/nesa/.ssh/nesa.pub)"
}
EOF
}

# Call API to mark this server as completely initialized.
curl -i \
-H "Accept: application/json" \
-H "Content-Type:application/json" \
-X POST --data "$(generate_post_data)" "https://01bc897c7302.ngrok.io/servers/4e8713b0-b9af-4b81-92bf-b0752aee4baa/initialization-callback?api_token=L1Gor73X59S7HtsBmnC0ej3MdwRtfHfddcHbhZFL"
