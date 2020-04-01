<template>
    <component :is="wrapper" @mounted="serverMounted">
        <template slot="content">
            <flash />
            <confirm-modal
                @confirm="deleteKey"
                :open="!!selectedKey"
                :confirming="deleting"
                @close="selectedKey = null"
                confirmHeading="Delete SSH key"
                :confirmText="
                    `Are you sure you want to delete the ssh key ${selectedKey && selectedKey.name}?`
                "
            />
            <card title='Add SSH key' class="mb-6">
                <form @submit.prevent="submit">
                    <info class="mb-5" v-if="wrapper === 'account-layout'">
                        These keys would be added to every new server you provision.
                    </info>
                    <text-input
                        name='name'
                        label='Name'
                        v-model="form.name"
                        placeholder='Macbook-Pro'
                        :errors="formErrors.name"
                        help='Provide a memorable name for this SSH key.'
                    />

                    <textarea-input
                        name='name'
                        class="mt-6"
                        v-model="form.key"
                        label='Public key'
                        component='textarea'
                        :errors="formErrors.key"
                        help="This is the public key of your SSH key pair."
                    />

                    <v-button type='submit' class="mt-5" label="Add key" :loading="submitting" />
                </form>
            </card>

            <card title='Active SSH Keys' :table="true" :rowsCount="keys.length" :emptyTableMessage="wrapper === 'account-info' ? 'No SSH keys yet.' : 'No SSH keys added to this server yet.'">
                <v-table :headers="headers" :rows="keys" >
                    <template slot='row' slot-scope='{ row, header }'>
                        <table-status
                            v-if="header.value === 'status'"
                            :status="row.status"
                        />

                        <delete-button
                            @click="selectedKey = row"
                            v-if="header.value === 'actions'"
                        />

                        <span
                            class="text-gray-800 text-sm"
                            v-if="['name'].includes(header.value)"
                        >
                            {{ row[header.value] }}
                        </span>
                    </template>
                </v-table>
            </card>
        </template>
    </component>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    name: '',
                    key: '',
                },
                deleting: false,
                selectedKey: null,
                headers: [{
                    label: 'Name',
                    value: 'name'
                }, {
                    label: 'Status',
                    value: 'status'
                }, {
                    label: '',
                    value: 'actions'
                }],
                wrapper: this.$route.name === 'account.ssh-keys' ? 'account-layout' : 'server-layout'
            }
        },
        computed: {
            keys() {
                if (this.wrapper === 'account-layout') {
                    return this.$root.auth.sshkeys
                }

                return this.server.sshkeys || []
            },
            server() {
                return this.$root.servers[this.$route.params.server] || {}
            }
        },
        methods: {
            serverMounted() {
                this.initializeForm(this.wrapper === 'account-info' ? '/api/me/sshkeys' : `/api/servers/${this.server.id}/sshkeys`)
            },
            submit() {
                this.submitForm()
                    .then(user => {
                        this.form = {
                            name: '',
                            key: ''
                        }

                        if (this.wrapper === 'account-info') {
                            this.$root.auth = user
                        } else {
                            this.$root.servers = {
                                ...this.$root.servers,
                                [this.server.id]: user
                            }
                        }

                        this.$root.flashMessage('Ssh key saved.')
                    })
                    .catch(({ response }) => {
                        this.$root.flashMessage(response.data.message || 'Failed adding key.', 'error')
                    })
            },
            deleteKey() {
                this.deleting = true

                axios.delete(this.wrapper === 'account-info' ? `/api/me/sshkeys/${this.selectedKey.id}` : `/api/servers/${this.server.id}/sshkeys/${this.selectedKey.id}`)
                    .then(({ data: user }) => {
                        if (this.wrapper === 'account-info') {
                            this.$root.auth = user
                        } else {
                            this.$root.servers = {
                                ...this.$root.servers,
                                [this.server.id]: user
                            }
                        }

                        this.$root.flashMessage('Ssh key deleted.')
                    })
                    .catch(({ response }) => {
                        this.$root.flashMessage(response.data.message || 'Failed deleting SSH key.', 'error')
                    })
                    .finally(() => {
                        this.selectedKey = null

                        this.deleting = false
                    })
            }
        }
    }
</script>
