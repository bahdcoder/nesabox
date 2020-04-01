<template>
    <site-layout>
        <template slot="content">
            <flash />
            <card title="Ssl certificate" v-if="! site.ssl_certificate_installed">
                <info class="mb-3">
                    To obtain a valid Let's Encrypt certificate, make sure your DNS configuration for {{ site.name }} has an A record pointing to {{ server.ip_address }}. This would be verified when obtaining the certificate.
                </info>
                <v-button @click="install" :loading="installing || site.installing_certificate" label='Install ssl certificate' />
            </card>
            <card title='Ssl Certificate' v-else>
                <info>
                    Ssl certificate for {{ site.name }} is installed and active. It will be automatically renewed.
                </info>
            </card>
        </template>
    </site-layout>
</template>

<script>

export default {
    data() {

        return {
            installing: false
        }
    },
    computed: {
        site() {
            return this.$root.sites[this.$route.params.site] || {}
        },
        server() {
            return this.$root.servers[this.$route.params.server] || {}
        },
        serverId() {
            return this.$route.params.server
        },
        siteId() {
            return this.$route.params.site
        }
    },
    methods: {
        install() {
            this.installing = true

            axios.post(`/api/servers/${this.serverId}/sites/${this.siteId}/lets-encrypt`)
                .then(({ data: site }) => {
                    this.$root.sites = {
                        ...this.$root.sites,
                        [this.siteId]: site
                    }
                })
                .finally(() => {
                    this.installing = false
                })
        }
    },
}
</script>
