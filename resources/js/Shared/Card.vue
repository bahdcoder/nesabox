<template>
    <div class="shadow mt-4 md:mt-0">
        <div
            class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6 rounded-t-lg"
        >
            <h3 class="text-lg leading-6 font-medium text-gray-900 capitalize">
                {{ title }}
            </h3>
        </div>

        <slot v-if="table && rowsCount > 0" />
        <div
            class="w-full flex px-6 py-12 justify-center items-center bg-white shadow"
            v-if="table && rowsCount === 0"
        >
            {{ emptyTableMessage }}
        </div>
        <div v-if="!table" class="w-full bg-gray-50 p-4 md:p-6 pb-8">
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
        },
        rowsCount: {
            type: Number,
            default: 0,
            required: false
        },
        emptyTableMessage: {
            type: String,
            required: false,
            default: 'No items yet.'
        }
    }
}
</script>
