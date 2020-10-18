#!/bin/bash
#
#
# RunCloud installer script for server Runcloud (134.122.18.233)
# Do not use in other server

OSNAME=`lsb_release -s -i`
OSVERSION=`lsb_release -s -r`
OSCODENAME=`lsb_release -s -c`
SUPPORTEDVERSION="16.04 18.04 20.04"
PHPCLIVERSION="php74rc"
INSTALLPACKAGE="nginx-rc apache2-rc runcloud-agent curl git wget mariadb-server expect nano openssl redis-server python-setuptools perl zip unzip net-tools bc fail2ban augeas-tools libaugeas0 augeas-lenses firewalld build-essential acl memcached beanstalkd passwd unattended-upgrades postfix nodejs make jq "

# Services detection
SERVICES=$(systemctl --type=service --state=active | grep -E '\.service' | cut -d ' ' -f1 | sed -r 's/.{8}$//' | tr '\n' ' ')
DETECTEDSERVICESCOUNT=0
DETECTEDSERVICESNAME=""

function ReplaceWholeLine {
    sed -i "s/$1.*/$2/" $3
}

function ReplaceTrueWholeLine {
    sed -i "s/.*$1.*/$2/" $3
}

function checkServiceInstalled {
    if rpm -qa | grep -q $1; then
        return 1
    else
        return 0
    fi
}

function RandomString {
    head /dev/urandom | tr -dc _A-Za-z0-9 | head -c55
}

function FixAutoUpdate() {
    AUTOUPDATEFILE50="/etc/apt/apt.conf.d/50unattended-upgrades"
    AUTOUPDATEFILE20="/etc/apt/apt.conf.d/20auto-upgrades"

    sed -i 's/Unattended-Upgrade::Allowed-Origins {/Unattended-Upgrade::Allowed-Origins {\n        "RunCloud:${distro_codename}";/g' $AUTOUPDATEFILE50
    ReplaceTrueWholeLine "\"\${distro_id}:\${distro_codename}-security\";" "        \"\${distro_id}:\${distro_codename}-security\";" $AUTOUPDATEFILE50
    ReplaceTrueWholeLine "\/\/Unattended-Upgrade::AutoFixInterruptedDpkg" "Unattended-Upgrade::AutoFixInterruptedDpkg \"true\";" $AUTOUPDATEFILE50
    ReplaceTrueWholeLine "\/\/Unattended-Upgrade::Remove-Unused-Dependencies" "Unattended-Upgrade::Remove-Unused-Dependencies \"true\";" $AUTOUPDATEFILE50

    echo -ne "\n\n
    Dpkg::Options {
       \"--force-confdef\";
       \"--force-confold\";
    }" >> $AUTOUPDATEFILE50

    echo "APT::Periodic::Update-Package-Lists \"1\";" > $AUTOUPDATEFILE20
    echo "APT::Periodic::Unattended-Upgrade \"1\";" >> $AUTOUPDATEFILE20
}

function BootstrapServer {
    apt-get -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" upgrade -y
}

function BootstrapInstaller {
    rm -f /etc/apt/apt.conf.d/50unattended-upgrades.ucf-dist

    apt-get install software-properties-common apt-transport-https -y

    # Install Key
    # RunCloud
    wget -qO - https://release.runcloud.io/runcloud.key | apt-key add -
    # MariaDB
    apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8

    # Install RunCloud Source List
    echo "deb [arch=amd64] https://release.runcloud.io/ $OSCODENAME main" > /etc/apt/sources.list.d/runcloud.list

    # LTS version nodejs
    curl -sL https://deb.nodesource.com/setup_12.x | sudo -E bash -

    if [[ "$OSCODENAME" == 'xenial' ]]; then
        add-apt-repository 'deb [arch=amd64] http://nyc2.mirrors.digitalocean.com/mariadb/repo/10.4/ubuntu xenial main'
        add-apt-repository 'deb [arch=amd64] http://sfo1.mirrors.digitalocean.com/mariadb/repo/10.4/ubuntu xenial main'

        PIPEXEC="pip"

        INSTALLPACKAGE+="python-pip php55rc php55rc-essentials php56rc php56rc-essentials php70rc php70rc-essentials php71rc php71rc-essentials php72rc php72rc-essentials php73rc php73rc-essentials php74rc php74rc-essentials"
    elif [[ "$OSCODENAME" == 'bionic' ]]; then
        add-apt-repository 'deb [arch=amd64] http://nyc2.mirrors.digitalocean.com/mariadb/repo/10.4/ubuntu bionic main'
        add-apt-repository 'deb [arch=amd64] http://sfo1.mirrors.digitalocean.com/mariadb/repo/10.4/ubuntu bionic main'

        PIPEXEC="pip"

        INSTALLPACKAGE+="python-pip php70rc php70rc-essentials php71rc php71rc-essentials php72rc php72rc-essentials php73rc php73rc-essentials php74rc php74rc-essentials"
    elif [[ "$OSCODENAME" == 'focal' ]]; then
        add-apt-repository 'deb [arch=amd64] http://nyc2.mirrors.digitalocean.com/mariadb/repo/10.4/ubuntu focal main'
        add-apt-repository 'deb [arch=amd64] http://sfo1.mirrors.digitalocean.com/mariadb/repo/10.4/ubuntu focal main'

        PIPEXEC="pip3"

        INSTALLPACKAGE+="python3-pip php72rc php72rc-essentials php73rc php73rc-essentials php74rc php74rc-essentials dirmngr gnupg libmagic-dev"
    fi

    # APT PINNING
    echo "Package: *
Pin: release o=MariaDB
Pin-Priority: 900" > /etc/apt/preferences

}

