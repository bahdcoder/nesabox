<template>
    <div
        v-if="message"
        class="w-full px-3 py-5 h-12 rounded flex items-center justify-between text-white my-3"
        :class="{ 'bg-green-400': type === 'success', 'bg-red-600': type === 'error' }"
    >
        <span class="inline-block">
            {{ message }}
        </span>

        <v-svg icon='cross-circle' width='24' height='24' />

        <svg
            v-if="type === 'success'"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                stroke="#fff"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
        </svg>
    </div>
</template>

<script>
export default {
    computed: {
        message() {
            return this.$root.flash.message
        },
        type() {
            return this.$root.flash.type
        }
    }
}
</script>
