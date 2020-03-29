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
                `Are you sure you want to delete your database ${deleteDatabase &&
                    deleteDatabase.name} ? All data will be lost, with all users.`
            "
        />
        <card title="Add Mongodb Database" class="mb-6">
            <form @submit.prevent="addDatabase">
                <text-input
                    name="name"
                    label="Database name"
                    v-model="form.name"
                    :errors="errors.name"
                    help="When you add a database, you can then add users to that database for authentication."
                />

                <v-button
                    type="submit"
                    class="mt-4"
                    label="Add database"
                    :loading="addingDatabase"
                />
            </form>
        </card>

        <card
            title="Mongodb databases"
            class="mb-6"
            :table="true"
            emptyTableMessage="No databases have been added yet."
            :rowsCount="databases.length"
        >
            <v-table :headers="databasesTable.headers" :rows="databases">
                <template slot="row" slot-scope="{ row, header }">
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

        <card title="Add Mongodb users" class="mb-6">
            <form v-if="databases.length > 0" @submit.prevent="addDatabaseUser">
                <select-input
                    name="database"
                    label="Database"
                    :options="databases"
                    v-model="addUserForm.database"
                    help="Select the database this user would be stored in. This user would also be able to access the selected database."
                />

                <div class="mt-4">
                    <text-input
                        name="name"
                        label="Name"
                        v-model="addUserForm.name"
                        :errors="databaseUserErrors.name"
                        help="This would be the username for the database user."
                    />
                </div>

                <div class="mt-4">
                    <text-input
                        name="password"
                        label="Password"
                        v-model="addUserForm.password"
                        :errors="databaseUserErrors.password"
                        help="This would be the password for the database user. The password and username would be required to authenticate as this user."
                    />
                </div>

                <div class="mt-6">
                    <checkbox
                        name="readonly"
                        label="Readonly"
                        :checked="addUserForm.readonly"
                        @input="addUserForm = {...addUserForm, readonly: !addUserForm.readonly}"
                        help="This user would have READ and WRITE access to the selected database. Check this if you want to grant only read access."
                    />
                </div>

                <v-button
                    label="Add database user"
                    class="mt-6"
                    type='submit'
                    :loading="addingDatabaseUser"
                    @click="addDatabaseUser"
                />
            </form>
            <info v-else>
                To add MongoDB users, create a database.
            </info>
        </card>

        <card
            title="Mongodb users"
            :table="true"
            :rowsCount="databaseUsers.length"
            emptyTableMessage="No database users yet."
        >
            <v-table
                :headers="databasesUsersTable.headers"
                :rows="databaseUsers"
            >
                <template slot="row" slot-scope="{ row, header }">
                    <table-status
                        v-if="header.value === 'status'"
                        :status="row.status"
                    />

                    <delete-button v-if="header.value === 'actions'" />

                    <span
                        v-if="['name', 'database'].includes(header.value)
                        "
                    >
                        {{ row[header.value] }}
                    </span>

                    <span class="text-xs text-gray-700" v-if="header.value === 'permission'">
                        {{ row.permission }}
                    </span>
                </template>
            </v-table>
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
                headers: [
                    {
                        label: 'Name',
                        value: 'name'
                    },
                    {
                        label: 'Status',
                        value: 'status'
                    },
                    {
                        label: '',
                        value: 'actions'
                    }
                ]
            },
            databasesUsersTable: {
                headers: [
                    {
                        label: 'Name',
                        value: 'name'
                    },
                    {
                        label: 'Database',
                        value: 'database'
                    },
                    {
                        label: 'Permission',
                        value: 'permission'
                    },
                    {
                        label: 'Status',
                        value: 'status'
                    },
                    {
                        label: '',
                        value: 'actions'
                    }
                ]
            },
            addUserForm: {
                database: '',
                name: '',
                password: '',
                readonly: false
            },
            deletingDatabase: false,
            deleteDatabase: null,
            addingDatabaseUser: false,
            databaseUserErrors: {}
        }
    },
    computed: {
        server() {
            return this.$root.servers[this.$route.params.server] || {}
        },
        databases() {
            return this.server.database_instances
                .filter(db => db.type === 'mongodb')
                .map(db => ({
                    ...db,
                    label: db.name,
                    value: db.id,
                }))
        },
        databaseUsers() {
            return this.server.database_users_instances
                .filter(db => db.type === 'mongodb')
                .map(db => ({
                    ...db,
                    label: db.name,
                    value: db.id,
                    database: db.databases[0] ? db.databases[0].name : null,
                    permission: db.read_only ? 'READ' : 'READ/WRITE'
                }))
        }
    },
    methods: {
        deleteDb() {
            this.deletingDatabase = true
            axios
                .delete(
                    `/api/servers/${this.server.id}/databases/${this.deleteDatabase.id}/mongodb/delete-databases`
                )
                .then(({ data: server }) => {
                    this.$root.servers = {
                        ...this.$root.servers,
                        [server.id]: server
                    }

                    this.$root.flashMessage(
                        'Database has been queued for deleting.'
                    )
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

            axios
                .post(
                    `/api/servers/${this.server.id}/databases/mongodb/add`,
                    this.form
                )
                .then(({ data: server }) => {
                    this.$root.servers = {
                        ...this.$root.servers,
                        [server.id]: server
                    }

                    this.form = {
                        name: ''
                    }

                    this.errors = {}

                    this.$root.flashMessage(
                        'Database has been added successfully.'
                    )
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.errors = response.data.errors
                    } else {
                        this.$root.flashMessage(
                            'Failed to add database to server.',
                            'error'
                        )
                    }
                })
                .finally(() => {
                    this.addingDatabase = false
                })
        },
        addDatabaseUser() {
            this.addingDatabaseUser = true

            axios
                .post(
                    `/api/servers/${this.server.id}/databases/${this.addUserForm.database}/mongodb/add-users`,
                    this.addUserForm
                )
                .then(({ data: server }) => {
                    this.$root.servers = {
                        ...this.$root.servers,
                        [server.id]: server
                    }

                    this.addUserForm = {
                        database: '',
                        name: '',
                        password: '',
                        readonly: false
                    }

                    this.errors = {}

                    this.$root.flashMessage('Database user has been queued.')
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.databaseUserErrors = response.data.errors
                    } else {
                        this.$root.flashMessage(
                            'Failed to add database user to server.',
                            'error'
                        )
                    }
                })
                .finally(() => {
                    this.addingDatabaseUser = false
                })
        }
    }
}
</script>