function EnableSwap {
    totalRAM=`grep MemTotal /proc/meminfo | awk '{print $2}'`
    if [[ $totalRAM -lt 4000000 ]]; then # kalau RAM less than 4GB, enable swap
        swapEnabled=`swapon --show | wc -l`
        if [[ $swapEnabled -eq 0 ]]; then # swap belum enable
            # create 2GB swap space
            fallocate -l 2G /swapfile
            chmod 600 /swapfile
            mkswap /swapfile
            swapon /swapfile

            # backup fstab
            cp /etc/fstab /etc/fstab.bak

            # register the swap to fstab
            echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
        fi
    fi
}

function InstallPackage {
    apt-get update
    apt-get remove mysql-common --purge -y

    apt-get install $INSTALLPACKAGE -y
}

function CheckingPortAccessible {
    echo -ne "\n\n\nChecking if port 34210 is accessible...\n"

    # send command to check wait 2 seconds inside jobs before trying
    curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/testport/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME 
    
    if [[ "$OSCODENAME" == 'xenial' ]]; then
        timeout 15 bash -c "echo -e 'HTTP/1.1 200 OK\r\n' | nc -l 34210"
    else
        timeout 15 bash -c "echo -e 'HTTP/1.1 200 OK\r\n' | nc -N -l 34210"
    fi
    ncstatus=$?
    if [[ $ncstatus -ne 0 ]]; then
        clear
echo -ne "\n
##################################################
# Unable to connect through port 34210 inside    #
# this server. Please disable firewall for this  #
# port and rerun the installation script again!  #
##################################################
\n\n\n
"
        exit 1
    fi
}

function BootstrapSupervisor {
    export LC_ALL=C
    $PIPEXEC install supervisor
    echo_supervisord_conf > /etc/supervisord.conf
    echo -ne "\n\n\n[include]\nfiles=/etc/supervisor.d/*.conf\n\n" >> /etc/supervisord.conf
    mkdir -p /etc/supervisor.d

    echo "[Unit]
Description=supervisord - Supervisor process control system for UNIX
Documentation=http://supervisord.org
After=network.target

[Service]
Type=forking
ExecStart=/usr/local/bin/supervisord -c /etc/supervisord.conf
ExecReload=/usr/local/bin/supervisorctl reload
ExecStop=/usr/local/bin/supervisorctl shutdown
User=root

[Install]
WantedBy=multi-user.target" > /etc/systemd/system/supervisord.service

    systemctl daemon-reload
}

function BootstrapFail2Ban {
    echo "# RunCloud Server API configuration file
#
# Author: Ahmad Fikrizaman
#

[Definition]
failregex = Authentication error from <HOST>" > /etc/fail2ban/filter.d/runcloud-agent.conf

    echo "[DEFAULT]
ignoreip = 127.0.0.1/8
bantime = 36000
findtime = 600
maxretry = 5


[sshd]
enabled = true
logpath = %(sshd_log)s
port = 22
banaction = iptables

[sshd-ddos]
enabled = true
logpath = %(sshd_log)s
banaction = iptables-multiport

[runcloud-agent]
enabled = true
logpath = /var/log/runcloud.log
port = 34210
banaction = iptables
maxretry = 2" > /etc/fail2ban/jail.local
}

