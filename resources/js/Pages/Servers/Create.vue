<template>
    <layout>
        <div v-if="!created">
            <h2 class="mb-4 font-semibold text-2xl">Create server</h2>
            <form @submit.prevent="create" class="w-full" method="POST">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 md:px-12 py-5 md:py-12 bg-white sm:p-6">
                        <div class="mb-4">
                            <label class="block w-full font-semibold"
                                >Server provider</label
                            >
                            <small class="text-gray-600 inline-block"
                                >Select your server provider. Nesabox will
                                connect using your provider's API and provision
                                a server with the specs you select. If you have
                                already provisioned a server on any provider,
                                select custom provider. Nesabox will connect to
                                your already provisioned server.
                            </small>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div
                                :key="provider.icon"
                                v-for="provider in providers"
                                @click="setProvider(provider)"
                                class="py-8 rounded-sm flex items-center flex-col justify-center w-full cursor-pointer border-gray-300 hover:border-sha-green-500"
                                :class="{
                                    'border-2 border-sha-green-500':
                                        form.provider === provider.icon,
                                    'border opacity-50':
                                        form.provider !== provider.icon
                                }"
                            >
                                <v-svg
                                    :icon="provider.icon"
                                    :width="provider.width"
                                    :height="provider.height"
                                />

                                <p class="mt-3">
                                    {{ provider.name }}
                                </p>
                            </div>
                        </div>

                        <hr class="my-6 md:my-12" v-if="form.provider" />

                        <div
                            class="w-full mt-5"
                            v-if="form.provider && form.provider !== 'custom'"
                        >
                            <text-input
                                name="credential"
                                component="select"
                                :options="credentials"
                                label="Provider Credential"
                                v-model="form.credential_id"
                            >
                                <template slot="help">
                                    <small class="text-gray-600">
                                        Select the {{ form.provider }} api key
                                        to be used to create this server.
                                        <span v-if="credentials.length === 0">
                                            You do not have any
                                            {{ form.provider }} credentials yet.
                                            <router-link
                                                to="/account/credentials"
                                                class="rounded text-white bg-sha-green-500 p-1 text-xs px-2"
                                                >Add one here</router-link
                                            >
                                        </span>
                                    </small>
                                </template>
                            </text-input>
                        </div>

                        <div v-if="showServerName" class="w-full mt-8">
                            <text-input
                                name="name"
                                label="Server name"
                                v-model="form.name"
                                :errors="errors.name"
                                placeholder="exasperant-sand-dunes-093"
                                help="Choose a memorable name that helps you easily find this server. This could be the name of your project."
                            />
                        </div>

                        <div v-if="showServerName" class="w-full mt-8">
                            <text-input
                                name="type"
                                component="select"
                                label="Server type"
                                v-model="form.type"
                                :errors="errors.type"
                                :options="serverTypes"
                                help="The default installs everything you need to run sites on a server. The load balancer provisions only nginx, optimizes it for load balancing, with no additional software."
                            />
                        </div>

                        <div
                            v-if="showServerName && form.provider !== 'custom'"
                            class="w-full mt-8"
                        >
                            <text-input
                                name="region"
                                component="select"
                                label="Region"
                                :options="regions"
                                v-model="form.region"
                                :errors="errors.region"
                                help="Select the region / data center where this server should be provisioned. If you are horizontally scaling, make sure you select the same region for all your resources."
                            />
                        </div>

                        <div
                            v-if="showServerName && form.provider !== 'custom'"
                            class="w-full mt-8"
                        >
                            <text-input
                                name="size"
                                component="select"
                                label="Size"
                                :options="sizes"
                                v-model="form.size"
                                :errors="errors.size"
                                help="Select the size of this server. RAM, GB and vCPUs."
                            />
                        </div>

                        <div
                            v-if="showServerName && form.provider === 'custom'"
                            class="w-full mt-8"
                        >
                            <text-input
                                name="size"
                                label="Size"
                                type="number"
                                placeholder="4"
                                v-model="form.size"
                                :errors="errors.size"
                                help="Provide the RAM of your server in GB. It'll be used to set the SWAP size."
                            />
                        </div>

                        <div
                            v-if="showServerName && form.provider === 'custom'"
                            class="w-full mt-8"
                        >
                            <text-input
                                name="region"
                                label="Region"
                                v-model="form.region"
                                :errors="errors.region"
                                placeholder="New York 1"
                                help="Provide the region of your custom server. This can help you identify the location of the server in future."
                            />
                        </div>

                        <div
                            class="w-full mt-8"
                            v-if="showServerName && form.provider === 'custom'"
                        >
                            <text-input
                                name="ip_address"
                                label="IP Address"
                                placeholder="196.50.6.1"
                                v-model="form.ip_address"
                                :errors="errors.ip_address"
                                help="Provide the public IPv4 address of your custom server. We'll use this so we can connect to your server."
                            />
                        </div>

                        <div
                            class="w-full mt-8"
                            v-if="showServerName && form.provider === 'custom'"
                        >
                            <text-input
                                name="private_ip_address"
                                placeholder="196.50.6.1"
                                label="Private IP Address"
                                v-model="form.private_ip_address"
                                :errors="errors.private_ip_address"
                                help="Provide the private IPv4 address of your custom server. This is optional, and is useful if you are setting up a network of servers."
                            />
                        </div>

                        <hr
                            class="my-6 md:my-12"
                            v-if="form.type && form.type !== 'load_balancer'"
                        />

                        <div
                            class="w-full mt-8"
                            v-if="form.type && form.type !== 'load_balancer'"
                        >
                            <h3 class="leading-6 font-semibold text-gray-900">
                                Databases
                            </h3>
                            <small class="mt-1 text-gray-500">
                                Check the databases you need installed on this
                                server.
                            </small>

                            <div class="mt-4">
                                <fieldset>
                                    <div
                                        class="mt-4"
                                        :key="database.value"
                                        v-for="database in databases"
                                    >
                                        <div class="relative flex items-start">
                                            <div
                                                class="absolute flex items-center h-5"
                                            >
                                                <input
                                                    type="checkbox"
                                                    :id="database.value"
                                                    @change="
                                                        selectDatabase(database)
                                                    "
                                                    :checked="
                                                        form.databases.includes(
                                                            database.value
                                                        )
                                                    "
                                                    class="form-checkbox h-4 w-4 text-sha-green-600 transition duration-150 ease-in-out"
                                                />
                                            </div>
                                            <div class="pl-7 text-sm leading-5">
                                                <label
                                                    :for="database.value"
                                                    class="font-medium text-gray-700"
                                                >
                                                    {{ database.label }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div
                        class="flex flex-wrap flex-wrap-reverse md:block px-4 py-3 bg-gray-50 text-right sm:px-6"
                    >
                        <router-link
                            to="/dashboard"
                            class="w-full md:w-auto inline-flex rounded-md shadow-sm"
                        >
                            <button
                                type="button"
                                class="w-full py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out"
                            >
                                Cancel
                            </button>
                        </router-link>
                        <span
                            class="mb-3 md:mb-0 w-full md:w-auto inline-flex rounded-md shadow-sm"
                        >
                            <v-button
                                type="submit"
                                :disabled="loading"
                                label="Deploy Server"
                            />
                        </span>
                    </div>
                </div>
            </form>
        </div>
        <div v-if="created && form.provider === 'custom'">
            <h2 class="mb-4 font-semibold text-2xl">Create server</h2>
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="p-5 bg-white">
                    <p class="w-full mb-4 text-gray-900">
                        Almost there! Login to your server as root and run the
                        following command. This would provision your server so
                        that it can be managed by us. Once done, your server
                        will become active on this dashboard.
                    </p>
                    <textarea
                        id="command"
                        readonly
                        class="w-full bg-gray-100 shadow-sm px-4 py-3 text-xs text-gray-600 border border-gray-200 rounded"
                        v-model="deployCommand"
                    />

                    <v-button
                        @click="copyCommand"
                        label="Copy command"
                        class="mt-4"
                    />
                    <v-trans-button
                        @click="$router.push('/dashboard')"
                        label="Go to dashboard"
                        class="mt-4"
                    />
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
export default {
    data() {
        return {
            errors: {},
            loading: false,
            created: false,
            deployCommand: '',
            databases: [
                {
                    label: 'Mongo DB v4.2',
                    value: 'mongodb'
                },
                {
                    label: 'MySQL v5.7',
                    value: 'mysql'
                },
                {
                    label: 'MySQL v8.0',
                    value: 'mysql8'
                },
                {
                    label: 'MariaDB v10.13',
                    value: 'mariadb'
                },
                {
                    label: 'Postgresql v11',
                    value: 'postgresql'
                }
            ],
            regionsAndSizes: {
                'digital-ocean': [],
                linode: [],
                vultr: []
            },
            serverTypes: [
                {
                    label: 'Default',
                    value: 'default'
                },
                {
                    label: 'Load balancer',
                    value: 'load_balancer'
                }
            ],
            providers: [
                {
                    icon: 'digital-ocean',
                    name: 'Digital Ocean'
                },
                {
                    icon: 'linode',
                    name: 'Linode'
                },
                {
                    icon: 'vultr',
                    name: 'Vultr'
                },
                {
                    icon: 'custom',
                    width: 60,
                    height: 60,
                    name: 'Custom Provider'
                }
            ],
            form: {
                name: '',
                provider: '',
                credential_id: '',
                databases: []
            }
        }
    },
    computed: {
        regions() {
            if (!this.form.provider) return []

            if (this.form.provider === 'custom') {
                return []
            }

            return this.regionsAndSizes[this.form.provider].regions
        },
        sizes() {
            if (!this.form.provider) return []

            if (this.form.provider === 'custom') {
                return []
            }

            return this.regionsAndSizes[this.form.provider].sizes
        },
        credentials() {
            if (!this.form.provider) return []

            if (this.form.provider === 'custom') {
                return []
            }

            return (this.$root.auth.providers[this.form.provider] || []).map(
                credential => ({
                    label: credential.profileName,
                    value: credential.id
                })
            )
        },
        showServerName() {
            if (!this.form.provider) {
                return false
            }

            if (this.form.provider !== 'custom' && !this.form.credential_id) {
                return false
            }

            if (this.form.provider === 'custom') {
                return true
            }

            return true
        }
    },
    methods: {
        create() {
            this.loading = true

            axios
                .post('/api/servers', this.form)
                .then(({ data }) => {
                    if (this.form.provider === 'custom') {
                        this.created = true

                        this.deployCommand = data.deploy_command
                    } else {
                        this.$router.push('/dashboard')
                    }
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.errors = response.data.errors
                    }
                })
                .finally(() => {
                    this.loading = false
                })
        },
        setProvider(provider) {
            this.form.provider = provider.icon

            this.form.credential_id = ''
        },
        selectDatabase(database) {
            const mysqlDatabases = ['mysql', 'mysql8', 'mariadb']

            let databases = [...this.form.databases]

            if (databases.includes(database.value)) {
                databases = databases.filter(db => db !== database.value)
            } else {
                if (mysqlDatabases.includes(database.value)) {
                    databases = databases.filter(
                        db => !mysqlDatabases.includes(db)
                    )
                }

                databases = [...databases, database.value]
            }

            this.form = {
                ...this.form,
                databases
            }
        },
        copyCommand() {
            const command = document.getElementById('command')

            command.select()
            command.setSelectionRange(0, 99999)
            document.execCommand('copy')
        }
    },
    async mounted() {
        const { data } = await axios.get('/api/servers/regions')

        this.regionsAndSizes = data
    }
}
</script>
