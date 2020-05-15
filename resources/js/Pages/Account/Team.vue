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
                <card title="Add new team member" class="mb-5">
                    <form @submit.prevent="submit">
                        <text-input
                            name="email"
                            label="Email"
                            v-model="form.email"
                            placeholder="member@email.com"
                            :errors="formErrors.name"
                            help="Provide an email to invite to team."
                        />

                        <v-button
                            type="submit"
                            class="mt-5"
                            label="Add team member"
                            :loading="submitting"
                        />
                    </form>
                </card>

                <card
                    :table="true"
                    title="Team Members"
                    :rowsCount="teamInvites.length"
                    emptyTableMessage="No team invites sent yet."
                >
                    <v-table :headers="table.headers" :rows="teamInvites">
                        <template slot="row" slot-scope="{ row, header }">
                            <span
                                v-if="header.value === 'email'"
                                class="text-gray-800"
                            >{{ row[header.value] }}</span>
                            <table-status v-if="header.value === 'status'" :status="row.status" />

                            <div v-if="header.value === 'actions'">
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
                        label: 'Email',
                        value: 'email'
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
                email: ''
            }
        }
    },
    computed: {
        subscription() {
            return this.$root.auth.subscription
        },
        teamInvites() {
            const team = this.$root.auth.teams.find(
                team => team.id === this.$route.params.id
            )
            return team.invites
        }
    },
    mounted() {
        this.initializeForm(`/api/teams/${this.$route.params.id}/invites`)
    },
    methods: {
        submit() {
            this.submitForm()
                .then(user => {
                    this.$root.auth = user

                    this.form = {
                        email: ''
                    }

                    this.$root.flashMessage('Team invite has been sent.')
                })
                .catch(response => {
                    this.$root.flashMessage(
                        response.data.message || 'Failed sending team invite.',
                        'error'
                    )
                })
        }
    }
}
</script>
