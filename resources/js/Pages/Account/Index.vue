<template>
    <account-layout>
        <template slot="content">
            <flash />
            <card title='Change Profile Information' class="mb-6">
                <form @submit.prevent="submit">
                    <text-input
                        name='name'
                        label='Name'
                        v-model="form.name"
                    />

                    <text-input
                        name='email'
                        label='Email'
                        class="mt-5"
                        v-model="form.email"
                    />

                    <v-button type='submit' class="mt-5" label='Update Profile' :loading="submitting" />
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
                submitting: false
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
                axios.put(`/api/me`, this.form)
                    .then(({ data: user }) => {
                        this.$root.auth = user

                        this.$root.flashMessage('Profile updated.')
                    })
                    .catch(({ response }) => {
                        this.$root.flashMessage(response.data.message || 'Failed updating profile.', 'error')
                    })
                    .finally(() => {
                        this.submitting = false
                    })
            }
        }
    }
</script>
