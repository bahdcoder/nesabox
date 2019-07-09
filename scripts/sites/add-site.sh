# This script is used to add sites to a server

SITE_NAME=$1
WILD_CARD=$2
SITE_PORT=$3

# Create config file

# Check if wildcard subdomains is setup for this site
if  [ $WILD_CARD = 1 ]
then
  cat > /etc/nginx/sites-available/$SITE_NAME << EOF
server {
    listen 80;
    server_name *.$SITE_NAME;
    
    location / {
    	proxy_pass http://localhost:$SITE_PORT;
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
    }
}
EOF
else
  cat > /etc/nginx/sites-available/$SITE_NAME << EOF
server {
    listen 80;
    server_name $SITE_NAME;

    location / {
    	proxy_pass http://localhost:$SITE_PORT;
    	proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
    	proxy_set_header Host \$http_host;
    	proxy_set_header X-NginX-Proxy true;
    	proxy_http_version 1.1;
    	proxy_set_header Upgrade \$http_upgrade;
    	proxy_set_header Connection "upgrade";
    	proxy_max_temp_file_size 0;
    	proxy_redirect off;
    	proxy_read_timeout 240s;
        proxy_set_header X-Forwarded-Proto \$scheme;
        proxy_set_header X-Real-IP \$remote_addr;
    }
}
EOF
fi

# Enable the nginx config for this site
ln -s /etc/nginx/sites-available/$SITE_NAME /etc/nginx/sites-enabled/

systemctl restart nginx
