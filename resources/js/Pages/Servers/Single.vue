<template>
    <server-layout>
        <template slot="content">
            <card title="New Site" class="mb-4 md:mb-8">
                <flash />
                <form @submit.prevent="submit">
                    <div class="w-full mt-5">
                        <text-input
                            name="domain"
                            label="Root domain"
                            v-model="form.name"
                            :errors="formErrors.name"
                            placeholder="www.example.com"
                            help="You can host multiple sites on a server. To add a new site, provide the domain name the site would be hosted on."
                        />
                    </div>

                    <div class="w-full mt-5">
                        <select-input
                            name="type"
                            v-model="form.type"
                            label="Project type"
                            :options="projectTypes"
                            :errors="formErrors.type"
                            placeholder="www.example.com"
                            help="If your project is a Single Page application, static html site or related, select Static HTML. Select Nodejs for a standard Nodejs application."
                        />
                    </div>

                    <div class="w-full mt-5">
                        <text-input
                            name="directory"
                            placeholder="/dist"
                            label="Web directory"
                            v-model="form.directory"
                            :errors="formErrors.directory"
                            help="If your app builds into a sub directory such as /dist or /build, set the web root to the build directory. The Nginx configuration will point to it."
                        />
                    </div>

                    <div class="flex justify-end w-full w-full mt-5">
                        <v-button
                            type="submit"
                            label="Add site"
                            :disabled="submitting"
                            class="w-full md:w-1/5"
                        />
                    </div>
                </form>
            </card>

            <card
                title="Active Sites"
                :rowsCount="sites.length"
                :table="true"
                emptyTableMessage="No sites on this server yet."
            >
                <v-table
                    :headers="table.headers"
                    :rows="sites"
                    @row-clicked="routeToSite"
                >
                    <template slot="row" slot-scope="{ row, header }">
                        <span class="text-gray-800 text-sm" v-if="header.value === 'name'">
                            {{ row.name }}
                        </span>

                        <span v-if="header.value === 'type'" class="text-gray-800 text-sm capitalize">
                            {{ row.type }}
                        </span>

                        <table-status
                            v-if="header.value === 'status'"
                            :status="row.status"
                        />

                        <div class="flex " v-if="header.value === 'repository'">
                            <svg
                                v-if="!row.repository"
                                class="mr-3"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M10 14L12 12M12 12L14 10M12 12L10 10M12 12L14 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                    stroke="#4A5568"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                            {{ row.repository || 'None' }}
                        </div>
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
            projectTypes: [
                {
                    label: 'Static HTML',
                    value: 'html'
                },
                {
                    label: 'Nodejs',
                    value: 'nodejs'
                }
            ],
            form: {
                directory: '/',
                type: 'nodejs',
                name: ''
            },
            table: {
                headers: [
                    {
                        label: 'Domain',
                        value: 'name'
                    },
                    {
                        label: 'Repository',
                        value: 'repository'
                    },
                    {
                        label: 'Type',
                        value: 'type'
                    },
                    {
                        label: 'Status',
                        value: 'status'
                    }
                ]
            }
        }
    },
    mounted() {
        this.initializeForm(`/api/servers/${this.serverId}/sites`)
    },
    methods: {
        submit() {
            this.submitForm().then(server => {
                this.form = {
                    directory: '/',
                    type: 'nodejs',
                    name: ''
                }

                this.$root.servers = {
                    ...this.$root.servers,
                    [server.id]: server
                }

                this.$root.flashMessage('Server has been created.')
            })
        },
        routeToSite(site) {
            if (site.status !== 'active') {
                return
            }

            this.$router.push(`/servers/${this.serverId}/sites/${site.id}`)
        }
    },
    computed: {
        serverId() {
            return this.$route.params.server
        },
        server() {
            return this.$root.servers[this.serverId]
        },
        sites() {
            if (!this.server) {
                return []
            }

            return this.$root.servers[this.serverId].sites
        }
    }
}
</script>