function BootstrapMariaDB {
    mkdir -p /tmp/lens
    curl -4 $RUNCLOUDURL/files/lenses/augeas-mysql.aug --create-dirs -o /tmp/lens/mysql.aug 


    ROOTPASS=$(RandomString)

    # Start mariadb untuk initialize
    systemctl start mysql

    SECURE_MYSQL=$(expect -c "
set timeout 5
spawn mysql_secure_installation

expect \"Enter current password for root (enter for none):\"
send \"\r\"

expect \"Switch to unix_socket authentication\"
send \"y\r\"

expect \"Change the root password?\"
send \"y\r\"

expect \"New password:\"
send \"$ROOTPASS\r\"

expect \"Re-enter new password:\"
send \"$ROOTPASS\r\"

expect \"Remove anonymous users?\"
send \"y\r\"

expect \"Disallow root login remotely?\"
send \"y\r\"

expect \"Remove test database and access to it?\"
send \"y\r\"

expect \"Reload privilege tables now?\"
send \"y\r\"

expect eof
")
    echo "$SECURE_MYSQL"


#     /usr/bin/augtool -I /tmp/lens/ <<EOF
# set /files/etc/mysql/my.cnf/target[ . = "client" ]/user root
# set /files/etc/mysql/my.cnf/target[ . = "client" ]/password $ROOTPASS
# save
# EOF

/usr/bin/augtool -I /tmp/lens/ <<EOF
set /files/etc/mysql/my.cnf/target[ . = "client" ]/user root
set /files/etc/mysql/my.cnf/target[ . = "client" ]/password $ROOTPASS
set /files/etc/mysql/my.cnf/target[ . = "mysqld" ]/bind-address 0.0.0.0
set /files/etc/mysql/conf.d/mariadb.cnf/target[ . = "mysqld" ]/innodb_file_per_table 1
set /files/etc/mysql/conf.d/mariadb.cnf/target[ . = "mysqld" ]/max_connections 15554
set /files/etc/mysql/conf.d/mariadb.cnf/target[ . = "mysqld" ]/query_cache_size 80M
set /files/etc/mysql/conf.d/mariadb.cnf/target[ . = "mysqld" ]/query_cache_type 1
set /files/etc/mysql/conf.d/mariadb.cnf/target[ . = "mysqld" ]/query_cache_limit 2M
set /files/etc/mysql/conf.d/mariadb.cnf/target[ . = "mysqld" ]/query_cache_min_res_unit 2k
set /files/etc/mysql/conf.d/mariadb.cnf/target[ . = "mysqld" ]/thread_cache_size 60
save
EOF

echo "[client]
user=root
password=$ROOTPASS
" > /etc/mysql/conf.d/root.cnf

    chmod 600 /etc/mysql/conf.d/root.cnf
}

function BootstrapWebApplication {
    USER="runcloud"
    RUNCLOUDPASSWORD=$(RandomString)
    HOMEDIR="/home/$USER/"
    groupadd users-rc
    adduser --disabled-password --gecos "" $USER
    usermod -a -G users-rc $USER

    echo "$USER:$RUNCLOUDPASSWORD" | chpasswd
    chmod 755 /home
    mkdir -p $HOMEDIR/logs/{nginx,apache2,fpm}

    # FACL
    setfacl -m g:users-rc:x /home
    setfacl -Rm g:users-rc:- /home/$USER
    setfacl -Rm g:users-rc:- /etc/mysql
    setfacl -Rm g:users-rc:- /var/log
    setfacl -Rm g:$USER:rx /home/$USER/logs


    mkdir -p /opt/RunCloud/{.ssh,letsencrypt}


    echo "-----BEGIN DH PARAMETERS-----
MIICCAKCAgEAzZmGWVJjBWNtfh1Q4MrxFJ5uwTM6xkllSewPOdMq5BYmXOFAhYMr
vhbig5AJHDexbl/VFp64S6JaokQRbTtiibBfHV92LCK9hVRJ2eB7Wlg6t5+YYjKc
QiNxQ/uvSG3eqmAAr39V3oUWxeyCj/b1WdUVkDuKdJyHevDgfaoyFl7JHymxwvrn
HR9/x7lH5o2Uhl60uYaZxlhzbbrqMU/ygx9JCj6trL5C5pv9hpH+2uJdvkp/2NJj
BJCwiHmLMlfqXA3H8/T7L0vn/QLk1JUmqQeGdvZFqEmCe//LAT8llGofawtOUUwT
v65K1Ovagt7R9iu+nOFIh6XPsLVLemq2HFy+amk+Ti4UZ+EJxvO+s84LxSvAqjsk
clEE2v+AlIbe8Hjo6YzubXtqSrFLD049kxocPdQXqbDbvlI6br1UjYgWl08upKSZ
fIwCFFsqwE4y7zRg1VY7MKc0z6MCBU7om/gI4xlPSSBxAP1fN9hv6MbSV/LEvWxs
pFyShqTqefToDKiegPpqBs8LAsOtuH78eSm18SgKYpVPL1ph0VhhbphbsmKxmqaU
+EP6bSOc2tTwCMPWySQslHN4TdbsiQJE/gJuVeaCLM1+u4sd0rU9NQblThPuOILp
v03VfaTd1dUF1HmcqJSl/DYeeBVYjT8GtAKWI5JrvCKDIPvOB98xMysCAQI=
-----END DH PARAMETERS-----" > /etc/nginx-rc/dhparam.pem
}

function BootstrapAgent {
    AGENTLOCATION="/RunCloud/Packages/RunCloudAgent"
    cp $AGENTLOCATION/config.json.example $AGENTLOCATION/config.json
    sed -i "s/{SERVERID}/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/g" $AGENTLOCATION/config.json
    sed -i "s/{SERVERKEY}/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME/g" $AGENTLOCATION/config.json
    sed -i "s/{ENVIRONMENT}/production/g" $AGENTLOCATION/config.json

    chmod 600 $AGENTLOCATION/config.json

    mkdir -p $AGENTLOCATION/ssl/

    echo "-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEAz0khrVUr3EtGTgqlaHz1+iAAiMFly41USim/eFPLoBrKcx7r
1maOA1T3cBUFB5myYveBmKqFci/fwJmrRfgfehfZfLggylQc9bu6dohGwd4jPk9H
dvk5TEPMRA31X5fsuSu1Ss5/dzCV+WTpRzwQ5lbDP/6DBP039zLZe7uYTiQ8zr0j
un60TX5ew+3y0nYYBUpvejYgOWFNRxcRjR5NRMEGDSdEq0kVGW76LVqf8HngtdSJ
Qj0sBcfAXsmiOBDPiHRS0kzI40US37uv8/sl8EECuWEiALotxlZkWRahCH5Srm0S
k2Pa0sO0QbUhWr7mWdrbYBvsxD5Kzpde2qgUrwIDAQABAoIBAApe/1dbzpD8Fy1q
bPI4Zlo4Za5x9rTjglcflZvgnWyKSpzN7EDS6sIR6ztpYdIKh5laBtrrlTesW4aY
abBbhL+oF9Kcp89XvAczeFXb5aZnhd3lO48hUxOUxNCW3jMFN+vf2vc+M7Rz8t0C
hoVw5OuRZb1673U9ciJlIeqF7U3jfSw2NDSOVc+/m9sLoP4wD3P2mlGeV9ZhS+OG
HBb4FQeLPS9tr37Gx4UsILWVGLwmrTXjXob9BCmj4ws+GG4ubvaBpJUeHgzAiNHM
JuqUHGa5yK5PyykhaNSqImw87GpU2FDtK0J2JfuzeJsEkiV+9LL3s53+e5C1c6/z
ZskwiXECgYEA9C1mwiEQBILv0Wkj5IggSFktFIhnJ3ZVhgu25hwaa+C+rNmpjiJX
S/aa1Vm0SNc3zySniJM+aw4pZwwbZlMoH9DW62mlL7mj9KXfFLdOepz20SzuE3Df
JeKDb+cv5HnDSdYXzyDaajao15mnRz8sYWw02NxF9Sv1jlcIWuY3zocCgYEA2VJ0
aBkd7lIi18jpcCEv0WNYvxgrI80vW71x2CHCELtZ947slQF3lDTkFHRCneQi965U
ZZp/Z9XPK0ACp+zODWi22UHgRBLdWHE3qU+k3RIBtd/LPfU6NE61R8wgTZi0HRVt
D6LU+rtgZpKSBBsEkW0IGfXkXe7dgvli7MyYqpkCgYAcC0bdDv0O95bhn0i/C59h
j9Rsoknur1mdPqUwApRKRZLAuXMO3wTVtaoMzPO21fkT8mrHddnaY9L/gdV3yB84
oDy+5vBQ7epVw+M+Ntttm6+BUL67PuByMnTUGmJWxGFYjp1HbceIIWT89inPNgM7
4Tikm4/xW64lPfu4rO5F6wKBgCzHkLTieAhjZEqSW4d/TxsWHXNCx6DY5qlAn/Sp
ABPyhtc73APYCYvAWj7ctd1S5OGkSj+lVhtPMyD2T5XKdarNqN+bc1qTiIhwy8dX
YeErlK6lLnvVy+h7T1878GwMyxAzGgXex4PimDqKeQvfhVFx5flzcVrpnTsfqAKj
JdIBAoGAGSSt56aUyewiCgh9T3Eayf2+aRgZ3NDfj19K5PnFsxgWxTFQO4oq+Rpj
m7LibMHebDY6GXa7/ooJZSH83fLlO4dTVgkbwyRYUbKAolo24YAM0OnHxdGnk/NP
MINiHhCfieCR2TZjlLALrEdvQCyd1haD1FzeDb60KBbVU6jjTSw=
-----END RSA PRIVATE KEY-----" > $AGENTLOCATION/ssl/server.key
    echo "-----BEGIN CERTIFICATE-----
MIIEDjCCAvagAwIBAgIUe6uRdeagJyd5yqTZQMsH6bLbJ+4wDQYJKoZIhvcNAQEL
BQAwgaoxCzAJBgNVBAYTAk1ZMQ4wDAYDVQQIDAVKb2hvcjEPMA0GA1UEBwwGU2t1
ZGFpMRwwGgYDVQQKDBNDb29sIENvZGUgU2RuLiBCaGQuMREwDwYDVQQLDAhSdW5D
bG91ZDEnMCUGA1UEAwweUnVuQ2xvdWQgQ2VydGlmaWNhdGUgQXV0aG9yaXR5MSAw
HgYJKoZIhvcNAQkBFhFmaWtyaUBydW5jbG91ZC5pbzAgFw0yMDEwMTQwNTMwMjNa
GA85OTk5MTIzMTIzNTk1OVowfjELMAkGA1UEBgwCTVkxDjAMBgNVBAgMBUpvaG9y
MQ8wDQYDVQQHDAZTa3VkYWkxGzAZBgNVBAoMElJ1bkNsb3VkIFNkbi4gQmhkLjEY
MBYGA1UECwwPUnVuQ2xvdWQgU2VydmVyMRcwFQYDVQQDDA4xMzQuMTIyLjE4LjIz
MzCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAM9JIa1VK9xLRk4KpWh8
9fogAIjBZcuNVEopv3hTy6AaynMe69ZmjgNU93AVBQeZsmL3gZiqhXIv38CZq0X4
H3oX2Xy4IMpUHPW7unaIRsHeIz5PR3b5OUxDzEQN9V+X7LkrtUrOf3cwlflk6Uc8
EOZWwz/+gwT9N/cy2Xu7mE4kPM69I7p+tE1+XsPt8tJ2GAVKb3o2IDlhTUcXEY0e
TUTBBg0nRKtJFRlu+i1an/B54LXUiUI9LAXHwF7JojgQz4h0UtJMyONFEt+7r/P7
JfBBArlhIgC6LcZWZFkWoQh+Uq5tEpNj2tLDtEG1IVq+5lna22Ab7MQ+Ss6XXtqo
FK8CAwEAAaNVMFMwDgYDVR0PAQH/BAQDAgXgMA8GA1UdEwEB/wQFMAMCAQAwDwYD
VR0RBAgwBocEhnoS6TAfBgNVHSMEGDAWgBR6ulnu+tZAAHmXjMuTrnq0qjgwRTAN
BgkqhkiG9w0BAQsFAAOCAQEAAB5qf7DiRCPyh8zugh/DyQLjok9LXOOFBe8krLNy
4ertCu1BTcNJ7LhoW1jdelCYyztN6aBMq2SbRUVx0XF7ujGQ6GnTAnvA56uK4zpW
AAIROe5rBIDqClAYQr4JAOvSBPPDkpvjeAfUZTVyBM9htB1sh81hny7clY4J2y5J
JOGndt0L9r5f4jzomF8wTrGfnSwk6IGQm3jp0K5Gmzzhtml9KQaZUJd3mPIwq9il
IQ/j5JjqDdfz9zV6yj0Ks5ySNa6WxVz933P63eH4GG78SaWcXaO0XsafnDPynD47
zaiH8PhCaCp1i+9JI29aLYF61ihCEuqsQIY4b5Z0tWgSWQ==
-----END CERTIFICATE-----" > $AGENTLOCATION/ssl/server.crt
    echo "-----BEGIN CERTIFICATE-----
MIIEOzCCAyOgAwIBAgIJAKUwNSAp1Rc0MA0GCSqGSIb3DQEBCwUAMIGqMQswCQYD
VQQGEwJNWTEOMAwGA1UECAwFSm9ob3IxDzANBgNVBAcMBlNrdWRhaTEcMBoGA1UE
CgwTQ29vbCBDb2RlIFNkbi4gQmhkLjERMA8GA1UECwwIUnVuQ2xvdWQxJzAlBgNV
BAMMHlJ1bkNsb3VkIENlcnRpZmljYXRlIEF1dGhvcml0eTEgMB4GCSqGSIb3DQEJ
ARYRZmlrcmlAcnVuY2xvdWQuaW8wIBcNMTYwOTE2MTQyMTU3WhgPMjExNjA4MjMx
NDIxNTdaMIGqMQswCQYDVQQGEwJNWTEOMAwGA1UECAwFSm9ob3IxDzANBgNVBAcM
BlNrdWRhaTEcMBoGA1UECgwTQ29vbCBDb2RlIFNkbi4gQmhkLjERMA8GA1UECwwI
UnVuQ2xvdWQxJzAlBgNVBAMMHlJ1bkNsb3VkIENlcnRpZmljYXRlIEF1dGhvcml0
eTEgMB4GCSqGSIb3DQEJARYRZmlrcmlAcnVuY2xvdWQuaW8wggEiMA0GCSqGSIb3
DQEBAQUAA4IBDwAwggEKAoIBAQC5Dhcl1VuuJcERr/Pz2Y9TNwI92/HGhNeib9+U
+vgYccKrWlzS477JOnDbeWq6COS6oCNgVugJwHPgd5jBs8qbe4L9LcvdHvGiBQ/j
s+Gbq0x0/DIAqYVot5G9T2EW9Nao6YTbXaNs8fEWHaWiQsDK9jVYLaHmCFdVk13t
PkG/0i2qc52PO1911fQ+iXNpt3HiOThWpUawPIV/IpFXjWar7wsZhEp9S5VdbsQL
iyluEDSlElBBj8FylaACc45gYn1m/YClGQPNdaOXK/O1F8TvOjRqkkUKLy5en4D7
YImjnnYkYNqOw+IBbylUytq0XdbT9QvBUzT6xbNwUqB6adM9AgMBAAGjYDBeMB0G
A1UdDgQWBBR6ulnu+tZAAHmXjMuTrnq0qjgwRTAfBgNVHSMEGDAWgBR6ulnu+tZA
AHmXjMuTrnq0qjgwRTAMBgNVHRMEBTADAQH/MA4GA1UdDwEB/wQEAwIBhjANBgkq
hkiG9w0BAQsFAAOCAQEAQK1lDleSMV/VCWaMQXK+R7IqY3dl2yYX12Vd9iF+0/Be
TiLgROoHWA527lHVZzaDm73F3ciayS3cnl8+pER8l0QSjGB4a2SD/Wn8FJ1Tsl+j
S6M++lSjeP358nVXjGkDFCmhTjEO5CNgZkb7w6IbjDfh6FkFAoY5F2SASoZpqxLV
w6KrK6vqdTmd+yfwFDtcheyUJvPM3l6hHVzjDOvROT4DMvZ9EictQrDYugDlBwW+
DjdGBnzCDaozBMND0sS/1IDm9fM6jaABjC1mNw9cAV6yvVQn4Jn/scKt6McgpGew
xmR8AAA7gTrrNnEkeRR8JxLiRTipWjykUwFIkRkreg==
-----END CERTIFICATE-----
" > $AGENTLOCATION/ssl/ca.crt
    sleep 1
    cat $AGENTLOCATION/ssl/server.crt $AGENTLOCATION/ssl/ca.crt > $AGENTLOCATION/ssl/bundle.crt

    chmod 600 $AGENTLOCATION/ssl/server.key

    echo "#!/bin/sh
if [ \"\$PAM_TYPE\" != \"close_session\" ]; then
    url=\"https://manage.runcloud.io/webhooks/sshlogin\"
    curl -4 -X POST  \\
        -H \"X-Server-ID: wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj\" \\
        -H \"X-Server-Key: smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME\" \\
        -H \"Content-Type: application/json\" \\
        --data '{\"user\": \"'\$PAM_USER'\", \"ipAddress\": \"'\$PAM_RHOST'\"}' \"\$url\" &
fi
exit
" > /usr/sbin/notifysshlogin

    chmod 700 /usr/sbin/notifysshlogin

    echo "session optional pam_exec.so seteuid /usr/sbin/notifysshlogin" >> /etc/pam.d/sshd
}

function BootstrapFirewall {
    # Stop iptables
    systemctl stop iptables
    systemctl stop ip6tables
    systemctl mask iptables
    systemctl mask ip6tables


    # remove ufw
    apt-get remove ufw -y
    # Start firewalld
    systemctl enable firewalld
    systemctl start firewalld

    # Add runcloud service to firewalld
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<service>
  <short>RunCloud Agent (RCA)</short>
  <description>Allow your server and RunCloud service to communicate to each other.</description>
  <port protocol=\"tcp\" port=\"34210\"/>
</service>" > /etc/firewalld/services/rcsa.xml

    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<zone>
  <short>RunCloud</short>
  <description>Default zone to use with RunCloud Server</description>
  <service name=\"rcsa\"/>
  <service name=\"dhcpv6-client\"/>
  <port protocol=\"tcp\" port=\"22\"/>
  <port protocol=\"tcp\" port=\"80\"/>
  <port protocol=\"tcp\" port=\"443\"/>
</zone>" > /etc/firewalld/zones/runcloud.xml

    sleep 3

    firewall-cmd --reload # reload to get rcsa
    firewall-cmd --set-default-zone=runcloud
    firewall-cmd --reload # reload to enable new config
}

function InstallComposer {
    ln -s /RunCloud/Packages/$PHPCLIVERSION/bin/php /usr/bin/php

    source /etc/profile.d/runcloudpath.sh
    # php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    wget -4 https://getcomposer.org/installer -O composer-setup.php
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    mv composer.phar /usr/sbin/composer

}

function RegisterPathAndTweak {
    echo "#!/bin/sh
export PATH=/RunCloud/Packages/apache2-rc/bin:\$PATH" > /etc/profile.d/runcloudpath.sh

    echo fs.inotify.max_user_watches=524288 | tee -a /etc/sysctl.conf && sysctl -p
    echo net.core.somaxconn = 65536 | tee -a /etc/sysctl.conf && sysctl -p
    echo net.ipv4.tcp_max_tw_buckets = 1440000 | tee -a /etc/sysctl.conf && sysctl -p
    echo vm.swappiness=10 | tee -a /etc/sysctl.conf && sysctl -p
    echo vm.vfs_cache_pressure=50 | tee -a /etc/sysctl.conf && sysctl -p
    echo vm.overcommit_memory=1 | tee -a /etc/sysctl.conf && sysctl -p


    /usr/bin/augtool <<EOF
set /files/etc/ssh/sshd_config/UseDNS no
set /files/etc/ssh/sshd_config/PasswordAuthentication yes
set /files/etc/ssh/sshd_config/PermitRootLogin yes
save
EOF
    systemctl restart sshd

}

function BootstrapSystemdService {
    systemctl enable runcloud-agent
    systemctl start runcloud-agent

    systemctl disable supervisord
    systemctl stop supervisord

    systemctl disable redis-server
    systemctl stop redis-server

    systemctl disable memcached
    systemctl stop memcached

    systemctl disable beanstalkd
    systemctl stop beanstalkd



    # Fix fail2ban
    touch /var/log/runcloud.log

    systemctl enable fail2ban
    systemctl start fail2ban
    systemctl restart fail2ban

    systemctl enable mysql
    systemctl restart mysql

}

RUNCLOUDURL="https://manage.runcloud.io"

locale-gen en_US en_US.UTF-8

export LANGUAGE=en_US.utf8
export LC_ALL=en_US.utf8
export DEBIAN_FRONTEND=noninteractive

# Checker
if [[ $EUID -ne 0 ]]; then
    message="RunCloud installer must be run as root!"
    echo $message 1>&2
    curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "err", "message": "'"$message"'"}' 
    exit 1
fi

if [[ "$OSNAME" != "Ubuntu" ]]; then
    message="This installer only support $OSNAME"
    echo $message
    curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "err", "message": "'"$message"'"}' 
    exit 1
