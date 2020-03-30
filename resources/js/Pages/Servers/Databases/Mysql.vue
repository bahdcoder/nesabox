<template>
    <server-layout>
        <template slot="content">
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
            <confirm-modal
                :confirming="deletingDatabaseUser"
                @confirm="deleteDbUser"
                :open="!!deleteUser"
                @close="closeConfirmDeleteDatabaseUser"
                confirmHeading="Delete database user"
                :confirmText="
                    `Are you sure you want to delete your database user ${deleteUser &&
                        deleteUser.name} ? This user would lose access to this database.`
                "
            />
            <card :title="`Add ${dbType} Database`" class="mb-6">
                <form @submit.prevent="addDatabase">
                    <text-input
                        name="name"
                        label="Database name"
                        v-model="form.name"
                        :errors="errors.name"
                    />

                    <text-input
                        name="user"
                        class="mt-4"
                        label="User name"
                        v-model="form.user"
                        :errors="errors.user"
                        help="You can optionally add a user that will have access to this database. To do so, provide a user name, and provide a password."
                    />

                    <text-input
                        class="mt-4"
                        name="password"
                        label="User password"
                        v-model="form.password"
                        :errors="errors.password"
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
                class="mb-6"
                :table="true"
                :title="`${dbType} databases`"
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

            <card :title="`Add ${dbType} users`" class="mb-6">
                <form
                    v-if="databases.length > 0"
                    @submit.prevent="addDatabaseUser"
                >
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
                        <label
                            for=""
                            class="block text-sm font-medium leading-5 text-gray-700"
                            >Databases</label
                        >
                        <small class="text-gray-600"
                            >Select all the databases users can access.</small
                        >
                        <checkbox
                            class="mt-4"
                            :key="database.id"
                            :name="database.id"
                            :label="database.name"
                            v-for="database in databases"
                            @input="selectDatabase($event, database)"
                            :checked="
                                addUserForm.databases.includes(database.id)
                            "
                        />
                    </div>

                    <v-button
                        label="Add database user"
                        class="mt-6"
                        type="submit"
                        :loading="addingDatabaseUser"
                    />
                </form>
                <info v-else>
                    To add {{ dbType }} users, create a database.
                </info>
            </card>

            <card
                :table="true"
                :title="`${dbType} users`"
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

                        <delete-button
                            v-if="header.value === 'actions'"
                            @click="setDeletingDatabaseUser(row)"
                        />

                        <span
                            v-if="['name', 'databases'].includes(header.value)"
                        >
                            {{ row[header.value] }}
                        </span>
                    </template>
                </v-table>
            </card>
        </template>
    </server-layout>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: '',
                user: '',
                password: ''
            },
            deleteUser: null,
            addingDatabase: false,
            deletingDatabaseUser: false,
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
                        label: 'Databases',
                        value: 'databases'
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
                databases: []
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
            if (!this.server || !this.server.id) {
                return []
            }
            return this.server.database_instances
                .filter(db => db.type === this.dbType)
                .map(db => ({
                    ...db,
                    label: db.name,
                    value: db.id
                }))
        },
        dbType() {
            if (!this.server || !this.server.id) return

            const sqlDatabase = this.server.databases.filter(
                _ => !['postgresql', 'mongodb'].includes(_)
            )

            return sqlDatabase[0]
        },
        databaseUsers() {
            if (!this.server || !this.server.id) {
                return []
            }
            return this.server.database_users_instances
                .filter(
                    db => db.type === this.dbType && db.databases.length !== 0
                )
                .map(db => ({
                    ...db,
                    label: db.name,
                    value: db.id,
                    databases: db.databases
                        .reduce((acc, db) => `${acc},${db.name}`, '')
                        .substr(1)
                }))
        }
    },
    methods: {
        selectDatabase(checked, database) {
            if (checked) {
                this.addUserForm = {
                    ...this.addUserForm,
                    databases: [...this.addUserForm.databases, database.id]
                }
            } else {
                this.addUserForm = {
                    ...this.addUserForm,
                    databases: this.addUserForm.databases.filter(
                        db => db !== database.id
                    )
                }
            }
        },
        deleteDbUser() {
            this.deletingDatabaseUser = true

            axios
                .delete(
                    `/api/servers/${this.server.id}/database-users/${this.deleteUser.id}`
                )
                .then(({ data: server }) => {
                    this.$root.servers = {
                        ...this.$root.servers,
                        [server.id]: server
                    }

                    this.$root.flashMessage(
                        'Database user has been queued for deleting.'
                    )
                })
                .catch(({ response }) => {
                    this.$root.flashMessage(
                        response.data.message ||
                            'Failed to delete database user.',
                        'error'
                    )
                })
                .finally(() => {
                    this.deletingDatabaseUser = false
                    this.deleteUser = null
                })
        },
        closeConfirmDeleteDatabaseUser() {
            this.deleteUser = null
            this.deletingDatabaseUser = false
        },
        deleteDb() {
            this.deletingDatabase = true
            axios
                .delete(
                    `/api/servers/${this.server.id}/databases/${this.deleteDatabase.id}`
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
                .catch(({ response }) => {
                    this.$root.flashMessage(
                        response.data.message || 'Failed to delete database.',
                        'error'
                    )
                })
                .finally(() => {
                    this.deletingDatabase = false
                    this.deleteDatabase = null
                })
        },
        setDeletingDatabase(database) {
            this.deleteDatabase = database
        },
        setDeletingDatabaseUser(user) {
            this.deleteUser = user
        },
        closeConfirmDeleteDatabase() {
            this.deleteDatabase = null
            this.deletingDatabase = false
        },
        addDatabase() {
            this.addingDatabase = true

            axios
                .post(`/api/servers/${this.server.id}/databases`, {
                    ...this.form,
                    type: this.dbType
                })
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
                        'Database creation has been queued.'
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
                .post(`/api/servers/${this.server.id}/database-users`, {
                    ...this.addUserForm,
                    type: this.dbType
                })
                .then(({ data: server }) => {
                    this.$root.servers = {
                        ...this.$root.servers,
                        [server.id]: server
                    }

                    this.addUserForm = {
                        name: '',
                        password: '',
                        databases: []
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
