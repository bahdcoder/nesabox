<template>
    <div>
        <sidebar-layout :nav="nav" :showNav="true" :active="active">
            <div>
                <template slot="header">
                    <!-- <div class="h-12 w-full mb-5"></div> -->
                    <slot name="header" />
                </template>

                <slot name="content" />
            </div>
        </sidebar-layout>
    </div>
</template>

<script>
export default {
    data() {
        const route = path => `/account${path ? '/' : ''}${path}`

        return {
            nav: [
                {
                    label: 'Account',
                    value: 'account',
                    to: route('')
                },
                {
                    label: 'Server providers',
                    value: 'server-providers',
                    to: route('server-providers')
                }
            ],
            loading: true
        }
    },
    computed: {
        active() {
            return this.$route.path
        }
    }
}
</script>
