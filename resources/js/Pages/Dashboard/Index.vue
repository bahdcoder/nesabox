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
                    <div
                        class="hidden md:flex mr-3 items-center justify-center"
                    >
                        <span
                            :class="{
                                'bg-gray-200': !showOnlyOwnServers,
                                'bg-sha-green-600': showOnlyOwnServers
                            }"
                            class="relative inline-block flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-50 focus:outline-none focus:shadow-outline"
                            role="checkbox"
                            tabindex="0"
                            @click="toggleShowOwnServers"
                            :aria-checked="showOnlyOwnServers"
                        >
                            <span
                                aria-hidden="true"
                                :class="{
                                    'translate-x-5': showOnlyOwnServers,
                                    'translate-x-0': !showOnlyOwnServers
                                }"
                                class="relative inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"
                            >
                                <span
                                    :class="{
                                        'opacity-0 ease-out duration-100': showOnlyOwnServers,
                                        'opacity-100 ease-in duration-200': !showOnlyOwnServers
                                    }"
                                    class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity"
                                >
                                    <svg
                                        class="h-3 w-3 text-gray-400"
                                        fill="none"
                                        viewBox="0 0 12 12"
                                    >
                                        <path
                                            d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </span>
                                <span
                                    :class="{
                                        'opacity-100 ease-in duration-200': showOnlyOwnServers,
                                        'opacity-0 ease-out duration-100': !showOnlyOwnServers
                                    }"
                                    class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity"
                                >
                                    <svg
                                        class="h-3 w-3 text-sha-green-500"
                                        fill="currentColor"
                                        viewBox="0 0 12 12"
                                    >
                                        <path
                                            d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"
                                        />
                                    </svg>
                                </span>
                            </span>
                        </span>

                        <span class="inline-block ml-3 text-gray-800"
                            >Show only own servers</span
                        >
                    </div>
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
                        {{ row.type }}
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
                            <v-svg :icon="row.provider" class="w-6 h-6" />
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
            }
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
        axios.get('/api/servers').then(({ data }) => {
            this.loading = false
            this.$root.allServers = data
        })
    },
    methods: {
        routeToServer(server) {
            this.$router.push(`/servers/${server.id}`)
        },
        toggleShowOwnServers() {
            this.showOnlyOwnServers = !this.showOnlyOwnServers
        }
    }
}
</script>
