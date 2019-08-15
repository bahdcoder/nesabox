#
# REQUIRES:
#    - site (the forge site instance)
#    - certificate (the forge certificate instance)
#    - domains (the domain list)
#    - env
#

# Initial Setup

set -e

TIME=$(date +%s)

# Check If Certificate Is Valid For > 14 Days

if [ -f /etc/nginx/ssl/app.nesabox.com/603928/server.crt ] && openssl x509 -checkend 1209600 -noout -in /etc/nginx/ssl/app.nesabox.com/603928/server.crt
then
echo "Certificate is still valid."
exit 0
fi

# Create Well Known Directory

echo "Creating challenge directory..."

mkdir -p /home/forge/.letsencrypt
echo "FORGE TEST FILE" > /home/forge/.letsencrypt/test
chown -R forge /home/forge/.letsencrypt
chgrp -R forge /home/forge/.letsencrypt

mkdir -p /etc/nginx/ssl/app.nesabox.com/603928

# Install Shell Client

echo "Installing LetsEncrypt client..."

if [ ! -d /root/letsencrypt$TIME ]; then
cd /root
git clone https://github.com/lukas2511/dehydrated letsencrypt$TIME
fi

cd /root/letsencrypt$TIME

if [ -f private_key.pem ]; then
rm private_key.pem
fi

# Build Domain File & Config File

echo "Configuring client..."

echo "app.nesabox.com > 15658220625d548c6e0454b" > domains.txt

cat > config << EOF
CA="https://acme-v02.api.letsencrypt.org/directory"
WELLKNOWN="/home/forge/.letsencrypt"
ACCOUNTDIR="./../letsencrypt_accounts"
EOF

# Setup Well Known Location

cat > /etc/nginx/forge-conf/app.nesabox.com/server/letsencrypt_challenge.conf << EOF
location /.well-known/acme-challenge {
auth_basic off;
allow all;
alias /home/forge/.letsencrypt;
}
EOF

# Restart Nginx

echo "Restarting Nginx..."

service nginx restart || true
service nginx reload || true

# Generate Certificate

echo "Generating Certificate..."

./dehydrated --register --accept-terms
./dehydrated -c

# Install The Certificate

echo "Installing Certificate..."

cp "certs/15658220625d548c6e0454b/privkey.pem" /etc/nginx/ssl/app.nesabox.com/603928/server.key
cp "certs/15658220625d548c6e0454b/fullchain.pem" /etc/nginx/ssl/app.nesabox.com/603928/server.crt

chmod 644 /etc/nginx/ssl/app.nesabox.com/603928/server.key
chmod 644 /etc/nginx/ssl/app.nesabox.com/603928/server.crt

echo "Restarting Nginx..."

service nginx restart || true
service nginx reload || true
nginx -s reload || true

# Delete Library

rm -rf /root/letsencrypt$TIME