fi

if [[ $(uname -m) != "x86_64" ]]; then
    message="This installer only support x86_64 architecture"
    echo $message
    curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "err", "message": "'"$message"'"}' 
    exit 1
fi

grep -q $OSVERSION <<< $SUPPORTEDVERSION
if [[ $? -ne 0 ]]; then
    message="This installer does not support $OSNAME $OSVERSION"
    echo $message
    curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "err", "message": "'"$message"'"}' 
    exit 1
fi

# existing services checker

if [[ $SERVICES == *"nginx"* ]]; then
  let "DETECTEDSERVICESCOUNT+=1"
  DETECTEDSERVICESNAME+=" Nginx"
fi

if [[ $SERVICES == *"apache2"* ]]; then
  let "DETECTEDSERVICESCOUNT+=1"
  DETECTEDSERVICESNAME+=" Apache"
fi

if [[ $SERVICES == *"lshttpd"* ]]; then
  let "DETECTEDSERVICESCOUNT+=1"
  DETECTEDSERVICESNAME+=" LiteSpeed"
fi

if [[ $SERVICES == *"mysql"* ]]; then
  let "DETECTEDSERVICESCOUNT+=1"
  DETECTEDSERVICESNAME+=" MySQL"
fi

if [[ $SERVICES == *"mariadb"* ]]; then
  let "DETECTEDSERVICESCOUNT+=1"
  DETECTEDSERVICESNAME+=" MariaDB"
