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

git clone --single-branch --branch $BRANCH $REPOSITORY_URL /home/$SSH_USER/$SITE_NAME

# Make sure latest version of node is available
n 12.8.0

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

cat > /home/$SSH_USER/.$SSH_USER/log-watchers/$SITE_NAME.watcher.js << EOF
const Fs = require('fs')
const Http = require('http')

function readLastLines(file, lastXLines, cb) {
    const stream = Fs.createReadStream(file, {
        flags: 'r',
        encoding: 'utf-8',
        fd: null,
        mode: '0666',
        bufferSize: 64 * 1024
    })

    let fileData = ''

    stream.on('data', function(data) {
        let lines = data.split('\n')

        fileData = lines.splice([lines.length - lastXLines]).join('\n')
    })

    stream.on('end', function() {
        cb(fileData)
    })
}

const filePath = '/home/$SSH_USER/.pm2/logs/$SITE_NAME'

function sendToNesabox(logs) {
    const data = JSON.stringify({
        logs
    })

    const options = {
        hostname: '$APP_HOSTNAME',
        port: 80,
        path: '$UPDATE_LOGS_ENDPOINT',
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    }

    const req = Http.request(options, res => {})

    req.write(data)
    req.end()
}

Fs.watchFile(filePath, () => {
    readLastLines(filePath, 40, sendToNesabox)
})
EOF

touch /home/$SSH_USER/.pm2/logs/$SITE_NAME

pm2 start /home/$SSH_USER/.$SSH_USER/log-watchers/$SITE_NAME.watcher.js --name=$LOG_WATCHER_NAME --interpreter /usr/local/n/versions/node/12.8.0/bin/node
