<template>
    <site-layout>
        <template slot="content">
            <flash />
            <confirm-modal
                @confirm="deleteSite"
                :confirming="deleting"
                :open="deletingModalOpen"
                @close="deletingModalOpen = false"
                confirmHeading="Delete site"
                :confirmText="`Are you sure you want to delete this site ?`"
            />
            <card title="Delete site">
                <span class="block text-gray-700">
                    This will permanently remove all files related to this site
                    from your server.
                </span>
                <red-button
                    @click="deletingModalOpen = true"
                    label="Delete site"
                    class="mt-5"
                />
            </card>
        </template>
    </site-layout>
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
        site() {
            return this.$root.sites[this.$route.params.site] || {}
        },
        serverId() {
            return this.$route.params.server
        },
        siteId() {
            return this.$route.params.site
        }
    },
    methods: {
        deleteSite() {
            this.deleting = true

            axios
                .delete(`/api/servers/${this.serverId}/sites/${this.siteId}`)
                .then(({ data: server }) => {
                    this.$router.push(`/servers/${this.serverId}`)

                    this.$root.servers = {
                        ...this.$root.server,
                        [this.serverId]: server
                    }
                })
                .catch(() => {
                    this.$root.flashMessage(
                        'Cannot delete site at the moment. There might be a process running on this server.',
                        'error',
                        5000
                    )
                })
                .finally(() => {
                    this.deleting = false
                    this.deletingModalOpen = false
                })
        }
    }
}
</script>
