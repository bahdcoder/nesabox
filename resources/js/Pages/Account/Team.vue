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
                <card
                    title="Invite new team member"
                    class="mb-5"
                    hasBackButton
                    :backHandler="() => this.$router.push({name: 'account.teams'})"
                >
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
            console.log(team, '>>team')
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
