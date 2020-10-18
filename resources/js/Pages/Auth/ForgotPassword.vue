<template>
    <div
        class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 px-3 sm:px-6 lg:px-8"
    >
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <img
                class="mx-auto h-8 w-auto"
                src="/assets/images/logo.svg"
                alt="Workflow"
            />

            <h2
                class="mt-6 text-center text-3xl leading-9 font-bold text-gray-800"
            >
                Forgot your password ?
            </h2>
            <p class="mt-2 text-center text-sm leading-5 text-gray-600 max-w">
                Or
                <router-link
                    to="/auth/login"
                    class="font-medium text-sha-green-500 hover:text-sha-green-400 focus:outline-none focus:underline transition ease-in-out duration-150"
                >
                    sign in to your account
                </router-link>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <div v-if="success" class="my-3 text-green-500 text-center">
                    {{ success }}
                </div>
                <form @submit.prevent="submit" method="POST">
                    <text-input
                        name="email"
                        v-model="form.email"
                        label="Email Address"
                        :errors="errors.email"
                    />

                    <div class="mt-6">
                        <span class="block w-full rounded-md shadow-sm">
                            <v-button
                                :loading="loading"
                                type="submit"
                                label="Send Password reset link"
                                :full="true"
                            />
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                email: '',
                password: '',
                remember: null
            },
            loading: false,
            success: null,
            errors: {
                email: []
            }
        }
    },
    methods: {
        submit() {
            this.loading = true

            axios
                .post('/password/email', this.form)
                .then(() => {
                    this.success = 'You password reset mail has been sent.'

                    this.form = {
                        email: ''
                    }

                    this.errors = {}

                    this.loading = false
                })
                .catch(({ response }) => {
                    this.loading = false

                    if (response.status === 422) {
                        this.errors = response.data.errors
                    } else {
                        this.errors = {
                            ...this.errors,
                            email: [
                                response.data.message || 'Failed to send email.'
                            ]
                        }
                    }
                })
        }
    }
}
</script>
