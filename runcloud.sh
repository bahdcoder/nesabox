#!/bin/bash
#
#
# RunCloud installer script for server Runcloud (208.68.38.80)
# Do not use in other server

OSNAME=`lsb_release -s -i`
OSVERSION=`lsb_release -s -r`
OSCODENAME=`lsb_release -s -c`
SUPPORTEDVERSION="16.04 18.04"
PHPCLIVERSION="php74rc"
INSTALLPACKAGE="nginx-rc apache2-rc runcloud-agent curl git wget mariadb-server expect nano openssl redis-server python-setuptools python-pip perl zip unzip net-tools bc fail2ban augeas-tools libaugeas0 augeas-lenses firewalld build-essential acl memcached beanstalkd passwd unattended-upgrades postfix nodejs make "

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
    curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -

    if [[ "$OSCODENAME" == 'xenial' ]]; then
        add-apt-repository 'deb [arch=amd64] http://nyc2.mirrors.digitalocean.com/mariadb/repo/10.2/ubuntu xenial main'
        add-apt-repository 'deb [arch=amd64] http://sfo1.mirrors.digitalocean.com/mariadb/repo/10.2/ubuntu xenial main'

        INSTALLPACKAGE+="php55rc php55rc-essentials php56rc php56rc-essentials php70rc php70rc-essentials php71rc php71rc-essentials php72rc php72rc-essentials php73rc php73rc-essentials php74rc php74rc-essentials"
    elif [[ "$OSCODENAME" == 'bionic' ]]; then
        add-apt-repository 'deb [arch=amd64] http://nyc2.mirrors.digitalocean.com/mariadb/repo/10.2/ubuntu bionic main'
        add-apt-repository 'deb [arch=amd64] http://sfo1.mirrors.digitalocean.com/mariadb/repo/10.2/ubuntu bionic main'

        NSTALLPACKAGE+="php70rc php70rc-essentials php71rc php71rc-essentials php72rc php72rc-essentials php73rc php73rc-essentials php74rc php74rc-essentials"
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
    curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/testport/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli
    
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
    pip install supervisor
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
    wget $RUNCLOUDURL/files/lenses/augeas-mysql.aug -O /tmp/lens/mysql.aug


    ROOTPASS=$(RandomString)

    # Start mariadb untuk initialize
    systemctl start mysql

    SECURE_MYSQL=$(expect -c "
set timeout 5
spawn mysql_secure_installation

expect \"Enter current password for root (enter for none):\"
send \"\r\"

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
    sed -i "s/{SERVERID}/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/g" $AGENTLOCATION/config.json
    sed -i "s/{SERVERKEY}/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli/g" $AGENTLOCATION/config.json
    sed -i "s/{ENVIRONMENT}/production/g" $AGENTLOCATION/config.json

    chmod 600 $AGENTLOCATION/config.json

    mkdir -p $AGENTLOCATION/ssl/

    echo "-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEArLdaK0cH8xNp7abtPAHq/+XLcnzU1kRcJS064f7Xsv7aX+ZP
tGvD8RaTwZehzKKNkbnqMvqWeVG4KpYbiNphqrFi/wv2Xa5XTp4Q99KuIzp92V37
4j4nVOxWb3spBnf+T91ocJiwzb1q5vJbQWKuXu1b5MC0lvmrztPrvMK15TH+bLhd
oVOhE9dPa29T8f+wuOcGxqNIP2LtQxV+FSkDvUBYapvCreqokw8E0jTLirLvfNTm
I8mnbNEW0R1juc+woaedvJQEiAk8i+nchNCujgF+V6JVZxVQSIZtq9lw+yAUEUwl
AJtDOAaGUnlITlA/6Y3u7kvpIuDCIR1nEFHVmwIDAQABAoIBAH+zcW/eLPqEuCWG
7pEXufFKrooP0i46jQbP0mGWU1V9KWkOmJAFvfJbhxP0USVEavf8RjU7IlbBHTIf
mmwtS1kxnsiEx7usU9FE/ba+hxJ1BOfanYpB+kLIG4CBx2uI/Pd/9OCLhO4ZxxjK
yL4ngFWfmfogoWWfZHCgMAR40pSKk0wEkT01Z9eCQS/Ma2WMS4nvRk+la4bz1BVJ
2zYTqZE/MT27H/agccQDgKeqt4F8NGTzx/4MHgE79CRdlfzQ4ltfS1TQdO7FBHEu
BDUhTucQS5hzirHjLOgsw8o/aO2K21jsD9U+0dlolcvYe2Yg8WGWdGgerZfy3jze
lbWdCQECgYEA2Aw4O1MmbM9ecjpTiQccogAWvY4jTMvZLkMF2DZhOWoENYJf2sd2
h/0s+kheYDnnwH7VAQQHdlQT0y7D6FpZir799kfx6Til+w0Zp3EfoVBwvH+kr+eE
sSTLnx1hNmOKwK4cvPSBo5iag3jDB+4URg/5LPMyb+fIpYVA7J2ildECgYEAzKfN
c5JmUelhMR6b7Hc1eAOGBlzpq6yD0ByZ2jHhMY2Oik3Q/LpIza4StLky8SWp48vf
SE+J6lvYlNLnLz2c86bkHRmdyVHe/cD8z5ToxzSGY2CN1qqPUVqfkTFXtc+mL1C3
6zs7nAdscB/sZak+2kO1sWDQv1BPEOccKIQVU6sCgYEAzgNQ95kBcg6HdAsULv0e
G4hUJurL1GYpsYhtoU+xhAsI+nFYsAk1iNUtt3ecrstop1EKEg7igr4HrX/RhdXp
vnkDOH5AOnEvLObACL/BNpS6+7QHuUjeagC5Pf/I/BENdVBRzQimOfbBmA5mg0BO
CsUeiPNarATsepFHaJmkOJECgYBlb6p6ZQ7H/LfKo9Q+0Rs0X/K3BqhfCcPPi82K
A30NlY8X59f029Qn9eaZos2oDrX1DWCfM6U0/s8LfLlPhedcMNURw1ecqDKxbU0h
CjqSOet+KCZFRwaAGT3Ltjw2BjTlcMmoExTJkjhiHsCJ5ugbQMI2p7jvijuWMiaX
wlfbvwKBgQCd52SqyImSeq8kYyvJ/xk0BbUOQN1S9qXg8XB2hBG/V0oRbm31/GvO
ZXdIJTMcxiE2ENQHTgj0CWellnmW9LuGi0y0Q0AXtsspkxCRs/x5hndBevqPW8Db
YabpqHcJb1MMwtEkmN2aPioS/XErVRj9Df6eoDmFSe+hN/ypCWxkWw==
-----END RSA PRIVATE KEY-----" > $AGENTLOCATION/ssl/server.key
    echo "-----BEGIN CERTIFICATE-----
MIIEDDCCAvSgAwIBAgIUGlyN5jS/QmjKE2GIn4qHoogOjvcwDQYJKoZIhvcNAQEL
BQAwgaoxCzAJBgNVBAYTAk1ZMQ4wDAYDVQQIDAVKb2hvcjEPMA0GA1UEBwwGU2t1
ZGFpMRwwGgYDVQQKDBNDb29sIENvZGUgU2RuLiBCaGQuMREwDwYDVQQLDAhSdW5D
bG91ZDEnMCUGA1UEAwweUnVuQ2xvdWQgQ2VydGlmaWNhdGUgQXV0aG9yaXR5MSAw
HgYJKoZIhvcNAQkBFhFmaWtyaUBydW5jbG91ZC5pbzAgFw0xOTEyMjUxMzE0Mjla
GA85OTk5MTIzMTIzNTk1OVowfDELMAkGA1UEBgwCTVkxDjAMBgNVBAgMBUpvaG9y
MQ8wDQYDVQQHDAZTa3VkYWkxGzAZBgNVBAoMElJ1bkNsb3VkIFNkbi4gQmhkLjEY
MBYGA1UECwwPUnVuQ2xvdWQgU2VydmVyMRUwEwYDVQQDDAwyMDguNjguMzguODAw
ggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCst1orRwfzE2ntpu08Aer/
5ctyfNTWRFwlLTrh/tey/tpf5k+0a8PxFpPBl6HMoo2Rueoy+pZ5UbgqlhuI2mGq
sWL/C/ZdrldOnhD30q4jOn3ZXfviPidU7FZveykGd/5P3WhwmLDNvWrm8ltBYq5e
7VvkwLSW+avO0+u8wrXlMf5suF2hU6ET109rb1Px/7C45wbGo0g/Yu1DFX4VKQO9
QFhqm8Kt6qiTDwTSNMuKsu981OYjyads0RbRHWO5z7Chp528lASICTyL6dyE0K6O
AX5XolVnFVBIhm2r2XD7IBQRTCUAm0M4BoZSeUhOUD/pje7uS+ki4MIhHWcQUdWb
AgMBAAGjVTBTMA4GA1UdDwEB/wQEAwIF4DAPBgNVHRMBAf8EBTADAgEAMA8GA1Ud
EQQIMAaHBNBEJlAwHwYDVR0jBBgwFoAUerpZ7vrWQAB5l4zLk656tKo4MEUwDQYJ
KoZIhvcNAQELBQADggEBAHGQkzs1tWJrciLuGR3T4T1qtYQe2LQh+/qE5pT6hyMK
0SVP0DrM2KNsWmSbKEHpaftIjd05P63r+FLvqu4O/A6kD0FvOcabvCCQ+2ITrjwu
zXvGl5E9Uj4c3YotEgkNN5TvcSvaFtDcGVExxF88WnzjuH236yUmc4xvRwwY1312
YnPpVbOo5j26irgzmIenmv/LyiCkMNzSewExf14cxtDHCY4Kus5gkO1Bqur0Nqr3
bisYXB4YUkWJQnIErlgCUZxtu8B7cZRPu2taM+Z+Z+/8cBCg1zTzEACDxTp4zsRB
R57AddJ3hSibjy0eGAwQQVBWq/VjKOZu19vz19J6prM=
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
    curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "err", "message": "'"$message"'"}'
    exit 1
