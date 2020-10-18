<template>
    <site-layout>
        <template slot="content">
            <flash />
            <confirm-modal
                @confirm="uninstall"
                :confirming="uninstalling"
                :open="showUninstallConfirmation"
                @close="showUninstallConfirmation = false"
                confirmHeading="Uninstall SSL"
                :confirmText="
                    `Are you sure you want to uninstall ssl from this site ? This would delete the existing ssl certificate.`
                "
            />
            <card
                title="Ssl certificate"
                v-if="!site.ssl_certificate_installed && !installCustom"
            >
                <info class="mb-3">
                    To obtain a valid Let's Encrypt certificate, make sure your
                    DNS configuration for {{ site.name }} has an A record
                    pointing to {{ server.ip_address }}. This would be verified
                    when obtaining the certificate. Otherwise, you can provide a
                    custom certificate to be installed on the server.
                </info>
                <v-button
                    @click="install"
                    :loading="installing || site.installing_certificate"
                    label="Install ssl certificate"
                />
                <v-trans-button
                    class="mt-2 md:mt-0"
                    @click="installCustom = true"
                    label="Install custom certificate"
                    v-if="!installing && !site.installing_certificate"
                />
            </card>
            <card title="Ssl Certificate" v-if="site.ssl_certificate_installed">
                <info>
                    Ssl certificate for {{ site.name }} is installed and active.
                </info>

                <red-button
                    class="mt-3"
                    @click="showUninstallConfirmation = true"
                    label="Uninstall certificate"
                />
            </card>
            <card title="Custom ssl certificate" v-if="installCustom">
                <form @submit.prevent="installCustomCertificate">
                    <textarea-input
                        v-model="form.privateKey"
                        :errors="errors.privateKey"
                        name="privateKey"
                        :rows="6"
                        label="Private key"
                        help="This is the private key of your ssl certificate"
                    />
                    <textarea-input
                        v-model="form.certificate"
                        :errors="errors.certificate"
                        name="certificate"
                        :rows="6"
                        label="Certificate"
                        help="This is the actual certificate content."
                        class="mt-3"
                    />

                    <div class="flex flex-wrap justify-end mt-3">
                        <v-trans-button
                            label="Cancel"
                            class="mt-2 md:mt-0"
                            @click="installCustom = false"
                        />
                        <v-button
                            type="submit"
                            class="md:ml-3"
                            label="Install ssl certificate"
                        />
                    </div>
                </form>
            </card>
        </template>
    </site-layout>
</template>

<script>
export default {
    data() {
        return {
            installing: false,
            installCustom: false,
            uninstalling: false,
            showUninstallConfirmation: false,
            form: {
                certificate: '',
                privateKey: ''
            },
            errors: {}
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

            axios
                .post(
                    `/api/servers/${this.serverId}/sites/${this.siteId}/lets-encrypt`
                )
                .then(({ data: site }) => {
                    this.$root.sites = {
                        ...this.$root.sites,
                        [this.siteId]: site
                    }
                })
                .finally(() => {
                    this.installing = false
                })
        },
        installCustomCertificate() {
            this.installing = true

            axios
                .post(
                    `/api/servers/${this.serverId}/sites/${this.siteId}/custom-ssl`,
                    this.form
                )
                .then(({ data: site }) => {
                    this.$root.sites = {
                        ...this.$root.sites,
                        [this.siteId]: site
                    }

                    this.installCustom = false
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.errors = response.data.errors
                    }
                })
                .finally(() => {
                    this.installing = false
                })
        },
        uninstall() {
            this.uninstalling = true

            axios
                .post(
                    `/api/servers/${this.serverId}/sites/${this.siteId}/uninstall-ssl`
                )
                .then(({ data: site }) => {
                    this.$root.sites = {
                        ...this.$root.sites,
                        [this.siteId]: site
                    }
                })
                .catch(({ response }) => {
                    this.$root.flashMessage(response.data.message)
                })
                .finally(() => {
                    this.uninstalling = false
                    this.showUninstallConfirmation = false
                })
        }
    }
}
</script>
