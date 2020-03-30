<template>
    <server-layout>
        <template slot="content">
            <flash />
            <confirm-modal
                @confirm="deleteJob"
                :open="showDeleteModal"
                :confirming="deletingJob"
                @close="closeConfirmDeleteJob"
                confirmHeading="Delete scheduled job"
                :confirmText="
                    `Are you sure you want to delete the scheduled job ${selectedJob &&
                        selectedJob.command} ?`
                "
            />
            <modal :open="showLogsModal">
                <div class="p-6">
                    <div v-if="selectedJob">
                        <h2 class="text-lg mb-3 text-gray-800">
                            Job logs ({{ selectedJob && selectedJob.slug }})
                        </h2>
                        <v-codemirror :value='logs' />
                    </div>

                    <div
                        v-else
                        style="background :#2b3e50"
                        class="w-full h-12 flex justify-center items-center rounded"
                    >
                        <pulse />
                    </div>

                    <div class="w-full md:flex md:justify-end">
                        <v-trans-button @click="hideLogs" label='Close' class="mt-4" />
                    </div>
                </div>
            </modal>
            <card class="mb-6" title="New Scheduled Job">
                <form @submit.prevent="submit">
                    <text-input
                        name="command"
                        label="Command"
                        v-model="form.command"
                        :errors="formErrors.command"
                    />

                    <text-input
                        name="user"
                        label="User"
                        class="mt-5"
                        v-model="form.user"
                        :errors="formErrors.user"
                        help="This is the user the cron job would be executed with."
                    />

                    <div class="mt-4">
                        <v-radio
                            id="frequency"
                            label="Frequency"
                            :options="frequencies"
                            v-model="form.frequency"
                            :errors="formErrors.frequency"
                        />
                        <a target="_blank" href="https://crontab.guru/">
                            <info
                                class="mt-4"
                                v-if="form.frequency === 'custom'"
                            >
                                <div>
                                    You can generate a valid cron expression
                                    using
                                    <span class="ml-1 font-semibold"
                                        >this tool</span
                                    >
                                </div>
                            </info>
                        </a>

                        <div
                            v-if="form.frequency === 'custom'"
                            class="grid grid-cols-5 gap-4 mt-3"
                        >
                            <text-input
                                name="cron-1"
                                class="text-center"
                                v-model="form.cron1"
                            />
                            <text-input
                                name="cron-2"
                                class="text-center"
                                v-model="form.cron2"
                            />
                            <text-input
                                name="cron-3"
                                class="text-center"
                                v-model="form.cron3"
                            />
                            <text-input
                                name="cron-4"
                                class="text-center"
                                v-model="form.cron4"
                            />
                            <text-input
                                name="cron-5"
                                class="text-center"
                                v-model="form.cron5"
                            />
                        </div>
                    </div>

                    <v-button type='submit' label="Schedule Job" class="mt-5" :loading="submitting" />
                </form>
            </card>

            <card title='Scheduled jobs' :table="true" :rowsCount="jobs.length" emptyTableMessage='No scheduled jobs running on this server yet.'>
                <v-table :headers="tableHeaders" :rows="jobs">
                    <template slot='row' slot-scope='{ row, header }'>
                        <table-status
                            v-if="header.value === 'status'"
                            :status="row.status"
                        />

                        <delete-button
                            v-if="header.value === 'delete'"
                            @click="showConfirmDeleteModal(row)"
                        />

                        <button @click="showLogs(row)" class="border-2 border-gray-500 p-1 rounded hover:bg-gray-100 shadow" v-if="header.value === 'logs'">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="20" height="20"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </button>

                        <span class="text-gray-700 text-sm" v-if="['slug', 'user', 'cron', 'frequency'].includes(header.value)">
                            {{ row[header.value] }}
                        </span>

                        <code class="text-red-400" v-if="header.value === 'command'">
                            {{ row.command }}
                        </code>
                    </template>
                </v-table>
            </card>
        </template>
    </server-layout>
</template>

<script>
export default {
    mounted() {
        this.form = {
            command: '',
            user: 'nesa',
            cron1: '*',
            cron2: '*',
            cron3: '*',
            cron4: '*',
            cron5: '*',
            frequency: 'everyMinute'
        }

        this.initializeForm(`/api/servers/${this.$route.params.server}/cron-jobs`)
    },
    data() {
        return {
            logs: '',
            deletingJob: false,
            selectedJob: null,
            showLogsModal: false,
            showDeleteModal: false,
            tableHeaders: [{
                label: 'Cron',
                value: 'cron'
            }, {
                label: 'User',
                value: 'user'
            }, {
                label: 'Status',
                value: 'status'
            }, {
                label: 'Command',
                value: 'command'
            }, {
                label: '',
                value: 'logs'
            }, {
                label: '',
                value: 'delete'
            }],
            frequencies: [
                {
                    label: 'Every Minute',
                    value: 'everyMinute'
                },
                {
                    label: 'Every Five Minutes',
                    value: 'everyFiveMinutes'
                },
                {
                    label: 'Every Ten Minutes',
                    value: 'everyTenMinutes'
                },
                {
                    label: 'Hourly',
                    value: 'hourly'
                },
                {
                    label: 'Nightly',
                    value: 'daily'
                },
                {
                    label: 'Weekly',
                    value: 'weekly'
                },
                {
                    label: 'Monthly',
                    value: 'monthly'
                },
                {
                    label: 'Custom cron',
                    value: 'custom'
                }
            ]
        }
    },
    computed: {
        server() {
            return this.$root.servers[this.$route.params.server] || {}
        },
        jobs() {
            return this.server.jobs || []
        }
    },
    methods: {
        hideLogs() {
            this.selectedJob = null

            this.showLogsModal = false
        },
        showConfirmDeleteModal(job) {
            this.showDeleteModal = true

            this.selectedJob = job
        },
        showLogs(job) {
            this.showLogsModal = true

            axios.post(`/api/servers/${this.server.id}/cron-jobs/${job.id}/log`)
                .then(({ data: logs }) => {
                    this.logs = logs

                    this.selectedJob = job
                })
                .catch(({ response }) => {
                    this.showLogsModal = false

                    this.$root.flashMessage(response.data.message || 'Failed fetching logs for job.', 'error')
                })
        },
        submit() {
            if (this.form.frequency === 'custom') {
                this.form = {
                    ...this.form,
                    cron: `${this.form.cron1}${this.form.cron2}${this.form.cron3}${this.form.cron4}${this.form.cron5}`
                }
            }

            this.submitForm().then((server) => {
                this.$root.servers = {
                    ...this.$root.servers,
                    [server.id]: server
                }

                this.form = {
                    command: '',
                    user: '',
                    frequency: 'everyMinute'
                }

                this.$root.flashMessage('Schedule job added.')
            })
        },
        deleteJob() {
            this.deletingJob = true

            axios.delete(`/api/servers/${this.server.id}/cron-jobs/${this.selectedJob.id}`)
                .then(({ data: server }) => {
                    this.$root.servers = {
                        ...this.$root.servers,
                        [server.id]: server
                    }

                    this.$root.flashMessage('Cron job has been deleted.')
                })
                .catch(({ response }) => {
                    this.$root.flashMessage(response.data.message || 'Failed deleting job.', 'error')
                })
                .finally(() => {
                    this.deletingJob = false

                    this.selectedJob = null
                    this.showDeleteModal = false
                })
        },
        closeConfirmDeleteJob() {
            this.deletingJob = false
            this.showDeleteModal = false

            this.selectedJob = null
        }
    }
}
</script>
