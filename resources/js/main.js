import Vue from 'vue'
import Axios from 'axios'
import Pusher from 'pusher-js'
import VueRouter from 'vue-router'
import LaravelEcho from 'laravel-echo'
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

import Svg from '@/Shared/Svg'
import Card from '@/Shared/Card'
import Info from '@/Shared/Info'
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
import SiteLayout from '@/Shared/Sitelayout'
import SelectInput from '@/Shared/SelectInput'
import ConfirmModal from '@/Shared/ConfirmModal'
import Serverlayout from '@/Shared/Serverlayout'
import AccountLayout from '@/Shared/AccountLayout'
import SidebarLayout from '@/Shared/SidebarLayout'
import ButtonTransparent from '@/Shared/ButtonTransparent'
import DeleteActionButton from '@/Shared/DeleteActionButton'

Vue.use(VueRouter)
Vue.mixin(formMixin)
Vue.component('info', Info)
Vue.component('v-svg', Svg)
Vue.component('card', Card)
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
Vue.component('select-input', SelectInput)
Vue.directive('click-outside', ClickOutside)
Vue.component('confirm-modal', ConfirmModal)
Vue.component('server-layout', Serverlayout)
Vue.component('sidebar-layout', SidebarLayout)
Vue.component('account-layout', AccountLayout)
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
            path: '/servers/:server/databases/:database',
            name: 'server.databases',
            component: () =>
                import(`@/Pages/Servers/Databases`).then(
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
        }
    ]
})

router.beforeEach((to, from, next) => {
    const nonAuthRoutes = ['login', 'register']

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
            sites: {}
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
        fetchServer(serverId) {
            axios.get(`/api/servers/${serverId}`).then(({ data }) => {
                this.servers = {
                    ...this.servers,
                    [serverId]: data
                }

                this.servers = {
                    ...this.servers,
                    [serverId]: data
                }

                const servers = this.allServers.servers.map(server => {
                    if (server.id !== serverid) {
                        return server
                    }

                    return data
                })

                const team_servers = this.allServers.team_servers.map(
                    server => {
                        if (server.id !== serverId) {
                            return server
                        }

                        return data
                    }
                )

                this.allServers = {
                    servers,
                    team_servers
                }
            })
        }
    },
    mounted() {
        if (! this.auth) {
            return
        }

        Echo.private(`App.User.${this.auth.id}`).notification(notification => {
            if (
                notification.type === 'App\\Notifications\\Sites\\SiteUpdated'
            ) {
                this.fetchSite(notification.server, notification.site)
            }

            if (
                notification.type ===
                'App\\Notifications\\Servers\\ServerIsReady'
            ) {
                this.fetchServer(notification.server)
            }
        })
    }
})