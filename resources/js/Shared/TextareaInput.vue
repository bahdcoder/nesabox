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
                <small class="text-gray-600">
                    {{ help }}
                </small>
            </slot>
        </div>
        <div class="mt-1 rounded-md shadow-sm">
            <textarea
                ref="input"
                :rows="rows"
                :type="type"
                :value="value"
                :readonly="readonly"
                :placeholder="placeholder"
                @input="$emit('input', $event.target.value)"
                :class="{
                    'appearance-none block w-full px-3 py-2 border rounded-md focus:outline-none transition duration-150 ease-in-out sm:text-sm sm:leading-5': true,
                    'border-gray-300 focus:border-blue-300 focus:shadow-outline-blue placeholder-gray-400':
                        errors.length === 0,
                    'border-red-300 focus:shadow-outline-red focus:border-red-300 placeholder-red-300':
                        errors.length > 0
                }"
                :name="name"
                :id="name"
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
        type: {
            type: String,
            required: false,
            default: 'text'
        },
        placeholder: {
            type: String,
            default: '',
            required: false
        },
        readonly: {
            type: Boolean,
            required: false,
            default: false
        },
        rows: {
            type: Number,
            required: false,
            default: 3
        }
    }
}
</script>
