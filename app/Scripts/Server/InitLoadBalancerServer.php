<?php

namespace App\Scripts\Server;

use App\Server;
use App\Scripts\Base as BaseScript;

class InitLoadBalancerServer extends BaseScript
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

        $callbackEndpoint =
            config('app.url') .
            route(
                'servers.initialization-callback',
                [
                    $this->server->id,
                    'api_token' => $this->server->user->api_token
                ],
                false
            );

        $nesaboxIp = config('nesa.ip');

        $metricsPort = config('nesa.metrics_port');

        $sudoPassword = $this->server->sudo_password;

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

# Add deployment user
useradd {$user}
mkdir -p /home/{$user}/.ssh
mkdir -p /home/{$user}/.{$user}
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
ssh-keygen -o -a 100 -t ed25519 -P '' -f /home/{$user}/.ssh/{$user} -C {$this->server->name}
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

        $userKeys = '';

        foreach ($this->server->user->sshkeys as $key) {
            $userKeys .= <<<EOD
\n
# {$key->name} key

{$key->key}
EOD;
        }

        return <<<EOD
cat >> /root/.ssh/authorized_keys << EOF
# Nesabox key

{$sshKey->key}

{$userKeys}
EOF
EOD;
    }
}
