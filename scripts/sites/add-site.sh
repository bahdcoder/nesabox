# This script is used to add sites to a server

SITE_NAME=$1

read LOWERPORT UPPERPORT < /proc/sys/net/ipv4/ip_local_port_range
\
# Find 
SITE_PORT=$(comm -23 <(seq $LOWERPORT $UPPERPORT | sort) <(ss -tan | awk '{print $4}' | cut -d':' -f2 | grep "[0-9]\{1,5\}" | sort -u) | shuf | head -n 1)

# Create config file
cat > /etc/nginx/sites-available/$SITE_NAME << EOF
server {
    listen 80;
    server_name $SITE_NAME;

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

# Enable the nginx config for this site
ln -s /etc/nginx/sites-available/$SITE_NAME /etc/nginx/sites-enabled/

systemctl restart nginx

echo $SITE_PORT