fi

if [[ $SERVICES == *"php"* ]]; then
  let "DETECTEDSERVICESCOUNT+=1"
  DETECTEDSERVICESNAME+=" PHP"
fi

if [[ $SERVICES == *"webmin"* ]]; then
  let "DETECTEDSERVICESCOUNT+=1"
  DETECTEDSERVICESNAME+=" Webmin"
fi

if [[ $SERVICES == *"lscpd"* ]]; then
  let "DETECTEDSERVICESCOUNT+=1"
  DETECTEDSERVICESNAME+=" CyberPanel"
fi

if [[ $SERVICES == *"psa"* ]]; then
  let "DETECTEDSERVICESCOUNT+=1"
  DETECTEDSERVICESNAME+=" Plesk Panel"
fi

if [[ $DETECTEDSERVICESCOUNT -ne 0 ]]; then
    message="Installer detected $DETECTEDSERVICESCOUNT existing services;$DETECTEDSERVICESNAME. Installation will not proceed."
    echo $message
    curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "err", "message": "'"$message"'"}'
    exit 1
fi

# end services checker

# Checking open port
CheckingPortAccessible


# Bootstrap the server
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "sti"}' 
BootstrapServer

# Bootstrap the installer
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "upg"}' 
BootstrapInstaller

# Enabling Swap if Not Enabled
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "sw"}' 
sleep 2
EnableSwap

