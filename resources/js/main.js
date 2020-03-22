import Vue from 'vue'
import { InertiaApp } from '@inertiajs/inertia-vue'

import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'

Vue.component('layout', Layout)
Vue.component('text-input', TextInput)

Vue.use(InertiaApp)

const app = document.getElementById('app')

new Vue({
    render: h =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: name =>
                    import(`@/Pages/${name}`).then(module => module.default)
            }
        })
}).$mount(app)
