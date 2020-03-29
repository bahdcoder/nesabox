<template>
    <site-layout>
        <template slot="content">
            <flash />
            <card title="PM2 Ecosystem file" class="mb-6" v-if="site.type === 'nodejs'">
                <info>
                    This is the PM2 configuration for your site. Here you can
                    define environment secrets. The content of this file is
                    never saved on our servers.
                </info>

                <v-button
                    @click="fetchPm2File"
                    :loading="fetchingEcosystemFile"
                    label="Edit Ecosystem file"
                    class="w-full md:w-auto mt-4"
                    v-if="!ecosystemFile"
                />
                <codemirror
                    v-model="ecosystemFile"
                    :options="codeMirrorOptions"
                    v-if="ecosystemFile"
                    class="my-4"
                />

                <v-button
                    @click="updatePm2File"
                    :loading="updatingEcosystemFile"
                    label="Update Ecosystem file"
                    class="w-full md:w-auto mt-4"
                    v-if="ecosystemFile"
                />
            </card>

            <card title="Nginx configuration file">
                <info>
                    This is the Nginx configuration for your site.
                    <span v-if="site.type === 'nodejs'" class="ml-1"
                        >Make sure the proxy port here matches the port your
                        application is running on.</span
                    >
                </info>

                <v-button
                    @click="fetchNginxConfigFile"
                    :loading="fetchingNginxConfigFile"
                    label="Edit Nginx Configuration"
                    class="w-full md:w-auto mt-4"
                    v-if="!nginxConfigFile"
                />
                <codemirror
                    v-model="nginxConfigFile"
                    :options="codeMirrorOptions"
                    v-if="nginxConfigFile"
                    class="my-4"
                />

                <v-button
                    @click="updateNginxConfigFile"
                    :loading="updatingNginxConfigFile"
                    label="Update Nginx file"
                    class="w-full md:w-auto mt-4"
                    v-if="nginxConfigFile"
                />
            </card>
        </template>
    </site-layout>
</template>

<script>
import { codemirror } from 'vue-codemirror'

import 'codemirror/lib/codemirror.css'
import 'codemirror/theme/lucario.css'
import 'codemirror/mode/shell/shell.js'

export default {
    components: {
        codemirror
    },
    data() {
        const codeMirrorOptions = {
            theme: 'lucario',
            tabSize: 4,
            line: true,
            mode: 'shell',
            lineNumbers: true
        }

        return {
            fetchingEcosystemFile: false,
            fetchingNginxConfigFile: false,
            updatingEcosystemFile: false,
            updatingNginxConfigFile: false,
            ecosystemFile: '',
            nginxConfigFile: '',
            codeMirrorOptions
        }
    },
    computed: {
        site() {
            return this.$root.sites[this.$route.params.site] || {}
        },
        serverId() {
            return this.$route.params.server
        },
        siteId() {
            return this.$route.params.site
        }
    },
    methods: {
        fetchPm2File() {
            this.fetchingEcosystemFile = true
            axios
                .get(
                    `/api/servers/${this.serverId}/sites/${this.siteId}/ecosystem-file`
                )
                .then(({ data }) => {
                    this.ecosystemFile = data
                })
                .catch(() => {
                    this.$root.flashMessage(
                        'Failed to fetch the ecosystem file.',
                        'error'
                    )
                })
                .finally(() => {
                    this.fetchingEcosystemFile = false
                })
        },
        fetchNginxConfigFile() {
            this.fetchingNginxConfigFile = true

            axios
                .get(
                    `/api/servers/${this.serverId}/sites/${this.siteId}/nginx-config`
                )
                .then(({ data }) => {
                    this.nginxConfigFile = data
                })
                .catch(() => {
                    this.$root.flashMessage(
                        'Failed to fetch the nginx configuration file.',
                        'error'
                    )
                })
                .finally(() => {
                    this.fetchingNginxConfigFile = false
                })
        },
        updateNginxConfigFile() {
            this.updatingNginxConfigFile = true

            axios
                .post(
                    `/api/servers/${this.serverId}/sites/${this.siteId}/nginx-config`,
                    {
                        nginxConfig: this.nginxConfigFile
                    }
                )
                .then(() => {
                    this.$root.flashMessage('Nginx configuration file updated.')

                    this.nginxConfigFile = ''
                })
                .catch(() => {
                    this.$root.flashMessage(
                        'Failed to update the nginx configuration file.',
                        'error'
                    )
                })
                .finally(() => {
                    this.updatingNginxConfigFile = false
                })
        },
        updatePm2File() {
            this.updatingEcosystemFile = true

            axios
                .post(
                    `/api/servers/${this.serverId}/sites/${this.siteId}/ecosystem-file`,
                    {
                        ecosystemFile: this.ecosystemFile
                    }
                )
                .then(() => {
                    this.$root.flashMessage('Ecosystem file updated.')

                    this.ecosystemFile = ''
                })
                .catch(() => {
                    this.$root.flashMessage(
                        'Failed to update the ecosystem file.',
                        'error'
                    )
                })
                .finally(() => {
                    this.updatingEcosystemFile = false
                })
        }
    }
}
</script>
