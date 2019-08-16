SITE_NAME=$1
NESA_USER=$2

# The first step is to get a free port for this site
read LOWERPORT UPPERPORT < /proc/sys/net/ipv4/ip_local_port_range
\
SITE_PORT=$(comm -23 <(seq $LOWERPORT $UPPERPORT | sort) <(ss -tan | awk '{print $4}' | cut -d':' -f2 | grep "[0-9]\{1,5\}" | sort -u) | shuf | head -n 1)

# Create config folder for new site
mkdir -p /etc/nginx/nesa-conf/$SITE_NAME

# Create base config file for this site
cat > /etc/nginx/nesa-conf/$SITE_NAME/base.conf << EOF
# ----------------------------------------------------------------------
# | Nesa base configuration file for $SITE_NAME - (Do not remove)      |
# ----------------------------------------------------------------------

server {
    listen 80;
    listen [::]:80;

    server_name www.$SITE_NAME;
    return 301 \$scheme://$SITE_NAME\$request_uri;
}
EOF

# Next we'll create the actual nginx configuration for this site
cat > /etc/nginx/conf.d/$SITE_NAME.conf << EOF
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
  include h5bp/basic.conf;

  # Web performance - Include gzip compression 
  include h5bp/web_performance/compression.conf;
  include h5bp/web_performance/pre-compressed_content_gzip.conf;

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

echo $SITE_PORT
