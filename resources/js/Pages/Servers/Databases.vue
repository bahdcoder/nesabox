<template>
    <server-layout>
        <template slot="content">
            <component :is="$route.params.database" />
        </template>
    </server-layout>
</template>

<script>
    import Mysql from '@/Pages/Servers/Databases/Mysql'
    import Mongodb from '@/Pages/Servers/Databases/Mongodb'

    export default {
        components: {
            mysql8: Mysql,
            mysql: Mysql,
            Mongodb
        },
        data() {
            return {}
        },
        computed: {
            server() {
                return this.$root.servers[this.$route.params.server] || {}
            }
        },
        methods: {
            mounted(server) {
                this.loading = false
                this.server = server
            }
        }
    }
</script>
