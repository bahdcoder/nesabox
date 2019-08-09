METRICS_SITE_NAME=$1
NGINX_USER=$2
NGINX_PASSWORD=$3
DATABASE_USER=$4
DATABASE_PASSWORD=$5
MONGODB_AUTH_USER=$6
MONGODB_AUTH_PASSWORD=$7

# Run the script that installs all packages needed for netdata to work correctly.

curl -Ss 'https://raw.githubusercontent.com/netdata/netdata-demo-site/master/install-required-packages.sh' >/tmp/kickstart.sh && bash /tmp/kickstart.sh -i netdata-all --non-interactive

# clone the netdata repository and run netdata installer

git clone https://github.com/netdata/netdata.git netdata-temp-folder --depth=100

# Execute the netdata install script
cd netdata-temp-folder

./netdata-installer.sh --dont-wait

cd ~

# Download fresh netdata.conf file
curl -o /etc/netdata/netdata.conf http://localhost:19999/netdata.conf

# Enable plugins in netdata config
# sed 's/# python.d/ python.d/g' netdata.conf

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

# Create the nginx config to enable stub_status endpoint

cat >> /etc/nginx/sites-available/netdata << EOF
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

# Link this nginx config file
ln -s /etc/nginx/sites-available/netdata /etc/nginx/sites-enabled/

# Create the nginx.conf file to enable the nginx netdata plugin

cat >> /etc/netdata/python.d/nginx.conf << EOF
localhost:
  name : 'local'
  url  : 'http://127.0.0.1/stub_status'
EOF

# Create a mysql user for setting up mysql monitoring
if [ -f /etc/init.d/mysql* ]; then
mysql -e "CREATE USER '$DATABASE_USER'@'localhost' IDENTIFIED BY '$DATABASE_PASSWORD';"
mysql -e "GRANT USAGE ON *.* TO $DATABASE_USER@localhost IDENTIFIED BY '$DATABASE_PASSWORD';"
mysql -e "FLUSH PRIVILEGES;"

# Create a mysql.conf file to enable the mysql netdata plugin
cat >> /etc/netdata/python.d/mysql.conf << EOF
tcp:
    name: 'local'
    host: '127.0.0.1'
    port: '3306'
    user: '$DATABASE_USER'
    pass: '$DATABASE_PASSWORD'
EOF

fi

# Create a mongodb user if mongodb is installed
mongo --version

if [ $? -eq 0 ]; then
# Create a mongodb user for monitoring
mongo --eval "db.getSiblingDB('admin').createUser({ user: '$DATABASE_USER', pwd: '$DATABASE_PASSWORD', roles: [{role: 'read', db: 'admin' }, { role: 'clusterMonitor', db: 'admin' }, {role: 'read', db: 'local' }]})" -u $MONGODB_AUTH_USER -p $MONGODB_AUTH_PASSWORD --authenticationDatabase admin

    # Create a mongodb.conf file to enable the mongodb netdata plugin
cat >> /etc/netdata/python.d/mongodb.conf << EOF
 local:
     name   : 'local'
     host   : '127.0.0.1'
     port   : 27017
     authdb : 'admin'
     user   : '$DATABASE_USER'
     pass   : '$DATABASE_PASSWORD'
EOF
fi

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

# Hide netdata behind an nginx configuration

# We are going to use a trick to acquire the nginx cert for the metrics site

# First let's create a normal app runnning on a random port, say 33765
cat > /home/temporal-node-app.js << EOF
const http = require('http');

const hostname = '127.0.0.1';
const port = 33765;

const server = http.createServer((req, res) => {
  res.statusCode = 200; 
  res.setHeader('Content-Type', 'text/plain');
  res.end('Hello World\n');
});

server.listen(port, hostname, () => {
  console.log(port, hostname)
});
EOF

npm -g install forever

# Next, we'll start the ndoe js application
forever start /home/temporal-node-app.js

# Create config file
cat > /etc/nginx/sites-available/$METRICS_SITE_NAME << EOF
server {
    listen 80;
    server_name $METRICS_SITE_NAME;

    location / {
    	proxy_pass http://localhost:19999;
    	proxy_set_header Host \$http_host;
    	proxy_set_header X-NginX-Proxy true;
    	proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
    	proxy_http_version 1.1;
    	proxy_set_header Upgrade \$http_upgrade;
    	proxy_set_header Connection "upgrade";
    	proxy_max_temp_file_size 0;
    	proxy_redirect off;
    	proxy_read_timeout 240s;
        proxy_set_header X-Forwarded-Proto \$scheme;
        proxy_set_header X-Real-IP \$remote_addr;

        auth_basic "Authentication is required to access this site.";
	    auth_basic_user_file /etc/nginx/.htpasswd;
    }
}
EOF

# Enable the nginx config for this site
ln -s /etc/nginx/sites-available/$METRICS_SITE_NAME /etc/nginx/sites-enabled/

# Install apache tools for securing nginx
apt-get install -y apache2-utils

# Create htpassword file if it does not exist
if [[ ! -e /etc/nginx/.htpasswd ]]; then
    touch /etc/nginx/.htpasswd
fi

# Create a user and password for accessing nginx site
htpasswd -b /etc/nginx/.htpasswd $NGINX_USER $NGINX_PASSWORD

# Generate ssl certificate for this sites
certbot --agree-tos -n --nginx --redirect -d $METRICS_SITE_NAME -m nesa@nesametrics.com

# Restart nginx
systemctl restart nginx
