SITE_NAME=$1

# Install ssl certificate for nesabox site
certbot --agree-tos -n --nginx --redirect -d $SITE_NAME -m nesa@nesabox.com
