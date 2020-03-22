<template>
    <div>
        <label
            :for="name"
            v-if="label"
            class="block text-sm font-medium leading-5 text-gray-700"
        >
            {{ label }}
        </label>
        <div class="mt-1 rounded-md shadow-sm">
            <input
                required
                :type="type"
                :value="value"
                v-bind="$attrs"
                @input="$emit('input', $event.target.value)"
                :class="{
                    'border-gray-300 focus:border-blue-300 focus:shadow-outline-blue placeholder-gray-400':
                        errors.length === 0,
                    'border-red-300 focus:shadow-outline-red focus:border-red-300 placeholder-red-300':
                        errors.length > 0
                }"
                :name="name"
                :id="name"
                class="appearance-none block w-full px-3 py-2 border rounded-md focus:outline-none transition duration-150 ease-in-out sm:text-sm sm:leading-5"
            />
        </div>

        <p v-if="errors.length > 0" class="mt-2 text-sm text-red-600">
            {{ errors[0] }}
        </p>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            required: false
        },
        value: {
            type: String,
            required: false
        },
        errors: {
            type: Array,
            default: () => []
        },
        name: {
            type: String,
            required: true
        },
        type: {
            type: String,
            required: false,
            default: 'text'
        }
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        select() {
            this.$refs.input.select()
        }
    }
}
</script>
