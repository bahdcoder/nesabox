<template>
    <site-layout>
        <template slot="content">
            <flash />
            <card title="PM2 Logs">
                <info>
                    The site logs will be updated in real time.
                </info>
                <codemirror
                    v-model="logs"
                    :options="codeMirrorOptions"
                    :class="{
                        'remove-bottom-border-radius': fetchingLogs,
                        'mt-4': true
                    }"
                />
                <div
                    v-if="fetchingLogs"
                    class="w-full h-6 flex justify-center rounded-b"
                    style="background :#2b3e50"
                >
                    <pulse />
                </div>
            </card>
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
            codeMirrorOptions,
            fetchingLogs: true,
            logs: ''
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
        }
    },
    methods: {
        fetchLogs() {
            axios
                .get(`/api/servers/${this.serverId}/sites/${this.siteId}/logs`)
                .then(({ data: logs }) => {
                    this.fetchingLogs = false
                    this.logs = logs
                })
        }
    },
    mounted() {
        this.fetchLogs()
    },
    watch: {
        site() {
            this.fetchLogs()
        }
    }
}
</script>
