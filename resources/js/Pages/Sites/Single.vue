<template>
    <site-layout @mounted="siteMounted">
        <template slot="content">
            <flash />
            <card
                title="Installing repository"
                v-if="site.installing_repository"
            >
                <div
                    class="w-full border border-blue-500 bg-blue-100 flex items-center rounded text-blue-900 px-2 py-3 text-sm"
                >
                    Installing repository
                    <spinner class="ml-3 text-blue-800 w-4 h-4" />
                </div>
            </card>
            <card v-if="!site.repository" title="Install Repository">
                <form
                    v-if="
                        !site.repository &&
                            repoOptions.length > 0 &&
                            !site.installing_repository
                    "
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
            </card>

            <div v-if="site.repository && !site.installing_repository">
                <card class="mb-6" title="Deployment">
                    <template slot="header">
                        <div class="flex justify-between items-center">
                            <h3
                                class="text-lg leading-6 font-medium text-gray-900 capitalize"
                            >
                                Deployment
                            </h3>

                            <v-button
                                @click="deploy"
                                :loading="site.deploying"
                                label="Deploy Now"
                            >
                                <template slot="loader">
                                    <pulse class="py-1 mr-3" />
                                    <span>Deploying </span>
                                </template>
                            </v-button>
                        </div>
                    </template>

                    <info>
                        If you enable Push to deploy, this site would
                        automatically be deployed when you push (or merge) to
                        the {{ site.repository_branch }} branch of this
                        repository.
                    </info>

                    <div
                        class="flex flex-wrap items-center justify-between mt-5"
                    >
                        <red-button
                            @click="quickDeploy(true)"
                            v-if="site.push_to_deploy"
                            :loading="quickDeploying"
                            label="Disable push to deploy"
                            class="w-full md:w-auto"
                        />
                        <v-button
                            @click="quickDeploy"
                            v-else
                            :loading="quickDeploying"
                            label="Enable push to deploy"
                            class="w-full md:w-auto"
                        />

                        <span
                            v-if="site.latest_deployment || site.deploying"
                            @click="toggleViewLatestDeploymentLogs"
                            class="text-sha-green-500 cursor-pointer hover:text-sha-green-400 transition ease-in-out duration-50 mt-3 md:mt-0"
                        >
                            {{ viewLatestDeploymentLogs ? 'Hide' : 'View' }}
                            latest deployment logs
                        </span>
                    </div>

                    <div
                        class="mt-3"
                        v-if="
                            viewLatestDeploymentLogs &&
                                (site.latest_deployment || site.deploying)
                        "
                    >
                        <codemirror
                            :class="{
                                'remove-bottom-border-radius': site.deploying
                            }"
                            v-model="site.latest_deployment.log"
                            :options="codeMirrorOptions"
                            v-if="site.latest_deployment"
                        />
                        <div
                            v-if="site.deploying"
                            class="w-full h-6 flex justify-center rounded-b"
                            style="background :#2b3e50"
                        >
                            <pulse />
                        </div>
                    </div>
                </card>

                <card title="Deploy script" class="mb-6">
                    <codemirror
                        v-model="deployScript"
                        :options="deployScriptCodeMirrorOptions"
                    />

                    <v-button
                        @click="saveScript"
                        label="Save script"
                        class="w-full md:w-auto mt-4"
                        :loading="savingScript"
                    />
                </card>

                <card title="Deployment trigger url">
                    <div class="text-sm text-gray-800">
                        Creating a slack bot to ease your deployments ? Or using
                        a service like Circle CI and want to trigger deployments
                        after all tests pass ? Make a GET or POST request to
                        this endpoint to trigger a deployment.
                    </div>

                    <text-input
                        name="deployment_trigger_url"
                        readonly
                        :value="site.deployment_trigger_url"
                        class="text-xs mt-5"
                    />

                    <v-button
                        label="Copy to Clipboard"
                        class="w-full md:w-auto mt-4"
                        @click="copyDeploymentTriggerUrl"
                    />
                </card>
            </div>
        </template>
    </site-layout>
</template>

<script>
import { codemirror } from 'vue-codemirror'

import 'codemirror/lib/codemirror.css'
import 'codemirror/theme/lucario.css'
import 'codemirror/mode/shell/shell.js'

export default {
    components: {
        codemirror
    },
    data() {
        const codeMirrorOptions = {
            theme: 'lucario',
            tabSize: 4,
            line: true,
            mode: 'shell',
            lineNumbers: true,
            readOnly: true
        }

        return {
            deploying: false,
            deployScript: '',
            savingScript: false,
            quickDeploying: false,
            viewLatestDeploymentLogs: false,
            form: {
                provider: '',
                repository: '',
                branch: 'master'
            },
            deployScriptCodeMirrorOptions: {
                ...codeMirrorOptions,
                readOnly: false
            },
            codeMirrorOptions
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
        },
        copyDeploymentTriggerUrl() {
            const command = document.getElementById('deployment_trigger_url')

            command.select()
            command.setSelectionRange(0, 99999)
            document.execCommand('copy')
        },
        quickDeploy(disabling = false) {
            this.quickDeploying = true
            axios
                .post(
                    `/api/servers/${this.serverId}/sites/${this.siteId}/push-to-deploy`
                )
                .then(({ data: site }) => {
                    this.$root.sites = {
                        ...this.$root.sites,
                        [site.id]: site
                    }

                    this.$root.flashMessage(
                        `Quick deploy has been ${
                            disabling ? 'disabled' : 'enabled'
                        } for this site.`
                    )
                })
                .finally(() => {
                    this.quickDeploying = false
                })
        },
        deploy() {
            this.deploying = true
            axios
                .post(
                    `/api/servers/${this.serverId}/sites/${this.siteId}/deployments`
                )
                .then(({ data: site }) => {
                    this.$root.sites = {
                        ...this.$root.sites,
                        [site.id]: site
                    }

                    this.viewLatestDeploymentLogs = true
                })
                .catch(() => {
                    this.$root.flashMessage(
                        'Failed to trigger deployment.',
                        'error'
                    )
                })
                .finally(() => {
                    this.deploying = false
                })
        },
        siteMounted() {
            this.deployScript = this.site.before_deploy_script
            this.viewLatestDeploymentLogs = this.site.deploying
        },
        saveScript() {
            this.savingScript = true

            axios
                .put(`/api/servers/${this.serverId}/sites/${this.siteId}`, {
                    before_deploy_script: this.deployScript
                })
                .then(({ data: site }) => {
                    this.$root.flashMessage('Deploy script saved.')

                    this.$root.sites = {
                        ...this.$root.sites,
                        [this.siteId]: site
                    }
                })
                .finally(() => {
                    this.savingScript = false
                })
        },
        toggleViewLatestDeploymentLogs() {
            this.viewLatestDeploymentLogs = !this.viewLatestDeploymentLogs
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
    },
    watch: {
        site(site) {
            this.deployScript = site.before_deploy_script
            this.viewLatestDeploymentLogs = site.deploying
        }
    }
}
</script>