# Install The Packages
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "si"}' 
sleep 2
InstallPackage

# Supervisor
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "sv"}' 
sleep 2
BootstrapSupervisor

# Fail2Ban
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "cf"}' 
sleep 2
BootstrapFail2Ban

# MariaDB
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "cm"}' 
sleep 2
BootstrapMariaDB

# Web Application
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "cu"}' 
sleep 2
BootstrapWebApplication

# Auto Update
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "au"}' 
sleep 2
FixAutoUpdate

# Agent
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "cra"}' 
sleep 2
BootstrapAgent

# Firewall
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "cfd"}' 
sleep 2
BootstrapFirewall

# Composer
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "ic"}' 
sleep 2
InstallComposer

# Tweak
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "tw"}' 
sleep 2
RegisterPathAndTweak

# Systemd Service
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "sta"}' 
sleep 2
BootstrapSystemdService

curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME -d '{"status": "fin"}' 
sleep 2

## CLEANUP
# This will only run coming from direct installation
if [ -f /tmp/installer.sh ]; then
    rm /tmp/installer.sh
fi
if [ -f /tmp/installation.log ]; then
    rm /tmp/installation.log
fi

############################# MOTD ##################################

echo "

8888888b.                     .d8888b.  888                        888 
888   Y88b                   d88P  Y88b 888                        888 
888    888                   888    888 888                        888 
888   d88P 888  888 88888b.  888        888  .d88b.  888  888  .d88888 
8888888P\"  888  888 888 \"88b 888        888 d88\"\"88b 888  888 d88\" 888 
888 T88b   888  888 888  888 888    888 888 888  888 888  888 888  888 
888  T88b  Y88b 888 888  888 Y88b  d88P 888 Y88..88P Y88b 888 Y88b 888 
888   T88b  \"Y88888 888  888  \"Y8888P\"  888  \"Y88P\"   \"Y88888  \"Y88888 


