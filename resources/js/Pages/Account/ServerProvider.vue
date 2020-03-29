<template>
    <account-layout>
        <template slot="content">
            <confirm-modal
                :confirming="deleting"
                @confirm="deleteProfile"
                @close="closeConfirmDelete"
                :open="!!deletingProvider"
                confirmHeading="Delete provider profile"
                :confirmText="
                    `Are you sure you want to delete your ${deletingProvider &&
                        deletingProvider.profileName} profile for ${deletingProvider &&
                        deletingProvider.provider} ?`
                "
            />
            <card title="New Server Provider" class="mb-5">
                <flash class="my-2" />
                <form @submit.prevent="submit">
                    <v-radio
                        id="provider"
                        label="Provider"
                        v-model="form.provider"
                        :options="serverOptions"
                        :errors="formErrors.provider"
                    />

                    <div class="w-full mt-5">
                        <text-input
                            v-if="form.provider"
                            name="profileName"
                            label="Profile name"
                            v-model="form.profileName"
                            :errors="formErrors.profileName"
                            help="This should be a memorable name to identify the provider api key. It can also be the name of the account."
                        />
                    </div>

                    <div class="w-full mt-5">
                        <text-input
                            v-if="form.provider"
                            :name="apiKeyLabel.name"
                            :label="apiKeyLabel.label"
                            v-model="form[apiKeyLabel.name]"
                        >
                            <template slot="help">
                                <small class="text-gray-600">
                                    Generate an {{ apiKeyLabel.label }} for
                                    {{ form.provider }} here
                                    <a
                                        class="ml-1 text-sha-green-500"
                                        :href="apiKeyLabel.link"
                                        target="_blank"
                                        >{{ apiKeyLabel.link }}</a
                                    >
                                </small>
                            </template>
                        </text-input>
                    </div>

                    <div class="flex justify-end w-full w-full mt-5">
                        <v-button
                            type="submit"
                            :loading="submitting"
                            :disabled="submitting"
                            class="w-full md:w-1/5"
                            label="Add provider"
                        />
                    </div>
                </form>
            </card>

            <card
                title="Active Providers"
                :table="true"
                :rowsCount="credentials.length"
                emptyTableMessage="No providers yet."
            >
                <v-table :headers="table.headers" :rows="credentials">
                    <template slot="row" slot-scope="{ row, header }">
                        <span
                            v-if="
                                ['profileName', 'provider'].includes(
                                    header.value
                                )
                            "
                        >
                            {{ row[header.value] }}
                        </span>

                        <delete-button
                            @click="setDeletingProvider(row)"
                            v-if="header.value === 'actions'"
                        />
                    </template>
                </v-table>
            </card>
        </template>
    </account-layout>
</template>

<script>
export default {
    data() {
        return {
            table: {
                headers: [
                    {
                        label: 'Profile name',
                        value: 'profileName'
                    },
                    {
                        label: 'Provider',
                        value: 'provider'
                    },
                    {
                        label: '',
                        value: 'actions'
                    }
                ]
            },
            serverOptions: [
                {
                    label: 'Digital Ocean',
                    value: 'digital-ocean'
                },
                {
                    label: 'Linode',
                    value: 'linode'
                },
                {
                    label: 'Vultr',
                    value: 'vultr'
                }
            ],
            form: {
                provider: '',
                profileName: '',
                accessToken: '',
                apiKey: '',
                apiToken: ''
            },
            deletingProvider: null,
            deleting: false
        }
    },
    computed: {
        credentials() {
            let providers = []

            Object.keys(this.$root.auth.providers).forEach(provider => {
                providers = providers.concat(
                    this.$root.auth.providers[provider].map(_ => ({
                        ..._,
                        provider: {
                            'digital-ocean': 'Digital Ocean',
                            linode: 'Linode',
                            vultr: 'Vultr'
                        }[provider]
                    }))
                )
            })

            return providers
        },
        apiKeyLabel() {
            return (
                {
                    'digital-ocean': {
                        label: 'API Token',
                        name: 'apiToken',
                        link:
                            'https://cloud.digitalocean.com/account/api/tokens'
                    },
                    linode: {
                        label: 'Access Token',
                        name: 'accessToken',
                        link: 'https://cloud.linode.com/profile/tokens'
                    },
                    vultr: {
                        label: 'API Key',
                        name: 'apiKey',
                        link: 'https://my.vultr.com/settings/#settingsapi'
                    }
                }[this.form.provider] || {}
            )
        }
    },
    mounted() {
        this.initializeForm('/api/settings/server-providers')
    },
    methods: {
        submit() {
            this.submitForm().then(data => {
                this.form = {
                    profileName: '',
                    accessToken: '',
                    apiKey: '',
                    apiToken: ''
                }

                this.$root.auth = data
                this.$root.flashMessage(
                    `Provider added successfully. You can now create servers using your new ${this.form.provider} credentials.`
                )
            })
        },
        setDeletingProvider(provider) {
            this.deletingProvider = provider
        },
        deleteProfile() {
            this.deleting = true

            axios
                .delete(
                    `/api/settings/server-providers/${this.deletingProvider.id}`
                )
                .then(({ data: user }) => {
                    this.$root.flashMessage(`Profile has been deleted.`)

                    this.$root.auth = user

                    this.deletingProvider = null
                    this.deleting = false
                })
                .catch(() => {
                    this.$root.flashMessage('Failed deleting profile.')

                    this.deleting = false
                    this.deletingProvider = null
                })
        },
        closeConfirmDelete() {
            this.deletingProvider = null
            this.deleting = false
        }
    }
}
</script>
