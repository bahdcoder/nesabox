<template>
    <codemirror :options="options" :value="value" />
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
    props: {
        value: {
            type: String,
            required: false,
            default: ''
        }
    },
    data() {
        const options = {
            theme: 'lucario',
            tabSize: 4,
            line: false,
            mode: 'shell',
            lineNumbers: false,
            readOnly: true
        }

        return {
            options
        }
    }
}
</script>
