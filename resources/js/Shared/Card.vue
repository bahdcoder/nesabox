<template>
    <div class="shadow mt-4 md:mt-0">
        <div
            class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6 rounded-t-lg"
        >
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ title }}
            </h3>
        </div>

        <slot v-if="table" />
        <div v-else class="w-full bg-gray-50 p-4 md:p-6 pb-8">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String
        },
        table: {
            type: Boolean,
            default: false,
            required: false
        }
    }
}
</script>
