<template>
    <div>
        <sidebar-layout
            :nav="nav"
            :active="active"
            :showNav="site.status === 'active'"
        >
            <template slot="header">
                <div
                    class="w-full flex flex-wrap justify-between mb-5"
                    v-if="!loading"
                >
                    <div class="w-full md:w-1/5">
                        <h3 class="text-lg text-gray-800">Site details</h3>
                    </div>
                    <div class="w-full md:w-4/5 flex flex-wrap md:justify-end">
                        <div
                            class="w-full md:w-auto flex items-center flex-wrap md:pl-6 uppercase text-gray-700 tracking-wide"
                        >
                            <span
                                class="w-full mt-2 md:mt-0 md:w-auto text-sha-green-500 font-medium text-sm hover:text-sha-green-600 transition duration-50 ease-in-out"
                            >
                                <router-link :to="`/servers/${server.id}`">{{
                                    server.name
                                }}</router-link>
                            </span>
                            <span
                                class="w-full mt-2 md:mt-0 md:w-auto text-gray-700 hover:text-gray-800 font-medium text-sm md:ml-4 transition duration-50 ease-in-out"
                            >
                                <a
                                    :href="`http://${site.name}`"
                                    target="_blank"
                                    >{{ site.name }}</a
                                >
                            </span>

                            <span
                                class="w-full mt-2 md:mt-0 md:w-auto md:ml-5"
                                >{{ server.ip_address }}</span
                            >
                            <span
                                class="w-full mt-2 md:mt-0 md:w-auto md:ml-2"
                                v-if="server.private_ip_address"
                                >({{ server.private_ip_address }})</span
                            >
                            <table-status
                                class="mt-2 md:mt-0 md:ml-4"
                                :status="site.status"
                            />
                        </div>
                    </div>
                </div>
            </template>
            <div v-if="!loading && site && site.status === 'active'">
                <template slot="header">
                    <!-- <div class="h-12 w-full mb-5"></div> -->
                    <slot name="header" />
                </template>
                <slot name="content" />
            </div>
            <main v-else class="px-3">
                <div class="max-w-5xl mx-auto py-6 sm:px-6 lg:px-8">
                    <div
                        class="w-full flex items-center justify-center"
                        v-if="loading"
                    >
                        <p class="w-full py-4 flex justify-center">
                            <spinner class="w-10 h-10" />
                        </p>
                    </div>

                    <div
                        class="w-full bg-white rounded shadow p-4 md:p-6"
                        v-if="!loading && site.status === 'installing'"
                    >
                        <p class="text-center text-gray-700">
                            Your site is still being created. we are setting up
                            nginx configuration files, PM2 ecosystem files, and
                            log files for this site. This usually takes about 5
                            seconds.
                        </p>

                        <p class="w-full py-4 flex justify-center">
                            <spinner class="w-10 h-10" />
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
            `/servers/${this.$route.params.server}/sites/${
                this.$route.params.site
            }${path ? '/' : ''}${path}`

        return {
            nav: [
                {
                    label: 'App',
                    value: 'sites',
                    to: route('')
                },
                {
                    label: 'Files',
                    value: 'files',
                    to: route('files')
                },
                {
                    label: 'SSL',
                    value: 'ssl',
                    to: route('ssl')
                },
                {
                    label: 'Logs',
                    value: 'logs',
                    to: route('logs')
                },
                {
                    label: 'Settings',
                    value: 'settings',
                    to: route('settings')
                }
            ],
            loading: true
        }
    },
    mounted() {
        let site = this.$root.sites[this.$route.params.site]
        if (site) {
            this.loading = false

            if (
                (site && site.type !== 'nodejs') ||
                (this.server && this.server.type === 'load_balancer')
            ) {
                this.nav = this.nav.filter(
                    item => !['logs'].includes(item.value)
                )
            }

            this.$emit('mounted')
            this.subscribeToServerBroadcast(this.server)

            return
        }

        axios
            .get(
                `/api/servers/${this.$route.params.server}/sites/${this.$route.params.site}`
            )
            .then(({ data: site }) => {
                this.$root.servers = {
                    ...this.$root.servers,
                    [this.$route.params.server]: site.server
                }

                this.$root.sites = {
                    ...this.$root.sites,
                    [site.id]: site
                }

                if (
                    site.type !== 'nodejs' ||
                    site.server.type === 'load_balancer'
                ) {
                    this.nav = this.nav.filter(
                        item => !['logs'].includes(item.value)
                    )
                }

                this.$emit('mounted')
                this.subscribeToServerBroadcast(this.server)

                this.loading = false
            })
            .catch(() => {
                this.$router.push(`/servers/${this.$route.params.server}`)
            })
    },
    computed: {
        active() {
            return this.$route.path
        },
        server() {
            return this.$root.servers[this.$route.params.server] || {}
        },
        site() {
            return this.$root.sites[this.$route.params.site] || {}
        }
    }
}
</script>
