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
                <div class="ml-4 mt-2 flex-shrink-0">
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
            <v-table @row-clicked="routeToServer" :headers='table.headers' :rows="servers">
                <template slot='row' slot-scope='{ row, header }'>
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
                        {{ row.type }}
                    </span>
                    <span
                        v-if="header.value === 'status'"
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                        :class="{
                            'bg-green-100 text-green-800': row.status === 'active',
                            'bg-blue-100 text-blue-800': ['initializing', 'installing'].includes(row.status)
                        }"
                    >
                        {{ row.status }}
                    </span>
                    <div v-if="header.value === 'name'" class="flex items-center">
                        <div class="flex-shrink-0 h-6 w-6">
                            <v-svg
                                :icon="row.provider"
                                class="w-6 h-6"
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
            allServers: {
                servers: [],
                team_servers: []
            },
            table: {
                headers: [{
                    label: 'Name',
                    value: 'name'
                }, {
                    label: 'IP Address',
                    value: 'ip_address'
                }, {
                    label: 'Status',
                    value: 'status'
                }, {
                    label: 'Type',
                    value: 'type'
                }],
            }
        }
    },
    computed: {
        servers() {
            return this.allServers.servers.concat(this.allServers.team_servers)
        }
    },
    mounted() {
        axios.get('/api/servers').then(({ data }) => {
            this.loading = false
            this.allServers = data
        })
    },
    methods: {
        routeToServer(server) {
            this.$router.push(`/servers/${server.id}`)
        }
    }
}
</script>
