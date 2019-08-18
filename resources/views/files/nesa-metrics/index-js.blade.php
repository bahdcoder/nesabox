const Axios = require('axios')
const Http = require('express')
const BodyParser = require('body-parser')

const Server = new Http()
Server.use(BodyParser.json())

Server.post('/', async (req, res) => {
    const { url } = req.body
    try {
        const { data } = await Axios.get(`http://localhost:19999/api/v1/data${url}`)

        return res.json(data)
    } catch (e) {
        return res.json({
            error: (e && e.response && e.response.message) || 'Failed fetching netdata.'
        })
    }
})

Server.listen({{ config('nesa.metrics_port') }})