fi

if [[ "$OSNAME" != "Ubuntu" ]]; then
    message="This installer only support $OSNAME"
    echo $message
    curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "err", "message": "'"$message"'"}'
    exit 1
fi

if [[ $(uname -m) != "x86_64" ]]; then
    message="This installer only support x86_64 architecture"
    echo $message
    curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "err", "message": "'"$message"'"}'
    exit 1
fi

grep -q $OSVERSION <<< $SUPPORTEDVERSION
if [[ $? -ne 0 ]]; then
    message="This installer does not support $OSNAME $OSVERSION"
    echo $message
    curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "err", "message": "'"$message"'"}'
    exit 1
fi

# Checking open port
CheckingPortAccessible


# Bootstrap the server
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "sti"}'
BootstrapServer

# Bootstrap the installer
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "upg"}'
BootstrapInstaller

# Enabling Swap if Not Enabled
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "sw"}'
sleep 2
EnableSwap

# Install The Packages
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "si"}'
sleep 2
InstallPackage

# Supervisor
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "sv"}'
sleep 2
BootstrapSupervisor

# Fail2Ban
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "cf"}'
sleep 2
BootstrapFail2Ban

# MariaDB
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "cm"}'
sleep 2
BootstrapMariaDB

# Web Application
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "cu"}'
sleep 2
BootstrapWebApplication

# Auto Update
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "au"}'
sleep 2
FixAutoUpdate

# Agent
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "cra"}'
sleep 2
BootstrapAgent

# Firewall
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "cfd"}'
sleep 2
BootstrapFirewall

# Composer
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "ic"}'
sleep 2
InstallComposer

# Tweak
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "tw"}'
sleep 2
RegisterPathAndTweak

# Systemd Service
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "sta"}'
sleep 2
BootstrapSystemdService

curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/status/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli -d '{"status": "fin"}'
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
curl -4 -H "Content-Type: application/json" -X POST https://manage.runcloud.io/webhooks/serverinstallation/firstregistration/zmcrIQBMH0QkmHGWfmNNBIbsiB1577452469nOe0pfVgnQrQBtgGhEbRJXrtpl4LibU1IEefBJ2MRfGb6oAYPSL7Ig3PcTDiqa80/BWGDsPGEF024XDsb4KKrMXjJ0IqDiVj1FAxti2BPbiS11OKWMn019n6TJwN80Gjgb1xU8FUd2fQpMzJqVySMMs07rq8fvSlQUVSti375dD8FWrl1mEdVSu0BnjwL0wli
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