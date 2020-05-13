export default {
    methods: {
        subscribeToServerBroadcast(server) {
            Echo.private(`App.Server.${server.id}`).notification(
                notification => {
                    if (
                        notification.type ===
                        'App\\Notifications\\Sites\\SiteUpdated'
                    ) {
                        this.$root.fetchSite(
                            notification.server,
                            notification.site
                        )
                    }

                    if (
                        notification.type ===
                        'App\\Notifications\\Servers\\ServerIsReady'
                    ) {
                        this.$root.fetchServer(notification.server)
                    }

                    if (
                        notification.type ===
                        'App\\Notifications\\Servers\\Alert'
                    ) {
                        const serverId = notification.data.server_id

                        this.$root.updateServer(serverId, {
                            ...this.$root.servers[serverId],
                            unread_notifications: [
                                ...this.$root.servers[serverId]
                                    .unread_notifications,
                                notification.data
                            ]
                        })
                    }
                }
            )
        }
    }
}
