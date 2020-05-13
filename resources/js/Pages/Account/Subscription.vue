<template>
    <account-layout>
        <template slot="content">
            <confirm-modal
                :confirming="cancelling"
                @confirm="cancelSubscription"
                :open="cancellingSubscription"
                @close="toggleCancellingSubscription"
                confirmHeading="Cancel subscription"
                :confirmText="
                    `Are you sure you want cancel your subscription ? You would loose all features in the ${subscription.plan} plan`
                "
            />
            <flash />
            <card title="Update your nesabox plan" class="mb-6">
                <div v-if="!updating" class="w-full flex flex-wrap">
                    <div
                        v-for="plan in plans"
                        :key="plan.key"
                        class="w-full sm:w-1/2 lg:w-1/3 px-2 mt-4 md:mt-0"
                    >
                        <div class="bg-white shadow rounded overflow-hidden">
                            <div
                                class="bg-sha-green-500 text-white px-3 py-3 text-xl"
                            >
                                <div
                                    class="flex justify-between items-center font-medium"
                                >
                                    <div>{{ plan.name }}</div>
                                    <div>${{ plan.price }} / month</div>
                                </div>
                            </div>

                            <div class="bg-white px-4 py-4">
                                <div
                                    v-for="feature in plan.features"
                                    :key="feature.name"
                                    class="mt-4"
                                >
                                    <div class="flex items-center">
                                        <svg
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            viewBox="0 0 24 24"
                                            class="w-6 h-6 text-sha-green-500"
                                        >
                                            <path d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <div
                                            class="ml-2"
                                            :class="{
                                                'font-medium': feature.bold
                                            }"
                                        >
                                            {{ feature.name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p
                            v-if="subscription.plan === plan.key"
                            class="mt-4 text-center font-medium text-lg"
                        >
                            Your current plan
                        </p>

                        <component
                            v-else
                            :full="true"
                            class="mt-4"
                            :is="
                                getPlanLabel(plan).match(/Downgrade/)
                                    ? 'red-button'
                                    : 'v-button'
                            "
                            @click="openPaddle(plan)"
                            :label="getPlanLabel(plan)"
                        />
                    </div>
                </div>

                <v-button
                    v-else
                    label="Updating subscription"
                    :loading="true"
                />
            </card>
        </template>
    </account-layout>
</template>

<script>
export default {
    data() {
        return {
            updating: false,
            cancellingSubscription: false,
            cancelling: false,
            plans: [
                {
                    name: 'Free',
                    key: 'free',
                    price: 0,
                    features: [
                        {
                            name: '1 Server',
                            bold: true
                        },
                        {
                            name: 'Unlimited Sites'
                        },
                        {
                            name: 'Unlimited Deployments'
                        },
                        {
                            name: 'Push To Deploy'
                        }
                    ]
                },
                {
                    name: 'Pro',
                    key: 'pro',
                    price: 5,
                    id: parseInt(process.env.MIX_PADDLE_PRO_PLAN_ID),
                    features: [
                        {
                            name: 'Unlimited Servers',
                            bold: true
                        },
                        {
                            name: 'Unlimited Sites'
                        },
                        {
                            name: 'Unlimited Deployments'
                        },
                        {
                            name: 'Push To Deploy'
                        }
                    ]
                },
                {
                    name: 'Business',
                    key: 'business',
                    price: 15,
                    id: parseInt(process.env.MIX_PADDLE_BUSINESS_PLAN_ID),
                    features: [
                        {
                            name: 'Unlimited Servers'
                        },
                        {
                            name: 'Unlimited Sites'
                        },
                        {
                            name: 'Unlimited Teams'
                        },
                        {
                            name: 'Unlimited Collaboratos',
                            bold: true
                        }
                    ]
                }
            ]
        }
    },
    computed: {
        subscription() {
            return this.user.subscription
        },
        user() {
            return this.$root.auth
        }
    },
    mounted() {
        window.Paddle.Setup({
            vendor: parseInt(process.env.MIX_PADDLE_VENDOR_ID)
        })
    },
    methods: {
        getPlanLabel(plan) {
            if (this.subscription.plan === 'free') {
                return `Upgrade to ${plan.name} plan`
            }

            if (this.subscription.plan === 'business') {
                return `Downgrade to ${plan.name} plan`
            }

            if (this.subscription.plan === 'pro') {
                if (plan.key === 'free') {
                    return 'Downgrade to free plan'
                }

                return 'Upgrade to Business plan'
            }
        },
        openPaddle(selectedPlan) {
            if (selectedPlan.key === 'free') {
                this.toggleCancellingSubscription()

                return
            }

            if (this.subscription.plan !== 'free') {
                this.updatePlan(selectedPlan)

                return
            }

            window.Paddle.Checkout.open({
                product: selectedPlan.id,
                email: this.user.email,
                successCallback: data => {
                    this.updating = true

                    setTimeout(() => {
                        window.location.href = '/account/subscription'
                    }, 5000)
                }
            })
        },
        cancelSubscription() {
            this.cancelling = true

            axios
                .delete('/api/subscription/cancel')
                .then(({ data }) => {
                    this.$root.auth = data

                    this.$root.flashMessage(`Your plan has been cancelled.`)
                })
                .catch(({ response }) => {
                    if (response.data && response.data.message) {
                        this.$root.flashMessage(response.data.message, 'error')
                    } else {
                        this.$root.flashMessage(
                            'Failed to cancel subscription.',
                            'error'
                        )
                    }
                })
                .finally(() => {
                    this.cancelling = false

                    this.toggleCancellingSubscription()
                })
        },
        toggleCancellingSubscription() {
            this.cancellingSubscription = !this.cancellingSubscription
        },
        updatePlan(selectedPlan) {
            this.updating = true

            axios
                .patch('/api/subscription/update', {
                    plan: selectedPlan.key
                })
                .then(({ data }) => {
                    this.$root.auth = data

                    this.$root.flashMessage(
                        `Subscription has been updated to ${selectedPlan.key}`
                    )
                })
                .catch(({ response }) => {
                    if (response.data && response.data.message) {
                        this.$root.flashMessage(response.data.message, 'error')
                    } else {
                        this.$root.flashMessage(
                            'Failed to update subscription plan.',
                            'error'
                        )
                    }
                })
                .finally(() => {
                    this.updating = false
                })
        }
    }
}
</script>
