<template>
    <server-layout @mounted="serverMounted">
        <template slot='content'>
            <flash />
            <confirm-modal
                :confirming="deleting"
                @confirm="deleteRule"
                :open="!!deletingRule"
                @close="closeConfirmDelete"
                confirmHeading="Delete firewall rule"
                :confirmText="
                    `Are you sure you want to delete the firewall rule ${deletingRule && deletingRule.name}?`
                "
            />
            <card class="mb-6" title='Server network' v-if="server.provider === 'digital-ocean'">
                <info>
                    Below is a list of all of the other servers that can access this server. You can expose a specific port to a selected list of servers. This is really helpful when using a server as a separate database, cache, or queue worker.
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

                    <text-input
                        name='from'
                        class="mt-4"
                        label='Ports'
                        v-model="form.ports"
                        :errors="errors.ports"
                        placeholder='27017,6379'
                        help='Provide which ports you want this server to have access to. You can add multiple ports separated by commas. For example, if you want this server to be able to access a Mongodb server and Redis server, provide ports 27017,6379'
                    />

                <v-button label='Update network' class="mt-4" :disabled="familyServers.length === 0" @click="updateNetwork" :loading="updatingNetwork" />
            </card>

            <card class="mb-6" title='New firewall rule'>
                <form @submit.prevent="addRule">
                    <info>
                        If you do not provide a "FROM IP ADDRESS", the specified port will be open to any IP address on the internet.
                    </info>

                    <text-input
                        name='name'
                        label='Name'
                        class="mt-4"
                        :errors="errors.name"
                        v-model="firewallForm.name"
                        placeholder='Websockets app'
                        help='Give this firewall rule a memorable name.'
                    />

                    <text-input
                        name='port'
                        label='Port'
                        class="mt-4"
                        placeholder='6001'
                        :errors="errors.port"
                        v-model="firewallForm.port"
                    />

                    <text-input
                        name='from'
                        class="mt-4"
                        :errors="errors.from"
                        label='From IP Address'
                        v-model="firewallForm.from"
                        placeholder='196.50.6.1,196.520.16.31'
                        help='You can add multiple IP addresses separated by commas'
                    />

                    <v-button
                        type='submit'
                        class="mt-5"
                        label='Add rule'
                        :loading="addingRule"
                    />
                </form>
            </card>

            <card title='Firewall rules' :table="true" :rowsCount="rules.length" emptyTableMessage='No rules added to this server.'>
                <v-table :headers="headers" :rows="rules" >
                    <template slot='row' slot-scope='{ row, header }'>
                        <table-status
                            v-if="header.value === 'status'"
                            :status="row.status"
                        />

                        <delete-button
                            @click="deletingRule = row"
                            v-if="header.value === 'actions'"
                        />

                        <span
                            class="text-gray-800 text-sm"
                            v-if="['name', 'port', 'from'].includes(header.value)"
                        >
                            {{ row[header.value] }}
                        </span>
                    </template>
                </v-table>
            </card>
        </template>
    </server-layout>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    servers: [],
                    ports: ''
                },
                deleting: false,
                deletingRule: null,
                headers: [{
                    label: 'Name',
                    value: 'name'
                }, {
                    label: 'Port',
                    value: 'port'
                }, {
                    label: 'From IP Address',
                    value: 'from'
                }, {
                    label: 'Status',
                    value: 'status'
                }, {
                    label: '',
                    value: 'actions'
                }],
                firewallForm: {
                    name: '',
                    from: '',
                    port: '',
                },
                addingRule: false,
                errors: {},
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
            },
            rules() {
                return this.server.firewall_rules || []
            }
        },
        methods: {
            closeConfirmDelete() {
                this.deleting = false
                this.deletingRule = null
            },
            deleteRule() {
                axios.delete(`/api/servers/${this.server.id}/firewall-rules/${this.deletingRule.id}`)
                    .then(({ data: server }) => {
                        this.$root.servers = {
                            ...this.$root.servers,
                            [server.id]: server
                        }
                    })
                    .catch(({ response }) => {
                        this.$root.flashMessage(response.data.message || 'Failed deleting firewall rule.', 'error')
                    })
                    .finally(() => {
                        this.deleting = false
                        this.deletingRule = null
                    })
            },
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

                axios.patch(`/api/servers/${this.server.id}/network`, {
                    ...this.form,
                    ports: this.form.ports.split(',')
                })
                    .then(({ data: server }) => {
                        this.$root.flashMessage('Network has been updated.')

                        this.$root.servers = {
                            ...this.$root.servers,
                            [server.id]: server
                        }
                    })
                    .catch(({ response }) => {
                        this.$root.flashMessage(response.data.message || 'Failed updating network.', 'error')
                    })
                    .finally(() => {
                        this.updatingNetwork = false
                    })
            },
            serverMounted() {
                this.form = {
                    ...this.form,
                    ports: (this.server.friend_servers || []).length > 0 ? (this.server.friend_servers || [])[0].ports : '',
                    servers: (this.server.friend_servers || []).map(server => server.friend_server_id)
                }
            },
            addRule() {
                this.addingRule = true

                axios.post(`/api/servers/${this.server.id}/firewall-rules`, {
                    ...this.firewallForm,
                    from: this.firewallForm.from.split(',')
                })
                .then(({ data: server }) => {
                    this.firewallForm = {
                        name: '',
                        port: '',
                        from: ''
                    }

                    this.$root.servers = {
                        ...this.$root.servers,
                        [server.id]: server
                    }
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.errors = response.data.errors

                        let invalidIpAddresses = false

                        Object.keys(response.data.errors).forEach((error) => {
                            if (error.match(/from/) && error !== 'from') {
                                invalidIpAddresses = true
                            }
                        })

                        if (invalidIpAddresses) {
                            this.errors = {
                                ...this.errors,
                                from: ['Some of the ip addresses are invalid. Please check again.']
                            }
                        }
                    }
                }).finally(() => {
                    this.addingRule = false
                })
            }
        },
    }
</script>
