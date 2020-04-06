SITE_NAME=$1
NESA_USER=$2

set -e

# The first step is to get a free port for this site
read LOWERPORT UPPERPORT < /proc/sys/net/ipv4/ip_local_port_range
\
SITE_PORT=$(comm -23 <(seq $LOWERPORT $UPPERPORT | sort) <(ss -tan | awk '{print $4}' | cut -d':' -f2 | grep "[0-9]\{1,5\}" | sort -u) | shuf | head -n 1)

# Create config folder for new site
mkdir -p /etc/nginx/nesa-conf/$SITE_NAME

# Create base config file for this site
cat > /etc/nginx/nesa-conf/$SITE_NAME/base.conf << EOF
# --------------------------------------------------------------------------------
# | Nesa base configuration file for $SITE_NAME - (Do not remove or modify)      |
# -------------------------------------------------------------------------------

server {
    listen 80;
    listen [::]:80;

    server_name www.$SITE_NAME;
    return 301 \$scheme://$SITE_NAME\$request_uri;
}
EOF

# Create default configurations file for this site
cat > /etc/nginx/nesa-conf/$SITE_NAME/default_configurations.conf << EOF
# -----------------------------------------------------------------------------------
# | Nesa default configuration file for $SITE_NAME - (Do not remove or modify)      |
# | Nginx Server Configs | MIT License                                              |
# | https://github.com/h5bp/server-configs-nginx                                    |
# ----------------------------------------------------------------------------------

# Nginx Server Configs | MIT License
# https://github.com/h5bp/server-configs-nginx

include h5bp/internet_explorer/x-ua-compatible.conf;
include h5bp/security/referrer-policy.conf;
include h5bp/security/x-content-type-options.conf;
# include h5bp/security/x-frame-options.conf;
include h5bp/security/x-xss-protection.conf;
include h5bp/location/security_file_access.conf;
include h5bp/cross-origin/requests.conf;
EOF

# Create certificates configuration
cat > /etc/nginx/nesa-conf/$SITE_NAME/ssl_certificates.conf << EOF
# --------------------------------------------------------------------------------------------
# | Nesa ssl certificates configuration file for $SITE_NAME - (Do not remove or modify)      |
# -------------------------------------------------------------------------------------------
EOF

# Next we'll create the actual nginx configuration for this site
cat > /etc/nginx/sites-available/$SITE_NAME << EOF
# ----------------------------------------------------------------------
# | Nesa base configuration file for $SITE_NAME - (Do not remove)      |
# ----------------------------------------------------------------------
include nesa-conf/$SITE_NAME/base.conf;

server {
  listen [::]:80;
  listen 80;

  # The host name to respond to
  server_name $SITE_NAME;

  # Include the basic h5bp config set
  include nesa-conf/$SITE_NAME/default_configurations.conf;

  # Web performance - Include gzip compression 
  include h5bp/web_performance/compression.conf;
  include h5bp/web_performance/pre-compressed_content_gzip.conf;

  # SSL optimization and security
  include h5bp/ssl/ssl_engine.conf;
  include h5bp/ssl/policy_intermediate.conf;
  include nesa-conf/$SITE_NAME/ssl_certificates.conf;

  location / {
    proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
    proxy_set_header X-Real-IP \$remote_addr;
    proxy_set_header Host \$http_host;

    proxy_http_version 1.1;
    proxy_set_header Upgrade \$http_upgrade;
    proxy_set_header Connection "upgrade";

    proxy_pass http://localhost:$SITE_PORT;
    proxy_redirect off;
    proxy_read_timeout 240s;

    proxy_set_header X-NginX-Proxy true;
    proxy_set_header X-Forwarded-Proto \$scheme;
    proxy_set_header X-Real-IP \$remote_addr;
  }
}
EOF

# Activate site
ln -s /etc/nginx/sites-available/$SITE_NAME /etc/nginx/sites-enabled/

# Restart nginx
systemctl reload nginx

echo $SITE_PORT
