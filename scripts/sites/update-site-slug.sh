# This script is used to add sites to a server

OLD_SITE_NAME=$1
NEW_SITE_NAME=$2
SITE_PORT=$3

# Remove old nginx configs
rm /etc/nginx/sites-available/$OLD_SITE_NAME
rm /etc/nginx/sites-enabled/$OLD_SITE_NAME

# Remove the old ssl certificate
certbot delete --cert-name $OLD_SITE_NAME

# Create the new nginx config
cat > /etc/nginx/sites-available/$NEW_SITE_NAME << EOF
server {
    listen 80;
    server_name $NEW_SITE_NAME;

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
echo '>>>>>>>>> LINKING NEW CONFIG'
# Enable the nginx config for this site
ln -s /etc/nginx/sites-available/$NEW_SITE_NAME /etc/nginx/sites-enabled/
echo '>>>>>>>>> RESTARTING NGINX'
systemctl restart nginx
echo '>>>>>>>>> GENERATE NEW CERTIFICATE'
# Install ssl certificate for new nesabox site
certbot --agree-tos -n --nginx --redirect -d $NEW_SITE_NAME -m nesa@nesabox.com
