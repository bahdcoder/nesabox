# Get the name of this site
SITE_NAME=$1
BRANCH=$2
REPOSITORY_URL=$3
SSH_USER=$4
PORT=$5

git clone --single-branch --branch $BRANCH $REPOSITORY_URL /home/$SSH_USER/$SITE_NAME

# Make sure latest version of node is available
n lts

# Generate PM2 Ecosystem config file
cat > /home/$SSH_USER/.$SSH_USER/ecosystems/$SITE_NAME.config.js  << EOF
module.exports = {
    apps: [{
        name: '$SITE_NAME',
        script: 'index.js',
        instances: 1,
        autorestart: true,
        exec_mode: 'cluster',
        log_date_format: 'YYYY-MM-DD HH:mm',
        cwd: '/home/$SSH_USER/$SITE_NAME',
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

