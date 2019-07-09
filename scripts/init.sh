# This script is the init script to be run on all new servers

USER="espectra"
SUDO_PASSWORD=$1

# if vps does not contains swap file - create it
SWAP_SIZE="1G"

# Upgrade The Base Packages

apt-get update # TODO: bypass the open-ssh prompt rubbish
apt-get upgrade -y

apt-get install -y --force-yes software-properties-common

apt-add-repository ppa:nginx/development -y
apt-add-repository ppa:chris-lea/redis-server -y

# Install some base packages
apt-get install -y --force-yes build-essential curl fail2ban gcc git libmcrypt4 libpcre3-dev \
make python2.7 python-pip supervisor ufw unattended-upgrades unzip whois zsh mc p7zip-full htop

# Disable Password Authentication Over SSH

sed -i "/PasswordAuthentication yes/d" /etc/ssh/sshd_config
echo "" | sudo tee -a /etc/ssh/sshd_config
echo "" | sudo tee -a /etc/ssh/sshd_config
echo "PasswordAuthentication no" | sudo tee -a /etc/ssh/sshd_config

# Restart SSH

ssh-keygen -A
service ssh restart

# Create The Root SSH Directory If Necessary

if [ ! -d /root/.ssh ]
then
    mkdir -p /root/.ssh
    touch /root/.ssh/authorized_keys
fi

# Setup espectra User

useradd $USER
mkdir -p /home/$USER/.ssh
adduser $USER sudo

# Setup Bash For User

chsh -s /bin/bash $USER
cp /root/.profile /home/$USER/.profile
cp /root/.bashrc /home/$USER/.bashrc

# Set The Sudo Password For User

PASSWORD=$(mkpasswd $SUDO_PASSWORD)
usermod --password $PASSWORD $USER

# Add User To www-data Group

usermod -a -G www-data $USER
id $USER
groups $USER

cp /root/.ssh/authorized_keys /home/$USER/.ssh/authorized_keys

# Setup ssh folder permissions for espectra user
chown -R espectra:espectra /home/$USER
chmod 0700 /home/$USER/.ssh
chmod 0600 /home/$USER/.ssh/authorized_keys

# Copy authorized keys from root to espectra folder.
# In this case, it copies only the espectra root authorized key since it's the only existent key.

# TODO: even though we have a key specifically for this server, we will generate a general key that espectra can use to access all it's servers in case of emergency.

# Restart SSH

ssh-keygen -A
service ssh restart

# Setup UFW Firewall

ufw allow 22
ufw allow 80
ufw allow 443
ufw --force enable

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

# Install nginx

apt-get install -y nginx

# Disable The Default Nginx Site

rm /etc/nginx/sites-enabled/default
rm /etc/nginx/sites-available/default

# Enable ufw for nginx 
sudo ufw allow 'Nginx HTTP'

# Restart nginx
service nginx restart

# Install redis-server
apt-get install -y redis-server

# Install node version manager

curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.34.0/install.sh | bash

# Start using nvm immediately.

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion

# # Install node version manager for espectra user
# su $USER

# # create nvm folder
# mkdir /home/$USER/.nvm

# # Install nvm
# curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.34.0/install.sh | bash

# # Start using nvm immediately.

# export NVM_DIR="/home/$USER/.nvm"
# [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
# [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion

# Install the latest version of Node/NPM
# nvm install node
