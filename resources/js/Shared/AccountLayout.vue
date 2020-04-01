<template>
    <div>
        <sidebar-layout :nav="nav" :showNav="true" :active="active">
            <template slot="header">
                <h3 class="text-lg mb-3">Your Account</h3>
            </template>

            <slot name="content" />
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
                    label: 'SSH keys',
                    value: 'sshkeys',
                    to: route('ssh-keys')
                },
                {
                    label: 'Server providers',
                    value: 'server-providers',
                    to: route('server-providers')
                },
                {
                    label: 'Source control',
                    value: 'source-control',
                    to: route('source-control')
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
