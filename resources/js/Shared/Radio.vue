<template>
    <div>
        <label
            v-if="label"
            class="block text-sm font-medium leading-5 text-gray-700"
        >
            {{ label }}
        </label>
        <div
            class="flex items-center mt-3"
            v-for="option in options"
            :key="option.value"
        >
            <input
                :id="option.value"
                name="form-input"
                type="radio"
                :checked="value === option.value"
                :value="value"
                @input="$emit('input', $event.target.value)"
                class="form-radio h-4 w-4 text-sha-green-500 transition duration-150 ease-in-out"
            />
            <label :for="option.value" class="ml-3 cursor-pointer">
                <span
                    class="block text-sm leading-5 font-medium text-gray-700 capitalize"
                >
                    {{ option.label }}
                </span>
            </label>
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
            required: false,
            default: ''
        },
        value: {
            type: String,
            required: false
        },
        options: {
            type: Array,
            required: false,
            default: () => []
        },
        id: {
            type: String,
            required: true
        },
        errors: {
            type: Array,
            required: false,
            default: () => []
        }
    }
}
</script>
