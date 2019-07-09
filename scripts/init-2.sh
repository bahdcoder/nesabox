
# =================== YOUR DATA ========================

SERVER_NAME=$1
SERVER_IP=$2

USER="espectra"
SUDO_PASSWORD=$3

# if vps not contains swap file - create it
SWAP_SIZE="1G"

TIMEZONE="Etc/GMT+0" # lits of avaiable timezones: ls -R --group-directories-first /usr/share/zoneinfo

# =================== LETS MAGIC BEGINS ================

# Prefer IPv4 over IPv6 - make apt-get faster

# sudo sed -i "s/#precedence ::ffff:0:0\/96  100/precedence ::ffff:0:0\/96  100/" /etc/gai.conf

# Upgrade The Base Packages

apt-get update
# apt-get upgrade -y

# Update Package Lists

apt-get update

# Base Packages

apt-get install -y --force-yes build-essential curl fail2ban gcc git libmcrypt4 libpcre3-dev


# make python2.7 python-pip supervisor ufw unattended-upgrades unzip whois zsh mc p7zip-full htop

# # Install Python Httpie

# pip install httpie

# Disable Password Authentication Over SSH

sed -i "/PasswordAuthentication yes/d" /etc/ssh/sshd_config
echo "" | sudo tee -a /etc/ssh/sshd_config
echo "" | sudo tee -a /etc/ssh/sshd_config
echo "PasswordAuthentication no" | sudo tee -a /etc/ssh/sshd_config

# Restart SSH

ssh-keygen -A
service ssh restart

# Set The Hostname If Necessary

# echo "$SERVER_NAME" > /etc/hostname
# sed -i "s/127\.0\.0\.1.*localhost/127.0.0.1	$SERVER_NAME localhost/" /etc/hosts
# hostname $SERVER_NAME

# Set The Timezone

# ln -sf /usr/share/zoneinfo/$TIMEZONE /etc/localtime

# Create The Root SSH Directory If Necessary

if [ ! -d /root/.ssh ]
then
    mkdir -p /root/.ssh
    touch /root/.ssh/authorized_keys
fi

# Setup User

useradd $USER
mkdir -p /home/$USER/.ssh
adduser $USER sudo

# Setup ssh folder permissions for espectra user
chown -R espectra:espectra /home/{$USER}/.ssh
chmod 0700 /home/${USER}/.ssh
chmod 0600 /home/${USER}/.ssh/authorized_keys

# Restart SSH

ssh-keygen -A
service ssh restart


# Setup Bash For User

chsh -s /bin/bash $USER
cp /root/.profile /home/$USER/.profile
cp /root/.bashrc /home/$USER/.bashrc

# Set The Sudo Password For User

PASSWORD=$(mkpasswd $SUDO_PASSWORD)
usermod --password $PASSWORD $USER

# Create The Server SSH Key

ssh-keygen -f /home/$USER/.ssh/id_rsa -t rsa -N ''

# Copy Github And Bitbucket Public Keys Into Known Hosts File

# ssh-keyscan -H github.com >> /home/$USER/.ssh/known_hosts
# ssh-keyscan -H bitbucket.org >> /home/$USER/.ssh/known_hosts

# Setup Site Directory Permissions

chown -R $USER:$USER /home/$USER
chmod -R 755 /home/$USER
chmod 700 /home/$USER/.ssh/id_rsa

# Setup Unattended Security Upgrades

cat > /etc/apt/apt.conf.d/50unattended-upgrades << EOF
Unattended-Upgrade::Allowed-Origins {
    "Ubuntu xenial-security";
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

# Setup UFW Firewall

ufw allow 22
ufw allow 80
ufw allow 443
ufw --force enable

# Configure Supervisor Autostart

systemctl enable supervisor.service
service supervisor start

# Configure Swap Disk

if [ -f /swapfile ]; then
    echo "Swap exists."
else
    fallocate -l $SWAP_SIZE /swapfile
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    echo "/swapfile none swap sw 0 0" >> /etc/fstab
    echo "vm.swappiness=30" >> /etc/sysctl.conf
    echo "vm.vfs_cache_pressure=50" >> /etc/sysctl.conf
fi

# Disable The Default Nginx Site

rm /etc/nginx/sites-enabled/default
rm /etc/nginx/sites-available/default
service nginx restart

# Configure A Few More Server Things

sed -i "s/worker_processes.*/worker_processes auto;/" /etc/nginx/nginx.conf
sed -i "s/# multi_accept.*/multi_accept on;/" /etc/nginx/nginx.conf

# # Install A Catch All Server

# cat > /etc/nginx/sites-available/catch-all << EOF
# server {
#     return 404;
# }
# EOF

# ln -s /etc/nginx/sites-available/catch-all /etc/nginx/sites-enabled/catch-all

# Restart Nginx Service

service nginx restart
service nginx reload

# Add User To www-data Group

usermod -a -G www-data $USER
id $USER
groups $USER

# Install & Configure Redis Server

apt-get install -y redis-server
sed -i 's/bind 127.0.0.1/bind 0.0.0.0/' /etc/redis/redis.conf
service redis-server restart

# Install & Configure Memcached

apt-get install -y memcached
sed -i 's/-l 127.0.0.1/-l 0.0.0.0/' /etc/memcached.conf
service memcached restart

# Install & Configure Beanstalk

apt-get install -y --force-yes beanstalkd
sed -i "s/BEANSTALKD_LISTEN_ADDR.*/BEANSTALKD_LISTEN_ADDR=0.0.0.0/" /etc/default/beanstalkd
sed -i "s/#START=yes/START=yes/" /etc/default/beanstalkd
/etc/init.d/beanstalkd start

