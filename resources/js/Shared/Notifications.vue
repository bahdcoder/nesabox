<template>
    <div v-if="notifications.length > 0" class="mb-5">
        <card
            :table="true"
            title="Server alerts"
            :rowsCount="notifications.length"
        >
            <v-table :rows="notifications" :headers="headers">
                <template slot="row" slot-scope="{ row, header }">
                    <span
                        class="text-gray-800 text-sm"
                        v-if="header.value === 'name'"
                    >
                        {{ row.name }}
                    </span>

                    <span
                        v-if="header.value !== 'actions'"
                        class="text-gray-800 text-sm capitalize"
                    >
                        {{ row[header.value] }}
                    </span>

                    <div class="flex " v-if="header.value === 'actions'">
                        <button
                            @click="showLogs(row)"
                            class="border-2 border-gray-500 p-1 rounded hover:bg-gray-100 shadow mr-6"
                        >
                            <svg
                                fill="none"
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                width="20"
                                height="20"
                            >
                                <path
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                ></path>
                            </svg>
                        </button>
                        <delete-button @click="deleteNotification(row)" />
                    </div>
                </template>
            </v-table>
        </card>

        <modal :open="showLogsModal">
            <div v-if="selectedAlert" class="p-6">
                <div>
                    <h2 class="text-lg mb-3 text-gray-800">
                        Server alert logs
                    </h2>

                    <info class="my-5">
                        An error occurred when we tried to execute an action on
                        your server. Sometimes this means we couldn't SSH into
                        your server, or there was a runtime error while
                        executing a command. If there is an error output, please
                        review it below. If you can't resolve this error from
                        your nesabox dashboard, please kindly SSH into your
                        server and manually resolve it, or contact us if you
                        need any assistance.
                    </info>

                    <v-codemirror :value="selectedAlert.output" />
                </div>

                <div class="w-full md:flex md:justify-end">
                    <v-trans-button
                        label="Close"
                        class="mt-4"
                        @click="hideLogs"
                    />
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
export default {
    props: {
        notifications: {
            type: Array,
            required: false,
            default: () => []
        }
    },
    data() {
        return {
            headers: [
                {
                    label: 'Issued',
                    value: 'created_at'
                },
                {
                    label: 'Message',
                    value: 'message'
                },
                {
                    label: '',
                    value: 'actions'
                }
            ],
            showLogsModal: false,
            selectedAlert: null
        }
    },
    methods: {
        showLogs(alert) {
            this.selectedAlert = alert

            this.showLogsModal = true
        },
        hideLogs() {
            this.selectedAlert = null

            this.showLogsModal = false
        },
        deleteNotification(notification) {
            this.$root.updateServer(notification.server_id, {
                ...this.$root.servers[notification.server_id],
                unread_notifications: this.$root.servers[
                    notification.server_id
                ].unread_notifications.filter(_ => _.id !== notification.id)
            })

            axios.delete(`/api/notifications/${notification.id}`)
        }
    }
}
</script>
