SITE_NAME=$1

# Create certificates configuration
cat > /etc/nginx/nesa-conf/$SITE_NAME/ssl_certificates.conf << EOF
# --------------------------------------------------------------------------------------------
# | Nesa ssl certificates configuration file for $SITE_NAME - (Do not remove or modify)      |
# -------------------------------------------------------------------------------------------

EOF

# Update base nginx configuration file for this domain
cat > /etc/nginx/nesa-conf/$SITE_NAME/base.conf << EOF
# --------------------------------------------------------------------------------
# | Nesa base configuration file for $SITE_NAME - (Do not remove or modify)      |
# --------------------------------------------------------------------------------

server {
    listen 80;
    listen [::]:80;

    server_name www.$SITE_NAME;
    return 301 \$scheme://$SITE_NAME\$request_uri;
}
EOF

# Change the following in main site configuration;

replace-in-file 'listen 443 ssl http2;' 'listen 80;'  /etc/nginx/sites-available/$SITE_NAME
replace-in-file 'listen [::]:443 ssl http2;' 'listen [::]:80;'  /etc/nginx/sites-available/$SITE_NAME

if [ -f /etc/nginx/ssl/$SITE_NAME/server.cer]
then
    rm /etc/nginx/ssl/$SITE_NAME/server.cer
fi

if [ -f /etc/nginx/ssl/$SITE_NAME/server.key]
then
    rm /etc/nginx/ssl/$SITE_NAME/server.key
fi

if [ -d /root/.acme.sh/$SITE_NAME]
then
    rm -rf /root/.acme.sh/$SITE_NAME
fi

# Reload nginx
systemctl reload nginx
# Yaay ! SSL is removed !!!
