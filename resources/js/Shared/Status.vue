<template>
    <div class="flex items-center">
        <span
            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
            :class="{
                'bg-green-100 text-green-800': status === 'active',
                'bg-blue-100 text-blue-800': [
                    'new',
                    'initializing',
                    'installing'
                ].includes(status)
            }"
        >
            {{ status }}
        </span>
        <span v-if="status !== 'active'" class="ml-3">
            <spinner class="w-4 h-4 text-blue-800" />
        </span>
    </div>
</template>

<script>
export default {
    props: {
        status: {
            type: String,
            required: true
        }
    }
}
</script>
