<template>
    <component
        :to="to"
        :type="type"
        :is="component"
        :disabled="disabled"
        @click="$emit('click')"
        :class="{
            'cursor-not-allowed': loading
        }"
        class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-sha-green-500 hover:bg-sha-green-400 focus:outline-none focus:border-sha-green-600 focus:shadow-outline-sha-green active:bg-sha-green-600 transition duration-150 ease-in-out"
    >
        <pulse class="py-1" v-if='loading' />
        <span v-else>{{ label }}</span>
    </component>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            required: false,
            default: 'button'
        },
        label: {
            type: String,
            required: false
        },
        disabled: {
            type: Boolean,
            required: false,
            default: false
        },
        component: {
            default: 'button'
        },
        to: {
            required: false
        },
        loading: {
            type: Boolean,
            required: false,
            default: false
        }
    }
}
</script>
