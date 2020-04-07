# Get the name of this site
SITE_NAME=$1
BRANCH=$2
REPOSITORY_URL=$3
SSH_USER=$4
PORT=$5
UPDATE_LOGS_ENDPOINT=$6
APP_HOSTNAME=$7
LOG_WATCHER_NAME=$8
WEB_DIRECTORY=$9

set -e

if [ -d /home/$SSH_USER/$SITE_NAME ]
then
rm -rf /home/$SSH_USER/$SITE_NAME
fi
git clone --single-branch --branch $BRANCH $REPOSITORY_URL /home/$SSH_USER/$SITE_NAME

# Generate PM2 Ecosystem config file

n lts

cat > /home/$SSH_USER/.$SSH_USER/ecosystems/$SITE_NAME.config.js  << EOF
module.exports = {
    apps: [{
        name: '$SITE_NAME',
        script: 'index.js',
        instances: 1,
        autorestart: true,
        exec_mode: 'cluster',
        log_date_format: 'YYYY-MM-DD HH:mm',
        cwd: '/home/$SSH_USER/$SITE_NAME$WEB_DIRECTORY',
        interpreter: '$(n which lts)',
        env: {
            NODE_ENV: 'production',
            PORT: $PORT
        },
        error_file: '/home/nesa/.pm2/logs/$SITE_NAME',
        out_file: '/home/nesa/.pm2/logs/$SITE_NAME',
    }]
}
EOF

if [ ! -d /home/$SSH_USER/.pm2/logs ]
then
    mkdir -p /home/$SSH_USER/.pm2/logs
fi

cat > /home/$SSH_USER/.pm2/logs/$SITE_NAME << EOF
<<<< PM2 Logs start >>>>
EOF
