const Fs = require('fs')
const Http = require('http')

const siteName = ''
const postPath = '/sites/52266fee-b767-4b04-823c-a24b8d539367/pm2-logs'
const hostName = 'bf5d9465.ngrok.io'

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

const filePath = '.ex.json'

function sendToNesabox(logs) {
    const data = JSON.stringify({
        logs
    })

    const options = {
        hostname: hostName,
        port: 80,
        path: postPath,
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
    readLastLines(filePath, 10, sendToNesabox)
})
