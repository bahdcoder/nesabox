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
                class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900"
            >
                Reset your password
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

                    <text-input
                        class="mt-4"
                        name="password"
                        label="Password"
                        type="password"
                        v-model="form.password"
                        :errors="errors.password"
                    />

                    <text-input
                        class="mt-4"
                        type="password"
                        name="password_confirmation"
                        label="Password Confirmation"
                        v-model="form.password_confirmation"
                        :errors="errors.password_confirmation"
                    />

                    <div class="mt-6">
                        <span class="block w-full rounded-md shadow-sm">
                            <button
                                type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-sha-green-500 hover:bg-sha-green-400 focus:outline-none focus:border-sha-green-600 active:bg-sha-green-600 transition duration-150 ease-in-out"
                            >
                                Reset Password
                            </button>
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
            sending: false,
            form: {
                email: '',
                password: '',
                password_confirmation: '',
                token: this.$route.params.token
            },
            success: null,
            errors: {
                email: []
            }
        }
    },
    methods: {
        submit() {
            axios
                .post('/password/reset', this.form)
                .then(() => {
                    this.success = 'Your password has been reset.'

                    this.form = {
                        email: '',
                        password: '',
                        password_confirmation: ''
                    }

                    setTimeout(() => {
                        window.location.href = '/dashboard'
                    }, 1000)
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.errors = response.data.errors
                    } else {
                        this.errors = {
                            ...this.errors,
                            email: [
                                response.data.message ||
                                    'Failed resetting password.'
                            ]
                        }
                    }
                })
        }
    }
}
</script>
