<template>
    <account-layout>
        <template slot="content">
            <flash />
            <card
                :title="provider.provider"
                v-for="provider in providers"
                :key="provider.provider"
                class="mb-6"
            >
                <red-button
                    :loading="isDisconnecting(provider.provider)"
                    @click="disconnect(provider)"
                    v-if="provider.connected"
                    :label="`Disconnect ${provider.provider}`"
                />
                <v-button
                    v-else
                    component="a"
                    :href="provider.url"
                    :label="`Connect to ${provider.provider}`"
                />
            </card>
        </template>
    </account-layout>
</template>

<script>
export default {
    data() {
        return {
            urls: {},
            disconnecting: {
                github: false,
                gitlab: false
            }
        }
    },
    computed: {
        providers() {
            return Object.keys(this.urls).map(provider => ({
                provider,
                url: this.urls[provider],
                connected: this.$root.auth.source_control[provider]
            }))
        }
    },
    mounted() {
        axios.get('/settings/source-control').then(({ data }) => {
            this.urls = data
        })
    },
    methods: {
        disconnect(provider) {
            this.disconnecting = {
                ...this.disconnecting,
                [provider.provider]: true
            }

            axios
                .post(
                    `/api/settings/source-control/${provider.provider}/unlink`
                )
                .then(({ data: user }) => {
                    this.$root.auth = user

                    this.$root.flashMessage(
                        `${provider.provider} has been unlinked.`
                    )
                })
                .catch(() => {
                    this.$root.flashMessage(
                        `Failed to unlink ${provider.provider}.`,
                        'error'
                    )
                })
                .finally(() => {
                    this.disconnecting = {
                        ...this.disconnecting,
                        [provider.provider]: false
                    }
                })
        },
        isDisconnecting(provider) {
            return this.disconnecting[provider]
        }
    }
}
</script>
