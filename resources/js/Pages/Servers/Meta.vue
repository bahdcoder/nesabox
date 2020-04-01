<template>
    <server-layout>
        <template slot="content">
            <flash />
            <confirm-modal
                @confirm="deleteServer"
                :confirming="deleting"
                :open="deletingModalOpen"
                @close="deletingModalOpen = false"
                confirmHeading="Delete server"
                :confirmText="
                    `Are you sure you want to delete your server ${server.name}?`
                "
            />
            <card title="Delete server">
                <info>
                    This will permanently delete this server from our records.
                    <span v-if="server.provider !== 'custom'"
                        >We won't delete this server from your
                        {{ server.provider }} account.</span
                    >
                </info>
                <red-button
                    @click="deletingModalOpen = true"
                    label="Delete server"
                    class="mt-5"
                />
            </card>
        </template>
    </server-layout>
</template>

<script>
export default {
    data() {
        return {
            deleting: false,
            deletingModalOpen: false
        }
    },
    computed: {
        server() {
            return this.$root.servers[this.$route.params.server] || {}
        }
    },
    methods: {
        deleteServer() {
            this.deleting = true

            axios
                .delete(`/api/servers/${this.server.id}`)
                .then(() => {
                    this.$router.push(`/dashboard`)
                })
                .catch(() => {
                    this.$root.flashMessage('Failed deleting server.', 'error')
                })
                .finally(() => {
                    this.deleting = false
                    this.deletingModalOpen = false
                })
        }
    }
}
</script>
