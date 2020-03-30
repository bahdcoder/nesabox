<template>
    <button
        :type="type"
        :disabled="disabled"
        @click="$emit('click')"
        class="w-full md:w-auto py-2 px-4 border border-gray-300 rounded-md text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out"
    >
        {{ label }}
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
        }
    }
}
</script>
