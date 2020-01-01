const Express = require('express')
const acme = require('acme-client')

const app = Express()

app.post('/', async (req, res) => {
    try {
        const accountKey = await acme.forge.createPrivateKey()

        console.log('accountKey', accountKey)

        const client = new acme.Client({
            directoryUrl: acme.directory.letsencrypt.staging,
            accountKey
        })

        console.log('CLIENT CREATED.')

        const [key, csr] = await acme.forge.createCsr({
            commonName: 'staging-p.nesabox.com',
            altNames: ['staging-q.nesabox.com', 'staging.r.nesabox.com']
        })

        console.log('csr CREATED.')

        const cert = await client.auto({
            csr,
            termsOfServiceAgreed: true,
            email: 'bahdcoder@gmail.com',
            challengeCreateFn: async (authz, challenge, keyAuthorization) => {
                // here, we'll make a POST request to nesabox
                // nesabox will receive the token and file contents
                // then ssh into the user's server.
                // then write the file
                //
            },
            challengeRemoveFn: async (authz, challenge, keyAuthorization) => {}
        })

        console.log('cert CREATED.')

        res.json({
            key,
            csr,
            cert
        })
    } catch (e) {
        console.log(e)
        res.status(400).json(e)
    }
})

const ACME_PORT = process.env.ACME_PORT || 3444

app.listen(ACME_PORT, () => {
    console.log(`Acme server running on http://localhost:${ACME_PORT}`)
})
