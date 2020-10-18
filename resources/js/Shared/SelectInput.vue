<template>
    <div>
        <div
            :class="{
                'mb-3': help
            }"
        >
            <label
                :for="name"
                v-if="label"
                class="block text-sm font-medium leading-5 text-gray-700"
            >
                {{ label }}
            </label>

            <slot name="help">
                <small class="text-gray-700">
                    {{ help }}
                </small>
            </slot>
        </div>
        <div class="mt-1 rounded-md shadow-sm">
            <select
                :id="name"
                ref="input"
                :name="name"
                :value="value"
                :placeholder="placeholder"
                @input="$emit('input', $event.target.value)"
                class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
            >
                <option value="">Select a {{ name }}</option>
                <option
                    v-for="option in options"
                    :value="option.value"
                    :key="option.value"
                    :selected="option.value === value"
                >
                    {{ option.label }}
                </option>
            </select>
        </div>

        <p v-if="errors.length > 0" class="mt-2 text-sm text-red-600">
            {{ errors[0] }}
        </p>
    </div>
</template>

<script>
export default {
    props: {
        help: {
            type: String,
            required: false
        },
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
        options: {
            type: Array,
            required: false,
            default: () => []
        },
        placeholder: {
            type: String,
            default: '',
            required: false
        }
    }
}
</script>
