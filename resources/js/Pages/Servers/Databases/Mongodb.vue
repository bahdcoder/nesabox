<template>
    <div>
        <flash />
        <confirm-modal
                :confirming="deletingDatabase"
                @confirm="deleteDb"
                :open="!!deleteDatabase"
                @close="closeConfirmDeleteDatabase"
                confirmHeading="Delete database"
                :confirmText="
                    `Are you sure you want to delete your database ${deleteDatabase && deleteDatabase.name} ? All data will be lost, with all users.`
                "
            />
        <card title='Add Mongodb Database' class="mb-6">
            <form @submit.prevent="addDatabase">
                <text-input
                    name='name'
                    label='Database name'
                    v-model="form.name"
                    :errors="errors.name"
                    help='When you add a database, you can then add users to that database for authentication.'
                />

                <v-button
                    type='submit'
                    class="mt-4"
                    label='Add database'
                    :loading="addingDatabase"
                /> 
            </form>
        </card>

        <card title='Mongodb databases' class="mb-6" :table="true" emptyTableMessage='No databases have been added yet.' :rowsCount="databases.length">
            <v-table :headers="databasesTable.headers" :rows="databases">
                <template slot='row' slot-scope='{ row, header }'>
                    <table-status
                        v-if="header.value === 'status'"
                        :status="row.status"
                    />

                    <delete-button 
                        @click="setDeletingDatabase(row)"
                        v-if="header.value === 'actions'"
                    />


                    <span v-if="header.value === 'name'">
                        {{ row.name }}
                    </span>
                </template>
            </v-table>
        </card>

        <card title='Add MongoDB users'>
        </card>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    name: ''
                },
                addingDatabase: false,
                errors: {},
                databasesTable: {
                    headers: [{
                        label: 'Name',
                        value: 'name'
                    }, {
                        label: 'Status',
                        value: 'status'
                    }, {
                        label: '',
                        value: 'actions'
                    }]
                },
                deletingDatabase: false,
                deleteDatabase: null
            }
        },
        computed: {
            server() {
                return this.$root.servers[this.$route.params.server] || {}
            },
            databases() {
                return this.server.database_instances.filter(db => db.type === 'mongodb')
            }
        },
        methods: {
            deleteDb() {
                this.deletingDatabase = true
                axios.delete(`/api/servers/${this.server.id}/databases/${this.deleteDatabase.id}/mongodb/delete-databases`)
                    .then(({ data: server }) => {
                        this.$root.servers = {
                            ...this.$root.servers,
                            [server.id]: server
                        }

                        this.$root.flashMessage('Database has been queued for deleting.')
                    })
                    .catch(() => {
                        this.$root.flashMessage('Failed to delete database.')
                    })
                    .finally(() => {
                        this.deletingDatabase = false
                        this.deleteDatabase = null
                    })
            },
            setDeletingDatabase(database) {
                this.deleteDatabase = database
            },
            closeConfirmDeleteDatabase() {
                this.deleteDatabase = null
                this.deletingDatabase = false
            },
            addDatabase() {
                this.addingDatabase = true

                axios.post(`/api/servers/${this.server.id}/databases/mongodb/add`, this.form)
                    .then(({ data: server }) => {
                        this.$root.servers = {
                            ...this.$root.servers,
                            [server.id]: server
                        }

                        this.form = {
                            name: ''
                        }

                        this.errors = {}

                        this.$root.flashMessage('Database has been added successfully.')
                    })
                    .catch(({ response }) => {
                        if (response.status === 422) {
                            this.errors = response.data.errors
                        } else {
                            this.$root.flashMessage('Failed to add database to server.', 'error')
                        }
                    })
                    .finally(() => {
                        this.addingDatabase = false
                    })
            }
        }
    }
</script>
