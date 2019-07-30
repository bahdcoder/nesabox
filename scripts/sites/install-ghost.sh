# This script installs ghost blog as a site on a server

abort() {
  echo
  echo "  $@" 1>&2
  echo
  exit 1
}

SITE_NAME=$1

MYSQL_USER=$2
MYSQL_PASSWORD=$3
MYSQL_DATABASE=$4

SITE_PORT=$5

if [ ! -d "$HOME/.nvm" ]
then
    echo '-----------------------------------------------------------------------------'
    echo '                                                                             '
    echo 'Downloading nvm'
    echo '                                                                             '
    echo '-----------------------------------------------------------------------------'
    curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.34.0/install.sh | bash

    export NVM_DIR="$HOME/.nvm"
    [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
    [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"

    echo '-------------------------------------------------------------------'
    echo '                                                                   '
    echo 'Installing latest version of node'
    echo '                                                                   '
    echo '-------------------------------------------------------------------'
    nvm install node
fi

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"

test $? -eq 0 || abort Failed installing ghost cli.

echo '---------------------------------------------------------------------'
echo '                                                                     '
echo 'Creating site folder'
echo '                                                                     '
echo '---------------------------------------------------------------------'
# Create site folder
mkdir /home/espectra/$SITE_NAME

# Change folder owner to espectra
chown espectra:espectra /home/espectra/$SITE_NAME

# Change folder permissions
chmod 775 /home/espectra/$SITE_NAME

# Change directory to the site folder
cd /home/espectra/$SITE_NAME

echo '-----------------------------------------------------------------------'
echo '                                                                       '
echo 'Downloading latest version of ghost'
echo '                                                                       '
echo '-----------------------------------------------------------------------'
# Download the latest ghost
curl -L https://github.com/TryGhost/Ghost/releases/download/2.25.9/Ghost-2.25.9.zip -o ghost-latest.zip
test $? -eq 0 || abort Failed downloading ghost zip.

echo '-----------------------------------------------------------------------'
echo '                                                                       '
echo 'Unzipping ghost'
echo '                                                                       '
echo '-----------------------------------------------------------------------'
# Unzip download
unzip ghost-latest.zip -d ./
test $? -eq 0 || abort Failed unzipping ghost.

# Delete zip file
rm ghost-latest.zip
test $? -eq 0 || abort Failed removing ghost.

echo '----------------------------------------------------'
echo '                                                    '
echo 'Installing latest supported node version of ghost   '
echo '                                                    '
echo '----------------------------------------------------'
# Install and switch to the latest node version supported by ghost
nvm install 10.13.0

echo '----------------------------------------------------'
echo '                                                    '
echo 'Installing latest supported node version of ghost'
echo '                                                     '
echo '---------------------------------------------------- '
# Install ghost dependencies
npm i -g yarn

echo '--------------------------------------------------------------------------------'
echo '                                                                                '
echo 'Installing node process manager'
echo '                                                                                '
echo '--------------------------------------------------------------------------------'
# Install pm2
npm install -g pm2
test $? -eq 0 || abort Failed installing pm2.


echo '-------------------------------------------------------------------------------'
echo '                                                                               '
echo 'Installing the knex migrator'
echo '                                                                               '
echo '-------------------------------------------------------------------------------'
# Install knex migrator
npm install -g knex-migrator
# test $? -eq 0 || abort Failed installing knex migrator.


yarn install --production
# test $? -eq 0 || abort Failed installing ghost dependencies with yarn.

# Create ghost production config

echo '---------------------------------------------------------------------------------------'
echo '                                                                                       '
echo 'Generating ghost configuration'
echo '                                                                                       '
echo '---------------------------------------------------------------------------------------'
cat > config.production.json << EOF
{
    "url": "https://$SITE_NAME",
    "database": {
        "client": "mysql",
        "connection": {
            "host"     : "127.0.0.1",
            "user"     : "$MYSQL_USER",
            "password" : "$MYSQL_PASSWORD",
            "database" : "$MYSQL_DATABASE"
        }
    },
    "server": {
        "host": "127.0.0.1",
        "port": $SITE_PORT
    },
    "paths": {
        "contentPath": "content/"
    },
    "logging": {
        "level": "info",
        "rotation": {
            "enabled": true
        },
        "transports": ["file", "stdout"]
    }
}
EOF
test $? -eq 0 || abort Failed creating ghost config.

# Change directory to the site folder
cd /home/espectra/$SITE_NAME

echo '---------------------------------------'
echo '                                       '
echo 'Running ghost migrations'
echo '                                       '
echo '---------------------------------------'
# Run knex migrations
NODE_ENV=production knex-migrator init
test $? -eq 0 || abort Failed migrating to database.

# Start the application using PM2

echo '------------------------------------------------------'
echo '                                                      '
echo 'Starting ghost blog'
echo '                                                      '
echo '-----------------------------------------------------'
# Start the first pm2 instance
NODE_ENV=production pm2 start index.js --name $SITE_NAME
test $? -eq 0 || abort Failed starting ghost blog.

#  # Enable the nginx config for this site
# ln -s /etc/nginx/sites-available/$SITE_NAME /etc/nginx/sites-enabled/
echo '------------------------------------------------------'
echo '                                                      '
echo 'Starting ghost blog'
echo '                                                      '
echo '------------------------------------------------------'
# Restart nginx 
# systemctl restart nginx
# certbot --agree-tos -n --nginx --redirect -d $SITE_NAME -m worker@espectra.com
