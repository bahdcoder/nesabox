<template>
    <account-layout>
        <template slot="content">
            <flash />
            <card
                v-if="subscription.plan !== 'business'"
                title="Upgrade your plan to add teams"
                class="mb-5"
            >
                <v-button
                    label="Upgrade to Business"
                    component="router-link"
                    to="/account/subscription"
                />
            </card>

            <div v-else>
                <card title="Add new team" class="mb-5">
                    <form @submit.prevent="submit">
                        <text-input
                            name="name"
                            label="Name"
                            v-model="form.name"
                            placeholder="Artisans"
                            :errors="formErrors.name"
                            help="Provide a name for your team."
                        />

                        <v-button
                            type="submit"
                            class="mt-5"
                            label="Add team"
                            :loading="submitting"
                        />
                    </form>
                </card>

                <card
                    :table="true"
                    title="Teams"
                    class="mb-5"
                    :rowsCount="teams.length"
                    emptyTableMessage="No teams yet."
                >
                    <v-table :headers="table.headers" :rows="teams">
                        <template slot="row" slot-scope="{ row, header }">
                            <span
                                v-if="header.value === 'name'"
                                class="text-gray-800"
                                >{{ row[header.value] }}</span
                            >

                            <div
                                class="flex items-center"
                                v-if="header.value === 'actions'"
                            >
                                <router-link
                                    class="mr-5 text-sha-green-500"
                                    style="text-decoration: underline;"
                                    :to="{
                                        name: 'account.team.team-id',
                                        params: {
                                            id: row.id
                                        }
                                    }"
                                >
                                    {{ row.invites.length }} Member{{
                                        row.invites.length === 1 ? '' : 's'
                                    }}
                                </router-link>

                                <button
                                    type="button"
                                    class="border-2 border-blue-500 p-1 rounded hover:bg-blue-100 shadow mr-3"
                                >
                                    <svg
                                        width="20"
                                        height="20"
                                        class="text-blue-500"
                                        fill="none"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"
                                        />
                                    </svg>
                                </button>

                                <delete-button />
                            </div>
                        </template>
                    </v-table>
                </card>
            </div>

            <card
                title="Team memberships"
                :table="true"
                :rowsCount="memberships.length"
                emptyTableMessage="You have not been added to any teams."
            >
                <v-table
                    :headers="membershipsTable.headers"
                    :rows="memberships"
                >
                    <template slot="row" slot-scope="{ row, header }">
                        <span
                            v-if="header.value === 'team_name'"
                            class="text-gray-800"
                            >{{ row[header.value] }}</span
                        >

                        <table-status
                            v-if="header.value === 'status'"
                            :status="row.status"
                        />

                        <div
                            class="flex items-center"
                            v-if="
                                header.value === 'actions' &&
                                    row.status !== 'active'
                            "
                        >
                            <button
                                type="button"
                                @click="acceptInvite(row)"
                                class="border-2 border-sha-green-500 p-1 rounded hover:bg-sha-green-100 shadow mr-3"
                            >
                                <svg
                                    class="text-sha-green-500"
                                    width="20"
                                    height="20"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>
                            </button>

                            <delete-button @click="rejectInvite(row)" />
                        </div>
                    </template>
                </v-table>
            </card>
        </template>
    </account-layout>
</template>

<script>
export default {
    data() {
        return {
            table: {
                headers: [
                    {
                        label: 'Name',
                        value: 'name'
                    },
                    {
                        label: '',
                        value: 'actions'
                    }
                ]
            },
            membershipsTable: {
                headers: [
                    {
                        label: 'Team name',
                        value: 'team_name'
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
            form: {
                name: ''
            }
        }
    },
    computed: {
        subscription() {
            return this.$root.auth.subscription
        },
        teams() {
            return this.$root.auth.teams
        },
        memberships() {
            return this.$root.auth.team_memberships.map(membership => ({
                ...membership,
                team_name: membership.team.name
            }))
        }
    },
    mounted() {
        this.initializeForm('/api/teams')
    },
    methods: {
        submit() {
            this.submitForm()
                .then(user => {
                    this.$root.auth = user

                    this.form = {
                        name: ''
                    }

                    this.$root.flashMessage('Team has been added.')
                })
                .catch(({ response }) => {
                    this.$root.flashMessage(
                        response.data.message || 'Failed creating team.',
                        'error'
                    )
                })
        },
        acceptInvite(invite) {
            this.updateInvite(invite, 'accepted')
        },
        rejectInvite(invite) {
            this.updateInvite(invite, 'rejected')
        },
        updateInvite(invite, status) {
            axios
                .patch(`/api/invites/${invite.id}/${status}`)
                .then(({ data: user }) => {
                    this.$root.auth = user
                })
                .catch(error => {
                    this.$root.flashMessage(
                        error.response.data.message ||
                            'Failed to update invite.'
                    )
                })
        }
    }
}
</script>
