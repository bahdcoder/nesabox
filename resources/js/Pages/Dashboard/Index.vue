<template>
    <layout>
        <div
            class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6 rounded-t"
        >
            <div
                class="-ml-4 -mt-2 flex items-center justify-between flex-wrap sm:flex-no-wrap"
            >
                <div class="ml-4 mt-2">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Servers
                    </h3>
                </div>
                <div class="ml-4 mt-2 flex-shrink-0 flex items-center">
                    <span class="inline-flex rounded-md shadow-sm">
                        <v-button
                            component="router-link"
                            to="/servers/create"
                            label="Add new server"
                        />
                    </span>
                </div>
            </div>
        </div>

        <div
            v-if="!loading && servers.length === 0"
            class="w-full flex px-6 py-12 justify-center items-center bg-white shadow"
        >
            <p>No servers yet.</p>
        </div>

        <div v-if="!loading && servers.length !== 0" class="w-full bg-white">
            <v-table
                @row-clicked="routeToServer"
                :headers="table.headers"
                :rows="servers"
            >
                <template slot="row" slot-scope="{ row, header }">
                    <span
                        v-if="header.value === 'ip_address'"
                        class="inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                    >
                        {{ row.ip_address }}
                    </span>
                    <span
                        v-if="header.value === 'type'"
                        class="inline-flex text-xs leading-5 font-semibold rounded-full capitalize text-gray-700"
                    >
                        {{ row.type.split('_').join(' ') }}
                    </span>
                    <table-status
                        v-if="header.value === 'status'"
                        :status="row.status"
                    />
                    <div
                        v-if="header.value === 'name'"
                        class="flex items-center"
                    >
                        <div class="flex-shrink-0 h-6 w-6">
                            <v-svg
                                :icon="row.provider"
                                v-if="row.provider !== 'linode'"
                                class="w-6 h-6"
                            />
                            <v-svg
                                :icon="row.provider"
                                v-if="row.provider === 'linode'"
                                :width="30"
                                :height="30"
                            />
                        </div>
                        <div class="ml-4">
                            <div
                                class="text-sm leading-5 font-medium text-gray-900"
                            >
                                {{ row[header.value] }}
                            </div>
                        </div>
                    </div>
                </template>
            </v-table>
        </div>
    </layout>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            showOnlyOwnServers: false,
            table: {
                headers: [
                    {
                        label: 'Name',
                        value: 'name'
                    },
                    {
                        label: 'IP Address',
                        value: 'ip_address'
                    },
                    {
                        label: 'Status',
                        value: 'status'
                    },
                    {
                        label: 'Type',
                        value: 'type'
                    }
                ]
            },
            interval: null
        }
    },
    computed: {
        servers() {
            return this.showOnlyOwnServers
                ? this.$root.allServers.servers
                : this.$root.allServers.servers.concat(
                      this.$root.allServers.team_servers
                  )
        }
    },
    mounted() {
        this.fetchServers().then(() => {
            this.subscribeServers()
        })
    },
    methods: {
        fetchServers() {
            return axios.get('/api/servers').then(({ data }) => {
                this.loading = false
                this.$root.allServers = data
            })
        },
        routeToServer(server) {
            this.$router.push(`/servers/${server.id}`)
        },
        toggleShowOwnServers() {
            this.showOnlyOwnServers = !this.showOnlyOwnServers
        },
        subscribeServers() {
            this.interval = setInterval(this.fetchServers, 3000)
        }
    },
    beforeDestroy() {
        clearInterval(this.interval)
    }
}
</script>
