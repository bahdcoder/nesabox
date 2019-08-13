# Nesabox api

su nesa <<EOF
cd /home/nesa
# Install latest version of node, just in case
n 12.8.0

# Install pm2 process manager
npm install -g pm2

# Change directory into the .nesa folder
cd ~/.nesa

# Create the file watching project
mkdir -p /home/nesa/.nesa/nesabox-logs-watcher
curl -Ss 'https://9094ca87.ngrok.io/logs-watcher-index-js' > /home/nesa/.nesa/nesabox-logs-watcher/index.js
curl -Ss 'https://9094ca87.ngrok.io/logs-watcher-package-json' > /home/nesa/.nesa/nesabox-logs-watcher/package.json

cd /home/nesa/.nesa/nesabox-logs-watcher
npm install
export API_URL=https://9094ca87.ngrok.io
export PORT=23443
export NODE_ENV=production
pm2 start index.js --name nesabox-logs-watcher --interpreter /usr/local/n/versions/node/12.8.0/bin/node
EOF
