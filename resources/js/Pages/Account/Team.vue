<template>
    <account-layout>
        <template v-if="validated" slot="content">
            <flash />
            <confirm-modal
                :open="!!removingMember"
                @confirm="removeMember"
                confirmHeading="Remove member"
                @close="removingMember = null"
                :confirming="removingMemberLoading"
                :confirmText="
                    `Are you sure you want to revoke this member's access to this team and its servers?`
                "
            />
            <card
                v-if="subscription.plan !== 'business'"
                title="Upgrade your plan to add teams"
            >
                <v-button
                    label="Upgrade to Business"
                    component="router-link"
                    to="/account/subscription"
                />
            </card>

            <div v-else>
                <card title="Invite new team member" class="mb-5">
                    <template slot="header">
                        <div class="w-full flex justify-between items-center">
                            <h3
                                class="text-lg leading-6 font-medium text-gray-900 capitalize"
                            >
                                Invite new team member ({{ team.name }})
                            </h3>

                            <router-link
                                class="mr-5 text-sha-green-500"
                                style="text-decoration: underline;"
                                to="/account/teams"
                            >
                                Back to teams
                            </router-link>
                        </div>
                    </template>

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
                    :title="`Team Members (${team.name})`"
                    :rowsCount="teamInvites.length"
                    emptyTableMessage="No team invites sent yet."
                >
                    <v-table :headers="table.headers" :rows="teamInvites">
                        <template slot="row" slot-scope="{ row, header }">
                            <span
                                v-if="header.value === 'email'"
                                class="text-gray-800"
                                >{{ row[header.value] }}</span
                            >
                            <table-status
                                v-if="header.value === 'status'"
                                :status="row.status"
                            />

                            <div v-if="header.value === 'actions'">
                                <delete-button @click="removingMember = row" />
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
            },
            validated: false,
            removingMember: null,
            removingMemberLoading: false
        }
    },
    computed: {
        subscription() {
            return this.$root.auth.subscription
        },
        teamInvites() {
            return this.team.invites
        },
        team() {
            return this.$root.auth.teams.find(
                team => team.id === this.$route.params.id
            )
        }
    },
    mounted() {
        this.initializeForm(`/api/teams/${this.$route.params.id}/invites`)

        if (!this.team) {
            this.$router.push('/account/teams')

            return
        }

        this.validated = true
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
        },
        removeMember() {
            this.removingMemberLoading = true

            axios
                .delete(`/api/invites/${this.removingMember.id}`)
                .then(({ data }) => {
                    this.removingMember = null
                    this.removingMemberLoading = false

                    this.$root.auth = data

                    this.$root.flashMessage('Member removed.')
                })
                .catch(error => {
                    this.removingMember = null
                    this.removingMemberLoading = false

                    this.$root.flashMessage(
                        error.response.data.message ||
                            'Failed to remove member.'
                    )
                })
        },
        setRemoveMember(member) {
            this.removeMemberConfirming = true

            this.removingMember = member
        }
    }
}
</script>
