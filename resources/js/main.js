import Vue from 'vue'
import Axios from 'axios'
import Pusher from 'pusher-js'
import VueRouter from 'vue-router'
import LaravelEcho from 'laravel-echo'
import vClickOutside from 'v-click-outside'
import ClickOutside from 'vue-click-outside'

window.Pusher = Pusher

window.Echo = new LaravelEcho({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: 'mt1',
    forceTLS: true
})

window.axios = Axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

import formMixin from '@/mixins/form'
import echoMixin from '@/mixins/echo'

import Svg from '@/Shared/Svg'
import Card from '@/Shared/Card'
import Info from '@/Shared/Info'
import Modal from '@/Shared/Modal'
import Pulse from '@/Shared/Pulse'
import Table from '@/Shared/Table'
import Flash from '@/Shared/Flash'
import Radio from '@/Shared/Radio'
import Status from '@/Shared/Status'
import Layout from '@/Shared/Layout'
import Button from '@/Shared/Button'
import Spinner from '@/Shared/Spinner'
import Checkbox from '@/Shared/Checkbox'
import RedButton from '@/Shared/RedButton'
import TextInput from '@/Shared/TextInput'
import Codemirror from '@/Shared/Codemirror'
import SiteLayout from '@/Shared/Sitelayout'
import SelectInput from '@/Shared/SelectInput'
import ConfirmModal from '@/Shared/ConfirmModal'
import Serverlayout from '@/Shared/Serverlayout'
import TextareaInput from '@/Shared/TextareaInput'
import Notifications from '@/Shared/Notifications'
import AccountLayout from '@/Shared/AccountLayout'
import SidebarLayout from '@/Shared/SidebarLayout'
import ButtonTransparent from '@/Shared/ButtonTransparent'
import DeleteActionButton from '@/Shared/DeleteActionButton'

Vue.use(VueRouter)
Vue.mixin(formMixin)
Vue.mixin(echoMixin)
Vue.use(vClickOutside)
Vue.component('info', Info)
Vue.component('v-svg', Svg)
Vue.component('card', Card)
Vue.component('modal', Modal)
Vue.component('pulse', Pulse)
Vue.component('flash', Flash)
Vue.component('v-radio', Radio)
Vue.component('layout', Layout)
Vue.component('v-table', Table)
Vue.component('v-button', Button)
Vue.component('spinner', Spinner)
Vue.component('checkbox', Checkbox)
Vue.component('table-status', Status)
Vue.component('red-button', RedButton)
Vue.component('text-input', TextInput)
Vue.component('site-layout', SiteLayout)
Vue.component('v-codemirror', Codemirror)
Vue.component('select-input', SelectInput)
Vue.directive('click-outside', ClickOutside)
Vue.component('confirm-modal', ConfirmModal)
Vue.component('server-layout', Serverlayout)
Vue.component('notifications', Notifications)
Vue.component('sidebar-layout', SidebarLayout)
Vue.component('account-layout', AccountLayout)
Vue.component('textarea-input', TextareaInput)
Vue.component('v-trans-button', ButtonTransparent)
Vue.component('delete-button', DeleteActionButton)

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/auth/login',
            name: 'login',
            component: () =>
                import(`@/Pages/Auth/Login`).then(module => module.default)
        },
        {
            path: '/auth/forgot-password',
            name: 'forgot-password',
            component: () =>
                import(`@/Pages/Auth/ForgotPassword`).then(
                    module => module.default
                )
        },
        {
            path: '/auth/reset-password/:token',
            name: 'reset-password',
            component: () =>
                import(`@/Pages/Auth/ResetPassword`).then(
                    module => module.default
                )
        },
        {
            path: '/auth/register',
            name: 'register',
            component: () =>
                import(`@/Pages/Auth/Register`).then(module => module.default)
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: () =>
                import(`@/Pages/Dashboard/Index`).then(module => module.default)
        },
        {
            path: '/servers/create',
            name: 'server.create',
            component: () =>
                import(`@/Pages/Servers/Create`).then(module => module.default)
        },
        {
            path: '/servers/:server',
            name: 'server.single',
            component: () =>
                import(`@/Pages/Servers/Single`).then(module => module.default)
        },
        {
            path: '/servers/:server/scheduler',
            name: 'server.scheduler',
            component: () =>
                import(`@/Pages/Servers/Scheduler`).then(
                    module => module.default
                )
        },
        {
            path: '/servers/:server/ssh-keys',
            name: 'server.ssh-keys',
            component: () =>
                import(`@/Pages/Account/SshKeys`).then(module => module.default)
        },
        {
            path: '/servers/:server/meta',
            name: 'server.meta',
            component: () =>
                import(`@/Pages/Servers/Meta`).then(module => module.default)
        },
        {
            path: '/servers/:server/network',
            name: 'server.network',
            component: () =>
                import(`@/Pages/Servers/Network`).then(module => module.default)
        },
        {
            path: '/servers/:server/databases/mysql8',
            name: 'server.databases.mysql8',
            component: () =>
                import(`@/Pages/Servers/Databases/Mysql`).then(
                    module => module.default
                )
        },
        {
            path: '/servers/:server/databases/mysql',
            name: 'server.databases.mysql',
            component: () =>
                import(`@/Pages/Servers/Databases/Mysql`).then(
                    module => module.default
                )
        },
        {
            path: '/servers/:server/databases/mariadb',
            name: 'server.databases.mariadb',
            component: () =>
                import(`@/Pages/Servers/Databases/Mysql`).then(
                    module => module.default
                )
        },
        {
            path: '/servers/:server/databases/postgresql',
            name: 'server.databases.postgresql',
            component: () =>
                import(`@/Pages/Servers/Databases/Postgres`).then(
                    module => module.default
                )
        },
        {
            path: '/servers/:server/databases/mongodb',
            name: 'server.databases.mongodb',
            component: () =>
                import(`@/Pages/Servers/Databases/Mongodb`).then(
                    module => module.default
                )
        },
        {
            path: '/servers/:server/sites/:site',
            name: 'server.site',
            component: () =>
                import(`@/Pages/Sites/Single`).then(module => module.default)
        },
        {
            path: '/servers/:server/sites/:site/settings',
            name: 'server.site.settings',
            component: () =>
                import(`@/Pages/Sites/Settings`).then(module => module.default)
        },
        {
            path: '/servers/:server/sites/:site/files',
            name: 'server.site.files',
            component: () =>
                import(`@/Pages/Sites/Files`).then(module => module.default)
        },
        {
            path: '/servers/:server/sites/:site/ssl',
            name: 'server.site.ssl',
            component: () =>
                import(`@/Pages/Sites/Ssl`).then(module => module.default)
        },
        {
            path: '/servers/:server/sites/:site/logs',
            name: 'server.site.logs',
            component: () =>
                import(`@/Pages/Sites/Logs`).then(module => module.default)
        },
        {
            path: '/account',
            name: 'account.profile',
            component: () =>
                import(`@/Pages/Account/Index`).then(module => module.default)
        },
        {
            path: '/account/teams',
            name: 'account.teams',
            component: () =>
                import(`@/Pages/Account/Teams`).then(module => module.default)
        },
        {
            path: '/account/subscription',
            name: 'account.subscription',
            component: () =>
                import(`@/Pages/Account/Subscription`).then(
                    module => module.default
                )
        },
        {
            path: '/account/server-providers',
            name: 'account.server-providers',
            component: () =>
                import(`@/Pages/Account/ServerProvider`).then(
                    module => module.default
                )
        },
        {
            path: '/account/source-control',
            name: 'account.source-control',
            component: () =>
                import(`@/Pages/Account/SourceControl`).then(
                    module => module.default
                )
        },
        {
            path: '/account/ssh-keys',
            name: 'account.ssh-keys',
            component: () =>
                import(`@/Pages/Account/SshKeys`).then(module => module.default)
        },
        {
            path: '/account/teams/:id',
            name: 'account.team.team-id',
            component: () =>
                import(`@/Pages/Account/Team`).then(module => module.default)
        }
    ]
})

