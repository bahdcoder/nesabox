<template>
    <account-layout>
        <template slot="content">
            <flash />
            <card v-if="subscription.plan !== 'business'" title="Upgrade your plan to add teams">
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
                    :rowsCount="teams.length"
                    emptyTableMessage="No teams yet."
                >
                    <v-table :headers="table.headers" :rows="teams">
                        <template slot="row" slot-scope="{ row, header }">
                            <span
                                v-if="header.value === 'name'"
                                class="text-gray-800"
                            >{{ row[header.value] }}</span>

                            <div v-if="header.value === 'actions'">
                                <router-link
                                    tag="button"
                                    class="border-2 border-blue-500 p-1 rounded hover:bg-blue-100 shadow mr-3"
                                    :to="{
                                        name: 'account.team.team-id',
                                        params: {
                                            id: row.id
                                        },
                                    }"
                                >
                                    <svg
                                        class="text-blue-500"
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
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                        />
                                    </svg>
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
        }
    }
}
</script>