- Do not use \"root\" user to create/modify any web app files
- Do not edit any config commented with \"Do not edit\"

Made with ♥ by RunCloud Team

" > /etc/motd


############################# Register ##################################
# Try register as installed
# Don't attempt to try spam this link. Rate limit in action. 1 query per minute and will be block for a minute
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/firstregistration/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME 
systemctl restart runcloud-agent

############################# FIX HOSTNAME ##################################

fixHostName=`hostname`
echo 127.0.0.1 $fixHostName | tee -a /etc/hosts

###################################### INSTALL SUMMARY #####################################
clear
echo -ne "\n
#################################################
# Finished installation. Do not lose any of the
# data below.
##################################################
\n
\n
\nMySQL ROOT PASSWORD: $ROOTPASS
User: $USER
Password: $RUNCLOUDPASSWORD
\n
\n
You can now manage your server using $RUNCLOUDURL
"
# root@runcloud:~# curl -4 --silent --location https://manage.runcloud.io/scripts/installer/wMGHPf2JmseIBb8qT4iLpbVeKo1602826223XesBROHlpBFM3wZe8k0rXC0k1ILmjo1BAN4qrebtMsAKoFDoKNHH5IZTsSNADmXj/smCv2EtBuWZOjpsQdzAgGDGx5v84ZNXe63aRLEPHp8aPcwQdw0bMNIQ5R4NuPGaXdnX55A9eFrcALTzTRWVm4HG9JhZwDH2EwPeFPPwG8lBb73mlW79PLur7YkN5bHME > run-cloud-script-1.sh
