<template>
    <server-layout>
        <template slot="content">
            <card title="Databases"></card>
        </template>
    </server-layout>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            server: {}
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
