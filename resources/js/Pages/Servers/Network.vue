<template>
    <server-layout @mounted="serverMounted">
        <template slot='content'>
            <flash />
            <card title='Server network' v-if="server.provider === 'digital-ocean'">
                <info>
                    Below is a list of all of the other servers this server may access. With a server's network, you can use a server as a separate database, cache or queue worker. Only servers from the same provider, and in the same region as this server would be listed here. 
                </info>

                <div class="mt-6">
                    <label
                        for=""
                        class="block text-sm font-medium leading-5 text-gray-700"
                        >Can connect to</label
                    >
                    <small class="text-gray-600"
                        >Select all the servers this server would have access to.</small
                    >
                    <checkbox
                        class="mt-4"
                        :key="server.id"
                        :name="server.id"
                        :label="server.name"
                        v-for="server in familyServers"
                        @input="selectServer($event, server.id)"
                        :checked="form.servers.includes(server.id)"
                    />
                </div>

                <v-button label='Update network' class="mt-4" :disabled="familyServers.length === 0" @click="updateNetwork" :loading="updatingNetwork" />
            </card>
        </template>
    </server-layout>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    servers: []
                },
                updatingNetwork: false
            }
        },
        computed: {
            server() {
                return this.$root.servers[this.$route.params.server] || {}
            },
            familyServers() {
                if (! this.server || ! this.server.id) return []

                return this.server.family_servers
            }
        },
        methods: {
            selectServer(checked, server) {
                if (checked) {
                    
                    this.form = {
                        ...this.form,
                        servers: [
                            ...this.form.servers,
                            server
                        ]
                    }
                } else {
                    this.form = {
                        ...this.form,
                        servers: this.form.servers.filter(s => s !== server)
                    }
                }
            },
            updateNetwork() {
                this.updatingNetwork = true

                axios.patch(`/api/servers/${this.server.id}/network`, this.form)
                    .then(({ data: server }) => {
                        this.$root.flashMessage('Network has been updated.')

                        this.$root.servers = {
                            ...this.$root.servers,
                            [server.id]: server
                        }
                    })
                    .catch(({ response }) => {
                        this.$root.flashMessage(response.data.message || 'Failed updating network.')
                    })
                    .finally(() => {
                        this.updatingNetwork = false
                    })
            },
            serverMounted() {
                this.form = {
                    ...this.form,
                    servers: this.server.friend_servers || []
                }
            }
        },
    }
</script>
