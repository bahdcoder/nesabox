<template>
    <div class="relative flex items-start">
        <div class="absolute flex items-center h-5">
            <input
                :id="name"
                type="checkbox"
                @click="toggle"
                :checked="checked"
                class="cursor-pointer form-checkbox h-4 w-4 text-sha-green-600 transition duration-150 ease-in-out"
            />
        </div>
        <div class="pl-7 text-sm leading-5">
            <label :for="name" class="font-medium text-gray-700 cursor-pointer">
                {{ label }}
            </label>
            <p v-if="help" class="text-gray-500">
                {{ help }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        name: {
            type: String,
            required: true
        },
        label: {
            type: String,
            required: false
        },
        help: {
            type: String,
            required: false
        },
        checked: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    methods: {
        toggle(event) {
            this.$emit('input', !this.checked)
        },
    },
}
</script>
