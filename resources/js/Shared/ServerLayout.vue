<template>
    <div>
        <sidebar-layout
            :showNav="server.status === 'active'"
            :nav="nav"
            :active="active"
        >
            <div v-if="server.status === 'active' && !loading">
                <template slot="header">
                    <!-- <div class="h-12 w-full mb-5"></div> -->
                    <slot name="header" />
                </template>
                <slot name="content" />
            </div>
        </sidebar-layout>

        <main class="px-3">
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
        }
    }
}
</script>
