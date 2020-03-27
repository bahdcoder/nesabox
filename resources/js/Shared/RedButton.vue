<template>
    <button
        :to="to"
        :type="type"
        :disabled="disabled"
        @click="$emit('click')"
        class="inline-flex justify-center rounded-md border border-transparent px-4 py-2 bg-red-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red transition ease-in-out duration-150 sm:text-sm sm:leading-5"
        :class="{
            'cursor-not-allowed': loading
        }"
    >
        <pulse class="px-4 py-1" v-if="loading" />
        <span v-else>{{ label }}</span>
    </button>
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