router.beforeEach((to, from, next) => {
    const nonAuthRoutes = [
        'login',
        'register',
        'forgot-password',
        'reset-password'
    ]

    if (nonAuthRoutes.includes(to.name)) {
        if (window.auth) {
            return next({ name: 'dashboard' })
        }
    }

    if (!nonAuthRoutes.includes(to.name)) {
        if (window.auth) {
            return next()
        } else {
            return next({ name: 'login' })
        }
    }

    next()
})

const app = new Vue({
    el: '#app',
    router,
    data() {
        return {
            auth: window.auth ? JSON.parse(window.auth) : null,
            servers: {},
            flash: {
                message: '',
                type: ''
            },
            allServers: {
                servers: [],
                team_servers: []
            },
            sites: {},
            notifications: []
        }
    },
    methods: {
        flashMessage(message, type = 'success', timeout = 3000) {
            this.flash = {
                type,
                message
            }

            setTimeout(() => {
                this.flash = {
                    message: '',
                    type: ''
                }
            }, timeout)
        },
        fetchSite(serverId, siteId) {
            axios
                .get(`/api/servers/${serverId}/sites/${siteId}`)
                .then(({ data }) => {
                    this.sites = {
                        ...this.sites,
                        [siteId]: data
                    }
                })
        },
        updateServer(serverId, updatedServer) {
            this.servers = {
                ...this.servers,
                [serverId]: updatedServer
            }

            const servers = this.allServers.servers.map(server => {
                if (server.id !== serverId) {
                    return server
                }

                return updatedServer
            })

            const team_servers = this.allServers.team_servers.map(server => {
                if (server.id !== serverId) {
                    return server
                }

                return updatedServer
            })

            this.allServers = {
                servers,
                team_servers
            }
        },
        fetchServer(serverId) {
            axios.get(`/api/servers/${serverId}`).then(({ data }) => {
                this.updateServer(serverId, data)
            })
        }
    },
    mounted() {
        if (!this.auth) {
            return
        }
    }
})
