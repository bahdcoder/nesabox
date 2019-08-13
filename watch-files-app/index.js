const Fs = require('fs')
const Http = require('http')
const Axios = require('axios')
const SocketIo = require('socket.io')
const ReadLastLines = require('read-last-lines')

const API_URL = process.env.API_URL || 'http://nesabox-api.ping'

const Server = Http.createServer((req, res) => {})

const Io = SocketIo(Server)

Io.on('connection', socket => {
    socket.on(
        'subscribe',
        ({ access_token, linesCount = 1000, filePaths } = {}) => {
            Axios.get(`${API_URL}/me`, {
                headers: {
                    Authorization: `Bearer ${access_token}`
                }
            }).then(({}) => {
                // We'll emit the logs for each of the watched files, before proceeding to
                // setup watchers
                const emitFileContent = filePath =>
                    ReadLastLines.read(filePath, linesCount).then(lines => {
                        socket.emit(`${filePath}`, lines)
                    })

                const filesToWatch = filePaths.split(' ')

                filesToWatch.forEach(filePath => {
                    if (!Fs.existsSync(filePath)) return

                    emitFileContent(filePath)
                    Fs.watchFile(filePath, () => emitFileContent(filePath))
                })
            })
        }
    )
})

Server.listen(process.env.PORT || 5654)
