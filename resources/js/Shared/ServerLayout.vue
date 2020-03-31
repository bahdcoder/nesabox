<template>
    <div>
        <sidebar-layout
            :nav="nav"
            :active="active"
            :showNav="server.status === 'active'"
        >
            <template slot="header">
                <div class="w-full flex flex-wrap justify-between mb-5" v-if="!loading">
                    <div class="w-full md:w-1/5">
                        <h3 class="text-lg text-gray-800">Server details</h3>
                    </div>
                    <div class="w-full md:w-4/5 flex flex-wrap md:justify-end">
                        <div class="w-full md:w-auto flex items-center flex-wrap md:pl-6 uppercase text-gray-700 tracking-wide">
                            <span class="w-full mt-2 md:mt-0 md:w-auto">{{ server.name }}</span>
                            <span class="w-full mt-2 md:mt-0 md:w-auto md:ml-5">{{ server.region }}</span>
                            <span class="w-full mt-2 md:mt-0 md:w-auto md:ml-5">{{ server.ip_address }}</span>
                            <span class="w-full mt-2 md:mt-0 md:w-auto md:ml-2" v-if="server.private_ip_address">({{ server.private_ip_address }})</span>
                            <table-status class="mt-2 md:mt-0 md:ml-4" :status="server.status" />
                        </div>
                    </div>
                </div>
            </template>
            <div v-if="server.status === 'active' && !loading">
                <slot name="content" />
            </div>

            <main v-else class="px-3">
                <div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
                    <div
                        class="w-full flex items-center justify-center"
                        v-if="loading"
                    ></div>
                    <div
                        class="w-full bg-white rounded shadow p-4 md:p-6"
                        v-if="!loading && server.status === 'initializing'"
                    >
                        <p
                            class="text-center text-gray-700"
                            v-if="server.provider !== 'custom'"
                        >
                            Your server has been provisioned on
                            {{ server.provider }}. We are currently installing all
                            necessary software. This process should take about 10
                            minutes or less on average.
                        </p>
                        <p class="text-center text-gray-700" v-else>
                            Your custom server is still initializing. Once the
                            installing script is done running, it'll become active.
                        </p>

                        <p
                            class="w-full py-4 flex flex-col items-center justify-center"
                        >
                            <spinner class="w-10 h-10" />

                            <red-button
                                @click="deleteServer"
                                label="Delete server"
                                class="w-full md:w-1/5 mt-5"
                            />
                        </p>
                    </div>

                    <div
                        class="w-full bg-white rounded shadow p-4 md:p-6"
                        v-if="!loading && server.status === 'new'"
                    >
                        <p class="text-center text-gray-700">
                            Your server is still being created on
                            {{ server.provider }}. It'll take about a minute. Once
                            we confirmed the server is active on
                            {{ server.provider }}, we'll begin running the
                            installation script.
                        </p>

                        <p
                            class="w-full py-4 flex items-center flex-col justify-center"
                        >
                            <spinner class="w-10 h-10" />

                            <red-button
                                class="mt-4"
                                @click="deleteServer"
                                :loading="deletingServer"
                                label="Delete server"
                            />
                        </p>
                    </div>
                </div>
            </main>
        </sidebar-layout>
    </div>
</template>

<script>
export default {
    data() {
        const route = path =>
            `/servers/${this.$route.params.server}${path ? '/' : ''}${path}`

        return {
            nav: [
                {
                    label: 'Sites',
                    value: 'sites',
                    to: route('')
                },
                {
                    label: 'Scheduler',
                    value: 'scheduler',
                    to: route('scheduler')
                },
                {
                    label: 'Network',
                    value: 'network',
                    to: route('network')
                },
                {
                    label: 'Meta',
                    value: 'meta',
                    to: route('meta')
                }
            ],
            loading: true,
            deletingServer: false
        }
    },
    mounted() {
        if (this.$root.servers[this.$route.params.server]) {
            this.loading = false
            this.addDatabasesToNav()
            this.$emit('mounted')

            return
        }

        axios
            .get(`/api/servers/${this.$route.params.server}`)
            .then(({ data }) => {
                this.$root.servers = {
                    ...this.$root.servers,
                    [this.$route.params.server]: data
                }

                this.addDatabasesToNav()
                this.$emit('mounted')

                this.loading = false
            })
            .catch(() => {
                this.$router.push('/dashboard')
            })
    },
    computed: {
        active() {
            return this.$route.path
        },
        server() {
            return this.$root.servers[this.$route.params.server] || {}
        }
    },
    methods: {
        deleteServer() {
            this.deletingServer = true
            axios.delete(`/api/servers/${this.server.id}`).then(() => {
                this.$router.push('/dashboard')
            })
        },
        addDatabasesToNav() {
            this.nav = [
                ...this.nav,
                ...this.server.databases.map(db => ({
                    label: db,
                    value: db,
                    to: `/servers/${this.server.id}/databases/${db}`
                }))
            ]

            if (this.server.type === 'load_balancer') {
                this.nav = this.nav.filter(item => !['scheduler'].includes(item.value))
            }
        }
    }
}
</script>
