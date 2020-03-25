import Vue from 'vue'
import Axios from 'axios'
import VueRouter from 'vue-router'
import ClickOutside from 'vue-click-outside'

window.axios = Axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

import Svg from '@/Shared/Svg'
import Table from '@/Shared/Table'
import Layout from '@/Shared/Layout'
import Button from '@/Shared/Button'
import TextInput from '@/Shared/TextInput'
import SidebarLayout from '@/Shared/SidebarLayout'
import ButtonTransparent from '@/Shared/ButtonTransparent'

Vue.use(VueRouter)
Vue.component('v-svg', Svg)
Vue.component('layout', Layout)
Vue.component('v-table', Table)
Vue.component('v-button', Button)
Vue.component('text-input', TextInput)
Vue.directive('click-outside', ClickOutside)
Vue.component('sidebar-layout', SidebarLayout)
Vue.component('v-trans-button', ButtonTransparent)

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
            auth: window.auth ? JSON.parse(window.auth) : null
        }
    }
})
