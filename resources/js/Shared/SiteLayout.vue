<template>
    <div>
        <sidebar-layout
            :nav="nav"
            :active="active"
            :showNav="site.status === 'active'"
        >
            <div v-if="site.status === 'active' && !loading">
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
                >
                    <p class="w-full py-4 flex justify-center">
                        <spinner class="w-10 h-10" />
                    </p>
                </div>

                <div
                    class="w-full bg-white rounded shadow p-4 md:p-6"
                    v-if="!loading && server.status === 'new'"
                >
                    <p class="text-center text-gray-700">
                        Your site is still being created. we are setting up
                        nginx configuration files, PM2 ecosystem files, and log
                        files for this site. This usually takes about 5 seconds.
                    </p>

                    <p class="w-full py-4 flex justify-center">
                        <spinner class="w-10 h-10" />
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
                    label: 'Settings',
                    value: 'settings',
                    to: route('settings')
                }
            ],
            loading: true
        }
    },
    mounted() {
        if (this.$root.sites[this.$route.params.site]) {
            this.loading = false

            this.$emit('mounted')

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

                this.$emit('mounted')

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
