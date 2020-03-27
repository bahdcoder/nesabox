<template>
    <site-layout>
        <template slot="content">
            <card v-if="!site.repository" title="Install Repository">
                <form
                    v-if="repoOptions.length > 0 && !site.installing_repository"
                    @submit.prevent="submit"
                >
                    <v-radio
                        id="provider"
                        :options="repoOptions"
                        label="Provider"
                        v-model="form.provider"
                        :errors="formErrors.provider"
                    />

                    <div class="w-full mt-5">
                        <text-input
                            name="repository"
                            label="Repository"
                            v-model="form.repository"
                            placeholder="user/repository"
                            :errors="formErrors.repository"
                            help="This should match the path to your repository."
                        />
                    </div>

                    <div class="w-full mt-5">
                        <text-input
                            name="branch"
                            label="Branch"
                            v-model="form.branch"
                            :errors="formErrors.branch"
                            help="All deployments would be triggered from this branch."
                        />
                    </div>

                    <div class="flex justify-end w-full w-full mt-5">
                        <v-button
                            type="submit"
                            :loading="submitting"
                            :disabled="submitting"
                            class="w-full md:w-1/5"
                            label="Install repository"
                        />
                    </div>
                </form>

                <router-link
                    to="/account/source-control"
                    v-if="
                        repoOptions.length === 0 && !site.installing_repository
                    "
                    class="w-full border border-blue-500 bg-blue-100 flex items-center rounded text-blue-900 px-2 py-3 text-sm"
                >
                    You have not configured any git repository providers yet. To
                    setup a site, connect your git repository provider here.
                </router-link>

                <div
                    v-if="site.installing_repository"
                    class="w-full border border-blue-500 bg-blue-100 flex items-center rounded text-blue-900 px-2 py-3 text-sm"
                >
                    Installing repository
                    <spinner class="ml-3 text-blue-800 w-4 h-4" />
                </div>
            </card>
        </template>
    </site-layout>
</template>

<script>
export default {
    data() {
        return {
            form: {
                provider: '',
                repository: '',
                branch: 'master'
            }
        }
    },
    computed: {
        site() {
            return this.$root.sites[this.$route.params.site] || {}
        },
        serverId() {
            return this.$route.params.server
        },
        siteId() {
            return this.$route.params.site
        },
        repoOptions() {
            const sourceControl = this.$root.auth.source_control
            const enabledProviders = Object.keys(
                this.$root.auth.source_control
            ).filter(provider => sourceControl[provider])

            return enabledProviders.map(provider => ({
                label: provider,
                value: provider
            }))
        }
    },
    methods: {
        submit() {
            this.submitForm().then(site => {
                this.$root.sites = {
                    ...this.$root.sites,
                    [this.siteId]: site
                }
            })
        }
    },
    mounted() {
        this.initializeForm(
            `/api/servers/${this.serverId}/sites/${this.siteId}/install-repository`
        )

        if (this.repoOptions.length === 1) {
            this.form = {
                ...this.form,
                provider: this.repoOptions[0].value
            }
        }
    }
}
</script>
