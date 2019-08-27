SITE_NAME=$1

set -e

mkdir -p /etc/nginx/ssl/$SITE_NAME

# We'll obtain the certificate and copy it to the nginx folder
/root/.acme.sh/acme.sh --issue --nginx -d $SITE_NAME --cert-file /etc/nginx/ssl/$SITE_NAME/server.cer --key-file /etc/nginx/ssl/$SITE_NAME/server.key

# Create certificates configuration
cat > /etc/nginx/nesa-conf/$SITE_NAME/ssl_certificates.conf << EOF
# --------------------------------------------------------------------------------------------
# | Nesa ssl certificates configuration file for $SITE_NAME - (Do not remove or modify)      |
# -------------------------------------------------------------------------------------------

ssl_certificate /etc/nginx/ssl/$SITE_NAME/server.cer;
ssl_certificate_key /etc/nginx/ssl/$SITE_NAME/server.key;
EOF

# Update base nginx configuration file for this domain
cat > /etc/nginx/nesa-conf/$SITE_NAME/base.conf << EOF
# --------------------------------------------------------------------------------
# | Nesa base configuration file for $SITE_NAME - (Do not remove or modify)      |
# -------------------------------------------------------------------------------

server {
    listen 80;
    listen [::]:80;

    server_name .$SITE_NAME;
    return 301 https://\$host\$request_uri;
}

server {
  listen [::]:443 ssl http2;
  listen 443 ssl http2;

  server_name www.$SITE_NAME;

  include h5bp/ssl/ssl_engine.conf;
  include h5bp/ssl/policy_modern.conf;
  include nesa-conf/$SITE_NAME/ssl_certificates.conf;

  return 301 https://$SITE_NAME\$request_uri;
}
EOF

# Change the following in main site configuration;

# listen 80; ----> listen 443 ssl http2;
replace-in-file 'listen 80;' 'listen 443 ssl http2;'  /etc/nginx/sites-available/$SITE_NAME
# listen [::]:80; ---->listen [::]:443 ssl http2;
replace-in-file 'listen [::]:80;' 'listen [::]:443 ssl http2;'  /etc/nginx/sites-available/$SITE_NAME

# Reload nginx
systemctl reload nginx
# Yaay ! SSL is configured !!!
