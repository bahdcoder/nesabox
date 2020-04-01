<template>
    <account-layout>
        <template slot="content">
            <flash />
            <card title="Change Profile Information" class="mb-6">
                <form @submit.prevent="submit">
                    <text-input name="name" label="Name" v-model="form.name" />

                    <text-input
                        name="email"
                        label="Email"
                        class="mt-5"
                        v-model="form.email"
                    />

                    <v-button
                        type="submit"
                        class="mt-5"
                        label="Update Profile"
                        :loading="submitting"
                    />
                </form>
            </card>

            <card title="Change your password">
                <form @submit.prevent="update">
                    <text-input
                        type="password"
                        name="current_password"
                        label="Current password"
                        v-model="passwordForm.current_password"
                        :errors="passwordErrors.current_password"
                    />

                    <text-input
                        class="mt-5"
                        type="password"
                        name="new_password"
                        label="New password"
                        v-model="passwordForm.new_password"
                        :errors="passwordErrors.new_password"
                    />

                    <text-input
                        class="mt-5"
                        type="password"
                        name="new_password_confirmation"
                        label="New password confirmation"
                        v-model="passwordForm.new_password_confirmation"
                    />

                    <v-button
                        class="mt-5"
                        label="Update password"
                        type="submit"
                        :loading="updating"
                    />
                </form>
            </card>
        </template>
    </account-layout>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: '',
                email: ''
            },
            passwordForm: {
                current_password: '',
                new_password: '',
                new_password_confirmation: ''
            },
            passwordErrors: {},
            submitting: false,
            updating: false
        }
    },
    mounted() {
        this.form = {
            name: this.$root.auth.name,
            email: this.$root.auth.email
        }
    },
    methods: {
        submit() {
            this.submitting = true
            axios
                .put(`/api/me`, this.form)
                .then(({ data: user }) => {
                    this.$root.auth = user

                    this.$root.flashMessage('Profile updated.')
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.errors = response.data.errors
                    } else {
                        this.$root.flashMessage(
                            response.data.message || 'Failed updating profile.',
                            'error'
                        )
                    }
                })
                .finally(() => {
                    this.submitting = false
                })
        },
        update() {
            this.updating = true

            axios
                .put(`/api/me/password`, this.passwordForm)
                .then(({ data: user }) => {
                    this.$root.flashMessage('Password updated.')

                    this.passwordErrors = {}
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.passwordErrors = response.data.errors
                    } else {
                        this.$root.flashMessage(
                            response.data.message ||
                                'Failed updating password.',
                            'error'
                        )
                    }
                })
                .finally(() => {
                    this.updating = false

                    this.passwordForm = {
                        current_password: '',
                        new_password: '',
                        new_password_confirmation: ''
                    }
                })
        }
    }
}
</script>
